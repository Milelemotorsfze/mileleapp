<?php

namespace App\Services;

use App\Rules\KnownLocationName;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Matches typed port / country names against the names already in the system
 * (master_shipping_ports and countries), so the same place is not saved under many spellings.
 *
 * Result statuses:
 *  - empty:  nothing typed
 *  - exact:  typed name is an existing name (ignoring case and extra spaces) -> save `canonical`
 *  - select: typed name is a different spelling of existing name(s) -> user must pick one
 *  - new:    not a spelling of an existing name -> may be saved as typed
 *
 * `suggestions` are the spellings the user must choose from (status "select").
 * `matches` lists every existing name that matches what was typed (search as you type):
 * the suggestions first, then names containing the typed words. The user may pick any of them.
 * For an exact name, `matches` holds the other names containing the typed words ("Dakar" -> "Dakar Port").
 */
class LocationNameMatcher
{
    public const PORT = 'port';
    public const COUNTRY = 'country';

    /** Work order fields checked by this matcher => list they are matched against. */
    public const WORK_ORDER_FIELDS = [
        'port_of_loading' => self::PORT,
        'port_of_discharge' => self::PORT,
        'final_destination' => self::COUNTRY,
    ];

    /**
     * Common short forms that share no letters with the full name. Only used when the full
     * name exists in the list. Keys are lowercase letters/digits only.
     */
    private const SHORT_FORMS = [
        self::COUNTRY => [
            'drc' => 'Democratic Republic of the Congo',
            'drcongo' => 'Democratic Republic of the Congo',
            'uae' => 'United Arab Emirates',
            'ksa' => 'Saudi Arabia',
            'usa' => 'United States of America',
            'uk' => 'United Kingdom',
            'car' => 'Central African Republic',
        ],
        self::PORT => [],
    ];

    /** Words that do not identify a place ("Port Khalid" ~ "Khalid"). */
    private const FILLER_WORDS = ['port', 'the', 'of', 'harbour', 'harbor', 'terminal'];

    /** Upper bound on names returned for one search (keeps the response small for very short input). */
    private const MAX_MATCHES = 100;

    /** Letters needed before searching names (one letter lists names with a word starting with it). */
    private const MIN_SEARCH_LENGTH = 1;

    /** Letters needed before also matching inside words ("abidj" in "Abidjan"; "ab" would match "Casablanca"). */
    private const MIN_CONTAINS_LENGTH = 4;

    private const CACHE_SECONDS = 600;

    /** @var array<string, array<int, array{name: string, lower: string, full: string, core: string, tokens: array, coreTokens: array}>> */
    private array $names = [];

    /**
     * @return array{status: string, canonical: ?string, suggestions: array<int, string>, matches: array<int, string>}
     */
    public function match(string $type, ?string $value): array
    {
        $typed = $this->collapse((string) $value);
        if ($typed === '') {
            return $this->result('empty');
        }

        $entries = $this->names($type);
        $lower = mb_strtolower($typed);
        foreach ($entries as $entry) {
            if ($entry['lower'] === $lower) {
                // Already an existing name: nothing to select, but other matching names are still listed.
                $others = array_values(array_diff($this->search($typed, $entries), [$entry['name']]));

                return $this->result('exact', $entry['name'], [], array_slice($others, 0, self::MAX_MATCHES));
            }
        }

        $suggestions = $this->suggest($type, $typed, $entries);
        $matches = array_slice(
            array_values(array_unique(array_merge($suggestions, $this->search($typed, $entries)))),
            0,
            self::MAX_MATCHES
        );

        return $this->result($suggestions ? 'select' : 'new', null, $suggestions, $matches);
    }

    private function result(string $status, ?string $canonical = null, array $suggestions = [], array $matches = []): array
    {
        return ['status' => $status, 'canonical' => $canonical, 'suggestions' => $suggestions, 'matches' => $matches];
    }

    /** Existing spelling of an exactly matching name (case/spacing only), otherwise null. */
    public function canonical(string $type, ?string $value): ?string
    {
        return $this->match($type, $value)['canonical'];
    }

    /**
     * Validation rules for the work order port / destination fields.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function validationRules(): array
    {
        $rules = [];
        foreach (self::WORK_ORDER_FIELDS as $field => $type) {
            $rules[$field] = ['nullable', 'string', 'max:255', new KnownLocationName($type)];
        }

        return $rules;
    }

    /**
     * Replaces exactly matching names with the system's spelling ("JEBEL ALI " -> "Jebel Ali").
     * Fields that are missing, not strings, or new names are left as they are.
     */
    public function normalizeWorkOrderFields(array $data): array
    {
        foreach (self::WORK_ORDER_FIELDS as $field => $type) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = $this->canonical($type, $data[$field]) ?? $data[$field];
            }
        }

        return $data;
    }

    /**
     * Everything the browser needs to run the same matching instantly while typing
     * (public/js/custom/location-name-matcher.js). Entries are the prepared, de-duplicated
     * names as [name, lower, full, core, tokens], so the browser never re-tokenises them.
     */
    public function clientData(): array
    {
        $lists = [];
        foreach ([self::PORT, self::COUNTRY] as $type) {
            $lists[$type] = array_map(
                fn ($entry) => [$entry['name'], $entry['lower'], $entry['full'], $entry['core'], $entry['tokens']],
                $this->names($type)
            );
        }

        return [
            'config' => [
                'shortForms' => self::SHORT_FORMS,
                'fillerWords' => self::FILLER_WORDS,
                'maxMatches' => self::MAX_MATCHES,
                'minSearchLength' => self::MIN_SEARCH_LENGTH,
                'minContainsLength' => self::MIN_CONTAINS_LENGTH,
            ],
            'lists' => $lists,
        ];
    }

    public function forgetCache(): void
    {
        $this->names = [];
        Cache::forget($this->cacheKey(self::PORT));
        Cache::forget($this->cacheKey(self::COUNTRY));
    }

    /**
     * @return array<int, string>
     */
    private function suggest(string $type, string $typed, array $entries): array
    {
        $typedTokens = $this->tokens($typed);
        $typedFull = implode('', $typedTokens);
        $typedCore = implode('', array_diff($typedTokens, self::FILLER_WORDS));
        $shortFormOf = self::SHORT_FORMS[$type][$typedFull] ?? null;

        $scored = [];
        foreach ($entries as $entry) {
            $score = null;

            if ($typedFull !== '' && $typedFull === $entry['full']) {
                $score = 0; // same letters, different spacing/punctuation: "JEBELALI" -> "Jebel Ali"
            } elseif ($shortFormOf !== null && mb_strtolower($shortFormOf) === $entry['lower']) {
                $score = 0; // known short form: "DRC" -> "Democratic Republic of the Congo"
            } elseif ($typedCore !== '' && $typedCore === $entry['core']) {
                $score = 1; // only filler words differ: "Khalid" -> "Port Khalid"
            } elseif (strlen($typedCore) >= 5 && $entry['core'] !== '') {
                $distance = levenshtein($typedCore, $entry['core']);
                if ($distance <= $this->allowedTypos($typedCore)) {
                    $score = 1 + $distance; // typo: "jabelali" -> "Jebel Ali"
                }
            }

            if ($score === null && strlen($typedCore) >= 4 && strlen($entry['core']) >= 4) {
                $entryCoreTokens = array_values(array_diff($entry['tokens'], self::FILLER_WORDS));
                $typedCoreTokens = array_values(array_diff($typedTokens, self::FILLER_WORDS));
                if ($this->containsAll($entryCoreTokens, $typedCoreTokens)) {
                    $score = 5; // typed name is part of an existing one: "Luanda" -> "Luanda Port"
                } elseif ($this->startsWith($typedCoreTokens, $entryCoreTokens)) {
                    $score = 6; // existing name plus extra words: "TEMA GHANA" -> "Tema" (but not "Hamriya Port Dubai" -> "Dubai")
                }
            }

            if ($score !== null) {
                $scored[$entry['name']] = min($score, $scored[$entry['name']] ?? PHP_INT_MAX);
            }
        }

        uksort($scored, fn ($a, $b) => [$scored[$a], $a] <=> [$scored[$b], $b]);

        return array_keys($scored);
    }

    /**
     * Search as you type: names where every typed word starts a word of the name
     * ("dar es" -> "Dar es Salaam"), or, from 4 letters, whose letters contain the typed letters
     * ("jebelal" -> "Jebel Ali").
     * Names starting with the typed text come first.
     *
     * @return array<int, string>
     */
    private function search(string $typed, array $entries): array
    {
        $typedTokens = array_values(array_diff($this->tokens($typed), self::FILLER_WORDS));
        $squashed = implode('', $typedTokens);
        if (strlen($squashed) < self::MIN_SEARCH_LENGTH) {
            return [];
        }

        $found = [];
        foreach ($entries as $entry) {
            if ($entry['core'] === '') {
                continue;
            }
            $containsLetters = strlen($squashed) >= self::MIN_CONTAINS_LENGTH && str_contains($entry['core'], $squashed);
            if ($containsLetters || $this->everyWordStartsAWord($typedTokens, $entry['coreTokens'])) {
                $found[$entry['name']] = str_starts_with($entry['core'], $squashed) ? 0 : 1;
            }
        }

        uksort($found, fn ($a, $b) => [$found[$a], mb_strtolower($a)] <=> [$found[$b], mb_strtolower($b)]);

        return array_keys($found);
    }

    private function everyWordStartsAWord(array $typedTokens, array $nameTokens): bool
    {
        foreach ($typedTokens as $typedToken) {
            $hit = false;
            foreach ($nameTokens as $nameToken) {
                if (str_starts_with($nameToken, $typedToken)) {
                    $hit = true;
                    break;
                }
            }
            if (!$hit) {
                return false;
            }
        }

        return $typedTokens !== [];
    }

    private function allowedTypos(string $core): int
    {
        return strlen($core) >= 8 ? 2 : 1;
    }

    /** True when $tokens begins with all of $prefix, in order. */
    private function startsWith(array $tokens, array $prefix): bool
    {
        return $prefix !== [] && array_slice($tokens, 0, count($prefix)) === $prefix;
    }

    /** True when every token of $needles is in $haystack. */
    private function containsAll(array $haystack, array $needles): bool
    {
        return $needles !== [] && array_diff($needles, $haystack) === [];
    }

    /**
     * Existing names for the list, one entry per distinct spelling (duplicates in the master list collapse).
     * The prepared entries are cached, so a check does no database work and no re-tokenising.
     */
    private function names(string $type): array
    {
        return $this->names[$type] ??= Cache::remember($this->cacheKey($type), self::CACHE_SECONDS, function () use ($type) {
            $query = $type === self::COUNTRY
                ? DB::table('countries')->whereNull('deleted_at')
                : DB::table('master_shipping_ports');

            return $this->prepareEntries($query->orderBy('id')->pluck('name')->all());
        });
    }

    private function prepareEntries(array $names): array
    {
        $entries = [];
        foreach ($names as $name) {
            $name = $this->collapse((string) $name);
            if ($name === '') {
                continue;
            }
            $tokens = $this->tokens($name);
            $full = implode('', $tokens);
            // Keep one spelling per place, preferring proper case over ALL CAPS ("Dar es Salaam" over "DAR ES SALAAM").
            if (isset($entries[$full]) && !($this->isAllCaps($entries[$full]['name']) && !$this->isAllCaps($name))) {
                continue;
            }
            $entries[$full] = [
                'name' => $name,
                'lower' => mb_strtolower($name),
                'full' => $full,
                'core' => implode('', array_diff($tokens, self::FILLER_WORDS)),
                'tokens' => $tokens,
                'coreTokens' => array_values(array_diff($tokens, self::FILLER_WORDS)),
            ];
        }

        return array_values($entries);
    }

    private function cacheKey(string $type): string
    {
        return 'location_name_matcher_entries_v2_' . $type;
    }

    /** Trim and collapse whitespace (including non-breaking spaces). */
    private function collapse(string $value): string
    {
        return trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $value));
    }

    /**
     * Lowercase alphanumeric words: "Apapa - Nigeria" -> ['apapa', 'nigeria'], "Côte d'Ivoire" -> ['cote', 'd', 'ivoire'].
     * Str::ascii gives the same result on every server (iconv differs between Windows and Linux).
     */
    private function tokens(string $value): array
    {
        $ascii = Str::ascii(mb_strtolower($value));

        return array_values(array_filter(preg_split('/[^a-z0-9]+/', $ascii) ?: [], fn ($t) => $t !== ''));
    }

    private function isAllCaps(string $name): bool
    {
        return mb_strtoupper($name) === $name && mb_strtolower($name) !== $name;
    }
}

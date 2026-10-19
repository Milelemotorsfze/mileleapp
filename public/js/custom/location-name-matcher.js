/**
 * Port / country name matching in the browser, so the work order form can react to every
 * keystroke or paste instantly. It mirrors App\Services\LocationNameMatcher (PHP) exactly and is
 * fed by its clientData() via GET /work-order/location-names. The server stays the authority:
 * the form confirms results with the server and the same rule is applied when saving.
 *
 * Usage: var matcher = new LocationNameMatcher(data); matcher.match('port', 'jabelali');
 * -> { status: 'select', canonical: null, suggestions: ['Jebel Ali'], matches: ['Jebel Ali'] }
 */
(function (root, factory) {
    if (typeof module === 'object' && module.exports) {
        module.exports = factory();
    } else {
        root.LocationNameMatcher = factory();
    }
})(typeof self !== 'undefined' ? self : this, function () {
    'use strict';

    // PHP: preg_replace('/[\s\x{00A0}]+/u', ' ') then trim() -> ASCII whitespace + NBSP only.
    var WHITESPACE = /[ \t\n\x0B\f\r ]+/g;
    var TRIM = /^[ \t\n\r\0\x0B]+|[ \t\n\r\0\x0B]+$/g;
    // Letters that iconv ASCII//TRANSLIT spells out but Unicode decomposition does not.
    var TRANSLIT = { 'ß': 'ss', 'æ': 'ae', 'œ': 'oe', 'ø': 'o', 'đ': 'd', 'ð': 'd', 'ł': 'l', 'þ': 'th', 'ı': 'i' };

    function collapse(value) {
        return String(value === null || value === undefined ? '' : value).replace(WHITESPACE, ' ').replace(TRIM, '');
    }

    /** Lowercase alphanumeric words: "Apapa - Nigeria" -> ['apapa', 'nigeria']. */
    function tokens(value) {
        var ascii = value.toLowerCase().replace(/[ßæœøđðłþı]/g, function (c) { return TRANSLIT[c]; });
        ascii = ascii.normalize('NFD').replace(/[̀-ͯ]/g, '');
        return ascii.split(/[^a-z0-9]+/).filter(function (t) { return t !== ''; });
    }

    function withoutFiller(list, filler) {
        return list.filter(function (t) { return filler.indexOf(t) === -1; });
    }

    function levenshtein(a, b) {
        if (a === b) { return 0; }
        var previous = [], current, i, j;
        for (j = 0; j <= b.length; j++) { previous[j] = j; }
        for (i = 1; i <= a.length; i++) {
            current = [i];
            for (j = 1; j <= b.length; j++) {
                current[j] = Math.min(
                    previous[j] + 1,
                    current[j - 1] + 1,
                    previous[j - 1] + (a.charAt(i - 1) === b.charAt(j - 1) ? 0 : 1)
                );
            }
            previous = current;
        }
        return previous[b.length];
    }

    /** Every needle is in the haystack (and there is at least one needle). */
    function containsAll(haystack, needles) {
        return needles.length > 0 && needles.every(function (n) { return haystack.indexOf(n) !== -1; });
    }

    /** tokens begins with all of prefix, in order. */
    function startsWith(list, prefix) {
        if (prefix.length === 0 || prefix.length > list.length) { return false; }
        for (var i = 0; i < prefix.length; i++) {
            if (list[i] !== prefix[i]) { return false; }
        }
        return true;
    }

    function everyWordStartsAWord(typedTokens, nameTokens) {
        if (typedTokens.length === 0) { return false; }
        return typedTokens.every(function (typed) {
            return nameTokens.some(function (name) { return name.indexOf(typed) === 0; });
        });
    }

    /** PHP <=> on strings: plain code-unit comparison. */
    function compare(a, b) {
        return a < b ? -1 : (a > b ? 1 : 0);
    }

    function unique(list) {
        var seen = {}, out = [];
        list.forEach(function (item) {
            if (!Object.prototype.hasOwnProperty.call(seen, item)) {
                seen[item] = true;
                out.push(item);
            }
        });
        return out;
    }

    function result(status, canonical, suggestions, matches) {
        return { status: status, canonical: canonical || null, suggestions: suggestions || [], matches: matches || [] };
    }

    function LocationNameMatcher(data) {
        var filler = data.config.fillerWords;
        this.config = data.config;
        this.lists = {};
        Object.keys(data.lists).forEach(function (type) {
            this.lists[type] = data.lists[type].map(function (e) {
                return { name: e[0], lower: e[1], full: e[2], core: e[3], tokens: e[4], coreTokens: withoutFiller(e[4], filler) };
            });
        }, this);
    }

    LocationNameMatcher.prototype.match = function (type, value) {
        var typed = collapse(value);
        if (typed === '') {
            return result('empty');
        }
        var entries = this.lists[type] || [];
        var lower = typed.toLowerCase();
        for (var i = 0; i < entries.length; i++) {
            if (entries[i].lower === lower) {
                var exactName = entries[i].name;
                var others = this.search(typed, entries).filter(function (n) { return n !== exactName; });
                return result('exact', exactName, [], others.slice(0, this.config.maxMatches));
            }
        }

        var suggestions = this.suggest(type, typed, entries);
        var matches = unique(suggestions.concat(this.search(typed, entries))).slice(0, this.config.maxMatches);

        return result(suggestions.length ? 'select' : 'new', null, suggestions, matches);
    };

    LocationNameMatcher.prototype.suggest = function (type, typed, entries) {
        var filler = this.config.fillerWords;
        var typedTokens = tokens(typed);
        var typedFull = typedTokens.join('');
        var typedCoreTokens = withoutFiller(typedTokens, filler);
        var typedCore = typedCoreTokens.join('');
        var shortForms = this.config.shortForms[type] || {};
        var shortFormOf = Object.prototype.hasOwnProperty.call(shortForms, typedFull) ? shortForms[typedFull] : null;
        var allowedTypos = typedCore.length >= 8 ? 2 : 1;

        var scored = {}, order = [];
        entries.forEach(function (entry) {
            var score = null;

            if (typedFull !== '' && typedFull === entry.full) {
                score = 0; // same letters, different spacing/punctuation
            } else if (shortFormOf !== null && shortFormOf.toLowerCase() === entry.lower) {
                score = 0; // known short form
            } else if (typedCore !== '' && typedCore === entry.core) {
                score = 1; // only filler words differ
            } else if (typedCore.length >= 5 && entry.core !== '') {
                var distance = levenshtein(typedCore, entry.core);
                if (distance <= allowedTypos) {
                    score = 1 + distance; // typo
                }
            }

            if (score === null && typedCore.length >= 4 && entry.core.length >= 4) {
                if (containsAll(entry.coreTokens, typedCoreTokens)) {
                    score = 5; // typed name is part of an existing one
                } else if (startsWith(typedCoreTokens, entry.coreTokens)) {
                    score = 6; // existing name plus extra words
                }
            }

            if (score !== null) {
                if (!Object.prototype.hasOwnProperty.call(scored, entry.name)) {
                    order.push(entry.name);
                    scored[entry.name] = score;
                } else {
                    scored[entry.name] = Math.min(score, scored[entry.name]);
                }
            }
        });

        return order.sort(function (a, b) {
            return (scored[a] - scored[b]) || compare(a, b);
        });
    };

    LocationNameMatcher.prototype.search = function (typed, entries) {
        var config = this.config;
        var typedTokens = withoutFiller(tokens(typed), config.fillerWords);
        var squashed = typedTokens.join('');
        if (squashed.length < config.minSearchLength) {
            return [];
        }

        var found = {}, order = [];
        entries.forEach(function (entry) {
            if (entry.core === '') {
                return;
            }
            var containsLetters = squashed.length >= config.minContainsLength && entry.core.indexOf(squashed) !== -1;
            if (containsLetters || everyWordStartsAWord(typedTokens, entry.coreTokens)) {
                if (!Object.prototype.hasOwnProperty.call(found, entry.name)) {
                    order.push(entry.name);
                }
                found[entry.name] = entry.core.indexOf(squashed) === 0 ? 0 : 1;
            }
        });

        return order.sort(function (a, b) {
            return (found[a] - found[b]) || compare(a.toLowerCase(), b.toLowerCase());
        });
    };

    LocationNameMatcher.collapse = collapse;
    LocationNameMatcher.tokens = tokens;

    return LocationNameMatcher;
});

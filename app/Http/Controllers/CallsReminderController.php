<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calls;
use App\Models\User;
use App\Mail\LeadsReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CallsReminderController extends Controller
{
    /**
     * Send reminder emails to all sales persons with their assigned leads
     */
    public function sendReminderEmails($leadType = 'new')
    {
        try {
            Log::info("Starting leads reminder email process for {$leadType} leads");
            
            // Determine status filter based on lead type
            $statusFilter = $leadType === 'new' ? ['New'] : ['contacted', 'working'];
            
            // Get all sales persons who have leads with specified status
            $salesPersons = User::whereHas('assignedLeads', function ($query) use ($statusFilter) {
                $query->whereIn('status', $statusFilter);
            })->with(['assignedLeads' => function ($query) use ($statusFilter) {
                $query->whereIn('status', $statusFilter)
                      ->orderBy('created_at', 'asc');
            }])->get();

            $emailsSent = 0;
            $totalLeads = 0;

            foreach ($salesPersons as $salesPerson) {
                $leads = $salesPerson->assignedLeads;
                
                if ($leads->count() > 0) {
                    $totalLeads += $leads->count();
                    
                    // Calculate separate counts for contacted/working leads
                    $contactedCount = 0;
                    $workingCount = 0;
                    if ($leadType === 'contacted_working') {
                        $contactedCount = $leads->where('status', 'contacted')->count();
                        $workingCount = $leads->where('status', 'working')->count();
                    }
                    
                    // Prepare leads data with pending days
                    $leadsData = $leads->map(function ($lead) {
                        $pendingDays = Carbon::parse($lead->created_at)->diffInDays(Carbon::now());
                        return [
                            'id' => $lead->id,
                            'name' => $lead->name,
                            'phone' => $lead->phone,
                            'email' => $lead->email,
                            'location' => $lead->location,
                            'created_at' => $lead->created_at,
                            'pending_days' => $pendingDays,
                            'url' => 'http://mileleapp.test/callsdeatilspage/' . $lead->id // Direct URL for viewing leads
                        ];
                    });

                    // Send email to sales person (using test email for testing)
                    $testEmail = 'basharat.ali@milele.com';
                    
                    try {
                        // Use configured Gmail driver for both local and production
                        
                        Mail::to($testEmail)->send(new LeadsReminderMail($salesPerson, $leadsData, $leadType, $contactedCount, $workingCount));
                        Log::info("✅ Email sent successfully to {$testEmail} for sales person {$salesPerson->name} ({$leadType} leads)");
                        $emailsSent++;
                    } catch (\Exception $e) {
                        Log::error("❌ Email sending failed for {$salesPerson->name}: " . $e->getMessage());
                        Log::error("Email error details: " . $e->getTraceAsString());
                        Log::error("Mail configuration: " . json_encode([
                            'default' => config('mail.default'),
                            'host' => config('mail.mailers.smtp.host'),
                            'port' => config('mail.mailers.smtp.port'),
                            'username' => config('mail.mailers.smtp.username'),
                            'from_address' => config('mail.from.address')
                        ]));
                    }
                }
            }

            Log::info("Leads reminder process completed. Emails sent: {$emailsSent}, Total leads: {$totalLeads}");
            
            return response()->json([
                'success' => true,
                'message' => "Reminder emails sent successfully",
                'emails_sent' => $emailsSent,
                'total_leads' => $totalLeads
            ]);

        } catch (\Exception $e) {
            Log::error('Error in leads reminder process: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending reminder emails: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send reminder email to specific sales person
     */
    public function sendReminderToSalesPerson($salesPersonId, $leadType = 'new')
    {
        try {
            $salesPerson = User::findOrFail($salesPersonId);
            
            // Determine status filter based on lead type
            $statusFilter = $leadType === 'new' ? ['New'] : ['contacted', 'working'];
            
            $leads = Calls::where('sales_person', $salesPersonId)
                           ->whereIn('status', $statusFilter)
                           ->orderBy('created_at', 'asc')
                           ->get();

            if ($leads->count() > 0) {
                // Calculate separate counts for contacted/working leads
                $contactedCount = 0;
                $workingCount = 0;
                if ($leadType === 'contacted_working') {
                    $contactedCount = $leads->where('status', 'contacted')->count();
                    $workingCount = $leads->where('status', 'working')->count();
                }
                
                $leadsData = $leads->map(function ($lead) {
                    $pendingDays = Carbon::parse($lead->created_at)->diffInDays(Carbon::now());
                    return [
                        'id' => $lead->id,
                        'name' => $lead->name,
                        'phone' => $lead->phone,
                        'email' => $lead->email,
                        'location' => $lead->location,
                        'created_at' => $lead->created_at,
                        'pending_days' => $pendingDays,
                        'url' => 'http://mileleapp.test/callsdeatilspage/' . $lead->id // Direct URL for viewing leads
                    ];
                });

                // Send email to sales person (using test email for testing)
                $testEmail = 'basharat.ali@milele.com';
                
                try {
                    // Use configured Gmail driver for both local and production
                    
                    Mail::to($testEmail)->send(new LeadsReminderMail($salesPerson, $leadsData, $leadType, $contactedCount, $workingCount));
                    Log::info("✅ Email sent successfully to {$testEmail} for sales person {$salesPerson->name} ({$leadType} leads)");
                } catch (\Exception $e) {
                    Log::error("❌ Email sending failed for {$salesPerson->name}: " . $e->getMessage());
                    Log::error("Email error details: " . $e->getTraceAsString());
                    Log::error("Mail configuration: " . json_encode([
                        'default' => config('mail.default'),
                        'host' => config('mail.mailers.smtp.host'),
                        'port' => config('mail.mailers.smtp.port'),
                        'username' => config('mail.mailers.smtp.username'),
                        'from_address' => config('mail.from.address')
                    ]));
                }
                
                return response()->json([
                    'success' => true,
                    'message' => "Reminder email sent to {$salesPerson->name}",
                    'emails_sent' => 1,
                    'leads_count' => $leads->count()
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => "No new leads found for {$salesPerson->name}",
                    'emails_sent' => 0,
                    'leads_count' => 0
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error sending reminder to sales person: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error sending reminder email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reminder statistics
     */
    public function getReminderStats()
    {
        try {
            $stats = [
                'total_sales_persons' => User::whereHas('assignedLeads', function ($query) {
                    $query->where('status', 'New');
                })->count(),
                'total_new_leads' => Calls::where('status', 'New')->count(),
                'leads_by_sales_person' => User::whereHas('assignedLeads', function ($query) {
                    $query->where('status', 'New');
                })->withCount(['assignedLeads' => function ($query) {
                    $query->where('status', 'New');
                }])->get(['id', 'name', 'email', 'assigned_leads_count'])
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting reminder stats: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error getting statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send daily report to management with structured table data
     */
    public function sendDailyReport()
    {
        try {
            Log::info('Starting daily leads report process');
            
            // Increase memory limit for large datasets
            ini_set('memory_limit', '512M');
            
            // Get sales persons with pagination to avoid memory issues
            $salesPersons = User::whereHas('assignedLeads', function ($query) {
                $query->whereIn('status', ['New', 'contacted', 'working']);
            })->with(['assignedLeads' => function ($query) {
                $query->whereIn('status', ['New', 'contacted', 'working'])
                      ->orderBy('created_at', 'asc')
                      ->limit(100); // Limit to 100 leads per salesperson to prevent memory issues
            }])->get();

            $reportData = [];
            $totalLeads = 0;
            $salesPersonSummary = [];

            foreach ($salesPersons as $salesPerson) {
                $leads = $salesPerson->assignedLeads;
                
                if ($leads->count() > 0) {
                    $totalLeads += $leads->count();
                    
                    // Get total count for this salesperson (not limited)
                    $totalCountForSalesPerson = \App\Models\Calls::where('sales_person', $salesPerson->id)
                        ->whereIn('status', ['New', 'contacted', 'working'])
                        ->count();
                    
                    $salesPersonSummary[] = [
                        'name' => $salesPerson->name,
                        'total_leads' => $totalCountForSalesPerson,
                        'displayed_leads' => $leads->count()
                    ];
                    
                    $leadsData = $leads->map(function ($lead) {
                        $pendingDays = Carbon::parse($lead->created_at)->diffInDays(Carbon::now());
                        return [
                            'id' => $lead->id,
                            'name' => $lead->name,
                            'status' => $lead->status,
                            'pending_days' => $pendingDays,
                            'url' => 'http://mileleapp.test/callsdeatilspage/' . $lead->id
                        ];
                    });

                    $reportData[] = [
                        'salesperson' => $salesPerson,
                        'leads' => $leadsData,
                        'count' => $leads->count()
                    ];
                }
            }

            // Send email to management
            $CSOemail = 'basharat.ali@milele.com';
            
            try {
                Mail::to($CSOemail)->send(new \App\Mail\DailyLeadsReportMail($reportData, $totalLeads, $salesPersonSummary));
                Log::info("✅ Daily report sent successfully to {$CSOemail}");
                
                return response()->json([
                    'success' => true,
                    'message' => "Daily report sent successfully",
                    'emails_sent' => 1,
                    'total_leads' => $totalLeads,
                    'sales_persons' => count($reportData)
                ]);
            } catch (\Exception $e) {
                Log::error("❌ Daily report sending failed: " . $e->getMessage());
                Log::error("Email error details: " . $e->getTraceAsString());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error sending daily report: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Error in daily report process: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error generating daily report: ' . $e->getMessage()
            ], 500);
        }
    }
}

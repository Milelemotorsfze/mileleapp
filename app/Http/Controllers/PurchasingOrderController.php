<?php

namespace App\Http\Controllers;

use App\Models\ApprovedLetterOfIndentItem;
use App\Models\LetterOfIndentItem;
use App\Models\LetterOfIndent;
use App\Models\LOIItemPurchaseOrder;
use App\Models\PfiItemPurchaseOrder;
use App\Models\MasterModel;
use Illuminate\Support\Facades\Log;
use App\Models\PFI;
use App\Models\PfiItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceChangeNotification;
use App\Mail\TransferCopyEmail;
use App\Mail\SwiftCopyEmail;
use App\Mail\DPEmailNotification;
use App\Mail\DPrealeasedEmailNotification;
use App\Mail\EmailNotificationInitiate;
use App\Models\PurchasingOrderEventsLog;
use App\Models\PurchasingOrder;
use App\Models\MasterShippingPorts;
use App\Models\PurchasingOrderItems;
use App\Models\PurchasingOrderSwiftCopies;
use App\Models\SupplierInventory;
use Illuminate\Http\Request;
use App\Models\Varaint;
use App\Models\SupplierAccount;
use App\Models\ColorCode;
use App\Models\Brand;
use App\Models\Country;
use App\Models\MasterModelLines;
use App\Models\Supplier;
use App\Models\Vehicles;
use App\Models\VehiclePurchasingCost;
use App\Models\Movement;
use App\Models\PaymentTerms;
use App\Models\PaymentLog;
use App\Models\User;
use App\Models\Vehicleslog;
use Carbon\Carbon;
use App\Models\ModelHasRoles;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Carbon\CarbonTimeZone;
use App\Models\UserActivities;
use App\Models\BankAccounts;
use App\Models\Purchasinglog;
use App\Models\PurchasedOrderPaidAmounts;
use App\Models\VendorPaymentAdjustments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplierAccountTransaction;
use App\Models\PurchasedOrderPriceChanges;
use App\Models\PurchasedOrderMessages;
use App\Models\PurchasedOrderReplies;
use App\Models\Purchasedorderoldplfiles;
use App\Models\VehiclesSupplierAccountTransaction;
use App\Models\DepartmentNotifications;
use App\Mail\EmailNotificationrequest;
use App\Mail\VINEmailNotification;
use App\Mail\PurchaseOrderUpdated;
use App\Mail\ChangeVariantNotification;
use App\Models\Dnaccess;
use App\Models\VehicleDn;
use Illuminate\Support\Facades\Crypt;
use setasign\Fpdi\PdfReader\Page;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Str;
use File;
use Exception;


class PurchasingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::whereIn('status', ['Initiated Payment', 'Approved', 'Suggested Payment'])->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $useractivities =  New UserActivities();
        $useractivities->activity = "Purchasing Order Index Page View";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $userId = auth()->user()->id;
        // $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-planning-po-list');
        if ($hasPermission){
        if(Auth::user()->hasPermissionForSelectedRole('demand-planning-po-list')){
            $demandPlanningPoIds = PfiItemPurchaseOrder::groupBy('purchase_order_id')->pluck('purchase_order_id')->toArray();
            // add migrated user Ids
            $Ids = ['16'];
            $Ids[] = $userId;
            $data = PurchasingOrder::with('purchasing_order_items')
            ->whereIn('id',$demandPlanningPoIds)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('vehicles')
                      ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                      ->where('vehicles.gdn_id', '=', null);
            })
            ->whereHas('vehicles', function ($query) {
                $query->whereNotNull('id');
            })
            ->whereNotNull('totalcost') // Added condition
            ->orderBy('po_date', 'desc')
            ->get();
        }else{
            $data = PurchasingOrder::with('purchasing_order_items')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('vehicles')
              ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
              ->where('vehicles.gdn_id', '=', null);
    })
    ->whereHas('vehicles', function ($query) {
        $query->whereNotNull('id');
    })
    ->whereNotNull('totalcost') // Added condition
    ->orderBy('po_date', 'desc')
    ->get();
        }
    }
    else
    {
        if(Auth::user()->hasPermissionForSelectedRole('demand-planning-po-list')){
            $demandPlanningPoIds = PfiItemPurchaseOrder::groupBy('purchase_order_id')->pluck('purchase_order_id');
//            return $demandPlanningPoIds;
            // add migrated user Ids
            $Ids = ['16'];
            $Ids[] = $userId;
            $data = PurchasingOrder::with('purchasing_order_items')
            ->whereIn('id',$demandPlanningPoIds)
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('vehicles')
                      ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                      ->where('vehicles.gdn_id', '=', null);
            })
            ->whereHas('vehicles', function ($query) {
                $query->whereNotNull('id');
            })
            ->whereNotNull('totalcost') // Added condition
            ->orderBy('po_date', 'desc')
            ->get();
        }else{
            $data = PurchasingOrder::with('purchasing_order_items')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('vehicles')
                      ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                      ->where('vehicles.gdn_id', '=', null);
            })
            ->whereHas('vehicles', function ($query) {
                $query->whereNotNull('id');
            })
            ->whereNotNull('totalcost') // Added condition
            ->orderBy('po_date', 'desc')
            ->get();
        }
    }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filter($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $data = PurchasingOrder::with('purchasing_order_items')->where('status', $status)->get();
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filtercancel($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $data = PurchasingOrder::with('purchasing_order_items')->where('status', $status)->get();
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterapprovedonly($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
            
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                    ->where('vehicles.status', 'Approved');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('supplier_account_transaction')
                    ->whereColumn('purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id');
            })
            ->groupBy('purchasing_order.id')
            ->get();                
        }
        else{
        $data = PurchasingOrder::with('purchasing_order_items')
        ->where('purchasing_order.status', 'Approved')
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('vehicles')
                ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
                ->where('vehicles.status', 'Approved');
        })
        ->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('supplier_account_transaction')
                ->whereColumn('purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id');
        })
        ->groupBy('purchasing_order.id')
        ->get();        
        }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterapproved($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where(function ($query) {
                $query->where('purchasing_order.status', 'Approved')
                      ->orWhereNot('purchasing_order.status', 'Cancelled');
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('vehicles.purchasing_order_id', 'purchasing_order.id')
                    ->where('vehicles.status', 'Approved')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('vehicle_purchasing_cost')
                            ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                            ->where(function ($query) {
                                $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                                      ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                            });
                    });
            })
            ->groupBy('purchasing_order.id')
            ->get();        
    }
        else{
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where(function ($query) {
                $query->where('purchasing_order.status', 'Approved')
                      ->orWhereNot('purchasing_order.status', 'Cancelled');
            })
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicles')
                    ->whereColumn('vehicles.purchasing_order_id', 'purchasing_order.id')
                    ->where('vehicles.status', 'Approved')
                    ->whereExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('vehicle_purchasing_cost')
                            ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                            ->where(function ($query) {
                                $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                                      ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                            });
                    });
            })
            ->groupBy('purchasing_order.id')
            ->get(); 
        }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterincomings($status)
{
    $bankaccounts = BankAccounts::get();
    $exchangeRates = [
        'USD' => 3.67,
        'EUR' => 4.03,
        'JPY' => 0.023,
        'CAD' => 2.89,
        'AED' => 1,
        'PHP' => 0.063,
        'SAR' => 0.98,
    ];
    $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
        return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
    }, 0);
    $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
    $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
        $purchasingOrder = $payment->purchasingOrder;
        if ($purchasingOrder) {
            $currency = $purchasingOrder->currency;
            $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
            return $carry + $amountInAED;
        }
        return $carry;
    }, 0);
    $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
    $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
    $availableFundsUSD = $availableFunds / 3.67;
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', 'vehicles.purchasing_order_id')
            ->where('status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicle_purchasing_cost')
                    ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                    ->whereColumn('vehicle_purchasing_cost.unit_price', 'vehicle_purchasing_cost.total_paid_amount');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', 'Approved')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', 'vehicles.purchasing_order_id')
            ->where('status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicle_purchasing_cost')
                    ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                    ->whereColumn('vehicle_purchasing_cost.unit_price', 'vehicle_purchasing_cost.total_paid_amount');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
}
    public function filterpayment($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Initiate Payment Request')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')->where('purchasing_order.created_by', $userId)
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Initiate Payment Request')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterpaymentrejectioned($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
            $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Rejected')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')->where('purchasing_order.created_by', $userId)
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Rejected')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterpaymentrel($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
           ->where('supplier_account_transaction.transaction_type', 'Pre-Debit')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    }
    else
    {
        $data = PurchasingOrder::with('purchasing_order_items')
        // ->where(function ($query) use ($userId) {
        //     $query->where('purchasing_order.created_by', $userId)
        //         ->orWhere('purchasing_order.created_by', 16);
        // })
        ->where('purchasing_order.status', $status)
        ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
       ->where('supplier_account_transaction.transaction_type', 'Pre-Debit')
        ->select('purchasing_order.*')
        ->groupBy('purchasing_order.id')
        ->get();
    }
    return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterintentreq($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where('vehicles.status', 'Request for Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
    }
    else
    {
        $data = PurchasingOrder::with('purchasing_order_items')
        // ->where(function ($query) use ($userId) {
        //     $query->where('purchasing_order.created_by', $userId)
        //         ->orWhere('purchasing_order.created_by', 16);
        // })
        ->where('purchasing_order.status', $status)
        ->join('vehicles', 'purchasing_order.id', '=', 'vehicles.purchasing_order_id')
        ->where('vehicles.status', 'Request for Payment')
        ->select('purchasing_order.*')
        ->groupBy('purchasing_order.id')
        ->get();
    }
    return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterpendingrelease($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Request For Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')
            // ->where(function ($query) use ($userId) {
            //     $query->where('purchasing_order.created_by', $userId)
            //         ->orWhere('purchasing_order.created_by', 16);
            // })
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Request For Payment')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterpendingdebits($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
        $userId = auth()->user()->id;
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
        if ($hasPermission){
        $data = PurchasingOrder::with('purchasing_order_items')
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Released')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        else
        {
            $data = PurchasingOrder::with('purchasing_order_items')
            // ->where('created_by', $userId)->orWhere('created_by', 16)
            ->where('purchasing_order.status', $status)
            ->join('supplier_account_transaction', 'purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
            ->where('supplier_account_transaction.transaction_type', 'Released')
            ->select('purchasing_order.*')
            ->groupBy('purchasing_order.id')
            ->get();
        }
        return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
    }
    public function filterpendingfellow($status)
    {
        $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98,
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
    $userId = auth()->user()->id;
    $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
    if ($hasPermission){
    $data = PurchasingOrder::with('purchasing_order_items')
        ->where('status', $status)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('supplier_account_transaction')
                ->whereColumn('purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
                ->where(function ($query) {
                    $query->Where('transaction_type', 'Debit')
                    ->whereNull('vendor_payment_status');
                });
        })
        ->groupBy('purchasing_order.id')
        ->get();
    }
    else
    {
        $data = PurchasingOrder::with('purchasing_order_items')
        // ->where('created_by', $userId)->orWhere('created_by', 16)
        ->where('status', $status)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('supplier_account_transaction')
                ->whereColumn('purchasing_order.id', '=', 'supplier_account_transaction.purchasing_order_id')
                ->where(function ($query) {
                    $query->Where('transaction_type', 'Debit')
                    ->whereNull('vendor_payment_status');
                });
        })
        ->groupBy('purchasing_order.id')
        ->get();
    }
    return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
}
public function filterconfirmation($status)
{
    $bankaccounts = BankAccounts::get();
    $exchangeRates = [
        'USD' => 3.67,
        'EUR' => 4.03,
        'JPY' => 0.023,
        'CAD' => 2.89,
        'AED' => 1,
        'PHP' => 0.063,
        'SAR' => 0.98,
    ];
    $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
        return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
    }, 0);
    $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
    $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
        $purchasingOrder = $payment->purchasingOrder;
        if ($purchasingOrder) {
            $currency = $purchasingOrder->currency;
            $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
            return $carry + $amountInAED;
        }
        return $carry;
    }, 0);
    $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
    $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
    $availableFundsUSD = $availableFunds / 3.67;
$userId = auth()->user()->id;
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
if ($hasPermission){
$data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->Where('payment_status', 'Vendor confirmed');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    // ->where('created_by', $userId)->orWhere('created_by', 16)
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
            ->where(function ($query) {
                $query->Where('payment_status', 'Vendor confirmed');
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
}
public function paymentinitiation($status)
{
    $bankaccounts = BankAccounts::get();
    $exchangeRates = [
        'USD' => 3.67,
        'EUR' => 4.03,
        'JPY' => 0.023,
        'CAD' => 2.89,
        'AED' => 1,
        'PHP' => 0.063,
        'SAR' => 0.98,
    ];
    $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
        return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
    }, 0);
    $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
    $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
        $purchasingOrder = $payment->purchasingOrder;
        if ($purchasingOrder) {
            $currency = $purchasingOrder->currency;
            $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
            return $carry + $amountInAED;
        }
        return $carry;
    }, 0);
    $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
    $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
    $availableFundsUSD = $availableFunds / 3.67;
$userId = auth()->user()->id;
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
if ($hasPermission){
    $data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('vehicles.purchasing_order_id', 'purchasing_order.id')
            ->where('vehicles.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicle_purchasing_cost')
                    ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                    ->where(function ($query) {
                        $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                              ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                    });
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    ->where('status', $status)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
            ->from('vehicles')
            ->whereColumn('vehicles.purchasing_order_id', 'purchasing_order.id')
            ->where('vehicles.status', 'Approved')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('vehicle_purchasing_cost')
                    ->whereColumn('vehicle_purchasing_cost.vehicles_id', 'vehicles.id')
                    ->where(function ($query) {
                        $query->whereColumn('vehicle_purchasing_cost.unit_price', '!=', 'vehicle_purchasing_cost.total_paid_amount')
                              ->orWhereNull('vehicle_purchasing_cost.total_paid_amount');
                    });
            });
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $countries = Country::get();
    $ports = MasterShippingPorts::with('country')->get();
    $useractivities =  New UserActivities();
        $useractivities->activity = "Creating Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $vendors = Supplier::whereHas('vendorCategories', function ($query) {
        $query->where('category', 'Vehicles');
    })->get();
    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $payments = PaymentTerms::get();
    return view('warehouse.create', compact('variants', 'vendors', 'payments','countries','ports'));
}
public function getBrandsAndModelLines(Request $request)
{
    $brands = Brand::all(); // Replace with your actual query to get brands
    $modelLines = MasterModelLines::all(); // Replace with your actual query to get model lines
    return response()->json([
        'brands' => $brands,
        'modelLines' => $modelLines,
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'payment_term_id' => 'required',
            'po_type' => 'required',
            'vendors_id' => 'required'
        ]);

        DB::beginTransaction();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Store the Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $poDate = $request->input('po_date');
        $po_type = $request->input('po_type');
        $vendors_id = $request->input('vendors_id');
        $purchasingOrder = new PurchasingOrder();
        $purchasingOrder->po_date = $poDate; 
        $purchasingOrder->vendors_id = $vendors_id;
        $purchasingOrder->po_type = $po_type;
        $purchasingOrder->payment_term_id = $request->input('payment_term_id');
        $purchasingOrder->currency = $request->input('currency');
        $purchasingOrder->shippingmethod = $request->input('shippingmethod');
        if($request->po_from != 'DEMAND_PLANNING') {
            $purchasingOrder->shippingcost = $request->input('shippingcost');
        }
        $purchasingOrder->totalcost = $request->input('totalcost');
        $purchasingOrder->pol = $request->input('pol');
        $purchasingOrder->pod = $request->input('pod');
        $purchasingOrder->fd = $request->input('fd');
        $purchasingOrder->status = "Pending Approval";
        $purchasingOrder->created_by = auth()->user()->id;
        $purchasingOrder->is_demand_planning_po = $request->is_demand_planning_po ? true : false;
        $purchasingOrder->payment_initiated_status = PurchasingOrder::PAYMENT_STATUS_PENDING; 
        $purchasingOrder->payment_status = PurchasingOrder::PAYMENT_STATUS_UNPAID; 
        if ($request->hasFile('uploadPL')) {
            // Get file with extension
            $fileNameWithExt = $request->file('uploadPL')->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get just extension
            $extension = $request->file('uploadPL')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Move file to public/storage/PL_Documents
            $path = $request->file('uploadPL')->move(public_path('storage/PL_Documents'), $fileNameToStore);
            // Store the path in the database
            $purchasingOrder->pl_file_path = 'storage/PL_Documents/' . $fileNameToStore;
        }
        $purchasingOrder->pl_number = $request->input('pl_number');
        $purchasingOrder->save();
        $purchasingOrderId = $purchasingOrder->id;
        $variantNames = $request->input('variant_id');
        if($variantNames != null)
        {
            $variantIds = Varaint::whereIn('name', $variantNames)->pluck('id')->all();
            $variantsQuantity = array_count_values($variantNames);
            foreach ($variantIds as $variantId) {
                $variant = Varaint::find($variantId);
                $purchasingOrderItem = new PurchasingOrderItems();
                $purchasingOrderItem->variant_id = $variantId;
                $purchasingOrderItem->purchasing_order_id = $purchasingOrderId;
                $purchasingOrderItem->qty = $variantsQuantity[$variant->name];
                $purchasingOrderItem->save();
            }
            $vins = $request->input('vin');
            $ex_colours = $request->input('ex_colour');
            $int_colours = $request->input('int_colour');
            $estimated_arrival = $request->input('estimated_arrival');
            $engine_number = $request->input('engine_number');
            $territory = $request->input('territory');
            $unit_prices = $request->input('unit_prices');
            $count = count($variantNames);
            foreach ($variantNames as $key => $variantName) {
                if ($variantName === null && $key === $count - 1) {
                continue;
                }
                $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
                $vin = $vins[$key];
                $ex_colour = $ex_colours[$key];
                $unit_price = $unit_prices[$key];
                $int_colour = $int_colours[$key];
                $estimation_arrival = $estimated_arrival[$key];
                $engine = $engine_number[$key];
                $vehicle = new Vehicles();
                $vehicle->varaints_id = $variantId;
                $vehicle->vin = $vin;
                $vehicle->ex_colour = $ex_colour;
    //            $vehicle->purchasing_price = $ex_colour;
                $vehicle->int_colour = $int_colour;
                $vehicle->estimation_date = $estimation_arrival;
                $vehicle->engine = $engine;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $vehicle->territory =  $territory;
                    if($request->input('master_model_id')) {
                        $masterModelId = $request->input('master_model_id');
                        $vehicle->model_id = $masterModelId[$key];
                    }
                }else{
                    $territorys = $territory[$key];
                    $vehicle->territory = $territorys;
                }
                $vehicle->purchasing_order_id = $purchasingOrderId;
                $vehicle->status = "Not Approved";
                // payment status need to update
               
                $vehicle->save();
                $vehiclecost = New VehiclePurchasingCost();
                $vehiclecost->currency = $request->input('currency');
                $vehiclecost->unit_price = round($unit_price, 2);
                $vehiclecost->vehicles_id = $vehicle->id;
                $vehiclecost->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $purchasinglog = new Purchasinglog();
                $purchasinglog->time = now()->toTimeString();
                $purchasinglog->date = now()->toDateString();
                $purchasinglog->status = 'PO Created';
                $purchasinglog->purchasing_order_id = $purchasingOrderId;
                $purchasinglog->variant = $variantId;
                $purchasinglog->estimation_date = $estimation_arrival;
                $purchasinglog->engine_number = $engine;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $purchasinglog->territory = $territory;
                }else{
                    $purchasinglog->territory = $territorys;
                }
                $purchasinglog->ex_colour = $ex_colour;
                $purchasinglog->int_colour = $int_colour;
                $purchasinglog->created_by = auth()->user()->id;
                $purchasinglog->role = Auth::user()->selectedRole;
                $purchasinglog->save();
            }
                
                if($request->po_from == 'DEMAND_PLANNING') {
                    $pfiId = Crypt::decrypt($request->pfi_id);
                    $pfi = PFI::findOrFail($pfiId);
                    $purchasingOrder->pl_number = $pfi->pfi_reference_number ?? ''; 
                    $purchasingOrder->save();
                    foreach($request->pfi_items as $key => $pfiItem) {
                        if($request->item_quantity_selected[$key] > 0) {
                            $PfiItemPurchaseOrder = new PfiItemPurchaseOrder();
                            $PfiItemPurchaseOrder->pfi_id = $pfiId;
                            $PfiItemPurchaseOrder->pfi_item_id = $pfiItem;
                            $PfiItemPurchaseOrder->purchase_order_id = $purchasingOrderId;
                            $PfiItemPurchaseOrder->master_model_id = $request->selected_model_ids[$key];
                            $PfiItemPurchaseOrder->quantity = $request->item_quantity_selected[$key] ?? '';
                            $PfiItemPurchaseOrder->save();
                        }
                    }
                    // if toyota pfi -> map with inventory
                    // $parentPfiItemLatest = PfiItem::where('pfi_id', $pfiId)
                    //                 ->where('is_parent', true)
                    //                 ->first();
                    // $brand = $parentPfiItemLatest->masterModel->modelLine->brand->brand_name ?? '';
                    //     if(strcasecmp($brand, 'TOYOTA') == 0 && $request->can_inventory_allocate == 1) {
                    //         $masterModels = $request->master_model_id;
                    //         $childPfiItemLatest =  PfiItem::where('pfi_id', $pfiId)
                    //                                 ->where('is_parent', false)
                    //                                 ->first();
                    //         $dealer =  $childPfiItemLatest->letterOfIndentItem->LOI->dealers ?? '';
                    //         $alreadyAddedIds = [];
                    //         foreach($masterModels as $key => $masterModel)
                    //         {
                    //             // map to inventory
                    //             $masterModel = MasterModel::find($masterModel);
                    //             $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    //                                 ->where('sfx', $masterModel->sfx)->pluck('id');

                    //             $inventoryItem = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                    //                 ->whereNull('purchase_order_id')
                    //                 ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                    //                 ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                    //                 ->where('supplier_id', $vendors_id)
                    //                 ->whereNotIn('id', $alreadyAddedIds)
                    //                 ->where('whole_sales', $dealer);
                                   
                    //              // if exterior colour is coming check same colour is existing with inventory
                    //             if($ex_colours[$key] && $int_colours[$key]) {
                    //                $inventoryItem->where('exterior_color_code_id', $ex_colours[$key])
                    //                                 ->where('interior_color_code_id', $int_colours[$key]);
                    //             }
                               
                    //             if($inventoryItem->count() > 0) {
                    //                 $inventory = $inventoryItem->first();

                    //             }else{
                    //                 $inventory = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                    //                 ->whereNull('purchase_order_id')
                    //                 ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                    //                 ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                    //                 ->where('supplier_id', $vendors_id)
                    //                 ->whereNotIn('id', $alreadyAddedIds)
                    //                 ->where('whole_sales', $dealer)
                    //                 ->first();
                    //             }
                    //             if($inventory) {
                    //                 $inventory->pfi_id = $pfiId;
                    //                 $pfiItemRow = PfiItem::where('pfi_id', $pfiId)
                    //                                         ->where('parent_pfi_item_id', $request->pfi_item_Ids[$key])
                    //                                         ->first();
                    //                 if($pfiItemRow) {
                    //                     $inventory->letter_of_indent_item_id = $pfiItemRow->loi_item_id ?? '';
                    //                 }
                    //                 $inventory->purchase_order_id = $purchasingOrder->id;
                    //                 $inventory->save();

                    //                 $vehicle = Vehicles::where('model_id', $masterModel->id)->where('purchasing_order_id', $purchasingOrderId)
                    //                                         // ->when(!empty($vins[$key]), function ($query) use ($vins, $key) {
                    //                                         //     return $query->where('vin', $vins[$key]);
                    //                                         // })
                    //                                         ->whereNull('supplier_inventory_id')
                    //                                         ->first();

                    //                 $vehicle->supplier_inventory_id = $inventory->id;
                    //                 $vehicle->save();

                    //                 (new SupplierInventoryController)->inventoryLog('Inventory item allocated for Purchase Order ('. $purchasingOrder->po_number.')', $inventory->id);
                    //                 $alreadyAddedIds[] = $inventory->id;
                    //             }
                    //         }
                    //     }
                }
        }
       
        $purchasingordereventsLog = New PurchasingOrderEventsLog();
        $purchasingordereventsLog->event_type = "PO Creation";
        $purchasingordereventsLog->created_by = auth()->user()->id;
        $purchasingordereventsLog->purchasing_order_id = $purchasingOrderId;
        $purchasingordereventsLog->save();
        $supplier_account_id = $request->input('vendors_id');
        $purchasing_order_id = $purchasingOrder->id;
        $updateponum = PurchasingOrder::find($purchasingOrderId);
        $po_number = $request->input('po_number');
        $updateponum->po_number = 'PO-' . $po_number;
        $updateponum->save();
        $supplier_exists = SupplierAccount::where('suppliers_id', $vendors_id)->exists();
        if (!$supplier_exists) {
        $supplier_created = New SupplierAccount();
        $supplier_created->opening_balance = 0;
        $supplier_created->current_balance = 0;
        $supplier_created->currency = $request->currency;
        $supplier_created->suppliers_id = $vendors_id;
        $supplier_created->save();
        }
        DB::commit();
    return redirect()->route('purchasing-order.index')->with('success', 'PO Created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $user = Auth::user();
    if (!$user->hasPermissionForSelectedRole('view-purchased-order-single-page')) {
        return redirect()->route('not_access_page');
    }

        $countries = Country::get();
        $ports = MasterShippingPorts::with('country')->get();
        $useractivities =  New UserActivities();
        $useractivities->activity = "Show The Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();

        $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
        $vendorPaymentAdjustments = VendorPaymentAdjustments::where('purchasing_order_id', $id)
    ->where(function ($query) {
        $query->where('status', '!=', 'Paid')
              ->where('status', '!=', 'pending')
              ->where('status', '!=', 'Rejected')
              ->where('status', '!=', 'Request For Payment')
              ->Where('status', '!=', 'Approved');
    })
    ->select('type', DB::raw('SUM(totalamount) as total_amount'), 'amount', DB::raw('SUM(amount) as total_adjusted_amount'))
    ->groupBy('type')
    ->get();
        $totalSum = $vendorPaymentAdjustments->sum('total_amount');
    $alreadypaidamount = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Paid')->sum('amount');
    $totalSurcharges = round((float) PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
    ->where('change_type', 'Surcharge')
    ->sum('price_change'), 2);
    $totalDiscounts = round((float) PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
    ->where('change_type', 'discount')
    ->sum('price_change'), 2);
    $intialamount = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Request For Payment')->sum('amount');
    $purchasingOrder = PurchasingOrder::with(['polPort', 'podPort', 'fdCountry'])->findOrFail($id);
    $paymentterms = PaymentTerms::findorfail($purchasingOrder->payment_term_id);
    $payments = PaymentTerms::get();
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $vehiclesdel = Vehicles::onlyTrashed()->where('purchasing_order_id', $id)->get();
    $vendorsname = Supplier::where('id', $purchasingOrder->vendors_id)->value('supplier');
    $vehicleslog = Vehicleslog::whereNull('category')->whereIn('vehicles_id', $vehicles->pluck('id'))->get();
    $purchasinglog = Purchasinglog::where('purchasing_order_id', $id)->get();
    $vendorstatus = SupplierAccount::where('suppliers_id', $purchasingOrder->vendors_id)
                               ->select('current_balance', 'currency')
                               ->first();
                               if ($vendorstatus) {
                                $vendorBalance = number_format((float) $vendorstatus->current_balance, 2, '.', ',');
                                $vendorCurrency = $vendorstatus->currency;
                                $vendorDisplay = $vendorBalance . ' - ' . $vendorCurrency;
                            } else {
                                $vendorDisplay = 'Account Not Existing';
                            }
        $previousId = PurchasingOrder::where('id', '<', $id)->max('id');
        $nextId = PurchasingOrder::where('id', '>', $id)->min('id');

        $vendors = Supplier::whereHas('vendorCategories', function ($query) {
            $query->where('category', 'Vehicles');
        })->get();
       
        // dp vehicle variants
        foreach($vehicles as $vehicle)
         {
            if($vehicle->model_id) {
                $masterModel = MasterModel::findOrFail($vehicle->model_id);
                $vehicle->variants = Varaint::select('id','name')
                ->whereHas('masterModel', function($query)use($masterModel){
                    $query->where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx);
                })
                ->get();
            }else{
                $vehicle->variants = [];
            }
        }
        $pfiVehicleVariants = [];
        $showCancelButton = 1; 
        $showAddMorePOSection = 1;
        if($purchasingOrder->is_demand_planning_purchase_order) {
            
            foreach($vehicles as $vehicle) {
                $vehicleMasterModel = MasterModel::findOrFail($vehicle->model_id);
                $vehicle->vehicleModels = MasterModel::where('model', $vehicleMasterModel->model)
                                                ->where('sfx', $vehicleMasterModel->sfx)
                                                ->get();
            }
            
            $pfi = PFI::findOrFail($purchasingOrder->PFIPurchasingOrder->pfi->id);
            // check for add more vehicle is possible or not
            $PoUtilizedQty = PfiItemPurchaseOrder::where('pfi_id', $pfi->id)
                                    ->sum('quantity');
            if($PoUtilizedQty) {
                $pfiQty =  PfiItem::select('is_parent','pfi_id','pfi_quantity')
                            ->where('is_parent', true)
                            ->where('pfi_id', $pfi->id)
                            ->sum('pfi_quantity');
                if($pfiQty <= $PoUtilizedQty) {
                    $showAddMorePOSection = 0;
                }
            }   

            $pfiItemLatest = PfiItem::where('pfi_id', $pfi->id)
                    ->where('is_parent', false)
                    ->first();
            if($pfiItemLatest) {
                 // only toyota PFI have child , so if child exist it will be toyota PO
                //   => hide cancel or reject button, only vehicle count is edit or add by pfi is possible
                $showCancelButton = 0;
            }
            $dealer =  $pfiItemLatest->letterOfIndentItem->LOI->dealers ?? '';
            $pfiVehicleVariants = PfiItem::where('pfi_id', $pfi->id)
                                    ->where('is_parent', true)
                                    ->get();

            foreach ($pfiVehicleVariants as $pfiVehicleVariant) {

                $alreadyAddedQuantity =  PfiItemPurchaseOrder::where('pfi_item_id', $pfiVehicleVariant->id)
                                                    ->sum('quantity');
                $pfiVehicleVariant->remaining_quantity = 0;
                $remainingQuantity = $pfiVehicleVariant->pfi_quantity - $alreadyAddedQuantity;
                if($remainingQuantity > 0) {
                    $pfiVehicleVariant->remaining_quantity = $remainingQuantity;
                }

                $masterModel = MasterModel::find($pfiVehicleVariant->masterModel->id);
                $pfiVehicleVariant->masterModels = MasterModel::select('id','model','sfx')->where('model', $masterModel->model)
                                                ->where('sfx', $masterModel->sfx)
                                                ->get();

                // $possibleModelIds = MasterModel::where('model', $masterModel->model)
                //                                 ->where('sfx', $masterModel->sfx)->pluck('id');

                // $pfiVehicleVariant->inventoryQuantity = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                //     ->whereNull('purchase_order_id')
                //     ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                //     ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                //     ->where('supplier_id', $pfi->supplier_id)
                //     ->where('whole_sales', $dealer)
                //     ->count();
            }
        }


        $purchasingOrderSwiftCopies = PurchasingOrderSwiftCopies::where('purchasing_order_id', $id)->orderBy('created_at', 'desc')
        ->get();
        $purchasedorderevents = PurchasingOrderEventsLog::where('purchasing_order_id', $id)->get();
        $oldPlFiles = Purchasedorderoldplfiles::where('purchasing_order_id', $id)->get();
        $transitions = SupplierAccountTransaction::where('purchasing_order_id', $id)
        ->where('transaction_amount', '!=', 0)
        ->with('purchaseOrder')
        ->orderBy('created_at', 'asc')
        ->get();
        $groupedTransitions = $transitions->groupBy('purchaseOrder.po_number');
        foreach ($groupedTransitions as $po_number => $transactions) {
        foreach ($transactions as $index => $transaction) {
        $transaction->row_number = $index + 1;
        $transaction->vehicle_count = \DB::table('vehicles_supplier_account_transaction')
                                        ->where('sat_id', $transaction->id)
                                        ->count();
        }
        }
        $accounts = SupplierAccount::with('supplier')->where('id', $id)->first();
        $additionalpaymentpend = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('status', 'pending')->where('change_type', 'surcharge')->sum('price_change');
        $additionalpaymentintreq = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('status', 'Initiated Request')->where('change_type', 'surcharge')->sum('price_change');
        $additionalpaymentint = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('status', 'Initiated')->where('change_type', 'surcharge')->sum('price_change');
        $additionalpaymentpapproved = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('status', 'Approved')->where('change_type', 'surcharge')->sum('price_change');
        $exColours = ColorCode::where('belong_to', 'ex')->orderBy('name', 'ASC')->get();
        $intColours = ColorCode::where('belong_to', 'int')->orderBy('name', 'ASC')->get();
        $vehiclesdn = Vehicles::where('purchasing_order_id', $id)
        ->where('status', 'Approved')
        ->whereNotNull('dn_id')
        ->get();
        $purchaseOrders = PurchasingOrder::whereNot('status','Cancelled')->whereNot('id',$purchasingOrder->id)
                            ->where('currency', $purchasingOrder->currency)
                            ->where('vendors_id', $purchasingOrder->vendors_id)->select('id','po_number')
                            ->orderBy('id','DESC')->get();

        return view('purchase.show', [
               'currentId' => $id,
               'previousId' => $previousId,
               'nextId' => $nextId
           ], compact('purchasingOrder', 'variants', 'vehicles', 'vendorsname','showCancelButton', 'vehicleslog','exColours','intColours','showAddMorePOSection',
            'purchasinglog','paymentterms','pfiVehicleVariants','vendors', 'payments','vehiclesdel','countries','ports','purchasingOrderSwiftCopies',
            'purchasedorderevents', 'vendorDisplay', 'vendorPaymentAdjustments', 'alreadypaidamount','intialamount','totalSum', 'totalSurcharges', 'totalDiscounts',
            'oldPlFiles','transitions', 'accounts','additionalpaymentpend','additionalpaymentint','additionalpaymentpapproved','additionalpaymentintreq','vehiclesdn',
            'purchaseOrders'));

    }
    public function edit($id)
    {
    $variants = Varaint::join('brands', 'varaints.brands_id', '=', 'brands.id')
        ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
        ->select('varaints.*', 'brands.brand_name', 'master_model_lines.model_line')
        ->get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
    $vendorsname = Supplier::where('id', $purchasingOrder->vendors_id)->value('supplier');
    return view('warehouse.edit', compact('purchasingOrder', 'variants', 'vehicles', 'vendorsname'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $useractivities =  New UserActivities();
            $useractivities->activity = "Update the Purchased order details";
            $useractivities->users_id = Auth::id();
            $useractivities->save();
        $purchasingOrderId = $id;
        $variantNames = $request->input('variant_id');
        if($variantNames != null)
            {
            $variantIds = Varaint::whereIn('name', $variantNames)->pluck('id')->all();
            $variantsQuantity = array_count_values($variantNames);
            foreach ($variantIds as $variantId) {
                $variant = Varaint::find($variantId);
                $purchasingOrderItem = new PurchasingOrderItems();
                $variantQuantity = $variantsQuantity[$variant->name];
                    $IsExistpurchasingOrderItem = PurchasingOrderItems::where('purchasing_order_id', $purchasingOrderId)
                                                     ->where('variant_id', $variantId)->first();
                    if($IsExistpurchasingOrderItem) {
                        $purchasingOrderItem =  $IsExistpurchasingOrderItem;
                        $variantQuantity = $IsExistpurchasingOrderItem->qty + $variantQuantity;
                    }
                $purchasingOrderItem->qty = $variantQuantity;
                $purchasingOrderItem->variant_id = $variantId;
                $purchasingOrderItem->purchasing_order_id = $purchasingOrderId;

                $purchasingOrderItem->save();
            }
            $vins = $request->input('vin');
            $ex_colours = $request->input('ex_colour');
            $int_colours = $request->input('int_colour');
            $estimated_arrival = $request->input('estimated_arrival');
            $territory = $request->input('territory');
            $engine_number = $request->input('engine_number');
            $unit_prices = $request->input('unit_prices');
            $count = count($variantNames);
            foreach ($variantNames as $key => $variantName) {
                if ($variantName === null && $key === $count - 1) {
                continue;
                }
                $variantId = Varaint::where('name', $variantName)->pluck('id')->first();
                $ex_colour = $ex_colours[$key];
                $int_colour = $int_colours[$key];
                $engine = $engine_number[$key];
                $estimated_arrivals = $estimated_arrival[$key];
                $unit_price = $unit_prices[$key];
                $vin = $vins[$key];
                $vehicle = new Vehicles();
                $vehicle->varaints_id = $variantId;
                $vehicle->vin = $vin;
                $vehicle->ex_colour = $ex_colour;
                $vehicle->int_colour = $int_colour;
                $vehicle->estimation_date = $estimated_arrivals;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $vehicle->territory = 'Africa';
                }else{
                    $territorys = $territory[$key];
                    $vehicle->territory = $territorys;
                }
                if($request->input('master_model_id')) {
                    $masterModelId = $request->input('master_model_id');
                    $vehicle->model_id = $masterModelId[$key];
                }
                $vehicle->purchasing_order_id = $purchasingOrderId;
                $vehicle->status = "New Vehicles";
                $vehicle->save();
                $purchasingOrdertotal = PurchasingOrder::find($purchasingOrderId);
                $purchasingOrdertotal->totalcost = $purchasingOrdertotal->totalcost + $unit_price;
                $purchasingOrdertotal->save();
                $vehiclecost = New VehiclePurchasingCost();
                $vehiclecost->currency = $request->input('currency');
                $vehiclecost->unit_price = round($unit_price, 2);
                $vehiclecost->vehicles_id = $vehicle->id;
                $vehiclecost->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $purchasinglog = new Purchasinglog();
                $purchasinglog->time = now()->toTimeString();
                $purchasinglog->date = now()->toDateString();
                $purchasinglog->status = 'Adding New Vehicle';
                $purchasinglog->purchasing_order_id = $purchasingOrderId;
                $purchasinglog->variant = $variantId;
                $purchasinglog->estimation_date = $estimated_arrivals;
                $purchasinglog->engine_number = $engine;
                if($request->po_from == 'DEMAND_PLANNING') {
                    $vehicle->territory = 'Africa';
                }else{
                    $purchasinglog->territory = $territorys;
                }
                $purchasinglog->ex_colour = $ex_colour;
                $purchasinglog->int_colour = $int_colour;
                $purchasinglog->created_by = auth()->user()->id;
                $purchasinglog->role = Auth::user()->selectedRole;
                $purchasinglog->save();
            }
            $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
            // return $purchasingOrder;
                if ($purchasingOrder) {
                    $purchasingOrder->status = 'Pending Approval';
                    $purchasingOrder->save();
                }

    //         Demand planning PO
                if($request->po_from == 'DEMAND_PLANNING') {
                    $masterModels = $request->master_model_id;
                    $pfiId = $purchasingOrder->PFIPurchasingOrder->pfi->id ?? " ";
                    foreach($request->pfi_items as $key => $pfiItem) {
                        if($request->item_quantity_selected[$key] > 0) {
                            $PfiItemPurchaseOrder = PfiItemPurchaseOrder::where('purchase_order_id', $purchasingOrder->id)
                            ->where('pfi_item_id', $pfiItem)
                            ->where('master_model_id', $request->selected_model_ids[$key])
                            ->first();
                            if(!$PfiItemPurchaseOrder) {
                                $PfiItemPurchaseOrder = new PfiItemPurchaseOrder();
                                $PfiItemPurchaseOrder->quantity = $request->item_quantity_selected[$key] ?? '';
                            }else{
                                $addedQty =  $PfiItemPurchaseOrder->quantity;
                                $PfiItemPurchaseOrder->quantity = $addedQty + $request->item_quantity_selected[$key] ?? '';
                            }

                            $PfiItemPurchaseOrder->pfi_id = $pfiId;
                            $PfiItemPurchaseOrder->pfi_item_id = $pfiItem;
                            $PfiItemPurchaseOrder->purchase_order_id = $purchasingOrderId;
                            $PfiItemPurchaseOrder->master_model_id = $request->selected_model_ids[$key];
                        
                            $PfiItemPurchaseOrder->save();
                        }
                    }
                }
        }
       
        foreach ($variantsQuantity as $variant => $quantity) {
            $description = $variant . ' with ' . $quantity . ' qty';
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Add New Vehicles";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $purchasingOrderId;
            $purchasingordereventsLog->description = $description;
            $purchasingordereventsLog->save();
        }
        DB::commit();
    return back()->with('success', 'Added Vehicles In PO successfully!');
    }
    public function deletes($id)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "Delete the Purchased Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    // Delete related records from vehicles_log table
    $vehicleIds = Vehicles::where('purchasing_order_id', $id)->pluck('id');
    Vehicleslog::whereIn('vehicles_id', $vehicleIds)->delete();

    // Delete records from other related tables
    PurchasingOrderItems::where('purchasing_order_id', $id)->delete();
    Vehicles::where('purchasing_order_id', $id)->delete();
    Purchasinglog::where('purchasing_order_id', $id)->delete();

    // Delete the purchasing order itself
    PurchasingOrder::where('id', $id)->delete();

    return back()->with('success', 'Deletion successful');
    $notPaidCount = Vehicles::where('purchasing_order_id', $id)
        ->where('payment_status', 'Payment Completed')
        ->count();

    if ($notPaidCount > 0) {
        return back()->with('error', 'Cannot delete. Some vehicles have payment status is "Paid"');
    } else {
        // Delete purchasing order items
        PurchasingOrderItems::where('purchasing_order_id', $id)->delete();

        // Delete vehicles
        Vehicles::where('purchasing_order_id', $id)->delete();

        // Delete purchasing order
        $purchasingOrder = PurchasingOrder::find($id);
        $purchasingOrder->delete();

        return back()->with('success', 'Deletion successful');
    }
}

    public function checkPONumber(Request $request)
    {
        $poNumber = $request->input('poNumber');
        $existingPO = PurchasingOrder::where('po_number', $poNumber)->first();
        if ($existingPO) {
            return response()->json(['error' => 'PO number already exists'], 422);
        }
        return response()->json(['success' => 'PO number is valid'], 200);
    }

    public function viewdetails($id)
{
    $useractivities =  New UserActivities();
        $useractivities->activity = "View details of the Purchased Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
    $varaint = Varaint::get();
    $purchasingOrder = PurchasingOrder::findOrFail($id);
    $data = Vehicles::where('purchasing_order_id', $id)->where('status', '!=', 'cancel')->get();
    $vendorsname = Supplier::where('id', $purchasingOrder->vendors_id)->value('supplier');
    $sales_persons = ModelHasRoles::get();
    $sales_ids = $sales_persons->pluck('model_id');
    $sales = User::whereIn('id', $sales_ids)->get();
    return view('warehouse.vehiclesdetails', compact('purchasingOrder', 'varaint', 'data', 'vendorsname', 'sales'));
}
public function checkcreatevins(Request $request)
    {
        $vinValues = $request->input('vin');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }
    public function checkcreatevinsinside(Request $request)
    {
        $vinValues = $request->input('vins');
        $po = $request->input('po');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->whereNot('purchasing_order_id', $po)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }
    public function checkeditcreate(Request $request)
    {
        $vinValues = $request->input('vin');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }

    public function checkeditvins(Request $request)
    {
        $vinValues = $request->input('oldvin');
        $vinValues = array_filter($vinValues, function ($value) {
            return trim($value) !== '';
        });
        $duplicates = array_unique(array_diff_assoc($vinValues, array_unique($vinValues)));
        if (!empty($duplicates)) {
            return response()->json('duplicate');
        }
        $existingVins = Vehicles::whereIn('vin', $vinValues)->pluck('vin')->toArray();
        if (!empty($existingVins)) {
            return response()->json('duplicate');
        }
        return response()->json('unique');
    }
    public function updatepurchasingData(Request $request)
{

    $updatedData = $request->json()->all();
    $updatedVins = [];
    foreach ($updatedData as $data) {
        $vehicleId = $data['id'];
        $fieldName = $data['name'];
        $fieldValue = $data['value'];
        $vehicle = Vehicles::find($vehicleId);
        if ($vehicle) {
            $oldValues = $vehicle->getAttributes();
            $vehicle->setAttribute($fieldName, $fieldValue);
            $vehicle->save();
            $changes = [];
            foreach ($oldValues as $field => $oldValue) {
                if ($field !== 'created_at' && $field !== 'updated_at') {
                    $newValue = $vehicle->$field;
                    if ($oldValue != $newValue) {
                        $changes[$field] = [
                            'old_value' => $oldValue,
                            'new_value' => $newValue,
                        ];
                    }
                }
            }
            // info($changes);
            if (!empty($changes)) {
                $vehicle->save();
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $vinChanges = [];
                foreach ($changes as $field => $change) {
                    $vehicleslog = new Vehicleslog();
                    $vehicleslog->time = $currentDateTime->toTimeString();
                    $vehicleslog->date = $currentDateTime->toDateString();
                    $vehicleslog->status = 'Update Vehicles On Purchased Order';
                    $vehicleslog->vehicles_id = $vehicleId;
                    $vehicleslog->field = $field;
                    $vehicleslog->old_value = $change['old_value'];
                    $vehicleslog->new_value = $change['new_value'];
                    $vehicleslog->created_by = auth()->user()->id;
                    $vehicleslog->role = Auth::user()->selectedRole;
                    $vehicleslog->save();
                    if ($field == 'vin') {
                        $updatedVins[] = $fieldValue; // Collect updated VINs
                        $vinChanges[] = [
                            'old_vin' => $change['old_value'],
                            'new_vin' => $change['new_value'],
                        ]; // Store VIN changes
                    }
                    if ($field == 'int_colour') {
                        $newfield = "Interior Colour";
                        $oldval = ColorCode::find($change['old_value']);
                        $oldvalue = $oldval ? $oldval->name : "";
                        $newval = ColorCode::find($change['new_value']);
                        $namevalue = $newval->name;
                    } elseif ($field == 'ex_colour') {
                        $newfield = "Exterior Colour";
                        $oldval = ColorCode::find($change['old_value']);
                        $oldvalue = $oldval ? $oldval->name : "";
                        $newval = ColorCode::find($change['new_value']);
                        $namevalue = $newval->name;
                    } elseif ($field == 'engine') {
                        $newfield = "Engine Number";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } elseif ($field == 'vin') {
                        $newfield = "VIN";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } elseif ($field == 'territory') {
                        $newfield = "Territory";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } elseif ($field == 'estimation_date') {
                        $newfield = "Estimation Date";
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    } else {
                        $newfield = $field;
                        $oldvalue = $change['old_value'] ?? "";
                        $namevalue = $change['new_value'];
                    }
                    $description = "Vehicle reference is $vehicleId change the $newfield from $oldvalue to $namevalue";
                    $purchasingordereventsLog = new PurchasingOrderEventsLog();
                    $purchasingordereventsLog->event_type = "Changes into Vehicle date";
                    $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = $newfield;
                    $purchasingordereventsLog->old_value = $oldvalue;
                    $purchasingordereventsLog->new_value = $namevalue;
                    $purchasingordereventsLog->description = $description;
                    $purchasingordereventsLog->save();
                }
                $purchasingOrderId = $vehicle->purchasing_order_id;
                $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
                if ($purchasingOrder) {
                //    info ($fieldName);
//                    check the po is under demand planning

                    $purchasingOrderPFI = PFIItemPurchaseOrder::where('purchase_order_id', $purchasingOrderId)->first();
                       if($purchasingOrderPFI) {
                           $supplierInventory = SupplierInventory::find($vehicle->supplier_inventory_id);
                           if($supplierInventory) {
                               if($fieldName == 'vin') {
                                   $supplierInventory->chasis = $fieldValue;
                               }
                               if($fieldName == 'estimation_date') {
                                   $supplierInventory->eta_import =  \Illuminate\Support\Carbon::parse($fieldValue)->format('Y-m-d');
                               }
                               if($fieldName == 'int_colour') {
                                   $supplierInventory->interior_color_code_id = $fieldValue ?? '';
                               }
                               if($fieldName == 'ex_colour') {
                                   $supplierInventory->exterior_color_code_id = $fieldValue ?? '';
                               }
                               if($fieldName == 'engine') {
                                   $supplierInventory->engine_number = $fieldValue ?? '';
                               }
                               $action = str_replace('_', ' ', $fieldName) ." updated";
                               (new SupplierInventoryController)->inventoryLog($action, $supplierInventory->id);

                               $supplierInventory->save();
                           }
                       }

                    $purchasingOrder->status = 'Pending Approval';
                    $purchasingOrder->save();
                    
                }

            }
        }
    }
    if (!empty($updatedVins)) {
        $groupedVehicles = Vehicles::whereIn('vin', $updatedVins)->with([
            'variant.master_model_lines.brand',
            'variant.brand',
            'interior',
            'exterior'
        ])->get()->groupBy('purchasing_order_id');
        foreach ($groupedVehicles as $purchasingOrderId => $vehicles) {
            $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
            if ($purchasingOrder) {

                $purchasingOrderPFI = PFIItemPurchaseOrder::where('purchase_order_id', $purchasingOrderId)->first();
                if($purchasingOrderPFI) {
                   $pfiNumber = $purchasingOrderPFI->pfi->pfi_reference_number; 
                }else{
                    $pfiNumber = $purchasingOrder->pl_number;
                }

                $logisticsEmail = env('LOGISTICS_EMAIL', 'default@domain.com');
                $additionalEmail = env('CSO_EMAIL', null);
                $recipients = $purchasingOrder->is_demand_planning_po == 1 
                ? [$logisticsEmail] 
                : [$logisticsEmail, $additionalEmail];

                $orderUrl = url('/purchasing-order/' . $purchasingOrderId);
                $vehicleDetails = $vehicles->map(function ($vehicle) use ($vinChanges) {
                    $vinChange = collect($vinChanges)->firstWhere('new_vin', $vehicle->vin);
                    return [
                        'brand' => $vehicle->variant->brand->brand_name ?? '',
                        'model_line' => $vehicle->variant->master_model_lines->model_line ?? '',
                        'variant' => $vehicle->variant->name ?? '',
                        'old_vin' => $vinChange['old_vin'] ?? '', // Include old VIN
                        'new_vin' => $vehicle->vin,
                        'int_colour' => $vehicle->interior->name ?? '',
                        'ext_colour' => $vehicle->exterior->name ?? '',
                    ];
                })->toArray(); // Ensure it's an array
                Mail::to($recipients)->send(new VINEmailNotification(
                    $purchasingOrder->po_number, 
                    $pfiNumber,
                    $orderUrl, 
                    count($vehicles), 
                    $vehicleDetails
                ));
                // Save notification details to the database
                $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
              "PFI Number: " .  $pfiNumber . "\n" .
              "Stage: Update The VIN\n" .
              "Number of Units: " . count($vehicles) . "\n" .
              "Old VIN: " . ($vinChange['old_vin'] ?? '') . "\n" .
              "New VIN: " . $vehicle->vin . "\n" .
              "Order URL: " . $orderUrl;
                $notification = new DepartmentNotifications();
                $notification->module = 'Procurement';
                $notification->type = 'Information';
                $notification->detail = $detailText;
                $notification->save();
                if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 8; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 8; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
            }
        }
    }

    return response()->json(['message' => 'Data updated successfully']);
}
public function purchasingupdateStatus(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Update Purchasing Order Status";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = $request->input('orderId');
        $status = $request->input('status');
        $purchasingOrder = PurchasingOrder::find($id);
        if (!$purchasingOrder) {
            return response()->json(['message' => 'Purchasing order not found'], 404);
        }
        $purchasingOrder->status = $status;
        $purchasingOrder->save();
        $vehicles = Vehicles::where('purchasing_order_id', $id)->where('status', '!=', 'Rejected')->get();
        foreach ($vehicles as $vehicle) {
            if ($vehicle->status == 'New Changes' || $vehicle->status == 'Not Approved' || $vehicle->status == 'New Vehicles') {
            if($purchasingOrder->po_type === "Payment Adjustment")
            {
                $vehicle->status = 'Payment Completed';
                $vehicle->payment_status = 'Payment Completed';
            }
            else{
                $vehicle->status = $status;
            }
            $vehicle->save();
            $ex_colour = $vehicle->ex_colour;
            $int_colour = $vehicle->int_colour;
            $variantId = $vehicle->	varaints_id;
            $estimation_arrival = $vehicle->estimation_date;
            $territorys = $vehicle->territory;
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'PO Approved';
            $purchasinglog->purchasing_order_id = $id;
            $purchasinglog->variant = $variantId;
            $purchasinglog->estimation_date = $estimation_arrival;
            $purchasinglog->territory = $territorys;
            $purchasinglog->ex_colour = $ex_colour;
            $purchasinglog->int_colour = $int_colour;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->save();
        }
    }
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "PO Approved";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $id;
            $purchasingordereventsLog->save();
        return response()->json(['message' => 'Status updated successfully'], 200);
    }
    public function confirmPayment($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Purchased order status to payment confirm";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle = Vehicles::find($id);
        if ($vehicle) {
            $vehicle->status = 'Request for Payment';
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Request for Payment';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Not Paid";
                $vehicleslog->new_value = "Request for Initiate Payment";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Request for Payment";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Vehicle Status";
                    $purchasingordereventsLog->old_value = "Not Paid";
                    $purchasingordereventsLog->new_value = "Request for Initiate Payment";
                    $purchasingordereventsLog->description = "PO Creator Request the Payment to the Againt of the Vehicle Ref $id";
                    $purchasingordereventsLog->save();
            return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function cancel(Request $request, $id)
    {
        $vehicle = Vehicles::findOrFail($id);
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-edit');
        if ($vehicle->status == 'Approved') {
            if($hasPermission)
            {
                $purchasinglog = new Purchasinglog();
                $purchasinglog->time = now()->toTimeString();
                $purchasinglog->date = now()->toDateString();
                $purchasinglog->status = 'Vehicle Cancel';
                $purchasinglog->role = Auth::user()->selectedRole;
                $purchasinglog->purchasing_order_id = $vehicle->purchasing_order_id;
                $purchasinglog->variant = $vehicle->varaints_id;
                $purchasinglog->estimation_date = $vehicle->estimation_date;
                $purchasinglog->territory = $vehicle->territory;
                $purchasinglog->int_colour = $vehicle->int_colour;
                $purchasinglog->ex_colour = $vehicle->ex_colour;
                $purchasinglog->created_by = auth()->user()->id;
                $purchasinglog->save();
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = now()->toTimeString();
                $vehicleslog->date = now()->toDateString();
                $vehicleslog->status = 'Vehicle Cancel';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Status";
                $vehicleslog->old_value = $vehicle->status;
                $vehicleslog->new_value = 'Vehicle Cancel';
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Vehicle Cancel";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Cancel Vehicle";
                    $purchasingordereventsLog->new_value = "Vehicle Cancel";
                    $purchasingordereventsLog->description = "Vehicle Procurement Manager Cancel the Vehicle $id";
                    $purchasingordereventsLog->save();
                $updateqty = PurchasingOrderItems::where('variant_id', $vehicle->varaints_id)->where('purchasing_order_id', $vehicle->purchasing_order_id)->first();
                if($updateqty)
                {
                    $updateqty->qty = intval($updateqty->qty) - 1;
                    $updateqty->save();
                }
                $updateprice = VehiclePurchasingCost::where('vehicles_id', $id)->first();
                if($updateprice)
                {
                $updatetotal = PurchasingOrder::find($vehicle->purchasing_order_id);
                $updatetotal->totalcost = $updatetotal->totalcost - $updateprice->unit_price;
                $updatetotal->save();
                }
                if($vehicle->purchasingOrder->is_demand_planning_purchase_order == true) {
                    // dp purchase order
                    $masterModel = MasterModel::find($vehicle->model_id);
                    // $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    //     ->where('sfx', $masterModel->sfx)->pluck('id');
                    // $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                    //     ->whereIn('master_model_id', $possibleModelIds)
                    //     ->first();
                    // $inventoryItem->purchase_order_id = NULL;
                    // $inventoryItem->pfi_id = NULL;
                    // $inventoryItem->letter_of_indent_item_id  = NULL;
                    // $inventoryItem->save();
        
                    $PfiPurchaseOrder = PfiItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                                                                ->where('master_model_id', $vehicle->model_id)
                                                                ->first();
                    if($PfiPurchaseOrder) {
                        $PfiPurchaseOrder->quantity = $PfiPurchaseOrder->quantity - 1;
                        $PfiPurchaseOrder->save();
                    }
                    // if payment initiated reduce the payment initiated qty => chcek toyota have any case of cancel
                        // $supplierAccountTransaction = SupplierAccountTransaction::select('transaction_type','purchasing_order_id')
                        //                             ->whereNot('transaction_type','Rejected')
                        //                             ->where('purchasing_order_id',  $vehicle->purchasing_order_id)
                        //                             ->first();
                        // if($supplierAccountTransaction){
                        //     // get the LOI Item to update initiated qty
                        //     $possibleModels = MasterModel::where('model', $masterModel->model)
                        //                             ->where('sfx',  $masterModel->sfx)
                        //                             ->pluck('id')->toArray();
                        //         $pfiItem = PfiItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                        //                                         ->whereIn('master_model_id', $possibleModels)
                        //                                         ->first();
                        //         $loiItem = LetterOfIndentItem::whereHas('pfiItems', function($query)use($pfiItem) {
                        //                     $query->where('is_parent', false)
                        //                     ->where('pfi_id', $pfiItem->pfi_id)
                        //                     ->where('parent_pfi_item_id', $pfiItem->pfi_item_id);
                        //                 })
                        //                 ->first();
                        //         if($loiItem) {
                        //             $latestUtilizedQuantity = $loiItem->utilized_quantity + 1;
                        //             $loiItem->po_payment_initiated_quantity = $loiItem->po_payment_initiated_quantity - 1;
                        //             $loiItem->utilized_quantity = $latestUtilizedQuantity;
                        //             $loiItem->save();
                        //         }
                        // }
                                                    
                }
            $vehicle->procurement_vehicle_remarks = $request->input('remarks');
            $vehicle->save();
            $vehicle->delete();
            }
            else
            {
            $vehicle->status = 'Request for Cancel';
            $vehicle->procurement_vehicle_remarks = $request->input('remarks');
            $vehicle->save();
            $purchasedorders = PurchasingOrder::find($vehicle->purchasing_order_id);
            $purchasedorders->status = 'Pending Approval';
            $purchasedorders->save();
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Cancel Request";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Vehicle Cancel Request";
                    $purchasingordereventsLog->new_value = "Cancel Request";
                    $purchasingordereventsLog->description = "Vehicle Executive Send to Cancel the Vehicle $id";
                    $purchasingordereventsLog->save();
            }
        }
        else
        {
        $purchasinglog = new Purchasinglog();
        $purchasinglog->time = now()->toTimeString();
        $purchasinglog->date = now()->toDateString();
        $purchasinglog->status = 'Vehicle Cancel';
        $purchasinglog->role = Auth::user()->selectedRole;
        $purchasinglog->purchasing_order_id = $vehicle->purchasing_order_id;
        $purchasinglog->variant = $vehicle->varaints_id;
        $purchasinglog->estimation_date = $vehicle->estimation_date;
        $purchasinglog->territory = $vehicle->territory;
        $purchasinglog->int_colour = $vehicle->int_colour;
        $purchasinglog->ex_colour = $vehicle->ex_colour;
        $purchasinglog->created_by = auth()->user()->id;
        $purchasinglog->save();
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = now()->toTimeString();
        $vehicleslog->date = now()->toDateString();
        $vehicleslog->status = 'Vehicle Cancel';
        $vehicleslog->vehicles_id = $id;
        $vehicleslog->field = "Status";
        $vehicleslog->old_value = $vehicle->status;
        $vehicleslog->new_value = 'Vehicle Cancel';
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
        $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Vehicle Cancel";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                    $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
                    $purchasingordereventsLog->field = "Cancel Vehicle";
                    $purchasingordereventsLog->new_value = "Vehicle Cancel";
                    $purchasingordereventsLog->description = "Vehicle Procurement Manager Cancel the Vehicle $id";
                    $purchasingordereventsLog->save();
        $updateqty = PurchasingOrderItems::where('variant_id', $vehicle->varaints_id)->where('purchasing_order_id', $vehicle->purchasing_order_id)->first();
        if($updateqty)
        {
            $updateqty->qty = intval($updateqty->qty) - 1;
            $updateqty->save();
        }
        $updateprice = VehiclePurchasingCost::where('vehicles_id', $id)->first();
        if($updateprice)
        {
        $updatetotal = PurchasingOrder::find($vehicle->purchasing_order_id);
        $updatetotal->totalcost = $updatetotal->totalcost - $updateprice->unit_price;
        $updatetotal->save();
        }
        if($vehicle->purchasingOrder->is_demand_planning_purchase_order == true) {
            // dp purchase order
            $masterModel = MasterModel::find($vehicle->model_id);
            // $possibleModelIds = MasterModel::where('model', $masterModel->model)
            //     ->where('sfx', $masterModel->sfx)->pluck('id');
            // $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
            //     ->whereIn('master_model_id', $possibleModelIds)
            //     ->first();
            // $inventoryItem->purchase_order_id = NULL;
            // $inventoryItem->pfi_id = NULL;
            // $inventoryItem->letter_of_indent_item_id  = NULL;
            // $inventoryItem->save();

            $PfiPurchaseOrder = PfiItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                                                        ->where('master_model_id', $vehicle->model_id)
                                                        ->first();
            if($PfiPurchaseOrder) {
                $PfiPurchaseOrder->quantity = $PfiPurchaseOrder->quantity - 1;
                $PfiPurchaseOrder->save();
            }

        }
        $vehicle->procurement_vehicle_remarks = $request->input('remarks');
        $vehicle->save();
        $vehicle->delete();
        }
        return redirect()->back()->with('success', 'Vehicle cancellation request submitted successfully.');
    }
    public function rejecteds($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Purchased order status to Rejected By BOD";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle = Vehicles::find($id);
        if ($vehicle) {
            $vehicle->status = 'Rejected';
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Rejected By BOD';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Pending Approval";
                $vehicleslog->new_value = "Rejected By BOD";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();

            if($vehicle->model_id) {
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id');
                $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->whereIn('master_model_id', $possibleModelIds)
                    ->first();

                $inventoryItem->purchase_order_id = NULL;
                $inventoryItem->pfi_id = NULL;
                $inventoryItem->letter_of_indent_item_id  = NULL;
                $inventoryItem->save();

                $purchaseOrderItem = PurchasingOrderItems::where('purchasing_order_id', $vehicle->purchasing_order_id)
                    ->where('variant_id', $vehicle->varaints_id)->first();

                if($purchaseOrderItem) {
                    $purchaseOrderItem->qty = $purchaseOrderItem->qty - 1;
                    $purchaseOrderItem->save();
                }

                $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->where('master_model_id', $vehicle->model_id)
                    ->first();

                $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity - 1;
                $loiPurchaseOrder->save();
            }
            return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function unrejecteds($id)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Change the Purchased order status to Rejected By BOD";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $vehicle = Vehicles::find($id);
        if ($vehicle) {
            $vehicle->status = 'Not Approved';
            $vehicle->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Un-Rejected By BOD';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Rejected By BOD";
                $vehicleslog->new_value = "Pending Approval";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            if($vehicle->model_id) {
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleModelIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id');

                $purchasingOrder = PurchasingOrder::findOrFail($vehicle->purchasing_order_id);
                $dealer = $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->letterOfIndent->dealers ?? '';
                $pfi_id = $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->id ?? '';
                $letterOfIndentId = $purchasingOrder->LOIPurchasingOrder->approvedLOI->pfi->letter_of_indent_id ?? '';

                $inventoryItem = SupplierInventory::whereIn('master_model_id', $possibleModelIds)
                    ->whereNull('purchase_order_id')
                    ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
                    ->where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
                    ->where('supplier_id', $purchasingOrder->vendors_id)
                    ->where('whole_sales', $dealer);

                if($vehicle->vin) {
                    $inventoryItem = $inventoryItem->where('chasis', $vehicle->vin);
                }

                if($inventoryItem->count() > 0) {
                    $inventoryIds = $inventoryItem->pluck('id');
                    $inventory = SupplierInventory::where('pfi_id', $pfi_id)
                        ->whereIn('id', $inventoryIds);
                    if($inventory->count() > 0) {
                        $inventoryItem = $inventory->first();

                    }else{
                        $inventoryItem = $inventoryItem->first();
                        $inventoryItem->pfi_id = $pfi_id;
                    }

                    $loiItem = LetterOfIndentItem::where('letter_of_indent_id', $letterOfIndentId)
                                                ->whereIn('master_model_id', $possibleModelIds)->first();
                    if($loiItem) {
                        $inventoryItem->letter_of_indent_item_id = $loiItem->id ?? '';
                    }
                    $inventoryItem->purchase_order_id = $purchasingOrder->id;
                    $inventoryItem->save();
                }
                $purchaseOrderItem = PurchasingOrderItems::where('purchasing_order_id', $vehicle->purchasing_order_id)
                                                             ->where('variant_id', $vehicle->varaints_id)->first();
                if($purchaseOrderItem) {
                    $purchaseOrderItem->qty = $purchaseOrderItem->qty + 1;
                    $purchaseOrderItem->save();
                }

                $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                    ->where('master_model_id', $vehicle->model_id)
                    ->first();
                $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity + 1;
                $loiPurchaseOrder->save();
            }
            return redirect()->back()->with('success', 'Un-Reject confirmed. Vehicle status updated.');
        }
        return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function deleteVehicle($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehiclesLog = new Vehicleslog();
        $vehiclesLog->time = $currentDateTime->toTimeString();
        $vehiclesLog->date = $currentDateTime->toDateString();
        $vehiclesLog->status = 'Deleted By BOD';
        $vehiclesLog->vehicles_id = $vehicle->id;
        $vehiclesLog->field = "Vehicle Status";
        $vehiclesLog->old_value = $vehicle->status;
        $vehiclesLog->new_value = "Deleted By BOD";
        $vehiclesLog->created_by = auth()->user()->id;
        $vehiclesLog->role = Auth::user()->selectedRole;
        $vehiclesLog->save();
        $vehicle->delete();
        return redirect()->back()->with('success', 'Vehicle deleted successfully.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentintconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Initiated Request';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Request';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated Request";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $totalCost = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
            $paymentinti = New PurchasedOrderPaidAmounts();
            $paymentinti->amount = $totalCost;
            $paymentinti->purchasing_order_id = $vehicle->purchasing_order_id;
            $paymentinti->created_by = auth()->user()->id;
            $paymentinti->status = "Request For Payment";
            $paymentinti->save();
        return redirect()->back()->with('success', 'Payment Initiated Request confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleaserejected($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Rejected';
        $vehicle->payment_status = 'Payment Initiate Request Rejected';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiate Request Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Initiated Request";
            $vehicleslog->new_value = "Payment Initiate Request Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiate Request Rejected confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleaseconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Initiate Request Approved';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiate Request Approved';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiated Request";
            $vehicleslog->new_value = "Payment Initiate Request Approved";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiate Request Approved confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Initiated';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiate Request Approved";
            $vehicleslog->new_value = "Payment Initiated";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiated confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleasesconfirm($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        DB::beginTransaction();
        $vehicle->status = 'Payment Requested';
        $vehicle->payment_status = 'Payment Release Approved';
        $vehicle->procurement_vehicle_remarks = null;
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Approved';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiated";
            $vehicleslog->new_value = "Payment Release Approved";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();

            // if($vehicle->model_id) {
            //     // get the loi item and update the utilization quantity
            //     $approvedIds = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
            //         ->pluck('approved_loi_id');

            //     $loiItemIds = ApprovedLetterOfIndentItem::whereIn('id', $approvedIds)->pluck('letter_of_indent_item_id');
            //     $possibleIds = MasterModel::where('model', $vehicle->masterModel->model)
            //         ->where('sfx', $vehicle->masterModel->sfx)->pluck('id')->toArray();
            //     foreach ($loiItemIds as $loiItemId) {
            //         $item = LetterOfIndentItem::find($loiItemId);
            //         if(in_array($item->master_model_id, $possibleIds)) {
            //             if($item->utilized_quantity < $item->total_loi_quantity) {
            //                 $item->utilized_quantity = $item->utilized_quantity + 1;
            //                 $item->save();
            //                 // get the total utilized qty and update against LOI
            //                 // $LOI = LetterOfIndent::find($item->letter_of_indent_id);
            //                 // $utilized_quantity =  LetterOfIndentItem::where('letter_of_indent_id', $LOI->id)
            //                 //                         ->sum('utilized_quantity');
            //                 // $LOI->utilized_quantity  = $utilized_quantity;
            //                 // $LOI->save();
            //                 break;
            //             }
            //         }
            //     }
            // }
            DB::commit();
        return redirect()->back()->with('success', 'Payment Payment Release Approved confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentreleasesrejected(Request $request, $id)
{

    // info($id);
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Payment Rejected';
        $vehicle->payment_status = 'Payment Release Rejected';
        $vehicle->procurement_vehicle_remarks = $request->input('remarks');
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Release Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Payment Status";
            $vehicleslog->old_value = "Payment Initiated";
            $vehicleslog->new_value = "Payment Release Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            return response()->json(['success' => 'Payment Release Rejected confirmed. Vehicle status updated.']);
    }
    return response()->json(['error' => 'Vehicle not found.'], 404);
}
public function paymentrelconfirmdebited(Request $request, $id)
{
    // need to test
    $vehicle = Vehicles::find($id);
    $vehicleCount = $vehicle->count();
           if ($request->hasFile('paymentFile')) {
            $file = $request->file('paymentFile');
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/swift_copies'), $fileNameToStore);            
            $latestBatch = DB::table('purchasing_order_swift_copies')
                ->where('purchasing_order_id', $vehicle->purchasing_order_id)
                ->orderBy('created_at', 'desc')
                ->first();
            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;
            $swiftcopy = new PurchasingOrderSwiftCopies();
            $swiftcopy->purchasing_order_id = $vehicle->purchasing_order_id;
            $swiftcopy->uploaded_by = auth()->user()->id;
            $swiftcopy->number_of_vehicles = $vehicleCount;
            $swiftcopy->batch_no = $batchNo;
            $swiftcopy->file_path = 'storage/swift_copies/' . $fileNameToStore;
            $swiftcopy->save();
            $purchasedorder = PurchasingOrder::where('id', $vehicle->purchasing_order_id)->first();
            $supplieraccountchange = SupplierAccount::where('suppliers_id', $purchasedorder->vendors_id)->first();
            $paymentad = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
                ->where('status', 'Approved')
                ->sum('amount');
            if (!$supplieraccountchange) {
            $supplieraccountchange = new SupplierAccount();
            $supplieraccountchange->suppliers_id = $purchasedorder->vendors_id;
            $supplieraccountchange->current_balance += $paymentad;
            $supplieraccountchange->currency = $purchasedorder->currency;
            $supplieraccountchange->opening_balance = 0;
            $supplieraccountchange->save();
            }
            $supplieracc->current_balance += $paymentad;
            $supplieraccountchange->save();
            $supplieraccount = new SupplierAccountTransaction();
            $supplieraccount->transaction_type = "Debit";
            $supplieraccount->purchasing_order_id = $vehicle->purchasing_order_id;
            $supplieraccount->supplier_account_id = $supplieraccountchange->id;
            $supplieraccount->created_by = auth()->user()->id;
            $supplieraccount->account_currency = $purchasedorder->currency;
            $supplieraccount->transaction_amount = $paymentad;
            $supplieraccount->save();
        }
    if ($vehicle) {
        DB::beginTransaction();
        $vehicle->status = 'Payment Completed';
        $vehicle->payment_status = 'Payment Completed';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Completed';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Release Approved";
            $vehicleslog->new_value = "Payment Completed";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $paymentlogs = new PaymentLog();
                $paymentlogs->date = $currentDateTime->toDateString();
                $paymentlogs->vehicle_id = $vehicle->id;
                $paymentlogs->created_by = auth()->user()->id;
                $paymentlogs->save();

                DB::commit();
        return redirect()->back()->with('success', 'Payment Payment Completed confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirmvendors($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Incoming Stock';
        $vehicle->payment_status = 'Incoming Stock';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Vendor Confirmed';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Completed";
            $vehicleslog->new_value = "Incoming Stock";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
//            if($vehicle->master_model_id) {
//                $masterModel = MasterModel::find($vehicle->master_model_id);
//                $similarModelIds = MasterModel::where('model', $masterModel->model)
//                    ->where('steering', $masterModel->steering)
//                    ->where('sfx', $masterModel->sfx)
//                    ->where('model_year', $masterModel->model_year)
//                    ->pluck('id')->toArray();
//                // find the supplier and dealer
//               $supplier_id = $vehicle->purchasingOrder->LOIPurchasingOrder->approvedLOI->letterOfIndent->supplier_id ?? '';
//               $dealer = $vehicle->purchasingOrder->LOIPurchasingOrder->approvedLOI->letterOfIndent->dealers ?? '';
//              // dd($supplier_id);
//                // check the eta import date update time
//               $supplierInventory = SupplierInventory::where('veh_status', SupplierInventory::VEH_STATUS_SUPPLIER_INVENTORY)
//                   ->where('upload_status', SupplierInventory::UPLOAD_STATUS_ACTIVE)
//                   ->where('supplier_id', $supplier_id)
//                   ->where('whole_sales', $dealer)
//                   ->whereIn('master_model_id', $similarModelIds)
//                    ->whereNull('delivery_note')
//                   ->first();
////               info($supplierInventory->id);
//               if($supplierInventory) {
//                   $supplierInventory->veh_status = SupplierInventory::VEH_STATUS_VENDOR_CONFIRMED;
//                   $supplierInventory->save();
//               }
//
//            }

        return redirect()->back()->with('success', 'Vendor Confirmed confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function paymentrelconfirmincoming($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Incoming Stock';
        $vehicle->payment_status = 'Incoming Stock';
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Incoming Stock';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Vendor Confirmed";
            $vehicleslog->new_value = "Incoming Stock";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Incoming Stock confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}

public function purchasingallupdateStatus(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
        ->where('payment_status', 'Payment Initiated Request')
        ->where('purchasing_order_id', $id)
        ->get();
    foreach ($vehicles as $vehicle) {
    if ($status == 'Approved') {
            $paymentStatus = 'Payment Initiate Request Approved';
        } elseif ($status == 'Rejected') {
            $paymentStatus = 'Payment Initiate Request Rejected';
        }
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['payment_status' => $paymentStatus]);
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Payment Initiated Request Status';
        $vehicleslog->vehicles_id = $vehicle->id;
        $vehicleslog->field = 'Payment Status';
        $vehicleslog->old_value = 'Payment Initiated Request';
        $vehicleslog->new_value = $paymentStatus;
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
    }
    return redirect()->back()->with('success', 'Payment Initiated Request confirmed. Vehicle status updated.');
}
public function purchasingallupdateStatusrel(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $remarks = $request->input('remarks', null);
    $vehicles = DB::table('vehicles')
    ->where('purchasing_order_id', $id)
    ->where(function($query) {
        $query->where('payment_status', 'Payment Initiated')
              ->orWhere('remaining_payment_status', 'Payment Initiated');
    })
    ->get();
    if ($status == 'Approved') {
        $PurchasingOrder = PurchasingOrder::find($id);
        VendorPaymentAdjustments::where('purchasing_order_id', $id)
                ->where('status', 'pending')
                ->update(['status' => 'Approved']);
        PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
                ->where('status', 'Suggested Payment')
                ->update(['status' => 'Approved']);
        SupplierAccountTransaction::where('purchasing_order_id', $id)
        ->where('status', 'pending')
        ->where('transaction_type', 'Pre-Debit')
        ->update([
            'status' => 'Approved',
            'remarks' => 'Approved For Released Payment'
        ]);
        }
    elseif ($status == 'Rejected') {
        PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Suggested Payment')->delete();
        VendorPaymentAdjustments::where('purchasing_order_id', $id)->where('status', 'Pending')->delete();
    }
    foreach ($vehicles as $vehicle) {
        if($vehicle->remaining_payment_status == 'Payment Initiated')
        {
            if ($status == 'Approved') {
                $paymentStatus = 'Payment Release Approved';
                $updateData = ['remaining_payment_status' => $paymentStatus,
                'procurement_vehicle_remarks' => null
            ];
            } elseif ($status == 'Rejected') {
                $paymentStatus = 'Payment Release Rejected';
                $updateData = [
                    'remaining_payment_status' => $paymentStatus,
                    'procurement_vehicle_remarks' => $remarks
                ];
            }
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update($updateData);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Status';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = 'Remanining Payment Status';
            $vehicleslog->old_value = 'Payment Initiated';
            $vehicleslog->new_value = 'Payment Relased';
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        }
        else
        {
        if ($status == 'Approved') {
                $paymentStatus = 'Payment Release Approved';
                $updateData = ['payment_status' => $paymentStatus,
                'procurement_vehicle_remarks' => null
            ];
            } elseif ($status == 'Rejected') {
                $paymentStatus = 'Payment Release Rejected';
                $updateData = [
                    'payment_status' => $paymentStatus,
                    'procurement_vehicle_remarks' => $remarks
                ];
            }
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update($updateData);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Status';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = 'Payment Status';
            $vehicleslog->old_value = 'Payment Initiated';
            $vehicleslog->new_value = $paymentStatus;
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            if($vehicle->model_id) {
                $approvedIds = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                                    ->pluck('approved_loi_id');

                $loiItemIds = ApprovedLetterOfIndentItem::whereIn('id', $approvedIds)->pluck('letter_of_indent_item_id');
                // info($loiItemIds);
                $masterModel = MasterModel::find($vehicle->model_id);
                $possibleIds = MasterModel::where('model', $masterModel->model)
                    ->where('sfx', $masterModel->sfx)->pluck('id')->toArray();
                // info($possibleIds);

                foreach ($loiItemIds as $loiItemId) {
                    $item = LetterOfIndentItem::find($loiItemId);
                    // info($item);
                    if(in_array($item->master_model_id, $possibleIds)) {
                        // info("master model id including li item");
                        if($item->utilized_quantity < $item->approved_quantity) {
                            // info("total quantity < utilized_quantity");
                            $item->utilized_quantity = $item->utilized_quantity + 1;
                            $item->save();

                              // get the total utilized qty and update against LOI
                            // $LOI = LetterOfIndent::find($item->letter_of_indent_id);
                            // $utilized_quantity =  LetterOfIndentItem::where('letter_of_indent_id', $LOI->id)
                            //                         ->sum('utilized_quantity');
                            // $LOI->utilized_quantity  = $utilized_quantity;
                            // $LOI->save();
                           
                            break;
                        }
                    }
                }
            }
        }
    }
    return redirect()->back()->with('success', 'Payment Status Updated');
}
public function allpaymentreqss(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
    ->where('purchasing_order_id', $id)
    ->where('status', 'Approved')
    ->where('payment_status', '')
    ->get();
    foreach ($vehicles as $vehicle) {
        $status = 'Request for Payment';
        DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['status' => $status]);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Request for Payment';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Vehicle Status";
                $vehicleslog->old_value = "Not Paid";
                $vehicleslog->new_value = "Request for Initiate Payment";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
    }
     return redirect()->back()->with('success', 'Payment Status Updated');
}
public function allpaymentreqssfin(Request $request)
{
    $percentage =  $request->input('percentage');
    $id = $request->input('orderId');
    $status = $request->input('status');
    if($status == "Approved")
    {
        $vehicles = DB::table('vehicles')
        ->where('purchasing_order_id', $id)
        ->where('status', 'Request for Payment')
        ->where('payment_status', '')
        ->get();
        $totalCost = 0;
        foreach ($vehicles as $vehicle) {
            $status = 'Payment Requested';
            $payment_status = 'Payment Initiated Request';
            DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['status' => $status, 'payment_status' => $payment_status]);
            $vehicleCost = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
            $adjustedCost = $vehicleCost * ($percentage / 100);
            $totalCost +=  $adjustedCost;
            $currentPercentage = DB::table('vehicles')->where('id', $vehicle->id)->value('purchased_paid_percentage');
            $currentPercentage = $currentPercentage ?? 0; // Treat null as 0
            $newPercentage = $currentPercentage + $percentage;
            DB::table('vehicles')
            ->where('id', $vehicle->id)
            ->update(['purchased_paid_percentage' => $newPercentage]);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Request';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated Request";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        }
        $paymentinti = New PurchasedOrderPaidAmounts();
        $paymentinti->amount = $totalCost;
        $paymentinti->purchasing_order_id = $id;
        $paymentinti->percentage = $percentage;
        $paymentinti->created_by = auth()->user()->id;
        $paymentinti->status = "Request For Payment";
        $paymentinti->save();
     }
    else
    {
        $vehicles = DB::table('vehicles')
        ->where('purchasing_order_id', $id)
        ->where('status', 'Request for Payment')
        ->where('payment_status', '')
        ->get();
        foreach ($vehicles as $vehicle) {
            $status = 'Approved';
            $payment_status = Null;
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['status' => $status, 'payment_status' => $payment_status]);
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Payment Initiated Request Rejected';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Vehicle Status, Payment Status";
                $vehicleslog->old_value = "Request for Initiate Payment";
                $vehicleslog->new_value = "Payment Initiated Request Rejected";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
        }   
    }
     return redirect()->back()->with('success', 'Payment Status Updated');
}
public function allpaymentreqssfinpay(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
        ->where('purchasing_order_id', $id)
        ->where(function($query) {
            $query->where('status', 'Payment Requested')
                  ->where('payment_status', 'Payment Initiated Request');
        })
        ->orWhere('remaining_payment_status', 'Payment Requested')
        ->get();
    foreach ($vehicles as $vehicle) {
        $status = 'Payment Requested';
        $payment_status = 'Payment Initiated';
        if(isset($vehicle->remaining_payment_status) && $vehicle->remaining_payment_status == "Payment Requested") {
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['remaining_payment_status' => 'Payment Initiated']);
        } else {
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['status' => $status, 'payment_status' => $payment_status]);
        }
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Payment Initiated';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Vehicle Status, Payment Status";
                $vehicleslog->old_value = "Payment Initiate Request Approved";
                $vehicleslog->new_value = "Payment Initiated";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
            }
            $purchasedorder = PurchasingOrder::where('id', $id)->first();
            $selectedOption =  $request->input('selectedOption');
            $adjustmentAmount =  $request->input('adjustmentAmount');
            $remainingAmount = $request->input('remainingAmount');
            $intialamount = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)->where('status', 'Request For Payment')->sum('amount');
            if($selectedOption == 'adjustment')
            {
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $adjustmentAmount;
                $VendorPaymentAdjustments->type = "Adjustment";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $adjustmentAmount + $remainingAmount;
                $VendorPaymentAdjustments->remaining_amount = $remainingAmount;
                $VendorPaymentAdjustments->save();
                $VendorPaymentAdjustmentsid = $VendorPaymentAdjustments->id;
                $totalcost = $intialamount;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount + $remainingAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
                $paidaccountid = $paidaccount->id;
                $description = "Adjustment the payment";
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Adjustment";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $id;
                $purchasingordereventsLog->field = "Price";
                $purchasingordereventsLog->new_value = $adjustmentAmount + $remainingAmount;
                $purchasingordereventsLog->description = $description;
                $purchasingordereventsLog->save();
            }
            elseif($selectedOption == 'payBalance')
            {
                $supplier = SupplierAccount::where('suppliers_id', $purchasedorder->vendors_id)->first();
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $adjustmentAmount - $intialamount;
                $VendorPaymentAdjustments->type = "Pay Balance";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $adjustmentAmount;
                $VendorPaymentAdjustments->remaining_amount = $intialamount;
                $VendorPaymentAdjustments->save();
                $VendorPaymentAdjustmentsid = $VendorPaymentAdjustments->id;
                $totalcost = $intialamount;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
                $paidaccountid = $paidaccount->id;
                $description = "Pay the Balance of with this PO";
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Adjustment";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $id;
                $purchasingordereventsLog->field = "Price";
                $purchasingordereventsLog->new_value = $adjustmentAmount;
                $purchasingordereventsLog->description = $description;
                $purchasingordereventsLog->save();
            }
            elseif($selectedOption == 'partialpayment')
            {
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $intialamount;
                $VendorPaymentAdjustments->type = "Partial Payment";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $adjustmentAmount;
                $VendorPaymentAdjustments->remaining_amount = $intialamount - $adjustmentAmount;
                $VendorPaymentAdjustments->save();
                $VendorPaymentAdjustmentsid = $VendorPaymentAdjustments->id;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
                $paidaccountid = $paidaccount->id;
                $totalcost = $intialamount;
                $description = "Partial Payment to the Vendor";
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Adjustment";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $id;
                $purchasingordereventsLog->field = "Price";
                $purchasingordereventsLog->new_value = $adjustmentAmount;
                $purchasingordereventsLog->description = $description;
                $purchasingordereventsLog->save();
            }
            else
            {
                $VendorPaymentAdjustments = New VendorPaymentAdjustments();
                $VendorPaymentAdjustments->amount = $intialamount;
                $VendorPaymentAdjustments->type = "No Adjustment";
                $VendorPaymentAdjustments->supplier_account_id = $purchasedorder->vendors_id;
                $VendorPaymentAdjustments->purchasing_order_id = $id;
                $VendorPaymentAdjustments->created_by = auth()->user()->id;
                $VendorPaymentAdjustments->totalamount = $intialamount;
                $VendorPaymentAdjustments->save();
                $VendorPaymentAdjustmentsid = $VendorPaymentAdjustments->id;
                $totalcost = $intialamount;
                $adjustmentAmount = $intialamount;
                $paidaccount = New PurchasedOrderPaidAmounts();
                $paidaccount->amount = $adjustmentAmount;
                $paidaccount->created_by = auth()->user()->id;
                $paidaccount->purchasing_order_id = $purchasedorder->id;
                $paidaccount->status = "Suggested Payment";
                $paidaccount->save();
                $paidaccountid = $paidaccount->id;
                $description = "Payment to the vendor without any adjustment";
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Adjustment";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $id;
                $purchasingordereventsLog->field = "Price";
                $purchasingordereventsLog->new_value = $adjustmentAmount;
                $purchasingordereventsLog->description = $description;
                $purchasingordereventsLog->save();
            }
            $paymentOrderStatus = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
    ->where('status', 'Request For Payment')
    ->get();

if ($paymentOrderStatus->isNotEmpty()) {
    foreach ($paymentOrderStatus as $paymentOrder) {
        $paymentOrder->status = 'Initiated Payment';
        $paymentOrder->save();
    }
    // info($paymentOrderStatus);
}
            $currency = $purchasedorder->currency;
            $supplieraccountchange = SupplierAccount::where('suppliers_id', $purchasedorder->vendors_id)->first();
            if (!$supplieraccountchange) {
            $supplieraccountchange = new SupplierAccount();
            $supplieraccountchange->suppliers_id = $purchasedorder->vendors_id;
            $supplieraccountchange->current_balance -= $totalcost;
            $supplieraccountchange->currency = $purchasedorder->currency;
            $supplieraccountchange->opening_balance = 0;
            $supplieraccountchange->save();
            }
        else{
        if($totalcost != 0)
        {
                switch ($currency) {
            case "USD":
                $totalcostconverted = $totalcost * 3.67;
                break;
            case "AUD":
                    $totalcostconverted = $totalcost * 2.29;
                    break;
            case "EUR":
                $totalcostconverted = $totalcost * 3.94;
                break;
            case "GBP":
                $totalcostconverted = $totalcost * 4.67;
                break;
            case "JPY":
                $totalcostconverted = $totalcost * 0.023;
                break;
            case "CAD":
                $totalcostconverted = $totalcost * 2.68;
                break;
            case "PHP":
                $totalcostconverted = $totalcost * 0.063;
                break;
            case "SAR":
                $totalcostconverted = $totalcost * 0.98;
                break;
            default:
                $totalcostconverted = $totalcost;
                }
                $supplieraccountchange->current_balance -= $totalcostconverted;
                $supplieraccountchange->save();
                }
                $supplieraccount = new SupplierAccountTransaction();
                $supplieraccount->transaction_type = "Pre-Debit";
                $supplieraccount->purchasing_order_id = $purchasedorder->id;
                $supplieraccount->supplier_account_id = $supplieraccountchange->id;
                $supplieraccount->created_by = auth()->user()->id;
                $supplieraccount->account_currency = $currency;
                $supplieraccount->transaction_amount = $totalcost;
                $supplieraccount->save();
                $supplieraccountid = $supplieraccount->id;
                foreach ($vehicles as $vehicle) {
                    $updatevehicle = New VehiclesSupplierAccountTransaction();
                    $updatevehicle->vehicles_id =  $vehicle->id;
                    $updatevehicle->sat_id = $supplieraccountid;
                    $updatevehicle->vpa_id = $VendorPaymentAdjustmentsid;
                    $updatevehicle->popa_id = $paidaccountid;
                    $updatevehicle->save();
                }
            }
                    return redirect()->back()->with('success', 'Payment Status Updated');
       }
       public function allpaymentreqssfinpaycomp(Request $request)
       {
           $id = $request->input('orderId');
           $status = $request->input('status');
           $vehicles = DB::table('vehicles')
        ->where('purchasing_order_id', $id)
        ->where(function($query) {
            $query->where('status', 'Payment Requested')
                  ->where('payment_status', 'Payment Release Approved');
        })
        ->orWhere('remaining_payment_status', 'Payment Release Approved')
        ->get();
           $vehicleCount = $vehicles->count();
           if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/swift_copies'), $fileNameToStore);            
            $latestBatch = DB::table('purchasing_order_swift_copies')
                ->where('purchasing_order_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;
            $swiftcopy = new PurchasingOrderSwiftCopies();
            $swiftcopy->purchasing_order_id = $id;
            $swiftcopy->uploaded_by = auth()->user()->id;
            $swiftcopy->number_of_vehicles = $vehicleCount;
            $swiftcopy->batch_no = $batchNo;
            $swiftcopy->file_path = 'storage/swift_copies/' . $fileNameToStore;
            $swiftcopy->save();
            $PurchasingOrder = PurchasingOrder::where('id', $id)->first();
            $supplieracc = SupplierAccount::where('suppliers_id', $PurchasingOrder->vendors_id)->first();
        if ($supplieracc) {
            $paymentad = PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
                ->where('status', 'Approved')
                ->sum('amount');
            $supplieracc->current_balance += $paymentad;
            $supplieracc->save();
            PurchasedOrderPaidAmounts::where('purchasing_order_id', $id)
                ->where('status', 'Approved')
                ->update(['status' => 'Paid']);
            VendorPaymentAdjustments::where('purchasing_order_id', $id)
                ->where('status', 'Approved')
                ->update(['status' => 'Paid']);
            if($paymentad != 0)
            {
            $supplieraccount = new SupplierAccountTransaction();
            $supplieraccount->transaction_type = "Debit";
            $supplieraccount->purchasing_order_id = $id;
            $supplieraccount->supplier_account_id = $supplieracc->id;
            $supplieraccount->created_by = auth()->user()->id;
            $supplieraccount->account_currency = $PurchasingOrder->currency;
            $supplieraccount->transaction_amount = $paymentad;
            $supplieraccount->save();
            }
        }
        }        
           foreach ($vehicles as $vehicle) {
            if($vehicle->remaining_payment_status == 'Payment Release Approved')
            {
                $remaining_payment_status = 'Payment Completed';
                DB::table('vehicles')
                    ->where('id', $vehicle->id)
                    ->update(['remaining_payment_status' => $remaining_payment_status]);
            }
            else
            {
               $status = 'Payment Completed';
               $payment_status = 'Payment Completed';
               DB::table('vehicles')
                   ->where('id', $vehicle->id)
                   ->update(['status' => $status, 'payment_status' => $payment_status]);
            }
                   $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                   $currentDateTime = Carbon::now($dubaiTimeZone);
                       $vehicleslog = new Vehicleslog();
                       $vehicleslog->time = $currentDateTime->toTimeString();
                       $vehicleslog->date = $currentDateTime->toDateString();
                       $vehicleslog->status = 'Payment Completed';
                       $vehicleslog->vehicles_id = $vehicle->id;
                       $vehicleslog->field = "Vehicle Status, Payment Status";
                       $vehicleslog->old_value = "Payment Release Approved";
                       $vehicleslog->new_value = "Payment Completed";
                       $vehicleslog->created_by = auth()->user()->id;
                       $vehicleslog->role = Auth::user()->selectedRole;
                       $vehicleslog->save();
                       $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                       $currentDateTime = Carbon::now($dubaiTimeZone);
                       $paymentlogs = new PaymentLog();
                       $paymentlogs->date = $currentDateTime->toDateString();
                       $paymentlogs->vehicle_id = $vehicle->id;
                       $paymentlogs->created_by = auth()->user()->id;
                       $paymentlogs->save();
                   }
                   return redirect()->back()->with('success', 'Payment Status Updated');
              }
              public function allpaymentintreqpocomp(Request $request)
              {
                  $id = $request->input('orderId');
                  $status = $request->input('status');
                  $vehicles = DB::table('vehicles')
                  ->where('purchasing_order_id', $id)
                  ->where('status', 'Payment Completed')
                  ->where('payment_status', 'Payment Completed')
                  ->get();
                  foreach ($vehicles as $vehicle) {
                    $status = 'Incoming Stock';
                    $payment_status = 'Incoming Stock';
                      DB::table('vehicles')
                          ->where('id', $vehicle->id)
                          ->update(['status' => $status, 'payment_status' => $payment_status]);
                          $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Vendor Confirmed';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Payment Completed";
            $vehicleslog->new_value = "Incoming Stock";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
                          }
                          return redirect()->back()->with('success', 'Payment Status Updated');
       }
         public function allpaymentintreqpocompin(Request $request)
         {
             $id = $request->input('orderId');
             $status = $request->input('status');
             $vehicles = DB::table('vehicles')
             ->where('purchasing_order_id', $id)
             ->where('status', 'Vendor Confirmed')
             ->where('payment_status', 'Vendor Confirmed')
             ->get();
             foreach ($vehicles as $vehicle) {
                 $status = 'Incoming Stock';
                 $payment_status = 'Incoming Stock';
                 DB::table('vehicles')
                     ->where('id', $vehicle->id)
                     ->update(['status' => $status, 'payment_status' => $payment_status]);
                     $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                       $currentDateTime = Carbon::now($dubaiTimeZone);
                       $vehicleslog = new Vehicleslog();
                       $vehicleslog->time = $currentDateTime->toTimeString();
                       $vehicleslog->date = $currentDateTime->toDateString();
                       $vehicleslog->status = 'Incoming Stock';
                       $vehicleslog->vehicles_id = $vehicle->id;
                       $vehicleslog->field = "Vehicle Status, Payment Status";
                       $vehicleslog->old_value = "Vendor Confirmed";
                       $vehicleslog->new_value = "Incoming Stock";
                       $vehicleslog->created_by = auth()->user()->id;
                       $vehicleslog->role = Auth::user()->selectedRole;
                       $vehicleslog->save();
                  }
                     return redirect()->back()->with('success', 'Payment Status Updated');
                }
       public function approvedcancel($id)
                            {
            $vehicle = Vehicles::findOrFail($id);
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'Vehicle Cancel';
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->purchasing_order_id = $vehicle->purchasing_order_id;
            $purchasinglog->variant = $vehicle->varaints_id;
            $purchasinglog->estimation_date = $vehicle->estimation_date;
            $purchasinglog->territory = $vehicle->territory;
            $purchasinglog->int_colour = $vehicle->int_colour;
            $purchasinglog->ex_colour = $vehicle->ex_colour;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->save();
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = now()->toTimeString();
            $vehicleslog->date = now()->toDateString();
            $vehicleslog->status = 'Vehicle Cancel';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Status";
            $vehicleslog->old_value = $vehicle->status;
            $vehicleslog->new_value = 'Vehicle Cancel';
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $updateqty = PurchasingOrderItems::where('variant_id', $vehicle->varaints_id)
                ->where('purchasing_order_id', $vehicle->purchasing_order_id)
                ->first();
            if($updateqty)
            {
                $updateqty->qty = intval($updateqty->qty) - 1;
                $updateqty->save();
            }
            $updateprice = VehiclePurchasingCost::where('vehicles_id', $id)->first();
            if($updateprice)
            {
            $updatetotal = PurchasingOrder::find($vehicle->purchasing_order_id);
            $updatetotal->totalcost = $updatetotal->totalcost - $updateprice->unit_price;
            $updatetotal->save();
            }
                if($vehicle->model_id) {
                    $masterModel = MasterModel::find($vehicle->model_id);
                    $possibleModelIds = MasterModel::where('model', $masterModel->model)
                                            ->where('sfx', $masterModel->sfx)->pluck('id');
                    $inventoryItem = SupplierInventory::where('purchase_order_id', $vehicle->purchasing_order_id)
                                            ->whereIn('master_model_id', $possibleModelIds)
                                            ->first();
                    $inventoryItem->purchase_order_id = NULL;
                    $inventoryItem->pfi_id = NULL;
                    $inventoryItem->letter_of_indent_item_id  = NULL;
                    $inventoryItem->save();

                    $purchaseOrderItem = PurchasingOrderItems::where('purchasing_order_id', $vehicle->purchasing_order_id)
                                                ->where('variant_id', $vehicle->varaints_id)->first();
                    if($purchaseOrderItem) {
                        $purchaseOrderItem->qty = $purchaseOrderItem->qty - 1;
                        $purchaseOrderItem->save();
                    }

                    $loiPurchaseOrder = LOIItemPurchaseOrder::where('purchase_order_id', $vehicle->purchasing_order_id)
                        ->where('master_model_id', $vehicle->model_id)
                        ->first();
                    $loiPurchaseOrder->quantity = $loiPurchaseOrder->quantity - 1;
                    $loiPurchaseOrder->save();
                }
            $vehicle->delete();
            return redirect()->back()->with('success', 'Vehicle cancellation request submitted successfully.');
        }
public function updatebasicdetails(Request $request)
{

  info("update basci details");
    $purchasingOrder = PurchasingOrder::find($request->input('purchasing_order_id'));
    if (!$purchasingOrder) {
        return response()->json(['error' => 'Purchasing order not found'], 404);
    }

    // Define the fields to be updated
    $fieldsToUpdate = [
        'vendors_id',
        'payment_term_id',
        'currency',
        'shippingmethod',
        'shippingcost',
        'pol',
        'pod',
        'fd',
        'pl_number',
        'po_number',
    ];
    // Store old values
    $oldValues = $purchasingOrder->only($fieldsToUpdate);
    // Update purchasing order details
    foreach ($fieldsToUpdate as $field) {
        if ($request->has($field)) {
            $purchasingOrder->$field = $request->input($field);
        }
    }
    $purchasingOrder->status = "Pending Approval";
    $changedFields = [];
    if ($request->hasFile('uploadPL')) {
        if($purchasingOrder->pl_file_path)
        {
        $oldpl = New Purchasedorderoldplfiles(); 
        $oldpl->purchasing_order_id = $purchasingOrder->id;
        $oldpl->file_path = $purchasingOrder->pl_file_path;
        $oldpl->save();
        }
        $description = "The PFI Document is Updated";
        $purchasingordereventsLog = new PurchasingOrderEventsLog();
        $purchasingordereventsLog->event_type = "Update PO PFI Document";
        $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
        $purchasingordereventsLog->created_by = auth()->user()->id;
        $purchasingordereventsLog->field = "PFI Document";
        $purchasingordereventsLog->description = $description;
        $purchasingordereventsLog->save();
        // Get file with extension
        $fileNameWithExt = $request->file('uploadPL')->getClientOriginalName();
        // Get just the filename
        $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $filename = Str::slug($filename);
     
        // Get just the extension
        $extension = $request->file('uploadPL')->getClientOriginalExtension();
        // Create a unique filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        // Move the file to the public storage path
        $path = $request->file('uploadPL')->move(public_path('storage/PL_Documents'), $fileNameToStore);
        // Update the file path in the purchasing order
        $purchasingOrder->pl_file_path = 'storage/PL_Documents/' . $fileNameToStore;
       
    }
    // Save the purchasing order
    $purchasingOrder->save();
    // Log changes
    foreach ($fieldsToUpdate as $field) {
        if ($oldValues[$field] != $purchasingOrder->$field) {
            $oldValue = $oldValues[$field];
            $newValue = $purchasingOrder->$field;
            $description = "Changed $field from $oldValue to $newValue";

            if ($field == 'vendors_id') {
                $oldVendorName = Supplier::find($oldValue)->supplier ?? 'Unknown';
                $newVendorName = Supplier::find($newValue)->supplier ?? 'Unknown';
                $description = "Changed Vendor from $oldVendorName to $newVendorName";
                $oldValue = $oldVendorName;
                $newValue = $newVendorName;
            } elseif ($field == 'payment_term_id') {
                $oldPaymentTerm = PaymentTerms::find($oldValue)->name ?? 'Unknown';
                $newPaymentTerm = PaymentTerms::find($newValue)->name ?? 'Unknown';
                $description = "Changed Payment Term from $oldPaymentTerm to $newPaymentTerm";
                $oldValue = $oldPaymentTerm;
                $newValue = $newPaymentTerm;
            }
            elseif ($field == 'pol') {
                $oldport = MasterShippingPorts::find($oldValue)->name ?? 'Unknown';
                $newport = MasterShippingPorts::find($newValue)->name ?? 'Unknown';
                $description = "Changed Port of Loading from $oldport to $newport";
                $oldValue = $oldport;
                $newValue = $newport;
            }
            elseif ($field == 'pod') {
                $oldport = MasterShippingPorts::find($oldValue)->name ?? 'Unknown';
                $newport = MasterShippingPorts::find($newValue)->name ?? 'Unknown';
                $description = "Changed Port of Delivery from $oldport to $newport";
                $oldValue = $oldport;
                $newValue = $newport;
            }
            elseif ($field == 'fd') {
                $oldport = Country::find($oldValue)->name ?? 'Unknown';
                $newport = Country::find($newValue)->name ?? 'Unknown';
                $description = "Changed Final Delivery from $oldport to $newport";
                $oldValue = $oldport;
                $newValue = $newport;
            }
            $changedFields[] = [
                'field' => ucfirst(str_replace('_', ' ', $field)),
                'old_value' => $oldValue,
                'new_value' => $newValue,
            ];
            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Update PO Basic Details";
            $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->field = ucfirst(str_replace('_', ' ', $field)) . " Changed";
            $purchasingordereventsLog->old_value = $oldValue;
            $purchasingordereventsLog->new_value = $newValue;
            $purchasingordereventsLog->description = $description;
            $purchasingordereventsLog->save();
        }
    }
    if (!empty($changedFields)) {
    if($purchasingOrder->is_demand_planning_po == 1)
    {
        $recipients = config('mail.custom_recipients.dp');
    }
    else
    {
        $recipients = config('mail.custom_recipients.cso');
    }
    $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
    Mail::to($recipients)->send(new PurchaseOrderUpdated($purchasingOrder->po_number, $purchasingOrder->pl_number, $changedFields, $orderUrl));
    $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
    "PFI Number: " . $purchasingOrder->pl_number . "\n" .
    "Stage: " . "Changing Into Purchased Order\n" .
    "Order URL: " . $orderUrl . "\n\n" .
    "Changed Fields:\n";

foreach ($changedFields as $changedField) {
$detailText .= $changedField['field'] . ": From '" . $changedField['old_value'] . "' to '" . $changedField['new_value'] . "'\n";
}
// Save the notification
$notification = new DepartmentNotifications();
$notification->module = 'Procurement';
$notification->type = 'Information';
$notification->detail = $detailText;
$notification->save();
if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
}
    return response()->json(['message' => 'Purchase order details updated successfully'], 200);
}       
        public function pendingvins($status)
{
    $bankaccounts = BankAccounts::get();
        $exchangeRates = [
            'USD' => 3.67,
            'EUR' => 4.03,
            'JPY' => 0.023,
            'CAD' => 2.89,
            'AED' => 1,
            'PHP' => 0.063,
            'SAR' => 0.98
        ];
        $totalBalanceAED = $bankaccounts->reduce(function ($carry, $account) use ($exchangeRates) {
            return $carry + ($account->current_balance * $exchangeRates[$account->currency]);
        }, 0);
        $suggestedPayments = PurchasedOrderPaidAmounts::where('status', 'Suggested Payment')->get();
        $suggestedPaymentTotalAED = $suggestedPayments->reduce(function ($carry, $payment) use ($exchangeRates) {
            $purchasingOrder = $payment->purchasingOrder;
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $amountInAED = $payment->amount * ($exchangeRates[$currency] ?? 1);
                return $carry + $amountInAED;
            }
            return $carry;
        }, 0);
        $suggestedPaymentTotalUSD = $suggestedPaymentTotalAED / 3.67;
        $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
        $availableFundsUSD = $availableFunds / 3.67;
$userId = auth()->user()->id;
$hasPermission = Auth::user()->hasPermissionForSelectedRole('view-all-department-pos');
if ($hasPermission){
$data = PurchasingOrder::with('purchasing_order_items')
    ->where('created_by', '!=', '16')
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('vehicles')
              ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
              ->whereNull('deleted_at')
              ->whereNull('vin'); // Check for at least one VIN being null
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
else
{
    $data = PurchasingOrder::with('purchasing_order_items')
    ->where('created_by', '!=', '16')
    // ->where('created_by', $userId)->orWhere('created_by', 16)
    ->whereExists(function ($query) {
        $query->select(DB::raw(1))
              ->from('vehicles')
              ->whereColumn('purchasing_order.id', '=', 'vehicles.purchasing_order_id')
              ->whereNull('deleted_at')
              ->whereNull('vin'); // Check for at least one VIN being null
    })
    ->groupBy('purchasing_order.id')
    ->get();
}
return view('warehouse.index', compact('data', 'availableFunds', 'suggestedPaymentTotalAED', 'availableFundsUSD', 'suggestedPaymentTotalUSD'));
}
public function rerequestpayment(Request $request)
{
    $id = $request->input('orderId');
    $status = $request->input('status');
    $vehicles = DB::table('vehicles')
        ->where('purchasing_order_id', $id)
        ->where(function($query) {
            $query->where('status', 'Payment Rejected')
                  ->orWhere('remaining_payment_status', 'Payment Rejected')
                  ->orWhere('payment_status', 'Payment Release Rejected');
        })
        ->get();
    // info($vehicles);
    foreach ($vehicles as $vehicle) {
        if ($vehicle->payment_status == 'Payment Release Rejected') {
            $status = 'Payment Requested';
            $payment_status = 'Payment Initiated Request';
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['status' => $status, 'payment_status' => $payment_status]);
        } else {
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['remaining_payment_status' => 'Payment Requested']);
        }
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Re Payment Initiated';
        $vehicleslog->vehicles_id = $vehicle->id;
        $vehicleslog->field = "Vehicle Status, Payment Status";
        $vehicleslog->old_value = "Payment Re Initiate Request";
        $vehicleslog->new_value = $vehicle->status == 'Payment Rejected' ? "Payment Release Rejected" : "Payment Requested";
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
    }

    return redirect()->back()->with('success', 'Payment Status Updated');
}
       public function repaymentintiation($id)
       {
           $vehicle = Vehicles::find($id);
           if ($vehicle) {
               $vehicle->status = 'Payment Requested';
               $vehicle->payment_status = 'Payment Initiated Request';
               $vehicle->save();
               $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
               $currentDateTime = Carbon::now($dubaiTimeZone);
                   $vehicleslog = new Vehicleslog();
                   $vehicleslog->time = $currentDateTime->toTimeString();
                   $vehicleslog->date = $currentDateTime->toDateString();
                   $vehicleslog->status = 'Payment Re Initiated Request';
                   $vehicleslog->vehicles_id = $id;
                   $vehicleslog->field = "Vehicle Status, Payment Status";
                   $vehicleslog->old_value = "Payment Released Rejected";
                   $vehicleslog->new_value = "Payment Re Initiated Request";
                   $vehicleslog->created_by = auth()->user()->id;
                   $vehicleslog->role = Auth::user()->selectedRole;
                   $vehicleslog->save();
               return redirect()->back()->with('success', 'Payment Initiated Request confirmed. Vehicle status updated.');
           }
           return redirect()->back()->with('error', 'Vehicle not found.');
       }
       public function cancelpo(Request $request, $id)
    {
        $purchasingOrder = PurchasingOrder::find($id);
        if ($purchasingOrder) {
            $purchasingOrder->status = 'Cancel Request';
            $purchasingOrder->remarks = $request->input('remarks');
            $purchasingOrder->save();
            $purchasinglog = new Purchasinglog();
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'PO Cancelled Request';
            $purchasinglog->purchasing_order_id = $id;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->save();
            // Respond with a JSON object indicating success and redirect URL
            return response()->json([
                'success' => true,
                'redirectUrl' => route('purchasing-order.index')
            ]);
        }
        // Respond with an error if the purchasing order was not found
        return response()->json([
            'success' => false,
            'message' => 'Purchasing order not found.'
        ], 404);
    }
    public function purchasingupdateStatuscancel(Request $request)
    {
        $useractivities =  New UserActivities();
        $useractivities->activity = "Cancel Purchasing Order";
        $useractivities->users_id = Auth::id();
        $useractivities->save();
        $id = $request->input('orderId');
        $status = $request->input('status');
        $purchasingOrder = PurchasingOrder::find($id);
        if($status == "Rejected")
        {
            $purchasingOrder->status = "Approved";
            $purchasingOrder->save();
        }
        else
        {
            $purchasingOrder->status = "Cancelled";
            $purchasingOrder->save();
            $purchasinglog = new Purchasinglog();
            $purchasinglog->time = now()->toTimeString();
            $purchasinglog->date = now()->toDateString();
            $purchasinglog->status = 'PO Cancelled';
            $purchasinglog->role = Auth::user()->selectedRole;
            $purchasinglog->purchasing_order_id = $id;
            $purchasinglog->created_by = auth()->user()->id;
            $purchasinglog->save();
            $vehicles = Vehicles::where('purchasing_order_id', $id)->get();
            foreach ($vehicles as $vehicle) {
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = now()->toTimeString();
                $vehicleslog->date = now()->toDateString();
                $vehicleslog->status = 'Vehicle Cancel';
                $vehicleslog->vehicles_id = $id;
                $vehicleslog->field = "Status";
                $vehicleslog->old_value = $vehicle->status;
                $vehicleslog->new_value = 'Vehicle Cancel';
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                $vehicle->delete();   
            }
        }
        return response()->json([
            'success' => true,
            'redirectUrl' => route('purchasing-order.index')
        ]);
    }
    public function paymentintconfirmrej($id)
{
    $vehicle = Vehicles::find($id);
    if ($vehicle) {
        $vehicle->status = 'Approved';
        $vehicle->payment_status = Null;
        $vehicle->save();
        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
        $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Payment Initiated Request Rejected';
            $vehicleslog->vehicles_id = $id;
            $vehicleslog->field = "Vehicle Status, Payment Status";
            $vehicleslog->old_value = "Request for Initiate Payment";
            $vehicleslog->new_value = "Payment Initiated Request Rejected";
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
        return redirect()->back()->with('success', 'Payment Initiated Request Rejected confirmed');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
}
public function getSupplierAndAmount($orderId) {
    $order = PurchasingOrder::find($orderId);
    if ($order) {
        $vendors_id = $order->vendors_id;
        $supplier = SupplierAccount::where('suppliers_id', $vendors_id)->first();
        $requestedcost =PurchasedOrderPaidAmounts::where('purchasing_order_id', $orderId)->where('status', 'Request For Payment')->sum('amount');
        $current_amount = $supplier->current_balance;
        $totalamount = $order->totalcost;
        $requestedcost = $requestedcost;
        return response()->json(['supplier_id' => $vendors_id, 'current_amount' => $current_amount, 'totalamount' => $totalamount, 'requestedcost' => $requestedcost]);
    }
    return response()->json(['error' => 'Order not found'], 404);
}
public function vehiclesdatagetting($id)
{
    $vehicles = Vehicles::where('purchasing_order_id', $id)->whereNull('deleted_at')->get();
    $vehicleData = [];
    foreach ($vehicles as $vehicle) {
        $price = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
        $vehicleData[] = [
            'vehicle_id' => $vehicle->id,
            'vin' => $vehicle->vin,
            'price' => number_format($price, 2, '.', ''),
        ];
    }
    return response()->json($vehicleData);
}
public function updatePOPrices(Request $request) {
    info("reached");
    info($request->all());
    // identify which item has price change
    try { 

        DB::beginTransaction();
            $pfiItems = $request->pfiItems;
            $prices = [];
            foreach($pfiItems as $key => $pfiItem) {
                $pfiItemId = $pfiItem[$key]->parent_pfi_item_id;
                $ParentPfiItem = PfiItem::find($pfiItemId);
            
                if($ParentPfiItem->unit_price != $pfiItem[$key]->unit_price) {
                    // add all vehcileId and Price to array
                    // get the model and sfx Ids and get vehicles
                    // PoPfiItem 
                    $PoPfiItem = PfiItemPurchaseOrder::where('purchase_order_id', $request->purchase_order_id)
                    ->where('pfi_item_id', $pfiItemId)
                    ->first();
            
                    // $possibleModelIds = MasterModel::where('model', $POpfiItem->model)
                    //                     ->where('sfx', $POpfiItem->sfx)->pluck('id')->toArray();
                    if($POpfiItem) {
                        $qty = $POpfiItem->quantity;
                        $vehicles = Vehicles::where('purchasing_order_id', $request->purchase_order_id)
                                        ->where('model_id', $PoPfiItem->master_model_id)   
                                        ->orderBy('id','DESC')    
                                        ->take($qty)
                                        ->pluck('id')->toArray(); 
                        foreach($vehicles as $vehicle) {
                            $prices['vehicle_id'] = $vehicle;
                            $prices['new_price'] = $pfiItem->unit_price; 
                        }                     
                    }
                }
            }
            info($prices);
        DB::commit();

        return response()->json(['message' => 'Price Updated successfully'], 200);
        } catch (Exception $e) { // Catch any exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }

}
public function updatePrices(Request $request)
{
    $prices = $request->input('prices');
    $totalPrice = round((float) $request->input('total_price'), 2);
    $purchasingOrderId = $request->input('purchasing_order_id');
    $userId = auth()->id();
    $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
    $orderCurrency = $purchasingOrder->currency;
    $supplierAccount = SupplierAccount::where('suppliers_id', $purchasingOrder->vendors_id)->first();

    if(!$supplierAccount) {
        $createsupplieracc = new SupplierAccount();
        $createsupplieracc->opening_balance = 0;
        $createsupplieracc->current_balance = 0;
        $createsupplieracc->suppliers_id = $purchasingOrder->vendors_id;
        $createsupplieracc->currency = $orderCurrency; 
        $createsupplieracc->save();
        $supplierAccount = $createsupplieracc;
    }

    $accountCurrency = $supplierAccount->currency;

    $conversionRates = [
        'USD' => 3.67,
        'EUR' => 4.03,
        'GBP' => 4.66,
        'JPY' => 0.023,
        'CAD' => 2.69,
        "PHP" => 0.063,
        'SAR' => 0.98,
    ];

    $totalDifference = 0;
    $priceChanges = [];
    $totalAmountOfChanges = 0;
    $totalVehiclesChanged = 0;
    // check if po is dp
    // add vehicle id to each 
    foreach ($prices as $priceData) {
        $vehicleId = $priceData['vehicle_id'];
        $newPrice = $priceData['new_price'];
        $vehicleCost = VehiclePurchasingCost::where('vehicles_id', $vehicleId)->first();
        $oldPrice = $vehicleCost->unit_price;
        $priceDifference = $oldPrice - $newPrice;

        if ($priceDifference != 0) {
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update Vehicles Price';
            $vehicleslog->vehicles_id = $vehicleId;
            $vehicleslog->field = "Price";
            $vehicleslog->old_value = $oldPrice;
            $vehicleslog->new_value = $newPrice;
            $vehicleslog->created_by = $userId;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicleslog->save();
            $statuses = [
                'Payment Release Approved', 
                'Payment Completed', 
                'Vendor Confirmed', 
                'Incoming Stock'
            ];

            $vehiclesalreadypaid = Vehicles::where('id', $vehicleId)
                ->where(function($query) use ($statuses) {
                    $query->whereIn('payment_status', $statuses)
                        ->where('purchased_paid_percentage', 100)
                        ->whereNull('remaining_payment_status');
                })
                ->first();
            $vehicleAlreadyPaidOrRemainingInStatuses = Vehicles::where('id', $vehicleId)
                ->where(function($query) use ($statuses) {
                    $query->whereIn('payment_status', $statuses)
                        ->where('purchased_paid_percentage', 100)
                        ->orWhereIn('remaining_payment_status', $statuses);
                })
                ->first();
            $priceChange = abs($priceDifference);
            $changeType = $priceDifference > 0 ? 'discount' : 'surcharge';

            $priceupdates = new PurchasedOrderPriceChanges();
            $priceupdates->purchasing_order_id = $purchasingOrderId;
            $priceupdates->vehicles_id = $vehicleId;
            $priceupdates->original_price = $oldPrice;
            $priceupdates->new_price = $newPrice;
            $priceupdates->price_change = $priceChange;
            $priceupdates->change_type = $changeType;
            $priceupdates->save();

            $vehicle = Vehicles::find($vehicleId);
            $priceChanges[] = [
                'vehicle_reference' => $vehicle->id,
                'Vin' => $vehicle->vin,
                'variant_name' => $vehicle->variant->name,
                'old_price' => $oldPrice,
                'new_price' => $newPrice,
                'changed_by' => auth()->user()->name,
            ];

            $totalAmountOfChanges += $priceDifference;
            $totalVehiclesChanged++;

            if ($orderCurrency !== $accountCurrency) {
                $priceDifferenceInAccountCurrency = $this->convertCurrency($priceDifference, $orderCurrency, $accountCurrency, $conversionRates);
            } else {
                $priceDifferenceInAccountCurrency = $priceDifference;
            }

            $totalDifference += $priceDifferenceInAccountCurrency;

            $vehicleCost->update(['unit_price' => $newPrice]);

            if(!empty($vehiclesalreadypaid) && !empty($vehicleAlreadyPaidOrRemainingInStatuses)) {
                // $supplierAccount->current_balance += $totalDifference;
                // $supplierAccount->save();

                SupplierAccountTransaction::create([
                    'transaction_type' => $totalDifference > 0 ? 'Debit' : 'Credit',
                    'purchasing_order_id' => $purchasingOrderId,
                    'supplier_account_id' => $supplierAccount->id,
                    'created_by' => $userId,
                    'account_currency' => $accountCurrency,
                    'transaction_amount' => abs($totalDifference),
                ]);
            }
if($purchasingOrder->is_demand_planning_po == 1)
{
    $recipients = [
        config('mail.custom_recipients.dp'),
        config('mail.custom_recipients.finance'),
    ];
}
else
{
    $recipients = [
        config('mail.custom_recipients.cso'),
        config('mail.custom_recipients.finance'),
    ];
}
$orderUrl = url('/purchasing-order/' . $purchasingOrderId);
// Format the detail text including the price changes information
$detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
"PFI Number: " . $purchasingOrder->pl_number . "\n" .
"Stage: " . "Price Change\n" .
"Order URL: " . $orderUrl . "\n\n" .
"Price Changes:\n";

foreach ($priceChanges as $priceChange) {
$detailText .= "Vehicle ID: " . $priceChange['vehicle_reference'] .
     " (VIN: " . $priceChange['Vin'] . ", Variant: " . $priceChange['variant_name'] . "): " .
     "From '" . number_format($priceChange['old_price'], 2) . "' to '" .
     number_format($priceChange['new_price'], 2) . "'\n" .
     "Changed by: " . $priceChange['changed_by'] . "\n";
}

// Save the notification
$notification = new DepartmentNotifications();
$notification->module = 'Procurement';
$notification->type = 'Information';
$notification->detail = $detailText;
$notification->save();
if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 1; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 1; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
    Mail::to($recipients)->send(new PriceChangeNotification($purchasingOrder->po_number, $orderCurrency, $priceChanges, $totalAmountOfChanges, $totalVehiclesChanged,$orderUrl));

        }
    }

    $purchasingOrder->update(['totalcost' => $totalPrice]);
}
private function convertCurrency($amount, $fromCurrency, $toCurrency, $conversionRates)
{
    if ($fromCurrency == 'AED') {
        // Convert from AED to the target currency
        return $amount / $conversionRates[$toCurrency];
    } elseif ($toCurrency == 'AED') {
        // Convert from the source currency to AED
        return $amount * $conversionRates[$fromCurrency];
    } else {
        // Convert from source currency to AED, then from AED to target currency
        $amountInAed = $amount * $conversionRates[$fromCurrency];
        return $amountInAed / $conversionRates[$toCurrency];
    }
}
public function storeMessages(Request $request)
    {
        $message = PurchasedOrderMessages::create([
            'purchasing_order_id' => $request->purchase_order_id,
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);
        return response()->json($message->load('user'));
    }
    public function storeReply(Request $request)
    {
        $reply = PurchasedOrderReplies::create([
            'purchased_order_messages_id' => $request->message_id,
            'user_id' => auth()->id(),
            'reply' => $request->reply
        ]);

        return response()->json($reply->load('user'));
    }
    public function indexmessages($purchaseOrderId)
    {
        $messages = PurchasedOrderMessages::where('purchasing_order_id', $purchaseOrderId)
                            ->with('user', 'replies.user')
                            ->get();

        return response()->json($messages);
    }
    public function vehiclesdatagettingvariants($id)
{
  
    $vehicles = Vehicles::with('variant')->where('purchasing_order_id', $id)->whereNull('deleted_at')->whereNull('movement_grn_id')->get();
    $vehicleData = [];
    foreach ($vehicles as $vehicle) {
        $vehicleData[] = [
            'vehicle_id' => $vehicle->id,
            'vin' => $vehicle->vin,
            'variant_name' => $vehicle->variant->name ?? 'N/A',
        ];
    }
    return response()->json($vehicleData);
}
public function updateVariants(Request $request)
{
   
    $changedVariants = [];
    $variants = $request->input('variants');
    $purchasingOrderId = $request->input('purchasing_order_id');
    foreach ($variants as $variant) {
        $vehicle = Vehicles::where('id', $variant['vehicle_id'])
            ->where('purchasing_order_id', $purchasingOrderId)
            ->first();
        if ($vehicle) {
            $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
            $currentDateTime = Carbon::now($dubaiTimeZone);
            $vehicleslog = new Vehicleslog();
            $vehicleslog->time = $currentDateTime->toTimeString();
            $vehicleslog->date = $currentDateTime->toDateString();
            $vehicleslog->status = 'Update Vehicles Varaint';
            $vehicleslog->vehicles_id = $vehicle->id;
            $vehicleslog->field = "Varaint";
            $vehicleslog->old_value = $vehicle->varaints_id;
            $vehicleslog->new_value = $variant['variant_id'];
            $vehicleslog->created_by = auth()->user()->id;
            $vehicleslog->role = Auth::user()->selectedRole;
            $vehicle->varaints_id = $variant['variant_id'];
            $vehicle->save();
            // Collect data for the email
            if($vehicleslog->old_value != $vehicleslog->new_value)
            {
                $oldVariantName = Varaint::where('id', $vehicleslog->old_value)->value('name');
                $newVariantName = Varaint::where('id', $vehicleslog->new_value)->value('name');
                $changedVariants[] = [
                    'vehicleid' => $vehicle->id,
                    'vin' => $vehicle->vin,
                    'oldvariant' => $oldVariantName,
                    'newvariant' => $newVariantName,
                ];
                $vehicleslog->old_value = $oldVariantName;
                $vehicleslog->new_value = $newVariantName;
                $vehicleslog->save();
            
            }
        }
    }
    PurchasingOrderItems::where('purchasing_order_id', $purchasingOrderId)->delete();
    $vehiclesGroupedByVariant = Vehicles::where('purchasing_order_id', $purchasingOrderId)
        ->selectRaw('varaints_id, COUNT(*) as qty')
        ->groupBy('varaints_id')
        ->get();
    foreach ($vehiclesGroupedByVariant as $group) {
        $purchasedorderitems = New PurchasingOrderItems();
        $purchasedorderitems->purchasing_order_id = $purchasingOrderId;
        $purchasedorderitems->variant_id = $group->varaints_id;
        $purchasedorderitems->qty = $group->qty;
        $purchasedorderitems->save();
    }
    $purchasingOrder = PurchasingOrder::find($purchasingOrderId);
    $orderUrl = url('/purchasing-order/' . $purchasingOrderId);
    if($purchasingOrder->is_demand_planning_po == 1)
    {
        $recipients = config('mail.custom_recipients.dp');
    }
    else
    {
        $recipients = config('mail.custom_recipients.cso'); 
    }
    $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
                  "PFI Number: " . $purchasingOrder->pl_number . "\n" .
                  "Stage: " . "Variant Change\n" .
                  "Order URL: " . $orderUrl . "\n\n" .
                  "Changed Variants:\n";

    foreach ($changedVariants as $changedVariant) {
        $detailText .= "Vehicle ID: " . $changedVariant['vehicleid'] . 
                       " (VIN: " . $changedVariant['vin'] . "): From '" . 
                       $changedVariant['oldvariant'] . "' to '" . 
                       $changedVariant['newvariant'] . "'\n";
    }

    // Save the notification
    $notification = new DepartmentNotifications();
    $notification->module = 'Procurement';
    $notification->type = 'Information';
    $notification->detail = $detailText;
    $notification->save();
    if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
    Mail::to($recipients)->send(new ChangeVariantNotification(
        $purchasingOrder->po_number,
        $purchasingOrder->pl_number,
        $changedVariants, // Correct order
        $orderUrl
    ));    
    return response()->json(['success' => true]);
}
public function paymentremanings($id)
{
$useractivities =  New UserActivities();
$useractivities->activity = "Request for Remaining Payments";
$useractivities->users_id = Auth::id();
$useractivities->save();
$vehicle = Vehicles::find($id);
if ($vehicle) {
    $vehicle->remaining_payment_status = 'Request for Payment';
    $vehicle->save();
    $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
    $currentDateTime = Carbon::now($dubaiTimeZone);
        $vehicleslog = new Vehicleslog();
        $vehicleslog->time = $currentDateTime->toTimeString();
        $vehicleslog->date = $currentDateTime->toDateString();
        $vehicleslog->status = 'Request for Remaining Payment';
        $vehicleslog->vehicles_id = $id;
        $vehicleslog->field = "Vehicle Payment Remaining Status";
        $vehicleslog->old_value = "Partial Payment";
        $vehicleslog->new_value = "Request for Initiate Payment";
        $vehicleslog->created_by = auth()->user()->id;
        $vehicleslog->role = Auth::user()->selectedRole;
        $vehicleslog->save();
        $purchasingordereventsLog = new PurchasingOrderEventsLog();
        $purchasingordereventsLog->event_type = "Request for Payment";
        $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $vehicle->purchasing_order_id;
            $purchasingordereventsLog->field = "Vehicle Payment Remaining Status";
            $purchasingordereventsLog->old_value = "Partial Payment";
            $purchasingordereventsLog->new_value = "Request for Initiate Payment";
            $purchasingordereventsLog->description = "PO Creator Request the Payment to the Againt of the Vehicle Ref $id";
            $purchasingordereventsLog->save();
    return redirect()->back()->with('success', 'Payment confirmed. Vehicle status updated.');
    }
    return redirect()->back()->with('error', 'Vehicle not found.');
    }
    public function allpaymentreqssfinremainig(Request $request)
    {
        $percentage =  $request->input('percentage');
        $id = $request->input('orderId');
        $status = $request->input('status');
        if($status == "Approved")
        {
        $vehicles = DB::table('vehicles')
        ->where('purchasing_order_id', $id)
        ->where('remaining_payment_status', 'Request for Payment')
        ->get();
        $totalCost = 0;
        foreach ($vehicles as $vehicle) {
            $status = 'Payment Requested';
            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['remaining_payment_status' => $status]);
                $vehicleCost = VehiclePurchasingCost::where('vehicles_id', $vehicle->id)->value('unit_price');
                $adjustedCost = $vehicleCost * ($percentage / 100);
                $totalCost +=  $adjustedCost;
                $currentPercentage = DB::table('vehicles')->where('id', $vehicle->id)->value('purchased_paid_percentage');
                $currentPercentage = $currentPercentage ?? 0; // Treat null as 0
                $newPercentage = $currentPercentage + $percentage;
                DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update(['purchased_paid_percentage' => $newPercentage]);
                $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                $currentDateTime = Carbon::now($dubaiTimeZone);
                $vehicleslog = new Vehicleslog();
                $vehicleslog->time = $currentDateTime->toTimeString();
                $vehicleslog->date = $currentDateTime->toDateString();
                $vehicleslog->status = 'Payment Initiated Request';
                $vehicleslog->vehicles_id = $vehicle->id;
                $vehicleslog->field = "Payment Status";
                $vehicleslog->old_value = "Request for Initiate Payment";
                $vehicleslog->new_value = "Payment Initiated Request";
                $vehicleslog->created_by = auth()->user()->id;
                $vehicleslog->role = Auth::user()->selectedRole;
                $vehicleslog->save();
                }
                $paymentinti = New PurchasedOrderPaidAmounts();
                $paymentinti->amount = $totalCost;
                $paymentinti->purchasing_order_id = $id;
                $paymentinti->percentage = $percentage;
                $paymentinti->created_by = auth()->user()->id;
                $paymentinti->status = "Request For Payment";
                $paymentinti->save();
            }
                else
                {
                    $vehicles = DB::table('vehicles')
                    ->where('purchasing_order_id', $id)
                    ->where('remaining_payment_status', 'Request for Payment')
                    ->get();
                    foreach ($vehicles as $vehicle) {
                        $status = Null;
                        DB::table('vehicles')
                            ->where('id', $vehicle->id)
                            ->update(['remaining_payment_status' => $status]);
                        $dubaiTimeZone = CarbonTimeZone::create('Asia/Dubai');
                            $currentDateTime = Carbon::now($dubaiTimeZone);
                            $vehicleslog = new Vehicleslog();
                            $vehicleslog->time = $currentDateTime->toTimeString();
                            $vehicleslog->date = $currentDateTime->toDateString();
                            $vehicleslog->status = 'Reamining Payment Initiated Request Rejected';
                            $vehicleslog->vehicles_id = $vehicle->id;
                            $vehicleslog->field = "Payment Status";
                            $vehicleslog->old_value = "Reamining Request for Initiate Payment";
                            $vehicleslog->new_value = "Reamining Payment Initiated Request Rejected";
                            $vehicleslog->created_by = auth()->user()->id;
                            $vehicleslog->role = Auth::user()->selectedRole;
                            $vehicleslog->save();
                             }   
                }
         return redirect()->back()->with('success', 'Payment Status Updated');
}
public function requestAdditionalPayment(Request $request)
    {
        $id = $request->input('id');
        $totalSurcharges = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('change_type', 'Surcharge')->where('status', 'Pending')->update(['status' => 'Initiated Request']);
        return response()->json(['message' => 'Submitted Additional Payment Request successfully']);
    }
    public function requestinitiatedPayment(Request $request)
    {
        $id = $request->input('id');
        $totalcost = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('change_type', 'Surcharge')->where('status', 'Initiated Request')->sum('price_change');
        PurchasedOrderPriceChanges::where('purchasing_order_id', $id)->where('change_type', 'Surcharge')->where('status', 'Initiated Request')->update(['status' => 'Initiated']);
        $purchasingOrder = PurchasingOrder::find($id);
        $orderCurrency = $purchasingOrder->currency;
        $supplierAccount = SupplierAccount::where('suppliers_id', $purchasingOrder->vendors_id)->first();
        $supplieraccounttransition = new SupplierAccountTransaction();
                $supplieraccounttransition->transaction_type = "Pre-Debit";
                $supplieraccounttransition->purchasing_order_id = $id;
                $supplieraccounttransition->supplier_account_id = $supplierAccount->id;
                $supplieraccounttransition->created_by = auth()->user()->id;
                $supplieraccounttransition->account_currency = $orderCurrency;
                $supplieraccounttransition->transaction_amount = $totalcost;
                $supplieraccounttransition->payment_category = "Additional Payment";
                $supplieraccounttransition->remarks = "Additional Payment";
                $supplieraccounttransition->save();
        return response()->json(['message' => 'Submitted Additional Payment Request successfully']);
    }
    public function requestReleasedPayment(Request $request)
    {
    $id = $request->input('id');
    PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
        ->where('change_type', 'Surcharge')
        ->where('status', 'Initiated')
        ->update(['status' => 'Approved']);
    $transition = SupplierAccountTransaction::where('purchasing_order_id', $id)
        ->where('transaction_type', 'Pre-Debit')
        ->where('payment_category', 'Additional Payment')
        ->first();
    if ($transition) {
        $transition->status = 'Approved';
        $transition->remarks = 'Approved For Released Payment';
        $transition->save();
    }
    return response()->json(['message' => 'Additional Payment Approved successfully']);
    }
    public function completedadditionalpayment(Request $request)
    {
        $id = $request->input('orderIdadditional');
        $status = $request->input('statusadditional');
        $vehicleCount = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
        ->where('change_type', 'Surcharge')
        ->where('status', 'Approved')
        ->count();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/swift_copies'), $fileNameToStore);            
            $latestBatch = DB::table('purchasing_order_swift_copies')
                ->where('purchasing_order_id', $id)
                ->orderBy('created_at', 'desc')
                ->first();
            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;
            $swiftcopy = new PurchasingOrderSwiftCopies();
            $swiftcopy->purchasing_order_id = $id;
            $swiftcopy->uploaded_by = auth()->user()->id;
            $swiftcopy->number_of_vehicles = $vehicleCount;
            $swiftcopy->batch_no = $batchNo;
            $swiftcopy->file_path = 'storage/swift_copies/' . $fileNameToStore;
            $swiftcopy->save();
            $PurchasingOrder = PurchasingOrder::where('id', $id)->first();
            // info($PurchasingOrder);
            $supplieracc = SupplierAccount::where('suppliers_id', $PurchasingOrder->vendors_id)->first();
        if ($supplieracc) {
            $paymentad = PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
                        ->where('change_type', 'Surcharge')
                        ->where('status', 'Approved')
                        ->sum('price_change');
            $supplieracc->current_balance += $paymentad;
            $supplieracc->save();
            PurchasedOrderPriceChanges::where('purchasing_order_id', $id)
            ->where('change_type', 'Surcharge')
            ->where('status', 'Approved')
            ->update(['status' => 'Paid']);
            if($paymentad != 0)
            {
            $supplieraccount = new SupplierAccountTransaction();
            $supplieraccount->transaction_type = "Debit";
            $supplieraccount->purchasing_order_id = $id;
            $supplieraccount->supplier_account_id = $supplieracc->id;
            $supplieraccount->created_by = auth()->user()->id;
            $supplieraccount->account_currency = $PurchasingOrder->currency;
            $supplieraccount->transaction_amount = $paymentad;
            $supplieraccount->save();
            $paymentspaid = New PurchasedOrderPaidAmounts();
            $paymentspaid->amount = $paymentad;
            $paymentspaid->created_by = auth()->user()->id;
            $paymentspaid->purchasing_order_id = $id;
            $paymentspaid->status = "Paid";
            $paymentspaid->percentage = "100";
            $paymentspaid->save();
            $vendoradjustmentpaid = New VendorPaymentAdjustments();
            $vendoradjustmentpaid->amount = $paymentad;
            $vendoradjustmentpaid->type = 'Additional Payment';
            $vendoradjustmentpaid->supplier_account_id = $supplieracc->id;
            $vendoradjustmentpaid->purchasing_order_id = $id;
            $vendoradjustmentpaid->created_by = auth()->user()->id;
            $vendoradjustmentpaid->totalamount = $paymentad;
            $vendoradjustmentpaid->status = 'Paid';
            $vendoradjustmentpaid->remaining_amount = 0;
            $vendoradjustmentpaid->save();
            }
        }
    }
                return redirect()->back()->with('success', 'Payment Status Updated');
           }
           public function getVehicles($purchaseOrderId) {
            $vehicles = Vehicles::where('purchasing_order_id', $purchaseOrderId)
            ->where('status', 'Approved')
                ->with(['variant.brand', 'variant.master_model_lines', 'vehiclePurchasingCost'])
                ->get();
            return response()->json($vehicles);
        }
        
        public function getVehicleDetails($vehicleId) {
            $vehicle = Vehicles::with(['variant.brand', 'variant.master_model_lines', 'vehiclePurchasingCost'])
                ->find($vehicleId);
        
            return response()->json($vehicle);
        }
        public function savePaymentDetails(Request $request)
{
    try { // Added try-catch for overall error handling
        $paymentOption = $request->input('paymentOption');
        $purchaseOrderId = $request->input('purchaseOrderId');
        $remarks = $request->input('remarks');
        $createdBy = auth()->user()->id; // Assuming you use authentication and want to log the user who created the record
        
        // Get purchase order details
        $purchaseOrder = PurchasingOrder::find($purchaseOrderId);
        if (!$purchaseOrder) { // Check if purchase order exists
            return response()->json(['error' => 'Purchase order not found'], 404);
        }
        $supplierAccountId = $purchaseOrder->vendors_id;
        
        // Check if supplier account exists, if not create a new one
        $supplierAccount = SupplierAccount::where('suppliers_id', $supplierAccountId)->first();
        if (!$supplierAccount) {
            $supplierAccount = new SupplierAccount();
            $supplierAccount->suppliers_id = $supplierAccountId;
            $supplierAccount->currency = $purchaseOrder->currency;
            if (!$supplierAccount->save()) {
                return response()->json(['error' => 'Failed to create supplier account'], 500);
            }
        }
        
        $accountCurrency = $purchaseOrder->currency;
        $transactionType = 'Draft';
        $status = 'Draft';
        $transactionAmount = 0;
        
        if ($paymentOption == 'purchasedOrder') {
            $purchasedOrderOption = $request->input('purchasedOrderOption');
            $transactionAmount = $request->input('amount');
            // Handle equalDivided case
            if ($purchasedOrderOption == 'equalDivided') {
                $vehicles = Vehicles::where('purchasing_order_id', $purchaseOrderId)
                                    ->where('status', 'approved')
                                    ->get();
                $totalVehicles = $vehicles->count();
                if ($totalVehicles > 0) {
                    $transactionAmount = $transactionAmount / $totalVehicles;
                }
            }
        } else {
            $vehicles = $request->input('vehicles');
            foreach ($vehicles as $vehicle) {
                $transactionAmount += $vehicle['initiatedPrice'];
            }
        }
        
        // Store in supplier_account_transaction table
        $supplierAccountTransaction = new SupplierAccountTransaction();
        $supplierAccountTransaction->transaction_type = $transactionType;
        $supplierAccountTransaction->purchasing_order_id = $purchaseOrderId;
        $supplierAccountTransaction->supplier_account_id = $supplierAccount->id;
        $supplierAccountTransaction->created_by = $createdBy;
        $supplierAccountTransaction->account_currency = $accountCurrency;
        $supplierAccountTransaction->transaction_amount = $transactionAmount;
        $supplierAccountTransaction->remarks = $remarks;
        $supplierAccountTransaction->status = $status;
        
        if (!$supplierAccountTransaction->save()) {
            return response()->json(['error' => 'Failed to save supplier account transaction'], 500);
        }
        
        $adjustmentAmount = $request->input('adjustmentAmount');
        $vendorpayment = new VendorPaymentAdjustments();
        $vendorpayment->amount = $adjustmentAmount ?: $transactionAmount; // Condensed assignment
        $vendorpayment->type = $adjustmentAmount ? 'Adjustment' : 'No Adjustment';
        $vendorpayment->supplier_account_id = $supplierAccount->id; // Correct foreign key reference
        $vendorpayment->purchasing_order_id = $purchaseOrderId;
        $vendorpayment->created_by = $createdBy;
        $vendorpayment->totalamount = $transactionAmount;
        $vendorpayment->status = 'Draft';
        $vendorpayment->sat_id = $supplierAccountTransaction->id;
        $vendorpayment->remaining_amount = $adjustmentAmount ? $transactionAmount - $adjustmentAmount : 0;
        
        if (!$vendorpayment->save()) {
            return response()->json(['error' => 'Failed to save vendor payment adjustment'], 500);
        }
        
        // Store in Purchased Order Paid Amounts
        $purchasedorderpaidamounts = new PurchasedOrderPaidAmounts();
        $purchasedorderpaidamounts->amount = $transactionAmount;
        $purchasedorderpaidamounts->created_by = $createdBy;
        $purchasedorderpaidamounts->purchasing_order_id = $purchaseOrderId;
        $purchasedorderpaidamounts->status = 'Draft';
        $purchasedorderpaidamounts->sat_id = $supplierAccountTransaction->id;
        
        if (!$purchasedorderpaidamounts->save()) {
            return response()->json(['error' => 'Failed to save purchased order paid amounts'], 500);
        }

        // If vehicles are involved, store in vehicles_supplier_account_transaction table
        if ($paymentOption == 'vehicle' || ($paymentOption == 'purchasedOrder' && $purchasedOrderOption == 'equalDivided')) {
            if ($paymentOption == 'vehicle') {
                $vehicles = $request->input('vehicles');
            } else {
                $vehicles = Vehicles::where('purchasing_order_id', $purchaseOrderId)
                                    ->where('status', 'approved')
                                    ->get();
            }
            foreach ($vehicles as $vehicle) {
                $vehiclesSupplierAccountTransaction = new VehiclesSupplierAccountTransaction();
                $vehiclesSupplierAccountTransaction->vehicles_id = $vehicle['vehicleId'];
                $vehiclesSupplierAccountTransaction->sat_id = $supplierAccountTransaction->id;
                $vehiclesSupplierAccountTransaction->popa_id = $purchasedorderpaidamounts->id;
                $vehiclesSupplierAccountTransaction->vpa_id = $vendorpayment->id;
                $vehiclesSupplierAccountTransaction->amount = $vehicle['initiatedPrice'];
                $vehiclesSupplierAccountTransaction->status = 'Draft';
                if (!$vehiclesSupplierAccountTransaction->save()) {
                    return response()->json(['error' => 'Failed to save vehicle supplier account transaction'], 500);
                }
            }
        }
        $purchasingordereventsLog = New PurchasingOrderEventsLog();
        $purchasingordereventsLog->event_type = "Payment Initiation Saved";
        $purchasingordereventsLog->created_by = auth()->user()->id;
        $purchasingordereventsLog->purchasing_order_id = $purchaseOrderId;
        $purchasingordereventsLog->description = "Payment Inititaion Save By the PO Creator";
        $purchasingordereventsLog->save();
        return response()->json(['message' => 'Payment details saved successfully'], 200);
    } catch (Exception $e) { // Catch any exception
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}
public function submitPaymentDetails(Request $request)
{
    // payment initiation function
    try { // Added try-catch for overall error handling

        DB::beginTransaction();
        $paymentOption = $request->input('paymentOption');
        $purchaseOrderId = $request->input('purchaseOrderId');
        $remarks = $request->input('remarks');
        // info($remarks);
        $createdBy = auth()->user()->id; // Assuming you use authentication and want to log the user who created the record
        // Get purchase order details
        $purchaseOrder = PurchasingOrder::find($purchaseOrderId);
        if (!$purchaseOrder) { // Check if purchase order exists
            return response()->json(['error' => 'Purchase order not found'], 404);
        }
        $supplierAccountId = $purchaseOrder->vendors_id;
        // Check if supplier account exists, if not create a new one
        $supplierAccount = SupplierAccount::where('suppliers_id', $supplierAccountId)->first();
        if (!$supplierAccount) {
            $supplierAccount = new SupplierAccount();
            $supplierAccount->suppliers_id = $supplierAccountId;
            $supplierAccount->currency = $purchaseOrder->currency;
            if (!$supplierAccount->save()) {
                return response()->json(['error' => 'Failed to create supplier account'], 500);
            }
        }
        $accountCurrency = $purchaseOrder->currency;
        $transactionType = 'Initiate Payment Request';
        $status = 'Initiate Payment Request';
        $transactionAmount = 0;
        if ($paymentOption == 'purchasedOrder') {
            $purchasedOrderOption = $request->input('purchasedOrderOption');
            $transactionAmount = $request->input('amount');
            // Handle equalDivided case
            if ($purchasedOrderOption == 'equalDivided') {
                $vehicles = Vehicles::where('purchasing_order_id', $purchaseOrderId)
                                    ->where('status', 'approved')
                                    ->get();
                $totalVehicles = $vehicles->count();
                if ($totalVehicles > 0) {
                    $transactionAmount = $transactionAmount / $totalVehicles;
                }
            }
        } else {
            $vehicles = $request->input('vehicles');
            foreach ($vehicles as $vehicle) {
                $transactionAmount += $vehicle['initiatedPrice'];
            }
        }
        // Store in supplier_account_transaction table
        $supplierAccountTransaction = new SupplierAccountTransaction();
        $supplierAccountTransaction->transaction_type = $transactionType;
        $supplierAccountTransaction->purchasing_order_id = $purchaseOrderId;
        $supplierAccountTransaction->supplier_account_id = $supplierAccount->id;
        $supplierAccountTransaction->created_by = $createdBy;
        $supplierAccountTransaction->account_currency = $accountCurrency;
        $supplierAccountTransaction->transaction_amount = $transactionAmount;
        $supplierAccountTransaction->remarks = $remarks;
        $supplierAccountTransaction->status = $status;
        
        if (!$supplierAccountTransaction->save()) {
            return response()->json(['error' => 'Failed to save supplier account transaction'], 500);
        }
        $adjustmentAmount = $request->input('adjustmentAmount');
        $vendorpayment = new VendorPaymentAdjustments();
        $vendorpayment->amount = $adjustmentAmount ?: $transactionAmount; // Condensed assignment
        $vendorpayment->type = $adjustmentAmount ? 'Adjustment' : 'No Adjustment';
        $vendorpayment->supplier_account_id = $supplierAccount->id; // Correct foreign key reference
        $vendorpayment->purchasing_order_id = $purchaseOrderId;
        $vendorpayment->created_by = $createdBy;
        $vendorpayment->totalamount = $transactionAmount;
        $vendorpayment->status = 'pending';
        $vendorpayment->sat_id = $supplierAccountTransaction->id;
        $vendorpayment->remaining_amount = $adjustmentAmount ? $transactionAmount - $adjustmentAmount : 0;
        
        if (!$vendorpayment->save()) {
            return response()->json(['error' => 'Failed to save vendor payment adjustment'], 500);
        }
        
        // Store in Purchased Order Paid Amounts
        $purchasedorderpaidamounts = new PurchasedOrderPaidAmounts();
        $purchasedorderpaidamounts->amount = $transactionAmount;
        $purchasedorderpaidamounts->created_by = $createdBy;
        $purchasedorderpaidamounts->purchasing_order_id = $purchaseOrderId;
        $purchasedorderpaidamounts->status = 'Initiate Payment Request';
        $purchasedorderpaidamounts->sat_id = $supplierAccountTransaction->id;
        
        if (!$purchasedorderpaidamounts->save()) {
            return response()->json(['error' => 'Failed to save purchased order paid amounts'], 500);
        }

        // If vehicles are involved, store in vehicles_supplier_account_transaction table
        if ($paymentOption == 'vehicle' || ($paymentOption == 'purchasedOrder' && $purchasedOrderOption == 'equalDivided')) {
            if ($paymentOption == 'vehicle') {
                $vehicles = $request->input('vehicles');
            } else {
                $vehicles = Vehicles::where('purchasing_order_id', $purchaseOrderId)
                                    ->where('status', 'approved')
                                    ->get();
            }
          
            $purchasingOrder = PurchasingOrder::find($purchaseOrderId);
              // chcek DP PO or not

              if($purchasingOrder->is_demand_planning_purchase_order)
               {
                    $pfiId = $purchasingOrder->PFIPurchasingOrder->pfi->id ?? '';
                    $pfiItemLatest = PfiItem::where('pfi_id', $pfiId)
                                        ->where('is_parent', false)
                                        ->first();
                    // Utilization qty update only for Toyota PO
                    if($pfiItemLatest) {
                        // only toyota PFI have child , so if child exist it will be toyota PO
                        $initiatedQtyUpdatedLOIItemIds = [];
                        foreach ($vehicles as $vehicle) {
                            $vehicle = Vehicles::find($vehicle['vehicleId']);
                            $masterModel = MasterModel::find($vehicle->model_id);
                            $possibleModels = MasterModel::where('model', $masterModel->model)
                                                    ->where('sfx',  $masterModel->sfx)
                                                    ->pluck('id')->toArray();
                            $pfiItem = PfiItemPurchaseOrder::where('purchase_order_id', $purchasingOrder->id)
                                                                ->whereIn('master_model_id', $possibleModels)
                                                                ->first();
                            $loiItem = LetterOfIndentItem::whereHas('pfiItems', function($query)use($pfiItem) {
                                $query->where('is_parent', false)
                                ->where('pfi_id', $pfiItem->pfi_id)
                                ->where('parent_pfi_item_id', $pfiItem->pfi_item_id);
                            })
                            ->select("*", DB::raw('COALESCE(quantity, 0) - COALESCE(utilized_quantity, 0) as remaining_quantity'))
                            ->havingRaw('remaining_quantity - po_payment_initiated_quantity > 0')
                            ->first();
                            // need to check with payment initiated qty with remaining qty
                            if($loiItem) {
                                // update po_payment_initiated_quantity and keep ids in array
                                $current_po_payment_initiated_quantity = $loiItem->po_payment_initiated_quantity;

                                $loiItem->po_payment_initiated_quantity = $current_po_payment_initiated_quantity + 1;
                                $loiItem->save();

                                $initiatedQtyUpdatedLOIItemIds[] = $loiItem->id;
                            }else{
                                // revise updated qty of po_payment_initiated_quantity
                                foreach($initiatedQtyUpdatedLOIItemIds as $LOIItemId) {
                                    $item = LetterOfIndentItem::find($LOIItemId);
                                    $current_po_payment_initiated_quantity = $item->po_payment_initiated_quantity;

                                    $item->po_payment_initiated_quantity = $current_po_payment_initiated_quantity - 1;
                                    $item->save();
                                }
                                return response()->json(['error' => 'LOI Quantity not available to full fill request'], 500);
                            }
                        }
                    
                    }
                }

            foreach ($vehicles as $vehicle) {
                $vehiclesSupplierAccountTransaction = new VehiclesSupplierAccountTransaction();
                $vehiclesSupplierAccountTransaction->vehicles_id = $vehicle['vehicleId'];
                $vehiclesSupplierAccountTransaction->sat_id = $supplierAccountTransaction->id;
                $vehiclesSupplierAccountTransaction->popa_id = $purchasedorderpaidamounts->id;
                $vehiclesSupplierAccountTransaction->vpa_id = $vendorpayment->id;
                $vehiclesSupplierAccountTransaction->amount = $vehicle['initiatedPrice'];
                $vehiclesSupplierAccountTransaction->status = 'pending';
                if (!$vehiclesSupplierAccountTransaction->save()) {
                    return response()->json(['error' => 'Failed to save vehicle supplier account transaction'], 500);
                }
            }
           
        }
        $purchasingordereventsLog = New PurchasingOrderEventsLog();
        $purchasingordereventsLog->event_type = "Payment Initation";
        $purchasingordereventsLog->created_by = auth()->user()->id;
        $purchasingordereventsLog->purchasing_order_id = $purchaseOrderId;
        $purchasingordereventsLog->description = "Payment Inititaion Request to the Procurement Manager";
        $purchasingordereventsLog->save();

        $purchasingOrder->payment_initiated_status = PurchasingOrder::PAYMENT_STATUS_INITIATED;
        $purchasingOrder->save();
        DB::commit();

        return response()->json(['message' => 'Payment details saved successfully'], 200);
    } catch (Exception $e) { // Catch any exception
        return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
    }
}
    public function handleActionInitiate(Request $request)
{
    $transitionId = $request->input('id');
        
    $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
    if ($supplierAccountTransaction) {
        $supplierAccountTransaction->transaction_type = 'Request For Payment';
        $supplierAccountTransaction->status = 'Request For Payment';
        $supplierAccountTransaction->save();
    }
    $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
    if ($purchasedOrderPaidAmounts) {
        $purchasedOrderPaidAmounts->status = 'Request For Payment';
        $purchasedOrderPaidAmounts->save();
    }
    $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
    if ($vendorPayment) {
        $vendorPayment->status = 'Request For Payment';
        $vendorPayment->save();
    }
    $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
    $transactionCount = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->count();
    foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
        $vehicleTransaction->status = 'Request For Payment';
        $vehicleTransaction->save();
    }
    $purchasingOrder = PurchasingOrder::where('id', $supplierAccountTransaction->purchasing_order_id)->first();
    $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
    $currency = $supplierAccountTransaction->account_currency;
    if($purchasingOrder->is_demand_planning_po == 1)
    {
    $recipients = config('mail.custom_recipients.finance');
    Mail::to($recipients)->send(new EmailNotificationrequest($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
    }
    else
    {
        $recipients = config('mail.custom_recipients.finance');
    Mail::to($recipients)->send(new EmailNotificationrequest($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
    }
    $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
          "PFI Number: " . $purchasingOrder->pl_number . "\n" .
          "Payment Amount: " . $supplierAccountTransaction->transaction_amount . "\n" .
          "Total Amount: " . $purchasingOrder->totalcost . "\n" .
          "Stage: " . "Payment Requested for Initiation\n" .
          "Number of Units: " . $transactionCount . " Vehicles\n" .
          "Order URL: " . $orderUrl;
        $notification = New DepartmentNotifications();
        $notification->module = 'Procurement';
        $notification->type = 'Information';
        $notification->detail = $detailText;
        $notification->save();
        if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 1; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 1; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
                $purchasingordereventsLog = New PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Initation";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = "Payment Inititaion Request to the Procurement Manager";
                $purchasingordereventsLog->save();
    return response()->json(['message' => 'Payment details saved successfully'], 200);  
}
    public function getVendorAndBalance($purchaseOrderId)
    {
        $purchaseOrder = PurchasingOrder::find($purchaseOrderId);
        if (!$purchaseOrder) {
            return response()->json(['error' => 'Purchase order not found'], 404);
        }

        $vendorId = $purchaseOrder->vendors_id;
        $supplierAccount = SupplierAccount::where('suppliers_id', $vendorId)->first();
        if (!$supplierAccount) {
            return response()->json(['error' => 'Supplier account not found'], 404);
        }
        return response()->json([
            'supplier_account_id' => $supplierAccount->id,
            'current_balance' => $supplierAccount->current_balance,
        ]);
    }
    public function submitforpayment(Request $request)
    {
    $transitionId = $request->input('id');
    $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
    if ($supplierAccountTransaction) {
        $supplierAccountTransaction->transaction_type = 'Initiate Payment Request';
        $supplierAccountTransaction->status = 'Initiate Payment Request';
        $supplierAccountTransaction->save();
    }

    $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
    if ($purchasedOrderPaidAmounts) {
        $purchasedOrderPaidAmounts->status = 'Initiate Payment Request';
        $purchasedOrderPaidAmounts->save();
    }
    $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
    if ($vendorPayment) {
        $vendorPayment->status = 'pending';
        $vendorPayment->save();
    }
    $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
    $transactionCount = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->count();
    foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
        $vehicleTransaction->status = 'pending';
        $vehicleTransaction->save();
    }
            $purchasingOrder = PurchasingOrder::where('id', $supplierAccountTransaction->purchasing_order_id)->first();
            $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
            $currency = $supplierAccountTransaction->account_currency;
            if($purchasingOrder->is_demand_planning_po == 1)
            {
            $recipients = config('mail.custom_recipients.dp');
            Mail::to($recipients)->send(new EmailNotificationInitiate($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
            }
            else
            {
            $recipients = config('mail.custom_recipients.cso');
            Mail::to($recipients)->send(new EmailNotificationInitiate($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
            }
            $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
                  "PFI Number: " . $purchasingOrder->pl_number . "\n" .
                  "Payment Amount: " . $supplierAccountTransaction->transaction_amount . "\n" .
                  "Total Amount: " . $purchasingOrder->totalcost . "\n" .
                  "Stage: " . "Payment Initiation\n" .
                  "Number of Units: " . $transactionCount . " Vehicles\n" .
                  "Order URL: " . $orderUrl;
            $notification = New DepartmentNotifications();
            $notification->module = 'Procurement';
            $notification->type = 'Information';
            $notification->detail = $detailText;
            $notification->save();
            if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
                $purchasingordereventsLog = New PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Initation";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = "Procurement Manager Forward Payment Inititaion Request to the Finance Department";
                $purchasingordereventsLog->save();
    return response()->json(['message' => 'Payment details saved successfully'], 200);
    }
    public function submitPayment(Request $request)
    {
        try {
            $transitionId = $request->input('transitionId');
            $bankAccount = $request->input('bankAccount');
            $file = $request->file('file');
            $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
            if ($supplierAccountTransaction) {
                if ($file) {
                    $fileNameToStore = time() . '_' . $file->getClientOriginalName();
                    $path = $file->move(public_path('storage/transition_file'), $fileNameToStore);
                    $supplierAccountTransaction->transition_file = 'storage/transition_file/' . $fileNameToStore;
                }
                $supplierAccountTransaction->transaction_type = 'Pre-Debit';
                $supplierAccountTransaction->status = 'pending';
                $supplierAccountTransaction->bank_accounts_id =  $bankAccount;
                $supplierAccountTransaction->save();
                $supplierAccount = SupplierAccount::where('id', $supplierAccountTransaction->supplier_account_id)->first();
                {
                    $purchasingOrder = PurchasingOrder::where('id', $supplierAccountTransaction->purchasing_order_id)->first();
                    if ($purchasingOrder) {
                        $currency = $purchasingOrder->currency;
                        $transactionAmount = $supplierAccountTransaction->transaction_amount;
                        // Conversion rates
                        $conversionRates = [
                            "USD" => 3.67,
                            "EUR" => 3.94,
                            "GBP" => 4.67,
                            "JPY" => 0.025,
                            "AUD" => 2.29,
                            "AED" => 1,
                            "CAD" => 2.68,
                            "PHP" => 0.063,
                            'SAR' => 0.98,
                        ];
                        // Check if the currencies are different
                        if ($purchasingOrder->currency != $supplierAccount->currency) {
                            // Convert the transactionAmount to the SupplierAccount currency
                            $purchasingOrderConversionRate = $conversionRates[$purchasingOrder->currency] ?? 1;
                            $supplierAccountConversionRate = $conversionRates[$supplierAccount->currency] ?? 1;

                            // Convert the transaction amount from the purchasing order currency to the supplier account currency
                            $transactionAmountInAED = $supplierAccountTransaction->transaction_amount * $purchasingOrderConversionRate; // Convert to base currency (e.g. AED)
                            $totalCostConverted = $transactionAmountInAED / $supplierAccountConversionRate; // Convert from AED to supplier account currency
                        } else {
                            $totalCostConverted = $supplierAccountTransaction->transaction_amount;
                           
                        }
                     
                        $account_balance = $supplierAccount->current_balance + $totalCostConverted;
                        $supplierAccount->current_balance = $account_balance <= 0 ? 0 : $account_balance;
                        
                        $supplierAccount->save();
                }
            }
            }
            $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
            if ($purchasedOrderPaidAmounts) {
                $purchasedOrderPaidAmounts->status = 'Suggested Payment';
                $purchasedOrderPaidAmounts->save();
            }
            $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
            if ($vendorPayment) {
                $vendorPayment->status = 'Initiate';
                $vendorPayment->save();
            }
            $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
            $transactionCount = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->count();
            foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
                $vehicleTransaction->status = 'Initiate';
                $vehicleTransaction->save();
            }
            $purchasingOrder = PurchasingOrder::where('id', $supplierAccountTransaction->purchasing_order_id)->first();
            $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
            $currency = $supplierAccountTransaction->account_currency;
            if($purchasingOrder->is_demand_planning_po == 1)
            {
            // $recipients = config('mail.custom_recipients.dp');
            // Mail::to($recipients)->send(new EmailNotificationInitiate($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
            }
            else
            {
            $recipients = config('mail.custom_recipients.cso');
            Mail::to($recipients)->send(new EmailNotificationInitiate($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
            }
            $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
                  "PFI Number: " . $purchasingOrder->pl_number . "\n" .
                  "Payment Amount: " . $supplierAccountTransaction->transaction_amount . "\n" .
                  "Total Amount: " . $purchasingOrder->totalcost . "\n" .
                  "Stage: " . "Payment Initiation\n" .
                  "Number of Units: " . $transactionCount . " Vehicles\n" .
                  "Order URL: " . $orderUrl;
            $notification = New DepartmentNotifications();
            $notification->module = 'Procurement';
            $notification->type = 'Information';
            $notification->detail = $detailText;
            $notification->save();
            if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
                $purchasingordereventsLog = New PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Initation";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = "Finance Manager Forward Payment Inititaion Request to the CEO office For Payment Released";
                $purchasingordereventsLog->save();
            return response()->json(['success' => true, 'message' => 'Payment submitted successfully']);
        } catch (\Exception $e) {
            Log::error('Payment submission failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error submitting payment', 'error' => $e->getMessage()], 500);
        }
    }
    public function approveTransition(Request $request)
    {
        // update utilization qty
        try{

            DB::beginTransaction();

            $transitionId = $request->input('transition_id');
            $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
            if ($supplierAccountTransaction) {
                $supplierAccountTransaction->transaction_type = 'Released';
                $supplierAccountTransaction->status = 'Approved';
                $supplierAccountTransaction->payment_released_date = Carbon::now()->format('Y-m-d');
                $supplierAccountTransaction->save();
            }
            $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
            if ($purchasedOrderPaidAmounts) {
                $purchasedOrderPaidAmounts->status = 'Approved';
                $purchasedOrderPaidAmounts->save();
            }
            $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
            if ($vendorPayment) {
                $vendorPayment->status = 'Approved';
                $vendorPayment->save();
            }
            $purchasingOrder = PurchasingOrder::where('id', $supplierAccountTransaction->purchasing_order_id)->first();

            $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
            $transactionCount = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->count();
            foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
                $vehicleTransaction->status = 'Approved';
                $vehicleTransaction->save();
                $vehiclespaid = VehiclePurchasingCost::where('vehicles_id', $vehicleTransaction->vehicles_id)->first();
                $vehiclespaid->total_paid_amount += $vehicleTransaction->amount;
                $vehiclespaid->save();
                    // DP PO
                if($purchasingOrder->is_demand_planning_purchase_order) {
                    $pfiId = $purchasingOrder->PFIPurchasingOrder->pfi->id ?? '';
                    $pfiItemLatest = PfiItem::where('pfi_id', $pfiId)
                            ->where('is_parent', false)
                            ->first();
                    // Utilization qty update only for Toyota PO
                    if($pfiItemLatest) {
                        // only toyota PFI have child , so if child exist it will be toyota PO
                            $vehicle = Vehicles::find($vehicleTransaction->vehicles_id);
                            $masterModel = MasterModel::find($vehicle->model_id);
                            $possibleModels = MasterModel::where('model', $masterModel->model)
                                                ->where('sfx',  $masterModel->sfx)
                                                ->pluck('id')->toArray();
                            $pfiItem = PfiItemPurchaseOrder::where('purchase_order_id', $purchasingOrder->id)
                                                            ->whereIn('master_model_id', $possibleModels)
                                                            ->first();
                            $loiItem = LetterOfIndentItem::whereHas('pfiItems', function($query)use($pfiItem) {
                                        $query->where('is_parent', false)
                                        ->where('pfi_id', $pfiItem->pfi_id)
                                        ->where('parent_pfi_item_id', $pfiItem->pfi_item_id);
                                    })
                                    ->first();
                            if($loiItem) {
                                $latestUtilizedQuantity = $loiItem->utilized_quantity + 1;
                                $loiItem->po_payment_initiated_quantity = $loiItem->po_payment_initiated_quantity - 1;
                                $loiItem->utilized_quantity = $latestUtilizedQuantity;
                                $loiItem->save();
                            }
                    }
                }
            }

            // payment status update => check transaction include full po qty or not
            $purchaseOrderQty = PurchasingOrderItems::where('purchasing_order_id', $purchasingOrder->id)
                                                    ->sum('qty');
            $transitionQty = $vehiclesSupplierAccountTransactions->count();
            if($transitionQty == $purchaseOrderQty) {
                $purchasingOrder->payment_status = PurchasingOrder::PAYMENT_STATUS_PAID;    
            }else{
                $purchasingOrder->payment_status = PurchasingOrder::PAYMENT_STATUS_PARTIALY_PAID;    
            }      
            $purchasingOrder->save();   

            // if PO is Dp => Seal the PFI Document with milele seal
            if($purchasingOrder->is_demand_planning_purchase_order) {
                $pdf = new Fpdi();
                $pfiId = $purchasingOrder->PFIPurchasingOrder->pfi->id ?? '';
                $pfi = PFI::find($pfiId);
                if(!$pfi->pfi_document_with_sign) {
                    if($pfi->new_pfi_document_without_sign) {
                        $destinationPath = 'New_PFI_document_without_sign/'.$pfi->new_pfi_document_without_sign;
                    }else{
                        $destinationPath = 'PFI_document_withoutsign/'. $pfi->pfi_document_without_sign; 
                    }
                    if($pfi->new_pfi_document_without_sign || $pfi->pfi_document_without_sign) {
    
                        $pageCount = $pdf->setSourceFile($destinationPath);
    
                        for ($i=1; $i <= $pageCount; $i++)
                        {
                            $pdf->setPrintHeader(false);
                            $pdf->AddPage();
                            $tplIdx = $pdf->importPage($i);
                            $pdf->useTemplate($tplIdx);
                            if($i == $pageCount) {
                                $pdf->Image('milele_seal.png', 80, 230, 50,35);
                            }
                        }
        
                        $signedFileName = 'MILELE - '.$pfi->pfi_reference_number.'.pdf';
                        $directory = public_path('PFI_Document_with_sign');
                        \Illuminate\Support\Facades\File::makeDirectory($directory, $mode = 0777, true, true);
                        if (File::exists(public_path('PFI_Document_with_sign/'.$signedFileName))) {
                            File::delete(public_path('PFI_Document_with_sign/'.$signedFileName));
                        }
                        $pdf->Output($directory.'/'.$signedFileName,'F');
                        $pfi->pfi_document_with_sign = $signedFileName;
                        $pfi->save();
                    }
                }
               
            }

            $orderUrl = url('/purchasing-order/' . $purchasingOrder->id);
            $currency = $supplierAccountTransaction->account_currency;
            if($purchasingOrder->is_demand_planning_po == 1)
            {
                $recipients = [
                    config('mail.custom_recipients.dp'),
                    config('mail.custom_recipients.finance'),
                ];
            Mail::to($recipients)->send(new DPrealeasedEmailNotification($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
            }
            else 
            {
                $recipients = [
                    config('mail.custom_recipients.cso'),
                    config('mail.custom_recipients.finance'),
                ];
            Mail::to($recipients)->send(new DPrealeasedEmailNotification($purchasingOrder->po_number, $purchasingOrder->pl_number, $supplierAccountTransaction->transaction_amount, $purchasingOrder->totalcost, $transactionCount, $orderUrl, $currency));
            }
            $detailText = "PO Number: " . $purchasingOrder->po_number . "\n" .
            "PFI Number: " . $purchasingOrder->pl_number . "\n" .
            "Payment Amount: " . $supplierAccountTransaction->transaction_amount . "\n" .
            "Total Amount: " . $purchasingOrder->totalcost . "\n" .
            "Stage: " . "Payment Released\n" .
            "Number of Units: " . $transactionCount . " Vehicles\n" .
            "Order URL: " . $orderUrl;
            $notification = New DepartmentNotifications();
            $notification->module = 'Procurement';
            $notification->type = 'Information';
            $notification->detail = $detailText;
            $notification->save();
            if($purchasingOrder->is_demand_planning_po == 1)
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 4; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 1; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                } 
                else
                {
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 15; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                    $dnaccess = New Dnaccess();
                    $dnaccess->master_departments_id = 1; 
                    $dnaccess->department_notifications_id = $notification->id;
                    $dnaccess->save();
                }
            $purchasingordereventsLog = New PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Payment Released";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
            $purchasingordereventsLog->description = "CEO office Released Confirmed";
            $purchasingordereventsLog->save();
            DB::commit();    

            return response()->json(['success' => true, 'transition_id' => $transitionId]);  
        }catch (\Exception $e){
            info($e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            // return response()->json(['success' => true, 'transition_id' => $transitionId]);
        }
    }

    public function rejectTransition(Request $request)
    {
    $transitionId = $request->input('transition_id');
    $remarks = $request->input('remarks');
    $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
    if ($supplierAccountTransaction) {
        $supplierAccountTransaction->transaction_type = 'Rejected';
        $supplierAccountTransaction->status = 'Rejected';
        $supplierAccountTransaction->remarks = $remarks;
        $supplierAccountTransaction->save();
        $supplierAccount = SupplierAccount::where('id', $supplierAccountTransaction->supplier_account_id)->first();
        {
            $purchasingOrder = PurchasingOrder::where('id', $supplierAccountTransaction->purchasing_order_id)->first();
            if ($purchasingOrder) {
                $currency = $purchasingOrder->currency;
                $transactionAmount = $supplierAccountTransaction->transaction_amount;
                // Conversion rates
                $conversionRates = [
                    "USD" => 3.67,
                    "EUR" => 3.94,
                    "GBP" => 4.67,
                    "JPY" => 0.023,
                    "AUD" => 2.29,
                    "AED" => 1,
                    "CAD" => 2.68,
                    "PHP" => 0.063,
                    'SAR' => 0.98,
                ];
                // Check if the currencies are different
        if ($purchasingOrder->currency != $supplierAccount->currency) {
            // Convert the transactionAmount to the SupplierAccount currency
            $purchasingOrderConversionRate = $conversionRates[$purchasingOrder->currency] ?? 1;
            $supplierAccountConversionRate = $conversionRates[$supplierAccount->currency] ?? 1;

            // Convert the transaction amount from the purchasing order currency to the supplier account currency
            $transactionAmountInAED = $supplierAccountTransaction->transaction_amount * $purchasingOrderConversionRate; // Convert to base currency (e.g. AED)
            $totalCostConverted = $transactionAmountInAED / $supplierAccountConversionRate; // Convert from AED to supplier account currency
        } else {
            // If the currencies are the same, no conversion is needed
            $totalCostConverted = $supplierAccountTransaction->transaction_amount;
        }

        // Update the supplier account balance
        $account_balance = $supplierAccount->current_balance - $totalCostConverted;
        $supplierAccount->current_balance = $account_balance <= 0 ? 0 : $account_balance;
        $supplierAccount->save();
        }
    }
    }
    $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
    if ($purchasedOrderPaidAmounts) {
        $purchasedOrderPaidAmounts->status = 'Rejected';
        $purchasedOrderPaidAmounts->save();
    }
    $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
    if ($vendorPayment) {
        $vendorPayment->status = 'Rejected';
        $vendorPayment->save();
    }
    $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
    foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
        $vehicleTransaction->status = 'Rejected';
        $vehicleTransaction->save();
    }
    $purchasingordereventsLog = New PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Requested Rejected";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = "The Payment Request is Rejected";
                $purchasingordereventsLog->save();
    return response()->json(['message' => 'Transition rejected successfully.']);
    }
    public function rejectTransitionlinitiate(Request $request)
    {
       info("rejected transition");
        // payment initiate reject
        try{

            DB::beginTransaction();
            $transitionId = $request->input('transition_id');
            $remarks = $request->input('remarks');
            $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
            $purchasingOrder = PurchasingOrder::find($supplierAccountTransaction->purchasing_order_id);    

                if ($supplierAccountTransaction) {
                   
                    $supplierAccount = SupplierAccount::where('id', $supplierAccountTransaction->supplier_account_id)->first();
                    if ($supplierAccount) {
                        $conversionRates = [
                            "USD" => 3.67,
                            "EUR" => 3.94,
                            "GBP" => 4.67,
                            "JPY" => 0.023,
                            "AUD" => 2.29,
                            "AED" => 1,
                            "CAD" => 2.68,
                            "PHP" => 0.063,
                            'SAR' => 0.98,
                        ];
                        if($supplierAccountTransaction->transaction_type == 'Released') {

                            if ($purchasingOrder->currency != $supplierAccount->currency) {
                                // Convert the transactionAmount to the SupplierAccount currency
                                $purchasingOrderConversionRate = $conversionRates[$purchasingOrder->currency] ?? 1;
                                $supplierAccountConversionRate = $conversionRates[$supplierAccount->currency] ?? 1;
        
                                // Convert the transaction amount from the purchasing order currency to the supplier account currency
                                $transactionAmountInAED = $supplierAccountTransaction->transaction_amount * $purchasingOrderConversionRate; // Convert to base currency (e.g. AED)
                                $totalCostConverted = $transactionAmountInAED / $supplierAccountConversionRate; // Convert from AED to supplier account currency
                            } else {
                                // If the currencies are the same, no conversion is needed
                                $totalCostConverted = $supplierAccountTransaction->transaction_amount;
                            }
        
                                // Update the supplier account balance
                              $account_balance = $supplierAccount->current_balance - $totalCostConverted;
                              $supplierAccount->current_balance = $account_balance <= 0 ? 0 : $account_balance;
                              $supplierAccount->save();
                        }
                    }
                    $supplierAccountTransaction->transaction_type = 'Rejected';
                    $supplierAccountTransaction->status = 'Rejected';
                    $supplierAccountTransaction->remarks = $remarks;
                    $supplierAccountTransaction->save();
                }
          
            $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
                if ($purchasedOrderPaidAmounts) {
                    $purchasedOrderPaidAmounts->status = 'Rejected';
                    $purchasedOrderPaidAmounts->save();
                }
            $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
                if ($vendorPayment) {
                    $vendorPayment->status = 'Rejected';
                    $vendorPayment->save();
                }
            $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
                foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
                    $vehicleTransaction->status = 'Rejected';
                    $vehicleTransaction->save();
                }
            $purchasingordereventsLog = New PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Payment Initiation Rejected";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
            $purchasingordereventsLog->description = "The Payment Initation is being rejected";
            $purchasingordereventsLog->save();
                // revert the payment initiated qty from LOI items table
            if($purchasingOrder->is_demand_planning_purchase_order)
            {
                $pfiId = $purchasingOrder->PFIPurchasingOrder->pfi->id ?? '';
                $pfiItemLatest = PfiItem::where('pfi_id', $pfiId)
                                    ->where('is_parent', false)
                                    ->first();
                // Utilization qty update only for Toyota PO
                if($pfiItemLatest) {
                    // only toyota PFI have child , so if child exist it will be toyota PO
                    $initiatedQtyUpdatedLOIItemIds = [];
                    foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
                        $vehicle = Vehicles::find($vehicleTransaction->vehicles_id);
                        $masterModel = MasterModel::find($vehicle->model_id);
                        $possibleModels = MasterModel::where('model', $masterModel->model)
                                                ->where('sfx',  $masterModel->sfx)
                                                ->pluck('id')->toArray();
                        $pfiItem = PfiItemPurchaseOrder::where('purchase_order_id', $purchasingOrder->id)
                                                            ->whereIn('master_model_id', $possibleModels)
                                                            ->first();
                        $loiItem = LetterOfIndentItem::whereHas('pfiItems', function($query)use($pfiItem) {
                            $query->where('is_parent', false)
                            ->where('pfi_id', $pfiItem->pfi_id)
                            ->where('parent_pfi_item_id', $pfiItem->pfi_item_id);
                        })
                        ->where('po_payment_initiated_quantity', '>=', 1)
                        ->first();
                        // need to check with payment initiated qty with remaining qty
                        if($loiItem) {
                            // update po_payment_initiated_quantity and keep ids in array
                            $loiItem->po_payment_initiated_quantity = $loiItem->po_payment_initiated_quantity - 1;
                            $loiItem->save();

                            $initiatedQtyUpdatedLOIItemIds[] = $loiItem->id;
                        }else{
                            // revise updated qty of po_payment_initiated_quantity
                            foreach($initiatedQtyUpdatedLOIItemIds as $LOIItemId) {
                                $item = LetterOfIndentItem::find($LOIItemId);
                                $current_po_payment_initiated_quantity = $item->po_payment_initiated_quantity;
                                $item->po_payment_initiated_quantity = $current_po_payment_initiated_quantity + 1;
                                $item->save();
                            }
                            return response()->json(['error' => 'Unable to revert the LOI Qty initiation'], 500);
                        }
                    }
                
                }
            }
        DB::commit();
            return response()->json(['message' => 'Transition rejected successfully.'],200);

        } catch (Exception $e) { // Catch any exception
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function uploadSwiftFile(Request $request)
{
    // Validate the request
    $request->validate([
        'swiftFile' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx',
        'transition_id' => 'required|integer'
    ]);

    try {
        $transitionId = $request->input('transition_id');
        $file = $request->file('swiftFile');

        // Check if the file and transaction exist
        if ($file && $supplierAccountTransaction = SupplierAccountTransaction::find($transitionId)) {
            $fileNameToStore = time() . '_' . $file->getClientOriginalName();
            $path = $file->move(public_path('storage/swift_copies'), $fileNameToStore);
            $vehicleCount = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->count();
            $latestBatch = DB::table('purchasing_order_swift_copies')
                ->where('purchasing_order_id', $supplierAccountTransaction->purchasing_order_id)
                ->orderBy('created_at', 'desc')
                ->first();
            $batchNo = $latestBatch ? $latestBatch->batch_no + 1 : 1;
            $swiftcopy = new PurchasingOrderSwiftCopies();
            $swiftcopy->purchasing_order_id = $supplierAccountTransaction->purchasing_order_id;
            $swiftcopy->uploaded_by = auth()->user()->id;
            $swiftcopy->number_of_vehicles = $vehicleCount;
            $swiftcopy->batch_no = $batchNo;
            $swiftcopy->sat_id = $transitionId;
            $swiftcopy->file_path = 'storage/swift_copies/' . $fileNameToStore;
            $swiftcopy->save();
            $supplierAccountTransaction->transaction_type = 'Debit';
            $supplierAccountTransaction->status = 'Paid';
            $supplierAccountTransaction->save();
            $supplierAccount = SupplierAccount::find($supplierAccountTransaction->supplier_account_id);
            if ($supplierAccount) {
                $purchasingOrder = PurchasingOrder::find($supplierAccountTransaction->purchasing_order_id);
                if ($purchasingOrder) {
                    $currency = $purchasingOrder->currency;
                    $transactionAmount = $supplierAccountTransaction->transaction_amount;
                    $conversionRates = [
                        "USD" => 3.67,
                        "EUR" => 3.94,
                        "GBP" => 4.67,
                        "JPY" => 0.023,
                        "AED" => 1,
                        "AUD" => 2.29,
                        "CAD" => 2.68,
                        "PHP" => 0.063,
                        "SAR" => 0.98,
                    ];
                    // Check if the currencies are different
                    if ($purchasingOrder->currency != $supplierAccount->currency) {
                        // Convert the transactionAmount to the SupplierAccount currency
                        $purchasingOrderConversionRate = $conversionRates[$purchasingOrder->currency] ?? 1;
                        $supplierAccountConversionRate = $conversionRates[$supplierAccount->currency] ?? 1;

                        // Convert the transaction amount from the purchasing order currency to the supplier account currency
                        $transactionAmountInAED = $supplierAccountTransaction->transaction_amount * $purchasingOrderConversionRate; // Convert to base currency (e.g. AED)
                        $totalCostConverted = $transactionAmountInAED / $supplierAccountConversionRate; // Convert from AED to supplier account currency
                    } else {
                        // If the currencies are the same, no conversion is needed
                        $totalCostConverted = $supplierAccountTransaction->transaction_amount;
                    }

                        // Update the supplier account balance
                      $account_balance = $supplierAccount->current_balance - $totalCostConverted;
                      $supplierAccount->current_balance = $account_balance <= 0 ? 0 : $account_balance;
                      $supplierAccount->save();
                                                    
                }
            }
            $this->updateRelatedStatuses($transitionId);

            return response()->json(['success' => true, 'message' => 'Payment submitted successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid transition ID or file'], 422);
        }
        $purchasingordereventsLog = New PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Confirmed";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = "Finance Department Update the Swift Copy";
                $purchasingordereventsLog->save();
    } catch (\Exception $e) {
        Log::error('Payment submission failed', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Error submitting payment', 'error' => $e->getMessage()], 500);
    }
}

private function updateRelatedStatuses($transitionId)
{
    // Update PurchasedOrderPaidAmounts
    $purchasedOrderPaidAmounts = PurchasedOrderPaidAmounts::where('sat_id', $transitionId)->first();
    if ($purchasedOrderPaidAmounts) {
        $purchasedOrderPaidAmounts->status = 'Paid';
        $purchasedOrderPaidAmounts->save();
    }

    // Update VendorPaymentAdjustments
    $vendorPayment = VendorPaymentAdjustments::where('sat_id', $transitionId)->first();
    if ($vendorPayment) {
        $vendorPayment->status = 'Paid';
        $vendorPayment->save();
    }

    // Update VehiclesSupplierAccountTransactions
    $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
    foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
        $vehicleTransaction->status = 'Paid';
        $vehicleTransaction->save();
    }
}
public function getSwiftDetails($id)
{
    try {
        $supplierAccountTransaction = SupplierAccountTransaction::find($id);

        if (!$supplierAccountTransaction) {
            return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
        }
        $swiftCopy = DB::table('purchasing_order_swift_copies')
            ->where('sat_id', $id)
            ->first();
        $bankAccount = DB::table('bank_accounts')
            ->where('id', $supplierAccountTransaction->bank_accounts_id)
            ->first();

        $bankMaster = null;
        if ($bankAccount) {
            $bankMaster = DB::table('bank_master')
                ->where('id', $bankAccount->bank_master_id)
                ->first();
        }

        $swiftDetails = [
            'transition_id' => $supplierAccountTransaction->id,
            'transition_file' => $supplierAccountTransaction->transition_file ? asset($supplierAccountTransaction->transition_file) : null,
            'swift_copy_file' => $swiftCopy ? asset($swiftCopy->file_path) : null,
            'bank_accounts_id' => $supplierAccountTransaction->bank_accounts_id ?? 'N/A',
            'account_number' => $bankAccount->account_number ?? 'N/A',
            'currency' => $bankAccount->currency ?? 'N/A',
            'current_balance' => $bankAccount->current_balance ?? 'N/A',
            'bank_name' => $bankMaster->bank_name ?? 'N/A',
        ];

        // info($swiftDetails);
        return response()->json(['success' => true, 'data' => $swiftDetails]);
    } catch (\Exception $e) {
        Log::error('Failed to fetch swift details', ['error' => $e->getMessage()]);
        return response()->json(['success' => false, 'message' => 'Error fetching details', 'error' => $e->getMessage()], 500);
    }
}
public function paymentconfirm(Request $request)
    {
    $transitionId = $request->input('id');
    $supplierAccountTransaction = SupplierAccountTransaction::where('id', $transitionId)->first();
    if ($supplierAccountTransaction) {
        $supplierAccountTransaction->vendor_payment_status = 'Confirmed';
        $supplierAccountTransaction->save();
    }
    $vehiclesSupplierAccountTransactions = VehiclesSupplierAccountTransaction::where('sat_id', $transitionId)->get();
    foreach ($vehiclesSupplierAccountTransactions as $vehicleTransaction) {
        $vehicleTransaction->status = 'Confirmed';
        $vehicleTransaction->save();
    }
    return response()->json(['message' => 'Payment details saved successfully'], 200);
    }
    public function getdata()
    {
        $vins = Vehicles::orderBy('vin', 'ASC')
        ->whereNotNull('vin')
        ->with('variant.master_model_lines.brand', 'interior', 'exterior', 'warehouseLocation', 'document')
        ->get()
        ->unique('vin');
        return view('test', compact('vins'));
    }
    // Method to save DN numbers
    public function saveDnNumbers(Request $request)
    {
        $purchasingOrderId = $request->input('purchasingOrderId');
        $type = $request->input('type');
        
        if ($type == 'full') {
            $dnNumber = $request->input('dnNumber');

            // Retrieve all vehicles associated with the purchasing order
            $vehicles = Vehicles::where('purchasing_order_id', $purchasingOrderId)->get();
            $batchNumber = 1;
    
            // Check for the latest batch number among vehicles in the purchasing order
            foreach ($vehicles as $vehicle) {
                $latestVehicleDn = VehicleDn::where('vehicles_id', $vehicle->id)
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                if ($latestVehicleDn) {
                    $batchNumber = $latestVehicleDn->batch + 1;
                    break;
                }
            }
    
            // Loop through each vehicle and assign the same DN number
            foreach ($vehicles as $vehicle) {
                // Create a new entry in the VehicleDn table
                $vehicledn = new VehicleDn();
                $vehicledn->dn_number = $dnNumber;
                $vehicledn->vehicles_id = $vehicle->id;
                $vehicledn->created_by = Auth::id();
                $vehicledn->batch = $batchNumber;
                $vehicledn->save();
    
                // Update the vehicle's dn_id field with the new VehicleDn record's ID
                $vehicle->dn_id = $vehicledn->id;
                $vehicle->save();
            }
        } else if ($type == 'vehicle') {
            // Retrieve vehicles associated with the purchasing order
            $vehicles = Vehicles::where('purchasing_order_id', $purchasingOrderId)->get();
            $batchNumber = 1;
            foreach ($vehicles as $vehicle) {
                $latestVehicleDn = VehicleDn::where('vehicles_id', $vehicle->id)
                                            ->orderBy('created_at', 'desc')
                                            ->first();
                if ($latestVehicleDn) {
                    $batchNumber = $latestVehicleDn->batch + 1;
                    break;
                }
            }
    
            // Get vehicle DN data from the request and ensure it's an array
            $vehicleDNData = $request->input('vehicleDNData', []);
    
            foreach ($vehicleDNData as $vehicleData) {
                $vehicleId = $vehicleData['vehicleId'];
                $dnNumber = $vehicleData['dnNumber'];
    
                if ($dnNumber) {
                    $vehicledn = new VehicleDn();
                    $vehicledn->dn_number = $dnNumber;
                    $vehicledn->vehicles_id = $vehicleId;
                    $vehicledn->created_by = Auth::id();
                    $vehicledn->batch = $batchNumber;
                    $vehicledn->save();
    
                    // Attempt to find the vehicle by ID
                    $vehicle = Vehicles::find($vehicleId);
    
                    // Check if the vehicle exists before assigning the dn_id
                    if ($vehicle) {
                        $vehicle->dn_id = $vehicledn->id;
                        $vehicle->save();
                    } else {
                        // Log or handle the error if the vehicle ID is invalid
                        \Log::error("Vehicle with ID {$vehicleId} not found.");
                    }
                }
            }
        }
        return response()->json(['success' => true]);
    }    
public function getVehiclesdn($purchaseOrderId) {
    $vehicles = Vehicles::where('purchasing_order_id', $purchaseOrderId)
    ->where('status', 'Approved')
    ->whereNull('dn_id')
        ->with(['variant.brand', 'variant.master_model_lines', 'vehiclePurchasingCost'])
        ->get();
    return response()->json($vehicles);
}
public function checkPoNumberedit(Request $request)
{
    $poNumber = $request->input('po_number');
    $currentId = $request->input('purchasing_order_id');

    // Check if PO number exists for another ID
    $exists = PurchasingOrder::where('po_number', $poNumber)
                ->where('id', '!=', $currentId)
                ->exists();

    return response()->json(['exists' => $exists]);
}
public function sendTransferCopy(Request $request) {
    $purchasingOrder = PurchasingOrder::find($request->purchasing_order_id);
    $supplierAccountTransaction = SupplierAccountTransaction::where('id', $request->transition_id)->first();
        if(!$purchasingOrder->supplier->email )  {
            return response()->json(['success' => false, 'message' => 'Email sending failed',
            'error' => "Supplier email not Found! Please update it!"], 500);
        }
        if(!$supplierAccountTransaction->transition_file ) {
            return response()->json(['success' => false, 'message' => 'Email sending failed',
            'error' => "Transfer copy file is not found! Please contact admin!"], 500);
        }
        if(!$purchasingOrder->pl_number) {
            return response()->json(['success' => false, 'message' => 'Email sending failed',
            'error' => "Invoice number (PFI number) is not added ! Please contact procurement!"], 500);
        }
            try{
                $Torecipient = $purchasingOrder->supplier->email;
                $recipients = config('mail.custom_recipients.procurement');
                Mail::to($Torecipient)->cc($recipients)->send(new TransferCopyEmail($purchasingOrder->pl_number,
                $supplierAccountTransaction->transaction_amount, $supplierAccountTransaction->transition_file));

                $supplierAccountTransaction->is_transfer_copy_email_send = 1;
                $supplierAccountTransaction->save();
                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Payment Transfer Copy Send to Supplier";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = 'The Email was sent to '.$purchasingOrder->supplier->supplier.' at '.$Torecipient;
                $purchasingordereventsLog->save();
                return response()->json(['success' => true, 'message' => 'The Email was sent to '.$purchasingOrder->supplier->supplier.' at '.$Torecipient . " successfully."]);
            } catch (\Exception $e) {
                Log::error('Email sending failed', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Email sending failed',
                 'error' => $e->getMessage()], 500);
            }

    }
    public function sendSwiftCopy(Request $request) {

        $purchasingOrder = PurchasingOrder::find($request->purchasing_order_id);
        $supplierAccountTransaction = SupplierAccountTransaction::select('id','transaction_amount')
                                            ->where('id', $request->transition_id)->first();

        if(!$purchasingOrder->supplier->email )  {
            return response()->json(['success' => false, 'message' => 'Email sending failed',
            'error' => "Supplier email not Found! Please update it!"], 500);
        }
        $transitionSwifyCopy = PurchasingOrderSwiftCopies::select('sat_id','purchasing_order_id','file_path')
                                        ->where('sat_id', $request->transition_id)
                                        ->where('purchasing_order_id', $request->purchasing_order_id)->first();

        if(!$transitionSwifyCopy->file_path) {
            return response()->json(['success' => false, 'message' => 'Email sending failed',
            'error' => "Swift copy file is not found! Please contact admin!"], 500);
        }
        if(!$purchasingOrder->pl_number) {
            return response()->json(['success' => false, 'message' => 'Email sending failed',
            'error' => "Invoice number(PFI number) is not added! Please contact procurement!"], 500);
        }
            try{
                $Torecipient = $purchasingOrder->supplier->email;
                $recipients = config('mail.custom_recipients.procurement');
                Mail::to($Torecipient)->cc($recipients)->send(new SwiftCopyEmail($purchasingOrder->pl_number,
                $supplierAccountTransaction->transaction_amount, $transitionSwifyCopy->file_path));

                $supplierAccountTransaction->is_swift_copy_email_send = 1;
                $supplierAccountTransaction->save();

                $purchasingordereventsLog = new PurchasingOrderEventsLog();
                $purchasingordereventsLog->event_type = "Swift Copy Send to Supplier";
                $purchasingordereventsLog->created_by = auth()->user()->id;
                $purchasingordereventsLog->purchasing_order_id = $purchasingOrder->id;
                $purchasingordereventsLog->description = 'The Email was sent to '.$purchasingOrder->supplier->supplier.' at '.$Torecipient;
                $purchasingordereventsLog->save();
                return response()->json(['success' => true, 'message' => 'The Email was sent to '.$purchasingOrder->supplier->supplier.' at '.$Torecipient .' successfully.']);
            } catch (\Exception $e) {
                Log::error('Email sending failed', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Email sending failed',
                 'error' => $e->getMessage()], 500);
            }
    }
    public function paymentAdjustment(Request $request) {
        // info($request->all());
        // add entry in supplier account transaction with type debit with payment adjustment amount
        DB::beginTransaction();

        try{
            $supplierAccount = SupplierAccountTransaction::findOrFail($request->po_transition_id);
            // already paid amount need to be reveresd based on new payment adjustment
        
            $purchaseOrder = PurchasingOrder::findOrFail($request->payment_adjustment_po_id);
            $alreadypaidAmount = PurchasedOrderPaidAmounts::where('purchasing_order_id',$supplierAccount->purchasing_order_id )
                                ->where('sat_id', $supplierAccount->id)->first();
                info($alreadypaidAmount);
            if($alreadypaidAmount) {
                $alreadypaidAmount->amount = $alreadypaidAmount->amount - $request->payment_adjustment_amount ?? 0;
                $alreadypaidAmount->save();
            }
            // for adjusted po already apid amount
            $purchasedorderpaidamounts = new PurchasedOrderPaidAmounts();
            $purchasedorderpaidamounts->amount = $request->payment_adjustment_amount ?? 0;
            $purchasedorderpaidamounts->created_by = Auth::id();
            $purchasedorderpaidamounts->purchasing_order_id = $request->payment_adjustment_po_id;
            $purchasedorderpaidamounts->status = 'Paid';
            $purchasedorderpaidamounts->sat_id = $supplierAccount->id;
            $purchasedorderpaidamounts->save();

            $supplierAccountTransaction = new SupplierAccountTransaction();
            $supplierAccountTransaction->transaction_type = "Debit";
            $supplierAccountTransaction->purchasing_order_id = $request->payment_adjustment_po_id;
            $supplierAccountTransaction->supplier_account_id = $supplierAccount->supplier_account_id;
            $supplierAccountTransaction->created_by = Auth::id();
            $supplierAccountTransaction->remarks = $request->remarks;
            $supplierAccountTransaction->account_currency = $supplierAccount->account_currency;
            $supplierAccountTransaction->transaction_amount = $request->payment_adjustment_amount ?? 0;
            $supplierAccountTransaction->save();

            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Payment Adjustment";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $request->payment_adjustment_po_id;
            $purchasingordereventsLog->description = $request->payment_adjustment_amount.' '.$supplierAccount->account_currency.' payment adjustment from '.$request->payment_from_po ?? '';
            $purchasingordereventsLog->save();

            $purchasingordereventsLog = new PurchasingOrderEventsLog();
            $purchasingordereventsLog->event_type = "Payment Adjustment";
            $purchasingordereventsLog->created_by = auth()->user()->id;
            $purchasingordereventsLog->purchasing_order_id = $supplierAccount->purchasing_order_id;
            $purchasingordereventsLog->description = $request->payment_adjustment_amount.' '.$supplierAccount->account_currency.' payment adjustment towards '.$purchaseOrder->po_number ?? '';
            $purchasingordereventsLog->save();

            $supplierAccount->transaction_amount =   $supplierAccount->transaction_amount - $request->payment_adjustment_amount;
            $supplierAccount->save();

            (new UserActivityController)->createActivity('Payment Adjustment successfully Done.');
          
            DB::commit();
            return redirect()->back()->with('success', 'Payment Adjustment successfully done towards '.$purchaseOrder->po_number.'.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Adjustment failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('roor', 'Payment Adjustment failed due to '. $e->getMessage().'.');
        
        }

    }
}

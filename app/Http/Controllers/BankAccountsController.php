<?php

namespace App\Http\Controllers;
use App\Models\BankAccounts;
use App\Models\BankAccountLog;
use App\Models\PurchasedOrderPaidAmounts;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class BankAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    (new UserActivityController)->createActivity('View Bank Accounts');
    $bankaccounts = BankAccounts::get();
    $exchangeRates = [
        'USD' => 3.67,
        'EUR' => 4.20,
        'JPY' => 0.034,
        'CAD' => 2.89,
        'AED' => 1
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
    $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
    return view('bankaccounts.index', compact('bankaccounts', 'totalBalanceAED', 'availableFunds'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    $bankaccount = BankAccounts::findOrFail($id);
    if (request()->ajax()) {
        $transactions = $bankaccount->transactions;
        return DataTables::of($transactions)
            ->addColumn('created_by', function($transaction) {
                return $transaction->user->name;
            })
            ->editColumn('created_at', function($transaction) {
                return $transaction->created_at->format('d M Y h:i A');
            })
            ->editColumn('type', function($transaction) {
                return ucfirst($transaction->type);
            })
            ->editColumn('amount', function($transaction) {
                return number_format($transaction->amount, 0, '.', ',');
            })
            ->make(true);
    }
    return view('bankaccounts.show', compact('bankaccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function updateBalance(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bank_accounts,id',
            'new_balance' => 'required|numeric'
        ]);
        $bankAccount = BankAccounts::findOrFail($request->id);
        $currentBalance = $bankAccount->current_balance;
        $difference = $request->new_balance - $currentBalance;
        $type = $difference > 0 ? 'debit' : 'credit';
        $bankAccount->current_balance = $request->new_balance;
        $bankAccount->save();
        if($difference != 0)
        {
        $bankaccountlog = New BankAccountLog();
        $bankaccountlog->amount = abs($difference);
        $bankaccountlog->type = $type;
        $bankaccountlog->bank_accounts_id = $request->id;
        $bankaccountlog->created_by = auth()->user()->id;
        $bankaccountlog->save();
        }
        return response()->json(['success' => 'Balance updated successfully']);
    }
}

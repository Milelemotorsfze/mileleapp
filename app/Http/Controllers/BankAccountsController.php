<?php

namespace App\Http\Controllers;
use App\Models\BankAccounts;
use App\Models\BankAccountLog;
use App\Models\BankMaster;
use App\Models\PurchasedOrderPaidAmounts;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\UserActivityController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BankAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    (new UserActivityController)->createActivity('View Bank Accounts');
    $bankaccounts = BankAccounts::with('bank')->get();
    $exchangeRates = [
        'USD' => 3.67,
        'EUR' => 4.20,
        'JPY' => 0.034,
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
    $availableFunds = $totalBalanceAED - $suggestedPaymentTotalAED;
    return view('bankaccounts.index', compact('bankaccounts', 'totalBalanceAED', 'availableFunds'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        (new UserActivityController)->createActivity('Create Bank Account');
        $banks = BankMaster::all();
        return view('bankaccounts.create', compact('banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'entity' => 'required|string|max:255',
            'bank_master_id' => 'required|integer|exists:bank_master,id',
            'account_number' => 'required|string|max:255|unique:bank_accounts,account_number',
            'currency' => 'required|string|in:USD,EUR,JPY,CAD,AED,PHP,SAR',
            'current_balance' => 'required|numeric|min:0',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create a new bank account
        $bankAccount = new BankAccounts();
        $bankAccount->entity = $request->input('entity');
        $bankAccount->bank_master_id = $request->input('bank_master_id');
        $bankAccount->account_number = $request->input('account_number');
        $bankAccount->currency = $request->input('currency');
        $bankAccount->current_balance = $request->input('current_balance');
        $bankAccount->save();

        // Redirect to a success page or the index page
        return redirect()->route('bankaccounts.index')->with('success', 'Bank account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
    (new UserActivityController)->createActivity('View Bank account Transitions');
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
    public function edit($id)
    {
        $bankAccount = BankAccounts::findOrFail($id);
        $banks = BankMaster::all();
        return view('bankaccounts.edit', compact('bankAccount', 'banks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'entity' => 'required|string|max:255',
            'bank_master_id' => 'required|integer',
            'account_number' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
        ]);
        $bankAccount = BankAccounts::findOrFail($id);
        $bankAccount->entity = $request->input('entity');
        $bankAccount->bank_master_id = $request->input('bank_master_id');
        $bankAccount->account_number = $request->input('account_number');
        $bankAccount->currency = $request->input('currency');
        $bankAccount->save();
        return redirect()->route('bankaccounts.index')->with('success', 'Bank account updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bankAccount = BankAccounts::findOrFail($id);
    // Check if there are any related logs
    $hasLogs = \DB::table('bank_account_log')->where('bank_accounts_id', $id)->exists();
    if ($hasLogs) {
        return redirect()->route('bankaccounts.index')->with('error', 'Cannot delete bank account as there are related logs.');
    }
    $bankAccount->delete();
    return redirect()->route('bankaccounts.index')->with('success', 'Bank account deleted successfully');
    }
    public function updateBalance(Request $request)
    {
        (new UserActivityController)->createActivity('Update the Current Balance');
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
    public function getBankAccounts(Request $request)
    {
        $bankId = $request->query('bank_id');
        $accounts = BankAccounts::where('bank_master_id', $bankId)->get();
        return response()->json($accounts);
    }
}

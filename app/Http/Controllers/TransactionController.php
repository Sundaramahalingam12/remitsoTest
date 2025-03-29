<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|exists:accounts,account_number',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
        ]);

        // Get the account and check ownership
        $account = Account::where('account_number', $validated['account_number'])
                        ->where('user_id', auth()->id()) // Ensures user owns the account
                        ->first();

        // If account is not found or doesn't belong to user, return error
        if (!$account) {
            return response()->json(['message' => 'Unauthorized: You do not own this account'], 403);
        }

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'account_id' => $account->id,
                'amount' => $validated['amount'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
            ]);

            // Update account balance
            if ($validated['type'] === 'credit') {
                $account->increment('balance', $validated['amount']);
            } else {
                if ($account->balance < $validated['amount']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Insufficient balance'], 400);
                }
                $account->decrement('balance', $validated['amount']);
            }

            DB::commit();
            return response()->json([
                'message' => 'Transaction recorded successfully',
                'transaction' => $transaction
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Transaction failed', 'error' => $e->getMessage()], 500);
        }
    }


    public function getTransactions(Request $request)
    {
        $request->validate([
            'account_id' => 'required|uuid|exists:accounts,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
        ]);

        // Fetch the account while ensuring the user is the owner
        $account = Account::where('id', $request->account_id)
                        ->where('user_id', auth()->id()) // Ensures user owns the account
                        ->first();

        // If account is not found or doesn't belong to user, return error
        if (!$account) {
            return response()->json(['error' => 'Unauthorized or account not found'], 403);
        }

        // Fetch transactions
        $query = Transaction::where('account_id', $account->id);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return response()->json($transactions, 200);
    }

}

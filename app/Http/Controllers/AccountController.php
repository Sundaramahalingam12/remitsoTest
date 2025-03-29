<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\User;
class AccountController extends Controller
{
// public function __construct(Type $var = null) {
//     $this->middleware('auth:sanctum');
// }

public function store(Request $request)
{
    $validated = $request->validate([
        'account_name' => 'required|string|unique:accounts,account_name',
        'account_type' => 'required|in:Personal,Business',
        'currency' => 'required|in:USD,EUR,GBP',
        'balance' => 'nullable|numeric|min:0.01',
    ]);
    do {
        $validated['account_number'] = $this->generateLuhnAccountNumber(mt_rand(12, 16));
    } while (Account::where('account_number', $validated['account_number'])->exists());

    $validated['user_id'] = auth()->id();
    $account = Account::create($validated);

    return response()->json($account, 201);
}

/**
 * Generate a unique, Luhn-compliant account number.
 */
private function generateLuhnAccountNumber($length = 12)
{
    $number = '';

    for ($i = 0; $i < $length - 1; $i++) {
        $number .= mt_rand(0, 9);
    }

    return $number . $this->calculateLuhnCheckDigit($number);
}

    public function validateLuhnAccount($number)
    {
        return response()->json([
            'account_number' => $number,
            'valid' => $this->calculateLuhnCheckDigit(substr($number, 0, -1)) == substr($number, -1)
        ]);
    }

    private function calculateLuhnCheckDigit($number)
    {
        $digits = array_map('intval', str_split($number));
        $sum = 0;
        $alt = false;

        for ($i = count($digits) - 1; $i >= 0; $i--) {
            if ($alt) {
                $digits[$i] *= 2;
                if ($digits[$i] > 9) {
                    $digits[$i] -= 9;
                }
            }
            $sum += $digits[$i];
            $alt = !$alt;
        }

        return (10 - ($sum % 10)) % 10;
    }

    public function show($account_number)
    {
        $account = Account::where('account_number', $account_number)
                          ->where('user_id', auth()->id())
                          ->firstOrFail();
    
        return response()->json($account, 200);
    }
    
    public function update(Request $request, $account_number)
    {
        $account = Account::where('account_number', $account_number)->firstOrFail();

        $validated = $request->validate([
            'account_name' => 'sometimes|required|string|unique:accounts,account_name,' . $account->id,
            'account_type' => 'sometimes|required|in:Personal,Business',
            'currency' => 'sometimes|required|in:USD,EUR,GBP',
            'balance' => 'sometimes|numeric|min:0.01',
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    public function delete($account_number)
    {
        $account = Account::where('account_number', $account_number)->firstOrFail();
        $account->delete();

        return response()->json(['message' => 'Account deactivated successfully'], 200);
    }

    
}

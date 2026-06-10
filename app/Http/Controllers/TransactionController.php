<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function details(Transaction $transaction)
    {
        $transaction->load(['customer', 'items.product']);

        return view('transactions.detail', compact('transaction'));
    }
}

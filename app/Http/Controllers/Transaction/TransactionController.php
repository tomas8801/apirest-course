<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;
use App\Traits\ApiResponser;
use App\Http\Controllers\ApiController;

class TransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $transactions = Transaction::all();
        return $this->showAll($transactions);
    }

    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }

}

<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;
use App\Traits\ApiResponser;
use App\Http\Controllers\ApiController;

class TransactionController extends ApiController
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::all();
        return $this->showAll($transactions);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }

}

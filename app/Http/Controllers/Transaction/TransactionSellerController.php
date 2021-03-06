<?php

namespace App\Http\Controllers\Transaction;

use App\Transaction;
use App\Http\Controllers\ApiController;

class TransactionSellerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Transaction $transaction)
    {
        # Si queremos obtener el vendedor de una transaccion especifica
        $seller = $transaction->product->seller;
        return $this->showOne($seller);
    }

}

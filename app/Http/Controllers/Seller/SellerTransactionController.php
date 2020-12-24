<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class SellerTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Seller $seller)
    {
        $transactions = $seller->products()->whereHas('transactions')
                                        ->with('transactions')
                                        ->get()
                                        ->pluck('transactions')
                                        ->collapse();

        return $this->showAll(new Collection($transactions));
    }

}

<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Seller $seller)
    {
        $buyers = $seller->products()->whereHas('transactions')->with('transactions.buyer')->get()->pluck('transactions')->collapse()->pluck('buyer')->unique()->values();
        return $this->showAll(new Collection($buyers));
    }

}

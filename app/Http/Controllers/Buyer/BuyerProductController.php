<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class BuyerProductController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product')->get()->pluck('product');
        return $this->showAll(new Collection($products));
    }

}

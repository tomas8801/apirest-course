<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;



class BuyerSellerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        # traemos las transacciones de un comprador junto a la lista de productos y sus vendedores
        $sellers = $buyer->transactions()->with('product.seller')
            ->get()
            # solo nos interesa la lista de vendedores
            ->pluck('product.seller')
            # que no se repitan
            ->unique('id')
            # reordenamos los indices e eliminamos elementos vacios
            ->values();

        return $this->showAll(new Collection($sellers));
    }


}

<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;



class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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


    /**
     * Display the specified resource.
     *
     * @param  \App\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //
    }


}

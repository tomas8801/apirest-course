<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class BuyerCategoryController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
                                            ->get()
                                            ->pluck('product.categories')
                                            # convertiremos una serie de listas en una sola
                                            ->collapse()
                                            ->unique('id')
                                            ->values();
        return $this->showAll(new Collection($categories));
    }


}

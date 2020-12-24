<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Seller $seller)
    {
        $categories = $seller->products()->with('categories')->get()->pluck('categories')->collapse()->values();
        return $this->showAll(new Collection($categories));
    }

}

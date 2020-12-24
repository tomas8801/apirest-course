<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class CategoryBuyerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category)
    {
        $buyers = $category->products()->whereHas('transactions')->with('transactions.buyer')->get()->pluck('transactions')
        ->collapse()
        ->pluck('buyer')->unique()->values();


        return $this->showAll(new Collection($buyers));
    }



}

<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class CategoryTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category)
    {
        $transactions = $category->products()->whereHas('transactions')->with('transactions')->get()->pluck('transactions')->collapse();
        return $this->showAll(new Collection($transactions));
    }


}

<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;

class CategorySellerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category)
    {
        $sellers = $category->products()->with('seller')
                                        ->get()
                                        ->pluck('seller')
                                        ->unique()
                                        ->values();
        return $this->showAll(new Collection($sellers));
    }

}

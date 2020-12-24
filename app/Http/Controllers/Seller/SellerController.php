<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $sellers = Seller::has('products')->get();

        return $this->showAll($sellers);
    }


    public function show(Seller $seller)
    {
        # $seller = Seller::has('products')->findOrFail($id);

        return $this->showOne($seller);

    }


}

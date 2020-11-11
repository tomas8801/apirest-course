<?php

namespace App;

use App\Scopes\SellerScope;
use App\Product;

class Seller extends User
{

    protected static function boot()
    {
        parent::boot();
        static::getGlobalScope(new SellerScope);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

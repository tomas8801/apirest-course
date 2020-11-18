<?php

namespace App;

use App\Product;
use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;

class Seller extends User
{

    public $transformer = SellerTransformer::class;

    # el metodo boot normalmente se utiliza para construir e inicializar el modelo
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

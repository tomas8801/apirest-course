<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SellerScope implements Scope
{
    # Un global scope es una consulta que podemos ejecutar de manera global en un modelo cade vez que se realizen consultas sobre él mismo.
    # Modificara la consulta tipica del modelo y agregara el has->products
    public function apply(Builder $builder, Model $model)
    {
        $builder->has('products');
    }
}

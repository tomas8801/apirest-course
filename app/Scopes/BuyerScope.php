<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class BuyerScope implements Scope
{
    # Un global scope es una consulta que podemos ejecutar de manera global en un modelo cade vez que se realizen consultas sobre él mismo.
    # Modificara la consulta tipica del modelo y agregara el has->transaction
    public function apply(Builder $builder, Model $model)
    {
        $builder->has('transactions');
    }
}

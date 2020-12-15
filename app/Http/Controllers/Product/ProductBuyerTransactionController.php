<?php

namespace App\Http\Controllers\Product;

use App\User;
use App\Product;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use App\Transformers\TransactionTransformer;

class ProductBuyerTransactionController extends ApiController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . TransactionTransformer::class)->only(['store']);
    }

    public function store(Request $request, Product $product, User $buyer)
    {
        // Validamos
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];
        $this->validate($request, $rules);

        // Comprobamos que el comprador y el vendedor sean diferentes
        if($product->seller_id == $buyer->id) {
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 409);
        }

        // El comprador debe estar verificado
        if(!$buyer->esVerificado()) {
            return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
        }

        // El vendedor debe estar verificado
        if(!$product->seller->esVerificado()) {
            return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
        }

        // El producto debe estar disponible
        if(!$product->estaDisponible()) {
            return $this->errorResponse('El producto para esta transaccion no esta disponible', 409);
        }

        // La cantidad de productos pedidos no debe exceder a la cantidad disponible
        if($product->quantity < $request->quantity) {
            return $this->errorResponse('El producto no tiene la cantidad disponible requerida para esta transaccion', 409);
        }

        // ----------------------
        // Las transacciones son operaciones que se realizan completas de a una sola vez, una por una
        // y en caso de falla todo se regresa a su estado normal
        // Utilazomos el fachade DB que nos proporciona Laravel
        return DB::transaction(function() use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }

}

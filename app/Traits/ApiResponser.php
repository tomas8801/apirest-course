<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

trait ApiResponser
{
    # Este metodo será el encardado de crear respuestas satisfactorias
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    # Este metodo devolverá respuestas de error
    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    # Este metodo mostrará una coleccion o lista completa de elementos.
    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse(new Collection(['data' => $collection]), 200);
    }

    # Este metodo mostrará una instancia de un modelo especifico.
    protected function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse(['data' => $instance], 200);
    }

     # Este metodo mostrará un mensaje
     protected function showMessage($message, $code = 200)
     {
         return $this->successResponse(['data' => $message], 200);
     }
}

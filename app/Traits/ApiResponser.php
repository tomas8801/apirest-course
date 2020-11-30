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
        if ($collection->isEmpty()) {
            return $this->successResponse(new Collection(['data' => $collection]), $code);
        }

        # Recuperamos el transformador de esa instancia
        $transformer = $collection->first()->transformer;

        # Filtramos la data
        $collection = $this->filterData($collection, $transformer);
        # Ordenamos la data
        $collection = $this->sortData($collection,$transformer);

        # Finalmente la transformamos
        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse($collection, 200);
    }

    # Este metodo mostrará una instancia de un modelo especifico.
    protected function showOne(Model $instance, $code = 200)
    {
        # Recuperamos el transformador de esa instancia
        $transformer = $instance->first()->transformer;

        # La transformamos
        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse( $instance, 200);
    }

    # Este metodo mostrará un mensaje
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], 200);
    }

    # Este metodo filtrara la data en base a atributos pasados por URL
    protected function filterData(Collection $collection, $transformer)
    {
        # Recorremos los parametros de la URL
        foreach(request()->query() as $query => $value) {
            # Transformamos ese campo al original
            $attribute = $transformer::originalAttribute($query);

            if(isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }



    # Este metodo ordenara la data en base a un atributo pasado por URL
    protected function sortData(Collection $collection, $transformer)
    {
    # Verificamos si existe dicho atributo
    if(request()->has('sort_by')) {
        $attribute = $transformer::originalAttribute(request()->sort_by);
        # Ordenamos la coleccion en torno a ese atributo
        $collection = $collection->sortBy->{$attribute};
    }
    return $collection;
    }

    # Este metodo transformara los atributos de las respuestas por los transformers.
    protected function transformData($data, $transformer)
    {
        # usamos el helper de fractal
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }
}

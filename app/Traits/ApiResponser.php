<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        # Paginamos la data
        $collection = $this->paginateData($collection);
        # Finalmente la transformamos
        $collection = $this->transformData($collection, $transformer);
        # Guardamos en caché ele st
        $collection = $this->cacheResponse($collection);


        return $this->successResponse(new Collection(['data' => $collection]), $code);
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

    # Este metodo paginara la coleccion
    protected function paginateData(Collection $collection)
    {
        # Usamos una clase que nos proporciona un paginador de Laravel
        # Conocemos la pagina en la cual estamos
        $page = LengthAwarePaginator::resolveCurrentPage();

        # Paginacion personalizada...
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        Validator::validate(request()->all(), $rules);

        # Definimos los elementos por pagina
        $perPage = 15;
        if(request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }

        # Dividimos la coleccion entre paginas. Slice devuelve un segmento de la colección comenzando en el índice dado.
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

        # Creamos la instancia del paginador como tal
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        # Le pedimos a los resultados paginados que agreguen la lista de todos los parametros pasados por URL.
        $paginated->appends(request()->all());

        return $paginated;
    }


    # Este metodo transformara los atributos de las respuestas por los transformers.
    protected function transformData($data, $transformer)
    {
        # usamos el helper de fractal
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    # Este metodo guardara en cache las respuestas dentro de tiempo determinado evitando sobrecargar la base de datos.
    protected function cacheResponse($data)
    {
        # Obtenemos la url actual
        $url = request()->url();

        # Obtenemos los parametros de la url
        $queryParams = request()->query();

        # Ordenamos el array de parametros por clave
        ksort($queryParams);

        # Transformamos en string los parametros
        $queryString = http_build_query($queryParams);

        # Contruimos la url commpleta
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 30, function () use ($data) {
            return $data;
        });
    }
}

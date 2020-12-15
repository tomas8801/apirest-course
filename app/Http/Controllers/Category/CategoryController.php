<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Traits\ApiResponser;
use App\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{
    use ApiResponser;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('transform.input:' . CategoryTransformer::class)->only(['store', 'update']);
    }

    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required'
        ];

        $this->validate($request, $rules);

        $category = Category::create($request->all());

        return $this->showOne($category, 201);
    }


    public function show(Category $category)
    {
        return $this->showOne($category);
    }



    public function update(Request $request, Category $category)
    {
        if($request->has('name')){
            $category->name = $request->name;
        }
        if($request->has('description')){
            $category->description = $request->description;
        }

        $category->save();
        return $this->showOne($category);
    }


    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}

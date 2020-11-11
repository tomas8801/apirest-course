<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /**
        * primero realizaremos un truncate de todas la tablas para evitar
        * que se llene constantemente la ddbb cada vez que ejecutemos el seeder
        */

        # debemos desactivar la verificacion de las claves foreaneas
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        # Desactivamos los eventos de todos los modelos para que no haya errores
        # a la hora de disparar los seeders.
        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();

        factory(User::class, 200)->create();

        factory(Category::class, 30)->create();

        factory(Product::class, 1000)->create()->each(function($product){
            # tremos una cantidad aleatoria de categorias para asociarlas con el producto
            # solo queremos el id de esas categorias
            $categories = Category::all()->random(rand(1, 5))->pluck('id');
            # y asociamos esas categorias con el producto en la tabla pivote
            $product->categories()->attach($categories);
        });

        factory(Transaction::class, 1000)->create();

    }
}

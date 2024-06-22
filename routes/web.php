<?php

use ElasticOrm\Model;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $model =  new Model();
    $model->where("any")->where([
        'any' => 777,
        'any2' => [1, 2, 5]
    ])->whereNot([
        "any",
        'any' => 777,
        'any2' => [1, 2, 5]
    ])->where("any", ">", 70)->like("column1", "%ahmed")->notLike("column2", "ali%")->dd();

    return view('welcome');
});

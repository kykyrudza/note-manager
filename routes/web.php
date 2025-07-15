<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/tasks', function (){
    return view('tasks/index');
});

Route::get('/tasks/{id}', function ($id){
    return view('tasks/show', ['taskId' => $id]);
});

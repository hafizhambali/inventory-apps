<?php

use App\Models\Employee;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/item', function (Request $request){
    $data = Item::all();

    foreach ($data as $item) {
        $item->description =  $item->brand->name . ' - ' . $item->type->name;
    }

    return $data;
})->name('api.items');


Route::get('/item', function (Request $request){
    // Retrieve search query parameter
    $searchQuery = $request->input('search');

    // Query items where employee_id is null
    $query = Item::with(['brand', 'type'])->whereNull('employee_id');

    // If search query is provided, filter the results
    if ($searchQuery) {
        $query->where(function ($q) use ($searchQuery) {
            $q->where('serial_number', 'like', '%' . $searchQuery . '%')
            ->orWhereHas('type', function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%');
            })
            ->orWhereHas('brand', function ($q) use ($searchQuery) {
                $q->where('name', 'like', '%' . $searchQuery . '%');
            });
        });
    }

    // Execute the query
    $data = $query->get();

    // Modify the description for each item
    foreach ($data as $item) {
        $item->description = $item->brand->name . ' - ' . $item->type->name;
    }

    return $data;
})->name('api.items.available');

Route::get('/employee', function (Request $request){
    // Retrieve search query parameter
    $searchQuery = $request->input('search');

    // Retrieve all employees
    $query = Employee::query();

    // If search query is provided, filter the results
    if ($searchQuery) {
        $query->where(function ($q) use ($searchQuery) {
            $q->where('name', 'like', '%' . $searchQuery . '%')
              ->orWhere('employee_id', 'like', '%' . $searchQuery . '%')
              ->orWhereHas('department', function ($q) use ($searchQuery) {
                  $q->where('name', 'like', '%' . $searchQuery . '%');
              });
        });
    }

    // Execute the query
    $data = $query->get();

    // Modify the description for each item
    foreach ($data as $item) {
        $item->description = $item->department->name . ' - ' . $item->employee_id;
    }

    return $data;
})->name('api.employees');




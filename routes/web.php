<?php

use App\Livewire\Department;
use App\Livewire\DepartmentController;
use App\Livewire\BrandController;
use App\Livewire\EmployeeController;
use App\Livewire\InventoryController;
use App\Livewire\ItemController;
use App\Livewire\Staff;
use App\Livewire\TypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::get('/employee', EmployeeController::class)->name('employee');
    Route::get('/department', DepartmentController::class)->name('department');
    Route::get('/brand', BrandController::class)->name('brand');
    Route::get('/item', ItemController::class)->name('item');
    Route::get('/type', TypeController::class)->name('type');
    Route::get('/inventory', InventoryController::class)->name('inventory');

});

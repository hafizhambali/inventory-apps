<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\Type;
use App\Models\Brand;
use WireUi\Traits\Actions;

class InventoryController extends Component
{
    use Actions;

    public $title = 'Inventory';
    public $modalIsActive = false;
    public $modalReturnIsActive = false;
    public $id = '' ;
    public $returnId = '' ;

    public $status;

    public $selected_item = [];

    public $searchTerm = '';

    public $item ;
    public $items ;
    public $brand  = '';
    public $employee_id  = '';
    public $borrow_date = '';
    protected $rules = [
        'employee_id' => 'required',
        'borrow_date' => 'required',
        'selected_item' => 'required',
    ];

    public function mount()
    {
        $data = Item::whereNull('employee_id')->get();

        foreach ($data as $inventory) {
            $inventory->description =  $inventory->brand->name . ' - ' . $inventory->type->name;
        }
    
        $this->items = $data;
    }

    public function render()
    {
        $query = Item::with(['brand', 'type', 'employee']);

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('serial_number', 'like', '%' . $this->searchTerm . '%')
                      ->orWhereHas('brand', function ($query) {
                          $query->where('name', 'like', '%' . $this->searchTerm . '%');
                      })
                      ->orWhereHas('type', function ($query) {
                          $query->where('name', 'like', '%' . $this->searchTerm . '%');
                      })
                      ->orWhereHas('employee', function ($query) {
                          $query->where('employee_id', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('name', 'like', '%' . $this->searchTerm . '%');

                      });
            });
        }

        $data = $query->get();

        return view('admin.inventory', [
            'data' => $data,
        ])->layout('layouts.app');
    }
    public function modalClicked()
    {
        $data = Item::whereNull('employee_id')->get();

        foreach ($data as $inventory) {
            $inventory->description =  $inventory->brand->name . ' - ' . $inventory->type->name;
        }
    
        $this->items = $data;

        $this->modalIsActive = true;
        // $this->reset(['id']);
    }

    public function edit($id)
    {
        $this->returnId = $id;
        $this->item = Item::find($id);
        $this->modalReturnIsActive = true;
    }

    public function save()
    {
        $data = Item::whereNull('employee_id')->get();

        foreach ($data as $inventory) {
            $inventory->description =  $inventory->brand->name . ' - ' . $inventory->type->name;
        }
    
        $this->items = $data;
        $this->validate();
        // dd($this->selected_item , $this->employee_id , $this->borrow_date);

        foreach($this->selected_item as $itemId){
            $item = Item::findOrFail($itemId);
            $item->update([
                'employee_id' => $this->employee_id, // Assuming employee_id is directly accessible in the component
                'borrow_date' => $this->borrow_date, // Assuming borrow_date is directly accessible in the component
            ]);     
        }
    
        $this->notification()->success(
            $title = 'Success',
            $description = 'Inventory was successfully saved'
        );
        
        $this->reset(['employee_id', 'borrow_date','selected_item', 'id', 'modalIsActive']);
    }

    public function return()
    {    
        $item = Item::findOrFail($this->returnId);
        $item->update([
            'employee_id' => null,
            'borrow_date' => null
        ]);     

        $this->notification()->success(
            $title = 'Success',
            $description = 'Inventory was successfully saved'
        );

        $data = Item::whereNull('employee_id')->get();

        foreach ($data as $inventory) {
            $inventory->description =  $inventory->brand->name . ' - ' . $inventory->type->name;
        }
    
        $this->items = $data;
        $this->reset(['returnId', 'modalReturnIsActive']);
    }

    public function resetModal()
    {    
        $this->reset(['item', 'id']);
    }

    public function updateSearch(){
        
        $query = Item::with(['brand', 'type', 'employee']);

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('serial_number', 'like', '%' . $this->searchTerm . '%')
                      ->orWhereHas('brand', function ($query) {
                          $query->where('name', 'like', '%' . $this->searchTerm . '%');
                      })
                      ->orWhereHas('type', function ($query) {
                          $query->where('name', 'like', '%' . $this->searchTerm . '%');
                      })
                      ->orWhereHas('employee', function ($query) {
                          $query->where('employee_id', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('name', 'like', '%' . $this->searchTerm . '%');

                      });
            });
        }

        $data = $query->get();

        return view('admin.inventory', [
            'data' => $data,
        ])->layout('layouts.app');
    }
    
}

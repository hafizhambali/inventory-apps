<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\Type;
use App\Models\Brand;
use WireUi\Traits\Actions;

class ItemController extends Component
{
    use Actions;

    public $searchTerm = '';

    public $title = 'Item';
    public $modalIsActive = false;
    public $modalDeleteIsActive = false;
    public $id = '' ;

    public $brand ;

    public $type ;
    public $item = [
        'serial_number' => '',
        'type_id' => '',
        'brand_id' => '',
    ];
    protected $rules = [
        'item.serial_number' => 'required',
        'item.type_id' => 'required',
        'item.brand_id' => 'required',
    ];

    public function mount()
    {
        $this->type = Type::all();
        $this->brand = Brand::all();
        $this->item = new Item();
    }
    public function render()
    {

        $query = Item::with(['brand' , 'type']);

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('serial_number', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('brand', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    })
                    ->orWhereHas('type', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }
        

        $data = $query->get();

        return view('admin.item', [
            'data' => $data,
        ])->layout('layouts.app');
    }
    public function modalClicked()
    {
        $this->reset(['item', 'id']);
        $this->modalIsActive = true;
    }

    public function edit($id)
    {
        $this->id = $id;
        $this->item = Item::find($id);
        $this->modalIsActive = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->id) {
            $item = Item::findOrFail($this->id);
            $item->update([
                'serial_number' => $this->item['serial_number'],
                'type_id' => $this->item['type_id'],
                'brand_id' => $this->item['brand_id'],
            ]);        
        } else {
            Item::create([
                'serial_number' => $this->item['serial_number'],
                'type_id' => $this->item['type_id'],
                'brand_id' => $this->item['brand_id'],
            ]);
        }

        $this->notification()->success(
            $title = 'Success',
            $description = 'Item was successfully saved'
        );
        
        $this->reset(['item', 'id', 'modalIsActive']);
    }
    public function delete($id){
        $this->id = $id;
        $this->modalDeleteIsActive = true;
    }
    public function confirmDelete(){
        // Find the item by its ID
        Item::find($this->id)->delete();

        $this->notification()->success(
            $title = 'Success',
            $description = 'Record was successfully deleted'
        );
        
        $this->reset(['item', 'id', 'modalDeleteIsActive']);
        
    }
    public function resetModal()
    {    
        $this->reset(['item', 'id']);
    }

    public function updateSearch(){
     
        $query = Item::with(['brand' , 'type']);

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('serial_number', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('brand', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    })
                    ->orWhereHas('type', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }
        

        $data = $query->get();

        return view('admin.employee', [
            'data' => $data,
        ])->layout('layouts.app');
    }
}

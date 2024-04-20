<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brand;
use WireUi\Traits\Actions;

class BrandController extends Component
{
    use Actions;
    public $searchTerm = '';

    public $title = 'Brand';
    public $modalIsActive = false;
    public $modalDeleteIsActive = false;
    public $id = '' ;

    public $brand = [
        'name' => '',
    ];
    protected $rules = [
        'brand.name' => 'required|string',
    ];

    public function mount()
    {
        $this->brand = new Brand();
    }
    public function render()
    {
        $query = Brand::query();

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')  ;
            });
        }
        

        $data = $query->get();

        return view('admin.brand', [
            'data' => $data,
        ])->layout('layouts.app');
    }
    public function modalClicked()
    {
        $this->reset(['brand', 'id']);
        $this->modalIsActive = true;
    }

    public function edit($id)
    {
        $this->id = $id;
        $this->brand = Brand::find($id);
        $this->modalIsActive = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->id) {
            $department = Brand::findOrFail($this->id);
            $department->update([
                'name' => $this->brand['name'],
            ]);        
        } else {
            Brand::create([
                'name' => $this->brand['name'],
            ]);
        }

        $this->notification()->success(
            $title = 'Success',
            $description = 'Brand was successfully saved'
        );
        
        $this->reset(['brand', 'id', 'modalIsActive']);
    }
    public function delete($id){
        $this->id = $id;
        $this->modalDeleteIsActive = true;
    }
    public function confirmDelete(){
        // Find the item by its ID
        Brand::find($this->id)->delete();

        $this->notification()->success(
            $title = 'Success',
            $description = 'Record was successfully deleted'
        );
        
        $this->reset(['brand', 'id', 'modalDeleteIsActive']);
        
    }

    public function updateSearch(){
        
        $query = Brand::query();

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')  ;
            });
        }
        

        $data = $query->get();

        return view('admin.brand', [
            'data' => $data,
        ])->layout('layouts.app');
    }

    public function resetModal()
    {    
        $this->reset(['brand', 'id']);
    }
}

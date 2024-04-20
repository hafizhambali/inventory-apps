<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Type;
use WireUi\Traits\Actions;

class TypeController extends Component
{
    use Actions;
    public $searchTerm = '';

    public $title = 'Type';
    public $modalIsActive = false;
    public $modalDeleteIsActive = false;
    public $id = '' ;

    public $type = [
        'name' => '',
    ];
    protected $rules = [
        'type.name' => 'required|string',
    ];

    public function mount()
    {
        $this->type = new Type();
    }
    public function render()
    {
        $query = Type::query();

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            });
        }
        
        $data = $query->get();

        return view('admin.type', [
            'data' => $data,
        ])->layout('layouts.app');
    }
    public function modalClicked()
    {
        $this->reset(['type', 'id']);
        $this->modalIsActive = true;
    }

    public function edit($id)
    {
        $this->id = $id;
        $this->type = Type::find($id);
        $this->modalIsActive = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->id) {
            $department = Type::findOrFail($this->id);
            $department->update([
                'name' => $this->type['name'],
            ]);        
        } else {
            Type::create([
                'name' => $this->type['name'],
            ]);
        }

        $this->notification()->success(
            $title = 'Success',
            $description = 'Type was successfully saved'
        );
        
        $this->reset(['type', 'id', 'modalIsActive']);
    }

    public function delete($id){
        $this->id = $id;
        $this->modalDeleteIsActive = true;
    }
    public function confirmDelete(){
        // Find the item by its ID
        Type::find($this->id)->delete();

        $this->notification()->success(
            $title = 'Success',
            $description = 'Record was successfully deleted'
        );
        
        $this->reset(['type', 'id', 'modalDeleteIsActive']);
        
    }

    public function resetModal()
    {    
        $this->reset(['type', 'id']);
    }

    public function updateSearch(){
     
        $query = Type::query();

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            });
        }
        
        $data = $query->get();

        return view('admin.type', [
            'data' => $data,
        ])->layout('layouts.app');
    }

}

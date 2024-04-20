<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use WireUi\Traits\Actions;

class DepartmentController extends Component
{
    use Actions;
    public $searchTerm = '';

    public $departmentModal = false;
    public $modalDeleteIsActive = false;
    public $departmentId = '' ;

    public $department = [
        'name' => '',
        'code' => ''
    ];
    protected $rules = [
        'department.name' => 'required|string',
        'department.code' => 'required|string|unique:departments,code'
    ];

    public function mount()
    {
        $this->department = new Department();
    }
    public function render()
    {
        $query = Department::query();

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('code', 'like', '%' . $this->searchTerm . '%');
  
            });
        }
        

        $data = $query->get();

        return view('admin.department', [
            'data' => $data,
        ])->layout('layouts.app');

    }
    public function departmentModalClicked()
    {
        $this->departmentModal = true;
    }

    public function edit($id)
    {
        $this->departmentId = $id;
        $this->department = Department::find($id);
        $this->departmentModal = true;
        // dd($id);
    }

    public function save()
    {
        $this->validate();

        if ($this->departmentId) {
            $department = Department::findOrFail($this->departmentId);
            $department->update([
                'name' => $this->department['name'],
                'code' => $this->department['code'],
            ]);        
        } else {
            Department::create([
                'name' => $this->department['name'],
                'code' => $this->department['code'],
            ]);
        }

        $this->notification()->success(
            $title = 'Success',
            $description = 'Department was successfully saved'
        );
        
        $this->reset(['department', 'departmentId', 'departmentModal']);
    }

    public function delete($id){
        $this->departmentId = $id;
        $this->modalDeleteIsActive = true;
    }
    public function confirmDelete(){
        // Find the item by its ID
        Department::find($this->departmentId)->delete();

        $this->notification()->success(
            $title = 'Success',
            $description = 'Record was successfully deleted'
        );
        
        $this->reset(['department', 'departmentId', 'modalDeleteIsActive']);
        
    }
    public function updateSearch(){
        
        $query = Department::query();

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('code', 'like', '%' . $this->searchTerm . '%');
  
            });
        }
        

        $data = $query->get();

        return view('admin.department', [
            'data' => $data,
        ])->layout('layouts.app');
    }
}

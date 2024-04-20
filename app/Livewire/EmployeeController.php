<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Department;
use Livewire\Component;
use App\Models\Brand;
use Illuminate\Validation\Rule;
use WireUi\Traits\Actions;

class EmployeeController extends Component
{
    use Actions;
    public $searchTerm = '';

    public $title = 'Employee';
    public $modalIsActive = false;
    public $modalDeleteIsActive = false;
    public $department;
    public $id = '' ;
    public $deptPrefix = '';

    public $employeeIdToIgnore = '' ;
    
    public $employee = [
        'name' => '',
        'dept_id' => '',
        'employee_id' => '',
    ];
    public function rules()
    {
        // dd($this->employeeIdToIgnore);
        return [
            'employee.name' => 'required',
            'employee.dept_id' => 'required',
            'employee.employee_id' => [
                'required',
            ],
            'employee.validate' => [
                'required',
                Rule::unique('employees', 'employee_id'),
            ],
        ];
    }

    // protected $rules = [
    //     'employee.name' => 'required|string',
    //     'employee.dept_id' => 'required',
    //     'employee.employee_id' => [
    //         'required',
    //         Rule::unique('employees', 'employee_id')->ignore($this->employee['id']),
    //     ],
    // ];

    public function mount()
    {
        // $this->department = new Department
        $this->department = Department::all();

        $this->employee = new Employee();
    }
    public function render()
    {
        $this->department = Department::all();
        // dd($this->department);
        $query = Employee::with(['department']);

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('employee_id', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('department', function ($query) {
                        $query->where('name', 'like', '%' . $this->searchTerm . '%');
                    });
            });
        }
        

        $data = $query->get();

        return view('admin.employee', [
            'data' => $data,
            'departments' => $this->department,
        ])->layout('layouts.app');

    }
    public function modalClicked()
    {
        $this->reset(['employee', 'id']);
        $this->modalIsActive = true;
    }

    public function edit($id)
    {

        $this->id = $id;
        // $this->id = $id;
        $employee = Employee::with('department')->find($id);
        $departmentCode = Department::find($employee->dept_id);
        $this->deptPrefix = $departmentCode->code ;
        
        if ($employee) {
            // Extract the part after the hyphen ('-') from the employee_id
            $employeeId = $employee->employee_id;
            $parts = explode('-', $employeeId);
            $stringAfterHyphen = end($parts); // Get the last element of the array
            // Overwrite the employee_id with the string after the hyphen
            $this->employee['name'] = $employee->name;
            $this->employee['dept_id'] = $employee->dept_id;
            $this->employee['employee_id'] = $stringAfterHyphen;
        }
        
        $this->modalIsActive = true;
    }

    public function save()
    {

        $this->employee['validate'] = $this->deptPrefix . '-' .$this->employee['employee_id'];
        $this->validate();
        
        // $employee = Employee::where('employee_id',  $this->deptPrefix . '-' .$this->employee['employee_id'])->first();

        // dd($this->id);
        if ($this->id) {
            $employeeQuery = Employee::findOrFail($this->id);

            if($employeeQuery){
                $employeeQuery->update([
                    'name' => $this->employee['name'],
                    'dept_id' => $this->employee['dept_id'],
                    'employee_id' => $this->deptPrefix . '-' .$this->employee['employee_id'],
                ]);        
            }
        } else {
            
            Employee::create([
                'name' => $this->employee['name'],
                'dept_id' => $this->employee['dept_id'],
                'employee_id' => $this->deptPrefix . '-' .$this->employee['employee_id'],
            ]);
        }

        $this->notification()->success(
            $title = 'Success',
            $description = 'Employee was successfully saved'
        );
        
        $this->reset(['employee' ,'deptPrefix', 'id', 'modalIsActive']);
    }
    public function delete($id){
        $this->id = $id;
        $this->modalDeleteIsActive = true;
    }
    public function confirmDelete(){
        // Find the item by its ID
        Employee::find($this->id)->delete();

        $this->notification()->success(
            $title = 'Success',
            $description = 'Record was successfully deleted'
        );
        
        $this->reset(['employee', 'id', 'modalDeleteIsActive']);
        
    }
    public function departmentSelected($id)
    {    
        $departmentCode = Department::find($id);
        $this->deptPrefix = $departmentCode->code  ;
    }


    public function updateSearch(){
        
        $query = Employee::with(['department']);

        // Apply search if search term is provided
        if ($this->searchTerm) {
            $query->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%')
                    ->orWhere('employee_id', 'like', '%' . $this->searchTerm . '%')
                    ->orWhereHas('department', function ($query) {
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

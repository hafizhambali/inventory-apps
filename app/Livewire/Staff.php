<?php

namespace App\Livewire;

use Livewire\Component;

class Staff extends Component
{
    public $employeeModal = false;
    public $departmentModal = false;

    public function render()
    {
        return view('admin.staff')->layout('layouts.app');
    }
    public function employeeModalClicked()
    {
        $this->employeeModal = true;
    }


}

<div>
      <x-notifications position="top-right" /> 

      <h2 class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-4xl dark:text-white capitalize">{{ $title }}</h2>
      <p class="text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400 ">You can create, edit and delete {{ $title }} detail here.</p>
      

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-4 mt-4">
        <div class="flex justify-between mb-2">
            <div class="bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input wire:model="searchTerm" wire:change="updateSearch" type="text" id="table-search" class="block pt-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search for items">
                </div>
            </div>
            <div class="flex">
                <x-button class="capitalize" icon="plus-circle" primary label="Create {{ $title }}" wire:click="modalClicked"/>
            </div>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="p-4">
                        <div class="flex items-center">
                            <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-all-search" class="sr-only">checkbox</label>
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Employee ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Employee Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Department Name
                    </th>
                    <th scope="col" class="px-6 py-3 float-end">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)    
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="w-4 p-4">
                        <div class="flex items-center">
                            <input id="checkbox-table-search-1" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                        </div>
                    </td>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->employee_id }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->name }}
                    </th>
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->department->name }}
                    </th>

                    <td class="px-6 py-4 float-end">
                        <x-button icon="pencil" warning label="Edit" wire:click="edit({{ $item->id }})"/>
                        <x-button icon="trash" negative  label="Delete" wire:click="delete({{ $item->id }})"/>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center">
                        <div class="mx-auto">
                            <p>No data.</p>
                        </div>
                    </td>
                    
                </tr>
                
                @endforelse
            </tbody>
        </table>
    </div>

    <x-modal.card title="{{ $title }}" blur wire:model.defer="modalIsActive" x-on:close="resetModal">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">     
            <div class="col-span-1 sm:col-span-2">
                <x-input label="Employee Name" wire:model.defer="employee.name"  placeholder="Enter employee name" />
            </div>
            <div class="w-full">     
                <x-native-select label="Select Department" wire:model="employee.dept_id" wire:change="departmentSelected($event.target.value)">
                    <option>Please select department</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->name }}</option>
                    @endforeach
                </x-native-select>
                
            </div>
            <x-input class="!pl-[6.5rem]" wire:model.defer="employee.employee_id" placeholder="Enter employee id" label="Dept Code"  prefix="{{ $deptPrefix }}" />

            <x-input class="hidden" wire:model.defer="employee.validate"  placeholder="Enter employee id" />
        </div>
     
        <x-slot name="footer">
            <div class="flex float-right gap-x-4">     
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Save" wire:click="save" />
                </div>
            </div>
        </x-slot>
    </x-modal.card>
    
    <x-modal.card title="Delete record" blur wire:model.defer="modalDeleteIsActive">
        <div class="">     
            <div>
                <h1>Are you sure to delete this record ?</h1>
            </div>

            <div class="col-span-1 sm:col-span-2">
         </div>
        </div>
     
        <x-slot name="footer">
            <div class="float-right gap-x-4">     
                <div class="flex">
                    <x-button flat label="Cancel" x-on:click="close" />
                    <x-button primary label="Yes" wire:click="confirmDelete" />
                </div>
            </div>
        </x-slot>
    </x-modal.card>
</div>
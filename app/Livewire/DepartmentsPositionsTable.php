<?php

namespace App\Livewire;

use App\Models\Department;
use Livewire\Component;
use App\Models\Position;
use Livewire\Attributes\On;
use Livewire\WithPagination;


class DepartmentsPositionsTable extends Component
{
    use WithPagination;
    public $departments = [];
    public $selectedPositionId = "";
    public $name = "";
    public $description = "";
    public $selectedDepartment = "";

    public function mount()
    {
       $this->resetPage();
       $this->departments = Department::select('id', 'name')->get();
    }

    public function render()
    {
        return view('livewire.admin.departments-positions-table', [
            'positions' => Position::with('department')->latest()->paginate(1)
        ]);
    }


    #[On(['added-position', 'deleted-position'])]
    public function resetTable()
    {
        $this->resetPage();
    }

    public function deletePosition($positionId)
    {
        $position = Position::find($positionId);
        if ($position) {
            $position->delete();
            $this->dispatch('deleted-position');
            $this->modal('delete-position' . $positionId)->close();
        }
    }
    public function editModal($positionId)
    {
        $position = Position::find($positionId);
        if ($position) {
            $this->name = $position->name;
            $this->description = $position->description;
            $this->selectedDepartment = $position->department_id;
            $this->selectedPositionId = $positionId;
            $this->modal('edit-position')->show();
        }
    }
    public function closeEditModal()
    {
        $this->reset(['name', 'description', 'selectedDepartment', 'selectedPositionId']);
        $this->modal('edit-position')->close();
    }

    public function updatePosition()
    {
        $validated = $this->validate([
            'name' => 'required'|'string'|'max:255',
            'description' => 'nullable|string|max:1000',
            'selectedDepartment' => 'required'|'exists:departments,id',
        ]);
        $position = Position::find($this->selectedPositionId);
        if ($position) {
            $position->update([
                'name' => $this->name,
                'description' => $this->description,
                'department_id' => $this->selectedDepartment,
            ]);
            $this->dispatch('updated-position');
            $this->closeEditModal();
        }
    }
}
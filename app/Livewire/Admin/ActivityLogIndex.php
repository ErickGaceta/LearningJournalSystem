<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityLogIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $action = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingAction(): void { $this->resetPage(); }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, fn($q) => $q->where('description', 'like', "%{$this->search}%")
                ->orWhereHas('user', fn($q) => $q->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")))
            ->when($this->action, fn($q) => $q->where('action', $this->action))
            ->latest()
            ->paginate(20);

        return view('livewire.admin.activity-log-index', compact('logs'));
    }
}
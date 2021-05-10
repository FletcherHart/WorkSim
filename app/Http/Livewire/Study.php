<?php

namespace App\Http\Livewire;

use App\Models\Degree;
use App\Models\DegreeProgress;
use Livewire\Component;

class Study extends Component
{

    public function mount() 
    {
        $this->degrees = DegreeProgress::where('user_id', auth()->id())
            ->join('degrees', 'degrees.id', '=', 'degree_progress.degree_id')
            ->select('degrees.title', 'degrees.description', 'degrees.cost', 'degree_progress.progress')
            ->get();

    }

    public function render()
    {
        return view('livewire.study', ['degrees' => $this->degrees]);
    }
}

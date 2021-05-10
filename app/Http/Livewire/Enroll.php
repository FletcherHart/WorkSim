<?php

namespace App\Http\Livewire;

use App\Models\Degree;
use App\Models\DegreeProgress;
use Livewire\Component;

class Enroll extends Component
{

    public function mount($id) 
    {
        $this->degree = Degree::find($id);
        DegreeProgress::create(
            [
                'user_id' => auth()->id(),
                'degree_id' => $id
            ]
        );
    }

    public function render()
    {
        return view('livewire.enroll', ['degree' => $this->degree]);
    }
}

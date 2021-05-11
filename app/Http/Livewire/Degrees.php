<?php

namespace App\Http\Livewire;

use App\Models\Degree;
use App\Models\DegreeProgress;
use Livewire\Component;

class Degrees extends Component
{
    public function render()
    {

        $excluded_degrees = DegreeProgress::where('user_id', '=', auth()->id())
        ->pluck('degree_id')
        ->toArray();

        $degrees = Degree::whereNotIn('id', $excluded_degrees)
        ->get();

        return view('livewire.degrees', ['degrees' => $degrees]);
    }
}

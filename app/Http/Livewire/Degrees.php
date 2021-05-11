<?php

namespace App\Http\Livewire;

use App\Models\Degree;
use App\Models\DegreeProgress;
use App\Models\UserDegree;
use Livewire\Component;

class Degrees extends Component
{
    public function render()
    {

        $enrolled_degrees = DegreeProgress::where('user_id', '=', auth()->id())
        ->pluck('degree_id')
        ->toArray();

        $completed_degrees = UserDegree::where('user_id', '=', auth()->id())
        ->pluck('degree_id')
        ->toArray();

        $excluded_degrees = array_merge($enrolled_degrees, $completed_degrees);

        $degrees = Degree::whereNotIn('id', $excluded_degrees)
        ->get();

        return view('livewire.degrees', ['degrees' => $degrees]);
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Degree;
use Livewire\Component;

class Degrees extends Component
{
    public function render()
    {
        $degrees = Degree::get();
        return view('livewire.degrees', ['degrees' => $degrees]);
    }
}

<?php
/*
@file
Livewire controller for user job page.
*/

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Occupation;

class Work extends Component
{
    public function render()
    {
        $occupation = Occupation::join('user_occupation', 'occupations.id', '=', 'user_occupation.occupation_id')
            ->where('user_occupation.user_id', '=', auth()->id())->first();
        return view('livewire.work', ['occupation' => $occupation]);
    }
}

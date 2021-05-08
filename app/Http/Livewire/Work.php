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

    public $occupation;
    public $user;

    public function mount() {

        $this->user = auth()->user();

        $this->occupation = Occupation::join(
            'user_occupation', 'occupations.id', 
            '=', 
            'user_occupation.occupation_id'
        )
        ->where('user_occupation.user_id', '=', $this->user->id)
        ->first();
    }

    public function render()
    {
        return view('livewire.work', ['occupation' => $this->occupation])
            ->layout('layouts.app');
    }

    /**
     * Spend a single unit of energy to recieve pay. Company pay should also be calculated and applied.
     * @return void
     */
    public function doWork() {
        $this->user->money += $this->occupation->salary;
        $this->user->save();

        $this->emit('addMoney');
    }
}

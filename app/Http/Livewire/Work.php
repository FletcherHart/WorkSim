<?php
/*
@file
Livewire controller for user job page.
*/

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Company;
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

        $company = Company::where('id', $this->occupation->company_id)->first();

        if ($this->occupation->bonus_stat == "charisma") 
        {
            $company->money += (200 - $this->occupation->salary + $this->user->charisma*2 + $this->user->intelligence + $this->user->fitness);
        }
        else if ($this->occupation->bonus_stat == "intelligence")
        {
            $company->money += (200 - $this->occupation->salary + $this->user->charisma + $this->user->intelligence*2 + $this->user->fitness);
        }
        else if ($this->occupation->bonus_stat == "fitness")
        {
            $company->money += (200 - $this->occupation->salary + $this->user->charisma + $this->user->intelligence + $this->user->fitness*2);
        }

        $company->save();

        $this->emit('addMoney');
    }
}

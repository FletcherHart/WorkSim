<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Occupation;
use App\Models\OccupationRequirement;
use App\Models\UserOccupation;

class Apply extends Component
{

    public $occupation;
    public $reqs;
    public $result = false;

    public function mount($id) {
        $this->occupation = Occupation::find($id);
        $this->reqs = OccupationRequirement::firstWhere('occupation_id', $id);
    }

    public function render()
    {
        $user = auth()->user();
        if($user->charisma >= $this->reqs->charisma && $user->fitness >= $this->reqs->fitness && $user->intelligence >= $this->reqs->intelligence) 
        {
            //Delete existing user occupation
            if(UserOccupation::firstWhere('user_id', $user->id) != null)
            {
                UserOccupation::where('user_id', $user->id)->delete();
            }

            $this->result = true;
            $newJob = new UserOccupation;
            $newJob->user_id = $user->id;
            $newJob->occupation_id = $this->occupation->id;
            $newJob->save();
            $this->reqs->delete();
        }


        return view('livewire.apply', ['result'=>$this->result, 'title' => $this->occupation->title]);
    }
}

<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Occupation;
use App\Models\OccupationRequirement;
use App\Models\UserDegree;
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

        //Check if user has or needs appropriate degree
        if($this->occupation->degree_id != null)
        {
            $user_degree = UserDegree::where(
                [
                    'user_id' => $user->id,
                    'degree_id' => $this->occupation->degree_id
                ]
            )
            ->first();

            if($user_degree == null) 
            {
                return view('livewire.apply', ['result'=>$this->result, 'title' => $this->occupation->title]);
            }
        }

        //Ensure occupation not taken by a user
        if(UserOccupation::firstWhere('occupation_id', $this->occupation->id) == null)
        {
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
            }
        }


        return view('livewire.apply', ['result'=>$this->result, 'title' => $this->occupation->title]);
    }
}

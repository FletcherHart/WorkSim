<?php

namespace App\Http\Livewire;

use App\Models\Degree;
use App\Models\DegreeProgress;
use App\Models\UserDegree;
use Livewire\Component;

class Study extends Component
{

    public $degrees;
    public $completed_degrees;
    public $error;

    public function render()
    {
        $this->degrees = DegreeProgress::where('user_id', auth()->id())
            ->join('degrees', 'degrees.id', '=', 'degree_progress.degree_id')
            ->select('degrees.title', 'degrees.description', 'degrees.cost', 'degrees.progress_needed', 'degree_progress.id', 'degree_progress.progress')
            ->get();

        $this->completed_degrees = Degree::join('user_degrees', 'user_degrees.degree_id', '=', 'degrees.id')
            ->where('user_degrees.user_id', auth()->id())
            ->select('degrees.title', 'degrees.description', 'user_degrees.created_at as date_recieved')
            ->get();

        return view('livewire.study', 
            [
                'degrees' => $this->degrees, 
                'completed_degrees' => $this->completed_degrees, 
                'error' => $this->error
            ]
        );
    }

    /**
     * Spend a single unit of energy + money
     * to increase progress toward degree
     * @return void
     */
    public function makeProgress($id) 
    {
        $degree_progress = DegreeProgress::where('id', $id)->first();
        $degree = Degree::where('id', $degree_progress->degree_id)->first();

        if(auth()->user()->current_energy <= 0)
        {
            $this->error = 'Oops! It looks like you don\'t have enough energy';
        }
        else if (auth()->user()->money < $degree->cost)
        {
            $this->error = 'Oops! It looks like you don\'t have enough money';
        }
        else {

            auth()->user()->current_energy -= 1;
            auth()->user()->money -= $degree->cost;
            auth()->user()->save();

            $degree_progress->progress += round(1 + (auth()->user()->intelligence/5));
            $degree_progress->save();

            if($degree_progress->progress >= $degree->progress_needed)
            {
                UserDegree::create(
                    [
                        'user_id' => auth()->id(),
                        'degree_id' => $degree->id
                    ]
                );
                $degree_progress->delete();
            }

            $this->emit('updateSidebar');
        }
    }
}

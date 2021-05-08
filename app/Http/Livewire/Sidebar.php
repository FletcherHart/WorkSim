<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Occupation;

class Sidebar extends Component
{

    protected $listeners = ['addMoney' => 'render'];

    public function render()
    {
        $user = User::where('id', auth()->id())
                ->first(['current_energy', 'max_energy', 'intelligence', 'fitness', 'charisma', 'money']);
        $user_occupation = Occupation::join('user_occupation', 'occupations.id', '=', 'user_occupation.occupation_id')
            ->where('user_id', auth()->id())->first();
        return view('livewire.sidebar', ['user' => $user, 'occupation' => $user_occupation]);
    }
}

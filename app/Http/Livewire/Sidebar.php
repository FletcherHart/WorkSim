<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;

class Sidebar extends Component
{

    public function render()
    {
        $user = User::where('id', auth()->id())
                ->first(['current_energy', 'max_energy', 'intelligence', 'fitness', 'charisma', 'money']);
        return view('livewire.sidebar', ['user' => $user]);
    }
}

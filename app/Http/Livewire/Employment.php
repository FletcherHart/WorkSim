<?php

namespace App\Http\Livewire;

use App\Models\Occupation;
use Livewire\Component;

class Employment extends Component
{
    public function render()
    {
        $occupations = Occupation::select('occupations.id',
            'occupations.title', 
            'occupations.description', 
            'occupations.salary', 
            'companies.company_name', 
            'degrees.title as degree',
            'req.charisma',
            'req.intelligence',
            'req.fitness')
            ->join('companies', 'companies.id', '=', 'occupations.company_id')
            ->Join('user_occupation', 'user_occupation.occupation_id', '=', 'occupations.id', 'left outer')
            ->where('user_occupation.id', null)
            ->join('occupation_requirements as req', 'occupations.id', '=', 'req.occupation_id')
            ->leftJoin('degrees', 'occupations.degree_id', '=', 'degrees.id')
            ->orderBy('occupations.id', 'ASC')
            ->get();

        return view('livewire.employment', ['occupations'=>$occupations]);
    }
}

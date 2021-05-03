<?php

namespace App\Http\Livewire;

use App\Models\Occupation;
use Livewire\Component;

class Employment extends Component
{
    public function render()
    {
        $occupations = Occupation::join('company_occupation', 'occupations.id', '=', 'company_occupation.occupation_id')
            ->join('companies', 'companies.id', '=', 'company_occupation.company_id')
            ->select('occupations.title', 'occupations.description', 'occupations.salary', 'companies.company_name')
            ->get();
        return view('livewire.employment', ['occupations'=>$occupations]);
    }
}

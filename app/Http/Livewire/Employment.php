<?php

namespace App\Http\Livewire;

use App\Models\Occupation;
use Livewire\Component;

class Employment extends Component
{
    public function render()
    {
        $occupations = Occupation::join('company_occupation', 'occupation.id', '=', 'company_occupation.occupation_id')
            ->join('companies', 'companies.id', '=', 'company_occupation.company_id')
            ->select('occupation.title', 'occupation.description', 'occupation.salary', 'company.company_name')
            ->get();
        return view('livewire.employment', ['occupations'=>$occupations]);
    }
}

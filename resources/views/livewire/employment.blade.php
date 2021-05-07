<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Work') }}
        </h1>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 items-center">
                @foreach($occupations as $occupation)
                    <div class="flex flex-col w-full border border-black border-left border-right border-top h-auto text-left text-sm p-1">
                        <div class="flex justify-between">
                            <div class="font-bold text-base">{{$occupation->title}}</div>
                            <p>Salary: {{$occupation->salary}}</p>
                        </div>
                        <div class="text-green-700 text-sm">{{$occupation->company_name}}</div>
                        <div class="">{{$occupation->description}}</div>
                        
                        <p class="font-semibold">Requirements:</p>
                            @if($occupation->degree == null)
                                <p>Degree: None</p>
                            @else
                                <p>Degree: {{$occupation->degree}}</p>
                            @endif
                        <div class="flex">
                            <p class="pr-2 border-r border-black">Charisma: {{$occupation->charisma}}</p>
                            <p class="pl-2 pr-2 border-r border-black">Intelligence: {{$occupation->intelligence}}</p>
                            <p class="pl-2">Fitness: {{$occupation->fitness}}</p>
                        </div>
                        <div class="m-2 flex justify-end">
                            <a class="bg-blue-600 p-2 text-white" href="/apply/{{$occupation->id}}">Apply</a>
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
    </section>
</x-app-layout>
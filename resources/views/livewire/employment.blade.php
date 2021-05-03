<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Work') }}
        </h1>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center">
                @foreach($occupations as $occupation)
                    <div class="flex w-full border-black border-left border-right border-top">
                            <div class="font-bold">{{$occupation->title}}</div>
                            <div class="">{{$occupation->description}}</div>
                            {{$occupation->salary}}
                            {{$occupation->company_name}}
                    </div>
                @endforeach
                
            </div>
        </div>
    </section>
</x-app-layout>
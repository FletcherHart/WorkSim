<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Work') }}
        </h1>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col items-center">
                @if($occupation == null)
                    <h3 class="text-lg font-bold">Unemployed</h3>
                    <p>You are currently unemployed. Please click the button below to look for jobs.</p>
                    <br>
                    <a href="#" class="bg-blue-600 p-2 text-white w-max">Look for a job.</a>
                @else
                    
                @endif
            </div>
        </div>
    </section>
</x-app-layout>
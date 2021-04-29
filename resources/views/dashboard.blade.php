<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden grid grid-cols-2">
                <article class="col-span-1 flex flex-col items-center">
                    <h2 class="text-lg font-semibold">{Placeholder title}</h2>
                    <h3>{placeholder}</h3>
                    <br>
                    <h3>{placeholder}</h3>
                </article>
                <article class="col-span-1 flex flex-col items-center">
                    <h2 class="text-lg font-semibold">{Placeholder title}</h2>
                    <h3>{placeholder}</h3>
                    <br>
                    <h3>{placeholder}</h3>
                </article>
            </div>
        </div>
    </section>
</x-app-layout>

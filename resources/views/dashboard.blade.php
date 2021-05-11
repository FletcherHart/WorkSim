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
                    <h2 class="text-lg font-semibold">Work</h2>
                    <a href="/work" class="bg-blue-600 p-2 text-white">Go to work</a>
                    <br>
                    <a href="/employment" class="bg-blue-600 p-2 text-white">Find a new job</a>
                </article>
                <article class="col-span-1 flex flex-col items-center">
                    <h2 class="text-lg font-semibold">Education</h2>
                    <a href="/study" class="bg-blue-600 p-2 text-white">Go to class</a>
                    <br>
                    <a href="/degrees" class="bg-blue-600 p-2 text-white">Enroll</a>
                </article>
            </div>
        </div>
    </section>
</x-app-layout>

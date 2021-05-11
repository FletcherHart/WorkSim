<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Degrees') }}
    </h1>
</x-slot>

<div class="flex flex-col items-center">
    <div class="sm:w-1/2 mt-10">
        <h2 class="font-semibold">Available Degrees</h2>
        @foreach ($degrees as $degree)
            <div class="text-left shadow mt-5 w-full border border-gray-400 rounded">
                <div class="font-semibold">{{$degree->title}}</div>
                <div>{{$degree->description}}</div>
                <div class="flex justify-between mt-5 relative">
                    <div class="self-end mb-2">Cost/Lesson: ${{$degree->cost}}</div>
                    <a href="/enroll/{{$degree->id}}" class="bg-blue-600 p-2 m-2 text-white">Enroll</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<x-slot name="header">
    <h1 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Study') }}
    </h1>
</x-slot>

<div class="mt-10">
    @if($error != null)
        <div>Error: {{$error}}</div>
    @endif

    @if(empty($completed_degrees))
        <div>Completed Degrees</div>
        @foreach($completed_degrees as $degree)
            <div>{{$degree->title}}</div>
            <div>{{$degree->description}}</div>
        @endforeach
    @endif

    <div class="flex flex-col items-center">
        <div class="sm:w-1/2">
            <h2 class="font-semibold">Current Enrollments</h2>
            @foreach($degrees as $degree)
                <div class="text-left shadow mt-5 w-full border border-gray-400 rounded">
                    <div class="font-semibold">{{$degree->title}}</div>
                    <div>{{$degree->description}}</div>
                    <div class="flex justify-between mt-5 relative">
                        <div>
                            <div class="self-end mb-2">Cost/Lesson: ${{$degree->cost}}</div>
                            <div class="self-end mb-2">Progress: {{$degree->progress}} / {{$degree->progress_needed}}</div>
                        </div>
                        <button class="bg-blue-600 p-2 m-2 text-white" wire:click="makeProgress({{$degree->id}})">Study</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

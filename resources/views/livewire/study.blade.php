<div>
    @foreach($degrees as $degree)
        <div>{{$degree->title}}</div>
        <div>{{$degree->description}}</div>
        <div>{{$degree->cost}}</div>
        <div>{{$degree->progress}}</div>
        <button class="bg-blue-600 p-2 text-white w-max" wire:click="makeProgress({{$degree->progress_id}})">Work</button>
    @endforeach
</div>

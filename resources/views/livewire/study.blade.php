<div>
    @foreach($degrees as $degree)
        <div>{{$degree->title}}</div>
        <div>{{$degree->description}}</div>
        <div>{{$degree->cost}}</div>
        <div>{{$degree->progress}}</div>
    @endforeach
</div>

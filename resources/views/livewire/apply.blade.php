<div>
    @if($result)
        <h2>Congrats! You have been accepted for the position of {{$title}}</h2>
    @else
        <h2>{{$reason}}</h2>
    @endif
</div>

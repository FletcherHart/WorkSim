<div>
    @foreach($occupations as $occupation)
        {{$occupation->title}}
        {{$occupation->description}}
        {{$occupation->salary}}
        {{$occupation->company_name}}
    @endforeach
    
</div>

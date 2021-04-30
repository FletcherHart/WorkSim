<section class="flex flex-col items-center text-center border-r border-black h-full">
    <h1 class="text-2xl font-bold">Character</h1>

    <h2 class="text-lg font-semibold">Occupation</h2>
    <div class="inline">
        @if($occupation != null)
            <h3>{{$occupation}}</h3>
            <p>Salary: {{$occupation->salary}}</p>
        @else
            <h3>Unemployed</h3>
            <p>Salary: $0</p>
        @endif
    </div>

    <br>

    <h2 class="text-lg font-semibold">Stats</h2>
    <div>
        <h3>Energy: {{$user->current_energy}}/{{$user->max_energy}}</h3> <!--Replace with bar-->
        <h3>Money: {{$user->money}}</h3>
        <h3>Intelligence: {{$user->intelligence}}</h3>
        <h3>Fitness: {{$user->fitness}}</h3>
        <h3>Charisma: {{$user->charisma}}</h3>
    </div>

    <br>

    <a href="#" class="bg-blue-600 p-2 text-white">Inventory</a>
    <a href="#" class="bg-blue-600 p-2 text-white">Owned Properties</a>

</section>

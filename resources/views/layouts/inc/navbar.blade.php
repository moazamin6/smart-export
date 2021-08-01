<nav class="navbar navbar-expand-lg navbar-light bg-light main-navbar"
     data-intro="These are the menu options where you can navigate to different pages." data-step="1">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link {{(Route::currentRouteName()==='dashboard')||(Route::currentRouteName()==='date-filter')?'active_item':''}}"
                   href="{{route('dashboard')}}">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Route::currentRouteName()==='triggers'?'active_item':''}}"
                   href="{{route('triggers')}}">Triggers</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{Route::currentRouteName()==='settings'?'active_item':''}}"
                   href="{{route('settings')}}">Settings</a>
            </li>
        </ul>
        <span class="date badge badge-pill">Today is: 01 September 2020</span>
    </div>
</nav>

@push('scripts')
    <script>
        let date = new Date();
        let currentDate = `${date.getDate()} ${date.toLocaleString('default', {month: 'long'})} ${date.getFullYear()}`
        $('.date').html('Today is: ' + currentDate)
    </script>
@endpush

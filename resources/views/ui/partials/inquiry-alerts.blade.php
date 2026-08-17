@if(session('success'))
    <div class="sk-inquiry-alert success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="sk-inquiry-alert error">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="sk-inquiry-alert error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@extends('layouts.app')

@section('bodyClasses')http-error http-error-@yield('code')@endsection

@section('content')
    <div class="http-error-container">
        <div class="http-error-card">
            <h1 class="http-error-code">@yield('code')</h1>
            @hasSection('imageUrl')
                <img src="@yield('imageUrl')" class="http-error-image" alt="@yield('code')">
            @endif
            <div class="http-error-message">@yield('message')</div>
        </div>
    </div>
@endsection

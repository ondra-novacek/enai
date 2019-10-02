<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    @include('partials._head')
    <body>   
        <div class="container fl-page">
            @yield('content')
            @include('partials._footer')
        </div>
        
        @include('partials._javascript')
        @yield('scripts')

    </body>
</html>

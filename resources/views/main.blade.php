<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

    @include('partials._head')
    <body>   
        
        @include('partials._javascript')
        @include('javascript.browserSupport')
        @include('javascript.saveForLater')

        @if(Request::path() === '/') 
            @include('javascript.randAvatars')
        @endif
        @if(Route::current()->getName() === 'show') 
            @include('javascript.printResults')
        @endif

        <div class="container fl-page">
            @yield('content')
            @include('partials._footer')
        </div>
        
        @yield('scripts')
    </body>

    <noscript>
        <style type="text/css">
            .fl-page {display:none;}
        </style>
        <div class="noscriptmsg">
            You don't have javascript enabled. Please, turn javascript on in browser settings.
        </div>
    </noscript>
</html>

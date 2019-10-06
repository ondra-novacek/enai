<nav class="mb-1 navbar navbar-expand-lg navbar-dark default-color-dark">
        <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="http://academicintegrity.eu"><strong>ENAI</strong></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/edit"><strong>Menu</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/"><strong>Tests</strong></a>
            </li>
          </ul>


          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                    <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    {{-- <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li> --}}
                @else
                <button class="btn btn-outline-white btn-md my-2 my-sm-0 ml-3" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">Logout</button>
                    <li class="nav-item dropdown">
                        {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a> --}}

                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
      </nav>
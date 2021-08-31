<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">
    <style>
        .base-container {
            display: flex;
            flex-direction: row;
            height: -webkit-fill-available;
            width: -webkit-fill-available;
        }

        /* sidebar */
        .sidebar-container {
            display: flex;
            flex-wrap: nowrap;
            height: 100vh;
            height: -webkit-fill-available;
            max-height: 100vh;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .bi {
            vertical-align: -.125em;
            pointer-events: none;
            fill: currentColor;
        }

        .dropdown-toggle {
            outline: 0;
        }

        .dropdown-toggle.username::after {
            border: 0 !important;
        }

        .nav-flush .nav-link {
            border-radius: 0;
        }

        /* end sidebar */

        /* content */
        .content-container {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            height: -webkit-fill-available;
            overflow: auto;
        }

        /* end content */
    </style>

    @include('partials.partial_header')
    @yield('header')
    <title>{{ env('APP_NAME') }} @yield('title', '')</title>
</head>

<body>
    <div class="sidebar-container float-start">
        <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="fs-4 bi me-2 fas fa-book"></i>
                <span class="fs-4">Library</span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="/page/books" class="nav-link text-white {{ ($title === 'Books') ? 'active': '' }}"
                        aria-current="page">
                        <i class="fas fa-book bi me-2"></i>
                        Books
                    </a>
                </li>
            </ul>
            @if(session()->get('role') === 'Admin')
            <hr>
            <ul class="nav nav-pills flex-column">
                <li>
                    <a href="/page/admin/books"
                        class="nav-link text-white {{ ($title === 'Manage Books') ? 'active': '' }}">
                        <i class="fas fa-book bi me-2"></i>
                        Manage Books
                    </a>
                </li>
                <li>
                    <a href="/page/admin/users"
                        class="nav-link text-white {{ ($title === 'Manage Users') ? 'active': '' }}">
                        <i class="fas fa-user bi me-2"></i>
                        Manage Users
                    </a>
                </li>
            </ul>
            @endif
            <hr class="mt-auto">
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle username"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset(session()->get('image_user')) }}" alt="" width="32" height="32"
                        class="rounded-circle me-2">
                    <strong>{{ session()->get('username') }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end text-small shadow"
                    aria-labelledby="dropdownUser1">
                    <li><a class="dropdown-item" href="{{ url('page/users/profile/setting') }}">Settings</a></li>
                    <li><a class="dropdown-item" href="{{ url('page/users/profile') }}">Profile</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('sign_out') }}">Sign out</a></li>
                </ul>
            </div>
        </div>
    </div>
    @yield('content')

    @include('partials.partial_footer')
    @yield('footer')
</body>

</html>
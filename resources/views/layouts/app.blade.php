<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Roles & Permissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">My App</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @can('manage-users')
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                        @endcan
                        @can('manage-users-related')
                        <li class="nav-item"><a class="nav-link" href="{{ route('roles.index') }}">Roles</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('permissions.index') }}">Permissions</a></li>
                        @endcan
                        @can('view_product')
                            <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">Products</a></li>
                        @endcan
                        @can('view_category')
                            <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Categories</a></li>
                        @endcan
                        @can('view_subcategory')
                            <li class="nav-item"><a class="nav-link" href="{{ route('subcategories.index') }}">Sub Categories</a></li>
                        @endcan

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @endauth
                    @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
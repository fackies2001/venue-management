<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Venue Management System – OCD</title>
    <link rel="icon" type="image/png" href="{{ asset('OCDLOGO.png') }}">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- FullCalendar --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

    <style>
        :root {
            --ocd-blue: #1a3c72;
            --ocd-orange: #e87722;
            --ocd-dark: #0a1144;
            --ocd-light-bg: #f4f7f6;
            /* Soft light gray para sa main content */
        }

        body {
            background: var(--ocd-light-bg);
            /* Pinalitan na natin ng Light Background */
            font-family: 'Segoe UI', sans-serif;
            color: #333;
            /* Dark text for light background */
            overflow-x: hidden;
        }

        /* Sidebar Styles (Dark Theme) */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--ocd-dark);
            /* Binalik natin sa Dark Blue */
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            /* Shadow para umangat ang sidebar */
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .sidebar.collapsed {
            left: -260px;
        }

        .sidebar-brand {
            padding: 2.5rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .sidebar-brand img {
            width: 75px;
            height: auto;
            margin-bottom: 1rem;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.3));
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: 0.5px;
            margin-bottom: 0.2rem;
            color: #fff;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            padding: 1.5rem 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, 0.4);
            padding: 0.5rem 1.5rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        .sidebar-nav .nav-item a {
            color: rgba(255, 255, 255, .75);
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.5rem;
            text-decoration: none;
            font-size: 0.95rem;
            transition: all .2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-nav .nav-item a i {
            font-size: 1.15rem;
            transition: color .2s ease;
        }

        .sidebar-nav .nav-item a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .sidebar-nav .nav-item a.active {
            background: linear-gradient(90deg, rgba(232, 119, 34, 0.2) 0%, transparent 100%);
            color: #fff;
            border-left: 4px solid var(--ocd-orange);
            font-weight: 600;
        }

        .sidebar-nav .nav-item a.active i {
            color: var(--ocd-orange);
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Top navbar */
        .top-navbar {
            background: var(--ocd-orange);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: .65rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        /* Burger Menu Styling */
        .burger-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            color: #fff;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.2rem 0.6rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .burger-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Cards */
        .card {
            background: #fff;
            color: #333;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .06);
            /* Lighter shadow for light background */
            border-top: 5px solid var(--ocd-orange);
            overflow: hidden;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 700;
            color: var(--ocd-blue);
            padding: 1.15rem 1.25rem;
        }

        /* Titles inside pages (like "Booking History") */
        h1,
        h2,
        h3,
        h4,
        h5,
        .page-title {
            color: var(--ocd-blue);
            /* Ensure page titles match the theme */
        }

        /* Badges */
        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-approved {
            background: #d1e7dd;
            color: #0f5132;
        }

        .badge-rejected {
            background: #f8d7da;
            color: #842029;
        }

        .badge-cancelled {
            background: #e2e3e5;
            color: #41464b;
        }

        .badge-completed {
            background: #cff4fc;
            color: #055160;
        }

        .badge-info {
            background: #cff4fc;
            color: #055160;
        }

        .alert {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Scrollbar styling for Dark Sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('OCDLOGO.png') }}" alt="OCD Logo">
            <div class="brand-title">Office of Civil Defense</div>
            <div class="brand-subtitle">Venue Management System</div>
        </div>

        <nav class="sidebar-nav">
            @auth
            <div class="nav-label">Main Menu</div>
            <ul class="nav flex-column list-unstyled">

                @php
                $prefix = match (auth()->user()->role) {
                'ndrrmoc_admin' => 'ndrrmoc',
                'nab_admin' => 'nab',
                'super_admin' => 'super-admin',
                default => 'user',
                };
                @endphp

                <li class="nav-item">
                    <a href="{{ route($prefix . '.calendar') }}"
                        class="{{ request()->routeIs($prefix . '.calendar*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-range"></i> Venue Calendar
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route($prefix . '.bookings.index') }}"
                        class="{{ request()->routeIs($prefix . '.bookings*') ? 'active' : '' }}">
                        <i class="bi bi-ui-checks"></i> Venue Booking List
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route($prefix . '.history') }}"
                        class="{{ request()->routeIs($prefix . '.history') ? 'active' : '' }}">
                        <i class="bi bi-clock"></i> History
                    </a>
                </li>

                @if (auth()->user()->isSuperAdmin())
                <div class="nav-label">Administration</div>
                <li class="nav-item">
                    <a href="{{ route('super-admin.users.index') }}"
                        class="{{ request()->routeIs('super-admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> User Management
                    </a>
                </li>
                @endif
            </ul>
            @endauth
        </nav>

        {{-- User info at bottom (Updated for Dark Theme) --}}
        @auth
        <div style="padding:1.25rem 1.5rem; background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05);">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div
                    style="width: 38px; height: 38px; background: var(--ocd-orange); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; color: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="overflow: hidden;">
                    <div
                        style="font-weight:600; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff;">
                        {{ auth()->user()->name }}
                    </div>
                    <div style="color:rgba(255,255,255,0.6); font-size: 0.75rem; text-transform:capitalize;">
                        {{ str_replace('_', ' ', auth()->user()->role) }}
                    </div>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="btn btn-sm w-100"
                style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.2); font-size:.8rem; font-weight: 500; transition: all 0.2s; border-radius: 6px;">
                <i class="bi bi-person-gear me-1"></i> Edit Profile
            </a>
        </div>
        @endauth
    </aside>

    {{-- Main Content --}}
    <div class="main-content" id="mainContent">

        {{-- Top Navbar --}}
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="burger-btn" id="burgerToggle" aria-label="Toggle Sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div style="font-weight:700; color:#fff; font-size: 1.1rem; letter-spacing: 0.5px; margin-top: 1px;">
                    Venue Management System
                </div>
            </div>

            @auth
            <div class="d-flex align-items-center gap-4">
                <span style="font-size:.85rem; font-weight: 500; color: rgba(255,255,255,0.95);">
                    <i class="bi bi-person-badge me-1 fs-6"></i> {{ auth()->user()->department }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm"
                        style="border-radius: 6px; font-weight: 600; background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.4); transition: all 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>

        {{-- Flash Messages --}}
        <div class="px-4 pt-4">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        {{-- Page Content --}}
        <div class="p-4">
            @yield('content')
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/fullcalendar.min.js') }}"></script>

    {{-- Script for Burger Menu --}}
    <script>
        document.getElementById('burgerToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');
        });
    </script>

    @stack('scripts')
</body>

</html>
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
    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --ocd-blue: #1a3c72;
            --ocd-orange: #e87722;
            --ocd-dark: #0a1144;
            --ocd-light-bg: #f4f6f9;
        }

        body {
            background: var(--ocd-light-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        /* ==========================================
           SIDEBAR STYLES (Dark Theme)
        ========================================== */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--ocd-dark);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .sidebar.collapsed {
            left: -260px;
        }

        .sidebar-brand {
            padding: 1.5rem 1.2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .sidebar-brand img {
            width: 50px;
            height: auto;
            margin-bottom: 0.85rem;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
        }

        .brand-title {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.3px;
            color: #fff;
            line-height: 1.2;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 0.2rem;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            padding: 0.5rem 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.4);
            padding: 0.5rem 1.5rem;
            margin-top: 0.5rem;
            font-weight: 600;
        }

        /* RESTORED: Original edge-to-edge sidebar links */
        .sidebar-nav .nav-item a {
            color: rgba(255, 255, 255, .75);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.5rem;
            text-decoration: none;
            font-size: 0.88rem;
            transition: all .2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-nav .nav-item a i {
            font-size: 1.1rem;
            transition: color .2s ease;
        }

        .sidebar-nav .nav-item a:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        /* RESTORED: Original Orange Gradient Active State */
        .sidebar-nav .nav-item a.active {
            background: linear-gradient(90deg, rgba(232, 119, 34, 0.2) 0%, transparent 100%);
            color: #fff;
            border-left: 4px solid var(--ocd-orange);
            font-weight: 600;
        }

        .sidebar-nav .nav-item a.active i {
            color: var(--ocd-orange);
        }

        /* Profile Section at Bottom */
        .sidebar-profile {
            padding: 1rem 1.25rem;
            background: rgba(0, 0, 0, 0.15);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: auto;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1rem;
            color: #fff;
        }

        /* ==========================================
           MAIN CONTENT & TOP NAVBAR
        ========================================== */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        .top-navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            padding: 0.75rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .burger-btn {
            background: transparent;
            border: none;
            color: var(--ocd-blue);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
            display: flex;
            align-items: center;
        }

        .burger-btn:hover {
            color: var(--ocd-orange);
        }

        /* Custom styles for Division and Logout (Blue default, Orange hover) */
        .top-division-badge {
            color: var(--ocd-blue);
            transition: all 0.2s ease;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            background: rgba(26, 60, 114, 0.05);
            cursor: default;
        }

        .top-division-badge:hover {
            color: var(--ocd-orange);
            background: rgba(232, 119, 34, 0.1);
        }

        .top-logout-btn {
            color: var(--ocd-blue);
            border: 1px solid var(--ocd-blue);
            background: transparent;
            transition: all 0.2s ease;
            font-weight: 600;
            border-radius: 6px;
        }

        .top-logout-btn:hover,
        .top-logout-btn:focus,
        .top-logout-btn:active {
            background: var(--ocd-orange) !important;
            border-color: var(--ocd-orange) !important;
            color: #fff !important;
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
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
                            'admin' => 'admin',
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
                                <i class="bi bi-people-fill"></i> User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('super-admin.divisions.index') }}"
                                class="{{ request()->routeIs('super-admin.divisions*') ? 'active' : '' }}">
                                <i class="bi bi-building"></i> Division
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('super-admin.buildings.index') }}"
                                class="{{ request()->routeIs('super-admin.buildings*') || request()->routeIs('super-admin.venues*') ? 'active' : '' }}">
                                <i class="bi bi-door-open-fill"></i> Venue
                            </a>
                        </li>

                        {{-- Added System/Archive Navigation here --}}
                        <div class="nav-label">System</div>
                        <li class="nav-item">
                            <a href="{{ route('super-admin.archive.index') }}"
                                class="{{ request()->routeIs('super-admin.archive*') ? 'active' : '' }}">
                                <i class="bi bi-archive-fill"></i> Archive
                            </a>
                        </li>
                    @endif
                </ul>
            @endauth
        </nav>

        {{-- User info at bottom --}}
        @auth
            <div class="sidebar-profile">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div style="overflow: hidden;">
                        <div
                            style="font-weight:600; font-size: 0.85rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #fff;">
                            {{ auth()->user()->name }}
                        </div>
                        <div style="color:rgba(255,255,255,0.6); font-size: 0.7rem; text-transform:capitalize;">
                            {{ str_replace('_', ' ', auth()->user()->role) }}
                        </div>
                    </div>
                </div>

                <a href="{{ route('profile.edit') }}" class="btn btn-sm w-100"
                    style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.8); border: 1px solid rgba(255,255,255,0.15); font-size:.75rem; transition: all 0.2s; border-radius: 6px;">
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
            </div>

            @auth
                <div class="d-flex align-items-center gap-3">
                    {{-- Custom Division Badge (Blue by default, Orange on Hover) --}}
                    <div class="d-none d-sm-block top-division-badge" style="font-size: 0.85rem;">
                        <i class="bi bi-building me-1"></i> {{ auth()->user()->division->name ?? 'No Division' }}
                    </div>

                    {{-- Custom Logout Button (Blue by default, Solid Orange on Hover) --}}
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm top-logout-btn px-3">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        {{-- Page Content --}}
        <div class="p-4 flex-grow-1">
            @yield('content')
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script for Burger Menu --}}
    <script>
        document.getElementById('burgerToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('expanded');

            // FIX: Force FullCalendar to resize smoothly during the 0.3s CSS transition
            let timesRun = 0;
            let interval = setInterval(() => {
                window.dispatchEvent(new Event('resize'));
                timesRun++;
                if (timesRun === 6) clearInterval(interval); // Stops after 300ms
            }, 50);
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            {{-- 1. Success Notification --}}
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            {{-- 2. General Error Notification --}}
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            {{-- 3. Validation Errors Popup (Now in English) --}}
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: '<span style="color:var(--ocd-blue)">Invalid Input</span>',
                    html: `
                    <div class="text-start">
                        <p class="fw-bold mb-2">Please correct the following errors:</p>
                        <ul class="text-danger small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                    confirmButtonColor: 'var(--ocd-blue)',
                    confirmButtonText: 'Understood',
                    customClass: {
                        popup: 'rounded-4 shadow-lg'
                    }
                });
            @endif
        });
    </script>
    @stack('scripts')
</body>

</html>

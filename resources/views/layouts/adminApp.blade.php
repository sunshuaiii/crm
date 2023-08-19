<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/icon/points.png') }}" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - @yield('title')</title>

    <!-- public/css/adminApp.css -->
    <link rel="stylesheet" href="{{ asset('css/adminApp.css') }}">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='fas fa-star icon'></i>
            <div class="logo_name">CRM</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <a href="{{ url('/admin') }}">
                    <i class='bx bx-home'></i>
                    <span class="links_name">Home</span>
                </a>
                <span class="tooltip">Home</span>
            </li>
            <li>
                <a href="{{ route('admin.couponManagement') }}">
                    <i class='bx bx-gift'></i>
                    <span class="links_name">Coupon Management</span>
                </a>
                <span class="tooltip">Coupon Management</span>
            </li>
            <li>
                <a href="{{ route('admin.staffRegistration') }}">
                    <i class='bx bx-user-plus'></i>
                    <span class="links_name">Staff Registration</span>
                </a>
                <span class="tooltip">Staff Registration</span>
            </li>
            <li>
                <a href="{{ route('admin.searchCustomer') }}">
                    <i class='bx bx-search-alt'></i>
                    <span class="links_name">Search Customer</span>
                </a>
                <span class="tooltip">Search Customer</span>
            </li>
            <!-- More tabs can be added here -->
            <li class="profile">
                <div class="profile-details">
                    <img src="{{ asset('images/icon/profile.png') }}" alt="profileImg">
                    <div class="name_job">
                        <div class="name">{{ Auth::user()->username }}</div>
                        <div class="job">Admin</div>
                    </div>
                </div>
                <a href="{{ route('logout') }}">
                    <i class='bx bx-log-out' id="log_out"></i>
                    <span class="links_name">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <header class="top-navbar navbar navbar-dark bg-dark">
            <div class="container">
                <div class="navbar-brand logo highlight mx-auto">
                    <span class="logo-text" style="font-size: 28px;">Management Dashboard</span>
                </div>
            </div>
        </header>
        @yield('content')
    </section>


    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");
        let searchBtn = document.querySelector(".bx-search");

        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });

        // searchBtn.addEventListener("click", () => { // Sidebar open when you click on the search iocn
        //     sidebar.classList.toggle("open");
        //     menuBtnChange(); //calling the function(optional)
        // });

        // following are the code to change sidebar button(optional)
        function menuBtnChange() {
            if (sidebar.classList.contains("open")) {
                closeBtn.classList.replace("bx-menu", "bx-menu-alt-right"); //replacing the iocns class
            } else {
                closeBtn.classList.replace("bx-menu-alt-right", "bx-menu"); //replacing the iocns class
            }
        }
    </script>
</body>

</html>
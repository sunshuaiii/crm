<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/icon/points.png') }}" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CRM - @yield('title')</title>

    <!-- public/css/adminApp.css -->
    <link rel="stylesheet" href="{{ asset('css/adminApp.css') }}">

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bxl-c-plus-plus icon'></i>
            <div class="logo_name">CRM</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="{{ url('/admin') }}">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="{{ url('/admin/coupon-management') }}">
                    <i class='bx bx-coupon'></i>
                    <span class="links_name">Coupon Management</span>
                </a>
                <span class="tooltip">Coupon Management</span>
            </li>
            <li>
                <a href="{{ url('/admin/search-customer') }}">
                    <i class='bx bx-user'></i>
                    <span class="links_name">Search Customer</span>
                </a>
                <span class="tooltip">Search Customer</span>
            </li>
            <li>
                <a href="{{ url('/admin/new-staff-registration') }}">
                    <i class='bx bx-user-plus'></i>
                    <span class="links_name">New Staff Registration</span>
                </a>
                <span class="tooltip">New Staff Registration</span>
            </li>
            <!-- More tabs can be added here -->
            <li class="profile">
                <div class="profile-details">
                    <img src="{{ asset('images/icon/profile.png') }}" alt="profileImg">
                    <div class="name_job">
                        <div class="name">Prem Shahi</div>
                        <div class="job">Web designer</div>
                    </div>
                </div>
                <i class='bx bx-log-out' id="log_out"></i>
            </li>
        </ul>
    </div>

    <section class="home-section">
        @yield('content')
    </section>

    <footer class="footer py-3">
        &copy; {{ date('Y') }} {{ 'Customer Relationship System for Retail Store' }}. All rights reserved.
    </footer>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let closeBtn = document.querySelector("#btn");
        let searchBtn = document.querySelector(".bx-search");

        closeBtn.addEventListener("click", () => {
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });

        searchBtn.addEventListener("click", () => { // Sidebar open when you click on the search iocn
            sidebar.classList.toggle("open");
            menuBtnChange(); //calling the function(optional)
        });

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
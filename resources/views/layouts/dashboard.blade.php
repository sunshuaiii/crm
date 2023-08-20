<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/icon/points.png') }}" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- public/css/adminApp.css -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Bootstrap JavaScript and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

    <script src="https://kit.fontawesome.com/3f39673dbb.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        .header {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4%;
        }
    </style>

</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='fas fa-star icon'></i>
            <div class="logo_name">CRM</div>
            <i class='bx bx-menu' id="btn"></i>
        </div>
        <ul class="nav-list">
            @if(Auth::guard('admin')->check())
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
            @elseif(Auth::guard('marketingStaff')->check())
            <!-- More tabs can be added here -->
            <li class="profile">
                <div class="profile-details">
                    <img src="{{ asset('images/icon/profile.png') }}" alt="profileImg">
                    <div class="name_job">
                        <div class="name">{{ Auth::user()->username }}</div>
                        <div class="job">Marketing Staff</div>
                    </div>
                </div>
                <a href="{{ route('logout') }}">
                    <i class='bx bx-log-out' id="log_out"></i>
                    <span class="links_name">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
            @elseif(Auth::guard('supportStaff')->check())
            <li>
                <a href="{{ url('/supportStaff') }}">
                    <i class='bx bx-home'></i>
                    <span class="links_name">Home</span>
                </a>
                <span class="tooltip">Home</span>
            </li>
            <li>
                <a href="{{ route('supportStaff.customerService') }}">
                    <i class='bx bx-headphone'></i>
                    <span class="links_name">Customer Service</span>
                </a>
                <span class="tooltip">Customer Service</span>
            </li>
            <li>
                <a href="{{ route('supportStaff.searchCustomer') }}">
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
                        <div class="job">Support Staff</div>
                    </div>
                </div>
                <a href="{{ route('logout') }}">
                    <i class='bx bx-log-out' id="log_out"></i>
                    <span class="links_name">Logout</span>
                </a>
                <span class="tooltip">Logout</span>
            </li>
            @endif
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
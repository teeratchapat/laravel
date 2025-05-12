<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>@yield('title', 'TimeOffSystem')</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>

    <!-- ใส่ favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/login.png') }}">


    {{-- <link rel='stylesheet' href="{{ asset('css/main.css') }}"> --}}

    <!-- เพิ่ม Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Flatpickr & Plugin --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <style>
        /* START CSS จาก importexcel */
        .processing-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .alert {
            margin-bottom: 1rem;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* END CSS จาก importexcel */

        /* START CSS จาก footer */
        .footer-background {
            background-color: #c0939333;
            /* ครีม */
        }

        /* END CSS จาก footer */



        /* ปรับให้ navbar สามารถแสดงผลได้ดีบนทุกขนาดหน้าจอ */
        .navbar-brand {
            font-size: 1.2rem;
            font-weight: bold;
        }

        /* ปรับการแสดงผลให้เหมาะสมกับมือถือ */
        .navbar-nav {
            margin-left: auto;
        }

        /* เพิ่มสไตล์ให้กับ nav-link */
        .nav-link {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }

        .nav-link:hover {
            color: #007bff;
            text-decoration: underline;
        }

        /* ปรับการแสดงผลของ user info และ logout ให้อยู่ที่ขวาสุด */
        .nav-item .nav-link {
            display: flex;
            align-items: center;
        }

        .navbar-collapse {
            justify-content: flex-end;
        }

        /* เพิ่มความสวยงามให้กับ button logout */
        .btn-logout {
            background-color: #b92a11;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            /* ลบเส้นขีดใต้ */
        }

        .btn-logout:hover {
            background-color: #9e2510;
            text-decoration: none;
            /* ลบเส้นขีดใต้เมื่อ hover */
        }

        /* ป้องกันไม่ให้ปุ่ม logout มีเส้นขีดใต้ */
        .btn-logout:focus,
        .btn-logout:active {
            box-shadow: none;
            /* ลบเงาเมื่อถูกคลิก */
            text-decoration: none;
            /* ลบเส้นขีดใต้ */
        }

        /* ปรับการแสดงผลให้เหมาะสมกับมือถือ */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-nav .nav-item {
                margin-left: 0;
                margin-right: 0;
            }
        }



        /* เมื่อ hover จะมีพื้นหลังและสีข้อความเปลี่ยน */
        .nav-link:hover {
            background-color: #007bff;
            /* สีพื้นหลังเมื่อ hover */
            color: white;
            /* สีข้อความยังคงเป็นสีขาว */
            border: 2px solid #007bff;
            /* สีขอบเมื่อ hover */
        }

        /* สีข้อความของลิงค์เป็นสีขาว */
        .nav-link {
            color: white !important;
            /* ทำให้สีข้อความเป็นสีขาว */
            background-color: transparent;
            /* พื้นหลังโปร่งใส */
            border: 2px solid transparent;
            /* ไม่มีขอบ */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* เพิ่มการเปลี่ยนสีอย่างนุ่มนวล */
        }

        /* === Animation Style === */
        .animated-page {
            animation: fadeInUp 0.8s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        /* animation navbar */
        .animated-navbar {
            animation: fadeDown 0.8s ease-out;
            opacity: 0;
            transform: translateY(-20px);
            animation-fill-mode: forwards;
        }

        @keyframes fadeDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation เวลา Hover ที่เมนู */
        .nav-link {
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: scale(1.05);
        }


        /* custom CSS หน้า report .custom-wide-box ? */
            {
            max-width: 95vw;
            margin: 0 auto;
        }

        .table td,
        .table th {
            padding: 0.75rem;
        }

        .table-responsive {
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }


        /* footer */
        .footer-background {
            background-color: #343a40;
            color: white;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .footer-background.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* เพิ่มเอฟเฟกต์ hover สำหรับลิงก์ */
        .footer-background a:hover {
            color: #ffc107;
            transition: color 0.3s ease;
        }



        /* From Uiverse.io by Nawsome */
        .svg-frame {
            position: relative;
            width: 300px;
            height: 300px;
            transform-style: preserve-3d;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .svg-frame svg {
            position: absolute;
            transition: .5s;
            z-index: calc(1 - (0.2 * var(--j)));
            transform-origin: center;
            width: 344px;
            height: 344px;
            fill: none;
        }

        .svg-frame:hover svg {
            transform: rotate(-80deg) skew(30deg) translateX(calc(45px * var(--i))) translateY(calc(-35px * var(--i)));
        }

        .svg-frame svg #center {
            transition: .5s;
            transform-origin: center;
        }

        .svg-frame:hover svg #center {
            transform: rotate(-30deg) translateX(45px) translateY(-3px);
        }

        #out2 {
            animation: rotate16 7s ease-in-out infinite alternate;
            transform-origin: center;
        }

        #out3 {
            animation: rotate16 3s ease-in-out infinite alternate;
            transform-origin: center;
            stroke: #ff0;
        }

        #inner3,
        #inner1 {
            animation: rotate16 4s ease-in-out infinite alternate;
            transform-origin: center;
        }

        #center1 {
            fill: #ff0;
            animation: rotate16 2s ease-in-out infinite alternate;
            transform-origin: center;
        }

        @keyframes rotate16 {
            to {
                transform: rotate(360deg);
            }
        }


        body {
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            /* สีเท่ๆ นีออนเข้ม */
            color: #ffffff;
            /* เพื่อให้อ่านง่าย */
        }

        .svg-frame {
            filter: drop-shadow(0 0 8px #00ffff);
            /* เพิ่มแสงให้ SVG */
        }

        .container {
            background-color: rgba(0, 0, 0, 0.3);
            /* เพิ่มพื้นหลังโปร่งให้กล่องข้อความ */
            border-radius: 16px;
            padding: 2rem;
        }
    

    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary animated-navbar fade-in-down" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#" style="font-size: 1.2rem; color: #154744;">
                <i class="bi bi-calendar-x-fill me-2" style="font-size: 1.5rem; color: #4CAF50;"></i>
                <span class="fw-bold" style="color: #4CAF50;">ระบบจัดการการลา</span>
                <span style="font-size: 1rem; color: #888;">[Time-Off-System]</span>
            </a>

            {{-- <a class="version text-light d-flex align-items-center" href="#" style="font-size: 14px;">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>VERSION</strong> : <span class="text-primary">#1.0.0</span>
                <span class="ms-2">|</span> <span class="text-warning">พัฒนาเดือน เมษายน 2568</span>
            </a> --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark rounded-3 px-4 py-2 me-3 my-2"
                            {{ request()->is('home') ? 'active' : '' }}" href="{{ url('/home') }}">
                            <i class="bi bi-house-door me-2"></i> หน้าแรก
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark rounded-3 px-4 py-2 me-3 my-2"
                            href="{{ route('history.leave') }}">
                            <i class="bi bi-file-earmark-text me-2"></i> จัดการรายงานการลา
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark rounded-3 px-4 py-2 me-3 my-2"
                            href="{{ route('import.leave') }}">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i> import Excel HIS
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark rounded-3 px-4 py-2 me-3 my-2"
                            href="{{ route('employees.index') }}">
                            <i class="bi bi-person-lines-fill me-2"></i> จัดการข้อมูลพนักงาน
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn btn-light text-dark rounded-3 px-4 py-2 me-3 my-2"
                            href="{{ route('report.leave-history') }}">
                            <i class="bi bi-bar-chart me-2"></i> รายงาน การลา
                        </a>
                    </li>





                    @auth
                        <li class="nav-item -mt-2">
                            <span class="nav-link d-flex align-items-center border rounded-3 p-3"
                                style="background-color: #154744; color: white; font-size: 14px;">
                                <i class="bi bi-person-circle me-3" style="font-size: 20px;"></i>
                                <span class="fw-bold">{{ Auth::user()->pre_name }}{{ Auth::user()->first_name }}
                                    {{ Auth::user()->last_name }}</span>
                                <span class="ms-2">|</span>
                                <span class="text-success ms-2">กำลังใช้งาน</span>
                            </span>
                        </li>

                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <li class="nav-item -mt-2">
                            <span class="nav-link d-flex align-items-center border rounded-3 p-2">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-logout btn-link" style="cursor: pointer;">
                                        <i class="bi bi-person-fill-up"></i> LOGOUT
                                    </button>
                                </form>
                            </span>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- content --}}
    <div class="container-fluid py-2 animated-page fade-in-up">
        @yield('content')
    </div>

    {{-- นำเข้า Footer --}}
    @include('layouts.footer')
</body>

</html>

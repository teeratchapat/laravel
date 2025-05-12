<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>เข้าสู่ระบบ</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    {{-- <link rel='stylesheet' type='text/css' media='screen' href='main.css'> --}}
    <!-- ใส่ favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/login.png') }}">
    {{-- bootstrap5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .alert {
            position: relative;
            /* ให้ alert อยู่ในตำแหน่งที่สามารถใช้ z-index */
            z-index: 1050;
            /* ค่า z-index ที่สูงกว่าพื้นหลังทั่วไป */
        }

        .gradient-custom {
            /* background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            min-height: 100vh; */

            background: linear-gradient(270deg, #6a11cb, #2575fc);
            background-size: 400% 400%;
            animation: gradientMove 10s ease infinite;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            min-height: 100vh;
        }

        /* Animation ของพื้นหลังไหลไปมา */
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .glitter {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background-image: radial-gradient(rgba(255, 255, 255, 0.4) 1px, transparent 1px);
            background-size: 30px 30px;
            animation: sparkleMove 3s linear infinite;
            opacity: 0.3;
            z-index: 2;
            /* ต้องมากกว่าพื้นหลัง แต่ไม่ทับเนื้อหา */
        }

        @keyframes sparkleMove {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 60px 60px;
            }
        }






        .content-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            width: 100%;
        }

        /* START-logo--27/03/68 */
        .login-logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .login-container {
            text-align: center;
        }

        /* เพิ่มลูกเล่นเมื่อมีการโฮเวอร์ที่คอนเทนเนอร์ */
        #theme-container {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        #theme-container:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* ปรับให้ไอคอนตาอยู่ในกล่องของรหัสผ่าน */
        .input-group {
            position: relative;
        }

        .input-group .input-group-text {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
        }



        /* ปรับให้ label ดูเด่นเมื่อคลิก หรือมีการกรอกข้อมูล */
        .form-floating>.form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
        }

        .form-floating label {
            color: #6c757d;
            transition: 0.2s ease;
        }

        .form-floating>.form-control:focus~label {
            color: #007bff;
        }

        /* ปรับให้ input มีการเคลื่อนไหวเมื่อ hover */
        .form-control:hover {
            border-color: #007bff;
        }


        /* ปรับให้คอนเทนเนอร์มีความกว้างพอเหมาะสำหรับทุกอุปกรณ์ */
        #theme-container {
            max-width: 100%;
            width: 100%;
            padding: 0 20px;
            /* เพิ่ม padding ซ้ายขวา */
        }

        /* เพิ่มความกว้างสำหรับหน้าจอขนาดใหญ่ */
        .card {
            max-width: 500px;
            /* กำหนดความกว้างสูงสุดที่เหมาะสมสำหรับ PC */
            width: 100%;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            /* เพิ่มเงา */
        }

        /* ปรับให้เหมาะสมกับขนาดหน้าจอเล็ก (มือถือ) */
        @media (max-width: 768px) {
            .card {
                max-width: 90%;
                /* ลดขนาดสูงสุดเมื่อใช้มือถือ */
            }
        }

        /* ปรับให้เหมาะสมกับหน้าจอใหญ่ (PC) */
        @media (min-width: 992px) {
            .card {
                max-width: 450px;
                /* ขนาดสูงสุดของ card สำหรับหน้าจอขนาดกลางขึ้นไป */
            }
        }

        /* เพิ่มความสวยงามเมื่อ hover ที่ input */
        .form-control:hover {
            border-color: #007bff;
        }



        /* Fade-in สำหรับทั้งหน้า */
        .fade-in {
            animation: fadeIn 1.2s ease-in-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo bounce-in effect */
        .login-logo {
            animation: bounceIn 1s ease-out;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            60% {
                transform: scale(1.1);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }

        /* ปรับให้ปุ่มมีเอฟเฟกต์ hover สวยขึ้น */
        .btn-outline-light:hover {
            background-color: #ffffff;
            color: #007bff;
            transition: 0.3s ease-in-out;
            transform: scale(1.05);
        }

        /* Animation เมื่อ input โฟกัส */
        .form-control:focus {
            animation: pulse 0.3s;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.3);
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        /* === Animation Effect === */
        .animated {
            opacity: 0;
            animation-fill-mode: forwards;
            animation-duration: 1s;
            animation-timing-function: ease;
        }

        .fade-in-down {
            animation-name: fadeInDown;
        }

        .fade-in-up {
            animation-name: fadeInUp;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* === Delay สำหรับแสดงทีละบรรทัด === */
        .delay-1 {
            animation-delay: 0.3s;
        }

        .delay-2 {
            animation-delay: 0.6s;
        }



        /* เอฟเฟกต์สำหรับลิงก์ลงทะเบียน */
        .animated-link {
            position: relative;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .animated-link::after {
            content: "";
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #ffffff;
            transition: width 0.3s ease;
        }

        .animated-link:hover {
            color: #ffcc00;
            /* สีเหลืองตอน hover */
            transform: translateY(-2px);
            /* ลอยขึ้นนิดหน่อย */
        }

        .animated-link:hover::after {
            width: 100%;
        }


      /* ปรับmodal box แจ้งเตือนใส่ user และ รหัสผ่าน ผิดพลาด   */
        /* ปรับให้ modal มีความกว้างพอเหมาะสำหรับทุกอุปกรณ์ */
        .modal-dialog {
            max-width: 90%;
            width: 100%;
            margin: auto;
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 768px) {
            .modal-dialog {
                max-width: 500px;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดเล็ก */
        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 90%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดกลาง */
        @media (min-width: 576px) and (max-width: 768px) {
            .modal-dialog {
                max-width: 80%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 768px) and (max-width: 992px) {
            .modal-dialog {
                max-width: 70%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 992px) and (max-width: 1200px) {
            .modal-dialog {
                max-width: 60%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 1200px) {
            .modal-dialog {
                max-width: 50%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 1400px) {
            .modal-dialog {
                max-width: 40%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 1600px) {
            .modal-dialog {
                max-width: 30%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 1800px) {
            .modal-dialog {
                max-width: 20%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 2000px) {
            .modal-dialog {
                max-width: 10%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 2200px) {
            .modal-dialog {
                max-width: 5%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 2400px) {
            .modal-dialog {
                max-width: 3%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 2600px) {
            .modal-dialog {
                max-width: 2%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 2800px) {
            .modal-dialog {
                max-width: 1%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 3000px) {
            .modal-dialog {
                max-width: 0%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 3200px) {
            .modal-dialog {
                max-width: 0%;
            }
        }
        /* ปรับให้ modal มีความกว้างสูงสุดสำหรับหน้าจอขนาดใหญ่ */
        @media (min-width: 3400px) {
            .modal-dialog {
                max-width: 0%;
            }
        }
        .custom-modal-content {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            color: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            animation: fadeInScale 0.5s ease-out;
            overflow: hidden;
            position: relative;
        }

        /* เอฟเฟกต์ตอน modal โผล่มา */
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ปุ่ม close สีขาว */
        .custom-modal-content .btn-close {
            filter: invert(1);
        }

        /* เพิ่มเงาไอคอน */
        .custom-modal-content .modal-title i {
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.4);
        }
    </style>


</head>

<body>



    {{-- Modal สำหรับแจ้งข้อผิดพลาด --}}
    {{-- <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-danger text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">
                        <i class="fas fa-exclamation-circle"></i> ข้อผิดพลาด!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertMessage">
                    <!-- ข้อความข้อผิดพลาดจะแสดงที่นี่ -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content custom-modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="alertModalLabel">
                        <i class="fas fa-exclamation-circle fa-shake"></i> ข้อผิดพลาด!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="alertMessage">
                    <!-- ข้อความข้อผิดพลาดจะแสดงที่นี่ -->
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle me-1"></i> ปิด
                    </button>
                </div>
            </div>
        </div>
    </div>


    @if ($errors->has('login'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById('alertModal'));
                var alertMessage = document.getElementById('alertMessage');
                alertMessage.innerHTML = '{{ $errors->first('login') }}'; // แสดงข้อความจาก error
                modal.show(); // แสดง Modal
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById('alertModal'));
                var alertMessage = document.getElementById('alertMessage');
                alertMessage.innerHTML = '{{ session('error') }}'; // แสดงข้อความจาก session error
                modal.show(); // แสดง Modal
            });
        </script>
    @endif

    {{-- เอฟเฟกต์พื้นหลัง --}}
    <div class="glitter"></div>

    <section class="vh-100 gradient-custom">
        <div class="content-wrapper">
            <div class="container-fluid py-5 h-100" id="theme-container">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-dark text-white fade-in" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <img src="{{ asset('images/login.png') }}" alt="Logo" class="login-logo">

                                    <h2 class="fw-bold mb-2 text-uppercase animated fade-in-down delay-1">
                                        เข้าสู่ระบบผู้ใช้งาน</h2>
                                    <p class="text-white-50 mb-5 animated fade-in-up delay-2 ">
                                        กรุณากรอกข้อมูลให้ครบถ้วนสมบูรณ์</p>
                                    <form method="POST" action="{{ route('login.process') }}">
                                        @csrf <!-- ป้องกัน CSRF -->

                                        <div data-mdb-input-init class="form-outline form-white mb-4">
                                            <div class="form-floating">
                                                <input type="text" id="username"
                                                    class="form-control form-control-lg" name="username"
                                                    placeholder="ชื่อผู้ใช้งาน" required />
                                                <label class="form-label" for="username">Username</label>
                                            </div>
                                        </div>


                                        <div class="form-outline form-white mb-4">
                                            <div class="input-group">
                                                <div class="form-floating">
                                                    <input type="password" id="password"
                                                        class="form-control form-control-lg" name="password"
                                                        placeholder="รหัสผ่าน" required />
                                                    <label class="form-label" for="password">Password</label>
                                                </div>
                                                <span id="showPasswordIcon" class="input-group-text">
                                                    <i class="fas fa-eye-slash"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- 2 column grid layout for inline styling -->
                                        <div class="row mb-4">
                                            <div class="col d-flex justify-content-center">
                                                <!-- Checkbox -->
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="showPasswordCheckbox" />
                                                    <label class="form-check-label" for="showPasswordCheckbox"> Show
                                                        Password
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <button data-mdb-button-init data-mdb-ripple-init
                                            class="btn btn-outline-light btn-lg px-5"
                                            type="submit">เข้าสู่ระบบ</button>
                                    </form>
                                    {{-- <div class="mt-4">
                                        <p class="text-white-50">ถ้ายังไม่มีบัญชีผู้ใช้งาน? <a
                                                href="{{ route('register') }}" class="text-white">ลงทะเบียนที่นี่</a>
                                        </p>
                                    </div> --}}

                                    <div class="mt-4">
                                        <p class="text-white-50">
                                            ถ้ายังไม่มีบัญชีผู้ใช้งาน?
                                            <a href="{{ route('register') }}" class="animated-link">ลงทะเบียนที่นี่</a>
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Function to toggle password visibility based on checkbox
        document.getElementById('showPasswordCheckbox').addEventListener('change', function() {
            var passwordField = document.getElementById('password');
            var icon = document.getElementById('showPasswordIcon').querySelector('i');

            if (this.checked) {
                passwordField.type = 'text'; // Show password
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password'; // Hide password
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>

</body>

</html>

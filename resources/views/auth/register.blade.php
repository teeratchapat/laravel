<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>สมัครสมาชิก</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ใส่ favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo1.png') }}">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- FontAwesome for Icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: linear-gradient(to right, rgba(106, 17, 203, 1), rgba(37, 117, 252, 1));
            font-family: 'Arial', sans-serif;
            color: white;
        }

        .container {
            margin-top: 100px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control {
            padding-left: 40px;
        }

        .form-control-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .form-group {
            position: relative;
        }

        h2,
        .card-body p {
            color: #333;
        }

        .btn-primary {
            background-color: #6a11cb;
            border-color: #6a11cb;
        }

        .btn-primary:hover {
            background-color: #2575fc;
            border-color: #2575fc;
        }

        /* ปรับสไตล์ปุ่มย้อนกลับ */
        .btn-back {
            background-color: #ff6600;
            /* สีส้ม */
            border: 1px solid #ff6600;
            color: #fff;
            border-radius: 25px;
            font-size: 16px;
            padding: 10px 20px;
        }

        .btn-back:hover {
            background-color: #ff4500;
            /* สีส้มเข้มเมื่อเอาเมาส์ไปวาง */
            border-color: #ff4500;
        }

        .btn-secondary {
            background-color: #ddd;
            border-color: #ccc;
        }

        .btn-secondary:hover {
            background-color: #ccc;
            border-color: #bbb;
        }

        .header-text {
            color: #333;
            font-weight: 600;
        }

        .header-subtext {
            color: #666;
            font-style: italic;
        }

        .icon-header {
            color: #6a11cb;
            font-size: 40px;
            margin-bottom: 15px;
        }

        /* สำหรับไอคอนดวงตา */
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        /* สีข้อความเตือนรหัสผ่านไม่ตรงกัน */
        .error-message {
            color: red;
            font-size: 0.875rem;
        }


        .password-strength {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 5px;
        }

        .weak {
            color: red;
        }

        .medium {
            color: orange;
        }

        .strong {
            color: green;
        }



        /* ANIMATION */
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

        .fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }

        .delay-1 {
            animation-delay: 0.3s;
        }

        .delay-2 {
            animation-delay: 0.6s;
        }

        /* GLITTER EFFECT */
        .glitter {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background-image: radial-gradient(rgba(255, 255, 255, 0.3) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: sparkleMove 3s linear infinite;
            opacity: 0.4;
            z-index: 0;
        }

        @keyframes sparkleMove {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 40px 40px;
            }
        }

        /* CARD HOVER */
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease;
        }

        /* INPUT HIGHLIGHT */
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
        }


        /* CSS ตรงนี้เพิ่มความรู้สึกว่าเป็น dropdown: */
        select.form-control {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding-right: 40px;
            background-color: white;
            color: #333;
            cursor: pointer;
        }

        select.form-control:hover {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.2);
        }
    </style>

    <script>
        // ฟังก์ชันสำหรับแสดง/ซ่อนรหัสผ่าน
        function togglePasswordVisibility() {
            const passwordField = document.getElementById("password");
            const confirmationField = document.getElementById("password_confirmation");
            const passwordIcon = document.getElementById("password-eye");

            // Toggle the type of input fields between password and text
            if (passwordField.type === "password") {
                passwordField.type = "text";
                confirmationField.type = "text";
                passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = "password";
                confirmationField.type = "password";
                passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // ฟังก์ชันสำหรับตรวจสอบระดับความยากของรหัสผ่าน
        function checkPasswordStrength() {
            const password = document.getElementById("password").value;
            const strengthText = document.getElementById("password-strength");
            const regexWeak = /^[a-zA-Z]{6,}$/;
            const regexMedium = /^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]{6,}$/;
            const regexStrong = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

            // Regular Expression เพื่อตรวจสอบการมีพยัญชนะไทยในรหัสผ่าน
            const regexThai = /[\u0E00-\u0E7F]/;

            // ตรวจสอบว่ามีพยัญชนะไทยหรือไม่
            if (regexThai.test(password)) {
                strengthText.textContent = "รหัสผ่านไม่สามารถมีตัวอักษรภาษาไทยได้";
                strengthText.className = "password-strength weak";
                return;
            }

            console.log("Password entered: " + password); // เพื่อตรวจสอบว่าได้ค่าถูกต้องไหม

            // ตรวจสอบความยาวของรหัสผ่าน
            if (password.length < 6) {
                strengthText.textContent = "รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัว";
                strengthText.className = "password-strength weak";
                return;
            }

            // ตรวจสอบรหัสผ่านตาม regex
            if (regexStrong.test(password)) {
                strengthText.textContent = "ค่อนข้างยาก";
                strengthText.className = "password-strength strong";
            } else if (regexMedium.test(password)) {
                strengthText.textContent = "ปานกลาง";
                strengthText.className = "password-strength medium";
            } else if (regexWeak.test(password)) {
                strengthText.textContent = "ค่อนข้างง่าย";
                strengthText.className = "password-strength weak";
            } else {
                strengthText.textContent = "";
                strengthText.className = "password-strength";
            }
        }






        // ตรวจสอบการยืนยันรหัสผ่าน
        function checkPasswordMatch() {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("password_confirmation").value;
            const errorMessage = document.getElementById("error-message");

            // ตรวจสอบรหัสผ่านตรงกันหรือไม่
            if (password !== confirmPassword) {
                errorMessage.style.display = "block";
                return false; // ห้ามส่งฟอร์ม
            } else {
                errorMessage.style.display = "none";
                return true; // ส่งฟอร์มได้
            }
        }


        // ตรวจสอบการยืนยันรหัสผ่านเมื่อฟอร์มถูกส่ง
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("registerForm").onsubmit = function(event) {
                if (!checkPasswordMatch()) {
                    event.preventDefault(); // ป้องกันการส่งฟอร์ม
                }
            };


            const passwordInput = document.getElementById("password");
            const strengthText = document.getElementById("password-strength");

            // ตรวจสอบความยากของรหัสผ่านเมื่อมีการพิมพ์
            passwordInput.addEventListener('keyup', function() {
                checkPasswordStrength();
            });
        });
    </script>

</head>

<body>
    {{-- เอฟเฟกต์พื้นหลัง --}}
    <div class="glitter"></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <!-- ปุ่มย้อนกลับไปหน้า Login -->
                        <div class="text-start mb-3">
                            <a href="{{ route('login') }}" class="btn btn-back">
                                <i class="fas fa-arrow-left"></i> ย้อนกลับไปหน้า Login
                            </a>
                        </div>

                        <!-- Header ส่วนหัว -->
                        <div class="text-center mb-4">
                            <i class="fas fa-user-plus icon-header fade-in-up delay-1"></i>
                            <h2 class="header-text fade-in-up delay-2">ลงทะเบียน</h2>
                            <p class="header-subtext fade-in-up delay-2">กรุณากรอกข้อมูลให้ครบถ้วนเพื่อสร้างบัญชีใหม่
                            </p>
                        </div>

                        <form method="POST" action="{{ route('register.process') }}" id="registerForm">
                            @csrf <!-- ป้องกัน CSRF -->

                            <!-- ชื่อผู้ใช้งาน -->
                            {{-- <div class="form-group mb-3">
                                <label for="user_name" class="form-label">ชื่อผู้ใช้งาน</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="user_name" name="user_name"
                                        placeholder="Username" required>
                                    <i class="fas fa-user form-control-icon"></i>
                                </div>
                            </div> --}}
                            <div class="form-group mb-3">
                                <label for="user_name" class="form-label">ชื่อผู้ใช้งาน</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                        id="user_name" name="user_name" placeholder="Username" required
                                        value="{{ old('user_name') }}">
                                    <i class="fas fa-user form-control-icon"></i>
                                    @error('user_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- คำนำหน้าชื่อ -->
                            {{-- <div class="form-group mb-3">
                                <label for="pre_name" class="form-label">คำนำหน้าชื่อ</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="pre_name" name="pre_name"
                                        placeholder="คำนำหน้า" required>
                                    <i class="fas fa-id-card form-control-icon"></i>
                                </div>
                            </div> --}}
                            <div class="form-group mb-3">
                                <label for="pre_name" class="form-label">คำนำหน้าชื่อ</label>
                                <div class="position-relative">
                                    <select class="form-control" id="pre_name" name="pre_name" required>
                                        <option value="" disabled selected>เลือกคำนำหน้า</option>
                                        <option value="นาย">นาย</option>
                                        <option value="นาง">นาง</option>
                                        <option value="นางสาว">นางสาว</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                    <i class="fas fa-id-card form-control-icon"></i>
                                    <i class="fas fa-caret-down form-control-icon" style="right: 10px; left: auto;"></i>
                                </div>
                            </div>


                            <!-- ชื่อจริง -->
                            <div class="form-group mb-3">
                                <label for="first_name" class="form-label">ชื่อจริง</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        placeholder="ชื่อจริง" required>
                                    <i class="fas fa-user form-control-icon"></i>
                                </div>
                            </div>

                            <!-- นามสกุล -->
                            <div class="form-group mb-3">
                                <label for="last_name" class="form-label">นามสกุล</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        placeholder="นามสกุล" required>
                                    <i class="fas fa-user form-control-icon"></i>
                                </div>
                            </div>

                            <!-- สถานะผู้ใช้ -->
                            {{-- <div class="form-group mb-3">
                                <label for="user_status" class="form-label">ระดับสิทธิ์</label>
                                <select class="form-control" id="user_status" name="user_status" required>
                                    <option value="" disabled selected>กรุณาเลือกสถานะผู้ใช้</option>
                                    <option value="admin">Admin(สิทธิ์ผู้ใช้ระดับสูง)</option>
                                    <option value="staff">Staff(สิทธิ์ผู้ใช้ทั่วไป)</option>
                                </select>
                            </div> --}}
                            <div class="form-group mb-3">
                                <label for="user_status" class="form-label">ระดับสิทธิ์</label>
                                <div class="position-relative">
                                    <select class="form-control" id="user_status" name="user_status" required>
                                        <option value="" disabled selected>กรุณาเลือกสถานะผู้ใช้</option>
                                        <option value="admin">Admin (สิทธิ์ผู้ใช้ระดับสูง)</option>
                                        <option value="staff">Staff (สิทธิ์ผู้ใช้ทั่วไป)</option>
                                    </select>
                                    <i class="fas fa-caret-down form-control-icon" style="right: 10px; left: auto;"></i>
                                </div>
                            </div>

                            <!-- ตำแหน่ง -->
                            <div class="form-group mb-3">
                                <label for="position" class="form-label">ตำแหน่ง</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="position" placeholder="ตำแหน่ง"
                                        name="position">
                                    <i class="fas fa-briefcase form-control-icon"></i>
                                </div>
                            </div>

                            <!-- แผนก -->
                            <div class="form-group mb-3">
                                <label for="department" class="form-label">แผนก</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="department" placeholder="แผนก"
                                        name="department">
                                    <i class="fas fa-building form-control-icon"></i>
                                </div>
                            </div>

                            <!-- รหัสผ่าน -->
                            <div class="form-group mb-3">
                                <label for="password" class="form-label">รหัสผ่าน</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="*****" required onkeyup="checkPasswordStrength()">
                                    <i class="fas fa-lock form-control-icon"></i>
                                    <!-- ไอคอนดวงตา -->
                                    <i id="password-eye" class="fas fa-eye eye-icon"
                                        onclick="togglePasswordVisibility()"></i>
                                </div>
                                <!-- ระดับความยากของรหัสผ่าน -->
                                <div id="password-strength" class="password-strength"></div>
                            </div>

                            <!-- ยืนยันรหัสผ่าน -->
                            <div class="form-group mb-3">
                                <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="*****" required>
                                    <i class="fas fa-lock form-control-icon"></i>
                                </div>
                                <!-- ข้อความแจ้งเตือนหากรหัสผ่านไม่ตรงกัน -->
                                <div id="error-message" class="error-message" style="display:none;">
                                    รหัสผ่านไม่ตรงกัน กรุณากรอกใหม่
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">ลงทะเบียน</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

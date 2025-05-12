@extends('layouts.layout')

@section('title', 'การดูการลา')

@section('content')
    <div class="container mt-4">
        <br>
        <h2 class="text-center">สรุปสถิติ ขาด ลา มาสาย</h2>

        <!-- เพิ่มตัวแปร hidden เพื่อเก็บเดือนปีปัจจุบัน -->
        <input type="hidden" id="currentMonthYear" value="{{ date('Y-m') }}">

        <!-- กล่องฟิลเตอร์ -->
        <div class="row mt-3 mb-3">
            <div class="col-md-4">
                <input type="text" id="search" class="form-control" placeholder="🔍 ค้นหาพนักงาน, ตำแหน่ง, แผนก">
            </div>

            {{-- START--MonthFilter --}}
            <div class="col-md-4">
                {{-- <label for="monthFilter">เลือกเดือน (พ.ศ.)</label> --}}
                <input type="text" id="monthFilter" class="form-control" placeholder="📅 เลือก เดือน ปี">
                <input type="hidden" id="monthFilterHidden" name="month">
            </div>
            {{-- END--MonthFilter --}}

            <div class="col-md-4">
                {{-- อันเก่า --}}
                {{-- <select id="leaveTypeFilter" class="form-control">
                    <option value="">📝ประเภทการลา</option>
                    <option value="personal_leave">ลากิจ</option>
                    <option value="vacation_leave">พักร้อน</option>
                    <option value="sick_leave">ลาป่วย</option>
                    <option value="absent_days">ขาด</option>
                    <option value="late_days">มาสาย</option>
                </select> --}}

                {{-- ใหม่ 27/3/68 --}}
                <select id="leaveTypeFilter" class="form-control">
                    <option value="">📝: พนักงานทั้งหมด</option>
                    <option value="personal_leave">🗳️: พนักงานลากิจ</option>
                    <option value="vacation_leave">🏖️: พนักงานพักร้อน</option>
                    <option value="sick_leave">🤒: พนักงานลาป่วย</option>
                    <option value="absent_days">🙅🏻‍♂️: พนักงานขาด</option>
                    <option value="late_days">🕘: พนักงานมาสาย</option>
                </select>
            </div>
        </div>

        <!-- ปุ่มค้นหาและล้างข้อมูล -->
        <div class="d-flex justify-content-end mb-3">
            <button id="searchButton" class="btn btn-outline-primary w-48 mx-1">🔍 ค้นหา</button>
            <button id="resetButton" class="btn btn-outline-secondary w-48 mx-1">🔄 ล้างค่า</button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark text-center">
                    <tr>
                        <th>#</th>
                        <th>รหัส</th>
                        <th>ชื่อ-สกุล</th>
                        <th>ตำแหน่ง</th>
                        <th>แผนก</th>
                        <th>ลากิจ</th>
                        <th>พักร้อน</th>
                        <th>ป่วย</th>
                        <th>ขาด</th>
                        <th>สาย</th>
                        <th>รวม</th>
                    </tr>
                </thead>
                <tbody id="employee-table">
                    @foreach ($employees as $key => $employee)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->pre_name }}{{ $employee->full_name }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department }}</td>
                            <td>{{ $employee->personal_leave ?? 0 }}</td>
                            <td>{{ $employee->vacation_leave ?? 0 }}</td>
                            <td>{{ $employee->sick_leave ?? 0 }}</td>
                            <td>{{ $employee->absent_days ?? 0 }}</td>
                            <td>{{ $employee->late_days ?? 0 }}</td>
                            <td>{{ $employee->leave_total ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function() {

            // ดึงค่าเดือน-ปีปัจจุบัน
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth();
            let currentMonthName = new Intl.DateTimeFormat('th-TH', {
                month: 'long'
            }).format(currentDate);
            let currentYearBE = currentYear + 543;

            // SATRT--Flatpickr
            // ตั้งค่า Flatpickr สำหรับเลือกเดือน
            flatpickr("#monthFilter", {
                locale: "th",
                dateFormat: "F Y", // แสดงเป็นชื่อเดือน + ปี
                // defaultDate: new Date(),
                defaultDate: currentDate,
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false,
                        dateFormat: "Y-m",
                        theme: "light"
                    })
                ],
                formatDate: function(date, format) {
                    let yearBE = date.getFullYear() + 543; // แปลงเป็น พ.ศ.
                    return flatpickr.formatDate(date, format).replace(date.getFullYear(), yearBE);
                },
                parseDate: function(datestr, format) {
                    let parts = datestr.split(" ");
                    let month = parts[0]; // ชื่อเดือน
                    let yearBE = parseInt(parts[1]); // ปี พ.ศ.
                    let yearAD = yearBE - 543; // แปลงกลับเป็น ค.ศ.
                    return flatpickr.parseDate(month + " " + yearAD, format);
                },
                onYearChange: function(selectedDates, dateStr, instance) {
                    setTimeout(() => {
                        let newYearBE = instance.currentYear + 543; // คำนวณปี พ.ศ.
                        instance.currentYearElement.value = newYearBE; // อัปเดตค่าใน UI
                        $(".cur-year").text(newYearBE); // แก้ปัญหาปีเด้งกลับ ค.ศ.
                    }, 10);
                },
                onMonthChange: function(selectedDates, dateStr, instance) {
                    setTimeout(() => {
                        let newYearBE = instance.currentYear + 543; // คำนวณปี พ.ศ.
                        instance.currentYearElement.value = newYearBE; // อัปเดตค่า UI
                        $(".cur-year").text(newYearBE);
                    }, 10);
                },
                onOpen: function(selectedDates, dateStr, instance) {
                    setTimeout(() => {
                        let currentYearBE = instance.currentYear + 543;
                        $(".numInput").val(currentYearBE); // แก้ปัญหาปีเด้งกลับเป็น ค.ศ.
                    }, 10);
                },
                onReady: function(selectedDates, dateStr, instance) {
                    setTimeout(() => {
                        let yearBE = instance.currentYear + 543;
                        instance.currentYearElement.value = yearBE; // ทำให้ปีแสดงเป็น พ.ศ.
                    }, 10);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        let selectedDate = selectedDates[0];
                        let yearAD = selectedDate.getFullYear();
                        let yearBE = yearAD + 543;
                        let monthIndex = selectedDate.getMonth();
                        let monthName = instance.l10n.months.longhand[monthIndex];

                        let displayDate = `${monthName} ${yearBE}`;
                        let formattedDate = `${yearAD}-${("0" + (monthIndex + 1)).slice(-2)}`;

                        console.log("📅 แสดงผลใน Input:", displayDate);
                        console.log("📤 ค่าที่ส่งไป Controller:", formattedDate);

                        $("#monthFilter").val(displayDate);
                        $("#monthFilterHidden").val(formattedDate); // ใส่ค่าใน hidden input
                        $("#monthFilter").attr("data-value", formattedDate); // ใช้ attr() แทน data()
                    }
                }

            });

            // ตั้งค่าเริ่มต้นสำหรับตัวกรอง - เพิ่มส่วนนี้หลัง flatpickr
            let displayDate = `${currentMonthName} ${currentYearBE}`;
            let formattedDate = `${currentYear}-${("0" + (currentMonth + 1)).slice(-2)}`;

            // ตั้งค่าเริ่มต้นเดือนปีปัจจุบัน
            $("#monthFilter").val(displayDate);
            $("#monthFilterHidden").val(formattedDate);
            $("#monthFilter").attr("data-value", formattedDate);


            // เมื่อกดปุ่ม submit
            $("#filterForm").on("submit", function(e) {
                e.preventDefault(); // ป้องกัน reload หน้า

                let filterValue = $("#monthFilterHidden").val(); // ดึงค่าจาก hidden input
                console.log("📤 ส่งค่าฟิลเตอร์:", filterValue);

                if (filterValue) {
                    this.submit(); // ส่งฟอร์มถ้ามีค่าถูกต้อง
                } else {
                    alert("กรุณาเลือกเดือนก่อนกดค้นหา");
                }
            });



            // END--Flatpickr

            // ฟังก์ชันดึงข้อมูลเมื่อกดปุ่มค้นหา
            function fetchFilteredData() {
                let search = $('#search').val().trim();
                let month = $('#monthFilterHidden').val(); // ดึงค่าจาก hidden input
                let leaveType = $('#leaveTypeFilter').val();

                $.ajax({
                    url: "{{ route('history.leave') }}",
                    method: "GET",
                    data: {
                        search: search,
                        month: month,
                        leaveType: leaveType
                    },
                    success: function(response) {
                        let rows = '';
                        $.each(response.employees, function(index, employee) {
                            rows += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${employee.employee_code}</td>
                        <td>${employee.pre_name}${employee.full_name}</td>
                        <td>${employee.position}</td>
                        <td>${employee.department}</td>
                        <td>${employee.personal_leave ?? 0}</td>
                        <td>${employee.vacation_leave ?? 0}</td>
                        <td>${employee.sick_leave ?? 0}</td>
                        <td>${employee.absent_days ?? 0}</td>
                        <td>${employee.late_days ?? 0}</td>
                        <td>${employee.leave_total ?? 0}</td>
                    </tr>
                `;
                        });

                        $('#employee-table').html(rows);
                    }
                });
            }

            // โหลดข้อมูลเดือนปีปัจจุบันโดยอัตโนมัติเมื่อเปิดหน้า
            fetchFilteredData();


            // กดปุ่มค้นหา
            $('#searchButton').on('click', function() {
                fetchFilteredData();
            });

            // ปุ่มล้างค่า - แก้ไขให้กลับไปเป็นค่าเดือนปีปัจจุบัน
            $('#resetButton').on('click', function() {
                $('#search').val('');
                // แก้ไขเป็นเดือนปีปัจจุบันแทนที่จะเป็นค่าว่าง
                $("#monthFilter").val(displayDate);
                $("#monthFilterHidden").val(formattedDate);
                $("#monthFilter").attr("data-value", formattedDate);
                $('#leaveTypeFilter').val('');
                fetchFilteredData(); // โหลดข้อมูลใหม่
            });

        });
    </script>
@endsection

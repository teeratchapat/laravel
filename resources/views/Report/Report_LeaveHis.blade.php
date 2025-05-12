@extends('layouts.layout')

@section('title', 'รายงานการลา')

@section('content')
    <div class="container-fluid mt-4 custom-wide-box">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center mb-4">รายงานการลาพนักงาน</h2>

                <form method="GET" id="leaveReportForm"
                    class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                    <div class="d-flex flex-column flex-sm-row mb-2">
                        <label class="me-2" for="monthFilter">เลือกเดือนและปี:</label>
                        <input type="text" id="monthFilter" name="month_year" class="form-control me-2 w-100 w-sm-auto"
                            placeholder="📅 เลือก เดือน ปี" value="{{ request('month_year', '') }}">
                        <input type="hidden" id="monthFilterHidden" name="month_year_hidden"
                            value="{{ request('month_year_hidden', date('Y-m')) }}">
                    </div>

                    <div class="d-flex flex-column flex-sm-row mb-2">
                        <label class="me-2" for="dep_category">เลือกแผนก:</label>
                        <select name="dep_category" class="form-select me-2 w-100 w-sm-auto">
                            <option value="">ทั้งหมด</option>
                            @foreach ($depCategories as $depCategory)
                                <option value="{{ $depCategory->dep_cate_id }}"
                                    {{ request('dep_category') == $depCategory->dep_cate_id ? 'selected' : '' }}>
                                    {{ $depCategory->dep_cate_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex flex-column flex-sm-row mb-2">
                        <label class="me-2" for="check_employee">เงื่อนไข:</label>
                        <select name="check_employee" class="form-select me-2 w-100 w-sm-auto">
                            <option value="">👥: ขาด ลา มาสายปกติ</option>
                            <option value="absent" {{ request('check_employee') == 'absent' ? 'selected' : '' }}>
                                😕: พนักงาน ขาดเกิน 3 ครั้ง
                            </option>
                            <option value="late" {{ request('check_employee') == 'late' ? 'selected' : '' }}>
                                😶‍🌫️: พนักงาน สายเกิน 3 ครั้ง
                            </option>
                        </select>
                    </div>

                    <div class="d-flex flex-column flex-sm-row">
                        <button type="submit" id="searchButton" class="btn btn-outline-primary me-2 mb-2 mb-sm-0">🔍
                            ค้นหา</button>
                        <button type="button" class="btn btn-outline-dark me-2 mb-2 mb-sm-0" id="resetBtn">🔄
                            ล้างค่า</button>
                        <a href="{{ route('report.export', ['month_year_hidden' => request('month_year_hidden'), 'dep_category' => request('dep_category'), 'check_employee' => request('check_employee')]) }}"
                            class="btn btn-success me-2 mb-2 mb-sm-0">📥 Export to Excel</a>
                        <button class="btn btn-info mb-2 mb-sm-0" onclick="window.print()">📑 พิมพ์รายงาน</button>
                    </div>
                </form>


                <!-- Table Report -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>รหัสพนักงาน</th>
                                <th>ชื่อพนักงาน</th>
                                <th>แผนก</th>
                                <th>ลากิจ</th>
                                <th>พักร้อน</th>
                                <th>ป่วย</th>
                                <th>ขาด</th>
                                <th>สาย</th>
                                <th>รวม</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $categoryNames = [
                                    'A' => 'ผู้จัดการ',
                                    'B' => 'เลขานุการ',
                                    'C' => 'หัวหน้าหน่วย',
                                    'D' => 'หัวหน้ากลุ่ม',
                                    'E' => 'พนักงาน/ช่างทั่วไป',
                                    'F' => 'เจ้าหน้าที่',
                                    'G' => 'อื่น ๆ',
                                ];
                            @endphp

                            @foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G'] as $categoryCode)
                                @php
                                    $categoryData = $reportData->filter(fn($row) => $row->category === $categoryCode);
                                @endphp

                                @if ($categoryData->isNotEmpty())
                                    <tr class="table-secondary">
                                        <td colspan="9"><strong>{{ $categoryNames[$categoryCode] }}</strong></td>
                                    </tr>

                                    @php
                                        // Group by department within this category
                                        $departmentGroups = $categoryData->groupBy('dep_cate_name');
                                    @endphp

                                    @foreach ($departmentGroups as $departmentName => $employees)
                                        <tr class="table-light">
                                            <td colspan="9">
                                                <strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $departmentName }}</strong>
                                            </td>
                                        </tr>

                                        @foreach ($employees as $row)
                                            <tr class="text-center">
                                                <td>{{ $row->employee_code }}</td>
                                                <td class="text-start">{{ $row->name }}</td>
                                                <td>{{ $row->dep_cate_name }}</td>
                                                <td>{{ $row->personal_leave }}</td>
                                                <td>{{ $row->vacation_leave }}</td>
                                                <td>{{ $row->sick_leave }}</td>
                                                <td>{{ $row->absent_days }}</td>
                                                <td>{{ $row->late_days }}</td>
                                                <td><strong>{{ $row->leave_total }}</strong></td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ดึงค่าเดือน-ปีปัจจุบัน
            let currentDate = new Date();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth();

            // ดึงค่าที่เลือกจาก hidden input (มาจาก request)
            let selectedValue = $("#monthFilterHidden").val() ||
                `${currentYear}-${("0" + (currentMonth + 1)).slice(-2)}`;
            let selectedYear = parseInt(selectedValue.split("-")[0]);
            let selectedMonth = parseInt(selectedValue.split("-")[1]) - 1;
            let selectedDate = new Date(selectedYear, selectedMonth, 1);

            // แปลงเป็นรูปแบบภาษาไทย
            let selectedMonthName = new Intl.DateTimeFormat('th-TH', {
                month: 'long'
            }).format(selectedDate);
            let selectedYearBE = selectedYear + 543;

            // ตั้งค่า Flatpickr สำหรับเลือกเดือน
            flatpickr("#monthFilter", {
                locale: "th",
                dateFormat: "F Y", // แสดงเป็นชื่อเดือน + ปี
                defaultDate: selectedDate, // ใช้วันที่จาก request หรือปัจจุบันถ้าไม่มี
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

            // ตั้งค่าเริ่มต้นจากค่าที่มีอยู่ใน hidden input
            let displayDate = `${selectedMonthName} ${selectedYearBE}`;
            $("#monthFilter").val(displayDate);
            $("#monthFilter").attr("data-value", selectedValue);

            // เงื่อนไขการ submit อัตโนมัติ - เฉพาะเมื่อไม่มี query parameters เท่านั้น
            if (window.location.search === "") {
                setTimeout(function() {
                    $("#leaveReportForm").submit();
                }, 100);
            }

            // ปุ่มรีเซ็ตค่า
            $("#resetBtn").on("click", function() {
                let currentDate = new Date();
                let currentYear = currentDate.getFullYear();
                let currentMonth = currentDate.getMonth() + 1;
                let formattedDate =
                    `${currentYear}-${currentMonth < 10 ? '0' + currentMonth : currentMonth}`;

                $("#monthFilterHidden").val(formattedDate);

                // ตั้งค่าเดือนปีเป็นปัจจุบันแทนการเคลียร์ค่า
                let thaiMonthName = new Intl.DateTimeFormat('th-TH', {
                    month: 'long'
                }).format(currentDate);
                $("#monthFilter").val(`${thaiMonthName} ${currentYear + 543}`);

                // ล้างค่าตัวกรองอื่นๆ
                $("select[name='dep_category']").val("");
                $("select[name='check_employee']").val("");

                // ส่งฟอร์ม
                $("#leaveReportForm").submit();
            });

            // เพิ่มการตรวจสอบเมื่อ submit ฟอร์ม
            $("#leaveReportForm").on("submit", function() {
                // ตรวจสอบว่ามีค่า data-value หรือไม่
                if ($("#monthFilter").attr("data-value")) {
                    $("#monthFilterHidden").val($("#monthFilter").attr("data-value"));
                }
            });
        });
    </script>
@endsection

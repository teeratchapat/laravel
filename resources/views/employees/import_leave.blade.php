@extends('layouts.layout')

@section('content')
    <div class="container-fluid">
        <br>
        <h3 class="text-center">ข้อมูลพนักงานที่นำเข้า</h3>

        {{-- ฟอร์มอัปโหลดไฟล์ Excel --}}
        <form id="uploadForm" action="{{ route('import.excel.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">เลือกไฟล์ Excel</label>
                <input type="file" class="form-control" name="file" id="file" required>
            </div>
        </form>

        <form id="submitForm" action="{{ route('import.excel.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="import_month_year" class="form-label">เลือกเดือนและปี</label>
                <input type="text" id="monthFilter" class="form-control" placeholder="📅 เลือก เดือน ปี" />
                <input type="hidden" id="monthFilterHidden" name="import_month_year" />
            </div>
        </form>

        {{-- ส่วนแสดงแผนกที่ผู้ใช้สามารถเลือกได้ --}}
        <div class="mb-3">
            <label for="dep_cate_id" class="form-label">เลือกแผนก:</label>
            <select name="dep_cate_id" id="dep_cate_id" class="form-control" required>
                <option value="">เลือกแผนก</option>
                @foreach ($depCates as $depCate)
                    <option value="{{ $depCate->dep_cate_id }}"
                        {{ old('dep_cate_id') == $depCate->dep_cate_id ? 'selected' : '' }}>
                        {{ $depCate->dep_cate_name }}
                    </option>
                @endforeach
            </select>
            {{-- <input type="hidden" id="dep_cate_id_hidden" name="dep_cate_id"> --}}
        </div>

        {{-- <--แสดงตัวเลือก Sheet หากมีการอัปโหลดไฟล์แล้ว --> --}}
        <div id="sheetSelection" style="display: none;">
            <div class="form-group mt-3">
                <label for="sheet_name">เลือก Sheet:</label>
                <select name="sheet_name" id="sheet_name" class="form-control"></select>
            </div>
            <button type="button" onclick="importSheet()" class="btn btn-primary mt-3">นำเข้าข้อมูลจาก Sheet</button>
        </div>

        {{-- ส่วนแสดงข้อความแจ้งเตือน --}}
        <div id="alertMessages" class="mt-3"></div>

        {{-- แสดงข้อมูลที่นำเข้า --}}
        @if (session('imported_data'))
            <div class="table-responsive mt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-primary">
                            <th>#</th>
                            <th>รหัส</th>
                            <th>คำนำหน้า</th>
                            <th>ชื่อ-สกุล</th>
                            <th>หมวด(ตำแหน่ง)</th>
                            <th>ตำแหน่ง</th>
                            {{-- <th>หมวด(แผนก)</th> --}}
                            <th>แผนก</th>
                            <th>ส่วนงาน</th>
                            <th>ว/ด/ป ที่เข้าทำงาน</th>
                            <th>ลากิจ</th>
                            <th>ลาพักร้อน</th>
                            <th>ป่วย</th>
                            <th>ขาด</th>
                            <th>สาย</th>
                            <th>รวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (session('imported_data') as $index => $data)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data['employee_code'] }}</td>
                                <td>{{ $data['pre_name'] }}</td>
                                <td>{{ $data['full_name'] }}</td>
                                <td>{{ $data['category'] }}</td>
                                <td>{{ $data['position'] }}</td>
                                <td>{{ $data['department'] }}</td>
                                {{-- <td>{{ $data['Dep_Category'] }}</td> --}}
                                <td>{{ $data['division'] }}</td>
                                <td>{{ $data['start_date'] }}</td>
                                <td>{{ $data['personal_leave'] }}</td>
                                <td>{{ $data['vacation_leave'] }}</td>
                                <td>{{ $data['sick_leave'] }}</td>
                                <td>{{ $data['absent_days'] }}</td>
                                <td>{{ $data['late_days'] }}</td>
                                <td>{{ $data['leave_total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- เปลี่ยนฟอร์มในส่วนการบันทึก -->
            <form id="submitForm" action="{{ route('import.excel.submit') }}" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="import_month_year" id="import_month_year_input"
                    value="{{ old('import_month_year', session('import_month_year')) }}" />
                <input type="hidden" id="dep_cate_id_hidden" name="dep_cate_id" value="{{ old('dep_cate_id') }}" />

                <button type="submit" class="btn btn-success" id="submitBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                        id="loadingSpinner"></span>
                    <span id="buttonText">บันทึกลงฐานข้อมูล</span>
                </button>
            </form>

            {{-- ปุ่มล้างข้อมูล --}}
            <form action="{{ route('import.excel.clear') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger">ล้างข้อมูล</button>
            </form>
        @else
            <p>ยังไม่มีข้อมูลที่ถูกอัปโหลด</p>
        @endif
    </div>

    {{-- Loading Spinner --}}
    <div id="loadingSpinner" style="display: none;">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log("หน้าโหลดแล้ว - กำลังดึงค่าจาก localStorage");

            // ดึงค่าจาก localStorage ทันทีเมื่อหน้าโหลด
            const preservedMonthFilter = localStorage.getItem('preservedMonthFilter');
            const preservedMonthFilterHidden = localStorage.getItem('preservedMonthFilterHidden');
            const preservedDepCateId = localStorage.getItem('preservedDepCateId');

            console.log("ค่าที่ดึงได้:", {
                monthFilter: preservedMonthFilter,
                monthFilterHidden: preservedMonthFilterHidden,
                depCateId: preservedDepCateId
            });

            // // ถ้ามีค่าบันทึกไว้ ให้นำมาใส่กลับที่ input
            // if (preservedMonthFilter) {
            //     $('#monthFilter').val(preservedMonthFilter);
            // }

            // if (preservedMonthFilterHidden) {
            //     $('#monthFilterHidden').val(preservedMonthFilterHidden);
            //     $('#import_month_year_input').val(preservedMonthFilterHidden);
            // }

            // ถ้ามีค่าแผนกที่บันทึกไว้
            if (preservedDepCateId) {
                $('#dep_cate_id').val(preservedDepCateId);
                $('#dep_cate_id_hidden').val(preservedDepCateId);
            }

            // เมื่อเลือกแผนก
            $('#dep_cate_id').change(function() {
                var selectedDepCateId = $(this).val(); // รับค่าจาก select
                $('#dep_cate_id_hidden').val(selectedDepCateId); // ตั้งค่าให้กับ hidden input

                // บันทึกค่าแผนกลง localStorage ทันที
                localStorage.setItem('preservedDepCateId', selectedDepCateId);
                console.log("บันทึกค่าแผนก:", selectedDepCateId);
            });

            // ตั้งค่า Flatpickr สำหรับเลือกเดือน
            let flatpickrInstance = flatpickr("#monthFilter", {
                locale: "th",
                dateFormat: "F Y", // แสดงเป็นชื่อเดือน + ปี
                // defaultDate: new Date(),
                defaultDate: preservedMonthFilterHidden ? new Date(preservedMonthFilterHidden) : new Date(),
                plugins: [
                    new monthSelectPlugin({
                        shorthand: false,
                        // dateFormat: "Y-m",
                        dateFormat: "F Y",
                        theme: "light"
                    })
                ],
                formatDate: function(date, format) {
                    let yearBE = date.getFullYear() + 543; // แปลงเป็น พ.ศ.
                    let monthIndex = date.getMonth(); //+
                    let monthName = flatpickr.l10ns.th.months.longhand[monthIndex]; //+
                    return monthName + " " + yearBE; //+
                    // return flatpickr.formatDate(date, format).replace(date.getFullYear(), yearBE);
                },
                parseDate: function(datestr, format) {
                    if (!datestr) return new Date();

                    let parts = datestr.split(" ");
                    if (parts.length < 2) return new Date(); // ป้องกันกรณีข้อมูลไม่ครบ

                    let month = parts[0]; // ชื่อเดือน
                    let yearBE = parseInt(parts[1]); // ปี พ.ศ.
                    let yearAD = yearBE - 543; // แปลงกลับเป็น ค.ศ.

                    // หา index ของชื่อเดือนภาษาไทย
                    let monthIndex = flatpickr.l10ns.th.months.longhand.indexOf(month);
                    if (monthIndex === -1) monthIndex = 0; // ถ้าหาไม่เจอให้ใช้เดือนแรก

                    return new Date(yearAD, monthIndex, 1);

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

                        // รูปแบบสำหรับแสดงผล (เช่น "เมษายน 2568")
                        let displayDate = `${monthName} ${yearBE}`;

                        // รูปแบบสำหรับส่งไปที่ backend (เช่น "2025-04-01")
                        let formattedDate = `${yearAD}-${("0" + (monthIndex + 1)).slice(-2)}-01`;

                        console.log("📅 แสดงผลใน Input:", displayDate);
                        console.log("📤 ค่าที่ส่งไป Controller:", formattedDate);

                        $("#monthFilter").val(displayDate);
                        $("#monthFilterHidden").val(formattedDate); // ใส่ค่าใน hidden input
                        $("#import_month_year_input").val(
                            formattedDate); // ใส่ค่าใน hidden input ของฟอร์ม submit

                        // บันทึกค่าวันที่ลง localStorage ทันที
                        localStorage.setItem('preservedMonthFilter', displayDate);
                        localStorage.setItem('preservedMonthFilterHidden', formattedDate);
                        console.log("บันทึกค่าวันที่:", {
                            display: displayDate,
                            value: formattedDate
                        });
                    }
                }
            });

            // ถ้ามีค่าวันที่บันทึกไว้ ให้ตั้งค่าให้กับ inputs หลังจากสร้าง flatpickr แล้ว
            if (preservedMonthFilter && preservedMonthFilterHidden) {
                $('#monthFilter').val(preservedMonthFilter);
                $('#monthFilterHidden').val(preservedMonthFilterHidden);
                $('#import_month_year_input').val(preservedMonthFilterHidden);
            }

            // เมื่อเลือกไฟล์ Excel
            $('#file').change(function() {
                var formData = new FormData($('#uploadForm')[0]);
                $('#loadingSpinner').show(); // แสดง loading spinner

                $.ajax({
                    url: "{{ route('import.excel.upload') }}", // ส่งไฟล์ไปที่เซิร์ฟเวอร์
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#loadingSpinner').hide(); // ซ่อน loading spinner

                        // ตรวจสอบว่ามีชื่อ Sheet ที่ได้จากเซิร์ฟเวอร์หรือไม่
                        if (response.sheet_names && response.sheet_names.length > 0) {
                            $('#sheet_name').empty(); // ล้างตัวเลือก sheet เก่า
                            $.each(response.sheet_names, function(index, sheetName) {
                                $('#sheet_name').append('<option value="' + sheetName +
                                    '">' + sheetName + '</option>');
                            });

                            // แสดง form สำหรับเลือก Sheet
                            $('#sheetSelection').show();
                        } else {
                            showAlert("ไม่พบข้อมูลในไฟล์ Excel", 'warning');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loadingSpinner').hide(); // ซ่อน loading spinner
                        console.error('เกิดข้อผิดพลาดในการอัปโหลดไฟล์:', error);
                        console.log(xhr.responseText);
                        showAlert("เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " + xhr.responseText,
                            'error');
                    }
                });
            });
        });

        // ฟังก์ชัน importSheet ที่แก้ไขแล้ว
        function importSheet() {
            var sheetName = $('#sheet_name').val();

            // เก็บค่าที่แสดงผล (เช่น "มีนาคม 2568")
            var selectedMonthFilter = $('#monthFilter').val();

            // เก็บค่าที่ใช้ส่งไป server (เช่น "2025-03-01")
            var selectedMonthFilterHidden = $('#monthFilterHidden').val();

            // เก็บค่า dep_cate_id
            var selectedDepCateId = $('#dep_cate_id').val();

            // ตรวจสอบว่าได้เลือกค่าที่จำเป็นครบแล้วหรือไม่
            if (!selectedMonthFilterHidden) {
                showAlert('กรุณาเลือกเดือนและปีก่อนนำเข้าข้อมูล', 'warning');
                return;
            }

            if (!selectedDepCateId) {
                showAlert('กรุณาเลือกแผนกก่อนนำเข้าข้อมูล', 'warning');
                return;
            }

            if (!sheetName) {
                showAlert('กรุณาเลือก Sheet ก่อนนำเข้าข้อมูล', 'warning');
                return;
            }

            // บันทึกค่าทั้งหมดลง localStorage
            localStorage.setItem('preservedMonthFilter', selectedMonthFilter);
            localStorage.setItem('preservedMonthFilterHidden', selectedMonthFilterHidden);
            localStorage.setItem('preservedDepCateId', selectedDepCateId);

            console.log("บันทึกค่าก่อนนำเข้า Sheet:", {
                monthFilter: selectedMonthFilter,
                monthFilterHidden: selectedMonthFilterHidden,
                depCateId: selectedDepCateId
            });

            $.ajax({
                url: "{{ route('import.excel.sheet') }}",
                type: 'POST',
                data: {
                    sheet_name: sheetName,
                    _token: "{{ csrf_token() }}",
                    dep_cate_id: selectedDepCateId,
                    import_month_year: selectedMonthFilterHidden // ส่งค่าเดือนปีไปด้วย
                },
                beforeSend: function() {
                    $('#sheetSelection button').prop('disabled', true);
                    showAlert('กำลังนำเข้าข้อมูล...', 'info');
                },
                success: function(response) {
                    if (response.status === 'success') {
                        showAlert(response.message, 'success');

                        // รอสักครู่แล้ว refresh หน้า
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        showAlert(response.message, 'error');
                        $('#sheetSelection button').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    showAlert('เกิดข้อผิดพลาด: ' + error, 'error');
                    $('#sheetSelection button').prop('disabled', false);
                    console.error(xhr.responseText);
                }
            });
        }

        // ฟังก์ชันแสดงข้อความแจ้งเตือน
        function showAlert(message, type) {
            const alertClass = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'info': 'alert-info',
                'warning': 'alert-warning'
            } [type] || 'alert-info';

            const html = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            $('#alertMessages').html(html);
        }

        // ฟังก์ชันสำหรับล้าง localStorage
        function clearLocalStorage() {
            localStorage.removeItem('preservedMonthFilter');
            localStorage.removeItem('preservedMonthFilterHidden');
            localStorage.removeItem('preservedDepCateId');
            console.log("ล้างค่าใน localStorage แล้ว");
        }

        // เพิ่ม event listener สำหรับฟอร์มบันทึกข้อมูล
        document.getElementById('submitForm').addEventListener('submit', function(e) {
            // ตรวจสอบค่าที่เลือกจาก select แผนก
            const depCateIdValue = document.getElementById('dep_cate_id').value;
            const monthFilterValue = document.getElementById('monthFilterHidden').value;

            // ตรวจสอบว่าได้เลือกค่าที่จำเป็นครบแล้วหรือไม่
            if (!depCateIdValue) {
                alert('กรุณาเลือกแผนกก่อน');
                e.preventDefault();
                return;
            }

            if (!monthFilterValue) {
                alert('กรุณาเลือกเดือนและปีก่อน');
                e.preventDefault();
                return;
            }

            // กำหนดค่าของ hidden inputs
            document.getElementById('dep_cate_id_hidden').value = depCateIdValue;
            document.getElementById('import_month_year_input').value = monthFilterValue;

            // แสดงการโหลด
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('loadingSpinner').classList.remove('d-none');
            document.getElementById('buttonText').textContent = 'กำลังบันทึก...';

            // ล้าง localStorage เมื่อกดบันทึก (เพราะข้อมูลจะถูกบันทึกลงฐานข้อมูลแล้ว)
            clearLocalStorage();
        });
    </script>
@endsection

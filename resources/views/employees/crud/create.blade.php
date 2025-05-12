@extends('layouts.layout')

@section('title', 'เพิ่มพนักงาน')

@section('content')
    <div class="container">
        <h2 class="mb-4">เพิ่มพนักงาน</h2>

        <a href="{{ route('employees.index') }}" class="btn btn-secondary mb-3">← ย้อนกลับ</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>เกิดข้อผิดพลาด:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employees.store') }}" autocomplete="off">
            @csrf

            <!-- Employee Code -->
            <div class="mb-3">
                <label for="employee_code" class="form-label">รหัสพนักงาน</label>
                <input type="text" class="form-control" name="employee_code" id="employee_code"
                    value="{{ old('employee_code') }}" required>
            </div>

            <!-- Pre Name -->
            <div class="mb-3">
                <label for="pre_name" class="form-label">คำนำหน้า</label>
                <select class="form-select" name="pre_name" id="pre_name">
                    <option value="" selected>เลือกคำนำหน้า</option>
                    <option value="นาย" {{ old('pre_name') == 'นาย' ? 'selected' : '' }}>นาย</option>
                    <option value="นาง" {{ old('pre_name') == 'นาง' ? 'selected' : '' }}>นาง</option>
                    <option value="นางสาว" {{ old('pre_name') == 'นางสาว' ? 'selected' : '' }}>นางสาว</option>
                </select>
            </div>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="full_name" class="form-label">ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" name="full_name" id="full_name" value="{{ old('full_name') }}"
                    required>
            </div>

            <!-- Position -->
            <div class="mb-3">
                <label for="position" class="form-label">ตำแหน่ง</label>
                <input type="text" class="form-control" name="position" id="position" value="{{ old('position') }}"
                    required>
            </div>

            <!-- Department -->
            <div class="mb-3">
                <label for="department" class="form-label">แผนก</label>
                <select class="form-select" name="department" id="department" required>
                    <option value="" selected>เลือกแผนก</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->dep_cate_id }}"
                            {{ old('department') == $department->dep_cate_id ? 'selected' : '' }}>
                            {{ $department->dep_cate_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Division -->
            <div class="mb-3">
                <label for="division" class="form-label">แผนกย่อย</label>
                <input type="text" class="form-control" name="division" id="division" value="{{ old('division') }}">
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="category" class="form-label">หมวดหมู่</label>
                <input type="text" class="form-control" name="category" id="category" value="{{ old('category') }}">
            </div>

            <!-- Dep_Category -->
            <div class="mb-3">
                <label for="Dep_Category" class="form-label">หมวดหมู่แผนก</label>
                <input type="text" class="form-control" name="Dep_Category" id="Dep_Category"
                    value="{{ old('Dep_Category') }}">
            </div>

            <!-- Start Date -->
            <div class="mb-3">
                <label for="display_start_date" class="form-label">วันเริ่มงาน (พ.ศ.)</label>
                <input type="text" id="display_start_date" class="form-control" placeholder="เลือกวันที่" required>
                <input type="hidden" name="start_date" id="start_date" value="{{ old('start_date') }}">
            </div>

            <button type="submit" class="btn btn-primary">💾 บันทึก</button>
        </form>
    </div>

    <script>
        flatpickr("#display_start_date", {
            dateFormat: "d-m-Y",
            altInput: false,
            locale: {
                firstDayOfWeek: 1,
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    let date = selectedDates[0];
                    let buddhistYear = date.getFullYear() + 543;
                    let month = (date.getMonth() + 1).toString().padStart(2, '0');
                    let day = date.getDate().toString().padStart(2, '0');

                    instance.input.value = `${day}-${month}-${buddhistYear}`;
                    document.getElementById('start_date').value = date.toISOString().split('T')[0];
                }
            }
        });
    </script>
@endsection

@extends('layouts.layout')

@section('title', 'การจัดการพนักงาน')

@section('content')
    <div class="container">
        <h2>รายการพนักงาน</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('employees.create') }}" class="btn btn-success mb-3">+ เพิ่มพนักงาน</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>รหัสพนักงาน</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>ตำแหน่ง</th>
                    <th>แผนก</th>
                    <th>ฝ่าย</th>
                    <th>ประเภท</th>
                    <th>หมวดแผนก</th>
                    <th>วันเริ่มงาน</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->employee_code }}</td>
                        <td>{{ $employee->pre_name }}{{ $employee->full_name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->department }}</td>
                        <td>{{ $employee->division }}</td>
                        <td>{{ $employee->category }}</td>
                        <td>{{ $employee->Dep_Category }}</td>
                        <td>{{ \Carbon\Carbon::parse($employee->start_date)->addYears(543)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('employees.edit', $employee->employee_code) }}"
                                class="btn btn-warning btn-sm">แก้ไข</a>
                            <form action="{{ route('employees.destroy', $employee->employee_code) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('ยืนยันการลบ?')">ลบ</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

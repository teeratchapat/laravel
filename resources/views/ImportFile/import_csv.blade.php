@extends('layouts.layout')

@section('title', 'import CSV')

@section('content')
    <div class="container mt-4">
        <h2>นำเข้าข้อมูลจาก CSV</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- <form action="{{ route('import.csv.form') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">เลือกไฟล์ CSV</label>
                <input type="file" name="csv_file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">อัปโหลด</button>
        </form> --}}

        <form action="{{ route('import.csv.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="csv_file">อัปโหลดไฟล์ CSV:</label>
            <input type="file" name="csv_file" required>
            <button type="submit">อัปโหลด</button>
        </form>

        @if (session('csvData'))
            @php $csvData = session('csvData'); @endphp
            <h3 class="mt-4">ตัวอย่างข้อมูล</h3>
            <form action="{{ route('import.csv.submit') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>รหัสพนักงาน</th>
                            <th>คำนำหน้า</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>ตำแหน่ง</th>
                            <th>แผนก</th>
                            <th>หมวดหมู่</th>
                            <th>วันที่เริ่มงาน</th>
                            <th>ลากิจ</th>
                            <th>ลาพักร้อน</th>
                            <th>ลาป่วย</th>
                            <th>ขาดงาน</th>
                            <th>มาสาย</th>
                            <th>รวมวันลา</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($csvData as $row)
                            <tr>
                                @foreach ($row as $cell)
                                    <td>{{ $cell }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
            </form>
        @endif

    </div>
@endsection

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }

        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            /* เพิ่มส่วนนี้สำหรับตัวหนา */
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 16pt;
        }

        h1,
        h2,
        h3,
        b,
        strong {
            font-family: 'THSarabunNew';
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .logo {
            width: 200px;
            display: block;
            margin: 0 auto;
        }

        .page-break {
            page-break-after: always;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            font-size: 14pt;
        }

        .text-indent {
            text-indent: 50px;
        }

        .signature {
            margin-left: 60%;
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="center">
        <img src="{{ public_path('images/ku-symbol.png') }}" class="logo">
        <h2 style="margin-top: 20px;">ประกาศมหาวิทยาลัยเกษตรศาสตร์</h2>
        <p>เรื่อง นิสิตที่มีความประพฤติดีเด่น นิสิตที่มีผลงานดีเด่นด้านกิจกรรมเสริมหลักสูตร<br>
            และนิสิตที่มีผลงานดีเด่นด้านความคิดสร้างสรรค์และนวัตกรรม ประจำภาค
            {{ $firstApp->event->semester == 1 ? 'ต้น' : 'ปลาย' }} ปีการศึกษา {{ $firstApp->event->academic_year }}</p>
    </div>

    <hr style="border: 0.5px dashed black; width: 20%; margin: 20px auto;">

    <div class="text-indent">
        ตามที่มหาวิทยาลัยเกษตรศาสตร์ ได้ตราระเบียบว่าด้วยการเสริมสร้างค่านิยมที่ดีของนิสิต พ.ศ. ๒๕๕๓
        เพื่อเสริมสร้างให้นิสิตได้มีค่านิยมต่อการประกอบความดีให้ยิ่ง ๆ ขึ้นไป นั้น สำหรับประจำภาค
        {{ $firstApp->event->semester == 1 ? 'ต้น' : 'ปลาย' }} ปีการศึกษา {{ $firstApp->event->academic_year }} นี้
        คณะอนุกรรมการพิจารณานิสิตดีเด่นและผู้แทนนิสิตเข้ารับรางวัลพระราชทาน
        ได้ดำเนินการคัดเลือกนิสิตที่มีคุณสมบัติตามที่ได้กำหนดไว้ในระเบียบดังกล่าว ตามรายชื่อแนบท้ายประกาศดังนี้
    </div>

    <div style="margin: 30px 0 0 80px;">
        นิสิตที่มีความประพฤติดีเด่น จำนวน {{ $stats['conduct'] }} คน<br>
        นิสิตที่มีผลงานดีเด่นด้านกิจกรรมเสริมหลักสูตร จำนวน {{ $stats['activity'] }} คน<br>
        นิสิตที่มีผลงานดีเด่นด้านความคิดสร้างสรรค์และนวัตกรรม จำนวน {{ $stats['innovation'] }} คน
    </div>

    <p class="text-indent" style="margin-top: 20px;">จึงประกาศมาเพื่อเป็นเกียรติสืบไป</p>

    <div class="signature">
        ประกาศ ณ วันที่ {{ $today->format('j') }} {{ $today->locale('th')->monthName }} พ.ศ.
        {{ $today->year + 543 }}<br><br><br>
        ({{ $signerName }})<br>
        อธิการบดีมหาวิทยาลัยเกษตรศาสตร์
    </div>

    @foreach ($groupedApps as $awardName => $apps)
        <div class="page-break"></div>
        <div class="center">
            <h3>รายชื่อนิสิตที่มีผลงานดีเด่นด้าน{{ $awardName }}</h3>
            <p>ภาค {{ $firstApp->event->semester == 1 ? 'ต้น' : 'ปลาย' }} ประจำปีการศึกษา
                {{ $firstApp->event->academic_year }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>รหัสนิสิต</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>คณะ</th>
                    <th>ภาควิชา</th>
                    <th>ปีการศึกษา</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($apps as $index => $app)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $app->user->student_id }}</td>
                        <td>{{ $app->user->firstName }} {{ $app->user->lastName }}</td>
                        <td>{{ $app->user->faculty->name }}</td>
                        <td>{{ $app->user->department->name }}</td>
                        <td>{{ $app->year }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>

</html>

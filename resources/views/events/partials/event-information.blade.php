<section>
    <form method="post" class="p-10 flex flex-col gap-y-12 bg-white shadow-sm rounded-lg">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 ">
            <div class="flex flex-col">
                <label for="name">ชื่อรอบการให้รางวัล</label>
                <input name="name" type="text" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm" >
            </div>
            <div class="flex flex-col">
                <label for="name">สถานะ</label>
                <select name="name" type="text" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm" >
                    <option value="{{ \App\Enums\Status::OPENED }}">เปิดรอบการให้รางวัล</option>
                    <option value="{{ \App\Enums\Status::CLOSED }}">ปิดรอบการให้รางวัล</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="academic-year">ปีการศึกษา</label>
                <select id="academic-year" name="academic-year" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm"></select>
                <script>
                    const AMOUNT_OF_YEAR = 5;
                    let currentYear = new Date().getFullYear() + 544;
                    for (let i = 0; i < AMOUNT_OF_YEAR; i++) {
                        let option = document.createElement('option');
                        option.value = currentYear--;
                        option.text = currentYear;
                        document.getElementById('academic-year').appendChild(option);
                    }
                </script>
            </div>
            <div class="flex flex-col">
                <label for="semester">ภาคเรียน</label>
                <select id="semester" name="semester" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="flex flex-col">
                <label for="start-date">วันที่เริ่มต้น</label>
                <input name="start-date" type="date" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm" >
            </div>
            <div class="flex flex-col">
                <label for="end-date">วันที่สิ้นสุด</label>
                <input name="end-date" type="date" class="border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm" >
            </div>
        </div>
        <div class="flex flex-row justify-end items-center">
            <button type="submit" class="px-10 py-1.5 flex-2 bg-primary font-semibold text-white text-[18px] rounded-md">ยืนยัน</button>
        </div>
    </form>
</section>

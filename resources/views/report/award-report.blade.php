<x-app-layout>
    <div class="p-10">
        <a class="flex gap-2 mb-10" href="{{ route('main.dashboard') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้าหลัก</p>
        </a>
        <div class="flex flex-col gap-7">
            <div class="flex-col flex gap-2">
                <p class="font-bold text-2xl">รายงานรางวัล</p>
                <p class=" text-gray-400">ดูสถิติและรายงานสรุปต่างๆ</p>
            </div>
            <div class="bg-white p-8 border rounded-xl flex flex-col gap-8">
                <p>สรุปสถิติรางวัล ภาคการศึกษา 2568/1</p>
                <div class="flex gap-8">
                    <div
                        class="flex justify-center items-center flex-col border rounded-xl p-5 flex-1 bg-red-50 border-red-500">
                        <p class="mb-1 text-red-500"><span class="font-bold text-4xl">50</span> รางวัล</p>
                        <p>ด้านกิจกรรมเสริมหลักสูตร</p>
                    </div>
                    <div
                        class="flex justify-center items-center flex-col border rounded-xl p-5 flex-1 bg-yellow-50 border-yellow-500">
                        <p class="mb-1 text-yellow-500"><span class="font-bold text-4xl ">35</span> รางวัล</p>
                        <p>ด้านความคิดสร้างสรรค์และนวัตกรรม</p>
                    </div>
                    <div
                        class="flex justify-center items-center flex-col border rounded-xl p-5 flex-1 bg-green-50 border-primary">
                        <p class="mb-1 text-primary"><span class="font-bold text-4xl ">40</span> รางวัล</p>
                        <p>ด้านความประพฤติดี</p>
                    </div>
                </div>
            </div>
            <div class="bg-white p-8 border rounded-xl flex flex-col gap-8">
                <div>
                    <p class="mb-3">เลือกปีการศึกษาและภาคการศึกษา</p>
                    <select name="" id=""
                        class="border border-gray-300  rounded-xl w-full cursor-pointer focus:outline-primary focus:border-gray-300 focus:ring-offset-0 focus:ring-0">
                        <option value="">2568/1</option>
                        <option value="">2568/2</option>
                        <option value="">2569/1</option>
                    </select>
                </div>
                <div class="rounded-xl border border-gray-300 overflow-hidden bg-white">
                    <table class="w-full">
                        <thead class="divide-y border-b bg-gray-100">
                            <tr class="divide-x">
                                <th class=" p-4 text-start">ชื่อนิสิต</th>
                                <th class="  p-4 text-start">ประเภทรางวัล</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr class="divide-x">
                                <td class=" p-4">Alfreds Futterkiste</td>
                                <td class=" p-4">Maria Anders</td>
                            </tr>
                            <tr class="divide-x">
                                <td class=" p-4">Francisco Chang</td>
                                <td class=" p-4">Mexico</td>
                            </tr>
                            <tr class="divide-x">
                                <td class=" p-4">Francisco Chang</td>
                                <td class=" p-4">Mexico</td>
                            </tr>
                            <tr class="divide-x">
                                <td class=" p-4">Francisco Chang</td>
                                <td class=" p-4">Mexico</td>
                            </tr>
                            <tr class="divide-x">
                                <td class=" p-4">Francisco Chang</td>
                                <td class=" p-4">Mexico</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end">
                    <div class="flex gap-4 items-center">
                        <x-icon name="arrow-head-left" class="stroke-gray-300"></x-icon>
                        <p class="border rounded-xl py-2 px-5">1</p>
                        <x-icon name="arrow-head-right"></x-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

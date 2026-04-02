<x-app-layout>
    <div class="p-10">
        <a class="flex gap-2 mb-10" href="{{ route('users.index') }}">
            <x-icon name="arrow-left"></x-icon>
            <p>กลับหน้ารายชื่อ</p>
        </a>
        <form
            action="{{route('users.store')}}"
            method="POST"
            class="flex items-center justify-center"
        >
            @csrf
            <div class="flex flex-col gap-5">
                <div class="mt-5 mb-3 text-3xl text-center">สร้างผู้ใช้ </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <select name="role" id="role"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    >
                        <option value="">เลือกตำแหน่ง</option>
                        @foreach($roles as $r)
                            @if($r->value != 'NISIT_DEV') @endif
                            <option value="{{$r->value}}">{{$r::label($r)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <div>
                        <label for="firstName" class="block text-sm font-medium text-gray-700">
                            ชื่อจริง
                        </label>
                        @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        <input type="text" name="firstName" id="first-name" placeholder="ชื่อจริง"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                        />
                    </div>
                    <div>
                        <label for="lastName" class="block text-sm font-medium text-gray-700">
                            นามสกุล
                        </label>
                        @error('lastName') <span class="inline- text-red-500 text-xs">{{ $message }}</span> @enderror
                        <input type="text" name="lastName" id="last-name" placeholder="นามสกุล"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                        />
                    </div>
                </div>
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้</label>
                    @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <input type="text" name="username" id="username" placeholder="ชื่อผู้ใช้"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        อีเมล
                    </label>
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <input type="email" name="email" id="email" placeholder="อีเมล"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <input type="password" name="password" id="password" placeholder="รหัสผ่าน"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                </div>
                <div id="student-id-field" class="hidden">
                    <label for="student_id" class="block text-sm font-medium text-gray-700">รหัสนิสิต</label>
                    @error('student_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <input type="text" name="student_id" id="student_id" placeholder="รหัสนิสิต"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    />
                </div>
                <div id="faculty-field">
                    <label for="faculty" class="block text-sm font-medium text-gray-700">คณะ</label>
                    @error('faculty') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <select name="faculty" id="faculty"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                    >
                        <option value="">เลือกคณะ</option>
                        @foreach($faculties as $f)
                            @if($f->campus === auth()->user()->campus)
                                <option value="{{$f->id}}">{{$f->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div id="department-field">
                    <label for="department" class="block text-sm font-medium text-gray-700">ภาควิชา</label>
                    @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    <select name="department" id="department"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"
                        disabled
                    >
                        <option value="">เลือกคณะก่อน</option>
                    </select>
                </div>
                <button
                    class="py-2 px-5 rounded text-center bg-green-500 hover:bg-green-600 cursor-pointer transition-all"
                    type="submit"
                >
                    ยืนยัน
                </button>
            </div>
        </form>
    </div>
</x-app-layout>

<script>
    const faculties = {!! json_encode($faculties->pluck('name', 'id')) !!};
    const departments = {!! json_encode($departments) !!};

    const roleSelect = document.getElementById('role');
    const studentIdField = document.getElementById('student-id-field');
    const facultyField = document.getElementById('faculty-field');
    const departmentField = document.getElementById('department-field');
    const facultySelect = document.getElementById('faculty');
    const departmentSelect = document.getElementById('department');

    // Handle role change
    roleSelect.addEventListener('change', function() {
        const role = this.value;

        // Reset fields
        studentIdField.classList.add('hidden');
        facultyField.classList.remove('hidden');
        departmentField.classList.remove('hidden');
        facultySelect.disabled = false;
        departmentSelect.disabled = true;

        // Role-based logic
        switch (role) {
            case 'NISIT': // นิสิต
                studentIdField.classList.remove('hidden');
                facultyField.classList.remove('hidden');
                departmentField.classList.remove('hidden');
                break;
            case 'DEPT_HEAD': // หัวหน้าภาค
                studentIdField.classList.add('hidden');
                facultyField.classList.remove('hidden');
                departmentField.classList.remove('hidden');
                break;
            case 'ASSO_DEAN': // รองคณบดี
            case 'DEAN': // คณบดี
                studentIdField.classList.add('hidden');
                facultyField.classList.remove('hidden');
                departmentField.classList.add('hidden');
                break;
            case 'BOARD': // คณะกรรมการ
            case 'NISIT_DEV': // กองพัฒนานิสิต
                studentIdField.classList.add('hidden');
                facultyField.classList.add('hidden');
                departmentField.classList.add('hidden');
                break;
        }
    });

    // Handle faculty change
    facultySelect.addEventListener('change', function() {
        const facultyId = parseInt(this.value);

        // Clear department options
        departmentSelect.innerHTML = '<option value="">เลือกภาควิชา</option>';

        if (facultyId) {
            // Filter departments by faculty_id
            const filteredDepartments = departments.filter(dept => dept.faculty_id === facultyId);

            if (filteredDepartments.length > 0) {
                departmentSelect.disabled = false;
                filteredDepartments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept.id;
                    option.textContent = dept.name;
                    departmentSelect.appendChild(option);
                });
            } else {
                departmentSelect.disabled = true;
                departmentSelect.innerHTML = '<option value="">ไม่มีภาควิชา</option>';
            }
        } else {
            departmentSelect.disabled = true;
            departmentSelect.innerHTML = '<option value="">เลือกคณะก่อน</option>';
        }
    });
</script>

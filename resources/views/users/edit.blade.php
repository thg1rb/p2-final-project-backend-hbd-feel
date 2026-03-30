<x-app-layout>
    <div class="p-10">
            <a class="flex gap-2 mb-10" href="{{ route('users.index') }}">
                <x-icon name="arrow-left"></x-icon>
                <p>กลับหน้ารายชื่อ</p>
            </a>
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

            <div class="h-32 bg-gradient-to-r from-green-600 to-emerald-600 relative"></div>

            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 pb-8 relative mt-3">

                    <div class="relative -mt-16 mb-8 flex items-end gap-6">
                        <div class="h-32 w-32 rounded-full border-4 border-white bg-white shadow-md flex items-center justify-center text-3xl font-bold text-gray-700 overflow-hidden">
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                {{ substr($user->firstName, 0, 1) }}{{ substr($user->lastName, 0, 1) }}
                            </div>
                        </div>
                        <div class="mb-4 hidden sm:block">
                            <h2 class="text-2xl font-bold text-gray-800">แก้ไขข้อมูลผู้ใช้งาน</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2 border-b pb-2">
                                ข้อมูลส่วนตัว
                            </h3>

                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700">ชื่อจริง</label>
                                <input type="text" name="firstName" id="firstName"
                                       value="{{ old('firstName', $user->firstName) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('firstName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700">นามสกุล</label>
                                <input type="text" name="lastName" id="lastName"
                                       value="{{ old('lastName', $user->lastName) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('lastName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">ตำแหน่ง</label>
                                <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">เลือกตำแหน่ง</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->value }}" {{ old('role', $user->role->value) == $r->value ? 'selected' : '' }}>
                                            {{ $r::label($r) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div id="student-id-field" class="{{ $user->role->value != 'NISIT' ? 'hidden' : '' }}">
                                <label for="student_id" class="block text-sm font-medium text-gray-700">รหัสนิสิต</label>
                                <input type="text" name="student_id" id="student_id"
                                       value="{{ old('student_id', $user->student_id) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('student_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2 border-b pb-2">
                                ข้อมูลบัญชี
                            </h3>

                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">ชื่อผู้ใช้</label>
                                <input type="text" name="username" id="username"
                                       value="{{ old('username', $user->username) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">อีเมล</label>
                                <input type="email" name="email" id="email"
                                       value="{{ old('email', $user->email) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div id="faculty-field" class="{{ in_array($user->role->value, ['BOARD', 'NISIT_DEV']) ? 'hidden' : '' }}">
                                <label for="faculty" class="block text-sm font-medium text-gray-700">คณะ</label>
                                <select name="faculty" id="faculty"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option value="">เลือกคณะ</option>
                                    @foreach($faculties as $f)
                                        @if($f->campus === auth()->user()->campus)
                                            <option value="{{$f->id}}" {{ old('faculty', $user->faculty_id) == $f->id ? 'selected' : '' }}>
                                                {{$f->name}}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('faculty') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div id="department-field" class="{{ in_array($user->role->value, ['DEAN', 'ASSO_DEAN', 'BOARD', 'NISIT_DEV']) ? 'hidden' : '' }}">
                                <label for="department" class="block text-sm font-medium text-gray-700">ภาควิชา</label>
                                <select name="department" id="department"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                >
                                    <option value="">เลือกภาควิชา</option>
                                    @foreach($departments as $d)
                                        <option value="{{$d->id}}" {{ old('department', $user->department_id) == $d->id ? 'selected' : '' }}>
                                            {{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="md:col-span-2 pt-4 border-t border-gray-100">
                            <h3 class="text-sm font-medium text-gray-500 mb-4">เปลี่ยนรหัสผ่าน (เว้นว่างไว้หากไม่ต้องการเปลี่ยน)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" name="password" id="password" autocomplete="new-password"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                    <span class="text-xs text-gray-400">* ตรวจสอบข้อมูลก่อนบันทึก</span>

                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            บันทึกการแก้ไข
                        </button>
                    </div>
                </div>
            </form>
            <div class="bg-red-50 px-6 py-4 border-t border-red-100 flex justify-end items-center">
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                        ลบบัญชีผู้ใช้
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    const faculties = {!! json_encode($faculties->pluck('name', 'id')) !!};
    const departments = {!! json_encode($departments) !!};
    const currentRole = "{{ $user->role->value }}";

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
            departmentSelect.innerHTML = '<option value="">เลือกภาควิชา</option>';
        }
    });

    // Initialize on page load based on current role
    document.addEventListener('DOMContentLoaded', function() {
        roleSelect.value = currentRole;
        roleSelect.dispatchEvent(new Event('change'));

        // Set current faculty if exists
        if ("{{ $user->faculty_id }}") {
            facultySelect.value = "{{ $user->faculty_id }}";
            facultySelect.dispatchEvent(new Event('change'));

            // Set current department if exists
            if ("{{ $user->department_id }}") {
                setTimeout(() => {
                    departmentSelect.value = "{{ $user->department_id }}";
                }, 100);
            }
        }
    });
</script>

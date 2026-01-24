@extends('award-registrations.create')

@section('content')
    @if(session('upload_success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200
                text-emerald-700 px-4 py-3 text-sm">
            {{ session('upload_success') }}
        </div>
    @endif
    <form method="POST"
          action="{{ route('award-registrations.store') }}?step=2"
          enctype="multipart/form-data">
        @csrf

        @includeIf(
            'award-registrations.steps.step2.' .
            session('award_registration.step1.award_type')
        )
        <div class="mt-10 flex justify-between">
            <button type="submit" name="action" value="back"
                    class="px-6 py-2 border rounded-lg">
               < ย้อนกลับ
            </button>
            <button type="submit" name="action" value="next"
                    class="px-6 py-2 bg-emerald-600 text-white rounded-lg">
                ถัดไป >
            </button>
        </div>
    </form>
@endsection

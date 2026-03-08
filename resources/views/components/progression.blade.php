@props(['application'])
@props(['approvals'])
@props(['event'])
@php
    $workflowOrder = ['DEPT_HEAD', 'ASSO_DEAN', 'DEAN', 'NISIT_DEV', 'BOARD', 'CHANCELLOR'];
    $roleNames = [
        'DEPT_HEAD' => 'หัวหน้าภาควิชา',
        'ASSO_DEAN' => 'รองคณบดี',
        'DEAN' => 'คณบดี',
        'NISIT_DEV' => 'หน่วยพัฒนานิสิต',
        'BOARD' => 'คณะกรรมการ',
        'CHANCELLOR' => 'อธิการบดี',
    ];
    $hasRejected = false;

    Log::info($approvals->toArray());

@endphp

<div class="flex flex-col gap-6 rounded-xl border border-gray-300 bg-white p-5 shadow-sm">
    <div class="flex gap-3 font-bold border-b pb-4">
        <x-icon name="user" class="text-blue-600" />
        <p>สถานะการดำเนินการ</p>
    </div>

    <div class="flex flex-col h-fit">
        @foreach ($workflowOrder as $index => $role)
            @php
                $approval = $approvals->first(fn($a) => $a->user->role->value === $role);
                $isLast = $loop->last;

                if ($hasRejected) {
                    $status = 'NOT_STARTED';
                } elseif ($approval) {
                    $isApproved = $approval->status->value === 'APPROVED';
                    $status = $isApproved ? 'APPROVED' : 'REJECT';
                    if (!$isApproved) {
                        $hasRejected = true;
                    }
                } else {
                    $status = $index === count($approvals) ? 'PENDING' : 'NOT_STARTED';
                }

                if ($event == null) {
                    $status = 'APPROVED';
                }

                $styles = match ($status) {
                    'APPROVED' => [
                        'border' => 'border-primary',
                        'bg' => 'bg-primary',
                        'icon' => 'check',
                        'text' => 'text-primary',
                    ],
                    'PENDING' => [
                        'border' => 'border-amber-500',
                        'bg' => 'bg-amber-500',
                        'icon' => 'loading',
                        'text' => 'text-amber-500',
                    ],
                    'REJECT' => [
                        'border' => 'border-red-400',
                        'bg' => 'bg-red-400',
                        'icon' => 'X',
                        'text' => 'text-red-400',
                    ],
                    default => [
                        'border' => 'border-gray-200',
                        'bg' => 'bg-gray-200',
                        'icon' => 'circle',
                        'text' => 'text-gray-400',
                    ],
                };
            @endphp

            <div class="flex h-fit gap-4">
                <div class="flex flex-col items-center">
                    <div class="flex items-center justify-center rounded-full border-2 {{ $styles['border'] }} p-1">
                        <div class="flex h-5 w-5 items-center justify-center">
                            <x-icon name="{{ $styles['icon'] }}" class="w-4 h-4 {{ $styles['text'] }}" />
                        </div>
                    </div>
                    @if (!$isLast)
                        <div class="h-full w-0.5 {{ $styles['bg'] }}"></div>
                    @endif
                </div>
                <div class="flex flex-col gap-1 pb-7">
                    <p class="font-medium {{ $styles['text'] }}">{{ $roleNames[$role] }}</p>
                    @if ($status !== 'NOT_STARTED' && $approval)
                        <p class="text-sm font-medium">{{ $approval->user->firstName }} {{ $approval->user->lastName }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $approval->created_at->translatedFormat('j M Y H:i') }}</p>
                        @if ($approval->reason)
                            <p class="text-xs text-gray-600 mt-1 italic">"{{ $approval->reason }}"</p>
                        @endif
                    @elseif ($event == null)
                        <p class="text-xs text-gray-600 mt-1 italic">
                            ดำเนินการลงนามใบสมัครนิสิตดีเด่นสำเร็จ
                        </p>
                    @else
                        <p class="text-sm text-gray-400 italic">ยังไม่มีการดำเนินการ</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

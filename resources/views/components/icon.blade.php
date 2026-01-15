@props(['name', 'size' => 24, 'class' => ''])

@php
    $icons = [
        'home' =>
            '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'badge' =>
            '<path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>',
    ];
@endphp

@if (isset($icons[$name]))
    <svg {{ $attributes->merge(['class' => $class]) }} xmlns="http://www.w3.org/2000/svg" width="{{ $size }}"
        height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round">
        {!! $icons[$name] !!}
    </svg>
@else
    <span class="text-red-500">Icon "{{ $name }}" not found</span>
@endif

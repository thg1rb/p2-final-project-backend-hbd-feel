@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
    $alignmentClasses = match ($align) {
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'top' => 'origin-top',
        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

    $widthClass = match ($width) {
        '48' => 'w-48',
        default => $width,
    };
@endphp

<div x-data="{
        open: false,
        position: { top: 0, left: 0 },
        calculatePosition() {
            let rect = this.$refs.button.getBoundingClientRect();
            this.position.top = rect.bottom + window.scrollY;
            this.position.left = (rect.right + window.scrollX);
        }
    }"
     @click.outside="open = false"
     @close.stop="open = false"
     class="relative">

    <div @click="open = !open; if(open) calculatePosition()" x-ref="button" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <template x-teleport="body">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="font-thai antialiased absolute z-[100] {{ $widthClass }} rounded-md shadow-lg"
             :style="`top: ${position.top}px; left: ${position.left}px; transform: translateX(-100%); display: none;`"
             @click="open = false">

            <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
                {{ $content }}
            </div>
        </div>
    </template>
</div>

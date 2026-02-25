@props(['label'])
<div class="flex flex-col gap-1">
    <label class="text-sm font-medium text-black dark:text-white">{{ $label }}</label>
    {{ $slot }}
</div>
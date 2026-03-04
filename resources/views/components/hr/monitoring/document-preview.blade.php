@props(['document', 'height' => '780px'])

<div class="w-full rounded-xl overflow-hidden border border-white/10 bg-black/20">

    {{-- Header bar --}}
    <div class="flex items-center justify-between px-4 py-2 border-b border-white/10 bg-white/5">
        <div class="flex items-center gap-2">
            <flux:icon.document-text class="w-4 h-4 text-zinc-400" />
            <flux:text size="sm" class="text-zinc-300 font-medium">
                {{ $document->user->full_name }} — {{ $document->module->title }}
            </flux:text>
        </div>
        <flux:text size="xs" class="text-zinc-500 italic">Read-only preview</flux:text>
    </div>

    {{-- PDF iframe --}}
    <iframe
        src="{{ route('hr.monitoring.document.preview', $document) }}"
        @style(["width: 100%", "height: {$height}", "border: none", "display: block"])
        loading="lazy"
        title="Learning Journal Preview">
        <p class="p-4 text-zinc-400 text-sm">
            Your browser does not support inline PDFs.
            <a href="{{ route('hr.monitoring.document.preview', $document) }}"
               class="underline text-teal-400">Open PDF</a>
        </p>
    </iframe>

</div>
@props([
    'url',
    'event' => 'open-pdf-preview',
])

<div
    x-data="{ open: false, docId: null, toolbar: '1' }"
    x-on:{{ $event }}.window="open = true; docId = $event.detail.id; toolbar = $event.detail.toolbar ?? '1'"
    x-show="open"
    x-cloak
    style="display:none"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
    @keydown.escape.window="open = false">

    <div
        class="absolute top-0 right-0 w-full max-w-2xl bg-zinc-900 rounded-xl shadow-2xl overflow-hidden"
        @click.outside="open = false">

        <div class="flex items-center justify-between px-5 py-3 border-b border-white/10 bg-white/5">
            <flux:heading size="sm">Document Preview</flux:heading>
            <flux:button size="sm" variant="ghost" icon="x-mark" x-on:click="open = false; docId = null" />
        </div>

        <template x-if="open && docId">
            <iframe
                :src="`{{ $url }}/${docId}/preview#toolbar=${toolbar}&navpanes=0&scrollbar=0`"
                style="width:100%; height:85vh; border:none; display:block;"
                loading="lazy"
                title="Learning Journal Preview">
            </iframe>
        </template>

    </div>
</div>
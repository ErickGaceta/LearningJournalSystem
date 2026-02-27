@php
    preg_match('/Temporary password: (.+)$/', session('success') ?? '', $matches);
    $password = $matches[1] ?? '';
@endphp

@if($password)
<div
    x-data="{ show: true, copied: false }"
    x-show="show"
    x-transition
    class="fixed top-2 right-2 z-50 w-full max-w-md" style="position: fixed; top: 5px; right: 5px;"
>

<flux:callout icon="key" variant="success">
    <div class="flex justify-between items-start gap-2">
        <div class="flex-1">
            <flux:callout.heading>
                Temporary Password — save this now
            </flux:callout.heading>
            <flux:callout.text>
                <div class="flex items-center gap-2 mt-2 p-2 bg-transparent border border-green-300 rounded-md">
                    <code class="flex-1 font-mono text-lg select-all">
                        {{ $password }}
                    </code>
                    <flux:button
                        size="sm"
                        variant="ghost"
                        @click="
                            navigator.clipboard.writeText('{{ $password }}');
                            copied = true;
                            setTimeout(() => copied = false, 2000)
                        "
                    >
                        <span x-text="copied ? 'Copied!' : 'Copy'"></span>
                    </flux:button>
                </div>
                <p class="text-xs mt-2">
                    This password Will appear again. The user must change it upon first login.
                </p>
            </flux:callout.text>
        </div>
        <flux:button
            size="xs"
            variant="ghost"
            @click="show = false"
        >
            <flux:icon.x-mark class="size-4"/>
        </flux:button>

    </div>

</flux:callout>

</div>
@endif
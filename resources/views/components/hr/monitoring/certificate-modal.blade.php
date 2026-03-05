{{-- ═══════════════════════════════════════════════════════════════
     FILE 1 ── routes/web.php  (add inside your HR middleware group)
     ═══════════════════════════════════════════════════════════════ --}}

// Inside your existing HR route group, e.g.:
// Route::prefix('hr')->middleware(['auth', 'role:hr'])->group(function () {

    Route::prefix('monitoring/certificates')
        ->name('hr.monitoring.certificates.')
        ->group(function () {

            // Preview (loaded in iframe)
            Route::get('{training}/{employee}',
                [App\Http\Controllers\HR\CertificateController::class, 'preview'])
                ->name('preview');

            // PDF download
            Route::get('{training}/{employee}/download',
                [App\Http\Controllers\HR\CertificateController::class, 'download'])
                ->name('download');
        });

// }); // end HR group


{{-- ═══════════════════════════════════════════════════════════════
     FILE 2 ── resources/views/components/hr/monitoring/certificate-modal.blade.php
     Drop this anywhere in a quarter-panel row to add the cert button.

     Usage:
       <x-hr.monitoring.certificate-modal
           :training="$training"
           :employee="$employee" />
     ═══════════════════════════════════════════════════════════════ --}}

@props([
    'training',   // Training model instance (must have ->id)
    'employee',   // Employee model instance (must have ->id, ->full_name / ->first_name)
])

@php
    $previewUrl  = route('hr.monitoring.certificates.preview',  [$training->id, $employee->id]);
    $downloadUrl = route('hr.monitoring.certificates.download', [$training->id, $employee->id]);
    $employeeName = $employee->full_name
        ?? trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? ''));
@endphp

{{-- ── Trigger button ── --}}
<div
    x-data="{ open: false }"
    class="inline-flex items-center gap-1">

    {{-- Certificate icon button --}}
    <flux:button
        size="xs"
        variant="ghost"
        icon="document-check"
        x-on:click="open = true"
        title="View Certificate"
    />

    {{-- ── Modal overlay ── --}}
    <div
        x-show="open"
        x-cloak
        style="display:none"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
        @keydown.escape.window="open = false">

        <div
            class="relative w-full max-w-5xl bg-zinc-900 rounded-2xl shadow-2xl overflow-hidden border border-white/10"
            @click.outside="open = false">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-white/10 bg-white/5">
                <flux:heading size="sm">
                    Certificate &mdash; {{ $employeeName }}
                </flux:heading>

                <div class="flex items-center gap-2">
                    {{-- Download button --}}
                    <flux:button
                        size="sm"
                        variant="filled"
                        color="lime"
                        icon="arrow-down-tray"
                        :href="$downloadUrl"
                        target="_blank"
                    >
                        Download PDF
                    </flux:button>

                    {{-- Close button --}}
                    <flux:button
                        size="sm"
                        variant="ghost"
                        icon="x-mark"
                        x-on:click="open = false"
                    />
                </div>
            </div>

            {{-- Certificate preview iframe -- only mounts when open --}}
            <template x-if="open">
                <iframe
                    src="{{ $previewUrl }}"
                    style="width:100%; height:78vh; border:none; display:block; background:#f5f0e8;"
                    loading="lazy"
                    title="Certificate Preview">
                </iframe>
            </template>

        </div>
    </div>

</div>

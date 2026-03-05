<x-layouts::app :title="__('View Learning Journal')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{ editing: false }">
        <!-- Header -->
        <div class="relative flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between lg:align-center">
            <div class="space-y-1">
                <flux:heading size="xl">Learning Journal</flux:heading>
                <flux:subheading>Submitted {{ $document->created_at->format('F d, Y') }}</flux:subheading>
            </div>
            <div class="flex flex-wrap gap-2 lg:flex-nowrap">
                <!-- Toggle Edit / Cancel -->
                <div x-data="{ open: false }" class="flex gap-4 align-end justify-end items-end w-full">
                    {{-- Archive Button (desktop) --}}
                    <div class="hidden lg:flex">
                        <flux:button
                            x-on:click="open = true"
                            icon="archive-box"
                            variant="filled" class="bg-amber-500 hover:bg-amber-600 text-white dark:bg-amber-500 dark:hover:bg-amber-600">
                            Archive
                        </flux:button>
                    </div>
                    {{-- Archive Button (mobile) --}}
                    <div class="flex lg:hidden">
                        <flux:button
                            x-on:click="open = true"
                            icon="archive-box"
                            variant="filled" class="bg-amber-500 hover:bg-amber-600 text-white dark:bg-amber-500 dark:hover:bg-amber-600">
                        </flux:button>
                    </div>

                    <div class="flex lg:hidden">
                        <flux:button
                            href="{{ route('user.documents.preview', $document) }}"
                            target="_blank"
                            icon="printer"
                            variant="primary">
                        </flux:button>
                    </div>
                    <div class="hidden lg:flex">
                        <flux:button
                            x-on:click="$dispatch('open-pdf-preview', { id: {{ $document->id }}, toolbar: '1' })"
                            icon="printer"
                            variant="primary">
                            Print
                        </flux:button>
                    </div>

                    <div class="flex lg:hidden">
                        <flux:button
                            x-show="!editing"
                            x-on:click="editing = true"
                            variant="ghost"
                            icon="pencil">
                        </flux:button>
                        <flux:button
                            x-show="editing"
                            x-on:click="editing = false"
                            variant="ghost"
                            icon="x-mark">
                        </flux:button>
                    </div>
                    <div class="hidden lg:flex">
                        <flux:button
                            x-show="!editing"
                            x-on:click="editing = true"
                            variant="ghost"
                            icon="pencil">
                            Edit
                        </flux:button>
                        <flux:button
                            x-show="editing"
                            x-on:click="editing = false"
                            variant="ghost"
                            icon="x-mark">
                            Cancel
                        </flux:button>
                    </div>

                    {{-- Archive Confirmation Modal --}}
                    <flux:modal x-model="open" class="min-w-88">
                        <form action="{{ route('user.documents.archive', $document) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="flex">
                                <div class="flex-1">
                                    <flux:heading size="lg">Archive this journal?</flux:heading>
                                    <flux:text class="mt-2">
                                        This learning journal will be archived and hidden from your active records.<br>
                                        You can restore it later from your archived documents.
                                    </flux:text>
                                </div>
                                <div class="-mx-2 -mt-2">
                                    <flux:button
                                        x-on:click="open = false"
                                        variant="ghost"
                                        size="sm"
                                        icon="x-mark"
                                        inset="top right bottom" />
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <flux:spacer />
                                <flux:button
                                    x-on:click="open = false"
                                    variant="ghost">
                                    Cancel
                                </flux:button>
                                <flux:button type="submit" variant="filled" class="bg-amber-500 hover:bg-amber-600 text-white dark:bg-amber-500 dark:hover:bg-amber-600">
                                    Archive
                                </flux:button>
                            </div>

                        </form>
                    </flux:modal>
                </div>
            </div>
            <div class="w-fit md:relative sm:absolute top-0 right-0">
                <flux:button class="w-fit"
                    href="{{ route('user.documents.index') }}"
                    variant="ghost"
                    icon="arrow-left"
                    wire:navigate>
                    Back
                </flux:button>
            </div>
        </div>

        <x-user.documents.journal-form :document="$document" />
    </div>

    <x-pdf-preview-modal :url="url('user/documents')" event="open-document-preview" />
</x-layouts::app>
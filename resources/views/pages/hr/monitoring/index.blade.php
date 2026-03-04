<x-layouts::app :title="__('Monitoring')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(session('success'))
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm">
            <div class="flex items-center">
                <svg class="w-3 h-3 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif



        <div class="relative overflow-hidden">
            <form method="GET" action="{{ route('hr.monitoring.index') }}" class="p-4">
                <div class="flex gap-3 justify-center items-center">
                    <div class="flex-1 relative">
                        <flux:input
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search by employee, module, venue, or conductor..."
                            icon:trailing="magnifying-glass"
                            class="w-full rounded-3xl" />
                    </div>
                    <flux:button type="submit" variant="primary" icon="magnifying-glass" color="lime" square />
                    @if(request('search'))
                    <flux:button :href="route('hr.modules.index')" variant="ghost">
                        Clear
                    </flux:button>
                    @endif
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
            <flux:card>
                <flux:heading size="lg" class="flex">Quater 1<flux:text size="sm">(Jan-Mar)</flux:text>
                </flux:heading>
                <flux:text class="mt-2 mb-4">

                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="flex">Quater 2<flux:text size="sm">(April-June)</flux:text>
                </flux:heading>
                <flux:text class="mt-2 mb-4">

                </flux:text>
            </flux:card>

            <flux:card>
                <flux:heading size="lg" class="flex">Quater 3<flux:text size="sm">(July-Sept)</flux:text>
                </flux:heading>
                <flux:text class="mt-2 mb-4">

                </flux:text>
            </flux:card>

            <flux:card>
                    <flux:heading size="lg" class="flex">Quater 4<flux:text size="sm">(Oct-Dec)</flux:text>
                </flux:heading>
                <flux:text class="mt-2 mb-4">

                </flux:text>
            </flux:card>
        </div>




    </div>
</x-layouts::app>

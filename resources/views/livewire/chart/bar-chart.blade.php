<div
    x-data="{
        isDark() {
            return document.documentElement.classList.contains('dark');
        },

        colors() {
    const dark = this.isDark();
    return {
        gridColor:   dark ? 'rgba(255,255,255,0.06)' : 'rgba(161,161,170,0.2)',  // zinc-400
        tickColor:   dark ? '#a1a1aa'                : '#71717a',                // zinc-400/500
        legendColor: dark ? '#d4d4d8'                : '#52525b',                // zinc-300/600
        tooltipBg:   dark ? 'rgba(9,9,11,0.95)'     : 'rgba(24,24,27,0.9)',     // zinc-950/900
    };
},

        buildOptions() {
            const c = this.colors();
            return {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 400, easing: 'easeInOutQuart' },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'rectRounded',
                            padding: 20,
                            color: c.legendColor,
                            font: { size: 13 },
                        },
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: c.tooltipBg,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 },
                        cornerRadius: 8,
                    },
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: c.tickColor, font: { size: 12 } },
                        border: { color: c.gridColor },
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: c.gridColor },
                        ticks: { color: c.tickColor, stepSize: 1, precision: 0, font: { size: 12 } },
                        border: { color: c.gridColor },
                    },
                },
            };
        },

        initChart(config) {
            const canvas = this.$refs.canvas;
            if (canvas._chartInstance) {
                canvas._chartInstance.destroy();
                canvas._chartInstance = null;
            }
            canvas._chartInstance = new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: { labels: config.labels, datasets: config.datasets },
                options: this.buildOptions(),
            });
        },

        updateChart(config) {
            const canvas = this.$refs.canvas;
            if (!canvas._chartInstance) return;
            canvas._chartInstance.data.labels   = config.labels;
            canvas._chartInstance.data.datasets = config.datasets;
            canvas._chartInstance.options       = this.buildOptions();
            canvas._chartInstance.update();
        },

        refreshTheme() {
            const canvas = this.$refs.canvas;
            if (!canvas._chartInstance) return;
            canvas._chartInstance.options = this.buildOptions();
            canvas._chartInstance.update();
        },

        init() {
            const tryInit = () => {
                if (this.$el.offsetParent !== null) {
                    this.initChart(@js($chartConfig));
                } else {
                    requestAnimationFrame(tryInit);
                }
            };
            this.$nextTick(tryInit);

            // Watch for dark mode class toggled on <html>
            const observer = new MutationObserver(() => this.refreshTheme());
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });
            this.$el._themeObserver = observer;
        },

        destroy() {
            if (this.$el._themeObserver) {
                this.$el._themeObserver.disconnect();
            }
        },
    }"
    x-on:bar-chart-updated.window="updateChart($event.detail.config)"
    class="bg-white dark:bg-zinc-900
       border border-zinc-200 dark:border-zinc-700
       rounded-2xl px-8 py-7 shadow-sm
       w-full h-full flex flex-col
       transition-colors duration-200"
    style="min-height: 400px;">
    {{-- ── Header ── --}}
    <div class="flex items-start justify-between flex-wrap gap-3 mb-6 shrink-0">
        <div>
            <h2 class="text-[1.1rem] font-bold text-zinc-900 dark:text-zinc-100 tracking-tight mb-1">
                {{ $title }}
            </h2>

            {{-- Week navigator --}}
            <div class="flex items-center gap-2 mt-1">
                <button
                    wire:click="previousWeek"
                    wire:loading.attr="disabled"
                    class="p-1 rounded-md
       text-zinc-400 dark:text-zinc-500
       hover:text-zinc-700 dark:hover:text-zinc-200
       hover:bg-zinc-100 dark:hover:bg-zinc-800
       disabled:opacity-40 transition-colors duration-150"
                    title="Previous week">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>

                <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400 min-w-[160px] text-center">
                    {{ $weekLabel }}
                </span>

                <button
                    wire:click="nextWeek"
                    wire:loading.attr="disabled"
                    @class([ 'p-1 rounded-md transition-colors duration-150' , 'text-zinc-400 dark:text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 disabled:opacity-40'=> !$isCurrentWeek,
                    'text-zinc-200 dark:text-zinc-700 cursor-not-allowed pointer-events-none' => $isCurrentWeek,

                    ])
                    title="Next week">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </button>

                @if(!$isCurrentWeek)
                <button
                    wire:click="currentWeek"
                    wire:loading.attr="disabled"
                    class="text-xs font-medium ml-1
       text-blue-500 dark:text-blue-400
       hover:text-blue-700 dark:hover:text-blue-300
       hover:underline disabled:opacity-40
       transition-colors duration-150"
                    title="Jump to current week">
                    Today
                </button>
                @endif
            </div>
        </div>

        {{-- Refresh --}}
        <button
            wire:click="computeConfig"
            wire:loading.attr="disabled"
            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium
       text-zinc-500 dark:text-zinc-400
       bg-zinc-50 dark:bg-zinc-800
       border border-zinc-200 dark:border-zinc-700
       rounded-lg cursor-pointer
       hover:bg-zinc-100 dark:hover:bg-zinc-700
       hover:border-zinc-300 dark:hover:border-zinc-600
       active:bg-zinc-200 dark:active:bg-zinc-600
       disabled:opacity-50 disabled:cursor-not-allowed
       transition-colors duration-150">
            <svg wire:loading.class="animate-spin"
                xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="shrink-0">
                <path d="M21 2v6h-6" />
                <path d="M3 12a9 9 0 0 1 15-6.7L21 8" />
                <path d="M3 22v-6h6" />
                <path d="M21 12a9 9 0 0 1-15 6.7L3 16" />
            </svg>
            <span wire:loading.remove>Refresh</span>
            <span wire:loading>Loading…</span>
        </button>
    </div>

    {{-- ── Chart Canvas ── --}}
    <div wire:ignore class="relative w-full flex-1 min-h-0 min-h-64">
        <canvas x-ref="canvas" class="absolute inset-0 w-full h-full"></canvas>
    </div>

</div>

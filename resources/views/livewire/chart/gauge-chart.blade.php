<div
    x-data="{
        chart: null,

        buildPlugin() {
            return {
                id: 'gaugeCenter',
                beforeDraw(chart) {
                    const { ctx, chartArea: { left, right, top, bottom } } = chart;
                    const cx = (left + right) / 2;
                    const cy = (top + bottom) / 2;
                    const { value, label, color } = chart.options.plugins.gaugeCenter;

                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.fillStyle = color;

                    // Percentage
                    ctx.font = 'bold 20px Arial Black, Arial, sans-serif';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(value, cx, cy - 8);

                    // Wrapped label
                    if (label) {
                        ctx.font = 'bold 11px Arial Black, Arial, sans-serif';
                        const words  = label.split(' ');
                        const lines  = [];
                        let current  = '';

                        for (const word of words) {
                            const test = current ? current + ' ' + word : word;
                            if (test.length > 14 && current) {
                                lines.push(current);
                                current = word;
                            } else {
                                current = test;
                            }
                        }
                        if (current) lines.push(current);

                        lines.forEach((line, i) => {
                            ctx.fillText(line, cx, cy + 10 + (i * 14));
                        });
                    }

                    ctx.restore();
                }
            };
        },

        initChart(config) {
            const ctx = this.$refs.canvas.getContext('2d');

            this.chart = new Chart(ctx, {
                type: 'doughnut',
                plugins: [this.buildPlugin()],
                data: {
                    datasets: [{
                        data: config.data,
                        backgroundColor: [config.strokeColor, '#1f1f1f', 'transparent'],
                        borderWidth: 0,
                        hoverOffset: 0,
                    }]
                },
                options: {
                    rotation: 150,      // start at ~8 o'clock, matches rotate(150) in SVG
                    cutout: '72%',
                    animation: { duration: 600, easing: 'easeInOutQuart' },
                    plugins: {
                        legend:  { display: false },
                        tooltip: { enabled: false },
                        gaugeCenter: {
                            value: config.centerText,
                            label: config.textLabel,
                            color: config.strokeColor,
                        },
                    },
                    events: [],
                }
            });
        },

        updateChart(config) {
            if (!this.chart) return;
            const ds = this.chart.data.datasets[0];
            ds.data = config.data;
            ds.backgroundColor[0] = config.strokeColor;

            const center = this.chart.options.plugins.gaugeCenter;
            center.value = config.centerText;
            center.label = config.textLabel;
            center.color = config.strokeColor;

            this.chart.update();
        },

        init() {
            this.$nextTick(() => this.initChart(@js($chartConfig)));
            $wire.$watch('chartConfig', (config) => this.updateChart(config));
        }
    }"
>
    {{-- wire:ignore prevents Livewire from overwriting the canvas on re-render --}}
    <div wire:ignore>
        <canvas x-ref="canvas" width="150" height="150"></canvas>
    </div>
</div>
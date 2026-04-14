<div
    x-data="{
        chart: null,

        buildPlugin() {
            return {
                id: 'comparisonCenter',
                beforeDraw(chart) {
                    const { ctx, chartArea: { left, right, top, bottom } } = chart;
                    const cx = (left + right) / 2;
                    const cy = (top + bottom) / 2;
                    const { label, isEmpty } = chart.options.plugins.comparisonCenter;

                    ctx.save();
                    ctx.textAlign    = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font         = 'bold 11px Arial Black, Arial, sans-serif';
                    ctx.fillStyle    = isEmpty ? '#6b7280' : '#ffffff';

                    if (label) {
                        const words   = label.split(' ');
                        const lines   = [];
                        let   current = '';

                        for (const word of words) {
                            const test = current ? current + ' ' + word : word;
                            if (test.length > 12 && current) {
                                lines.push(current);
                                current = word;
                            } else {
                                current = test;
                            }
                        }
                        if (current) lines.push(current);

                        const lineH      = 14;
                        const totalH     = lines.length * lineH;
                        const startY     = cy - totalH / 2 + lineH / 2;

                        lines.forEach((line, i) => {
                            ctx.fillText(line, cx, startY + i * lineH);
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
                    labels: config.labels,
                    datasets: [{
                        data:            config.data,
                        backgroundColor: config.isEmpty
                            ? ['#1f1f1f', '#1f1f1f', '#1f1f1f']
                            : config.colors,
                        borderColor:     '#0f0f0f',
                        borderWidth:     2,
                        hoverOffset:     4,
                    }]
                },
                options: {
                    cutout: '62%',
                    animation: { duration: 600, easing: 'easeInOutQuart' },
                    plugins: {
                        legend: {
                            display:  true,
                            position: 'bottom',
                            labels: {
                                color:    '#9ca3af',
                                boxWidth: 10,
                                padding:  8,
                                font:     { size: 10, family: 'Arial' },
                            }
                        },
                        tooltip: {
                            enabled: !config.isEmpty,
                            callbacks: {
                                label: (ctx) => ' ' + ctx.label,
                            }
                        },
                        comparisonCenter: {
                            label:   config.textLabel,
                            isEmpty: config.isEmpty,
                        },
                    },
                }
            });
        },

        updateChart(config) {
            if (!this.chart) return;
            const ds                         = this.chart.data.datasets[0];
            ds.data                          = config.data;
            ds.backgroundColor               = config.isEmpty
                ? ['#1f1f1f', '#1f1f1f', '#1f1f1f']
                : config.colors;
            this.chart.data.labels           = config.labels;
            this.chart.options.plugins.comparisonCenter.label   = config.textLabel;
            this.chart.options.plugins.comparisonCenter.isEmpty = config.isEmpty;
            this.chart.update();
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
            $wire.$watch('chartConfig', (config) => this.updateChart(config));
        },
    }"
>
    <div wire:ignore>
        <canvas x-ref="canvas" width="150" height="175"></canvas>
    </div>
</div>

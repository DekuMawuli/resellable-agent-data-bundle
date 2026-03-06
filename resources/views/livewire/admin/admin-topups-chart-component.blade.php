<div class="card h-100">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h4 class="card-title mb-1">Top-Up Trend</h4>
                <p class="text-muted mb-0">Total: <strong>GHS {{ number_format((float) $chart["totalAmount"], 2) }}</strong> ({{ $chart["totalCount"] }} deposits)</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="topup-range-{{ $this->getId() }}" class="mb-0 text-muted small">Range</label>
                <select id="topup-range-{{ $this->getId() }}" wire:model.change="range" class="form-select form-select-sm" style="min-width: 120px;">
                    @foreach($rangeOptions as $days)
                        <option value="{{ $days }}">{{ $days === 1 ? "Today" : "Last {$days} days" }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div wire:ignore id="admin-topups-chart-{{ $this->getId() }}" style="min-height: 290px;"></div>
    </div>

    @script
    <script>
        let topupsChartInstance = null;
        const topupsChartElementId = "admin-topups-chart-{{ $this->getId() }}";

        const renderTopupsChart = (chartData) => {
            const chartElement = document.getElementById(topupsChartElementId);
            if (!chartElement || typeof ApexCharts === "undefined") {
                return;
            }

            // Livewire can replace the chart node on updates; recreate chart when that happens.
            if (topupsChartInstance && topupsChartInstance.el !== chartElement) {
                topupsChartInstance.destroy();
                topupsChartInstance = null;
            }

            const options = {
                chart: {
                    type: "line",
                    height: 290,
                    toolbar: { show: false },
                    animations: { easing: "easeinout", speed: 350 }
                },
                stroke: {
                    curve: "smooth",
                    width: 3
                },
                markers: {
                    size: 3,
                    hover: { size: 5 }
                },
                dataLabels: { enabled: false },
                grid: { borderColor: "rgba(120, 130, 140, 0.12)" },
                colors: ["#22c55e"],
                series: [
                    {
                        name: "Top-Ups (GHS)",
                        data: chartData.series
                    }
                ],
                xaxis: {
                    categories: chartData.labels
                },
                yaxis: {
                    labels: {
                        formatter: (value) => `GHS ${Number(value).toFixed(2)}`
                    }
                },
                tooltip: {
                    y: {
                        formatter: (value, { dataPointIndex }) => {
                            const count = chartData.countSeries[dataPointIndex] ?? 0;
                            return `GHS ${Number(value).toFixed(2)} (${count} deposits)`;
                        }
                    }
                }
            };

            if (topupsChartInstance) {
                topupsChartInstance.updateOptions({
                    xaxis: options.xaxis,
                    tooltip: options.tooltip
                }, false, true);
                topupsChartInstance.updateSeries(options.series, true);
                return;
            }

            topupsChartInstance = new ApexCharts(chartElement, options);
            topupsChartInstance.render();
        };

        renderTopupsChart(@js($chart));

        $wire.on("admin-topups-chart-updated", ({ chart }) => {
            renderTopupsChart(chart);
        });
    </script>
    @endscript
</div>

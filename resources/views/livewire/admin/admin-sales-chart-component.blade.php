<div class="card h-100">
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <h4 class="card-title mb-1">Sales Trend</h4>
                <p class="text-muted mb-0">Total: <strong>GHS {{ number_format((float) $chart["totalSales"], 2) }}</strong> ({{ $chart["totalOrders"] }} orders)</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <label for="sales-range-{{ $this->getId() }}" class="mb-0 text-muted small">Range</label>
                <select id="sales-range-{{ $this->getId() }}" wire:model.change="range" wire:loading.attr="disabled" wire:target="range" class="form-select form-select-sm" style="min-width: 120px;">
                    @foreach($rangeOptions as $days)
                        <option value="{{ $days }}">Last {{ $days }} days</option>
                    @endforeach
                </select>
                <span class="small text-muted d-none align-items-center gap-2" wire:loading.flex wire:target="range">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Updating...
                </span>
            </div>
        </div>

        <div class="position-relative" wire:loading.class="opacity-50" wire:target="range">
            <div class="position-absolute top-0 start-50 translate-middle-x mt-2 d-none" wire:loading.flex wire:target="range" style="z-index: 1;">
                <div class="badge bg-light text-dark border shadow-sm px-3 py-2">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Refreshing chart...
                </div>
            </div>
            <div wire:ignore id="admin-sales-chart-{{ $this->getId() }}" style="min-height: 290px;"></div>
        </div>
    </div>

    @script
    <script>
        let salesChartInstance = null;
        const salesChartElementId = "admin-sales-chart-{{ $this->getId() }}";

        const renderSalesChart = (chartData) => {
            const chartElement = document.getElementById(salesChartElementId);
            if (!chartElement || typeof ApexCharts === "undefined") {
                return;
            }

            // Livewire can replace the chart node on updates; recreate chart when that happens.
            if (salesChartInstance && salesChartInstance.el !== chartElement) {
                salesChartInstance.destroy();
                salesChartInstance = null;
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
                colors: ["#3b82f6"],
                series: [
                    {
                        name: "Sales (GHS)",
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
                            const count = chartData.ordersSeries[dataPointIndex] ?? 0;
                            return `GHS ${Number(value).toFixed(2)} (${count} orders)`;
                        }
                    }
                }
            };

            if (salesChartInstance) {
                salesChartInstance.updateOptions({
                    xaxis: options.xaxis,
                    tooltip: options.tooltip
                }, false, true);
                salesChartInstance.updateSeries(options.series, true);
                return;
            }

            salesChartInstance = new ApexCharts(chartElement, options);
            salesChartInstance.render();
        };

        renderSalesChart(@js($chart));

        $wire.on("admin-sales-chart-updated", ({ chart }) => {
            renderSalesChart(chart);
        });
    </script>
    @endscript
</div>

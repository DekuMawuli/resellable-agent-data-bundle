<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class AdminSalesChartComponent extends Component
{
    public int $range = 3;

    public array $rangeOptions = [3, 7, 30, 90];

    public function mount(): void
    {
        $this->range = (int) $this->range;
        $this->range = in_array($this->range, $this->rangeOptions, true) ? $this->range : 3;
    }

    public function updatedRange(): void
    {
        $this->range = (int) $this->range;

        if (! in_array($this->range, $this->rangeOptions, true)) {
            $this->range = 3;
        }

        $this->dispatch("admin-sales-chart-updated", chart: $this->buildChartPayload())->self();
    }

    public function render()
    {
        return view("livewire.admin.admin-sales-chart-component", [
            "chart" => $this->buildChartPayload(),
        ]);
    }

    private function buildChartPayload(): array
    {
        $startDate = now()->startOfDay()->subDays($this->range - 1);
        $endDate = now()->endOfDay();

        $aggregates = Order::query()
            ->selectRaw("DATE(created_at) as order_date, COALESCE(SUM(total_amount), 0) as total_amount, COUNT(*) as orders_count")
            ->where("payment_made", true)
            ->whereBetween("created_at", [$startDate, $endDate])
            ->groupBy("order_date")
            ->orderBy("order_date")
            ->get()
            ->keyBy("order_date");

        $labels = [];
        $series = [];
        $ordersSeries = [];
        $totalSales = 0.0;
        $totalOrders = 0;

        foreach (CarbonPeriod::create($startDate, "1 day", $endDate) as $date) {
            $day = $date->toDateString();
            $point = $aggregates->get($day);

            $daySales = (float) ($point->total_amount ?? 0);
            $dayOrders = (int) ($point->orders_count ?? 0);

            $labels[] = Carbon::parse($day)->format("M j");
            $series[] = round($daySales, 2);
            $ordersSeries[] = $dayOrders;

            $totalSales += $daySales;
            $totalOrders += $dayOrders;
        }

        return [
            "labels" => $labels,
            "series" => $series,
            "ordersSeries" => $ordersSeries,
            "totalSales" => round($totalSales, 2),
            "totalOrders" => $totalOrders,
        ];
    }
}

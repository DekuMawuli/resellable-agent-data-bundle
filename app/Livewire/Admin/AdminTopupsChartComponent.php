<?php

namespace App\Livewire\Admin;

use App\Models\TopUp;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class AdminTopupsChartComponent extends Component
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

        $this->dispatch("admin-topups-chart-updated", chart: $this->buildChartPayload())->self();
    }

    public function render()
    {
        return view("livewire.admin.admin-topups-chart-component", [
            "chart" => $this->buildChartPayload(),
        ]);
    }

    private function buildChartPayload(): array
    {
        $startDate = now()->startOfDay()->subDays($this->range - 1);
        $endDate = now()->endOfDay();

        $aggregates = TopUp::query()
            ->selectRaw("DATE(created_at) as topup_date, COALESCE(SUM(amount), 0) as total_amount, COUNT(*) as deposits_count")
            ->where("payment_made", true)
            ->where("status", "!=", "cancelled")
            ->whereBetween("created_at", [$startDate, $endDate])
            ->groupBy("topup_date")
            ->orderBy("topup_date")
            ->get()
            ->keyBy("topup_date");

        $labels = [];
        $series = [];
        $countSeries = [];
        $totalAmount = 0.0;
        $totalCount = 0;

        foreach (CarbonPeriod::create($startDate, "1 day", $endDate) as $date) {
            $day = $date->toDateString();
            $point = $aggregates->get($day);

            $dayAmount = (float) ($point->total_amount ?? 0);
            $dayCount = (int) ($point->deposits_count ?? 0);

            $labels[] = Carbon::parse($day)->format("M j");
            $series[] = round($dayAmount, 2);
            $countSeries[] = $dayCount;

            $totalAmount += $dayAmount;
            $totalCount += $dayCount;
        }

        return [
            "labels" => $labels,
            "series" => $series,
            "countSeries" => $countSeries,
            "totalAmount" => round($totalAmount, 2),
            "totalCount" => $totalCount,
        ];
    }
}

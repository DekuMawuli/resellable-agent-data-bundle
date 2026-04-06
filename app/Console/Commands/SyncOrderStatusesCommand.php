<?php

namespace App\Console\Commands;

use App\Services\OrderStatusSyncService;
use Illuminate\Console\Command;

class SyncOrderStatusesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:sync-statuses {--limit=50} {--min-age=2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync in-flight provider orders so admin sales and order statuses stay accurate.';

    public function handle(OrderStatusSyncService $orderStatusSyncService): int
    {
        $result = $orderStatusSyncService->syncPendingProviderOrders(
            (int) $this->option('limit'),
            (int) $this->option('min-age')
        );

        $this->info(sprintf(
            'Synced: %d, updated: %d, failed: %d, skipped: %d',
            $result['synced'] ?? 0,
            $result['updated'] ?? 0,
            $result['failed'] ?? 0,
            $result['skipped'] ?? 0,
        ));

        if (filled($result['message'] ?? null)) {
            $this->line((string) $result['message']);
        }

        return self::SUCCESS;
    }
}

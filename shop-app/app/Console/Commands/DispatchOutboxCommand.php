<?php

namespace App\Console\Commands;

use App\Services\OutboxDispatcher;
use Illuminate\Console\Command;

class DispatchOutboxCommand extends Command
{
    protected $signature = 'outbox:dispatch {--limit=50}';

    protected $description = 'Send pending outbox messages to the integration broker';

    public function handle(OutboxDispatcher $dispatcher): int
    {
        $sent = $dispatcher->dispatchPending((int) $this->option('limit'));
        $this->info("Sent {$sent} outbox message(s).");

        return self::SUCCESS;
    }
}

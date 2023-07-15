<?php

namespace App\Listeners;

use App\Events\LocalTransferCreated;
use App\Jobs\TransferLocalFileToCloud;
use Illuminate\Support\Facades\Bus;
use Throwable;

class CreateTransferBatch
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param LocalTransferCreated $event
     * @return void
     * @throws Throwable
     */
    public function handle(LocalTransferCreated $event)
    {
        $transfer = $event->getTransfer();
        $jobs = $transfer->files->mapInto(TransferLocalFileToCloud::class);

        $batch = Bus::batch($jobs)
            ->finally(function () use ($transfer) {
                TransferCompleted::dispatch($transfer);
            })->dispatch();

        $event->getTransfer()->update([
            'batch_id' => $batch->id
        ]);
    }
}

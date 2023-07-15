<?php

namespace App\Jobs;

use App\Models\TransferFile;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class TransferLocalFileToCloud implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private TransferFile $file)
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cloudPath = Storage::disk('s3')->put('images', new File($localPath = $this->file->path));

        $this->file->update([
            'disk' => 's3',
            'path' => $cloudPath,
        ]);

        Storage::delete(explode('/app/', $localPath)[1]);

        // Dispatch event
    }
}

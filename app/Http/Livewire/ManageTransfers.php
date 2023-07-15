<?php

namespace App\Http\Livewire;

use App\Events\LocalTransferCreated;
use App\Models\TransferFile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class ManageTransfers extends Component
{
    use WithFileUploads;

    public array $pendingFiles = [];

    public function initiateTransfer()
    {
        $this->validate([
            'pendingFiles.*' => ['image', 'max:5120']
        ]);

        // This code will not execute if the validation fails
        $transfer = auth()->user()->transfers()->create();
        $transfer->files()->saveMany(
            collect($this->pendingFiles)
                ->map(function (TemporaryUploadedFile $pendingFile) {
                    return new TransferFile([
                        'disk' => $pendingFile->disk,
                        'path' => $pendingFile->getRealPath(),
                        'size' => $pendingFile->getSize(),
                    ]);
                })
        );

        $this->pendingFiles = [];

        LocalTransferCreated::dispatch($transfer);
    }

    public function getListeners()
    {
        $userId = auth()->id();

        return [
            "echo-private:notifications.{$userId},FileTransferredToCloud" => '$refresh',
            "echo-private:notifications.{$userId},TransferCompleted" => 'fireConfettiCannon',
        ];
    }

    public function fireConfettiCannon()
    {
        $this->emit('confetti');
    }

    public function render()
    {
        $user = auth()->loginUsingId(\App\Models\User::first()->id);

        return view('livewire.manage-transfers', [
            'transfers' => $user->transfers()->with('jobBatch')->withSum('files', 'size')->get(),
        ]);
    }
}

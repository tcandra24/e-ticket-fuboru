<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Carbon\Carbon;

class DeleteUnvalidateRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $registrations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($registrations)
    {
        $this->registrations = $registrations;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //  && Carbon::now()->greaterThan($this->registrations->created_at->addMinutes(2))
        if ($this->registrations->receipts()->count() < 1) {
            // Hapus transaksi jika sudah kadaluwarsa
            $this->registrations->delete();
        }
    }
}

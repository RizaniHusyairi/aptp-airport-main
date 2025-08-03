<?php

namespace App\Jobs;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogVisitorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    // Properti untuk menyimpan data dari controller
    protected $ip_address;
    protected $user_agent;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $ip_address, ?string $user_agent)
    {
        $this->ip_address = $ip_address;
        $this->user_agent = $user_agent;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Logika yang ingin kita jalankan di latar belakang
        // Sama persis seperti yang ada di controller sebelumnya
        Visitor::create([
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
        ]);
    }
}

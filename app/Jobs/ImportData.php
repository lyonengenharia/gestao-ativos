<?php

namespace App\Jobs;

use App\Facades\Importer;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;




class ImportData implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $request;
    public function __construct($data)
    {
        $this->request = $data;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Importer::Process($this->request);
    }

}

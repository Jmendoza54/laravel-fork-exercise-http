<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class runHttp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'connect:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Conectar por post';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = rescue(function () {

            return Http::retry(3, 100)->post('https://atomic.incfile.com/fakepost');
        
        }, function ($e) {
            
            return $e->response;
        
        });

        if($response->successful()){
            $this->info('Listo ' . $response->status() . $response->body());
        }

        if($response->failed()){
            $this->error('Error' . $response->status());
        }

        if($response->clientError())
            $this->error('Error'. $response->status());

        if($response->serverError())
            $this->error('Error'. $response->status());
    }
}

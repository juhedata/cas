<?php

namespace JuHeData\CasLogin\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'juhecas:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all of the JuHe Cas Login resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Sanctum Service Provider...');
        $this->callSilent('vendor:publish', ['--provider' => 'Laravel\Sanctum\SanctumServiceProvider']);

        $this->comment('Publishing Sanctum Migrate...');

        $this->comment('Publishing Cas Client Provider...');
        $this->callSilent('vendor:publish', ['--provider' => 'JuHeData\Cas\CasServiceProvider']);

        $this->comment('Publishing Juhe Cas Login Service Provider...');
        $this->callSilent('vendor:publish', ['--provider' => 'JuHeData\CasLogin\CasLoginServiceProvider']);

        $this->callSilent('migrate');

    }
}

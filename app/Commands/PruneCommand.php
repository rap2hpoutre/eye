<?php

namespace Eyewitness\Eye\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class PruneCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'eyewitness:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eyewitness.io - command to prune old history. Will be called automatically by the package.';

    /**
     * Indicates whether the command should be shown in the Artisan command list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(config('eyewitness.days_to_keep_history'));

        \Eyewitness\Eye\Repo\History\Scheduler::where('created_at', '<', $date)->delete();
        \Eyewitness\Eye\Repo\History\Database::where('created_at', '<', $date)->delete();
        \Eyewitness\Eye\Repo\History\Custom::where('created_at', '<', $date)->delete();
        \Eyewitness\Eye\Repo\History\Queue::where('date', '<', $date)->delete();
        \Eyewitness\Eye\Repo\Notifications\History::where('created_at', '<', $date)->delete();

        $this->info('Eyewitness prune complete.');
    }
}

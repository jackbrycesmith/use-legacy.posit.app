<?php

namespace App\Actions\Utils;

use App\Notifications\TelescopeReport;
use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Laravel\Telescope\EntryType;
use Laravel\Telescope\Storage\EntryModel;
use Laravel\Telescope\Storage\EntryQueryOptions;
use Lorisleiva\Actions\Action;

class SendTelescopeReport extends Action
{
    protected static $commandSignature = 'utils:send-telescope-report';

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get exceptions

        $exceptionEntries = EntryModel::select('uuid', 'type', 'content')
            ->where('reported', false)
            ->get();

        if ($exceptionEntries->count() === 0) {
            return false;
        }

        Notification::route('telegram', '')->notify(new TelescopeReport($exceptionEntries));

        DB::table('telescope_entries')
            ->whereIn('uuid', $exceptionEntries->modelKeys())
            ->update(['reported' => true]);

        return true;
    }

    public function consoleOutput($result, Command $command)
    {
        if ($result) {
            $command->info("Sent Telescope Report!");
        } else {
            $command->info("Nothing to report!");
        }
    }
}

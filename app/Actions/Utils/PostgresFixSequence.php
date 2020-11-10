<?php

namespace App\Actions\Utils;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Action;

class PostgresFixSequence extends Action
{
    protected static $commandSignature = 'utils:postgres-fix-sequence';

    /**
     * Execute the action and return a result.
     *
     * @return mixed
     *
     * @see https://stackoverflow.com/questions/37970743/postgresql-unique-violation-7-error-duplicate-key-value-violates-unique-const
     */
    public function handle()
    {
        $tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = \'public\' ORDER BY table_name;');

        // e.g. tables without numerical 'id' incrementing sequence
        $tablesToIgnore = [
            'sessions',
            'password_resets',
            'stripe_accounts',
            'stripe_checkout_sessions',
            'stripe_customers',
            'stripe_events',
            'stripe_payment_intents',
            'telescope_entries',
            'telescope_entries_tags',
            'telescope_monitoring',
        ];

        collect($tables)->each(function ($table) use ($tablesToIgnore) {
            if (in_array($table->table_name, $tablesToIgnore)) return;

            $seq = DB::table($table->table_name)->max('id') + 1;
            DB::select('ALTER SEQUENCE ' . $table->table_name . '_id_seq RESTART WITH ' . $seq);
        });
    }

    public function consoleOutput($result, Command $command)
    {
        $command->info("Fixed postgres table sequence!");
    }
}

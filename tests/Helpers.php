<?php

namespace Tests;

/**
 * A basic assert example.
 */
function assertExample(): void
{
    test()->assertTrue(true);
}

/**
 * Dump the content of database table.
 *
 * @param  string|array $tables
 * @param  array        $data
 * @return void
 */
function dumpTable($tables, array $data = [])
{
    $database = app()->make('db');

    $connection = $database->getDefaultConnection();

    if (is_string($tables)) {
        $tables = [$tables];
    }

    foreach ($tables as $table) {
        (new \Symfony\Component\VarDumper\VarDumper)->dump([$table => $database->connection($connection)->table($table)->where($data)->get()]);
    }

    die(1);
}

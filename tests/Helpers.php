<?php

namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;

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

/**
 * Set the currently logged in user for the application.
 *
 * @return TestCase
 */
function actingAs(Authenticatable $user, string $driver = null)
{
    return test()->actingAs($user, $driver);
}

/**
 * Check inertia component response & existence
 *
 * @param \Illuminate\Testing\TestResponse $response The response
 * @param string $component The expected inertia page component
 */
function assertInertiaComponent(TestResponse $response, string $component, ?string $componentFileEnding = '.vue')
{
    $inertiaData = $response->getOriginalContent()->getData();
    $inertiaComponentResponse = Arr::get($inertiaData, 'page.component');
    assertEquals($component, $inertiaComponentResponse, "Inertia response component: {$inertiaComponentResponse}] does not match provided: {$component}");

    if (is_null($componentFileEnding)) return;
    $componentFilePath = resource_path() . '/js/Pages/' . Str::of($component)->replace('\\', '/') . $componentFileEnding;
    assertFileExists($componentFilePath, "Inertia component file is missing");
}

/**
 * Check inertia props response equality
 *
 * @param \Illuminate\Testing\TestResponse $response The response
 * @param array $propsToCheck The properties to check
 */
function assertInertiaProps(TestResponse $response, array $propsToCheck = [])
{
    $inertiaData = $response->getOriginalContent()->getData();
    $inertiaPropsResponse = Arr::get($inertiaData, 'page.props');

    // TODO
}

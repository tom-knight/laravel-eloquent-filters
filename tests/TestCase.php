<?php

namespace Pricecurrent\LaravelEloquentFilters\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Pricecurrent\LaravelEloquentFilters\QueryFiltersServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Pricecurrent\\LaravelEloquentFilters\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            QueryFiltersServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        $this->createTables('filterable_models');
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function (string $tableName) {
            Schema::create($tableName, function (Blueprint $table) use ($tableName) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('text')->nullable();
                $table->string('occupation')->nullable();
                $table->integer('age')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        });
    }

    public function filtersPath($path = '')
    {
        return app_path('Filters' . ($path ? "/$path" : ''));
    }
}

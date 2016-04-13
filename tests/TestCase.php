<?php

namespace Spatie\Activitylog\Test;

use Spatie\Activitylog\ActivitylogServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Activitylog\Test\Models\User1;
use Spatie\Activitylog\Test\Models\User2;
use Spatie\Activitylog\Test\Models\Item1;
use Spatie\Activitylog\Test\Models\Item2;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [ActivitylogServiceProvider::class];
    }
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => $this->getTempDirectory().'/database.sqlite',
            'prefix' => '',
        ]);
        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }
    protected function setUpDatabase($app)
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);

        $this->createItem1sTable($app);

        $this->createItem2sTable($app);

        $this->createUser1sTable($app);

        $this->createUser2sTable($app);

        $this->createActivityLogTable($app);

        $this->createUsers();

        $this->createItems();
    }

    public function getTempDirectory($suffix = '')
    {
        return __DIR__.'/temp'.($suffix == '' ? '' : '/'.$suffix);
    }

    protected function createUsers()
    {
        foreach (range(1, 10) as $index) {
            User1::create();
            User2::create();
        }
    }

    /**
     * @param $app
     */
    protected function createItem1sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('item1s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('causes_activity_type')->nullable();
            $table->integer('causes_activity_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @param $app
     */
    protected function createItem2sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('item2s', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('causes_activity_type')->nullable();
            $table->integer('causes_activity_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * @param $app
     */
    protected function createUser1sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('user1s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    /**
     * @param $app
     */
    protected function createUser2sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('user2s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    /**
     * @param $app
     */
    protected function createActivityLogTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->integer('causes_activity_id')->nullable();
            $table->string('causes_activity_type')->nullable();
            $table->integer('logs_activity_id')->nullable();
            $table->string('logs_activity_type')->nullable();
            $table->string('ip_address', 64);
            $table->string('adjustments')->nullable();
            $table->timestamps();
        });
    }

    protected function createItems()
    {
        User1::each(function ($user) {
            $item1 = new Item1();
            $item1->name = 'test name';
            $item2 = new Item2();
            $item2->name = 'test name';
            $user->logsActivity()->save($item1);
            $user->logsActivity()->save($item2);
        });

        User2::each(function ($user) {
            $item1 = new Item1();
            $item1->name = 'test name';
            $item2 = new Item2();
            $item2->name = 'test name';
            $user->logsActivity()->save($item1);
            $user->logsActivity()->save($item2);
        });
    }
}

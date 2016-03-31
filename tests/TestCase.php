<?php

namespace Spatie\Activitylog\Test;

use Spatie\Activitylog\ActivitylogServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Activitylog\Test\Models\DummyUser1;
use Spatie\Activitylog\Test\Models\DummyUser2;
use Spatie\Activitylog\Test\Models\DummyItem1;
use Spatie\Activitylog\Test\Models\DummyItem2;

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

        $this->createDummyItem1sTable($app);

        $this->createDummyItem2sTable($app);

        $this->createDummyUser1sTable($app);

        $this->createDummyUser2sTable($app);

        $this->createActivityLogTable($app);

        $this->createDummyUsers();

        $this->createDummyItems();

    }

    public function getTempDirectory($suffix = '')
    {
        return __DIR__.'/temp'.($suffix == '' ? '' : '/'.$suffix);
    }

    protected function createDummyUsers()
    {
        foreach (range(1, 10) as $index) {
            DummyUser1::create();
            DummyUser2::create();
        }
    }

    /**
     * @param $app
     */
    protected function createDummyItem1sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('dummy_item1s', function (Blueprint $table) {
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
    protected function createDummyItem2sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('dummy_item2s', function (Blueprint $table) {
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
    protected function createDummyUser1sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('dummy_user1s', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
    }

    /**
     * @param $app
     */
    protected function createDummyUser2sTable($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('dummy_user2s', function (Blueprint $table) {
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

    protected function createDummyItems()
    {
        DummyUser1::each(function($user) {
            $item1 = new DummyItem1();
            $item1->name = "test name";
            $item2 = new DummyItem2();
            $item2->name = "test name";
            $user->logsActivity()->save($item1);
            $user->logsActivity()->save($item2);
        });

        DummyUser2::each(function($user) {
            $item1 = new DummyItem1();
            $item1->name = "test name";
            $item2 = new DummyItem2();
            $item2->name = "test name";
            $user->logsActivity()->save($item1);
            $user->logsActivity()->save($item2);
        });

    }

}

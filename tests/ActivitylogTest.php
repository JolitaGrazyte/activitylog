<?php

namespace Spatie\Activitylog\Test;

use Spatie\Activitylog\Test\Models\DummyItem1;
use Spatie\Activitylog\Test\Models\DummyItem2;
use Spatie\Activitylog\Test\Models\DummyUser1;
use Spatie\Activitylog\Test\Models\DummyUser2;
use Spatie\Activitylog\Models\Activity;

class ActivitylogTest extends TestCase
{
    /** @test */
    public function it_logs_activity_to_the_database_when_item_is_created()
    {
        $dummyItems = DummyItem1::count();

        $activityLogs = Activity::where('text', 'Item "test name" was created')->where('logs_activity_type', DummyItem1::class)->count();

        $this->assertEquals($dummyItems, $activityLogs);

    }

    /** @test */
    public function it_logs_activity_to_the_database_when_item_is_updated()
    {
        $this->updateAllDummyItems();

        $dummyItems = DummyItem1::count();

        $activityLogs = Activity::where('text', 'Item "updated name" was updated')->where('logs_activity_type', DummyItem1::class)->count();

        $this->assertEquals($dummyItems, $activityLogs);

    }

    /** @test */
    public function it_can_log_activity_to_the_database_when_item_is_deleted()
    {
        $totalDummyItems = app(DummyItem1::class)->count();

        app(DummyItem1::class)->each(function($item){
            $item->delete();
        });

        $activity_logs = Activity::where('text', 'Item "test name" was deleted')->where('logs_activity_type', DummyItem1::class)->count();

        $this->assertEquals($totalDummyItems, $activity_logs);

    }

    /** @test */
    public function it_logs_different_models()
    {
        $dummyItems1ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('logs_activity_type', DummyItem1::class)->count();
        $dummyItems2ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('logs_activity_type', DummyItem2::class)->count();

        $this->assertEquals(DummyItem1::count(), $dummyItems1ActivityLogs);
        $this->assertEquals(DummyItem2::count(), $dummyItems2ActivityLogs);

    }

    /** @test */
    public function it_logs_different_models_causing_activity()
    {
        $user1DummyItems1 = DummyItem1::where('causes_activity_type', DummyUser1::class)->count();
        $user1DummyItems2 = DummyItem2::where('causes_activity_type', DummyUser1::class)->count();
//        dd($user1DummyItems);
        $user2DummyItems1 = DummyItem1::where('causes_activity_type', DummyUser2::class)->count();
        $user2DummyItems2 = DummyItem1::where('causes_activity_type', DummyUser2::class)->count();
//        dd($user2DummyItems);

        $user1ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('causes_activity_type', DummyUser1::class)->count();
        $user2ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('causes_activity_type', DummyUser2::class)->count();

        $this->assertEquals(($user1DummyItems1+$user1DummyItems2), $user1ActivityLogs);
        $this->assertEquals(($user2DummyItems1+$user2DummyItems2), $user2ActivityLogs);

    }

    /** @test */
    public function it_logs_adjustments_in_json_format()
    {
        Activity::each(function($item){
            $this->assertJson($item->adjustments);
        });

    }

    protected function updateAllDummyItems()
    {
        DummyItem1::each(function($item){
            $item->update([
                'name' => 'updated name'
            ]);
        });
    }
}

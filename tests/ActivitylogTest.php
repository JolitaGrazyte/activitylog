<?php

namespace Spatie\Activitylog\Test;

use Spatie\Activitylog\Test\Models\Item1;
use Spatie\Activitylog\Test\Models\Item2;
use Spatie\Activitylog\Test\Models\User1;
use Spatie\Activitylog\Test\Models\User2;
use Spatie\Activitylog\Models\Activity;

class ActivitylogTest extends TestCase
{
    /** @test */
    public function it_logs_activity_to_the_database_when_item_is_created()
    {
        $Items = Item1::count();
        $activityLogs = Activity::where('text', 'Item "test name" was created')
            ->where('logs_activity_type', Item1::class)->count();

        $this->assertEquals($Items, $activityLogs);
    }

    /** @test */
    public function it_logs_activity_to_the_database_when_item_is_updated()
    {
        $this->updateAllItems();
        $items = Item1::count();
        $activityLogs = Activity::where('text', 'Item "updated name" was updated')->where('logs_activity_type', Item1::class)->count();

        $this->assertEquals($items, $activityLogs);
    }

    /** @test */
    public function it_can_log_activity_to_the_database_when_item_is_deleted()
    {
        $totalItems = app(Item1::class)->count();

        app(Item1::class)->each(function ($item) {
            $item->delete();
        });

        $activity_logs = Activity::where('text', 'Item "test name" was deleted')->where('logs_activity_type', Item1::class)->count();

        $this->assertEquals($totalItems, $activity_logs);
    }

    /** @test */
    public function it_logs_different_models()
    {
        $items1ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('logs_activity_type', Item1::class)->count();
        $items2ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('logs_activity_type', Item2::class)->count();

        $this->assertEquals(Item1::count(), $items1ActivityLogs);
        $this->assertEquals(Item2::count(), $items2ActivityLogs);
    }

    /** @test */
    public function it_logs_different_models_causing_activity()
    {
        $user1Items1 = Item1::where('causes_activity_type', User1::class)->count();
        $user1Items2 = Item2::where('causes_activity_type', User1::class)->count();

        $user2Items1 = Item1::where('causes_activity_type', User2::class)->count();
        $user2Items2 = Item1::where('causes_activity_type', User2::class)->count();

        $user1ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('causes_activity_type', User1::class)->count();
        $user2ActivityLogs = Activity::where('text', 'Item "test name" was created')->where('causes_activity_type', User2::class)->count();

        $this->assertEquals(($user1Items1 + $user1Items2), $user1ActivityLogs);
        $this->assertEquals(($user2Items1 + $user2Items2), $user2ActivityLogs);
    }

    /** @test */
    public function it_logs_adjustments_in_json_format()
    {
        Activity::each(function ($item) {
            $this->assertJson($item->adjustments);
        });
    }

    protected function updateAllItems()
    {
        Item1::each(function ($item) {
            $item->update([
                'name' => 'updated name',
            ]);
        });
    }
}

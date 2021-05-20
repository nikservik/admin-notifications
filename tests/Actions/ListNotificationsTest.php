<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use App\Models\User;
use Nikservik\AdminNotifications\Actions\ListNotifications;
use Nikservik\AdminNotifications\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class ListNotificationsTest extends TestCase
{

    public function testHandleEmpty()
    {
        $notifications = ListNotifications::run();

        $this->assertCount(0, $notifications);
    }

    public function testHandle()
    {
        User::factory()->count(3)->hasSupportMessages(5)->create();
        SupportMessage::factory()->notification()->count(3)->create();

        $notifications = ListNotifications::run();

        $this->assertCount(3, $notifications);
    }

    public function testHandleReadCountZero()
    {
        $notification = SupportMessage::factory()->notification()->create();

        $notifications = ListNotifications::run();

        $this->assertEquals(0, $notifications[0]->read);
    }

    public function testHandleReadCount()
    {
        $user = User::factory()->create();
        $notification = SupportMessage::factory()->notification()->create();
        SupportMessage::factory()->for($user)->notificationRead($notification)->create();

        $notifications = ListNotifications::run();

        $this->assertEquals(1, $notifications[0]->read);
    }

    public function testHandleOnlyNotifications()
    {
        User::factory()->hasSupportMessages(5)->create();
        SupportMessage::factory()->notification()->count(3)->create();

        $notifications = ListNotifications::run();

        $this->assertCount(3, $notifications);
    }

    public function test_list_notifications_empty()
    {
        $this->actingAs($this->admin)
            ->get('/notifications')
            ->assertOk()
            ->assertViewHas('notifications');
    }

    public function test_list_notifications()
    {
        $notifications = SupportMessage::factory()->notification()->count(3)->create();

        $this->actingAs($this->admin)
            ->get('/notifications')
            ->assertOk()
            ->assertSee($notifications[0]->message)
            ->assertSee($notifications[1]->message)
            ->assertSee($notifications[2]->message);
    }

    public function test_list_notifications__only()
    {
        $user = User::factory()->hasSupportMessages(1)->create();
        $notification = SupportMessage::factory()->notification()->create();

        $this->actingAs($this->admin)
            ->get('/notifications')
            ->assertOk()
            ->assertDontSee($user->supportMessages[0]->message)
            ->assertSee($notification->message);
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->get('/notifications')
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->get('/notifications')
            ->assertRedirect('/login');
    }

}

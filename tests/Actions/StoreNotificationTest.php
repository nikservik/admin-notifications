<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use Nikservik\AdminNotifications\Actions\StoreNotification;
use Nikservik\AdminNotifications\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class StoreNotificationTest extends TestCase
{
    public function testHandle()
    {
        $notification = StoreNotification::run('test message');

        $this->assertEquals('test message', $notification->message);
        $this->assertEquals('notification', $notification->type);
    }

    public function test_create_notification()
    {
        $this->actingAs($this->admin)
            ->post('/notifications/', [
                'message' => 'test message',
            ])
            ->assertStatus(302);

        $this->assertEquals(1, SupportMessage::where('type', 'notification')->count());
    }

    public function test_dont_create_empty_notification()
    {
        $this->actingAs($this->admin)
            ->post('/notifications/', [
                'message' => '',
            ])
            ->assertStatus(302)
            ->assertSessionHasErrors('message');

        $this->assertEquals(0, SupportMessage::where('type', 'notification')->count());
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->post('/notifications/', [
                'message' => '',
            ])
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->post('/notifications/', [
                'message' => '',
            ])
            ->assertRedirect('/login');
    }

}

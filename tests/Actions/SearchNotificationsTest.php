<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use App\Models\User;
use Nikservik\AdminNotifications\Actions\SearchNotifications;
use Nikservik\AdminNotifications\Tests\TestCase;
use Nikservik\SimpleSupport\Models\SupportMessage;

class SearchNotificationsTest extends TestCase
{

    public function testHandleEmpty()
    {
        $notifications = SearchNotifications::run('test');

        $this->assertCount(0, $notifications);
    }

    public function testHandle()
    {
        SupportMessage::factory()->notification()->count(3)->create(['message' => 'test message']);
        SupportMessage::factory()->notification()->count(3)->create(['message' => 'other message']);

        $notifications = SearchNotifications::run('test');

        $this->assertCount(3, $notifications);
    }

    public function testHandleOnlyNotifications()
    {
        User::factory()->has(
            SupportMessage::factory()->state(['message' => 'test message'])
        )->create();
        SupportMessage::factory()->notification()->create(['message' => 'test notification']);

        $notifications = SearchNotifications::run('test');

        $this->assertCount(1, $notifications);
    }

    public function test_search_notifications_empty()
    {
        $this->actingAs($this->admin)
            ->get('/notifications/search?q=test')
            ->assertOk()
            ->assertViewHas('notifications');
    }

    public function test_search_notifications()
    {
        User::factory()->has(
            SupportMessage::factory()->state(['message' => 'test message'])
        )->create();
        SupportMessage::factory()->notification()->create(['message' => 'test notification']);

        $this->actingAs($this->admin)
            ->get('/notifications/search?q=test')
            ->assertOk()
            ->assertSee('test notification')
            ->assertDontSee('test message');
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->get('/notifications/search?q=test')
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->get('/notifications/search?q=test')
            ->assertRedirect('/login');
    }

}

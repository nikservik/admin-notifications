<?php

namespace Nikservik\AdminNotifications\Tests\Actions;

use Nikservik\AdminNotifications\Actions\CreateNotification;
use Nikservik\AdminNotifications\Tests\TestCase;

class CreateNotificationTest extends TestCase
{

    public function test_edit_message()
    {
        $this->actingAs($this->admin)
            ->get("/notifications/create")
            ->assertOk();
    }

    public function test_redirect_when_not_authenticated()
    {
        $this->get("/notifications/create")
            ->assertRedirect('/login');
    }

    public function test_redirect_when_not_admin()
    {
        $this->actingAs($this->user)
            ->get("/notifications/create")
            ->assertRedirect('/login');
    }

}

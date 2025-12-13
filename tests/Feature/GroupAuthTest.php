<?php

namespace Tests\Feature;

use Tests\TestCase;

class GroupAuthTest extends TestCase
{
    public function test_guest_cannot_access_groups_index(): void
    {
        $this->get('/groups')->assertRedirect('/login');//ok
    }

    public function test_guest_cannot_access_groups_create(): void
    {
        $this->get('/groups/create')->assertRedirect('/login');
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;

class NoPublicDatabaseResetTest extends TestCase
{
    public function test_there_is_no_public_route_that_resets_the_database(): void
    {
        $this->get('/fix-db')->assertNotFound();
    }
}

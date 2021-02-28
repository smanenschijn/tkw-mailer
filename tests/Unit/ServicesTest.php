<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ServicesTest extends TestCase
{
    /**
     * Tests if api throws an error when no recipients are sent
     *
     * @return void
     */
    public function test_throws_an_exception_when_failed_to_send()
    {
        // @todo implement test
        $this->assertTrue(true);
    }

    public function test_falls_back_on_another_service_when_sending_fails()
    {
        // @todo implement test;
        $this->assertTrue(true);
    }



}

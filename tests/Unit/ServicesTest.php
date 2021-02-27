<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CreateMessageResponseTestTest extends TestCase
{
    /**
     * Tests if api throws an error when no recipients are sent
     *
     * @return void
     */
    public function test_returns_an_error_when_message_data_fails_to_validate()
    {
        $messageData = [
            'subject' => 'default subject',
            'body' => 'default_body'
        ];

        $response = $this->postJson('/api/email/message', $messageData);

        $response->assertStatus(500);
    }



}

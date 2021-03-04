<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

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


    /**
     * Tests if an email is saved to the database after receiving a correctly formatted json
     */
    public function test_it_saves_a_record_to_the_database_when_a_correct_json_is_delivered()
    {
        $messageData = [
            'recipients' => ['test@example.com'],
            'subject' => 'default subject',
            'body' => 'default_body'
        ];

        Http::fake();

        $this->postJson('/api/email/message', $messageData);

        $this->assertDatabaseCount('emails', 1);
    }

}

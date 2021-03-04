<?php

namespace Tests\Unit;

use App\Events\MessageSent;
use App\Fallback\CircuitBreakerInterface;
use App\Mail\Mailer;
use App\Mail\MailerInterface;
use App\Mail\StubMailer;
use App\Mail\StubService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class MailerTest extends TestCase
{

    /**
     * @test
     */
    public function it_throws_an_all_services_failed_exception_after_failed_delivery()
    {
        Http::fake();
        $this->mock(CircuitBreakerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('isAvailable')->andReturn(false);
        });

        $this->expectExceptionMessage('All services are currently unavailable');

        $mailer = resolve(Mailer::class);

        $mailer->send(1);

    }

    /**
     * @test
     */
    public function it_registers_a_failed_attempt_after_a_message_failed()
    {
        $mock = $this->partialMock(CircuitBreakerInterface::class, function (MockInterface $mock) {
            $mock->shouldReceive('isAvailable')->andReturn(true);
            $mock->shouldReceive('registerFailedAttempt')->once();
        });

        $this->app->bind(MailerInterface::class, StubMailer::class);

        $mailer = resolve(MailerInterface::class);

        $mailer->send(2);

        $mock->shouldHaveReceived('registerFailedAttempt');
    }

    /**
     * @test
     */
    public function it_triggers_a_message_sent_event_after_a_successful_message()
    {
        Event::fake();

        $this->app->bind(MailerInterface::class, StubMailer::class);
        $mailer = resolve(MailerInterface::class);

        $mailer->send(1);

        Event::assertDispatched(MessageSent::class);
    }
}

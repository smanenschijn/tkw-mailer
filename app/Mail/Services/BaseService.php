<?php

namespace App\Mail\Services;

use App\Models\Email;
use App\Repositories\EmailRepositoryInterface;
use Illuminate\Cache\RateLimiter;

class BaseService
{
    /**
     * @var RateLimiter
     */
    protected RateLimiter $rateLimiter;
    /**
     * @var EmailRepositoryInterface
     */
    private EmailRepositoryInterface $emailRepository;

    /**
     * BaseService constructor.
     * @param RateLimiter $rateLimiter
     * @param EmailRepositoryInterface $emailRepository
     */
    public function __construct(RateLimiter $rateLimiter, EmailRepositoryInterface $emailRepository)
    {

        $this->emailRepository = $emailRepository;
        $this->rateLimiter = $rateLimiter;
    }

    protected function getEmail(int $emailId): ?Email
    {
        return $this->emailRepository->find($emailId);
    }
}

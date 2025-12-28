<?php

namespace NH\Notification\Traits;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

trait NhtRateLimiter
{
    /**
     * @param string $key
     * @param int $maxAttempts
     * @param int $decaySeconds
     * @param string $message
     * @return void
     */
    private static function throttle(string $key, int $maxAttempts, int $decaySeconds, string $message): void
    {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            throw new ThrottleRequestsException($message, null, [
                'Retry-After' => $retryAfter,
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);
    }

    /**
     * @param string $prefix
     * @param int|null $userId
     * @param string|null $method
     * @return string
     */
    private static function key(string $prefix, ?int $userId, ?string $method): string
    {
        $scope = [];

        if ($method) {
            $scope[] = "method:{$method}";
        }

        if ($userId) {
            $scope[] = "user:{$userId}";
        }

        if (empty($scope)) {
            $scope[] = 'global';
        }

        return $prefix . ':' . implode('|', $scope);
    }
}

<?php

namespace App\Integrations\Core\Authentication;

use Illuminate\Support\Facades\Cache;

class TokenCache
{
    public function get(string $key): ?AccessToken
    {
        $token = Cache::get($key);

        return $token instanceof AccessToken
            ? $token
            : null;
    }

    public function put(
        string $key,
        AccessToken $token,
    ): void {
        Cache::put(
            $key,
            $token,
            now()->addSeconds(
                max(
                    1,
                    $token->expiresIn - 30
                )
            )
        );
    }

    public function forget(string $key): void
    {
        Cache::forget($key);
    }
}

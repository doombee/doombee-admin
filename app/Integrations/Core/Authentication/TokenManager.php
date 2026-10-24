<?php

namespace App\Integrations\Core\Authentication;

use App\Integrations\Core\Authentication\Contracts\TokenProvider;

class TokenManager
{
    public function __construct(
        protected string $cacheKey,
        protected TokenProvider $provider,
        protected TokenCache $cache = new TokenCache(),
    ) {}

    /**
     * @throws \DateMalformedStringException
     */
    public function token(): string
    {
        $token = $this->cache->get($this->cacheKey);

        if ($token instanceof AccessToken) {

            if (! $token->isExpired()) {
                return $token->token;
            }

            $this->cache->forget(
                $this->cacheKey
            );
        }

        $token = $this->provider->fetch();

        $this->cache->put(
            $this->cacheKey,
            $token
        );

        return $token->token;
    }

    public function refresh(): string
    {
        $this->cache->forget(
            $this->cacheKey
        );

        return $this->token();
    }
}

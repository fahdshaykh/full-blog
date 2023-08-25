<?php

namespace App\Services;

use App\Contracts\CounterContract;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class Counter implements CounterContract {

    private $timeout;
    private $cache;
    private $session;
    private $supportTags;

    public function __construct(Cache $cache, Session $session, int $timeout)
    {
        $this->timeout = $timeout;
        $this->cache = $cache;
        $this->session = $session;
        $this->supportTags = method_exists($cache, 'tags');
    }

    public function increment(string $key, array $tags = null): int
    {
        // dump($this->session);
        // dd($this->cache);
        $sessionId = $this->session->getId();
        $counterKey = "{$key}-counter";
        $usersKey = "{$key}-users";

        $cache = $this->supportTags && null != $tags 
        ? $this->cache->tags($tags) : $this->cache;

        $users = $cache->get($usersKey, []);
        $usersUpdate = [];
        $diffrence = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if($now->diffInMinutes($lastVisit) >= $this->timeout) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if (
            !array_key_exists($sessionId, $users) 
            || $now->diffInMinutes($users[$sessionId]) >= $this->timeout
        ) {
            $diffrence++;
        }

        $usersUpdate[$sessionId] = $now;
        $cache->forever($usersKey, $usersUpdate);

        if(!$cache->has($counterKey)) {
            $cache->forever($counterKey, 1);
        } else {
            $cache->increment($counterKey, $diffrence);
        }

        $counter = $cache->get($counterKey);

        return $counter;
    }
}
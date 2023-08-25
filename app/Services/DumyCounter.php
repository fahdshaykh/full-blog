<?php 

namespace App\Services;

use App\Contracts\CounterContract;

class DumyCounter implements CounterContract {
    public function increment(string $key, array $tags = null): int
    {
        dump('hello im a dumy contact');

        return 0;
    }
}
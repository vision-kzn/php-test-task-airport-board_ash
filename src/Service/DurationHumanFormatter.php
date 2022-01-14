<?php

namespace App\Service;

class DurationHumanFormatter
{
    private int $minutes;

    public function __construct(int $minutes)
    {
        $this->minutes = $minutes;
    }

    public function __toString(): string
    {
        if(!$this->minutes) {
            return '0m';
        }

        $symbol = $this->minutes < 0 ? '-' : '';

        $hours = floor($this->minutes / 60);
        $minutes = $this->minutes % 60;

        $timeParts = [];
        if($hours) {
            $timeParts[] = abs($hours).'h';
        }
        if($minutes) {
            $timeParts[] = abs($minutes).'m';
        }

        return $symbol.implode(':', $timeParts);
    }
}
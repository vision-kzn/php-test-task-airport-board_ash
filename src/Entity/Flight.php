<?php

namespace App\Entity;

class Flight
{
    private Airport $fromAirport;
    private string $fromTime;
    private Airport $toAirport;
    private string $toTime;

    public function __construct(Airport $fromAirport, string $fromTime, Airport $toAirport, string $toTime)
    {
        $this->fromAirport = $fromAirport;
        $this->fromTime = $fromTime;
        $this->toAirport = $toAirport;
        $this->toTime = $toTime;
    }

    public function getFromAirport(): Airport
    {
        return $this->fromAirport;
    }

    public function getFromTime(): string
    {
        return $this->fromTime;
    }

    public function getToAirport(): Airport
    {
        return $this->toAirport;
    }

    public function getToTime(): string
    {
        return $this->toTime;
    }

    public function calculateDurationMinutes(): int
    {
        $preResult = $this->calculateMinutesFromStartDay($this->toTime) - $this->calculateMinutesFromStartDay($this->fromTime);

        if ($preResult < 0) {
            $preResult =  $preResult + 1440;
        }

        $result = $preResult + $this->addTimeZoneCalculation();

        if ($result > 1440 ) {
            return $result - 1440;
        }
        elseif ($result < 0) {
            return $result + 1440;
        }
        else {
            return $result;
        }
    }

    private function calculateMinutesFromStartDay(string $time): int
    {
        [$hour, $minutes] = explode(':', $time, 2);

        return 60 * (int) $hour + (int) $minutes;
    }

    private function parseTimeZone(string $timeZone): int
    {
        if (str_contains($timeZone, '+')) {
            return (int) substr($timeZone, -2);
        }
        else {
            return -(int) substr($timeZone, -2);
        }
    }

    private function addTimeZoneCalculation(): int
    {
        $timeZoneFrom = $this->parseTimeZone($this->fromAirport->getTimeZone());
        $timeZoneTo = $this->parseTimeZone($this->toAirport->getTimeZone());

        return 60 * ($timeZoneFrom - $timeZoneTo);
    }
}
<?php

namespace App\Service;

use App\Repository\AirportRepository;
use App\Repository\FlightRepository;

class ServiceBuilder
{
    private string $sourceDir;

    public function __construct(string $sourceDir)
    {
        $this->sourceDir = $sourceDir;
    }

    public function buildFlightRepository(): FlightRepository
    {
        return new FlightRepository(
            $this->buildJsonParser(),
            $this->buildAirportRepository()
        );
    }

    private function buildJsonParser(): JsonParser
    {
        return new JsonParser($this->sourceDir);
    }

    private function buildAirportRepository(): AirportRepository
    {
        return new AirportRepository(
            $this->buildJsonParser()
        );
    }
}
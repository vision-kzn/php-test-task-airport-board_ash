<?php

namespace App\Repository;

use App\Entity\Flight;
use App\Service\JsonParser;

class FlightRepository extends AbstractRepository
{
    private const FROM_DATA_KEY = 'from';
    private const TO_DATA_KEY = 'to';
    private const AIRPORT_DATA_KEY = 'airport';
    private const TIME_DATA_KEY = 'time';

    private JsonParser $jsonParser;
    private AirportRepository $airportRepository;

    public function __construct(JsonParser $jsonParser, AirportRepository $airportRepository)
    {
        $this->jsonParser = $jsonParser;
        $this->airportRepository = $airportRepository;
    }

    public function getAll(): array
    {
        return $this->getData();
    }

    protected function loadData(): array
    {
        return array_map(
            function(array $flightData): Flight {
                return $this->buildFlight($flightData);
            },
            $this->jsonParser->load('flight.json')
        );
    }

    private function buildFlight(mixed $flightData): Flight
    {
        $this->assertKeysExists($flightData, [self::FROM_DATA_KEY, self::TO_DATA_KEY]);
        [self::FROM_DATA_KEY => $fromData, self::TO_DATA_KEY => $toData] = $flightData;
        $this->assertKeysExists($fromData, [self::AIRPORT_DATA_KEY, self::TIME_DATA_KEY]);
        $this->assertKeysExists($toData, [self::AIRPORT_DATA_KEY, self::TIME_DATA_KEY]);

        return new Flight(
            $this->airportRepository->getAirport($fromData[self::AIRPORT_DATA_KEY]),
            $fromData[self::TIME_DATA_KEY],
            $this->airportRepository->getAirport($toData[self::AIRPORT_DATA_KEY]),
            $toData[self::TIME_DATA_KEY]
        );
    }
}
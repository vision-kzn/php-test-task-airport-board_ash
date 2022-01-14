<?php

namespace App\Entity;

class Airport
{
    private string $code;
    private string $name;
    private string $city;

    public function __construct(string $code, string $name, string $city)
    {
        $this->code = $code;
        $this->name = $name;
        $this->city = $city;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCity(): string
    {
        return $this->city;
    }
}
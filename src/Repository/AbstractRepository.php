<?php

namespace App\Repository;

abstract class AbstractRepository
{
    private ?array $data = null;

    abstract protected function loadData(): array;

    protected function getData(): array
    {
        if($this->data === null) {
            $this->data = $this->loadData();
        }

        return $this->data;
    }

    protected function assertKeysExists(array $data, array $requiredKeys): void
    {
        $dataKeys = array_keys($data);
        $missingKeys = array_diff($requiredKeys, $dataKeys);
        if(count($missingKeys)) {
            throw new \Exception(sprintf(
                'Required keys "%s" is empty. Available keys: "%s".',
                implode('", "', $missingKeys),
                implode('", "', $dataKeys)
            ));
        }
    }
}
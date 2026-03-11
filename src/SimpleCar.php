<?php

declare(strict_types=1);

namespace GrftTestSimpleCar;

use GrftTestSimpleCar\Utils\Uuid;

final class SimpleCar
{
    private string $id;
    private string $make;
    private string $model;
    private int $year;
    private bool $isRunning = false;

    public function __construct(string $make, string $model, int $year, ?string $id = null)
    {
        $this->id = ($id !== null && trim($id) !== '') ? $id : Uuid::generateUuid();
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMake(): string
    {
        return $this->make;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getWheels(): int
    {
        return 4;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function start(): void
    {
        $this->isRunning = true;
    }

    public function stop(): void
    {
        $this->isRunning = false;
    }

    public function __toString(): string
    {
        $status = $this->isRunning ? 'Running' : 'Not Running';

        return sprintf(
            '%s %d %s %s (SimpleCar) %s',
            $this->id,
            $this->year,
            $this->make,
            $this->model,
            $status
        );
    }
}

<?php

declare(strict_types=1);

namespace GrftTestSimpleCar;

/**
 * Contract for any vehicle with basic identity, state, and engine control.
 */
interface VehicleInterface
{
    public function getId(): string;
    public function getMake(): string;
    public function getModel(): string;
    public function getYear(): int;
    public function getWheels(): int;
    public function isRunning(): bool;
    public function start(): void;
    public function stop(): void;
}

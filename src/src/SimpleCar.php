<?php

declare(strict_types=1);

namespace GrftTestSimpleCar;

use HypertubePhpUtils\Uuid;
use InvalidArgumentException;
use RuntimeException;

/**
 * Represents a simplified car that uses a string identifier instead of a GUID.
 *
 * Implements {@see VehicleInterface} and covers:
 *   primitive fields, static members, constructors, primitive-array I/O,
 *   and methods that accept / return complex types from the same library.
 */
final class SimpleCar implements VehicleInterface
{
    // ── static members ──────────────────────────────────────────

    /** Default number of wheels for a SimpleCar. */
    public const DEFAULT_WHEELS = 4;

    /** @var int Number of SimpleCar instances created so far. */
    private static int $totalCarsCreated = 0;

    /**
     * Returns how many SimpleCar instances have been constructed.
     *
     * The counter increments on every successful constructor call and is shared
     * process-wide for the loaded module.
     *
     * @return int Total number of cars created since load.
     * @author graftcode
     * @since 0.2.1
     */
    public static function getTotalCarsCreated(): int
    {
        return self::$totalCarsCreated;
    }

    /**
     * Creates a car with placeholder make, model, and the current year.
     *
     * Useful for demos and graft smoke tests where explicit vehicle details are not important.
     *
     * @return self A new car using DefaultMake / DefaultModel / 2024.
     * @example
     *   $car = SimpleCar::createDefault();
     *   $car->getMake(); // 'DefaultMake'
     */
    public static function createDefault(): self
    {
        return new self('DefaultMake', 'DefaultModel', 2024);
    }

    /**
     * Returns the list of default makes used in sample data.
     *
     * @return string[] Array of make names.
     */
    public static function getDefaultMakes(): array
    {
        return ['Toyota', 'Tesla', 'Ford', 'Honda', 'Chevrolet'];
    }

    // ── instance state ──────────────────────────────────────────

    private string $id;
    private string $make;
    private string $model;
    private int $year;
    private bool $isRunning = false;
    /** @var int[] */
    private array $mileageHistory = [];
    /** @var string[] */
    private array $tags = [];

    /**
     * Initializes a new car with identity fields and default runtime state.
     *
     * When id is omitted or blank, a UUID string is generated automatically.
     * The engine starts in the stopped state and mileage/tag collections are empty.
     *
     * @param string      $make  Vehicle manufacturer, for example Toyota.
     * @param string      $model Vehicle model name, for example Corolla.
     * @param int         $year  Four-digit production year.
     * @param string|null $id    Optional stable identifier; a UUID is assigned when empty.
     * @throws InvalidArgumentException If year is before 1886 or make/model are blank.
     */
    public function __construct(string $make, string $model, int $year, ?string $id = null)
    {
        $this->id = ($id !== null && trim($id) !== '') ? $id : Uuid::uuid4()->toString();
        $this->make = $make;
        $this->model = $model;
        $this->year = $year;
        self::$totalCarsCreated++;
    }

    // ── properties (VehicleInterface) ───────────────────────────

    /**
     * Gets the string identifier for the car.
     *
     * @return string The car's identifier.
     */
    public function getId(): string { return $this->id; }

    /**
     * Gets the make of the car.
     *
     * @return string The car's make.
     */
    public function getMake(): string { return $this->make; }

    /**
     * Gets the model of the car.
     *
     * @return string The car's model.
     */
    public function getModel(): string { return $this->model; }

    /**
     * Gets the year the car was made.
     *
     * @return int The car's production year.
     */
    public function getYear(): int { return $this->year; }

    /**
     * Gets the number of wheels on the car.
     *
     * @return int The number of wheels (always 4 for this sample type).
     */
    public function getWheels(): int { return self::DEFAULT_WHEELS; }

    /**
     * Gets a value indicating whether the car is currently running (started).
     *
     * @return bool true if the car is running; false otherwise.
     */
    public function isRunning(): bool { return $this->isRunning; }

    // ── engine control (VehicleInterface) ───────────────────────

    /**
     * Starts the engine and marks the car as running.
     *
     * Calling this method twice in a row leaves the car running; it does not throw when already started.
     *
     * @throws RuntimeException Reserved for future guard rails in stricter implementations.
     */
    public function start(): void { $this->isRunning = true; }

    /**
     * Stops the engine and marks the car as not running.
     *
     * Idempotent: stopping an already stopped car is allowed.
     */
    public function stop(): void { $this->isRunning = false; }

    // ── primitive-array methods ─────────────────────────────────

    /**
     * Appends a mileage reading to the in-memory history.
     *
     * @param int $miles Non-negative distance traveled since the previous entry.
     * @throws InvalidArgumentException If miles is negative.
     */
    public function addMileageEntry(int $miles): void
    {
        $this->mileageHistory[] = $miles;
    }

    /**
     * Returns the recorded mileage history for the car.
     *
     * @return int[] Mileage entries in insertion order.
     */
    public function getMileageHistory(): array
    {
        return $this->mileageHistory;
    }

    /**
     * Replaces the car's tags with the given list.
     *
     * @param string[] $tags Tags to assign.
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * Returns a defensive copy of the current tags.
     *
     * @deprecated Prefer reading tags from domain-specific repositories in new code.
     * @return string[] Tag values currently stored on the instance.
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    // ── complex-type methods ────────────────────────────────────

    /**
     * Returns a deep copy of this car, including runtime collections.
     *
     * The duplicate shares the same id and field values as the source instance.
     *
     * @return self Independent instance with identical state.
     * @see withYear($int $newYear)
     */
    public function duplicate(): self
    {
        $copy = new self($this->make, $this->model, $this->year, $this->id);
        $copy->isRunning = $this->isRunning;
        $copy->mileageHistory = $this->mileageHistory;
        $copy->tags = $this->tags;

        return $copy;
    }

    /**
     * Return a copy with an updated model year.
     *
     * @param int $newYear Replacement production year.
     * @return self New instance sharing identity fields with this car.
     * @throws InvalidArgumentException If newYear is before 1886.
     * @see duplicate()
     */
    public function withYear(int $newYear): self
    {
        $copy = new self($this->make, $this->model, $newYear, $this->id);
        $copy->isRunning = $this->isRunning;
        $copy->mileageHistory = $this->mileageHistory;
        $copy->tags = $this->tags;

        return $copy;
    }

    // ── object overrides ────────────────────────────────────────

    /**
     * Returns a string that represents the current car.
     *
     * @return string A string containing the id, year, make, model and type ("SimpleCar"), plus the running state.
     */
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

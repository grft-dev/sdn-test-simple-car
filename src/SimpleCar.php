<?php

declare(strict_types=1);

namespace GrftTestSimpleCar;

use GrftTestSimpleCar\Utils\Uuid;

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
     * Returns the total number of SimpleCar instances created so far.
     *
     * @return int Total instances created.
     */
    public static function getTotalCarsCreated(): int
    {
        return self::$totalCarsCreated;
    }

    /**
     * Creates a default SimpleCar instance with placeholder make/model/year.
     *
     * @return self A newly created default SimpleCar.
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
     * Initializes a new instance of the SimpleCar class.
     *
     * @param string      $make  The make of the car.
     * @param string      $model The model of the car.
     * @param int         $year  The year the car was made.
     * @param string|null $id    The string identifier for the car. If null or empty, a new UUID string is generated.
     */
    public function __construct(string $make, string $model, int $year, ?string $id = null)
    {
        $this->id = ($id !== null && trim($id) !== '') ? $id : Uuid::generateUuid();
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
     * Starts the car.
     */
    public function start(): void { $this->isRunning = true; }

    /**
     * Stops the car.
     */
    public function stop(): void { $this->isRunning = false; }

    // ── primitive-array methods ─────────────────────────────────

    /**
     * Appends a new mileage entry to the car's mileage history.
     *
     * @param int $miles Miles to record.
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
     * Returns the tags currently assigned to the car.
     *
     * @return string[] The car's tags.
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    // ── complex-type methods ────────────────────────────────────

    /**
     * Returns a deep copy of this SimpleCar, including state and collections.
     *
     * @return self A new SimpleCar with the same data.
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
     * Returns a copy of this SimpleCar with the production year replaced.
     *
     * @param int $newYear The new production year.
     * @return self A new SimpleCar with the updated year.
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

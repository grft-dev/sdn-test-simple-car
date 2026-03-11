# sdn/test-simple-car

Simple car abstraction — a lightweight PHP class representing a car.

## Requirements

- PHP >= 7.4

## Install

```bash
composer require sdn/test-simple-car
```

## Usage

```php
use GrftTestSimpleCar\SimpleCar;

$car = new SimpleCar('Toyota', 'Corolla', 2020);
$car->start();

echo $car;
// e.g.: 550e8400-e29b-41d4-a716-446655440000 2020 Toyota Corolla (SimpleCar) Running
```

You can also provide a custom ID:

```php
$car = new SimpleCar('Honda', 'Civic', 2021, 'my-custom-id');
echo $car->getId();
```

## License

MIT

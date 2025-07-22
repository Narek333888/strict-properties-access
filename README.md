# StrictPropertiesAccess

Enforce strict control over property access in your PHP classes. Prevent accidental creation of dynamic properties and provide detailed handling for invalid property interactions using customizable logging and observation tools.

---

## 📦 Package Overview

**StrictPropertiesAccess** provides:

- A trait (`StrictPropertyAccess`) for strict property access enforcement.
- An abstract class (`AbstractStrictModel`) to simplify inheritance.
- Interfaces for customization: `LoggerInterface`, `PropertyAccessObserverInterface`.
- Built-in implementations: `ErrorLogger`, `DebugObserver`.

---

## 📂 Installation

Install via Composer:

```bash
composer require armdevstack/strict-properties-access
```

---

## 🚀 Quick Start

### ✅ Recommended: Extend the Abstract Class

```php
use ArmDevStack\StrictPropertiesAccess\Classes\Base\AbstractStrictModel;

class User extends AbstractStrictModel
{
    public string $name;
    public string $email;
}

$user = new User();
$user->name = 'John';     // ✅ OK
echo $user->email;        // ✅ OK
echo $user->age;          // ❌ Triggers observer/log/exception
```

### ⚙️ Alternative: Use the Trait Directly

```php
use ArmDevStack\StrictPropertiesAccess\Traits\StrictPropertyAccess;

class Product
{
    use StrictPropertyAccess;

    public string $title;
}

$product = new Product();
$product->title = 'Laptop';       // ✅ OK
echo $product->price;             // ❌ Invalid access
```

---

## ⚙️ Features

### 🔐 Strict Mode

- Enabled by default.
- Disables dynamic properties.
- Triggers warning/observer/exception on access to undefined properties.

### 📢 Error Handling Modes

- **Echo:** Output messages directly.
- **Log:** Send to error log (via logger).
- **Both (default):** Echo + log.

### 🔧 Observers & Loggers

Attach custom loggers or observers to control error behavior or monitor access attempts.

---

## 🛠️ Configuration API

### Enable/Disable Strict Mode

```php
$object->enableStrictMode();    // Enable strict enforcement
$object->disableStrictMode();   // Disable enforcement
```

### Enable/Disable Exceptions

```php
$object->enableExceptions();    // Throws LogicException on invalid access
$object->disableExceptions();   // Echo/log instead
```

### Set Logger

```php
use ArmDevStack\StrictPropertiesAccess\Loggers\ErrorLogger;

$logger = new ErrorLogger();
$object->setLogger($logger);
```

### Set Observer

```php
use ArmDevStack\StrictPropertiesAccess\Observers\DebugObserver;

$observer = new DebugObserver();
$object->setPropertyAccessObserver($observer);
```

### Set Error Output Mode

```php
$object->setErrorOutputMode('echo'); // echo | log | both
```

---

## 📤 Debugging Tools

### Track Invalid Accesses

```php
$invalid = $object->getInvalidAccesses();
print_r($invalid);
```

### 🔍 Custom Handler: `handleMissingProperty()`

If your class defines a method named `handleMissingProperty(string $property)`, it will be automatically invoked when a non-existent property is accessed.

This allows you to override the default error behavior with custom logic:

```php
class MyModel extends AbstractStrictModel
{
    public string $title;

    protected function handleMissingProperty(string $property): void
    {
        echo "Custom handler: '$property' was accessed but does not exist!" . PHP_EOL;
    }
}

$model = new MyModel();
echo $model->nonExistent; // Triggers handleMissingProperty()
```

- This method is **optional**.
- Only triggered if strict mode is enabled and property does not exist.
- Takes precedence over observers and default echo/log/error behavior.

---

## 🧩 Interfaces

### `StrictPropertyAccessInterface`

```php
public function enableStrictMode(): void;
public function disableStrictMode(): void;
public function enableExceptions(): void;
public function disableExceptions(): void;
public function getInvalidAccesses(): array;
```

### `LoggerInterface`

```php
public function log(string $message): void;
```

### `PropertyAccessObserverInterface`

```php
public function onMissingProperty(string $property);
public function onDynamicPropertyCreationAttempt(string $property, $value);
```

---

## 🏗️ Extend with Your Own Classes

### Custom Observer Example

```php
use ArmDevStack\StrictPropertiesAccess\Contracts\Observers\PropertyAccessObserverInterface;

class CustomObserver implements PropertyAccessObserverInterface
{
    public function onMissingProperty(string $property)
    {
        // Your custom logic
    }

    public function onDynamicPropertyCreationAttempt(string $property, $value)
    {
        // Custom alerting/logging
    }
}
```

---

## 📌 Example Use Case: Preventing Bugs in DTOs

```php
class PaymentDTO extends AbstractStrictModel
{
    public string $amount;
    public string $currency;
}

$dto = new PaymentDTO();
$dto->amount = '100';
// Oops! Typo
$dto->currncy = 'USD';  // ❌ Will trigger strict mode warning/exception
```

---

## 🧪 Unit Testing Tip

For test environments, you can disable strict mode:

```php
$dto->disableStrictMode();
```

---

## 🧾 License

MIT © [ArmDevStack](https://github.com/armdevstack)

---

## 🙌 Contributing

Pull requests are welcome. For major changes, please open an issue first.

---

## 📫 Support

For bugs or suggestions, open an issue on GitHub or contact [vardapetyannarek0@gmail.com](mailto:svardapetyannarek0@gmail.com).

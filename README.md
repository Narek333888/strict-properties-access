
# 📦 StrictPropertyAccess Trait

The `StrictPropertyAccess` trait is designed to **enforce strict control over property access** in PHP classes. It prevents **accidental creation of dynamic properties**, helps catch **typos in property names**, and optionally **throws exceptions** or **logs access violations**.

> ✅ Useful for clean, maintainable, and bug-resistant OOP design.

---

## ✨ Features

- ❌ Prevents dynamic property creation
- 🛡 Strict property access enforcement
- ⚠️ Optional exception throwing on errors
- 🧠 Detects and logs invalid property accesses
- 🧪 Fully customizable behavior (strict mode toggle, logging, and exceptions)

---

## 📦 Installation

Just include the trait in your project manually or via Composer (if packaged).

```php
use ArmDevStack\StrictPropertiesAccess\Traits\StrictPropertyAccess;
```

---

## 🚀 Usage

### Step 1: Include the Trait

```php
class User
{
    use StrictPropertyAccess;

    public string $name;
    public string $email;
}
```

### Step 2: Instantiate Your Class

```php
$user = new User();
```

### Step 3: Try Accessing Undefined Property

```php
echo $user->age;
```

**Output:**

```bash
Prop 'age' does not exist!!!
```

Or throws `LogicException`, depending on configuration.

---

## ⚙️ Configuration Options

### 🔒 Enable/Disable Strict Mode

```php
$user->disableStrictMode(); // Allow dynamic properties (default PHP behavior)
$user->enableStrictMode();  // Enforce strict access (default)
```

### 🚨 Enable/Disable Exception Throwing

```php
$user->enableExceptions();  // Will throw LogicException on errors
$user->disableExceptions(); // Will echo message and log error
```

---

## 🔍 Example with Logging

```php
class Product
{
    use StrictPropertyAccess;

    public string $title;
}

$product = new Product();

$product->price = 99.99; // This triggers warning or exception
```

---

## 🧠 Invalid Access Tracking

You can retrieve a list of all invalid property access attempts:

```php
$product->getInvalidAccesses(); // ['price']
```

---

## 🧩 Extendability: Custom Missing Property Handler

You can define a `handleMissingProperty` method in your class to add custom logic:

```php
class Post
{
    use StrictPropertyAccess;

    public string $title;

    protected function handleMissingProperty(string $prop)
    {
        echo "Custom handler: Property '$prop' is missing!";
    }
}
```

---

## ✅ Best Practices

- Use this trait in **DTOs**, **ViewModels**, or **Service classes** where strict control matters.
- Enables **early bug detection** by preventing typo-induced silent bugs.
- Works perfectly in **unit-tested systems** where strict object shape is expected.

---

## 📌 Notes

- Works with both **CLI** and **Web** environments (`PHP_EOL` or `<br>` used accordingly).
- Reflection is initialized once on instantiation for performance.
- Does **not** prevent method overloading or other OOP features.

---

## 🔧 Advanced: Internal Methods

| Method                      | Description                                  |
|----------------------------|----------------------------------------------|
| `__get()` / `__set()`      | Magic interceptors for access and assignment |
| `handleError($message)`    | Central error handler                        |
| `logAccessError($message)` | Logs error using `error_log()`               |
| `getInvalidAccesses()`     | Returns array of invalid property accesses   |
| `fillProperties()`         | Loads class properties using Reflection      |

---

## 📄 License

MIT License. Free for commercial and personal use.

---

## 👨‍💻 Author

Crafted by [ArmDevStack](https://github.com/ArmDevStack) with ❤️ for better object-oriented code.

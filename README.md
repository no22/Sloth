Sloth
================================================================
Sloth is a simple lazy application iterator for PHP 5.2 or later.

Setup
----------------------------------------------------------------

```php
    require "Sloth/Autoload.php";
```

Create iterator object
----------------------------------------------------------------
```php
    $iter = Sloth::iter($array_or_iterator);
```

or

```php
    $iter = Sloth::iter($initialValue, $functionCallback);
```

Lazy map
----------------------------------------------------------------

```php
    function foo($n)    
    {
        echo "foo called.\n";
        return $n * $n;
    }
    
    $iter = Sloth::iter(array(1,2,3,4,5))->map('foo');

    foreach ($iter as $e) {
        echo $e . "\n";
    }
```

```php
    foo called.
    1
    foo called.
    4
    foo called.
    9
    foo called.
    16
    foo called.
    25
```

Infinite sequense
----------------------------------------------------------------

for PHP 5.2
```php
    $even = Sloth::iter(0, Sloth::fn('$n + 2'));
    $evenLessThan10 = $even->takeWhile(Sloth::fn('$n < 10'));
    foreach ($evenLessThan10 as $e) {
        echo $e . "\n";
    }
```

for PHP 5.3
```php
    $even = Sloth::iter(0, function($n){return $n + 2;});
    $evenLessThan10 = $even->takeWhile(function($n){return $n < 10;});
    foreach ($evenLessThan10 as $e) {
        echo $e . "\n";
    }
```

License
------------------------------------------------------------------------
Sloth is dual Licensed MIT and GPLv3. You may choose the license that fits best for your project.

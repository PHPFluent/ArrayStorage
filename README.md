# PHPFluent\ArrayStorage
[![Build Status](https://secure.travis-ci.org/PHPFluent/ArrayStorage.png)](http://travis-ci.org/PHPFluent/ArrayStorage)
[![Total Downloads](https://poser.pugx.org/phpfluent/arraystorage/downloads.png)](https://packagist.org/packages/phpfluent/arraystorage)
[![License](https://poser.pugx.org/phpfluent/arraystorage/license.png)](https://packagist.org/packages/phpfluent/arraystorage)
[![Latest Stable Version](https://poser.pugx.org/phpfluent/arraystorage/v/stable.png)](https://packagist.org/packages/phpfluent/arraystorage)
[![Latest Unstable Version](https://poser.pugx.org/phpfluent/arraystorage/v/unstable.png)](https://packagist.org/packages/phpfluent/arraystorage)

Non-persistent way to use arrays as _database_.

## Installation

Package is available on [Packagist](https://packagist.org/packages/phpfluent/arraystorage), you can install it
using [Composer](http://getcomposer.org).

```bash
composer require phpfluent/arraystorage
```

# Usage

The examples below are using the following use statement at the beginning of the file:

```php
use PHPFluent\ArrayStorage\Storage;
```

## Creating and returning a collection

```php
$storage = new Storage();
$storage->users; // This is a collection
```

## Inserting records to a collection

You can use a single array:

```php
$storage = new Storage();
$storage->users->insert(array('name' => 'Henrique Moody'));
```

But you also can use a Record object:

```php
use PHPFluent\ArrayStorage\Record;

$storage = new Storage();
$record = new Record();
$record->name = 'Henrique Moody';

$storage->users->insert($record);
```

You can create a Record object from Storage object:

```php
$storage = new Storage();

$record = $storage->users->record(); // You also can provide default data, like an array or stdClass
$record->name = 'Henrique Moody';

$storage->users->insert($record);
```

An important point to note is that, after you insert the record to the collection object
it gives to the record an unique (incremental integer) `id` property.

## Removing records from a collection

```php
$storage = new Storage();
$storage->users->delete(array('name' => 'Henrique Moody'));
```

## Removing all records from a collection

```php
$storage = new Storage();
$storage->users->delete();
```

## Converting collection to an array

```php
$storage = new Storage();
$collectionData = $storage->users->toArray();
```

The example above convert all Record objects that are children of Record objects into an string, but returning its `id` property.

## Converting collection to an expanded array

```php
$storage = new Storage();
$collectionData = $storage->users->toArray();
```

The example above convert all Record objects into an array.

## Finding multiple records into a collection

```php
$storage->users->findAll(array('name' => 'Henrique Moody')); // Return an Collection object with the partial result (if any)
```

## Finding single record into a collection

```php
$storage->users->find(array('name' => 'Henrique Moody')); // Return an Record object with the first matched result (if any) or NULL
```

## Using Criteria object

```php
$criteria = $storage->users->criteria();
$criteria->foo->equalTo(2)
         ->bar->in(array(1, 2, 3))
         ->baz->regex('/^[0-9]{3}$/')
         ->qux->like('This _s spart%')
         ->quux->iLike('tHiS _S sPaRt%')
         ->corge->between(array(1, 42))
         ->grault->lessThan(1000)
         ->garply->greaterThan(0)
         ->waldo->notEqualTo(false)
         ->fred->greaterThanOrEqualTo(13);

$storage->users->find($criteria);
```

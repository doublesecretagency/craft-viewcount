---
description: If needed, you can reset a counter to zero, or set the value to any positive integer.
---

# Setting or resetting a counter

It's possible to both **reset a counter**, or to **set it to a specific value**.

:::tip Available in Twig/PHP only
These methods are not natively available in JavaScript or via AJAX. [See more below...](#javascript-ajax)
:::

## Methods

### `setCounter(newValue, elementId, key = null)`

Set the **new integer value**, alongside the Element ID and optional key.

### `resetCounter(elementId, key = null)`

Wrapper for `setCounter`, with a **new value of 0**.

## Twig

```twig
{# Set counter to 42 #}
{% do craft.viewCount.setCounter(42, entry.id) %}

{# Reset counter back to 0 #}
{% do craft.viewCount.resetCounter(entry.id) %}
```

## PHP

```php
use doublesecretagency\viewcount\ViewCount;

// Set counter to 42
ViewCount::$plugin->view->setCounter(42, $entry->id);

// Reset counter back to 0
ViewCount::$plugin->view->resetCounter($entry->id);
```

## JavaScript/AJAX

:::warning Not supported
For security reasons, these methods feature no out-of-the-box support for JS or AJAX.
:::

If your site requires the ability to set or reset a counter via JavaScript or AJAX, it will be up to you to:

1. Create your own module (or plugin).
2. Create a Controller action to handle the request. See the [IncrementController](https://github.com/doublesecretagency/craft-viewcount/blob/ff0f22bf4114498efa07430a3ac1fe8d39c5c2be/src/controllers/IncrementController.php#L49-L50) for a rough example.
3. From within your Controller action, call the PHP method you need.

Any other related behavior will be up to you, as the module developer, to write and maintain.

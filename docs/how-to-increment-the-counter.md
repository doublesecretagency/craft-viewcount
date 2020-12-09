---
description: There are three ways to increment a view counter... (1) On page load in Twig. (2) Manually via AJAX. (3) Manually via PHP.
---

# How to increment the counter

There are three easy ways to increment a view counter. Each method uses the same parameters:

| Parameter   | Description
|:------------|:------------
| `elementId` | The ID of whatever element (Entry, Asset, User, etc) you want to record views of.
| `key`       | (optional) A custom string to allow multiple view types for the same element.
| `userId`    | (optional) A specific user to associate this view with. Will default to the ID of the currently logged-in user, or null if not logged in.

---
---

## PHP - Call from a custom plugin or module

Under the hood, each method of incrementing the counter relies on this PHP function:

```php
ViewCount::$plugin->view->increment($elementId, $key = null, $userId = null);
```

---
---

## Twig - On page load

In its simplest form, you can easily increment the view counter for a given element:

```twig
{% do craft.viewCount.increment(elementId) %}
```

All parameters are available at the Twig level:

```twig
{% do craft.viewCount.increment(elementId, key, userId) %}
```

---
---

## JavaScript - Trigger via AJAX

Here's an example (using jQuery) of how to increment a view counter via AJAX:

```js
function incrementView(elementId, key) {

    // Set view data
    var data = {
        'id': elementId,
        'key': key
    };

    // Append CSRF Token
    data[window.csrfTokenName] = window.csrfTokenValue;

    // Render search results
    $.post(
        'actions/view-count/increment',
        data,
        function (response) {
            // Handle response
        }
    );

}
```

:::warning No userId for AJAX calls
For security reasons, **you cannot control the `userId` value when submitting via AJAX**. It will always default to the currently logged-in user, or _null_ if not logged in.

If you really need to override the `userId`, copy the `IncrementController::actionIndex` function into your own custom module.
:::

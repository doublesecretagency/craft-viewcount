---
description: There are three ways to increment a view counter... (1) On page load in Twig. (2) Manually via JavaScript. (3) Manually via PHP.
---

# How to increment the counter

There are three easy ways to increment a view counter. Each method uses the same parameters:

| Parameter   | Description
|:------------|:------------
| `elementId` | The ID of whatever element (Entry, Asset, User, etc) you want to record views of.
| `key`       | (optional) A custom string to allow multiple view types for the same element.
| `userId`    | (optional) A specific user to associate this view with. Will default to the ID of the currently logged-in user, or null if not logged in.

## Twig - On page load

In its simplest form, you can easily increment the view counter for a given element:

```twig
{% do craft.viewCount.increment(elementId) %}
```

All parameters are available at the Twig level:

```twig
{% do craft.viewCount.increment(elementId, key, userId) %}
```

## PHP - Call from a custom plugin or module

Under the hood, each method of incrementing the counter relies on this PHP function:

```php
ViewCount::$plugin->view->increment($elementId, $key = null, $userId = null);
```

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

    // Increment the view count
    $.post(
        'actions/view-count/increment',
        data,
        function (response) {
            // Handle response
        }
    );

}
```

## JavaScript - Trigger via Fetch (using Blitz)

Here's another example using vanilla Javascript to fetch a CSRF token from the Blitz plugin:

```js
// Action URLs
const csrfTokenUrl = '/actions/blitz/csrf/token';
const incrementViewCountUrl = '/actions/view-count/increment';

// Set view data
var data = {
    'id': elementId,
    'key': key
};

// Async function for incrementing the view count
async function incrementView() {
    try {
        
        // Fetch the CSRF token
        const csrfToken = await fetch(csrfTokenUrl).then(response => response.text());

        // Compile request data
        const requestData = {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken,
            },
        };

        // Get the response
        const response = await fetch(incrementViewCountUrl, requestData);
        const jsonResponse = await response.json();

        console.log(jsonResponse);

    } catch (error) {
        console.error(error);
    }
}

// Increment the view count
incrementView();
```

:::warning No userId for AJAX calls
For security reasons, **you cannot control the `userId` value when submitting via AJAX**. It will always default to the currently logged-in user, or _null_ if not logged in.

If you really need to override the `userId`, copy the `IncrementController::actionIndex` function into your own custom module.
:::

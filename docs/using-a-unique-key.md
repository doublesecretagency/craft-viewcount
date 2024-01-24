---
description: The optional `key` parameter allows you to keep track of different view types for the same element.
---

# Using a unique key

The optional `key` parameter allows you to keep track of different view types for the same element.

You set the key when [incrementing a view counter](/how-to-increment-the-counter/).

```twig
{% do craft.viewCount.increment(elementId, 'startedReading') %}
{% do craft.viewCount.increment(elementId, 'finishedReading') %}
{% do craft.viewCount.increment(elementId, 'watchedVideo') %}
```

And conversely, when you [get the view total](/get-view-totals-per-element/).

```twig
{{ craft.viewCount.total(elementId, 'startedReading') }}
{{ craft.viewCount.total(elementId, 'finishedReading') }}
{{ craft.viewCount.total(elementId, 'watchedVideo') }}
```

:::warning More than just a view counter
Because of the architecture of View Count, you can record arbitrary actions as "views". Simply set a custom `key` value which identifies your action!
:::

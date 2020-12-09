---
description: The optional `key` parameter allows you to keep track of different view types for the same element.
---

# Using a unique key

The optional `key` parameter allows you to keep track of different view types for the same element.

You set the key when [triggering a view](/how-to-increment-the-counter/).

```twig
{{ craft.viewCount.view(article.id, 'startedReading') }}
{{ craft.viewCount.view(article.id, 'finishedReading') }}
{{ craft.viewCount.view(article.id, 'watchedVideo') }}
```

:::warning More than just a view counter
Because of the architecture of View Count, you can record arbitrary actions as "views". Simply set a custom `key` value which identifies your action!
:::

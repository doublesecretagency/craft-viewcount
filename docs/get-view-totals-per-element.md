---
description: See an element's total view count via Twig or PHP.
---

# Get view totals per Element

You can easily retrieve the view count of an element in Twig...

```twig
craft.viewCount.total(elementId, key = null)
```

Or in PHP...

```php
ViewCount::$plugin->query->total($elementId, $key = null)
```

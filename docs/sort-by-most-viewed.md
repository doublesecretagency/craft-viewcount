---
description: You can sort your results to display the most viewed elements first. Fetch your Element Query just as you normally would, then pass it into the sort method.
---

# Sort by most viewed

You can sort your results to display the **most viewed** elements first.

Fetch your [Element Query](https://craftcms.com/docs/3.x/element-queries.html) just as you normally would, then pass the Element Query into the `sort` method.

```twig
{% set articles = craft.entries.section('articles') %}

{% do craft.viewCount.sort(articles) %}
```

If you want to sort by a specific [key](/using-a-unique-key/), simply add it as the second parameter...

```twig
{% do craft.viewCount.sort(articles, 'articleRead') %}
```

Views can be assigned to any valid element type, whether it's native or 3rd party.

:::warning Must be an Element Query
Don't apply the `.all()` method until _after_ you have sorted the Element Query.
:::

## Pagination

When [paginating](https://craftcms.com/docs/5.x/reference/twig/tags.html#paginate) the results, be sure to pass a **query** into the `paginate` tag:

```twig
{# Create a query #}
{% set query = craft.entries()
    .section('articles')
    .limit(10) %}

{# Order query by most viewed #}
{% do craft.viewCount.sort(query) %}

{# Paginate results #}
{% paginate query as pageInfo, pageEntries %}
```

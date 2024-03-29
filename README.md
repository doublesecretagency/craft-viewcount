<img align="left" src="https://plugins.doublesecretagency.com/view-count/images/icon.svg" alt="Plugin icon">

# View Count plugin for Craft CMS

**Count the number of times an element has been viewed.**

---

<p align="center">
    <img width="370" alt="Example of View Count results" src="https://github.com/doublesecretagency/craft-viewcount/raw/ff0f22bf4114498efa07430a3ac1fe8d39c5c2be/src/resources/img/example-totalviews.png">
</p>

---

## Track views via Twig, PHP, or AJAX

Basic tracking looks like this...

```twig
{% do craft.viewCount.increment(elementId) %}
```

However, there are several more advanced ways to [increment a view counter](https://plugins.doublesecretagency.com/view-count/how-to-increment-the-counter/).

## Allow multiple counters on the same element

If you need to track multiple aspects of the same element, it's easy to do so! Simply specify an [optional key](https://plugins.doublesecretagency.com/view-count/using-a-unique-key/) when setting up the tracking mechanism.

## Sort by "most viewed"

Once you've started logging views, you'll likely want to know which items have been [viewed the most...](https://plugins.doublesecretagency.com/view-count/sort-by-most-viewed/)

```twig
{% set articles = craft.entries.section('articles') %}
{% do craft.viewCount.sort(articles) %}
```

## Display view totals in the control panel

You can add a ["Total Views" field](https://plugins.doublesecretagency.com/view-count/total-views-fieldtype/), which displays a read-only total for each element.

## Events

They do [exactly what you think...](https://plugins.doublesecretagency.com/view-count/events/)

 - EVENT_BEFORE_VIEW
 - EVENT_AFTER_VIEW
 
Within the `EVENT_BEFORE_VIEW` event, you can prevent the view from being counted.

---

## Further Reading

If you haven't already, flip through the [complete plugin documentation](https://plugins.doublesecretagency.com/view-count/).

And if you have any remaining questions, feel free to [reach out to us](https://www.doublesecretagency.com/contact) (via Discord is preferred).

**On behalf of Double Secret Agency, thanks for checking out our plugin!** 🍺

<p align="center">
    <img width="130" src="https://www.doublesecretagency.com/resources/images/dsa-transparent.png" alt="Logo for Double Secret Agency">
</p>

---
description: View Count events follow the same basic pattern as standard Craft events.
---

# Events

View Count events follow the same pattern as [standard Craft events](https://craftcms.com/docs/3.x/extend/updating-plugins.html#events).

```php
use doublesecretagency\viewcount\services\View;
use doublesecretagency\viewcount\events\ViewEvent;
use yii\base\Event;

// Do something BEFORE an element is viewed...
Event::on(
    View::class,
    View::EVENT_BEFORE_VIEW,
    function(ViewEvent $event) {

        // Do something before view...

        // Optionally prevent view from being recorded
        // $event->isValid = false;

    }
);
```

```php
use doublesecretagency\viewcount\services\View;
use doublesecretagency\viewcount\events\ViewEvent;
use yii\base\Event;

// Do something AFTER an element is viewed...
Event::on(
    View::class,
    View::EVENT_AFTER_VIEW,
    function(ViewEvent $event) {

        // Do something after view...

    }
);
```

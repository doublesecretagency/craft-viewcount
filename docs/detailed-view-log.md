---
description: If you need to do complex custom sorting, filtering, or tracking of views, then you'll want to enable the option to "Keep a detailed record of all views".
---

# Detailed view log

## Optional

You don't need the detailed view information for View Count to function properly. This information is generally considered extraneous, but there may be some use-cases where it is handy to have.

## Enabling Detailed Logging

To enable the detailed view log:

1. Go to the plugin's settings page.
2. Check the box to "Keep a detailed record of all views".
3. Save.

##

Please note, at least some level of plugin development experience is required to pull this off.

You can communicate with View Count via PHP (ie: a custom plugin or module). Simply call the following Record:

```php
use doublesecretagency\viewcount\records\ViewLog;

$detailedLog = ViewLog::findAll([
    'elementId' => $elementId,
    'viewKey'   => $key,
]);
```

The following data will be available:

| Value         | Descriptions
|:--------------|:----------------------------
| `id`          | Primary key of view record.
| `elementId`   | Element ID of view target.
| `viewKey`     | Optional key to allow multiple viewing of the same element.
| `userId`      | User ID of viewer (or _NULL_ if viewed anonymously).
| `ipAddress`   | IP address of viewer.
| `userAgent`   | User agent of viewer's device.
| `dateCreated` | Timestamp of when vote was cast.

---
description: Individual user views are recorded in multiple places simultaneously... anonymous via cookies, and database for logged-in users.
---

# Get view totals per User

Individual user views are recorded in multiple places simultaneously:

 - **Cookie** tracking records views for everyone, whether they are logged-in or not.
 - **Database** tracking records views only for logged-in users.

## Cookie - Anonymous View Tracking

```php
// Cookie history of current user
ViewCount::$plugin->viewCount->getAnonymousHistory();
```

## Database - Logged-in View Tracking

```php
// Database history of currently logged in user
ViewCount::$plugin->viewCount->getUserHistory();

// Database history of specified user
ViewCount::$plugin->query->userHistory($userId);
```

---
---

:::warning Dual Tracking
It's important to note that views are tracked in both places _simultaneously_. Use whichever method makes the most sense for your project.
:::

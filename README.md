# WordPress Frontend Notifications

This is an extension for the `WP_Error` class which allows various notifications (not only errors) to be returned and displayed in the frontend.

Basically, you can add notifications in the same way you add errors to the WP_Error class, and it generates and displays HTML for all your notifications via an action. You can specify a status class for the notification (i.e. 'warning', 'error', 'success', 'info', and so on ) and even a icon (i.e. 'thumbs-up') which will be inserted in the HTML.

## Usage

#### 1. Include the class

First, make sure you add `include "path/to/class.wpfn-notification.php"` in your `functions.php` file. You have now access to the global instance of the object, `$wpfn_notifications`.

#### 2. Add Notifications

Now add one (or more) notifications in your function:

```php
global $wpfn_notifications;

/**
 * The notification message.
 */
 $message = __( 'Congratulations! You have successfully updated your profile.', 'your-textdomain' );

/**
 * Optionally specify a status and an/or an icon.
 */
$data = array(
  'status' => 'success',
  'icon'   => 'thumbs-up'
);

$wpfn_notifications->add( 'profile-updated', $message, $data );
```

#### 3. Display notifications

Finally, add the following code in your page or template file, wherever you want the notifications to be displayed.

```php
do_action('wpfn_notifications');
```

With the previous parameters, this outputs the following code:
```html
<div class="alert alert--success alert--profile-updated alert--has-icon">
  <i aria-hidden="true" class="icon icon-thumbs-up">
  Congratulations! You have successfully updated your profile.
</div>
```

**Note about the `$data` array**: if you do not specify 'status' the default class will be 'error'. If you do not specify an icon, the 'alert--has-icon' class and the icon element will not show.
# WordPress Frontend Notifications

This is an extension for the `WP_Error` class which allows various notifications (not only errors) to be returned and displayed in the frontend.

Basically, you can add notifications in the same way you add errors to the WP_Error class, and it generates and displays HTML for all your notifications via an action. You can specify a status class for the notification (i.e. `warning`, `error`, `success`, `info`, and so on ) and even a icon (i.e. `thumbs-up`) which will be inserted in the HTML.

The generated HTML uses the [BEM syntax](https://bem.info/), apart from the icon, which uses the single-dash sintax which is more common for icon font tool such as [Icomoon](https://icomoon.io/app/). More on that below.

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

*Note about the `$data` array: if you do not specify 'status' the default class will be 'error'. If you do not specify an icon, the 'alert--has-icon' class and the icon element will not show.*

## Filters

So now, if you’re screaming: *but I don’t use the 'alert' class in my CSS!*, well, good news for you: there’s a filter for that.

You can specify the class for the container by putting this in your `functions.php` file:


```php
/**
 * Change the default container class for the notifications.
 */
function sltr_notifications_class() {
  return 'myclass';
}
add_filter( 'wpfn_container_class', 'sltr_notifications_class' );
```

Now the HTML output will be:

```html
<div class="myclass myclass--success myclass--profile-updated myclass--has-icon">
  <i aria-hidden="true" class="icon icon-thumbs-up">
  Congratulations! You have successfully updated your profile.
</div>
```

## Shortcode

Want to put the notifications in the page content? No sweat: add `[wpfn_notifications]` in the editor and you’re done.

## The end

I made this because I believe in using WordPress core functions whenever possible, and I did not find any valid solution for displaying multi-purpose notices in the frontend.

For more methods and documentation, see the [codex page for WP_Error](http://codex.wordpress.org/Class_Reference/WP_Error).

Critics, comments, suggestions and bug reports are welcome.
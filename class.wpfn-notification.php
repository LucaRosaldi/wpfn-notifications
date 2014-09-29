<?php
/**
 * WordPress Frontend Notifications class.
 *
 * This is an extension for the WP_Error class which allows various notifications
 * (not only errors) to be returned and displayed in the front end with some help
 * from the {@link is_wpfn_notification} function.
 *
 * @package WordPress
 * @subpackage WordPress Frontend Notifications
 *
 * @uses WP_Error
 * @version 1.0
 */
class WPFN_Notification extends WP_Error {

  /**
   * Stores the html string for the notification.
   *
   * @since 1.0
   * @access private
   * 
   * @var string
   */
  private $html = '';

  /**
   * The status class for the notification. Defaults to 'error', to
   * follow along with the default WP_Error implementation.
   *
   * @since 1.0
   * @access private
   * 
   * @var string
   */
  private $status = 'error';

  /**
   * The icon class.
   *
   * @since 1.0
   * @access private
   * 
   * @var string
   */
  private $icon = '';

  /**
   * The container class for displaying notifications. Defaults to 'alert'.
   *
   * @since 1.0
   * @access public
   * 
   * @var string
   */
  public $container_class = 'alert';

  /**
   * Initialize the notification.
   *
   * Basically the structure is the same of WP_Error, but it stores
   * $status and $icon data from the $data array, and uses them for
   * displaying the error.
   *
   * $status is the class which is applied to the container (i.e.
   * if the container class is 'alert' and status is 'warning', the
   * resulting html would be <div class="alert alert--warning">),
   * using BEM CSS coding style.
   *
   * NOTE: as for the icons, the BEM methodology is dropped for a
   * single dash class (i.e. "icon icon-warning"), as this coding
   * style is more common in applications such as icomoon.
   *
   * @since 1.0
   * @access public
   * 
   * @param string|int $code Error code
   * @param string $message Error message
   * @param mixed $data Optional. Error data.
   * @return WP_Error
   */
  public function __construct( $code = '', $message = '', $data = '' ) {
    if ( empty($code) )
      return;

    $this->add( $code, $message, $data );
  }

  /**
   * Add a notification or append additional message to an existing notification.
   *
   * @since 1.0
   * @access public
   *
   * @param string|int $code Notification code.
   * @param string $message Notification message.
   * @param mixed $data Optional. Notification data.
   */
  public function add($code, $message, $data = '') {
    $this->__set( 'errors', array( $code => array( $message ) ) );
    if ( ! empty($data) ) {
      $this->__set( 'error_data', array( $code => array( $data ) ) );
      if ( isset( $data['status'] ) )
        $this->status = $data['status'];
      if ( isset( $data['icon'] ) )
        $this->icon = $data['icon'];
    }
  }

  /**
   * Build the html string with all the notifications.
   *
   * @since 1.0
   * @access public
   *
   * @return string The html for the notifications.
   */
  public function build( $container_class = '' ) {
    
    $html                 = '';
    $status               = $this->status;
    $icon                 = $this->icon;
    $container_class      = ( $container_class ) ? $container_class : $this->container_class;
    $container_icon_class = ( $icon ) ? $container_class . '--has-icon' : '';

    foreach ( $this->errors as $code => $message ) {

      // container start tag
      $html .= "<div class=\"$container_class $container_class--$code $container_class--$status $container_icon_class\">\n";

      // container icon
      if ( $icon ) $html .= "<i aria-hidden=\"true\" class=\"icon icon-$icon\"></i>\n";

      // container message
      $html .= $this->get_error_message( $code ) . "\n";

      // container close tag
      $html .= "</div>";

    }
    return $html;

  }

  /**
   * Echo html string with all the notifications.
   *
   * @since 1.0
   * @access public
   * 
   * @param string $container_class The class for the notification container.
   * @return void        If at least one notification is present, echoes the notifications HTML.
   */
  public function display( $container_class = '' ) {
    if ( !empty( $this->errors ) )
      echo $this->build( $container_class );
  }

}


/**
 * Create an instance of WPFN_Notification for site-wide usage.
 *
 * @since 1.0
 */
$wpfn_notifications = new WPFN_Notification();


/**
 * Create an action to display all notifications.
 *
 * It is now possibile to display all the registered notifications just
 * adding do_action('wpfn_notifications') to a page or template file.
 *
 * Using the 'wpfn_container_class' filter, it is also possible to change
 * the default notifications container class.
 *
 * @since 1.0
 *
 * @param mixed $thing A WPFN_Notification object. Defaults to the global object.
 * @return void        If at least one notification is present, echoes the notifications HTML.
 */
function wpfn_notifications( $thing = '' ) {
  global $wpfn_notifications;
  if ( !$wpfn_notifications ) { $wpfn_notifications = $thing; }

  if ( !is_wpfn_notification( $wpfn_notifications ) ) { return false; }

  /**
   * Add a filter to change the default notifications container class.
   */
  $container_class = apply_filters( 'wpfn_container_class', $wpfn_notifications->container_class );

  /**
   * Build and display the notifications.
   */
  $wpfn_notifications->display( $container_class );

}
add_action( 'wpfn_notifications', 'wpfn_notifications', 10, 1 );


/**
 * Check whether variable is a WPFN_Notification Object.
 *
 * This is just an alias of the is_wp_error class.
 * Checking a class returns true even with class extensions.
 *
 * @uses is_wp_error()
 *
 * @param mixed $thing Check if unknown variable is a WPFN_Notification or WP_Error object.
 * @return bool True, if WPFN_Notification or WP_Error. False, if not WPFN_Notification or WP_Error.
 */
function is_wpfn_notification( $thing ) {
  return is_wp_error( $thing );
}
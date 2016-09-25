<?php

/*
Plugin Name: Twitch TV Status Widget
Description: A widget to see status of twitch channels
Version: 1.0.0
Author: Ben Swierzy

*/

defined( 'ABSPATH' ) or die( 'And the access is .... DENIED' );

add_action( 'widgets_init', function(){
  register_widget( 'Twitch_TV_Status' );
});

class Twitch_TV_Status extends WP_Widget {

    /**
  	 * Sets up the widgets name etc
  	 */
  	public function __construct() {
  		parent::__construct( 'Twitch_TV_Status', 'Twitch TV Status' );
  	}
  
  	/**
  	 * Outputs the content of the widget
  	 *
  	 * @param array $args
  	 * @param array $instance
  	 */
  	public function widget( $args, $instance ) {
      
      $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Streams' );
      $streams = ( ! empty( $instance['streams'] ) ) ? $instance['streams'] : 'SaltyTeemo';
      
      $streams = explode(';', $streams);
      
      wp_enqueue_style( 'TTVstatus.css' , plugins_url( 'TTVstatus.css', __FILE__ ));
      wp_enqueue_script( 'twitch-status.js' , plugins_url( 'twitch-status.js', __FILE__ ));
      wp_localize_script( 'twitch-status.js' , 'script' , Array('path' => plugins_url( 'twitch.php' , __FILE__ )));
      
      $channels = $streams;
      echo $args['before_widget'];
      echo '<div class="twitchWidget">';
      echo $args['before_title'] . $title . $args['after_title'] . '<br />';
      
      foreach($channels as $channel) {
      
        echo "<div class='user $channel'>";
         
        echo "<div class='symbol'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>&nbsp;&nbsp;&nbsp;&nbsp;<a href='https://twitch.tv/$channel' target='_blank'>$channel</a>";
        
        echo '</div>';
        
      }
      
      echo '</div><br />';
      
      echo $args['after_widget'];
      
  	}
  
  	/**
  	 * Outputs the options form on admin
  	 *
  	 * @param array $instance The widget options
  	 */
  	function form( $instance ) {
      
      $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
      $streams   = isset( $instance['streams'] ) ? esc_attr( $instance['streams'] ) : 'SaltyTeemo';
      ?>
      
    <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
    
    <p><label for="<?php echo $this->get_field_id( 'streams' ); ?>"><?php _e( "Streams (seperated by ';'):" ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'streams' ); ?>" name="<?php echo $this->get_field_name( 'streams' ); ?>" type="text" value="<?php echo $streams; ?>" /></p>
    
    <p><label for="<?php echo $this->get_field_id( 'client_id' ); ?>"><?php _e( "Client ID (Not changed when left empty):" ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'client_id' ); ?>" name="<?php echo $this->get_field_name( 'client_id' ); ?>" type="text" value="" /></p>
    
    <?php
      
  	}
  
  	/**
  	 * Processing widget options on save
  	 *
  	 * @param array $new_instance The new options
  	 * @param array $old_instance The previous options
  	 */
  	function update( $new_instance, $old_instance ) {
      
      $instance = $old_instance;
  		$instance['title'] = sanitize_text_field( $new_instance['title'] );
   		$instance['streams'] = sanitize_text_field( $new_instance['streams'] );
      
      if ( ! empty( $new_instance['client_id'] ) ) {
        
        $twitch_php = <<<'TWITCHPHP'
<?php

if ($_SERVER['REMOTE_ADDR'] !== $_SERVER['SERVER_ADDR']) die("Access Forbidden");

$clientID = '
TWITCHPHP;

        $twitch_php .= $new_instance['client_id'];
        
        $twitch_php .= <<<'TWITCHPHP'
';

function jsonp_decode($jsonp, $assoc = false) { 
    if($jsonp[0] !== '[' && $jsonp[0] !== '{') { 
       $jsonp = substr($jsonp, strpos($jsonp, '('));
    }
    return json_decode(trim($jsonp,'();'), $assoc);
}

$streams = (isset($_GET)) ? $_GET : array('SaltyTeemo');

$result = array();

foreach ($streams as $stream) {

  $data = jsonp_decode(@file_get_contents("https://api.twitch.tv/kraken/streams/$stream?client_id=$clientID"));
  
  $result[$stream] = ($data->stream == null) ? 0 : 1;

}

echo json_encode($result);

?>

TWITCHPHP;
        
        $file = plugin_dir_path(__FILE__) . "twitch.php";
        
        $fp = @fopen($file, "w");
        
        if ($fp != NULL) {
          fwrite($fp, $twitch_php);
          
          fclose($fp);
        }
      
      }
      
  		return $instance;
      
  	}

}

?>

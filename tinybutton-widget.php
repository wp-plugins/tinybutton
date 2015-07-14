<?php
/*
Plugin Name: TinyButton 
Plugin URI: http://www.tinybutton.com
Description: Make your website multilingual instantly
Author: TinyButton
Version: 1.0
Author URI: http://www.tinybutton.com
*/
 
 
class tinybuttonwidget extends WP_Widget
{
  function tinybuttonwidget()
  {
    $widget_ops = array('classname' => 'tinybuttonwidget', 'description' => 'Make your website multilingual instantly');
    $this->WP_Widget('tinybuttonwidget', 'TinyButton', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'email' => '', 'password' => '' , 'content' => 'TinyButton: Not Configured' ) );
    
    $email   = $instance['email'];
    $password = $instance['password'];
?>
    <p><label for="<?php echo $this->get_field_id('email'); ?>">Email <input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo attribute_escape($email); ?>" /></label></p>
  
    <p><label for="<?php echo $this->get_field_id('password'); ?>">Password <input class="widefat" id="<?php echo $this->get_field_id('password'); ?>" name="<?php echo $this->get_field_name('password'); ?>" type="password" value="<?php echo attribute_escape($password); ?>" /></label></p>

    <p>Don't have an account? <a href='http://www.tinybutton.com'>Signup</a></p>
   <?php
  }
	
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    
    $instance['email']   = strip_tags($new_instance['email']);
    $instance['password'] = strip_tags($new_instance['password']);
    
	$instance['content'] = 'TinyButton: Not Configured';
	
	try
	{
		$response = wp_remote_post( 'https://secure.tinybutton.com/widget/getwidget', array(
			'method' => 'POST',
			'body' => array( 'Email' => $instance['email'], 'Password' => $instance['password'] ), 
			'timeout' => 5,
			'sslverify' => true
		));
		
		if( is_array($response) ) 
		{
			$instance['content'] = $response['body'];
		}
	}
	catch (Exception $e)
	{	
		
	}	

    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    echo $instance['content'];
    echo $after_widget;
  } 
}

add_action( 'widgets_init', create_function('', 'return register_widget("tinybuttonwidget");') );

?>
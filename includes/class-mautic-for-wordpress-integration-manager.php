<?php
class Mautic_For_Wordpress_Integration_Manager{

    public static function initialize_active_integrations(){
		
		if( !self::get_connection_status() ){
			return ;
		}
		$integrations = self::get_integrations();
		foreach( $integrations as $key => $details ){
			$integration = self::get_integration( $details );
			if(!$integration) continue ;
			if( $integration->is_active() && $integration->is_enabled()){
				$integration->initialize();
			}
		}
	}

    public static function get_integration( $details ){
		extract($details) ;
		if(empty($class) || empty($path) || empty($id) ){
			return false ; 
		}
		$file_path = MWB_M4WP_PLUGIN_PATH.'includes/integrations/'.$path ; 

		$file_path = apply_filters( 'mwb_m4wp_integration_path' , $file_path ) ; 

		if(file_exists($file_path)){
			require_once $file_path ; 
			$all_settings = get_option('mwb_m4wp_integration_settings' , array());	
			$settings = isset($all_settings[$id]) ? $all_settings[$id] : array();
			$integration = new $class( $id , $settings );
			return $integration ; 
		}
		return false;
	}
	
	public static function get_integrations( $key = '' ){

		$integrations = array(
			'mwb_m4wp_registration' => array(
				'class' => 'Registration_Form_Integration',
				'path' => 'class-registration-form-integration.php',
				'id' =>  'mwb_m4wp_registration'
			),
			'mwb_m4wp_comment' => array(
				'class' => 'Comment_Form_Integration',
				'path' => 'class-comment-form-integration.php',
				'id' =>  'mwb_m4wp_comment'
			),
		) ;                               
		
		$integrations = apply_filters( 'mwb_m4wp_available_integrations', $integrations );

		if($key != '' && isset($integrations[ $key ])){
			return $integrations[ $key ] ; 
		}

		return $integrations ; 
	}

	public static function get_connection_status(){
		return get_option('mwb_m4wp_connection_status' , false);
	}
}
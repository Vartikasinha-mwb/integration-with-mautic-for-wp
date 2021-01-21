<?php

class Mautic_For_Wordpress_Ajax {
    
    public function test_api_connection(){
        $user =  MWB_M4WP_Mautic_Api::get_self_user();
        var_dump($user);
        die;
    }

}

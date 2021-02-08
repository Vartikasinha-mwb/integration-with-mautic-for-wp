<?php
if(isset($_GET['id']) && $_GET['id'] != ''){
    $file_path = 'admin/partials/mautic-for-wordpress-integration-setting.php' ;
}else{
    $file_path = 'admin/partials/mautic-for-wordpress-integration-list.php' ;
}
Mautic_For_Wordpress_Admin::load_template($file_path) ; 


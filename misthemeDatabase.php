<?php
add_action( 'wp_ajax_nopriv_AdFormSubmit', 'mistheme_AdFormSubmit_callback' );
add_action( 'wp_ajax_AdFormSubmit', 'mistheme_AdFormSubmit_callback' );

function mistheme_AdFormSubmit_callback() {
    $json = array();
    $errors = new WP_Error();
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'AdFormSubmit_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['msg'] = '<div class="alert alert-danger">
			    <p class="login-msg">'. $errors->get_error_message().'</p></div>';
            die(json_encode( $json ));
        }
    }
    $ad_Data = array();
    $ad_Data['Ad_id'] = $_POST['Ad_id'];
    $ad_Data['Ad_en_name'] = trim($_POST['Ad_en_name']);
    $ad_Data['Ad_ar_name'] = trim($_POST['Ad_ar_name']);
    $ad_Data['Ad_link'] = $_POST['Ad_link'];
    $ad_Data['Ad_link_type'] = $_POST['Ad_link_type'];
    $ad_Data['Ad_type'] = $_POST['Ad_type'];
    $ad_Data['Ad_priority'] = $_POST['Ad_priority'];
    $ad_Data['Ad_start_date'] = $_POST['Ad_start_date'];
    $ad_Data['Ad_end_date'] = $_POST['Ad_end_date'];
    $ad_Data['Ad_locations'] = $_POST['Ad_locations'];
    $ad_Data['Ad_show_to_captain'] = $_POST['Ad_show_to_captain'];
    $ad_Data['Ad_show_to_user'] = $_POST['Ad_show_to_user'];
    $ad_Data['Ad_cap_notify'] = $_POST['Ad_cap_notify'];
    $ad_Data['Ad_user_notify'] = $_POST['Ad_user_notify'];
    $ad_Data['Ad_showonmap_captain'] = $_POST['Ad_showonmap_captain'];
    $ad_Data['Ad_showonmap_user'] = $_POST['Ad_showonmap_user'];
    $ad_Data['Ad_cap_view_no'] = trim($_POST['Ad_cap_view_no']);
    $ad_Data['Ad_user_view_no'] = trim($_POST['Ad_user_view_no']);
    $ad_Data['Advertiser_name'] = trim($_POST['Advertiser_name']);
    $ad_Data['Advertiser_type'] = trim($_POST['Advertiser_type']);
    $ad_Data['Advertiser_phone'] = trim($_POST['Advertiser_phone']);
    $ad_Data['Advertiser_email'] = trim($_POST['Advertiser_email']);
    $ad_Data['Advertiser_address'] = trim($_POST['Advertiser_address']);
    $ad_Data['Advertiser_website'] = trim($_POST['Advertiser_website']);
    $ad_Data['Advertiser_rep_name'] = trim($_POST['Advertiser_rep_name']);
    $ad_Data['Advertiser_rep_phone'] = trim($_POST['Advertiser_rep_phone']);
    $ad_Data['Advertiser_rep_email'] = trim($_POST['Advertiser_rep_email']);
    $ad_Data['Advertiser_rep_type'] = trim($_POST['Advertiser_rep_type']);

    $emptyFields = array();
    foreach($ad_Data as $item => $value){
        if($item != 'Ad_id'){
            if(($value == "" || $value == null)){
                array_push($emptyFields, $item);
            }
        }
    }
    //var_dump($emptyFields);
    $msg = '';
    if( count($emptyFields) > 0 ) {
        $errors->add('emptyFields', 'بعض المدخلات فارغة!');
        $json['emptyFields'] = $emptyFields;
    } else {
        //var_dump($ad_Data);
        $adSubmit = mistheme_create_ad($ad_Data);
        if(!$adSubmit){
            $errors->add('noAddUpdate','لا يمكن الوصول إلى قاعدة البيانات في الوقت الحالي، حاول مرة أخرى');
        }else{
            $json['success'] = 1;
            if($adSubmit == 'updated'){
                $msg = 'تم تعديل الإعلان';
                $json['action'] = 'update';
            }else{
                $msg = 'تم حفظ الإعلان';
                $json['id'] = $adSubmit;
                $json['action'] = 'insert';
            }

        }
    }

    if ($errors->get_error_code()){
        $json['msg'] = '<div class="alert alert-danger fade in">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <p class="login-msg">'. $errors->get_error_message().'</p></div>';
    }

    if ($json['success'] == 1){
        $json['msg'] = '<div class="alert alert-success fade in">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <p class="login-msg">'. $msg .'</p></div>';
    }

    echo json_encode( $json );

    die();
}

add_action( 'wp_ajax_nopriv_AdminTableData', 'mistheme_AdminTableData_callback' );
add_action( 'wp_ajax_AdminTableData', 'mistheme_AdminTableData_callback' );

function mistheme_AdminTableData_callback() {
    $json = array();
    $errors = new WP_Error();
    $nonce = $_POST['nonce'];
    global $wpdb;
    if ( ! wp_verify_nonce( $nonce, 'TableData_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['msg'] = '<div class="alert alert-danger">
			    <p class="login-msg">'. $errors->get_error_message().'</p></div>';
            die(json_encode( $json ));
        }
    }
    $tableData = mistheme_get_allAds();
    $json['total'] = $wpdb->num_rows;
    $json['rows'] = $tableData;
    echo json_encode( $json );

    die();
}

add_action( 'wp_ajax_nopriv_admineDeleteAd', 'mistheme_admineDeleteAd_callback' );
add_action( 'wp_ajax_admineDeleteAd', 'mistheme_admineDeleteAd_callback' );

function mistheme_admineDeleteAd_callback() {
    $json = array();
    $errors = new WP_Error();
    $nonce = $_POST['nonce'];
    $Ad_id = $_POST['Ad_id'];
    global $wpdb;
    if ( ! wp_verify_nonce( $nonce, 'TableData_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['msg'] = '<div class="alert alert-danger">
			    <p class="login-msg">'. $errors->get_error_message().'</p></div>';
            die(json_encode( $json ));
        }
    }
    $queryData = mistheme_deleteSingleAd($Ad_id);
    if(!$queryData){
        $json['success'] = 0;
    }else{
        $json['success'] = 1;
    }
    echo json_encode( $json );
    die();
}


function mistheme_create_ad($data=array()){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $Ad_id = $data['Ad_id'];
    $check_row = $wpdb->get_row("SELECT Ad_id FROM $tableName WHERE Ad_id = $Ad_id ");
    if (!$check_row){
        $wpdb->insert($tableName, $data);
        if ($wpdb->insert_id) {
            return $wpdb->insert_id;
        } else {
            return false;
        }
    } else {
        $check_updated = $wpdb->update($tableName, $data, array('Ad_id'=>absint($Ad_id)));
        if ($check_updated === FALSE){
            return false;
        } else {
            return 'updated';
        }
    }

}

function mistheme_get_allAds(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $get_data = $wpdb->get_results("SELECT
        Ad_id,
        Ad_en_name,
        Ad_ar_name,
        Ad_type,
        Ad_link_type,
        Ad_link,
        Ad_start_date,
        Ad_end_date,
        Ad_locations,
        Ad_priority,
        Ad_show_to_captain,
        Ad_show_to_user,
        Ad_show_to_captain,
        Ad_cap_notify,
        Ad_user_notify,
        Ad_cap_view_no,
        Ad_user_view_no,
        Ad_showonmap_captain,
        Ad_showonmap_user,
        Advertiser_name,
        Advertiser_type,
        Advertiser_phone,
        Advertiser_email,
        Advertiser_address,
        Advertiser_website,
        Advertiser_rep_name,
        Advertiser_rep_phone,
        Advertiser_rep_email,
        Advertiser_rep_type
        FROM $tableName", 'ARRAY_A');
    return $get_data;
}

function mistheme_getSingleAd($Ad_id){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $get_data = $wpdb->get_row("SELECT * FROM $tableName WHERE Ad_id = $Ad_id");
    return $get_data;
}

function mistheme_deleteSingleAd($Ad_id){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $data = $wpdb->delete( $tableName, array( 'Ad_id' => $Ad_id ) );
    return $data;
}
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
	
// تحديد الأسعار
	$allprices  = mistheme_getPrices();

	$picture_price = $allprices->picture_price;
	$video_price= $allprices->video_price;
	$priority_price= $allprices->priority_price;
	$showtocap_price= $allprices->showtocap_price;
	$notifycap_price= $allprices->notifycap_price;
	$mapcap_price= $allprices->mapcap_price;
	$showtouser_price= $allprices->showtouser_price;
	$notifyuser_price= $allprices->notifyuser_price;
	$mapuser_price= $allprices->mapuser_price;
	$cap100view_price= $allprices->cap100view_price;
	$user100view_price= $allprices->user100view_price;
	$pluslocation_price= $allprices->pluslocation_price;
	
	
	
// حساب سعر الاعلان بناءً على البيانات
	$price = 0;
	if($ad_Data['Ad_link_type']==1) $price = $picture_price;
	if($ad_Data['Ad_link_type']==2) $price = $video_price;
	if($ad_Data['Ad_priority']>1){
		$price += $priority_price * ( $ad_Data['Ad_priority'] - 1) ;
	}

	if($ad_Data['Ad_show_to_captain']==1) $price += $showtocap_price;
	if($ad_Data['Ad_cap_notify']==1) $price += $notifycap_price;
	if($ad_Data['Ad_showonmap_captain']==1) $price += $mapcap_price;
	if($ad_Data['Ad_show_to_user']==1) $price += $showtouser_price;
	if($ad_Data['Ad_user_notify']==1) $price += $notifyuser_price;
	if($ad_Data['Ad_showonmap_user']==1) $price += $mapuser_price;
	if($ad_Data['Ad_cap_view_no']>0) {
		$price += $cap100view_price * $ad_Data['Ad_cap_view_no']/100;
	}
	if($ad_Data['Ad_user_view_no']>0){
		$price += $user100view_price * $ad_Data['Ad_user_view_no']/100;
	}
	// how many location?
	$LocationArr = explode(":", $ad_Data['Ad_locations']);
	if(sizeof($LocationArr)>1){
		$price += $pluslocation_price * ( sizeof($LocationArr) - 1 ) ;
	}

	// how many days ????
	$date1 = new DateTime($ad_Data['Ad_start_date']);
	$date2 = new DateTime($ad_Data['Ad_end_date']);
	$howmanydays = $date2->diff($date1)->format("%a");
	$howmanydays++;
	//$howmanydays = $ad_Data['Ad_end_date']->diff($ad_Data['Ad_start_date'])->format("%a");
	if($howmanydays > 1){
		$price = $price * $howmanydays ;
	}
	
	$ad_Data['Ad_price']= $price;

    $emptyFields = array();
    foreach($ad_Data as $item => $value){
        if($item != 'Ad_id' && $item != 'Ad_price' && $item != 'Advertiser_website'){
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
		Ad_price,
		Ad_paid,
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
        FROM $tableName

        ", 'ARRAY_A');
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
//Finance Here

function mistheme_getFinance(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $get_data = $wpdb->get_results("SELECT * FROM $tableName", 'ARRAY_A');
    return $get_data;
}

function mistheme_getPrices(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'prices';
    $get_data = $wpdb->get_row("SELECT * FROM $tableName WHERE id = 1");
    return $get_data;
}

// Captain table

//function mistheme_getCaptains(){
//    global $wpdb;
//    $wpdb->hide_errors();
//    $tableName = $wpdb->prefix.'captains';
//    $get_data = $wpdb->get_results("SELECT * FROM $tableName", 'ARRAY_A');
//    return $get_data;
//}
add_action( 'wp_ajax_nopriv_capTableData', 'mistheme_capTableData_callback' );
add_action( 'wp_ajax_capTableData', 'mistheme_capTableData_callback' );

function mistheme_capTableData_callback() {
    $json = array();
    global $wpdb;
    $tableData = mistheme_getAllCaps();
    $json['total'] = $wpdb->num_rows;
    $json['rows'] = $tableData;
    echo json_encode( $json );
    die();
}

add_action( 'wp_ajax_nopriv_updateCapPass', 'mistheme_updateCapPass_callback' );
add_action( 'wp_ajax_updateCapPass', 'mistheme_updateCapPass_callback' );

function mistheme_updateCapPass_callback() {
    $json = array();
    $errors = new WP_Error();
    $cap_id = $_POST['cap_id'];
    $password = $_POST['newPass'];
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'capPassWordSubmit_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['result'] = false;
            die(json_encode( $json ));
        }
    }
    $json['result'] = mistheme_updateSingleCapPass($cap_id,$password);
    echo json_encode( $json );
    die();
}

function mistheme_updateSingleCapPass($cap_id, $password){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'captains';
    $check_updated = $wpdb->update($tableName, array('Cap_Password'=>$password), array('Cap_ID'=>absint($cap_id)));
    if ($check_updated === FALSE){
        return false;
    } else {
        return true;
    }
}

function mistheme_deleteSingleCap($cap_id){
    global $wpdb;
    $wpdb->hide_errors();
    $tableNameCap = $wpdb->prefix.'captains';
    $tableNameStat = $wpdb->prefix.'adlogs';
    $capName = $wpdb->get_row("SELECT Cap_Username FROM $tableNameCap WHERE Cap_ID = $cap_id");
    //var_dump($capName->Cap_Username);
    $dataStat = $wpdb->delete( $tableNameStat, array( 'capname' => $capName->Cap_Username ) );
    $data = FALSE;
    if($dataStat !== FALSE){
        $data = $wpdb->delete( $tableNameCap, array( 'Cap_ID' => $cap_id ) );
    }
    if ($data === FALSE){
        return false;
    } else {
        return true;
    }
}

add_action( 'wp_ajax_nopriv_capDelete', 'mistheme_capDelete_callback' );
add_action( 'wp_ajax_capDelete', 'mistheme_capDelete_callback' );

function mistheme_capDelete_callback() {
    $json = array();
    $errors = new WP_Error();
    $nonce = $_POST['nonce'];
    $cap_id = $_POST['cap_id'];

    if ( ! wp_verify_nonce( $nonce, 'capTable_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['result'] = false;
            die(json_encode( $json ));
        }
    }
    $json['result'] = mistheme_deleteSingleCap($cap_id);
    echo json_encode( $json );
    die();
}

function mistheme_createCap($capname, $password){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'captains';
    $wpdb->insert($tableName, array('Cap_Username'=> $capname, 'Cap_Password'=>$password,'Cap_WorkingTime'=>0,'Cap_WorkingDistance'=>0));
    if ($wpdb->insert_id) {
        $check_row = $wpdb->get_row("SELECT * FROM $tableName WHERE Cap_ID = $wpdb->insert_id ");
        return $check_row;
    } else {
        return false;
    }
}

function mistheme_checkCapUsername($capname){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'captains';
    $check_row = $wpdb->get_row("SELECT * FROM $tableName WHERE Cap_Username = '$capname'");
    if($check_row === null){
        return true;
    }else{
        return false;
    }
}

add_action( 'wp_ajax_nopriv_capNew', 'mistheme_capNew_callback' );
add_action( 'wp_ajax_capNew', 'mistheme_capNew_callback' );

function mistheme_capNew_callback() {
    $json = array();
    $errors = new WP_Error();
    $nonce = $_POST['nonce'];
    $capName = $_POST['capName'];
    $capPass = $_POST['capPass'];

    if ( ! wp_verify_nonce( $nonce, 'capNew_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['result'] = false;
            $json['msg'] = 'حاول مرة أخرى';
            die(json_encode( $json ));
        }
    }
    if(!mistheme_checkCapUsername($capName)){
        $json['result'] = false;
        $json['msg'] = 'اسم الكابتن مُستخدم';
        die(json_encode( $json ));
    }else{
        $json['result'] = mistheme_createCap($capName,$capPass);
    }

    echo json_encode( $json );
    die();
}

/*
 * Captain Stats
 */

function mistheme_getAllCaps(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'captains';
    $data = $wpdb->get_results("SELECT * FROM $tableName",'ARRAY_A');
    return $data;
}

function mistheme_getCapShow($cap, $startDate, $endDate){
    global $wpdb;
    $data = array();
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $tableNameAds = $wpdb->prefix.'advertisement';
    $data['rows'] = $wpdb->get_results("SELECT  $tableNameLogs.ad_id, $tableNameLogs.capname, $tableNameLogs.timestamp, COUNT(*) tCount, $tableNameAds.Ad_ar_name, $tableNameLogs.meta
                                FROM $tableNameLogs
                                INNER JOIN $tableNameAds ON $tableNameLogs.ad_id = $tableNameAds.Ad_id
                                WHERE capname = '$cap' AND event = 'captain_show' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                                GROUP BY $tableNameLogs.ad_id
                                ",'ARRAY_A');
    $wpdb->get_results("
                            SELECT *
                            FROM $tableNameLogs
                            WHERE capname = '$cap' AND event = 'captain_show' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                            ");
    $data['count'] = $wpdb->num_rows;
    return $data;
}
function mistheme_getCapView($cap, $startDate, $endDate){
    global $wpdb;
    $data = array();
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $tableNameAds = $wpdb->prefix.'advertisement';
    $data['rows'] = $wpdb->get_results("SELECT  $tableNameLogs.ad_id, $tableNameLogs.capname, $tableNameLogs.timestamp, COUNT(*) tCount, $tableNameAds.Ad_ar_name
                                FROM $tableNameLogs
                                INNER JOIN $tableNameAds ON $tableNameLogs.ad_id = $tableNameAds.Ad_id
                                WHERE capname = '$cap' AND event = 'captain_view' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                                GROUP BY $tableNameLogs.ad_id
                                ",'ARRAY_A');
    $wpdb->get_results("SELECT *
                        FROM $tableNameLogs
                        WHERE capname = '$cap' AND event = 'captain_view' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                        ");
    $data['count'] = $wpdb->num_rows;
    return $data;
}

function mistheme_getCapDist($cap, $startDate, $endDate){
    global $wpdb;
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $data = $wpdb->get_results("SELECT DATE(timestamp) date, SUM(meta) tSum
                                FROM $tableNameLogs
                                WHERE capname = '$cap' AND event = 'captain_ad_dist' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                                GROUP BY DATE(timestamp)
                                ",'ARRAY_A');
    return $data;
}

function mistheme_getCapDistPerAd($cap, $startDate, $endDate){
    global $wpdb;
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $tableNameAds = $wpdb->prefix.'advertisement';
    $data = $wpdb->get_results("SELECT  $tableNameLogs.ad_id, SUM($tableNameLogs.meta) tSum, $tableNameAds.Ad_ar_name
                                FROM $tableNameLogs
                                INNER JOIN $tableNameAds ON $tableNameLogs.ad_id = $tableNameAds.Ad_id
                                WHERE capname = '$cap' AND event = 'captain_ad_dist' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                                GROUP BY $tableNameLogs.ad_id
                                ",'ARRAY_A');
    return $data;
}

function mistheme_getCapDailyUpdate($cap, $startDate, $endDate){
    global $wpdb;
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $data = $wpdb->get_results("SELECT DATE_FORMAT(timestamp, '%l%p') hour, COUNT(*) tCount
                                FROM $tableNameLogs
                                WHERE capname = '$cap' AND event = 'captain_update' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                                GROUP BY HOUR(timestamp)
                                ",'ARRAY_A');
    return $data;
}

function mistheme_getCapMap($cap, $startDate, $endDate){
    global $wpdb;
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $tableNameAds = $wpdb->prefix.'advertisement';
    $data = $wpdb->get_results("
                            SELECT $tableNameLogs.ad_id, $tableNameAds.Ad_ar_name, $tableNameLogs.meta, COUNT(*) tCount
                            FROM $tableNameLogs
                            INNER JOIN $tableNameAds ON $tableNameLogs.ad_id = $tableNameAds.Ad_id
                            WHERE capname = '$cap' AND event = 'captain_show' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                            GROUP BY $tableNameLogs.meta, $tableNameLogs.ad_id
                            ORDER BY tCount DESC
                            LIMIT 10
    ",'ARRAY_A');
    return $data;
}

add_action( 'wp_ajax_nopriv_selectCapStat', 'mistheme_selectCapStat_callback' );
add_action( 'wp_ajax_selectCapStat', 'mistheme_selectCapStat_callback' );

function mistheme_selectCapStat_callback() {
    $json = array();
    $cap = $_POST['capname'];
    $startDate = date_format(date_time_set(date_create($_POST['startdate']),0,0,0),"Y-m-d H:i:s");
    $endDate = date_format(date_time_set(date_create($_POST['enddate']),23,59,59),"Y-m-d H:i:s");
    //$endDate2 = date_format(date_time_set(date_create($_POST['startdate']),23,59,59),"Y-m-d H:i:s");
    $event = $_POST['event'];
    $html = '';
    if($event=="captain_show"){
        $queryData = mistheme_getCapShow($cap,$startDate,$endDate);
        $html = '
        <div class="well text-center" style="margin: 15px;">
            <strong>اجمالي الإعلانات التي ظهرت للكابتن: </strong>
            <span>'.$queryData['count'].'</span>
        </div>
        <table class="table table-hover"><thead><tr>
            <th class="text-right" style="width: 8%;">#</th>
            <th>اسم الإعلان</th>
            <th class="text-left" style="width: 25%;">عدد مرات الظهور</th>
        </tr></thead><tbody>';
        foreach($queryData['rows'] as $row){
            //var_dump($row['ad_id']);
            $html .= '<tr><td>'.$row['ad_id'].'</td><td>'.$row['Ad_ar_name'].'</td><td class="text-left">'.$row['tCount'].'</td></tr>';
        }
        $html .= '</tbody></table>';
    }elseif($event == "captain_view"){
        $queryData = mistheme_getCapView($cap,$startDate,$endDate);
        $html = '
        <div class="well text-center" style="margin: 15px;">
            <strong>اجمالي الإعلانات التي عرضت للكابتن: </strong>
            <span>'.$queryData['count'].'</span>
        </div>
        <table class="table table-hover"><thead><tr>
            <th class="text-right" style="width: 8%;">#</th>
            <th>اسم الإعلان</th>
            <th class="text-left" style="width: 25%;">عدد مرات العرض</th>
        </tr></thead><tbody>';
        foreach($queryData['rows'] as $row){
            //var_dump($row['ad_id']);
            $html .= '<tr><td>'.$row['ad_id'].'</td><td>'.$row['Ad_ar_name'].'</td><td class="text-left">'.$row['tCount'].'</td></tr>';
        }
        $html .= '</tbody></table>';
    }elseif($event == "captain_ad_dist24"){
        $queryData = mistheme_getCapDist($cap,$startDate,$endDate);
        //var_dump($queryData);
        $html = '
        <table class="table table-hover"><thead><tr>
            <th>التاريخ</th>
            <th >المسافة المقطوعة</th>
        </tr></thead><tbody>';
        foreach($queryData as $row){
            $html .= '<tr><td>'.$row['date'].'</td><td>'.$row['tSum'].'</td></tr>';
        }
        $html .= '</tbody></table>';
    }elseif($event == "captain_ad_distAd"){
        $queryData = mistheme_getCapDistPerAd($cap,$startDate,$endDate);
        //var_dump($queryData);
        $html = '
        <table class="table table-hover"><thead><tr>
            <th style="width: 8%;">#</th>
            <th>اسم الإعلان</th>
            <th style="width: 30%;">المسافة المقطوعة</th>
        </tr></thead><tbody>';
        foreach($queryData as $row){
            $html .= '<tr><td>'.$row['ad_id'].'</td><td>'.$row['Ad_ar_name'].'</td><td>'.$row['tSum'].'</td></tr>';
        }
        $html .= '</tbody></table>';
    }elseif($event == "captain_update"){
        $queryData = mistheme_getCapDailyUpdate($cap,$startDate,$endDate);
        //var_dump($queryData);
        $labels = '';
        $data = '';
        foreach($queryData as $row){
            $labels .= '"'.$row['hour'].'",';
            $data .= $row['tCount'].',';
        }
        $html = '<canvas id="myChart" width="830" height="375"></canvas>';
        $html .= '
        <script>
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: ['.$labels.'],
                    datasets: [{
                        label: "التحديث اليومي",
                        data: ['.$data.'],
                        lineTension: 0,
                        fill: false,
                        borderColor:"rgba(255,99,132,1)",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                        spanGaps: true,
                    }],

                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                    }
                                }]
        }
                }
            });
            </script>
        ';
    }elseif($event == "cap_map"){
        $queryData = mistheme_getCapMap($cap, $startDate,$endDate);
        $html = '<div id="mapCap" class="mapContainer"></div><script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='. GOOGLE_MAPS_API .'&callback=initMap"></script>';
        $html .= '<script>
                function initMap() {
                    var mapCanvas = document.getElementById("mapCap");
                    var myCenter = new google.maps.LatLng(24.647017162630366,44.589385986328124);
                    var mapOptions = {center: myCenter, zoom: 5,streetViewControl: false};
                    var infoWindow = new google.maps.InfoWindow();
                    var image = {
                    url: "https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi-dotless.png",
                    scaledSize: new google.maps.Size(30, 50),
                    labelOrigin: new google.maps.Point(8, 15),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(10, 10)
                    };
                    map = new google.maps.Map(mapCanvas, mapOptions);
                    ';

        foreach($queryData as $marker){
            $html .= '
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng('.$marker['meta'].'),
                    label: "'.$marker["tCount"].'",
                    map: map,
                    icon: image,
                    title: "'.$marker["Ad_ar_name"].'",
                });
                google.maps.event.addListener(marker, "click", function () {
                    infoWindow.setContent("<h1>'.$marker['Ad_ar_name'].'</h1><p>عدد مرات الظهور: '.$marker["tCount"].'</p>");
                    infoWindow.open(map, this);
                });
                ';
        }
        $html .= '};</script>';
    }

    $json['result'] = $html;
    echo json_encode( $json );
    die();
}

/*
 * User Stats
 */

function mistheme_getUserStat($event, $startDate, $endDate){
    global $wpdb;
    $data = array();
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $tableNameAds = $wpdb->prefix.'advertisement';
    $data['rows'] = $wpdb->get_results("SELECT  $tableNameLogs.ad_id, COUNT(*) tCount, $tableNameAds.Ad_ar_name
                                FROM $tableNameLogs
                                INNER JOIN $tableNameAds ON $tableNameLogs.ad_id = $tableNameAds.Ad_id
                                WHERE capname = 0 AND event = '$event' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                                GROUP BY $tableNameLogs.ad_id
                                ",'ARRAY_A');
    $wpdb->get_results("
                            SELECT *
                            FROM $tableNameLogs
                            WHERE capname = 0 AND event = '$event' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                            ");
    $data['count'] = $wpdb->num_rows;
    return $data;
}

function mistheme_getUserMap($startDate, $endDate){
    global $wpdb;
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $tableNameAds = $wpdb->prefix.'advertisement';
    $data = $wpdb->get_results("
                            SELECT $tableNameLogs.ad_id, $tableNameAds.Ad_ar_name, $tableNameLogs.meta, COUNT(*) tCount
                            FROM $tableNameLogs
                            INNER JOIN $tableNameAds ON $tableNameLogs.ad_id = $tableNameAds.Ad_id
                            WHERE capname = 0 AND event = 'user_notify' AND (timestamp >= '$startDate' AND timestamp <= '$endDate')
                            GROUP BY $tableNameLogs.meta, $tableNameLogs.ad_id
                            ORDER BY tCount DESC
                            LIMIT 10
    ",'ARRAY_A');
    return $data;
}

add_action( 'wp_ajax_nopriv_selectUserStat', 'mistheme_selectUserStat_callback' );
add_action( 'wp_ajax_selectUserStat', 'mistheme_selectUserStat_callback' );

function mistheme_selectUserStat_callback() {
    $json = array();
    $startDate = date_format(date_time_set(date_create($_POST['startdate']),0,0,0),"Y-m-d H:i:s");
    $endDate = date_format(date_time_set(date_create($_POST['enddate']),23,59,59),"Y-m-d H:i:s");
    $event = $_POST['event'];
    if($event == "user_map"){
        $queryData = mistheme_getUserMap($startDate,$endDate);
    }else{
        $queryData = mistheme_getUserStat($event, $startDate,$endDate);
    }
    //var_dump($queryData);
    $countTXT = 'عدد المرات';
    if($event=="user_show"){
        $totalTXT = 'قائمة الإعلانات التي ظهرت على الخارطة للمستخدمين: ';
    }elseif($event=="user_notify"){
        $totalTXT = 'قائمة الإعلانات التي ظهرت مع تنبيه للمتسخدمين: ';
    }elseif($event=="user_browse"){
        $totalTXT = 'قائمة الإعلانات التي تم تصفحها من المستخدمين: ';
    }else{
        $totalTXT = 'قائمة الإعلانات التي ظهرت على الخارطة بعد النقر عليها: ';
    }
    if($event == "user_map"){
        $html = '<div id="map" class="mapContainer"></div><script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key='. GOOGLE_MAPS_API .'&callback=initMap"></script>';
        $html .= '<script>

                function initMap() {
                    var mapCanvas = document.getElementById("map");
                    var myCenter = new google.maps.LatLng(24.647017162630366,44.589385986328124);
                    var mapOptions = {center: myCenter, zoom: 5,streetViewControl: false};
                    var infoWindow = new google.maps.InfoWindow();
                    var image = {
                    url: "https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi-dotless.png",
                    scaledSize: new google.maps.Size(30, 50),
                    labelOrigin: new google.maps.Point(8, 15),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(10, 10)
                    };
                    map = new google.maps.Map(mapCanvas, mapOptions);
                    ';

            foreach($queryData as $marker){
                $html .= '
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng('.$marker['meta'].'),
                    label: "'.$marker["tCount"].'",
                    map: map,
                    icon: image,
                    title: "'.$marker["Ad_ar_name"].'",
                });
                google.maps.event.addListener(marker, "click", function () {
                    infoWindow.setContent("<h1>'.$marker['Ad_ar_name'].'</h1><p>عدد مرات الظهور: '.$marker["tCount"].'</p>");
                    infoWindow.open(map, this);
                });
                ';
            }
        $html .= '};</script>';
    }else{
        $html = '
        <div class="well text-center" style="margin: 15px;">
            <strong>'.$totalTXT.'</strong>
            <span>'.$queryData['count'].'</span>
        </div>
        <table class="table table-hover"><thead><tr>
            <th class="text-right" style="width: 8%;">#</th>
            <th>اسم الإعلان</th>
            <th class="text-left" style="width: 25%;">'.$countTXT.'</th>
        </tr></thead><tbody>';
        foreach($queryData['rows'] as $row){
            $html .= '<tr><td>'.$row['ad_id'].'</td><td>'.$row['Ad_ar_name'].'</td><td class="text-left">'.$row['tCount'].'</td></tr>';
        }
        $html .= '</tbody></table>';
    }


    $json['result'] = $html;
    echo json_encode( $json );
    die();
}

//Single Ad Stat
function mistheme_getSingleAdStat($ad_id){
    global $wpdb;
    $data = array();
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $data[0] = $wpdb->get_results("SELECT COUNT(*) userShow
                                            FROM $tableNameLogs
                                            WHERE ad_id = '$ad_id' AND event = 'user_browse'")[0];
    $data[1] = $wpdb->get_results("SELECT COUNT(*) capShow
                                            FROM $tableNameLogs
                                            WHERE ad_id = '$ad_id' AND event = 'captain_view'")[0];
    $data[2] = $wpdb->get_results("SELECT COUNT(*) userNotify
                                            FROM $tableNameLogs
                                            WHERE ad_id = '$ad_id' AND event = 'user_notify'")[0];
    $data[3] = $wpdb->get_results("SELECT COUNT(*) capNotify
                                            FROM $tableNameLogs
                                            WHERE ad_id = '$ad_id' AND event = 'captain_notify'")[0];
    return $data;
}

function mistheme_getSingleAdLocations($Ad_id){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $get_data = $wpdb->get_row("SELECT Ad_locations FROM $tableName WHERE Ad_id = $Ad_id",'ARRAY_A');
    $data = explode( ':', $get_data['Ad_locations']);
    return $data;
}

function mistheme_getSingleLocationStats($ad_id, $location){
    global $wpdb;
    $data = array();
    $wpdb->hide_errors();
    $tableNameLogs = $wpdb->prefix.'adlogs';
    $data[0] = $wpdb->get_results("SELECT COUNT(*) capLoc
                                   FROM $tableNameLogs
                                   WHERE ad_id = '$ad_id' AND event = 'captain_show' AND meta = '$location'")[0];
    $data[1] = $wpdb->get_results("SELECT COUNT(*) userLoc
                                   FROM $tableNameLogs
                                   WHERE ad_id = '$ad_id' AND event = 'user_notify' AND meta = '$location'")[0];
    return $data;
}
add_action( 'wp_ajax_nopriv_singleAdStat', 'mistheme_singleAdStat_callback' );
add_action( 'wp_ajax_singleAdStat', 'mistheme_singleAdStat_callback' );

function mistheme_singleAdStat_callback() {
    $json = array();
    $locationArray = array();
    $statsArray = array();
    $ad_id = $_POST['ad_id'];
    $statData = mistheme_getSingleAdStat($ad_id);
    $adLocations = mistheme_getSingleAdLocations($ad_id);
    //var_dump($statData);
    foreach($statData as $stat){
        foreach($stat as $item => $value){
            $statsArray[$item] = $value;
        }
    }

    $index = 0;
    foreach($adLocations as $location){
        $locationData = mistheme_getSingleLocationStats($ad_id,$location);
        $locationArray[$index]['location'] = $location;
        $locationArray[$index]['capCount'] = $locationData[0]->capLoc;
        $locationArray[$index]['userCount'] = $locationData[1]->userLoc;
        $index++;
    }
    //var_dump($statsArray);
    $html = '
       <div class="panel-group">
           <div class="panel panel-primary">
              <div class="panel-heading">عدد مرات الظهور </div>
               <ul class="list-group">
                    <li class="list-group-item">
                        <span>المستخدم: </span>
                        <span> '.$statsArray['userShow'].'</span>
                    </li>
                    <li class="list-group-item">
                        <span>الكابتن: </span>
                        <span> '.$statsArray['capShow'].'</span>
                    </li>
              </ul>
           </div>
           <div class="panel panel-primary">
              <div class="panel-heading">عدد مرات ظهور التنبيهات </div>
               <ul class="list-group">
                    <li class="list-group-item">
                        <span>المستخدم: </span>
                        <span> '.$statsArray['userNotify'].'</span>
                    </li>
                    <li class="list-group-item">
                        <span>الكابتن: </span>
                        <span> '.$statsArray['capNotify'].'</span>
                    </li>
              </ul>
           </div>
           <div class="panel panel-primary">
              <div class="panel-heading">علامات الخريطة </div>
               <ul class="list-group">
                    <li class="list-group-item">
                        <img src="'. plugins_url('admin/img/markerDefault.png', (__FILE__) ).'" style="width:18%;display:inline;" class="img-responsive" alt="Default Image">
                        <span>  للمستخدم والكابتن</span>
                    </li>
                    <li class="list-group-item">
                        <img src="'. plugins_url('admin/img/markerCap.png', (__FILE__) ).'" style="width:18%;display:inline;" class="img-responsive" alt="Captain Image">
                        <span>  للكابتن</span>
                    </li>
                    <li class="list-group-item">
                        <img src="'. plugins_url('admin/img/markerUser.png', (__FILE__) ).'" style="width:18%;display:inline;" class="img-responsive" alt="‘User Image">
                        <span>  للمستخدم</span>
                    </li>
              </ul>
           </div>
       </div>
    ';
    $json['result'] = $locationArray;
    $json['stats'] = $html;
    echo json_encode( $json );
    die();
}

function mistheme_updateSingleAdPaid($ad_id, $paid){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $check_updated = $wpdb->update($tableName, array('Ad_paid'=>absint($paid)), array('Ad_id'=>absint($ad_id)));
    if ($check_updated === FALSE){
        return false;
    } else {
        return $wpdb->get_results("SELECT SUM(Ad_paid) totalPaid FROM $tableName")[0]->totalPaid;
    }
}

add_action( 'wp_ajax_nopriv_adPaidSubmit', 'mistheme_adPaidSubmit_callback' );
add_action( 'wp_ajax_adPaidSubmit', 'mistheme_adPaidSubmit_callback' );

function mistheme_adPaidSubmit_callback() {
    $json = array();
    $errors =  new WP_Error();
    $ad_id = $_POST['ad_id'];
    $paidTxt = $_POST['paidtxt'];
    $nonce = $_POST['nonce'];
    if ( ! wp_verify_nonce( $nonce, 'adPaidSubmit_action' ) ){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['result'] = false;
            die(json_encode( $json ));
        }
    }

    $checkUpdated = mistheme_updateSingleAdPaid($ad_id,$paidTxt);

    $json['result'] = $checkUpdated;
	
    echo json_encode( $json );
    die();
}

add_action( 'wp_ajax_nopriv_adPriceCustomSubmit', 'mistheme_adPriceCustomSubmit_callback' );
add_action( 'wp_ajax_adPriceCustomSubmit', 'mistheme_adPriceCustomSubmit_callback' );

function mistheme_adPriceCustomSubmit_callback() {
    $json = array();
    $errors =  new WP_Error();
    $nonce = $_POST['nonce'];
    $dataForm = $_POST;
    unset($dataForm['action']);
    unset($dataForm['nonce']);
//    foreach($_POST as $item => $value){
//        if($item != 'nonce' && $item != 'action'){
//            $dataForm[$item] = $value;
//        }
//    }
    //var_dump($dataForm);
    if ( !wp_verify_nonce($nonce, 'adPriceCustom_action')){
        $errors->add('badNonce', 'Security Error!');
        if ($errors->get_error_code()){
            $json['result'] = false;
            die(json_encode( $json ));
        }
    }
    $json['result'] = mistheme_updatePricesRow($dataForm);
    echo json_encode( $json );
    die();
}

function mistheme_updatePricesRow($data){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'prices';
    $check_updated = $wpdb->update($tableName, $data, array('id'=> 1));
    if ($check_updated === FALSE){
        return false;
    } else {
        return true;
    }
}
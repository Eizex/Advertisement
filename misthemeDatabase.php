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
//Finance Here

function mistheme_getFinance(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'advertisement';
    $get_data = $wpdb->get_results("SELECT * FROM $tableName", 'ARRAY_A');
    return $get_data;
}




/*
 * Captain Stats
 */

function mistheme_getAllCaps(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'caps';
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

add_action( 'wp_ajax_nopriv_selectCapStat', 'mistheme_selectCapStat_callback' );
add_action( 'wp_ajax_selectCapStat', 'mistheme_selectCapStat_callback' );

function mistheme_selectCapStat_callback() {
    $json = array();
    $cap = $_POST['capname'];
    $startDate = date_format(date_time_set(date_create($_POST['startdate']),0,0,0),"Y-m-d H:i:s");
    $endDate = date_format(date_time_set(date_create($_POST['enddate']),23,59,59),"Y-m-d H:i:s");
    $endDate2 = date_format(date_time_set(date_create($_POST['startdate']),23,59,59),"Y-m-d H:i:s");
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
        $queryData = mistheme_getCapDailyUpdate($cap,$startDate,$endDate2);
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

add_action( 'wp_ajax_nopriv_selectUserStat', 'mistheme_selectUserStat_callback' );
add_action( 'wp_ajax_selectUserStat', 'mistheme_selectUserStat_callback' );

function mistheme_selectUserStat_callback() {
    $json = array();
    $startDate = date_format(date_time_set(date_create($_POST['startdate']),0,0,0),"Y-m-d H:i:s");
    $endDate = date_format(date_time_set(date_create($_POST['enddate']),23,59,59),"Y-m-d H:i:s");
    $event = $_POST['event'];
    $queryData = mistheme_getUserStat($event, $startDate,$endDate);
    $totalTXT = '';
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

    $json['result'] = $html;
    echo json_encode( $json );
    die();
}
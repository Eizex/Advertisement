﻿<?php
function mistheme_getFinance(){
    global $wpdb;
    $wpdb->hide_errors();
    $tableName = $wpdb->prefix.'finance';
    $get_data = $wpdb->get_results("SELECT * FROM $tableName", 'ARRAY_A');
    return $get_data;
}

function display_mistheme_adFinance_submenu() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
	$data = mistheme_getFinance();

	?>
<table class="table table-striped">
    <thead>
      <tr>
		<th>#</th>
        <th>الإعلان</th>
        <th>السعر</th>
        <th>المدفوع</th>
		<th>الباقي</th>
      </tr>
    </thead>
    <tbody>
	<?php
		foreach($data as $row){
	?>
	<tr>
		<td><?php echo $row['ad_id']; ?></td>
        <td><?php echo $row['ad_ar_name']; ?></td>
        <td><?php echo $row['ad_price']; ?></td>
        <td><?php echo $row['ad_paid']; ?></td>
		<td><?php echo $row['ad_price'] - $row['ad_paid']; ?></td>
     </tr>
	<?php
		}
	?>
      
    </tbody>
</table>
	
	<?php
	
}



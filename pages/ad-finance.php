<?php
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
	$prices = mistheme_getPrices();

    ?>
	<br>
	<br>
	<br>
		<h1> الأسعار والمدفوعات </h1> 

    <table class="table table-striped" width="50%" align="center">
        <thead>
        <tr>
            <th width="10%">#</th>
            <th width="20%">الإعلان</th>
            <th width="20%">السعر</th>
            <th width="30%">المدفوع</th>
            <th width="20%">الباقي</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row){
            ?>
            <tr>
                <td><?php echo $row['Ad_id']; ?></td>
                <td><?php echo $row['Ad_ar_name']; ?></td>
                <td><?php echo $row['Ad_price']; ?></td>
                <td>
						<form action="prices.php" method="Post">
						<input type="text" name="paidtext" style="width: 60px;" value="" placeholder="<?php echo $row['Ad_paid']; ?>">
						<input class="button button5" type="submit" name="submit" value="حفظ">
						</form>
				</td>
                <td><?php echo $row['Ad_price'] - $row['Ad_paid']; ?></td>
            </tr>
            <?php
        }
        ?>

        </tbody>
    </table>
	<br>
	<br>
	<br>
	

	
	
	
	<h1> تحديد الأسعار </h1> 
	<form action="prices.php" method="Post">
	<table style='align="center" border: 1px; width:40%'>
	  		
				
				<input type="hidden" name="site_name" value="<? echo $site_name; ?>">
				
				<tr><td>إعلان صورة لمدة يوم:</td><td><input type="text" name="picture_price" value="" placeholder="<?php echo $prices->picture_price; ?>"></td></tr>
				<tr><td>إعلان فيديو لمدة يوم:</td><td><input type="text" name="video_price" value="" placeholder="<?php echo $prices->video_price; ?>"></td></tr>
				<tr><td>أولوية إضافية:</td><td><input type="text" name="priority_price" value="" placeholder="<?php echo $prices->priority_price; ?>"></td></tr>
				<tr><td>عرض للكابتين:</td><td><input type="text" name="showtocap_price" value="" placeholder="<?php echo $prices->showtocap_price; ?>"></td></tr>
				<tr><td>ظهور على الخارطة للكابتين:</td><td><input type="text" name="mapcap_price" value="" placeholder="<?php echo $prices->mapcap_price; ?>"></td></tr>
				<tr><td>تنبيه الكابتين:</td><td><input type="text" name="notifycap_price" value="" placeholder="<?php echo $prices->notifycap_price; ?>"></td></tr>
				<tr><td>عرض للمستخدم:</td><td><input type="text" name="showtouser_price" value="" placeholder="<?php echo $prices->showtouser_price; ?>"></td></tr>
				<tr><td>تنبيه المستخدم:</td><td><input type="text" name="notifyuser_price" value="" placeholder="<?php echo $prices->notifyuser_price; ?>"></td></tr>
				<tr><td>ظهور على الخارطة للمستخدم:</td><td><input type="text" name="mapuser_price" value="" placeholder="<?php echo $prices->mapuser_price; ?>"></td></tr>
				<tr><td>100 ظهور للكابتين:</td><td><input type="text" name="cap100view_price" value="" placeholder="<?php echo $prices->cap100view_price; ?>"></td></tr>
				<tr><td>100 ظهور للمستخدم:</td><td><input type="text" name="user100view_price" value="" placeholder="<?php echo $prices->user100view_price; ?>"></td></tr>
				<tr><td>موقع إضافي على الخريطة:</td><td><input type="text" name="pluslocation_price" value="" placeholder="<?php echo $prices->pluslocation_price; ?>"></td></tr>
				<tr><td><br></td></tr>
				<tr><td align="left"> <input class="button button5" type="submit" name="submit" value="حفظ"></td><tr>
			
	</table>
	                          
	</form>
	
	
	
	

    <?php

}
<?php

function display_mistheme_adFinance_submenu() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $data = mistheme_getFinance();
	$prices = mistheme_getPrices();

    ?>
    <div class="wrap">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-center"> الأسعار والمدفوعات </h1>
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
                        <tr id="<?php echo 'paidAd'.$row['Ad_id']; ?>">
                            <td><?php echo $row['Ad_id']; ?></td>
                            <td><?php echo $row['Ad_ar_name']; ?></td>
                            <td><?php echo $row['Ad_price']; ?></td>
                            <td>
                                <form method="post" id="paid-form">
                                    <?php
                                    if ( function_exists( 'wp_nonce_field' ) )
                                        wp_nonce_field( 'adPaidSubmit_action', 'adPaidSubmit_nonce' );
                                    ?>
                                    <input type="text" name="ad_id" id="ad_id" value="<?php echo $row['Ad_id']; ?>" hidden="hidden" />
                                    <input type="text" name="Ad_price" id="Ad_price" value="<?php echo $row['Ad_price']; ?>" hidden="hidden" />
                                    <input type="text" name="paidtext" id="paid-<?php echo $row['Ad_id']; ?>" style="width: 60px;" value="<?php echo $row['Ad_paid']; ?>">
                                    <button class="btn btn-default btn-sm" type="submit" name="submit" name="paid_btn" id="paid_btn">حفظ</button>
                                </form>
                            </td>
                            <td id="remaining<?php echo $row['Ad_id']; ?>"><?php echo $row['Ad_price'] - $row['Ad_paid']; ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                    </tbody>
                </table>
            </div>
        </div>

        <br>
        <br>
        <br>

        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <h1 class="text-center"> تحديد الأسعار </h1>
                <form id="priceCustom" method="post">
                    <?php
                    if ( function_exists( 'wp_nonce_field' ) )
                        wp_nonce_field( 'adPriceCustom_action', 'adPriceCustom_nonce' );
                    ?>
                    <table class="table">
                        <tr>
                            <td>إعلان صورة لمدة يوم:</td>
                            <td><input type="text" class="form-control" name="picture_price" value="<?php echo $prices->picture_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>إعلان فيديو لمدة يوم:</td>
                            <td><input type="text" class="form-control" name="video_price" value="<?php echo $prices->video_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>أولوية إضافية:</td>
                            <td><input type="text" class="form-control" name="priority_price" value="<?php echo $prices->priority_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>عرض للكابتين:</td>
                            <td><input type="text" class="form-control" name="showtocap_price" value="<?php echo $prices->showtocap_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>ظهور على الخارطة للكابتين:</td>
                            <td><input type="text" class="form-control" name="mapcap_price" value="<?php echo $prices->mapcap_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>تنبيه الكابتين:</td>
                            <td><input type="text" class="form-control" name="notifycap_price" value="<?php echo $prices->notifycap_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>عرض للمستخدم:</td>
                            <td><input type="text" class="form-control" name="showtouser_price" value="<?php echo $prices->showtouser_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>تنبيه المستخدم:</td>
                            <td><input type="text" class="form-control" name="notifyuser_price" value="<?php echo $prices->notifyuser_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>ظهور على الخارطة للمستخدم:</td>
                            <td><input type="text" class="form-control" name="mapuser_price" value="<?php echo $prices->mapuser_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>100 ظهور للكابتين:</td>
                            <td><input type="text" class="form-control" name="cap100view_price" value="<?php echo $prices->cap100view_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>100 ظهور للمستخدم:</td>
                            <td><input type="text" class="form-control" name="user100view_price" value="<?php echo $prices->user100view_price; ?>"></td>
                        </tr>
                        <tr>
                            <td>موقع إضافي على الخريطة:</td>
                            <td><input type="text" class="form-control" name="pluslocation_price" value="<?php echo $prices->pluslocation_price; ?>"></td>
                        </tr>
                        <tr >
                            <td colspan="2">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <button class="btn btn-primary form-control" type="submit" name="submit">حفظ</button>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div id="priceAlertMsg"></div>
                </form>
            </div>
        </div>
    </div>

    <?php
}


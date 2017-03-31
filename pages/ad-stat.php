<?php
function display_mistheme_statAd_submenu()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    //var_dump(mistheme_getAllCaps());
    $capsData = mistheme_getAllCaps();
?>
    <div class="wrap">
        <div class="row cap-row">
            <div class="col-sm-3">
                <form role="form">
                    <div class="form-group">
                        <input id="searchinput" class="form-control" type="search" placeholder="Search..." />
                    </div>
                    <div id="searchlist" class="list-group">
                        <?php
                        foreach($capsData as $cap){
                            echo '<a class="list-group-item cap-item" href="" data-cap="'.$cap['capname'].'"><span>'. $cap['capname'] .'</span></a>';
                        }
                        ?>
                    </div>
                </form>
                <script>
                    jQuery('#searchlist').btsListFilter('#searchinput', {itemChild: 'span'});
                </script>
            </div>
            <div class="col-sm-9">
                <div class="cap-stat-container">
                    <div class="form stat-group col-sm-12 ">
                        <select id="event" class="input-group form-control" style="margin-bottom: 15px;">
                            <option value="captain_view">عدد مرات عرض الإعلانات</option>
                            <option value="captain_show">عدد مرات ظهور الإعلانات على الخريطة </option>
                            <option value="captain_ad_dist24">المسافة التي قطعها الكابتن خلال 24 ساعة</option>
                            <option value="captain_ad_distAd">المسافة التي قطعها الكابتن لكل إعلان</option>
                            <option value="captain_update">التحديث اليومي</option>
                        </select>
                        <div class="form-group" style="margin-bottom: 0px;">
                            <div class="input-group date-group-stat">
                                <input required type="text" class="form-control text-center" name="filterStartDate" id="filterStartDate" placeholder="تاريخ البداية" value="" data-statdate/>
                                <div class="input-group-addon">إلى</div>
                                <input required type="text" class="form-control text-center" name="filterEndDate" id="filterEndDate" placeholder="تاريخ النهاية" value="" data-statdate/>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-primary" id="adsFilterBtn">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="remoteData">

                    </div>
                </div>
            </div>
        </div>
        <div class="row user-row">
            <div class="col-sm-12">
                <div class="cap-stat-container">
                    <div class="col-sm-6 col-sm-offset-3" style="margin-top: 15px; padding-bottom: 15px;">
                        <div class="form">
                            <select id="user_event" class="input-group form-control" style="margin-bottom: 15px;">
                                <option value="user_show">الاعلانات التي ظهرت على الخريطة</option>
                                <option value="user_notify">الاعلانات التي ظهرت مع تنبيه</option>
                                <option value="user_browse">الاعلانات التي تم تصفحها</option>
                                <option value="user_click">الاعلانات التي ظهرت على الخريطة بعد النقر</option>
                            </select>
                            <div class="form-group" style="margin-bottom: 0px;">
                                <div class="input-group date-group-stat">
                                    <input required type="text" class="form-control text-center" name="userfilterStartDate" id="userfilterStartDate" placeholder="تاريخ البداية" value="" data-statdate/>
                                    <div class="input-group-addon">إلى</div>
                                    <input required type="text" class="form-control text-center" name="userfilterEndDate" id="userfilterEndDate" placeholder="تاريخ النهاية" value="" data-statdate/>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary" id="userAdsFilterBtn">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="remoteUserData">

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
<?php
function display_mistheme_newAd_submenu(){
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $Ad_en_name = 'الاسم بالانجليزية';
    $Ad_ar_name = 'الاسم بالعربية';
    $Ad_link = 'رابط المادة الإعلانية';
    $Ad_link_type = 'نوع المادة الإعلانية';
    $Ad_type = 'نوع الإعلان';
    $Ad_priority = 'الأولوية';
    $Ad_start_date = 'تاريخ البداية';
    $Ad_end_date = 'تاريخ النهاية';
    $Ad_startEndDate = 'تاريخ البداية والنهاية';
    $Ad_locations = 'الموقع';
    $Ad_show_to_captain = 'إظهار للكابتن';
    $Ad_show_to_user = 'إظهار للمستخدم';
    $Ad_cap_notify = 'تنبيه الكابتن';
    $Ad_user_notify = 'تنبيه المستخدم';
    $Ad_showonmap_captain = 'اظهار على الخريطة للكابتن';
    $Ad_showonmap_user = 'اظهار على الخريطة للمستخدم';
    $Ad_cap_view_no = 'عدد مرات الظهور اليومي للكابتن';
    $Ad_user_view_no = 'عدد مرات الظهور اليومي للمستخدم';
    $Advertiser_name = 'الاسم';
    $Advertiser_type = 'النوع';
    $Advertiser_phone = 'الهاتف';
    $Advertiser_email = 'الايميل';
    $Advertiser_address = 'العنوان';
    $Advertiser_website = 'الموقع الالكتروني';
    $Advertiser_rep_name = 'الاسم';
    $Advertiser_rep_phone = 'الهاتف';
    $Advertiser_rep_email = 'الايميل';
    $Advertiser_rep_type = 'النوع';

    $label_class = 'col-sm-3';
    $input_class = 'col-sm-9';

    $ad_id = null;
    $Ad_object = null;
    if(isset($_GET['id']) && $_GET['id'] != ''){
        $ad_id = $_GET['id'];
        $Ad_object = mistheme_getSingleAd($ad_id);
    }

    $objectShowtoCap = 0;
    $objectShowtoUser = 0;
    $objectNotifyCap = 0;
    $objectNotifyUser = 0;
    $objectMapCap = 0;
    $objectMapUser = 0;
    $objectAdRepType = 1;
    $objectAdType = 1;
    $objectAderType = 1;
    if($Ad_object != null){
        $objectShowtoCap = $Ad_object->Ad_show_to_captain;
        $objectShowtoUser = $Ad_object->Ad_show_to_user;
        $objectNotifyCap = $Ad_object->Ad_cap_notify;
        $objectNotifyUser = $Ad_object->Ad_user_notify;
        $objectAderType = $Ad_object->Advertiser_type;
        $objectAdType = $Ad_object->Ad_type;
        $objectAdRepType = $Ad_object->Advertiser_rep_type;
        $objectMapCap = $Ad_object->Ad_showonmap_captain;
        $objectMapUser = $Ad_object->Ad_showonmap_user;
    }

    //var_dump($ad_id);
    ?>
    <div class="wrap">
        <form method="post" class="form-horizontal" id="AdForm">
            <div id="" class="col-md-10 col-md-offset-1" style="margin-top: 30px;">
                <div class="panel panel-default" id="scrollTo">
                    <div class="panel-heading" style="padding-bottom: 0px; border-bottom: 0px solid transparent;">
                        <ul class="nav nav-tabs" id="slide-nav">
                            <li role="presentation" class="active"><a href="#" data-target="slide-1">المعلومات الأساسية</a></li>
                            <li role="presentation"><a href="#" data-target="slide-2">المادة الإعلانية</a></li>
                            <li role="presentation"><a href="#" data-target="slide-3">المواقع الجغرافية</a></li>
                            <li role="presentation"><a href="#" data-target="slide-4">إعدادات الكابتن والمستخدم</a></li>
                            <li role="presentation"><a href="#" data-target="slide-5">الجهة المعلنة</a></li>
                            <li role="presentation"><a href="#" data-target="slide-6">ممثل الجهة المعلنة</a></li>
                        </ul>
                    </div>
                    <div class="panel-body" id="wizardSlides">
                        <div class="panel panel-default activeSlide" id="slide-1">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center">المعلومات الأساسية</h3>
                            </div>
                            <div class="panel-body">
                                <section>
                                    <?php
                                    if ( function_exists( 'wp_nonce_field' ) )
                                        wp_nonce_field( 'AdFormSubmit_action', 'AdFormSubmit_nonce' );
                                    ?>
                                    <input hidden="hidden" name="Ad_id" id="Ad_id" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_id : ''; ?>"/>
                                    <div class="form-group">
                                        <label for="Ad_ar_name" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_ar_name; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Ad_ar_name" title="<?php echo $Ad_ar_name; ?>" id="Ad_ar_name" placeholder="<?php echo $Ad_ar_name; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_ar_name : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Ad_en_name" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_en_name; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Ad_en_name" id="Ad_en_name" placeholder="<?php echo $Ad_en_name; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_en_name : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Ad_type" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_type; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <select required class="form-control" name="Ad_type" id="Ad_type">
                                                <option  value="1" <?php selected( $objectAdType, 1 ); ?>>فعالية</option>
                                                <option value="2" <?php selected( $objectAdType, 2 ); ?>>دعاية</option>
                                                <option value="3" <?php selected( $objectAdType, 3 ); ?>>عروض ترويجية</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Ad_priority" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_priority; ?></label>
                                        <div class="<?php echo $input_class; ?>" dir="ltr">
                                            <input required id="Ad_priority" name="Ad_priority" type="number" class="rating" min=0 max=5 step=1 data-size="xs" data-rtl="true" data-show-caption="false" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_priority : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Ad_start_date" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_startEndDate; ?></label>
                                        <div class="<?php echo $input_class; ?> input-group date-group" style="padding-left: 15px; padding-right: 15px;">
                                            <input required type="text" class="form-control text-center" name="Ad_start_date" id="Ad_start_date" placeholder="<?php echo $Ad_start_date; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_start_date : ''; ?>" data-date/>
                                            <div class="input-group-addon">إلى</div>
                                            <input required type="text" class="form-control text-center" name="Ad_end_date" id="Ad_end_date" placeholder="<?php echo $Ad_end_date; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_end_date : ''; ?>" data-date/>
                                        </div>
                                    </div>

                                </section>
                            </div>
                        </div>
                        <div class="panel panel-default" id="slide-2">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center">المادة الإعلانية</h3>
                            </div>
                            <div class="panel-body">
                                <section>
                                    <?php
                                    $Ad_objMedia = false;
                                        if($Ad_object != null){
                                            if(($Ad_object->Ad_link_type != '' || $Ad_object->Ad_link_type != null) && ($Ad_object->Ad_link != '' || $Ad_object->Ad_link != null) ){
                                                $Ad_objMedia = true;
                                            }
                                        }
                                    ?>
                                    <div class="col-sm-offset-1 col-sm-10">
                                        <div class="well <?php echo $Ad_objMedia ? '': 'hidden'; ?>" id="Ad_preview">
                                            <?php
                                            if($Ad_objMedia){
                                                if($Ad_object->Ad_link_type == '1'){?>
                                                    <img src="<?php echo $Ad_object->Ad_link ?>" alt="..." class="img-thumbnail">
                                                <?php }elseif($Ad_object->Ad_link_type == '2'){ ?>
                                                    <video width="100%" height="auto" class="img-thumbnail" controls>'+
                                                        <source src="<?php echo $Ad_object->Ad_link ?>">
                                                        Your browser does not support the video tag
                                                    </video>
                                                <?php }
                                            }
                                            ?>
                                        </div>
                                        <button type="button" id="uploadAd" class="btn btn-success btn-block <?php echo $Ad_objMedia ? 'hidden': ''; ?>">
                                            <span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
                                            <span>اضف المادة الإعلانية</span>
                                        </button>
                                        <div class="btn-group btn-group-justified <?php echo $Ad_objMedia ? '': 'hidden'; ?>" role="group" id="AdRemoveChangeGroup">
                                            <div class="btn-group" role="group">
                                                <button type="button" id="clearUploadAd" class="btn btn-danger">
                                                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                    <span>إحذف المادة الإعلانية</span>
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" id="uploadAd" class="btn btn-primary">
                                                    <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                                                    <span>تغيير المادة الإعلانية</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input required type="text" hidden="hidden" name="Ad_link_type" id="Ad_link_type" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_link_type : ''; ?>"/>
                                        <input required type="text" hidden="hidden" name="Ad_link" id="Ad_link" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_link : ''; ?>"/>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="panel panel-default" id="slide-3">
                            <?php
                            if($Ad_object != null){
                                $locationString = $Ad_object->Ad_locations;
                                $locationArray = explode(':',$locationString);
                                $locationObject = null;
                                foreach($locationArray as $location){
                                    $location = explode(',',$location);
                                    $location = "{lat: ". $location[0] . ", lng: " .$location[1]."},";
                                    $locationObject .= $location;
                                }
                                $locationObject = "[".$locationObject."]";

                            }
                            ?>
                            <div class="panel-heading">
                                <h3 class="panel-title text-center">المواقع الجغرافية</h3>
                            </div>
                            <div class="panel-body">
                                <section>
                                    <div style="width: 100%;height: 400px" class="">
                                        <div id="map"></div>
                                    </div>
                                    <script async defer type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVnu-mKrNr3kmhixEBLE8WBU_Rd2Beiy8"></script>
                                    <script>
                                        var labelIndex = 1;
                                        var allMarkers = [];
                                        var allLocations = <?php echo $Ad_object!=null? $locationObject :"[]";?>;
                                        var locationString;
                                        var map;
                                        function myMap() {
                                            var mapCanvas = document.getElementById("map");
                                            var myCenter = new google.maps.LatLng(24.647017162630366,44.589385986328124);
                                            var mapOptions = {center: myCenter, zoom: 5,streetViewControl: false};
                                            map = new google.maps.Map(mapCanvas, mapOptions);
                                            google.maps.event.addListener(map, 'rightclick', function(event) {
                                                var conf= window.confirm("هل تريد أضافة هذا الموقع؟");
                                                if (conf == true){
                                                    //allMarkers.push(event.latLng);
                                                    placeMarker(event.latLng);
                                                    showMapMarkers(map);
                                                }
                                                //console.log(allMarkers);
                                                //console.log(event);
                                                //console.log(map);
                                            });
                                            if(allLocations.length > 0){
                                                for (var i = 0; i < allLocations.length; i++){
                                                    placeMarker(allLocations[i]);
                                                }
                                            }
                                            showMapMarkers(map);

                                        };

                                        function showMapMarkers(map){
                                            locationString = "";
                                            for (var i = 0; i < allMarkers.length; i++){
                                                allMarkers[i].setMap(map);
                                                allMarkers[i].setLabel((i+1).toString());
                                                var markerLatLng = allMarkers[i].getPosition();
                                                if(i == allMarkers.length - 1){
                                                    locationString += markerLatLng.lat() + "," + markerLatLng.lng();
                                                }else{
                                                    locationString += markerLatLng.lat() + "," + markerLatLng.lng() + ":";
                                                }
                                            }
                                            jQuery("#Ad_locations").val(locationString).attr('value',locationString);
                                        }

                                        function placeMarker(location) {
                                            var marker = new google.maps.Marker({
                                                position: location,
                                                draggable:true,
                                            });
                                            allMarkers.push(marker);
                                            //labelIndex++;
                                            google.maps.event.addListener(marker, 'rightclick', function(event){
                                                var conf = window.confirm("هل تريد حذف هذا الموقع؟");
                                                if(conf == true){
                                                    var markerIndex = Number(marker.getLabel()) - 1;
                                                    showMapMarkers(null);
                                                    allMarkers.splice(markerIndex,1);
                                                    showMapMarkers(map);
                                                    //console.log(allMarkers);
                                                }
                                            });
                                            google.maps.event.addListener(marker,'dragend',function(event){
                                                var markerIndex = Number(marker.getLabel() - 1);
                                                showMapMarkers(null);
                                                allMarkers[markerIndex] = marker;
                                                showMapMarkers(map);
                                            });
                                        }

                                    </script>
                                    <div class="form-group">
                                        <input required type="text" class="form-control hidden" id="Ad_locations" placeholder="<?php echo $Ad_locations; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_locations : ''; ?>"/>
                                    </div>

                                </section>
                            </div>
                        </div>
                        <div class="panel panel-default" id="slide-4">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center">إعدادت الكابتن والمستخدم</h3>
                            </div>
                            <div class="panel-body">
                                <section>
                                    <div class="well">
                                        <div class="form-group">
                                            <label for="Ad_show_to_captain" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_show_to_captain; ?></label>
                                            <div class="radio-inline">
                                                <label>
                                                    <input required type="radio" name="Ad_show_to_captain" id="Ad_show_to_captain1" value="1" <?php checked( $objectShowtoCap, 1); ?>/>
                                                    نعم
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input required type="radio" name="Ad_show_to_captain" id="Ad_show_to_captain2" value="0" <?php checked( $objectShowtoCap, 0); ?>/>
                                                    لا
                                                </label>
                                            </div>
                                        </div>
                                        <div id="capOpt" <?php echo $objectShowtoCap == 0 ? 'style="display: none;"':''; ?>>
                                            <div class="form-group">
                                                <label for="Ad_cap_notify" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_cap_notify; ?></label>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_cap_notify" id="Ad_cap_notify1" value="1" <?php checked( $objectNotifyCap, 1); ?>/>
                                                        نعم
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_cap_notify" id="Ad_cap_notify2" value="0" <?php checked( $objectNotifyCap, 0); ?>/>
                                                        لا
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Ad_showonmap_captain" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_showonmap_captain; ?></label>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_showonmap_captain" id="Ad_showonmap_captain1" value="1" <?php checked( $objectMapCap, 1); ?>/>
                                                        نعم
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_showonmap_captain" id="Ad_showonmap_captain2" value="0" <?php checked( $objectMapCap, 0); ?>/>
                                                        لا
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Ad_cap_view_no" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_cap_view_no; ?></label>
                                                <div class="<?php echo $input_class; ?>">
                                                    <input required type="number" class="form-control" name="Ad_cap_view_no" id="Ad_cap_view_no" placeholder="<?php echo $Ad_cap_view_no; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_cap_view_no : '0'; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="well">
                                        <div class="form-group">
                                            <label for="Ad_show_to_user" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_show_to_user; ?></label>
                                            <div class="radio-inline">
                                                <label>
                                                    <input required type="radio" name="Ad_show_to_user" id="Ad_show_to_user1" value="1" <?php checked( $objectShowtoUser, 1); ?>/>
                                                    نعم
                                                </label>
                                            </div>
                                            <div class="radio-inline">
                                                <label>
                                                    <input required type="radio" name="Ad_show_to_user" id="Ad_show_to_user2" value="0" <?php checked( $objectShowtoUser, 0); ?>/>
                                                    لا
                                                </label>
                                            </div>
                                        </div>
                                        <div id="userOpt" <?php echo $objectShowtoUser == 0 ? 'style="display: none;"':''; ?>>
                                            <div class="form-group">
                                                <label for="Ad_user_notify" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_user_notify; ?></label>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_user_notify" id="Ad_user_notify1" value="1" <?php checked( $objectNotifyUser, 1); ?> />
                                                        نعم
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_user_notify" id="Ad_user_notify2" value="0" <?php checked( $objectNotifyUser, 0); ?> />
                                                        لا
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Ad_showonmap_user" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_showonmap_user; ?></label>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_showonmap_user" id="Ad_showonmap_user1" value="1" <?php checked( $objectMapUser, 1); ?>/>
                                                        نعم
                                                    </label>
                                                </div>
                                                <div class="radio-inline">
                                                    <label>
                                                        <input required type="radio" name="Ad_showonmap_user" id="Ad_showonmap_user2" value="0" <?php checked( $objectMapUser, 0); ?>/>
                                                        لا
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="Ad_user_view_no" class="<?php echo $label_class; ?> control-label"><?php echo $Ad_user_view_no; ?></label>
                                                <div class="<?php echo $input_class; ?>">
                                                    <input required type="number" class="form-control" name="Ad_user_view_no" id="Ad_user_view_no" placeholder="<?php echo $Ad_user_view_no; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Ad_user_view_no : '0'; ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="panel panel-default" id="slide-5">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center">الجهة المعلنة</h3>
                            </div>
                            <div class="panel-body">
                                <section>
                                    <div class="form-group">
                                        <label for="Advertiser_name" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_name; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Advertiser_name" id="Advertiser_name" placeholder="<?php echo $Advertiser_name; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_name : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_type" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_type; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <select required class="form-control" name="Advertiser_type" id="Advertiser_type">
                                                <option value="1" <?php selected( $objectAderType, 1 ); ?>>جهة حكومية</option>
                                                <option value="2" <?php selected( $objectAderType, 2 ); ?>>جهة خاصة</option>
                                                <option value="3" <?php selected( $objectAderType, 3 ); ?>>جهة خيرية</option>
                                                <option value="4" <?php selected( $objectAderType, 4 ); ?>>اخرى</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_phone" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_phone; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Advertiser_phone" id="Advertiser_phone" placeholder="<?php echo $Advertiser_phone; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_phone : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_email" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_email; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="email" class="form-control" name="Advertiser_email" id="Advertiser_email" placeholder="<?php echo $Advertiser_email; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_email : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_address" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_address; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Advertiser_address" id="Advertiser_address" placeholder="<?php echo $Advertiser_address; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_address : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_website" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_website; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="URL" class="form-control" name="Advertiser_website" id="Advertiser_website" placeholder="<?php echo $Advertiser_website; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_website : ''; ?>"/>
                                            <div class="help-block text-left">http://www.example.com</div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <div class="panel panel-default" id="slide-6">
                            <div class="panel-heading">
                                <h3 class="panel-title text-center">ممثل الجهة المعلنة</h3>
                            </div>
                            <div class="panel-body">
                                <section>
                                    <div class="form-group">
                                        <label for="Advertiser_rep_name" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_rep_name; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Advertiser_rep_name" id="Advertiser_rep_name" placeholder="<?php echo $Advertiser_rep_name; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_rep_name : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_rep_type" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_rep_type; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <select required class="form-control" name="Advertiser_rep_type" id="Advertiser_rep_type">
                                                <option value="1" <?php selected( $objectAdRepType, 1 ); ?>>مباشر</option>
                                                <option value="2" <?php selected( $objectAdRepType, 2 ); ?>>وسيط</option>
                                                <option value="3" <?php selected( $objectAdRepType, 3 ); ?>>مندوب</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_rep_phone" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_rep_phone; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="text" class="form-control" name="Advertiser_rep_phone" id="Advertiser_rep_phone" placeholder="<?php echo $Advertiser_rep_phone; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_rep_phone : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="Advertiser_rep_email" class="<?php echo $label_class; ?> control-label"><?php echo $Advertiser_rep_email; ?></label>
                                        <div class="<?php echo $input_class; ?>">
                                            <input required type="email" class="form-control" name="Advertiser_rep_email" id="Advertiser_rep_email" placeholder="<?php echo $Advertiser_rep_email; ?>" value="<?php echo ($Ad_object!= null) ? $Ad_object->Advertiser_rep_email : ''; ?>"/>
                                        </div>
                                    </div>

                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-default pull-right" id="Ad_prevSlide" disabled="disabled">
                                    السابق
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <div class="tooltip-wrapper disabled">
                                    <button type="submit" class="btn btn-default form-control" role="button" id="adsFormSubmit">حفظ</button>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-default pull-left" id="Ad_nextSlide">
                                    التالي
                                </button>
                            </div>
                        </div>



                    </div>
                </div>
                <div id="msgBox"></div>
            </div>
        </form>
    </div>
    <?php
}
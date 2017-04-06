<?php
function display_mistheme_adCaptains_submenu() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    $data = mistheme_getAllCaps();
    ?>
    <div class="wrap">
        <div class="col-md-12">
            <div class="well" style="margin-top: 10px;">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#capModal">
                    <span class="glyphicon glyphicon-plus"></span>
                    أضف كابتن جديد
                </button>

                <div class="modal fade" id="capModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-sm" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">أضف كابتن جديد</h4>
                            </div>
                            <div class="modal-body">
                                <form id="newCapFrom">
                                    <?php
                                    if ( function_exists( 'wp_nonce_field' ) )
                                        wp_nonce_field( 'capNew_action', 'capNew_nonce' );
                                    ?>
                                    <div class="form-group">
                                        <label for="recipient-name" class="control-label">اسم الكابتن</label>
                                        <input type="text" class="form-control" id="capName">
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="control-label">كلمة السر</label>
                                        <input type="text" class="form-control" id="capPassword">
                                    </div>
                                </form>
                                <div id="capAlertMsg"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">إلغاء</button>
                                <button type="button" id="newCapSubmit" class="btn btn-primary">حفظ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ( function_exists( 'wp_nonce_field' ) )
                wp_nonce_field( 'capTable_action', 'capTable_nonce' );
            ?>
            <table id="capTable"></table>

        </div>
        <script>
            var capTableElement = jQuery('#capTable');
            capTableElement.bootstrapTable({
                columns: [{
                    field: 'Cap_ID',
                    title: '#',
                    width: '50px',
                    class: 'text-center',
                }, {
                    field: 'Cap_Username',
                    title: 'اسم الكابتن',
                    //formatter: adLinkTypeFormatter,
                    class: 'text-center',
                },{
                    field: 'Cap_Password',
                    title: 'كلمة السر',
                    //formatter: AdTypeFormatter,
                    class: 'text-center',
                },{
                    field: 'Cap_WorkingTime',
                    title: 'ساعات العمل',
                    class: 'text-center',
                }, {
                    field: 'Cap_WorkingDistance',
                    title: 'المسافة المقطوعة',
                    class: 'text-center',
                }, {
                    field: 'Cap_ID',
                    title: 'إعدادات',
                    formatter: optionFormatter,
                    width: '50px',
                    class: 'text-center',
                }
                ],
                ajax: 'capTable',
                sidePagination: 'server',
                detailView: true,
                detailFormatter: detailFormatter,
                formatNoMatches: noMatchesFormatter,
            });
            function noMatchesFormatter(){
                return 'لا يوجد معلومات للعرض '+'<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#capModal"><span class="glyphicon glyphicon-plus"></span> أضف كابتن جديد</button>' ;

            }
            function capTable (params){
                var contents = {
                    action:	'capTableData',
                };
                jQuery.post( admin_ajax.url, contents, function(data){
                    params.success(data);
                }, 'json');
            }
            function optionFormatter(value, row, index) {
                //console.log(value);
                return [
                    '<button class="btn btn-danger btn-xs" id="deleteCap" data-id="'+ value +'">',
                        '<span>حذف</span>',
                        '<i class="glyphicon glyphicon-remove"></i>',
                    '</button>'
                ].join('');
            }
            function detailFormatter(index, row){
                return [
                    '<form id="capPassForm" class="form-inline">',
                        '<?php
                        if ( function_exists( 'wp_nonce_field' ) )
                            wp_nonce_field( 'capPassWordSubmit_action', 'capPassWordSubmit_nonce' );
                        ?>',
                        '<div class="form-group" style="margin-left: 15px;">',
                            '<label for="capPassword" style="margin: 0px; padding: 7px 5px 7px 15px;">كلمة السر الجديدة</label>',
                            '<input type="text" class="form-control" name="capPassword" id="capPassword" placeholder="كلمة السر الجديدة">',
                        '</div>',
                        '<input type="hidden" class="form-control" name="capId" id="capId" value="',row.Cap_ID,'">',
                        '<input type="hidden" class="form-control" name="rowIndex" id="rowIndex" value="',index,'">',
                        '<button type="submit" name="submitBtn" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i></button>',
                    '</form>'
                ].join('');
            }
        </script>
    </div>
    <?php
}
<?php
function display_mistheme_adCaptains_submenu() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }

    $data = mistheme_getCaptains();


    ?>
    <br>
    <br>
    <br>
    <h1>  الكابتين </h1>

    <table class="table table-striped" width="50%" align="center">
        <thead>
        <tr>
            <th width="30px">#</th>
            <th width="40px">اسم المستخدم</th>
            <th width="40px">كلمة المرور</th>
            <th width="40px">ساعات العمل</th>
            <th width="40px">المسافة المقطوعة</th>
            <th width ="50px">إعدادات</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row){
            ?>
            <tr>
                <td><?php echo $row['Cap_ID']; ?></td>
                <td><?php echo $row['Cap_Username']; ?></td>
                <td><?php echo $row['Cap_Password']; ?></td>
                <td><?php echo $row['Cap_WorkingTime']; ?></td>
                <td><?php echo $row['Cap_WorkingDistance']; ?></td>
                <td><a class="btn btn-warning btn-xs" href="<?php menu_page_url( 'ads-new-submenu', true );  ?>&id=" id="editCap">
                        <i class="glyphicon glyphicon-pencil"></i> تعديل
                    </a>
                    <a class="btn btn-danger btn-xs" href="#" id="deleteCap" data-id="">
                        <i class="glyphicon glyphicon-remove"></i> حذف
                    </a>


            </tr>

            <?php
        }
        ?>

        </tbody>
    </table>
    <br>
    <br>
    <br>





    <h1> إضافة كابتين </h1>
    <form action="prices.php" method="Post">
        <table style='align="center" border: 1px; width:40%'>




            <tr><td>إسم المستخدم:</td><td><input type="text" name="Cap_Username" value=""></td></tr>
            <tr><td>كلمة المرور :</td><td><input type="text" name="Cap_Password" value=""></td></tr>
            <tr><td><br></td></tr>
            <tr><td align="left"> <input class="button button5" type="submit" name="submit" value="حفظ"></td><tr>

        </table>

    </form>





    <?php

}
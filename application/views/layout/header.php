<?php if ($this->session->userdata('login') != true) {
    redirect('login');
} ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <title><?php if (isset($title)) {
                echo $title;
            } ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="<?php echo base_url() ?>assets/css/jquery-ui.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/ui.jqgrid.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/select2.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/pnotify.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/main.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/jquery.autocomplete.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/bootstrap_upload/bootstrap-fileupload.min.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/jquery.treeview.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/uploadfile.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/samping.css" rel="stylesheet" media="screen">

    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery-ui-1.10.3.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/grid.locale-id.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jqgrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/select2.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/formatCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/pnotify.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/numeral.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/numeral.id.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.tmpl.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/knockout.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/knockout.validation.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/app.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/pagu.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/searchAdvance.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/bootstrap-fileupload.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/moment.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.treeview.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.uploadfile.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/grid-autoNumeric.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.nestable.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/viewerjs/viewer.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/viewerjs/PluginLoader.js"></script>
    <script type="text/javascript" src="https://livejs.com/live.js"></script>

    <script type="text/javascript">
        var root = '<?php echo base_url(); ?>';
        var modul = '<?php echo (isset($modul) ? $modul : ' '); ?>';
    </script>
</head>

<body>
    <!--START NAVBAR -->
    <div class="navbar navbar-inverse navbar-fixed-top" style="background: #2A5DC4;">
        <div class="navbar-inner" style="background: #2A5DC4;;">
            <div class="container" style="background: #2A5DC4; margin-top: 7px">

                <a class="brand poppins-extrabold" style="color: #ffffff;font-weight: 500; " href="#"><?php echo PRODUCT_NAME ?></a>
                <span class="brand poppins-extrabold" style="; color: #FFFFFF; font-weight: 500; pointer-events: none;"><?php echo isset($nama_pemda) ? $nama_pemda : "" ?></span>
                <span class="brand poppins-extrabold" style="color: #ffffff; font-weight: 500"><?php echo isset($tahun) ? $tahun : "" ?>
                    -
                    <?php echo isset($status) ? $status : "" ?></span>
                <div class="nav-collapse">
                    <ul class="nav pull-right">
                        <li class="dropdown">
                            <a href="pages.html" class="dropdown-toggle" data-toggle="dropdown">
                                <span style="padding-right:10px; width:30px;">

                                    <?php $icon = $this->Group_model->get_one('ICON', 'USERS', 'ID = ' .
                                        $this->session->userdata('id_user'));
                                    if ($icon != '') { ?>
                                        <img src="<?php echo base_url() . 'assets/img/user/' . $icon; ?>" style="width:30px;" alt="" />
                                    <?php
                                    } else {
                                    ?>
                                        <img src="<?php echo base_url(); ?>assets/img/user.png" style="width:30px;" alt="" />
                                    <?php } ?>

                                </span>
                                <?php //echo isset($nama_operator) ? $nama_operator : "" $string =
                                $this->session->userdata('nama_operator');
                                $string = character_limiter(
                                    $string,
                                    10
                                );
                                echo $string; ?>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo base_url() ?>auth/user/<?php echo $this->session->userdata('id_user'); ?>">
                                        <i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-user"></i>My Account</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>auth/group/<?php echo $this->session->userdata('id_user'); ?>">
                                        <i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-lock"></i>Privacy Settings</a>
                                </li>
                                <li>
                                    <a href="error.htm">
                                        <i style="font-size:14px; padding-top:3px; padding-right:5px;" class="icon-cog"></i>System Settings</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>logout" title="Logout" class="medium twitter button radius" style="text-decoration:none;">
                                        <i style="font-size:16px; padding-top:3px; padding-right:5px;" class="icon-off"></i>Log out
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- START SUB-NAVBAR -->

    <div class="sidebar">
        <ul>
            <div class="kotakAwal">
                <a href="<?php echo base_url() ?>/home" style="color: #333;">
                    <i class="textAwal"></i>Beranda</a>
                <?php $menu =
                    $this->Group_model->get_backend_menu($this->session->userdata('group'));
                foreach ($menu as $x => $value) {
                    if ($value['child']) { ?>
            </div>
            <div class="kotakAwal">
                <div class="dropdown" style="margin-top: 20px;">
                    <a href="<?php echo base_url() . $value['menu_link']; ?>" class="dropdown-toggle" data-toggle="dropdown" style="color: #333;">
                        <i></i><?php echo $value['menu_title']; ?>
                        <b class="caret"></b>
                    </a>

                    <ul class="dropdown-menu">
                        <?php foreach ($value['child'] as $key => $child) {
                            if ($child['AKSI'] == 0)
                                continue;
                            if ($child['TITLE'] == '---') { ?>
                                <li class="divider"></li>

                            <?php
                            } else {
                            ?>
                                <li>
                                    <a href="<?php echo base_url() . $child['LINK']; ?>" style="color: #333;">
                                        <?php echo $child['TITLE']; ?>
                                    </a>
                                </li>
                        <?php }
                        } ?>
                    </ul>
                </div>
            <?php
                    } else {

            ?>
                <div style="margin-top: 18px;">
                    <a href="<?php echo base_url() . $value['menu_link']; ?>" style="color: #333;">
                        <i style="left: 15%;"></i><?php echo $value['menu_title']; ?>
                    </a>
                </div>

        <?php
                    }
                }
        ?>
        </ul>
    </div>
    <!--END NAVBAR -->

    <!--START MAIN-CONTENT -->
    <div style="margin-top: 25px;"></div>
    <div id="notification" class="notification">
        <span id="notification-text"></span>
    </div>
    <div class="container" style="padding-bottom:50px">
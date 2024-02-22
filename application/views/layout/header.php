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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
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

<body style>
    <!--START NAVBAR -->
    <div class="navbar navbar-inverse navbar-fixed-top" style="height: 55px;">
        <div class="navbar-inner" style="background: #2A5DC4;height: 55px;">
            <div class="container" style="background: #2A5DC4;height: 45px; margin-top: 7px">

                <a class="brand poppins-extrabold" style="color: #ffffff;font-weight: 500; " href="<?php echo base_url() ?>/home"><?php echo PRODUCT_NAME ?></a>
                <div class="brand poppins-extrabold" style="color: #FFFFFF; font-weight: 500;;">
                    <a style="color: #FFFFFF;pointer-events: visible;text-decoration:none" href="https://merauke.go.id/"><?php echo isset($nama_pemda) ? $nama_pemda : "" ?></a>
                </div>

                <span class="brand poppins-extrabold" style="color: #ffffff; font-weight: 500"><?php echo isset($tahun) ? $tahun : "" ?>
                    <?php echo isset($status) ? $status : "" ?></span>
                <div class="nav-collapse">
                    <ul class="nav pull-right">
                        <li class="dropdown" style="bottom: 15px;">
                            <a href="pages.html" class="dropdown-toggle" data-toggle="dropdown" style="background-color: rgba(255, 235, 255, 0); height:62.5px">
                                <span style="width:30px;">

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
                                <b class="caret" style="margin-top: 20px;"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo base_url() ?>auth/user/<?php echo $this->session->userdata('id_user'); ?>">
                                        <i style="font-size:14px; padding-top:3px; padding-right:20px;" class="icon-user"></i>My Account</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>auth/group/<?php echo $this->session->userdata('id_user'); ?>">
                                        <i style="font-size:14px; padding-top:3px; padding-right:20px;" class="icon-lock"></i>Privacy Settings</a>
                                </li>
                                <li>
                                    <a href="<?php echo base_url() ?>logout" title="Logout" class="medium twitter button radius" style="text-decoration:none;">
                                        <i style="font-size:16px; padding-top:3px; padding-right:20px;" class="icon-off"></i>Log out
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
    <div class="menu-btn" style="margin-top: 35px;margin-right: 100px;position: -ms-page;">
        <i class="fas fa-bars"></i>
    </div>
    <div class="side-bar" style="margin-top:55px; ">
        <div class="close-btn" style="margin-top: 15px; ">
            <i class="fas fa-times"></i>
        </div>
        <ul>
            <div class="item" style="margin-top: 20px; margin-left: 5px;">
                <a href="<?php echo base_url() ?>/home" class="sidebar-link" style="color: #333;">
                    <i></i>Beranda</a>

                <?php $menu =
                    $this->Group_model->get_backend_menu($this->session->userdata('group'));
                foreach ($menu as $x => $value) {
                    if ($value['child']) { ?>
            </div>
            <div class="item">
                <div style="margin-top: 20px;">
                    <a class="sub-btn" style="color: #333;cursor: pointer;">
                        <i style="margin-right: 5px;"></i><?php echo $value['menu_title']; ?>
                        <b class="fas fa-angle-right dropdown"></b>
                    </a>

                    <ul class="sub-menu">
                        <?php foreach ($value['child'] as $key => $child) {
                            if ($child['AKSI'] == 0)
                                continue;
                            if ($child['TITLE'] == '---') { ?>
                                <li style="margin-top: 10px;"></li>

                            <?php
                            } else {
                            ?>
                                <li style="margin-top: 10px;">
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
                <div style="margin-top: 18px; margin-left: 5px;">
                    <a href="<?php echo base_url() . $value['menu_link']; ?>" style="color: #333;" ">
                            <i style=" left: 15%;"></i><?php echo $value['menu_title']; ?>
                    </a>
                </div>

        <?php
                    }
                }
        ?>
        </ul>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            function handleWindowResize() {
                if ($(window).width() <= 1400) { 
                $('.side-bar').removeClass('active'); 
                } else {
                    $('.side-bar').addClass('active'); 
                    $('.close-btn').prop('disabled', false);
                }
            }
            function handleWindowResizeHam() {
                if ($(window).width() <= 1050) { 
                $('.menu-btn').removeClass('active'); 
                } else {
                    $('.menu-btn').addClass('active');
                }
            }

            // Call the function initially
            handleWindowResize();
            handleWindowResizeHam();
            // Add event listener for window resize
            $(window).resize(function() {
                handleWindowResize();
                handleWindowResizeHam();
            });
            // Prevent default behavior of sidebar links
            $('.sidebar-link')
                .click(function(e) {
                    e.preventDefault();
                    // Add additional logic if needed For example, navigate to the link using
                    // JavaScript
                    window.location.href = $(this).attr('href');
                });

            // jQuery for toggle sub menus
            $('.sub-btn').click(function() {
                // Toggle the visibility of the clicked sub-menu
                $(this)
                    .next('.sub-menu')
                    .slideToggle();
                $(this)
                    .find('.dropdown')
                    .toggleClass('rotate');
            });

            // Hide all sub-menus when the page loads
            $('.sub-menu').hide();


            // Add the 'active' class to the sidebar by default
            $('.side-bar').addClass('active');
            // Set close-btn visibility initially
            $('.close-btn').css("visibility", "visible");

            // jQuery for expand and collapse the sidebar
            $('.menu-btn').click(function() {
                // Add or remove the 'active' class to toggle the sidebar visibility
                $('.side-bar').toggleClass('active');
                // Toggle the visibility of close-btn
                $('.close-btn').css("visibility", function(index, value) {
                    return value === 'hidden' ?
                        'visible' :
                        'hidden';
                });
            });
            $('.sub-menu').click(function() {
                $('.menu-btn').css("visibility", function(index, value) {
                    return value === 'hidden' ?
                        'visible' :
                        'hidden';
                });
            });

            // Delay the appearance of the close-btn for 0.02 seconds
            setTimeout(function() {
                $('.close-btn').css("visibility", "visible");
            }, 400);

            // jQuery for closing the sidebar
            $('.close-btn').click(function() {
                // Add or remove the 'active' class to toggle the sidebar visibility
                $('.side-bar').toggleClass('active');
                // Toggle the visibility of close-btn
                $('.close-btn').css("visibility", function(index, value) {
                    return value === 'hidden' ?
                        'visible' :
                        'hidden';
                });

                // Ensure that menu-btn is visible
                $('.menu-btn').css("visibility", "visible");
            });

        });
    </script>

    <!--END NAVBAR -->

    <!--START MAIN-CONTENT -->
    <div style="margin-top: 25px;"></div>
    <div id="notification" class="notification">
        <span id="notification-text"></span>
    </div>
    <div class="container" style="padding-bottom:50px">
<!DOCTYPE html>
<html>

<head>
  <meta charset='utf-8'>
  <title><?php if (isset($title)) {
            echo $title;
          } ?></title>
  <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.js"></script>
  <link href="<?php echo base_url() ?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
  <link href="<?php echo base_url() ?>assets/css/login.css" rel="stylesheet" media="screen">


  <script type="text/javascript">
    var root = '<?php echo base_url(); ?>';
    var modul = '<?php echo (isset($modul) ? $modul : ''); ?>';
  </script>
</head>

<body>
  <div class="container">
    <div class="container-tampilan">
      <div class="form-login-2">
      <?php
      echo '<img src="assets\img\Lambang.png" alt="LOGO" width="400px" height="405px" align="center"/>';
      ?>
      </div>
      <div class="form-login-1">
        <h2>SIMHIBANSOS</h2>
        Sistem Informasi Manajemen Hibah dan Bantuan Sosial
        <label for="username">Username</label>
        <input type="text" class="span2" required="required" name="username" id="username" value="" /><br />
        <label>Password</label>
        <input type="password" class="span2" required="required" name="password" id="password" value="" /><br />
        <label>Tahun Anggaran</label>
        <?php
        $opt = 'id="tahun" class="span2"';
        echo form_dropdown('tahun', $option_tahun, $tahun_kini, $opt);
        ?>
        <button type="submit" id="login" class="btn btn-success">Login</button>
      </div>
    </div>

    <?php echo form_open('auth/process_login', array('class' => 'form-login_box')); ?>

    <?php echo form_close(); ?>

    <!-- Notification -->
    <div id="msg" align="center">

      <?php $message = $this->session->flashdata('message'); ?>
      <?php echo $message == '' ? '' : $message; ?>
    </div>

  </div>


  <script>
    $(document).ready(function() {
      $('#username').focus();
      var TAHUN = $("#tahun").val();
      $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>auth/get_thn_session" + "/" + TAHUN,
        data: TAHUN,
        success: function(msg) {
          $('#sts').html(msg);
        }
      });

      var msg = "";
      var elements = document.getElementsByTagName("INPUT");

      for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function(e) {
          if (!e.target.validity.valid) {
            switch (e.target.id) {
              case 'password':
                e.target.setCustomValidity("Password harus diisi");
                break;
              case 'username':
                e.target.setCustomValidity("Username harus diisi");
                break;
              default:
                e.target.setCustomValidity("");
                break;

            }
          }
        };
        elements[i].oninput = function(e) {
          e.target.setCustomValidity(msg);
        };
      }

    });

    $("#tahun").val(<?php echo $tahun_kini ?>);
    $("#tahun").change(function() { //keyboard
      var TAHUN = $("#tahun").val();
      var tahun = {
        tahun: $("#tahun").val()
      };
      $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>auth/get_thn_session" + "/" + TAHUN,
        data: TAHUN,
        success: function(msg) {
          $('#sts').html(msg);
        }
      });
    });
    $("#tahun").click(function() { //mouse
      var TAHUN = $("#tahun").val();
      var tahun = {
        tahun: $("#tahun").val()
      };
      $.ajax({
        type: "POST",
        url: "<?php echo base_url() ?>auth/get_thn_session" + "/" + TAHUN,
        data: TAHUN,
        success: function(msg) {
          $('#sts').html(msg);
        }
      });

    });
  </script>

  <script>
    <?php echo $message == "" ? "" : "
	$('#msg').hide().delay('500').fadeIn().show('slow').delay('5000').fadeOut().hide('slow');";
    ?>
  </script>

</body>

</html>
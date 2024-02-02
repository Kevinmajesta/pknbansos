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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-MCEQpz5EjX8QpFAa8fCX4VsnYtfXb8qk7eEoGpyR/fm3YjMWQw6VekSmh2D4ZhO8xJ0f6qb3drx5AORjRItYjA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


  <script type="text/javascript">
    var root = '<?php echo base_url(); ?>';
    var modul = '<?php echo (isset($modul) ? $modul : ''); ?>';
  </script>
</head>

<body>
  <div class="container">
    <div class="container-tampilan">
      <div class="form-login-kiri">
        <?php echo form_open('auth/process_login', array('class' => 'form-login_box')); ?>
        <?php
        echo '<img src="assets\img\Lambang.png" alt="LOGO" width="400px" height="405px" align="center"/>';
        ?>
        <h4 class="poppins-regular" style="color: #ffffff; text-align: center;">PEMERINTAH KABUPATEN MERAUKE</h4>
      </div>
      <div class="form-login-kanan">
        <h2 class="poppins-bold" style="font-size: 35px; margin-bottom: -40px;">SIMHIBANSOS</h2>
        <t class="poppins-medium" style="color: #71747a; font-size: 15px; margin-bottom: -80px;" >Sistem Informasi Manajemen</t><br/>
        <t class="poppins-medium" style="color: #71747a; font-size: 15px; margin-top: -50px;" > Hibah dan Bantuan Sosial</t>
        <div class="form-login-kanan-box">
          <label for="username" class="poppins-regular" style=" font-size: 13px;">Username</label>
          <input type="text" class="span2" required="required" name="username" id="username" value="" style="width: 201px; font-family: poppins; color: #ababab; font-size: 13px;"/>
          <label class="poppins-regular" style="font-size: 13px;">Password</label>
          <input type="password" class="span2" required="required" name="password" id="password" style="width: 201px; font-family: poppins; color: #ababab; font-size: 13px;"/><br />
          <label class="poppins-regular" style=" font-size: 13px;">Tahun Anggaran</label>
          <?php
          $opt = 'id="tahun" class="span2" style=" font-size: 13px; font-family: poppins"';
          echo form_dropdown('tahun', $option_tahun, $tahun_kini, $opt);
          ?><br />
          <button type="submit" id="login" class="btn btn-primary" style="width: 214px; margin-top: 10px; font-family: poppins;">Login</button>
        </div>
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
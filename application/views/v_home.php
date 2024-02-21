<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8'>
    <title><?php if (isset($title)) {
                echo $title;
            } ?></title>
    <script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.js"></script>
    <link href="<?php echo base_url() ?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
    <link href="<?php echo base_url() ?>assets/css/login-box.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- </script> -->
</head>
<script>
    function updateDate() {
        var currentDate = new Date();
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        var formattedDate = currentDate.toLocaleDateString('id-ID', options);

        document.getElementById('tanggal').innerHTML = formattedDate;
    }

    // Panggil fungsi updateDate setiap detik
    setInterval(updateDate, 100);
</script>

<body>
    <div class="container">
        <h1 class="awal" style="text-align: left;">Selamat Datang</h1>
        <p id="tanggal"></p>
        <p>Aplikasi Simbansos atau Sistem Informasi Hibah dan bantuan Sosial adalah merupakan aplikasi yang mengelola atau mengatur mengenai hibah ataupun bantuan sosial baik ke lembaga atau pemerintah daerah atau juga individu/perorangan.</p>
        <h1 style="text-align: left;">Daftar Lokasi</h1>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Lokasi</th>
                </tr>
            </thead>
            <?php
            $lokasi = array(
                'Merauke',
                'Semangga',
                'Tanah Miring',
                'Jagebob',
                'Mutin',
                'Ulilin',
                'Eligobel',
                'Kurik',
                'Okaba',
                'Kimaam',
            );
            ?>

            <tbody>
                <?php for ($i = 0; $i < count($lokasi); $i++) : ?>
                    <tr>
                        <th scope="row"><?php echo $i + 1; ?></th>
                        <td><?php echo $lokasi[$i]; ?></td>
                    </tr>
                <?php endfor; ?>
            </tbody>

        </table>
    </div>
</body>

<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #ffffff;
    }

    .awal {
        margin-top: 50px;
        /* Sesuaikan nilai margin sesuai keinginan Anda */
    }

    .container {
        width: 80%;
        margin: 0 auto !important;
    }


    .column {
        width: 30%;
        margin: 1%;
        float: left;
        text-align: center;
    }

    .circle-container {
        width: 50%;
        height: 0;
        padding-bottom: 50%;
        /* border-radius: 50%; */
        overflow: hidden;
        margin: 0 auto;
    }

    /* CSS table */
    .table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
    }

    .table th,
    .table td {
        padding: 8px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .table th {
        background-color: #f2f2f2;
        color: #333;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .table tbody tr:hover {
        background-color: #ddd;
    }
</style>

</html>
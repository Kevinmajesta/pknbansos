<legend>Laporan</legend>

<ul id="navigasi" class="filetree" style="color: black;"><?php echo $ul; ?></ul>

<div id="modalFilter" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<a class="close" data-dismiss="modal"><i class="icon-arrow-up"></i></a>
		<h3 style="color: black;">Filter</h3>
	</div>
	<div class="modal-body" id="dialogFilter"></div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Batal</button>
		<button class="btn btn-primary" id="pilih">Oke</button>
	</div>
</div>

<br />
<script type="text/javascript">
	$(document).ready(function() {

		$.datepicker.setDefaults($.datepicker.regional['id']);

		$("#navigasi").treeview({
			collapsed: true,
			unique: true,
			persist: "location"
		});

		pilih_filter();
	});

	function filter_option(i, j) {
		var data = {
			'i': i,
			'j': j
		};
		$.post('<?php echo base_url(); ?>laporan/filter_option/', data, function(resp) {
			$('#dialogFilter').empty();
			$('#dialogFilter').append(resp);
			set_filter_option(i, j);
		}, 'json');
	}

	function param(i, j) {
		var id_bidang = $('#Id_Bidang').val(),
			id_skpd = $('#Id_SKPD').val(),
			jenis_bantuan = $('#Jenis_bantuan').val(),
			kategori = $('#Kategori').val(),
			id_kegiatan = $('#Id_Kegiatan').val(),
			tgl_awal = $('#TanggalAwal').val(),
			tgl_akhir = $('#TanggalAkhir').val();
		var data = {
			'id_bidang': id_bidang,
			'id_skpd': id_skpd,
			'jenis_bantuan': jenis_bantuan,
			'kategori': kategori,
			'id_kegiatan': id_kegiatan,
			'tgl_awal': tgl_awal,
			'tgl_akhir': tgl_akhir,
			'i': i,
			'j': j
		};

		return data;
	}

	function load_bidang(i, j) {
		var data = param(i, j);
		$('#Id_Bidang').after('<img id="load_bidang" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_Bidang').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_bidang', data, function(resp) {
			$('#load_bidang').remove();
			$('#Id_Bidang').prop('disabled', false);
			$('#Id_Bidang').empty();
			$('#Id_Bidang').append(resp);
		}, 'json');
	}

	function load_skpd(i, j) {
		var data = param(i, j);
		$('#Id_SKPD').after('<img id="load_skpd" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_SKPD').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_skpd', data, function(resp) {
			$('#load_skpd').remove();
			$('#Id_SKPD').prop('disabled', false);
			$('#Id_SKPD').empty();
			$('#Id_SKPD').append(resp);
		}, 'json')
	}

	function load_kegiatan(i, j) {
		var data = param(i, j);
		$('#Id_Kegiatan').after('<img id="load_kegiatan" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_Kegiatan').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_kegiatan', data, function(resp) {
			$('#load_kegiatan').remove();
			$('#Id_Kegiatan').prop('disabled', false);
			$('#Id_Kegiatan').empty();
			$('#Id_Kegiatan').append(resp);
		}, 'json');
	}

	function load_jenis(i, j) {
		var data = param(i, j);
		$('#Jenis_bantuan').after('<img id="load_jenis" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Jenis_bantuan').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_jenis', data, function(resp) {
			$('#load_jenis').remove();
			$('#Jenis_bantuan').prop('disabled', false);
			$('#Jenis_bantuan').empty();
			$('#Jenis_bantuan').append(resp);
		}, 'json');
	}

	function load_kategori(i, j) {
		var data = param(i, j);
		$('#Kategori').after('<img id="load_kategori" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Kategori').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_kategori', data, function(resp) {
			$('#load_kategori').remove();
			$('#Kategori').prop('disabled', false);
			$('#Kategori').empty();
			$('#Kategori').append(resp);
		}, 'json');
	}

	function load_rekening(i, j) {
		var data = param(i, j);
		$('#Id_Rekening').after('<img id="load_rekening" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_Rekening').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_rekening', data, function(resp) {
			$('#load_rekening').remove();
			$('#Id_Rekening').prop('disabled', false);
			$('#Id_Rekening').empty();
			$('#Id_Rekening').append(resp);
		}, 'json');
	}

	function load_pejabat(i, j) {
		var data = param(i, j);
		$('#Id_PA').after('<img id="load_pa" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_PA').prop('disabled', 'disabled');
		$('#Id_PPK').after('<img id="load_ppk" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_PPK').prop('disabled', 'disabled');
		$('#Id_BP').after('<img id="load_bp" src="<?php echo base_url(); ?>assets/img/select2-spinner.gif">');
		$('#Id_BP').prop('disabled', 'disabled');
		$.post('<?php echo base_url(); ?>laporan/get_pejabat', data, function(resp) {
			$('#load_pa').remove();
			$('#Id_PA').prop('disabled', false);
			$('#Id_PA').empty();
			$('#Id_PA').append(resp);
			$('#load_ppk').remove();
			$('#Id_PPK').prop('disabled', false);
			$('#Id_PPK').empty();
			$('#Id_PPK').append(resp);
			$('#load_bp').remove();
			$('#Id_BP').prop('disabled', false);
			$('#Id_BP').empty();
			$('#Id_BP').append(resp);
		}, 'json');
	}

	function klik_bidang(i, j) {
		$('#Id_Bidang').change(function() {
			load_skpd(i, j);
			load_kegiatan(i, j);
			load_rekening(i, j);
			load_pejabat(i, j);
		});
	}

	function klik_tgl_awal(i, j) {
		$('#TanggalAwal').change(function() {
			load_bidang(i, j);
			load_skpd(i, j);
			load_kegiatan(i, j);
			load_rekening(i, j);
			load_pejabat(i, j);
		});

	}

	function klik_tgl_akhir(i, j) {
		$('#TanggalAkhir').change(function() {
			load_bidang(i, j);
			load_skpd(i, j);
			load_kegiatan(i, j);
			load_rekening(i, j);
			load_pejabat(i, j);
		});
	}

	function klik_skpd(i, j) {
		$('#Id_SKPD').change(function() {
			load_kegiatan(i, j);
			load_rekening(i, j);
			load_pejabat(i, j);
		});
	}

	function klik_kegiatan(i, j) {
		$('#Id_Kegiatan').change(function() {
			load_rekening(i, j);
		});
	}

	function klik_jenis(i, j) {
		$('#Jenis_bantuan').change(function() {
			load_kategori(i, j);
		});
	}


	function set_filter_option(i, j) {
		$('#modalFilter').modal({
			keyboard: true,
		});
		$('.datepicker').datepicker();
		param(i, j);
		klik_bidang(i, j);
		klik_jenis(i, j);
		klik_tgl_awal(i, j);
		klik_tgl_akhir(i, j);
		klik_skpd(i, j);
		klik_kegiatan(i, j);
	}

	function pilih_filter() {
		$('#pilih').click(function() {
			var tgl_lapor = $('#Tanggal').val(),
				tgl_awal = $('#TanggalAwal').val(),
				tgl_akhir = $('#TanggalAkhir').val(),
				no_hlmn = $('#NoHalaman').val(),
				realisasi = $('#Realisasi').val(),
				bulan = $('#Bulan').val(),
				header = $('#Header').val(),
				semester = $('#Semester').val(),
				tipe = $('#Tipe').val(),
				jenis_sp2d = $('#JenisSP2D').val(),
				id_sd = $('#Id_Sumber_Dana').val(),
				histori = $('#histori').val(),
				id_bidang = $('#Id_Bidang').val(),
				id_skpd = $('#Id_SKPD').val(),
				jenis_bantuan = $('#Jenis_bantuan').val(),
				kategori = $('#Kategori').val(),
				kode_rek = $('#kode_rek').val(),
				id_pa = $('#Id_PA').val(),
				id_ppk = $('#Id_PPK').val(),
				id_bp = $('#Id_BP').val(),
				id_kegiatan = $('#Id_Kegiatan').val(),
				id_rekening = $('#Id_Rekening').val(),
				keperluan = $('#Keperluan').val(),
				file = $('#file').val(),
				format = $('input[name="format"]:checked').val();
			var param = {
				'tgl_lapor': tgl_lapor,
				'tgl_awal': tgl_awal,
				'tgl_akhir': tgl_akhir,
				'no_hlmn': no_hlmn,
				'realisasi': realisasi,
				'bulan': bulan,
				'header': header,
				'semester': semester,
				'tipe': tipe,
				'jenis_sp2d': jenis_sp2d,
				'id_sd': id_sd,
				'histori': histori,
				'id_bidang': id_bidang,
				'id_skpd': id_skpd,
				'jenis_bantuan': jenis_bantuan,
				'kategori': kategori,
				'kode_rek': kode_rek,
				'id_pa': id_pa,
				'id_ppk': id_ppk,
				'id_bp': id_bp,
				'id_kegiatan': id_kegiatan,
				'id_rekening': id_rekening,
				'keperluan': keperluan,
				'file': file,
				'format': format
			};

			$.ajax({
				type: "post",
				dataType: "json",
				data: param,
				url: root + 'laporan/preview',
				success: function(res, stat) {
					if (stat == 'success') {
						if (res.isSuccessful) {
							url = root + 'laporan/view/' + res.id + '/' + res.nama;
							window.open(url, '_blank');
						} else {
							// TODO: show information error
							$.pnotify({
								text: res.message,
								type: 'error'
							});
						}
					}
				},
				error: function(res, stat, error) {
					// TODO: show information error
				}
			})
			return false;
		});
	}
</script>
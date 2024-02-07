
<head>
	<meta charset='utf-8'>
	<script type="text/javascript" src="<?php echo base_url() ?>assets/script/jquery.js"></script>
	<link href="<?php echo base_url() ?>assets/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="<?php echo base_url() ?>assets/css/login-box.css" rel="stylesheet" media="screen">
	<!-- </script> -->
</head>


<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>

<h4>
	<legend id="bc" data-bind="text: title"></legend>
</h4>

<form id="frm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>auth/user_proses">
	<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a href="#home">Account</a></li>
		<li><a href="#profile">Password</a></li>
	</ul>

	<div class="tab-content" style="height:430px">
		<div class="tab-pane active" id="home">
			<div class="control-group" data-bind="validationElement: username">
				<label class="control-label" for="inputUsername">
					Username
				</label>
				<div class="controls">
					<input type="text" id="username" placeholder="Username" data-bind="value: username">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputName">
					Nama Operator
				</label>
				<div class="controls">
					<input type="text" id="name" placeholder="Nama Operator" data-bind="value: name">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputName">
					Icon
				</label>
				<div class="controls">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail" style="width: 63px; height: 68px;">
							<?php
							if (isset($data['ICON'])) {

							?>
								<img src="<?php echo base_url() . 'assets/img/user/' . $data['ICON']; ?>" />
							<?php

							} else {
							?>
								<img src="http://www.placehold.it/63x68/EFEFEF/AAAAAA" />
							<?php
							}
							?>
						</div>
						<?php
						if (isset($data['ICON'])) {
						?>
							<span data-bind="click: hapus_icon"><i class="icon-remove" title="hapus icon"></i></span>
						<?php
						}
						?>
						<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px;"></div>
						<span class="btn btn-file">
							<span class="fileupload-new">
								Select image
							</span>
							<span class="fileupload-exists">
								Change
							</span>
							<input type="file" id="image" name="image" data-bind="value: image">
						</span>
						<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>Ukuran Max 100kb
				</div>
			</div>
			<div class="control-group" data-bind="validationElement: email">
				<label class="control-label" for="inputEmail">
					Email
				</label>
				<div class="controls">
					<input type="email" id="email" placeholder="Email" data-bind="value: email">
				</div>
			</div>
			<div class="control-group" data-bind="validationElement: guser">
				<label class="control-label" for="inputGUser">
					Group User
				</label>
				<div class="controls">
					<select id="guser" <?php echo isset($data['ID']) ? 'disabled="disabled"' : '' ?>>
						<?php
						if (isset($data['ID'])) {
							if ($groups) {
								echo '<option value="0" selected="selected">==Pilih Group==</option>';
								foreach ($groups as $groups) {
									$selected = ($data['GROUP_ID'] == $groups['ID']) ? 'selected="selected"' : '';
									echo '<option value="' . $groups['ID'] . '" ' . $selected . '>' . $groups['NAME'] . '</option>';
								}
							}
						} else {
							if ($groups) {
								echo '<option value="0">==Pilih Group==</option>';
								foreach ($groups as $groups) {
									//$selected = ($groups['ID'] == '2') ? 'selected="selected"' : '';
									echo '<option value="' . $groups['ID'] . '">' . $groups['NAME'] . '</option>';
								}
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="control-group" data-bind="validationElement: id_skpd">
				<label class="control-label" for="inputSKPD">
					SKPD
				</label>
				<div class="controls">
					<input type="text" class="span3" id="nm_skpd" readonly="1" data-bind="value: nm_skpd" />
					<span class="add-on" data-bind="visible: !isEdit(), click: pilih_skpd"><i class="icon-folder-open"></i></span>
				</div>
			</div>
			<div class="controls-row">
				<label class="control-label" for="ppkd">PPKD</label>
				<div class="controls">
					<input type="checkbox" class="checkbox" id="ppkd" data-bind="checked: ppkd" disabled="1" />
				</div>
			</div>
		</div>
		<div class="tab-pane" id="profile">
			<div data-bind="visible: mode() === 'edit'">
				<div class="control-group" data-bind="validationElement: old_passwd">
					<label class="control-label" for="inputPassword" data-bind="validationElement: old_passwd">
						Password Lama
					</label>
					<div class="controls" data-bind="validationElement: old_passwd">
						<input type="password" id="old_passwd" placeholder="Password lama" data-bind="value: old_passwd">
					</div>
				</div>
				<div class="control-group" data-bind="validationElement: passwd">
					<label class="control-label" for="inputPassword" data-bind="validationElement: passwd">
						Password baru
					</label>
					<div class="controls">
						<input type="password" id="passwde" placeholder="Password" data-bind="value: passwd">
					</div>
				</div>
				<div class="control-group" data-bind="validationElement: repasswd">
					<label class="control-label" for="inputPassword" data-bind="validationElement: repasswd">
						Ulangi Password baru
					</label>
					<div class="controls">
						<input type="password" id="repasswde" placeholder="Ulangi Password" data-bind="value: repasswd">
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="control-group">
		<div class="controls">
			<input type="submit" id="save" value="Simpan" class="btn btn-primary" data-bind="enable: canSave, click: save" />
			<!--<button type="submit" class="btn">Tambah</button>-->
		</div>
	</div>
</form>
<!--<pre data-bind="text: ko.toJSON($root, null, 2)"></pre>-->
<script>
	var last;
	var purge = new Array();

	$('#myTab a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	})

	/*function refresh (timeoutPeriod)
  {
     refresh = setTimeout(function(){
	 	<?php //$this->session->sess_destroy(); 
			?>
		window.location = "<?php echo base_url() . 'login'; ?>"
		
		},timeoutPeriod);
  }*/
	function refresh(timeoutPeriod) {
		refresh = setTimeout(function() {
			window.location.reload(true);
		}, timeoutPeriod);
	}
	//ko
	ko.validation.init({
		insertMessages: true,
		decorateElement: true,
		errorElementClass: 'error',
	});


	var ModelUser = function() {
		var self = this;
		self.modul = 'User';
		self.akses_level = ko.observable(3);
		self.id = ko.observable('<?php echo isset($data['ID']) ? $data['ID'] : 0 ?>');
		self.username = ko.observable('<?php echo isset($data['USERNAME']) ? $data['USERNAME'] : '' ?>')
			.extend({
				required: {
					params: true,
					message: 'Username tidak boleh kosong'
				}
			});
		self.email = ko.observable('<?php echo isset($data['EMAIL']) ? $data['EMAIL'] : '' ?>')
			.extend({
				required: {
					params: true,
					message: 'Email tidak boleh kosong'
				},
				email: {
					params: true,
					message: 'Alamat Email tidak valid'
				}
			});
		self.name = ko.observable('<?php echo isset($data['NAME']) ? $data['NAME'] : '' ?>');

		self.image = ko.observable('');
		self.url = '<?php echo base_url() . "auth/upload"; ?>';

		self.displaypassword = ko.observable(false);
		<?php
		if (isset($data['ID'])) {
		?>
			self.old_passwd = ko.observable('')
				.extend({
					required: {
						params: true,
						message: 'Password Lama tidak boleh kosong',
						onlyIf: self.displaypassword
					},
				});
			self.passwd = ko.observable('')
				.extend({
					required: {
						params: true,
						message: 'Password baru tidak boleh kosong',
						onlyIf: self.displaypassword
					},
					minLength: {
						params: 8,
						message: 'Password minimal 8 karakter'
					},
				});
			self.repasswd = ko.observable('')
				.extend({
					required: {
						params: true,
						message: 'Ulangi Password baru tidak boleh kosong',
						onlyIf: self.displaypassword
					},
					equal: {
						params: self.passwd,
						message: 'Password harus sama'
					},
				});
		<?php
		}
		?>
		self.id_skpd = ko.observable(<?php echo isset($data['SKPD_ID']) ? $data['SKPD_ID'] : '' ?>);
		self.status = ko.observable('<?php echo isset($data['STATUS']) ? $data['STATUS'] : '1' ?>');
		self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD']) ? $data['NAMA_SKPD'] : '' ?>');
		self.guser = ko.observable('<?php echo isset($data['GROUP_ID']) ? $data['GROUP_ID'] : '2' ?>');
		self.ppkd = ko.observable(<?php echo isset($data['PPKD']) && $data['PPKD'] == 1 ? 'true' : 'false' ?>);

		self.mode = ko.computed(function() {
			return self.id() > 0 ? 'edit' : 'new';
		});

		self.title = ko.computed(function() {
			return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
		});

		self.isEdit = ko.computed(function() {
			return self.mode() === 'edit';
		});

		self.canPrint = ko.computed(function() {
			return self.akses_level() >= 2;
		});

		self.canSave = ko.computed(function() {
			return self.akses_level() >= 3;
		});

		self.errors = ko.validation.group(self);
	}



	var App = new ModelUser();

	/*App.isValid = function(){
    var Status = true;

    //if (!App.id_skpd()) Status = false;

		return Status;
	}*/

	App.prev = function() {
		show_prev(modul, App.id());
	}

	App.next = function() {
		show_next(modul, App.id());
	}

	App.print = function() {
		preview({
			"tipe": "form",
			"id": App.id()
		});
	}

	App.back = function() {
		location.href = root + modul;
	}

	App.save = function() {
		var $frm = $('#frm'),
			data = JSON.parse(ko.toJSON(App));

		if (!App.isValid()) {
			App.errors.showAllMessages();;
			return;
		}

		//var fileInput = $("input[name='image']");
		//var file = fileInput.get(0).files[0];

		var file = document.getElementById('image').files[0];

		var formData = new FormData($('form#frm')[0]);
		//formData.append("image", file);

		//tanpa image
		if (App.image() == "") {
			$.ajax({
				url: $frm.attr('action'),
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(res, xhr) {
					if (res.id) App.id(res.id);

					$.pnotify({
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
				}
			});
		}
		//dengan image
		else {
			$.ajax({
				url: App.url,
				type: 'post',
				dataType: 'json',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(res, xhr) {
					$.pnotify({
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
					if (res.isSuccess == true) {
						$.ajax({
							url: $frm.attr('action'),
							type: 'post',
							dataType: 'json',
							data: $.extend(data, {
								icon: res.filename
							}),
							success: function(res, xhr) {
								if (res.id) App.id(res.id);

								$.pnotify({
									title: res.isSuccess ? 'Sukses' : 'Gagal',
									text: res.message,
									type: res.isSuccess ? 'info' : 'error'
								});
							}
						});
					}
				}
			});
		}
		setTimeout(function() {
			window.location = root + modul
		}, 2000);
	}

	App.pilih_skpd = function() {
		var option = {
			multi: 0
		};
		Dialog.pilihSKPD(option, function(obj, select) {
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.id_skpd(rs.id);
			//App.kd_skpd(rs.kode);
			App.nm_skpd(rs.nama);
		});
	}

	App.hapus_icon = function() {
		var agree = confirm("Apakah Anda yakin akan menghapus icon?");
		if (agree) {
			$.ajax({
				url: '<?php echo base_url() ?>auth/icon',
				type: 'post',
				dataType: 'json',
				data: {
					id: App.id()
				},
				success: function(res, xhr) {
					if (res.id) App.id(res.id);

					$.pnotify({
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
					refresh('2400');
				}
			});
		}
	}

	App.init_select = function(element, callback) {
		var data = {
			'text': $(element).attr('data-init')
		};
		callback(data);
	}

	App.query_pejabat_skpd = function(option) {
		var id_skpd = App.id_skpd();
		$.ajax({
			url: "<?php echo base_url() ?>pilih/pejabat_skpd",
			type: 'POST',
			dataType: 'json',
			data: {
				'q': option.term,
				'skpd': id_skpd,
			},
			success: function(data) {
				option.callback({
					results: data.results
				});
			}
		});
	};

	ko.applyBindings(App);
</script>
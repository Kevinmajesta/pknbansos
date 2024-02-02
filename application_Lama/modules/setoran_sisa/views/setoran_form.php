<fieldset class="header-aktivitas" >
	<legend id="bc" class="judul-aktivitas" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>setoran_sisa/proses">
	<div class="controls-row">
		<div class="control-group pull-left" data-bind="validationElement: no" >
			<label class="control-label" for="no">Nomor</label>
			<input type="text" id="no" class="span3" data-bind="value: no" required/>
		</div>
		<div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl" >
			<label class="control-label" for="tgl">Tanggal</label>
			<input type="text" name="tgl" id="tgl" class="span2 datepicker" data-bind="value: tgl, attr: {readonly: canEdit() ? null : 'readonly'}, css: {datepicker: canEdit()}" placeholder="dd/mm/yyyy" required/>
		</div>
	</div>

	<div class="controls-row">
		<div class="control-group pull-left" data-bind="validationElement: id_skpd" >
			<label class="control-label" for="kode_skpd">SKPD</label>
			<input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" />
			<div class="controls span6 input-append">
				<input type="text" class="span6" id="nama_skpd" readonly="1" data-bind="value: nm_skpd, executeOnEnter: pilih_skpd" />
				<span class="add-on" data-bind="visible: !isEdit() && !isSKPD && canSave(), click: pilih_skpd"><i class="icon-folder-open"></i></span>
			</div>
		</div>
	</div>

	<div class="controls-row">
		<div class="control-group pull-left" data-bind="validationElement: deskripsi">
			<label class="control-label" for="deskripsi">Keterangan</label>
			<textarea data-required="true" rows="2" class="span8" data-bind="value: deskripsi, attr: {readonly: canEdit() ? null : 'readonly'}" required ></textarea>
		</div>
		<!--<div class="control-group pull-right">
			<div class="controls-row control-group" data-bind="validationElement: sisa_sp2dup">
				<label class="control-label" for="sisa_sp2dup">Sisa SP2D UP</label>
				<input type="text" id="sisa_sp2dup" class="span3 currency" data-bind="numeralvalue: sisa_sp2dup" />
			</div>
		</div>-->
		<div class="controls pull-right">
			<label class="control-label" for="sisa_sp2d_all">Sisa SP2D Keseluruhan</label>
			<input type="text" id="sisa_sp2d_all" class="span2 currency" readonly="1" data-bind="numeralvalue:sisa_sp2d_all" />
		</div>
		<div class="controls pull-right" style="margin-right: 25px;">
			<label class="control-label" for="sisa_sp2d">Sisa SP2D S/d Sekarang</label>
			<input type="text" id="sisa_sp2d" class="span2 currency" readonly="1" data-bind="numeralvalue:sisa_sp2d" />
		</div>
	</div>

	<div class="controls-row">
		<div class="control-group pull-left">
			<div class="control-group controls-row" data-bind="validationElement: pekas">
				<label class="control-label" for="kdrek_pekas">Akun Bendahara</label>
				<input type="text" class="span2" data-bind="value: kd_pekas, executeOnEnter: pilih_sd_skpd" readonly="1" />
				<div class="controls input-append span6">
					<input type="text" class="span6" data-bind="value: nm_pekas, executeOnEnter: pilih_sd_skpd" readonly="1" />
					<span class="add-on" data-bind="visible: canEdit(), click: pilih_sd_skpd" ><i class="icon-folder-open"></i></span>
				</div>
			</div>
		</div>
		<div class="control-group pull-right">
			<div class="control-group pull-right" data-bind="validationElement: total" >
				<label class="control-label" for="total">Total Pengembalian UP</label>
				<input type="text" id="total" class="span3 currency" data-bind="numeralvalue: total" />
			</div>
		</div>
	</div>

	<div class="bottom-bar">
		<input type="button" id="prev" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
		<input type="button" id="next" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
		<div class="btn-group dropup">
			<button type="button" class="btn btn-primary" data-bind="enable: canSave() && !processing(), click: function(data, event){save(false, data, event) }" />Simpan</button>
			<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave()">
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" data-bind="enable: canSave(), click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
			</ul>
		</div>
		<button type="button" class="btn btn-primary" id="print" data-bind="enable: canPrint(), click: print" >Cetak</button>
		<input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
	</div>
</form>

<script>
var lastrek, lastsd, newid, batas = batas_all = 0,  purge_rek = [], purge_sd = [];

$(document).ready(function() {
  inisialisasi();

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });
  
});
	function GetSisaSKPD(){
		var data = {
			id: App.id(),
			id_skpd: App.id_skpd(),
			tanggal: App.tgl(),
		};

		$.ajax({
			type: "post",
			dataType: "json",
			data: data,
			url: root+modul+'/sisa_skpd',
			success: function(res) {
				App.batas(res.sisa);
				App.batas_all(res.sisa_all);
			},
		});
	}
  
	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: 'error',
	});

	var ModelSSU = function (){
		var self = this;
		self.modul_display = '<?php echo $modul_display ?>';
		self.processing = ko.observable(false);
		self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
		self.akses_level = ko.observable('<?php echo isset($akses) ? $akses : 0 ?>');
		self.id = ko.observable('<?php echo isset($data['ID_AKTIVITAS']) ? $data['ID_AKTIVITAS'] : 0 ?>');
		self.no = ko.observable(<?php echo isset($data['NOMOR']) ? json_encode($data['NOMOR']) : "''" ?>)
		  .extend({
			required: {params: true, message: 'Nomor tidak boleh kosong'},
			maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
		  });
		self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
		  .extend({
			required: {params: true, message: 'Tanggal tidak boleh kosong'}
		  });
		self.total = ko.observable(<?php echo isset($data['NOMINAL']) ? $data['NOMINAL'] : '0' ?>)
		  .extend({
			required: {params: true, message: 'Total tidak boleh kosong'},
			notEqual: {params: 0, message: 'Total tidak boleh bernilai 0'},
		  });
		self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : '' ?>)
		  .extend({
			required: {params: true, message: 'SKPD belum dipilih'}
		  });
		self.kd_skpd = ko.observable(<?php echo isset($data['KODE_SKPD_LKP']) ? json_encode($data['KODE_SKPD_LKP']) : "''" ?>);
		self.nm_skpd = ko.observable(<?php echo isset($data['NAMA_SKPD']) ? json_encode($data['NAMA_SKPD']) : "''" ?>);
		self.deskripsi = ko.observable(<?php echo isset($data['DESKRIPSI']) ? json_encode($data['DESKRIPSI']): "''" ?>)
		  .extend({
			required: {params: true, message: 'Deskripsi tidak boleh kosong'}
		  });
		self.pekas = ko.observable(<?php echo isset($data['ID_REKENING_PEKAS']) ? $data['ID_REKENING_PEKAS'] : '' ?>)
		  .extend({
			required: {params: true, message: 'Akun bendahara belum diisi'}
		  });
		self.kd_pekas = ko.observable(<?php echo isset($data['KODE_REKENING_PEKAS']) ? json_encode($data['KODE_REKENING_PEKAS']) : "''" ?>);
		self.nm_pekas = ko.observable(<?php echo isset($data['NAMA_REKENING_PEKAS']) ? json_encode($data['NAMA_REKENING_PEKAS']) : "''" ?>);
		self.batas = ko.observable(0);
		self.batas_all = ko.observable(0);
		
		self.tgl.subscribe(function(){
			GetSisaSKPD();
		});

		self.id_skpd.subscribe(function(){
			GetSisaSKPD();
		});

		self.total.subscribe(function(){
			GetSisaSKPD();
		});

		self.sisa_sp2d = ko.computed(function(){
			return self.batas() - self.total();
		});

		self.sisa_sp2d_all = ko.computed(function(){
			return self.batas_all() - self.total();
		});

		self.mode = ko.computed(function(){
			return self.id() > 0 ? 'edit' : 'new';
		});

		self.title = ko.computed(function(){
		  return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul_display;
		});

		self.isEdit = ko.computed(function(){
		  return self.mode() === 'edit';
		});

		self.canEdit = ko.computed(function(){
			return self.akses_level() >= 3;
		});

		self.canPrint = ko.computed(function(){
		  return self.akses_level() >= 2;
		});

		self.canSave = ko.computed(function(){
		  return self.akses_level() >= 3;
		});

		self.errors = ko.validation.group(self);
	}

	var App = new ModelSSU();

	App.prev = function(){
		show_prev(modul, App.id());
	}

	App.next = function(){
		show_next(modul, App.id());
	}

	App.print = function(){
		preview({"tipe":"form", "id": App.id()});
	}

	App.back = function(){
		location.href = root+modul;
	}

	App.formValidation = function(){
		var errmsg = [];
		
		if (App.sisa_sp2d() < 0){
			errmsg.push('Sisa SP2D Sekarang tidak boleh minus');
		}
		
		if (App.sisa_sp2d_all() < 0){
			errmsg.push('Sisa SP2D Keseluruhan tidak boleh minus');
		}

		if (!App.isValid()){
			errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
			App.errors.showAllMessages();
		}
		if (errmsg.length > 0) {
			message = errmsg.join('</br>');
			show_warning(message);
			return false;
		}
		return true;
	}

	App.save = function(createNew){
		if (!App.formValidation()){ return }

		var $frm = $('#frm'),
			data = JSON.parse(ko.toJSON(App));

		App.processing(true);
		$.ajax({
			url: $frm.attr('action'),
			type: 'post',
			dataType: 'json',
			data: data,
			success: function(res, xhr){
				if (res.isSuccess){
					if (res.id) App.id(res.id);
					if (res.nomor) App.no(res.nomor);
					App.init_grid();
				}

				if (res.isSuccess) show_info(res.message, 'Sukses');
				else show_error(res.message, 'Gagal');

				if (createNew) location.href = root+modul+'/form/';
			},
			complete: function(){
				App.processing(false);
			}
		});
	}

	App.init_grid = function(){
		GetSisaSKPD();
	}

	App.pilih_skpd = function(){
		if (!App.canSave() || App.isEdit()) { return; }
		var option = {multi:0};
		Dialog.pilihSKPD(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.id_skpd(rs.id);
			App.kd_skpd(rs.kode);
			App.nm_skpd(rs.nama);
		});
	}
	
	App.pilih_sd_skpd = function(){
		if (!App.canSave()) { return; }
		var option = {multi:0, id_skpd: App.id_skpd(), mode:'bk'};
		Dialog.pilihSumberdanaSKPD(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.pekas(rs.idrek);
			App.kd_pekas(rs.kdrek);
			App.nm_pekas(rs.nmrek);
			//App.pekas_tunai(rs.bank == 'TUNAI' ? true : false);
		});
	}
  
  ko.applyBindings(App);
  setTimeout(function(){
<?php
if ($id_skpd !== 0 && !isset($data['ID_AKTIVITAS'])){
?>
    App.id_skpd(<?php echo $id_skpd; ?>);
    App.kd_skpd('<?php echo $kode_skpd; ?>');
    App.nm_skpd('<?php echo $nama_skpd; ?>');
<?php
}
?>
	GetSisaSKPD();
    App.init_grid();
    
  }, 500)
</script>
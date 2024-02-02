<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>bast/<?php echo $link_proses;?>" enctype="multipart/form-data">
	<fieldset>
		<div class="controls-row">
			<div class="control-group pull-left" data-bind="validationElement: no" >
				<label class="control-label" for="no">Nomor</label>
				<input type="text" class="span3" id="no" data-bind="value: no" required />
			</div>
			<div class="control-group pull-left span2" data-bind="validationElement: tgl" >
				<label class="control-label" for="tgl">Tanggal</label>
				<input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required />
			</div>		
			<div class="control-group pull-left span2">
				<label class="control-label" >Keperluan</label>
				<label class="radio">
					<input type="radio" data-bind="disable: isEdit, checked: keperluan" value="UP" />UP/GU
				</label>
				<label class="radio">
					<input type="radio" data-bind="disable: isEdit, checked: keperluan" value="LS" />LS
				</label>
			</div>
			<div class="controls pull-right" data-bind="validationElement: sisa_sp2d" >
				<label class="control-label" for="sisa_sp2d">Sisa SP2D</label>
				<input type="text" id="sisa_sp2d" class="span3 currency" readonly="1" data-bind="numeralvalue: sisa_sp2d" />
			</div>
		</div>
	  
		<div class="controls-row">
			<div class="control-group pull-left" data-bind="validationElement: id_skpd" >
				<label class="control-label" for="kode_skpd">SKPD</label>
				<input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd" />
				<div class="controls span6 input-append">
					<input type="text" class="span6" id="nama_skpd" readonly="1" data-bind="value: nm_skpd" />
					<span class="add-on" data-bind="visible: !isEdit() , click: pilih_skpd" ><i class="icon-folder-open"></i></span>
				</div>
			</div>
			<div class="control-group pull-right">
				<label class="control-label" for="nom">Nominal Pengajuan</label>
				<input type="text" id="nom" readonly="1" class="span3 currency" data-bind="numeralvalue: nom" />
			</div>
		</div>
		<div class="controls-row">	
			<div class="control-group pull-left" >
				<label class="control-label" for="uraian">Uraian Belanja Bantuan Sosial</label>
				<input type="text" class="span8" id="uraian" readonly="1" data-bind="value: uraian" />
				<div class="controls input-append">
					
					<span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_uraian" ><i class="icon-folder-open"></i></span>
				</div>
			</div>
			
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Data Penerima Bantuan Sosial</legend>
		<div class="controls-row">
			<div class="control-group pull-left" data-bind="validationElement: nama">
				<label class="control-label" for="nama">Nama Penerima</label>
				<input type="text" class="span6" id="nama" data-bind="value: nama" required />
			</div>
			<div class="control-group pull-left span2" data-bind="validationElement: nik">
				<label class="control-label" for="nik">NIK</label>
				<input type="text" class="span3" id="nik" data-bind="value: nik" required />
			</div>
			<div class="control-group pull-right" data-bind="validationElement: tgl_lahir" >
				<label class="control-label" for="tgl_lahir">Tanggal Lahir</label>
				<input type="text" class="span3 datepicker" id="tgl_lahir" data-bind="value: tgl_lahir" />
			</div>
		</div>
		<div class="controls-row">
			<div class="control-group pull-left" data-bind="validationElement: pekerjaan" >
				<label class="control-label" for="pekerjaan">Jabatan/Pekerjaan</label>
				<input type="text" id="pekerjaan" class="span3" data-bind="value: pekerjaan" required />
			</div>
			<div class="control-group pull-left span2" data-bind="validationElement: mewakili" >
				<label class="control-label" for="mewakili">Mewakili</label>
				<input type="text" id="mewakili" class="span3" data-bind="value: mewakili, select2: { minimumResultsForSearch: -1, containerCss: {'margin-left':'0px'}, containerCssClass: 'span3',  placeholder: 'Mewakili',  data:{ results: data_mewakili}, }" />
			</div>			
			<div class="control-group pull-left" data-bind="validationElement: nama_rekening" style="margin-left:100px;">
				<label class="control-label" for="nama_rekening">Nama Rekening</label>
				<input type="text" id="nama_rekening" class="span3" data-bind="value: nama_rekening" />
			</div>
			<div class="control-group pull-right" data-bind="validationElement: nomor_rekening" >
				<label class="control-label" for="nomor_rekening">Nomor Rekening</label>
				<input type="text" id="nomor_rekening" class="span3" data-bind="value: nomor_rekening" />
			</div>
		</div>		
		<div class="controls-row">
			<div class="control-group pull-left" >
				<label class="control-label" for="alamat">Alamat</label>
				<input type="text" id="alamat" class="span6" data-bind="value: alamat" required/>
			</div>
			<div class="control-group pull-left span2" >
				<label class="control-label" for="nama_bank">Nama Bank</label>
				<input type="text" id="nama_bank" class="span3" data-bind="value: nama_bank" />
			</div>
			<div class="control-group pull-right" data-bind="validationElement: npwp" >
				<label class="control-label" for="npwp">NPWP</label>
				<input type="text" id="npwp" class="span3" data-bind="value: npwp" />
			</div>
		</div>
		<div class="controls-row">
			<div class="control-group pull-left">
				<div class="controls-row">
					<label class="control-label" for="ppt">Pejabat Penanda Tangan</label>
					<input type="text" id="ppt" class="span6" data-bind="attr : {'data-init': nm_ppt}, value: ppt, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Penanda Tangan', initSelection: init_select, query: query_pejabat_penanda_tangan }" />
				</div>
			</div>
			<div class="control-group pull-right">
				<div class="controls-row">
					<label class="control-label" for="pejabat_daerah">Pejabat Daerah</label>
					<input type="text" id="pejabat_daerah" class="span6" data-bind="attr : {'data-init': nm_pejabat_daerah}, value: pejabat_daerah, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Daerah', initSelection: init_select, query: query_pejabat_daerah }" required />
				</div>
			</div>
		</div>
		<div class="controls-row" style="margin-top:20px;">
			<div class="control-group pull-left">
				<label class="control-label" for="peruntukan">Peruntukan Belanja Hibah</label>
				<textarea rows="2" class="span9" id="peruntukan" data-bind="value: peruntukan" ></textarea>
			</div>			
		</div>
		
		<ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
			<li class="active"><a href="#dok">Dokumen Pendukung</a></li>
			<li><a href="#rinc">Rincian Rekening</a></li>
		</ul>
		
		<div class="tab-content" style="height:280px;">
			<div class="tab-pane active" id="dok">
				<div class="control-group pull-left">
					<div id="fileuploader_mat"><i class="icon-plus icon-white"></i>Upload</div>
				</div>
				<div class="ccontrol-group pull-left" style="margin-top:10px;">
					<table id="grid_file_mat"></table>
					<div id="pager_file_mat"></div>
				</div>
			</div>
			<div class="tab-pane" id="rinc">
				<table id="grd_rek"></table>
				<div id="pgr_rek"></div>
			</div>
		</div>
			
		
		<div class="bottom-bar" style="margin-top:10px">
			<input type="button" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
			<input type="button" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
			<div class="btn-group dropup">
				<button type="button" class="btn btn-primary" data-bind="enable: canSave, click: function(data, event){save(false, data, event) }" />Simpan</button>
				<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave">
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="#" data-bind="enable: canSave, click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
				</ul>
			</div>
			<div class="btn-group dropup">
				<button type="button" class="btn btn-primary" data-bind="enable: canPrint, click: print" >Cetak</button>
				<button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint">
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="#" doc-type="pdf" data-bind="enable: canPrint, click: print">PDF</a></li>
					<li><a href="#" doc-type="xls" data-bind="enable: canPrint, click: print">XLS</a></li>
					<li><a href="#" doc-type="docx" data-bind="enable: canPrint, click: print">DOC</a></li>
				</ul>
			</div>
			<input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
		</div>
  </fieldset>
</form>

<style type="text/css">
#alertmod{
  top: 700px !important;
}
</style>

<script>
var data_mewakili = [{id:1, text:'Pribadi'}, {id:2, text:'Lembaga'}];
var lastrek, purge_filemat = [];
$(document).ready(function() {
  
	//$('.datepicker#tgl').datepicker();
	inisialisasi();

	$('.currency')
	.blur(function(){ $(this).formatCurrency(fmtCurrency); })
	.focus(function(){ $(this).toNumber(fmtCurrency); });

	$('#myTab a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	// fileupload material
	$("#fileuploader_mat").uploadFile({
		url:root+modul+"/do_upload",
		fileName:"file",
		uploadButtonClass:"btn btn-primary",
		dragDropStr:"",
		fileCounterStyle:". ",
		multiple:true,
		formData:{jenis_uji:'mat'},
		returnType:"json",
		onSuccess:function(files,data,xhr) {
			var isi = {'nama_doc':data.realname, 'nama_file':data.name, 'mime':data.mime, 'ukuran':data.size, 'tgl_upload':data.tgl};
			var $grid = $('#grid_file_mat'),
			rowids = $grid.jqGrid('getDataIDs'),
			last = rowids.length + 1;
			$grid.addRowData(last, isi);
			$('.ajax-file-upload-statusbar').hide();
		},
	});
	
	$("#grid_file_mat").jqGrid({
		url: App.id() ? root+modul+'/get_fileupload_mat/'+App.id() : '',
		datatype: App.id() ? 'json' : 'local',
		mtype: 'POST',
		colNames:['', 'Nama Dokumen', 'Nama File', 'Mime', 'Ukuran (bytes)', 'Tanggal Upload'],
		colModel:[
			{name:'id_doc', hidden:true},
			{name:'nama_doc', width:380, sortable:false},
			{name:'nama_file', hidden:true},
			{name:'mime', width:200, sortable:false},
			{name:'ukuran', width:150, formatter:'integer', align:'rught', sortable:false, align:'right'},
			{name:'tgl_upload', width:150, formatter:'date', align:'center', sortable:false},
		   ],
		pager:'#pager_file_mat',
		rowNum:10000,
		scroll:true,
		rownumbers:true,
		viewrecords:true,
		gridview:true,
		shrinkToFit:false,
		loadonce:true,
		width:'935',
		height:'70',
		recordtext:'{2} baris',
		caption:'Daftar File Upload'
	});

	$("#grid_file_mat").jqGrid('bindKeys', {});
	$("#grid_file_mat").jqGrid('navGrid', '#pager_file_mat', {
		add:false,
		edit:false,
		del:true,
		deltext:'Hapus',
		delfunc:del_fileupload,
		search:false,
		refresh:false,
    },{},{},{},{});		  
	
	function del_fileupload(id)
	{
		var $grid = $(this),
		rs = $(this).jqGrid('getRowData', id),
		answer = false,
		dokumen = rs.nama_doc,
		file = rs.nama_file;
		answer = confirm('Hapus ' + dokumen + ' dari daftar?');

		if(answer == true){
			if (App.id() > 0) {
				purge_filemat.push(id);
				var $grid = $(this),
					selrowid = $grid.jqGrid ('getGridParam', 'selrow'),
					id_doc = $grid.jqGrid ('getCell', selrowid, 'id_doc'),
					nama_file = $grid.jqGrid ('getCell', selrowid, 'nama_file');
				if (id_doc.length === 0) {
					unlink_file(nama_file, $grid, id);
				} 
				else {
					$(this).jqGrid('delRowData', id);
				}
			} 
			else {
				unlink_file(file, $grid, id);
			}		  
		}
	}
  
	function unlink_file(filename, $grid, id)
	{
		$.ajax({
			url: root+modul+'/delete_fileupload',
			type: 'post',
			dataType: 'json',
			data: {filename:filename},
			success: function(res, xhr){
				if (res.isSuccess === true)
				$grid.jqGrid('delRowData', id);
			}
		});
	}
	
	$("#grd_rek").jqGrid({
		url: '',
		datatype: 'local',
		mtype: 'POST',
		colNames:['', '', 'Kode Rekening', 'Nama Rekening', 'Nominal', 'Sisa', '', ''],
		colModel:[
			{name:'idra',hidden:true},
			{name:'idrek',hidden:true},
			{name:'kdrek',width:100, sortable:false},
			{name:'nmrek',width:400, sortable:false},
			{name:'nom',width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
			{name:'sisa',width:150, sortable:false, formatter:'currency', align:'right'},
			{name:'batas', hidden:true},
			{name:'lvl', hidden:true}
		],
		pager: '#pgr_rek',
		rowNum:10000,
		scroll:1,
		rownumbers:true,
		viewrecords: true,
		gridview: true,
		shrinkToFit:false,
		loadonce:true,
		width:'935',
		height:'210',
		loadComplete:function(){
			sisa_rekening();
		},
		onSelectRow: function(id){
			if(id && id!==lastrek){
				$(this).restoreRow(lastrek);
				lastrek=id;
			}
		},
		ondblClickRow: edit_row,
	});
	$("#grd_rek").jqGrid('bindKeys', { "onEnter": edit_row});
	$("#grd_rek").jqGrid('navGrid','#pgr_rek',{
		add:false,
		del:false,
		edit:true,
		edittext: 'Ubah',
		editfunc:edit_row,
		search:false,
		refresh:false,
		refreshtext:'Refresh'
	});
	
	function edit_row(id){
		var last;
		last = lastrek;

		$(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
		$(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);

		lastrek = id;
	};
	
	function after_save(id){
		$(this).focus();
		sisa_rekening();
	}
});

	function sisa_rekening(){
		var idarr = $('#grd_rek').jqGrid('getDataIDs');
		var nom=0;
		for(var i=0;i<idarr.length;i++){			
			datarek = $('#grd_rek').jqGrid('getRowData',idarr[i]);
			if(datarek.lvl=='2'){
				sisa = datarek.batas - datarek.nom;
				nom = datarek.nom;
				style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
				$('#grd_rek').jqGrid('setRowData', datarek.idra, {sisa: sisa}, style);
				App.nom(datarek.nom);
			}
		}
		for(var i=0;i<idarr.length;i++){
			datarek = $('#grd_rek').jqGrid('getRowData',idarr[i]);
			if(datarek.lvl=='1'){
				sisa_ = datarek.batas - nom;
				style_ = sisa_ < 0 ? {color: '#FF0000'} : {color: '#000000'};
				$('#grd_rek').jqGrid('setRowData', datarek.idra, {sisa: sisa_}, style_);
			}
		}
		App.sisa_sp2d(parseFloat(App.sisa_sp2d_asli()-App.nom()));
	}

	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: 'error',
	});
    
	var ModelBAST = function (){
		var self = this;
		self.modul = 'BAST';
		self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
		self.id = ko.observable('<?php echo isset($data['ID_BAST']) ? $data['ID_BAST'] : 0 ?>');
		self.no = ko.observable('<?php echo isset($data['NOMOR_BAST']) ? $data['NOMOR_BAST'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Nomor tidak boleh kosong'},
			maxLength: {params: 100, message: 'Nomor tidak boleh melebihi 100 karakter'},
		  });
		self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
		  .extend({
			required: {params: true, message: 'Tanggal tidak boleh kosong'},
		  });
		self.nom = ko.observable('<?php echo isset($data['NOMINAL']) ? $data['NOMINAL'] : 0 ?>')
		  .extend({
			required: {params: true, message: 'Nominal tidak boleh kosong'}
		  });
		self.sisa_sp2d = ko.observable('<?php echo isset($data['SISA_SP2D']) ? $data['SISA_SP2D'] : 0 ?>');
		self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD'])?$data['ID_SKPD']: 0 ?>)
		  .extend({
			required: {params: true, message: 'SKPD belum dipilih'},
			trackChange: true 
		  });
		self.kd_skpd = ko.observable(<?php echo isset($data['KODE_SKPD_LKP']) ? json_encode($data['KODE_SKPD_LKP']) : "''" ?>);
		self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD']) ? $data['NAMA_SKPD'] : '' ?>');
		self.id_rincian_anggaran = ko.observable(<?php echo isset($data['ID_RINCIAN_ANGGARAN'])?$data['ID_RINCIAN_ANGGARAN']: 0 ?>)
		  .extend({
			required: {params: true, message: 'Uraian belum dipilih'},
			trackChange: true 
		  });
		self.id_rekening = ko.observable(<?php echo isset($data['ID_REKENING'])?$data['ID_REKENING']: 0 ?>);
		self.uraian = ko.observable('<?php echo isset($data['URAIAN']) ? $data['URAIAN'] : '' ?>');
		self.nama = ko.observable('<?php echo isset($data['NAMA']) ? $data['NAMA'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Nama tidak boleh kosong'},
			maxLength: {params: 100, message: 'Nama tidak boleh melebihi 100 karakter'},
		  });
		self.nik = ko.observable('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>')
		  .extend({
			required: {params: true, message: 'NIK tidak boleh kosong'},
			maxLength: {params: 50, message: 'NIK tidak boleh melebihi 50 karakter'},
		  });
		self.alamat = ko.observable('<?php echo isset($data['ALAMAT']) ? $data['ALAMAT'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Alamat tidak boleh kosong'},
			maxLength: {params: 200, message: 'Alamat tidak boleh melebihi 200 karakter'},
		  });
		self.tgl_lahir = ko.observable('<?php echo isset($data['TANGGAL_LAHIR']) ? format_date($data['TANGGAL_LAHIR']) : date('d/m/Y') ?>');
		self.pekerjaan = ko.observable(<?php echo isset($data['PEKERJAAN']) ? json_encode($data['PEKERJAAN']) : "''" ?>)
		  .extend({
			required: {params: true, message: 'Jabatan/Pekerjaan belum dipilih'},
			maxLength: {params: 100, message: 'Jabatan/Pekerjaan tidak boleh melebihi 100 karakter'},
		  });
		self.mewakili = ko.observable('<?php echo isset($data['MEWAKILI']) ? $data['MEWAKILI'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Mewakili belum dipilih'}
		  });
		self.nama_rekening = ko.observable(<?php echo isset($data['NAMA_REKENING']) ? json_encode($data['NAMA_REKENING']) : "''" ?>);
		self.nomor_rekening = ko.observable(<?php echo isset($data['NOMOR_REKENING']) ? json_encode($data['NOMOR_REKENING']) : "''" ?>);
		self.nama_bank = ko.observable(<?php echo isset($data['NAMA_BANK']) ? json_encode($data['NAMA_BANK']) : "''" ?>);
		self.npwp = ko.observable(<?php echo isset($data['NPWP']) ? json_encode($data['NPWP']) : "''" ?>);
		self.pejabat_daerah = ko.observable('<?php echo isset($data['ID_PEJABAT_DAERAH']) ? $data['ID_PEJABAT_DAERAH'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Pejabat Daerah belum dipilih'}
		  });
		self.nm_pejabat_daerah = ko.observable('<?php echo isset($data['NAMA_PEJABAT_DAERAH']) ? $data['NAMA_PEJABAT_DAERAH'] : '' ?>');
		self.ppt = ko.observable('<?php echo isset($data['ID_PEJABAT_PENANDA_TANGAN']) ? $data['ID_PEJABAT_PENANDA_TANGAN'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Pejabat Penanda Tangan belum dipilih'}
		  });
		self.nm_ppt = ko.observable('<?php echo isset($data['NAMA_PEJABAT_PENANDA_TANGAN']) ? $data['NAMA_PEJABAT_PENANDA_TANGAN'] : '' ?>');
		self.peruntukan = ko.observable(<?php echo isset($data['PERUNTUKAN']) ? json_encode($data['PERUNTUKAN']) : "''" ?>)
			.extend({ 
			trackChange: true 
        });
		self.keperluan = ko.observable('<?php echo isset($data['KEPERLUAN']) ? $data['KEPERLUAN'] : '' ?>')
		  .extend({
			required: {params: true, message: 'Keperluan belum dipilih'}
		  });
		self.sisa_sp2d_asli = ko.observable(0);  		      
		self.mode = ko.computed(function(){
		  return self.id() > 0 ? 'edit' : 'new';
		});

		self.title = ko.computed(function(){
		  return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul + ' - Bantuan Sosial';
		});

		self.isEdit = ko.computed(function(){
		  return self.mode() === 'edit';
		});

		self.canPrint = ko.computed(function(){
		  return self.akses_level() >= 2 && self.mode() === 'edit';
		});

		self.canSave = ko.computed(function(){
		  return self.akses_level() >= 3;
		});
		
		self.errors = ko.validation.group(self);
        
	}

	var App = new ModelBAST();

	App.prev = function(){
		show_prev(modul, App.id());
	}

	App.next = function(){
		show_next(modul, App.id());
	}

	App.print = function(data, event){
		var doc = event.target.getAttribute('doc-type') || 'pdf';
		preview({"tipe":"form", "format":doc, "id": App.id()});
	}

	App.back = function(){
		location.href = root+modul;
	}
	
	App.pilih_skpd = function(){
		if(!App.keperluan()){show_warning('Keperluan belum dipilih.');return}
		var option = {multi:0, mode:'rka221'};
		Dialog.pilihSKPD(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.id_skpd(rs.id);
			App.kd_skpd(rs.kode);
			App.nm_skpd(rs.nama);
			App.pilih_uraian();
		});
	}
	
	App.pilih_uraian = function(){
		if(!App.id_skpd()){show_warning('SKPD belum dipilih.');return}
		var option = {multi:0, lvl:0, tree:2, id_skpd:App.id_skpd(), mode:'BANSOS'};
		Dialog.pilihUraian(option, function(obj, select){			
			if (select.length === 0) return;
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.id_rincian_anggaran(rs.idra);
			App.id_rekening(rs.idrek);
			App.uraian(rs.nmrek);	
			$("#grd_rek").jqGrid('clearGridData');
			App.nom(parseFloat(rs.pagu));
			App.init_grid();			
		});
	}
	
	App.keperluan.subscribe(function(new_keperluan){		
		App.init_grid();
	});
	
	App.nik.subscribe(function(){		
		App.getduplikat_nik();
	});
	
	App.init_grid = function(){
		data = {id:App.id(), idra:App.id_rincian_anggaran(), idrek:App.id_rekening(), id_skpd:App.id_skpd(), keperluan:App.keperluan()};
		$.ajax({
			type: "post",
			dataType: "json",
			url: root+modul+'/data_rekening',
			data: data,
			success: function(res) {				
				App.sisa_sp2d_asli(parseFloat(res.nominal_sp2d-res.bast_pakai));
				App.sisa_sp2d(parseFloat(res.nominal_sp2d-res.bast_pakai-App.nom()));
			},
		});	
		
		$("#grd_rek").jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/rincian/' + App.id() +'/'+App.id_skpd() +'/'+App.id_rekening() +'/'+App.id_rincian_anggaran(), 'datatype': 'json'});
		$("#grd_rek").trigger('reloadGrid');
	}
    
	App.formValidation = function(){
		var errmsg = [];  
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
		data['file_mat'] = JSON.stringify($('#grid_file_mat').jqGrid('getRowData'));
		data['purge_filemat'] = purge_filemat;

		$.ajax({
		  url: $frm.attr('action'),
		  type: 'post',
		  dataType: 'json',
		  data: data,
		  success: function(res, xhr){
			if (res.isSuccess){
			  if (res.id) App.id(res.id);
			  show_info(res.message, 'Sukses');
			}
			else show_error(res.message, 'Gagal');

			if (createNew) location.href = root+modul+'/form';
		  }
		});
	}
  
	App.init_select = function(element, callback){
		var data = {'text': $(element).attr('data-init')};
		callback(data);
	}

	App.query_pejabat_daerah = function(option){
		$.ajax({
			url: "<?php echo base_url()?>pilih/pejabat_daerah",
			type: 'POST',
			dataType: 'json',
			data: {
				'q': option.term
			},
			success: function (data) {
				option.callback({
				results: data.results
				});
			}
		});
	};
	
	App.query_pejabat_penanda_tangan = function(option){
		$.ajax({
			url: "<?php echo base_url()?>pilih/pejabat_daerah",
			type: 'POST',
			dataType: 'json',
			data: {
				'q': option.term
			},
			success: function (data) {
				option.callback({
				results: data.results
				});
			}
		});
	};
	
	//promise untuk mengecek nik, jalan setelah promise no selesai
	App.getduplikat_nik = function () {
		return new Promise(function (resolve, reject) {
			var ajax = $.ajax({
				type: "post",
				dataType: "json",
				url: root+modul+'/duplikat_nik/',
				data: {no: App.no(), nik : App.nik(),id:App.id()},
				success: function(res, xhr){
					var tanggal = res.tanggal;
					if(res.isSuccess == false){					
						return false;
					}
					else
					{
						return true;
					}
				}
			});

			ajax.done(function(data) {
				if (data.isSuccess == true){
					console.log('true');
					resolve(data.isSuccess);
				}else{
					var tanggal = data.tanggal;
					answer = confirm('NIK tersebut sudah pernah mengajukan '+data.jenis+' pada tanggal '+tanggal+'. Lanjutkan entri data dengan NIK tersebut?');		
					if(answer == true){
						resolve(answer);
						return true;
					}
					else
					{
						//return false;
						location.href = root+modul;
					}
				}
			});
		});
	};
  
	ko.applyBindings(App);
	setTimeout(function(){
		App.init_grid();
	}, 500);
  
</script>
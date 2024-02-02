<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>spj/proses" enctype="multipart/form-data">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: no" >
      <label class="control-label" for="no">Nomor</label>
      <input type="text" id="no" class="span3" data-bind="value: no, attr: {readonly: isEdit() }" required/>
    </div>
    <div class="control-group pull-left" style="margin-left:20px"  data-bind="validationElement: tgl">
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" required placeholder="dd/mm/yyyy"/>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: id_skpd" >
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" />
      <div class="controls span5 input-append">
        <input type="text" class="span5" id="nama_skpd" readonly="1" data-bind="value: nm_skpd, executeOnEnter: pilih_skpd" />
        <span class="add-on" data-bind="visible: !isEdit() && !isSKPD && canSave(), click: pilih_skpd"><i class="icon-folder-open"></i></span>
      </div>
    </div>
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
    <div class="controls-group pull-left">
      <div class="control-group controls-row" data-bind="validationElement: deskripsi">
        <label class="control-label" for="deskripsi">Keterangan</label>
        <textarea rows="2" class="span7" data-bind="value: deskripsi" required></textarea>
      </div>
      <div class="control-group controls-row" data-bind="validationElement: pekas" >
        <label class="control-label" for="kdrek_pekas">Rekening Bendahara</label>
        <input type="text" class="span2" id="kdrek_pekas" data-bind="value: kd_pekas, executeOnEnter: pilih_pekas" required readonly="1" />
        <div class="controls input-append span5">
          <input type="text" class="span5" id="nmrek_pekas" data-bind="value: nm_pekas, executeOnEnter: pilih_pekas" required readonly="1" />
          <span class="add-on" data-bind="visible: !isEdit(), click: pilih_pekas" ><i class="icon-folder-open"></i></span>
        </div>
      </div>

    </div>
    <div class="controls-group pull-right" style="display: none;">
      <div class="control-group span2" data-bind="validationElement: keperluan" >
        <fieldset>
          <h6>Keperluan</h6>
          <label class="radio">
            <input type="radio" data-bind="disable: isEdit, checked: keperluan" value="LS" />LS
          </label>
        </fieldset>
      </div>
      <div class="control-group pull-right" data-bind="validationElement: beban">
        <fieldset>
          <h6>Beban</h6>
          <label class="radio">
            <input type="radio" data-bind="disable: isEdit, checked: beban" value="BTL" /> Beban Tidak Langsung
          </label>
        </fieldset>
      </div>
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
    <li class="active"><a href="#detailspj">Detail SPJ</a></li>
    <li><a href="#rincianspj">Rincian Rekening</a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane active" id="detailspj">
      <div class="control-group pull-left">
        <div class="controls-row" style="margin-bottom:10px;">
          <label class="control-label" for="bk">Bendahara Pengeluaran</label>
          <input type="text" id="bk" class="span8" data-bind="attr : {'data-init': nm_bk}, value: bk, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Bendahara Pengeluaran', initSelection: init_select, query: query_pejabat_skpd }" />
        </div>
        <div class="controls-row" style="margin-bottom:10px">
          <label class="control-label" for="pa">Penguna Anggaran</label>
          <input type="text" id="pa" class="span8" data-bind="attr : {'data-init': nm_pa}, value: pa, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, containerCssClass: 'span8', placeholder: 'Pejabat Pengguna Anggaran', initSelection: init_select, query: query_pejabat_skpd }" />
        </div>
        <div class="controls-row" style="margin-bottom:10px;">
          <div class="control-group pull-left" style="margin-bottom:10px;">
          <div id="fileuploader"><i class="icon-plus icon-white"></i>Upload</div>
          </div>
        </div>
        <div class="controls-row">
          <div class="control-group pull-left" >
            <table id="grid_file"></table>
            <div id="pager_file"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="rincianspj">
      <table id="grdrek"></table>
      <div id="pgrrek"></div>
      <div class="controls pull-right" style="margin-top:5px;">
        <label style="float:left; margin-top:10px; margin-right:10px;" for="total_rek">Total Rekening</label>
        <input type="text" id="total_rek" class="span3 currency" data-bind="numeralvalue: total_rek" />
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-right" style="margin-top:5px; margin-left:20px" data-bind="validationElement: total_rek">
      <label style="float:left; margin-top:10px; margin-right:10px;" for="total">Total</label>
      <input type="text" id="total" class="span3 currency" readonly="1" data-bind="numeralvalue: total_rek" />
    </div>
  </div>

  <div class="bottom-bar">
    <input type="button" id="prev" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
    <input type="button" id="next" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
    <div class="btn-group dropup">
      <button type="button" class="btn btn-primary" data-bind="enable: canSave && !processing(), click: function(data, event){save(false, data, event) }" />Simpan</button>
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave">
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#" data-bind="enable: canSave, click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
      </ul>
    </div>
    <div class="btn-group dropup">
      <button type="button" class="btn btn-primary" id="print" data-bind="enable: canPrint, click: print">Cetak</button>
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint">
        <span class="caret"></span>
      </button type="button" >
      <ul class="dropdown-menu">
        <li><a href="#" doc-type="pdf" data-bind="enable: canPrint, click: print">PDF</a></li>
        <li><a href="#" doc-type="xls" data-bind="enable: canPrint, click: print">XLS</a></li>
      </ul>
    </div>
    <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
  </div>
</form>

<script>
var last, batas = batas_all = newid = 0,
    purge_rek = [];
var purge_file = [];

$(document).ready(function() {
  inisialisasi();

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#grdrek").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['','', 'Kode Rekening', 'Nama Rekening', 'Nominal', 'Sisa s/d sekarang', 'Sisa keseluruhan', '', ''],
    colModel:[
        {name:'idr', hidden:true},
        {name:'idrek', hidden:true},
        {name:'kdrek', width:100, sortable: false},
        {name:'nmrek', width:300, sortable: false},
        {name:'nom', width:150, sortable: false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
        {name:'sisa', width:150, sortable: false, formatter:'currency', align:'right'},
        {name:'sisa_all', width:150, sortable: false, formatter:'currency', align:'right'},
        {name:'batas', hidden:true},
        {name:'batas_all', hidden:true},
    ],
    pager:'#pgrrek',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'200',
    recordtext:'{2} baris',
    loadComplete:function(){
      TotalRekening();
      var idarr = $(this).jqGrid('getDataIDs');
      GetSisaRekening(idarr);
    },
    onSelectRow: function(id){
      if(id && id !== last){
         $(this).restoreRow(last);
         last = id;
      }
    },
    ondblClickRow: edit_row,
  });
  $("#grdrek").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#grdrek").jqGrid('navGrid', '#pgrrek', {
    add:true,
    addtext: 'Tambah',
    addfunc:add_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    edit:false,
    search:false,
    refresh:false,
    refreshtext:'Refresh',
  },{},{},{},{});

   // fileupload 
  $("#fileuploader").uploadFile({
    url:root+modul+"/do_upload",
    fileName:"file",
    uploadButtonClass:"btn btn-primary",
    doneButtonClass:"btn btn-primary",
    dragDropStr:"",
    fileCounterStyle:". ",
    multiple:true,
    returnType:"json",
    onSuccess:function(files,data,xhr) {
      var isi = {'nama_doc':data.realname, 'nama_file':data.name, 'mime':data.mime, 'ukuran':data.size, 'tgl_upload':data.tgl};
      var $grid = $('#grid_file'),
          rowids = $grid.jqGrid('getDataIDs'),
          last = rowids.length + 1;
      $grid.addRowData(last, isi);
    
    },
	});
  
  $("#grid_file").jqGrid({
    url: App.id() ? root+modul+'/get_fileupload/'+App.id() : '',
    datatype: App.id() ? 'json' : 'local',
    mtype: 'POST',
    colNames:['', 'Nama Dokumen', 'Nama File', 'Mime', 'Ukuran (bytes)', 'Tanggal Upload'],
    colModel:[
        {name:'id_doc', hidden:true},
        {name:'nama_doc', width:380, sortable:false},
        {name:'nama_file', hidden:true},
        {name:'mime', width:200, sortable:false},
        {name:'ukuran', width:150, formatter:'integer', sortable:false, align:'right'},
        {name:'tgl_upload', width:150, formatter:'date', align:'center', sortable:false},
       ],
    pager:'#pager_file',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'200',
    recordtext:'{2} baris',
  });

  $("#grid_file").jqGrid('bindKeys', {});
  $("#grid_file").jqGrid('navGrid', '#pager_file', {
      add:false,
      edittext: 'Preview',
		editicon:'ui-icon-search',
		editfunc: function(id){
			var rf = $('#grid_file').jqGrid('getRowData', id);
			if(rf.nama_doc)
			{
				url = "<?php echo base_url()?>assets/ViewerJS/#../../uploads/"+rf.nama_file; 
				window.open( url, '_blank');
			}
		},
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
        purge_file.push(id);
        var $grid = $(this),
            selrowid = $grid.jqGrid ('getGridParam', 'selrow'),
            id_doc = $grid.jqGrid ('getCell', selrowid, 'id_doc'),
            nama_file = $grid.jqGrid ('getCell', selrowid, 'nama_file');
        if (id_doc.length === 0) {
          unlink_file(nama_file, $grid, id);
        } else {
          $(this).jqGrid('delRowData', id);
        }
      } else {
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
  
  
});

  /*function TotalRekening(){
    var total =  $('#grdrek').jqGrid('getCol', 'nom', '', 'sum');
    App.total_rek(total);
  }*/
  
  function TotalRekening(){
    var total = i = 0,
      totalkeg = [],
      datarek = $('#grdrek').jqGrid('getRowData');
    for (i = 0; i < datarek.length; i++){
      total += parseFloat(datarek[i].nom);
    }
    App.total_rek(total);

  }
  
	function GetSisaSKPD(){
		var data = {
			id: App.id(),
			id_skpd: App.id_skpd(),
			tanggal: App.tgl(),
			beban: App.beban(),
			keperluan: App.keperluan(),
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
  
	function HitungSisaRekening(id){
		var rs = $('#grdrek').jqGrid('getRowData', id),
		sisa = parseFloat(rs.batas) - parseFloat(rs.nom),
		sisa_all = parseFloat(rs.batas_all) - parseFloat(rs.nom),
		style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
		$('#grdrek').jqGrid('setRowData', id, {sisa: sisa, sisa_all: sisa_all}, style);
	}

	function GetSisaRekening(idarr){
		var len = idarr.length,
		rs = data = {};

		for (var i = 0; i < len; i++){
			rs = $('#grdrek').jqGrid('getRowData', idarr[i]);

			data = {
				id: App.id(),
				idr: idarr[i],
				id_skpd: App.id_skpd(),
				tanggal: App.tgl(),
				beban: App.beban(),
				keperluan: App.keperluan(),
				//id_kegiatan: rs.idkeg,
				id_rekening: rs.idrek
			};

			$.ajax({
				type: "post",
				dataType: "json",
				url: root+modul+'/sisa_rekening',
				data: data,
				success: function(res) {
					$('#grdrek').jqGrid('setRowData', res.idr, {batas: res.sisa, batas_all: res.sisa_all});
					HitungSisaRekening(res.idr);
				},
			});
		}
	}

	function add_row(){
		var id_skpd = App.id_skpd(),
        id_spj = App.id(),
        beban = App.beban(),
        keperluan = App.keperluan(),
        tanggal = App.tgl(),
        $grdrek = $('#grdrek'),
        option = rskeg = rsrek = srcid = [],
        i = 0;

		if (("" === beban) || (undefined === id_skpd)){
			var message = id_skpd ? '' : 'SKPD';
			message += ("" === beban ? (message ? ' dan ' : '') + 'Beban' : '');
			message += ' belum dipilih.';
			show_warning(message);
		}
		else {
			var option = {multi:1, id_skpd:id_skpd, beban:beban, keperluan:keperluan, tanggal:tanggal, mode:'spj', id_spj:id_spj};
			Dialog.pilihRekeningSPJ(option, function(obj, select){
			for (i = 0; i < select.length; i++){
					rsrek = $(obj).jqGrid('getRowData', select[i].id);
					newid = newid - 1;
					addRowSorted($grdrek, {'id':'idr', 'sortName':['kdrek']}, {'idr':newid, 'idrek':rsrek.idrek, 'kdrek':rsrek.kdrek, 'nmrek':rsrek.nmrek});
					GetSisaRekening([newid]);
				}
			});
		}
	};

	function edit_row(id){
		$(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
		$(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
		last = id;
	};

	function after_save(id){
		$(this).focus();
		TotalRekening();
		HitungSisaRekening(id);
	}

	function del_row(id){
		var rs = {},
		answer = false,
		rek = '',
		len = id.length;

		rs = $(this).jqGrid('getRowData', id);
		kode = rs.kdrek;
		answer = confirm('Hapus ' + kode + ' dari daftar?');

		if(answer == true){
			idrek = id;
			purge_rek.push(idrek);
			$(this).jqGrid('delRowData', idrek);
			datarek = $('#grdrek').jqGrid('getRowData');
			TotalRekening();
		}
	};

 ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelSPJ = function (){
    var self = this;
    self.modul_display = '<?php echo $modul_display ?>';
    self.processing = ko.observable(false);
    self.auto = ko.observable(<?php echo isset($data['ID_AKTIVITAS']) ? 'false' : 'true' ?>);
    self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_AKTIVITAS']) ? $data['ID_AKTIVITAS'] : 0 ?>');
    self.no = ko.observable(<?php echo isset($data['NOMOR'])?json_encode($data['NOMOR']) : '' ?>)
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'}
      });
    self.total_rek = ko.observable(0)
      .extend({
        required: {params: true, message: 'Total tidak boleh kosong'},
      });
    self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : '' ?>)
      .extend({
        required: {params: true, message: 'SKPD belum dipilih'}
      });
    self.kd_skpd = ko.observable(<?php echo isset($data['KODE_SKPD_LKP']) ? json_encode($data['KODE_SKPD_LKP']) : '' ?>);
    self.nm_skpd = ko.observable(<?php echo isset($data['NAMA_SKPD']) ? json_encode($data['NAMA_SKPD']) : '' ?>);
    self.deskripsi = ko.observable(<?php echo isset($data['DESKRIPSI']) ? json_encode($data['DESKRIPSI']) : '' ?>)
      .extend({
        required: {params: true, message: 'Deskripsi tidak boleh kosong'}
      });
    self.keperluan = ko.observable('<?php echo isset($data['KEPERLUAN']) ? $data['KEPERLUAN'] : 'TU' ?>')
      .extend({
        required: {params: true, message: 'Keperluan belum dipilih'}
      });
    self.beban = ko.observable('<?php echo isset($data['BEBAN']) ? $data['BEBAN'] : 'BTL' ?>')
      .extend({
        required: {params: true, message: 'Beban belum dipilih'}
      });
    self.pekas = ko.observable(<?php echo isset($data['ID_REKENING_PEKAS']) ? $data['ID_REKENING_PEKAS'] : '' ?>)
      .extend({
        required: {params: true, message: 'Rekening bendahara pengeluaran belum diisi'}
      });
    self.kd_pekas = ko.observable(<?php echo isset($data['KODE_REKENING_PEKAS']) ? json_encode($data['KODE_REKENING_PEKAS']) : '' ?>);
    self.nm_pekas = ko.observable(<?php echo isset($data['NAMA_REKENING_PEKAS']) ? json_encode($data['NAMA_REKENING_PEKAS']) : '' ?>);
    self.bk = ko.observable('<?php echo isset($data['ID_BK']) ? $data['ID_BK'] : '' ?>');
    self.nm_bk = ko.observable(<?php echo isset($data['BK_NAMA']) ? json_encode($data['BK_NAMA']) : '' ?>);
    self.pa = ko.observable('<?php echo isset($data['ID_PA']) ? $data['ID_PA'] : '' ?>');
    self.nm_pa = ko.observable(<?php echo isset($data['PA_NAMA']) ? json_encode($data['PA_NAMA']) : '' ?>);
    self.batas = ko.observable(0);
    self.batas_all = ko.observable(0);

    self.id_skpd.subscribe(function(){
      //var grdkeg = $('#grdkeg');
      var grdrek = $('#grdrek');
      //self.hapusRincian(grdkeg);
      self.hapusRincian(grdrek);

      self.updatePejabat();
      self.updateSumberDana();

      GetSisaSKPD();
    });

    self.beban.subscribe(function(new_beban){
      var grdrek = $('#grdrek');
      self.hapusRincian(grdrek);
      GetSisaSKPD();
    });

    self.tgl.subscribe(function(){
      GetSisaSKPD();
    });

    self.keperluan.subscribe(function(){
      GetSisaSKPD();
    });

    self.sisa_sp2d = ko.computed(function(){
      return self.batas() - self.total_rek()
    });

    self.sisa_sp2d_all = ko.computed(function(){
      return self.batas_all() - self.total_rek();
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

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
  }

  var App = new ModelSPJ();

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

	App.formValidation = function(){
		var grdrek = $('#grdrek'), errmsg = [];
		// cek jika ada baris di grid belum disimpan
		checkGridRow(grdrek, 'idr', null);
		// cek jika grid belum diisi
		if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
			errmsg.push('Belum ada Rekening yang di entri.');
		}
		
		if (checkGridMinus(grdrek, 'sisa')){
			errmsg.push('Ada Rekening yang sisanya minus');
		}
		
		if (App.sisa_sp2d() < 0){
			errmsg.push('Sisa Kas tidak boleh minus');
		}
		
		if (App.sisa_sp2d_all() < 0){
			errmsg.push('Sisa Kas tidak boleh minus');
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
        data['rinci'] = JSON.stringify($('#grdrek').jqGrid('getRowData'));
        data['purge_rek'] = purge_rek;
        data['file'] = JSON.stringify($('#grid_file').jqGrid('getRowData'));
        data['purge_file'] = purge_file;
        
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
    var grdrek = $('#grdrek');

    if (App.id() > 0){
      grdrek.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rekening/' + App.id(), 'datatype': 'json'});
      grdrek.trigger('reloadGrid');
    }
    else {
      grdrek.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }

  App.hapusRincian = function(grid){
    grid.jqGrid('clearGridData');
    TotalRekening();
  }

  App.pilih_skpd = function(){
    var option = {multi:0};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
    });
  }

  App.pilih_pekas = function(){
    var option = {multi:0, id_skpd:App.id_skpd};
    Dialog.pilihSumberdanaSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.pekas(rs.idrek);
      App.kd_pekas(rs.kdrek);
      App.nm_pekas(rs.nmrek);
    });
  }

  App.updateSumberDana = function() {
    App.default_sumberdana_skpd(App.id_skpd, App.pekas, App.kd_pekas, App.nm_pekas);
  }

  App.updatePejabat = function(){
    App.default_pejabat_skpd(App.id_skpd, App.bk, App.nm_bk, 'BK');
    App.default_pejabat_skpd(App.id_skpd, App.pa, App.nm_pa, 'PA');
    //App.default_pejabat_skpd(App.id_skpd, App.ppk, App.nm_ppk, 'PPK');
  }

  App.default_pejabat_skpd = function(skpd, id_pejabat, nama_pejabat, kode){
    $.ajax({
      url: "<?php echo base_url();?>pilih/pejabat_skpd",
      type: 'POST',
      dataType: 'json',
      data: {skpd:skpd, kode:kode},
      success: function(res){
        if (res && res.results[0]){
          id_pejabat(res.results[0].id);
          nama_pejabat(res.results[0].text);
        }
      }
    });
  }

  App.default_sumberdana_skpd = function(skpd, id_pekas, kd_pekas, nm_pekas){
    $.ajax({
      url: "<?php echo base_url();?>pilih/sumberdana_skpd_def",
      type: 'POST',
      dataType: 'json',
      data: {skpd:skpd},
      success: function(res){
        if (res && res.results[0]){
          id_pekas ? id_pekas(res.results[0].idrek) : '';
          kd_pekas ? kd_pekas(res.results[0].kdrek) : '';
          nm_pekas ? nm_pekas(res.results[0].nmrek) : '';
        }
      }
    });
  }

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  }

  App.query_pejabat_skpd = function(option){
    var id_skpd = App.id_skpd();
    $.ajax({
      url: "<?php echo base_url()?>pilih/pejabat_skpd",
      type: 'POST',
      dataType: 'json',
      data: {
        'q': option.term,
        'skpd': id_skpd,
      },
      success: function (data) {
        option.callback({
            results: data.results
        });
      }
    });
  };

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
if (!isset($data['ID_AKTIVITAS'])){
  echo "App.auto.valueHasMutated();";
}
?>
    GetSisaSKPD();
    if (App.beban()) App.beban.valueHasMutated();
    App.init_grid();

  }, 500)
</script>
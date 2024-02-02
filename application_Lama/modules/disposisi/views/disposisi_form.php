<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>disposisi/<?php echo $link_proses;?>">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: bantuan" >
      <label class="control-label" for="bantuan">Jenis Bantuan</label>
      <select id="bantuan" class="span2" data-bind="options: opsiBantuan, optionsValue:'value', optionsText:'text', value: bantuan, enable: !isEdit()"></select>
    </div>
    <div class="control-group pull-left" data-bind="validationElement: no_proposal" style="margin-left:20px" >
      <label class="control-label" for="no_proposal">Nomor Proposal</label>
      <div class="controls input-append">
        <input type="text" class="span3" id="no_proposal" readonly="1" data-bind="value: no_proposal, executeOnEnter: pilih_proposal" required />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(),  click: pilih_proposal" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
    <div class="control-group pull-left" data-bind="validationElement: no_disposisi" style="margin-left:20px" >
      <label class="control-label" for="no_disposisi">Nomor Rekomendasi</label>
      <input type="text" class="span3" id="no_disposisi" data-bind="value: no_disposisi" required />
    </div>
    <div class="control-group pull-right" data-bind="validationElement: tgl" >
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" class="span2 datepicker" id="tgl" data-bind="value: tgl" required />
    </div>
  </div>

  <div class="controls-row" style="margin-bottom:20px;">
    <table id="grid"></table>
    <div id="pager"></div>
  </div>

  <div class="controls-row">
    <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
      <li class="control-group" data-bind="validationElement: hasil_uji_adm"><a class="control-label" href="#uji_adm">Uji Administrasi</a></li>
      <li class="control-group" data-bind="validationElement: hasil_uji_mat"><a class="control-label" href="#uji_mat">Uji Material</a></li>
      <li class="control-group" ><a class="control-label" href="#tim_uji">Tim Penguji</a></li>
      <li class="control-group active" data-bind="validationElement: tab_disposisi"><a class="control-label" href="#disposisi">Rekomendasi</a></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane" id="uji_adm">
        <div class="control-group pull-left" style="margin-right:20px;">
          <label class="control-label" for="uji_adm">Catatan</label>
          <textarea rows="5" class="span10" id="uji_adm" data-bind="value: uji_adm" disabled ></textarea>
        </div>
        <div class="control-group pull-left" >
          <label class="control-label" >Rekomendasi</label>
          <label class="radio">
            <input type="radio" data-bind="checked: hasil_uji_adm" value="1" disabled />Terima
          </label>
          <label class="radio">
            <input type="radio" data-bind="checked: hasil_uji_adm" value="0" disabled />Tolak
          </label>
          <!--<button type="button" class="btn btn-primary" disabled >Upload</button>-->
        </div>
		<div class="ccontrol-group pull-left" style="margin-top:10px;">
			<table id="grid_file_adm"></table>
			<div id="pager_file_adm"></div>
		</div>
      </div>
      <div class="tab-pane" id="uji_mat">
        <div class="control-group pull-left" style="margin-right:20px;">
          <label class="control-label" for="uji_mat">Catatan</label>
          <textarea rows="5" class="span10" id="uji_mat" data-bind="value: uji_mat" disabled ></textarea>
        </div>
        <div class="control-group pull-left" >
          <label class="control-label" >Rekomendasi</label>
          <label class="radio">
            <input type="radio" data-bind="checked: hasil_uji_mat" value="1" disabled />Terima
          </label>
          <label class="radio">
            <input type="radio" data-bind="checked: hasil_uji_mat" value="0" disabled />Tolak
          </label>
          <!--<button type="button" class="btn btn-primary" disabled >Upload</button>-->
        </div>
		<div class="ccontrol-group pull-left" style="margin-top:10px;">
			<table id="grid_file_mat"></table>
			<div id="pager_file_mat"></div>
		</div>
      </div>
      <div class="tab-pane" id="tim_uji" >
        <!--<div class="control-group" style="position: absolute;">
          <input type="text" id="tim_uji" class="span8" disabled data-bind="attr : {'data-init': nm_timuji}, value: tim_uji, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Tim Penguji', initSelection: init_select, query: query_tim_uji }" />
        </div>-->
       
          <table id="grd_tim_uji"></table>
          <div id="pgr_tim_uji"></div>

      </div>
      <div class="tab-pane active" id="disposisi">
        <div class="control-group pull-left" style="margin-right:20px;" data-bind="validationElement: cttn_disposisi">
          <label class="control-label" for="cttn_disposisi">Catatan</label>
          <textarea rows="5" class="span10" id="cttn_disposisi" data-bind="value: cttn_disposisi"></textarea>
        </div>
        <div class="control-group pull-left" data-bind="validationElement: hasil_disposisi" >
          <label class="control-label" >Keputusan</label>
          <label class="radio">
            <input type="radio" data-bind="checked: hasil_disposisi" value="1" />Terima
          </label>
          <label class="radio">
            <input type="radio" data-bind="checked: hasil_disposisi" value="0" />Tolak
          </label>
        </div>
      </div>
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
      </ul>
    </div>
    <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
  </div>
</form>

<script>
var files = [];

$(document).ready(function() {
  
	$('.datepicker#tgl').datepicker({
		minDate: App.tgl_pengujian()
	});
  
  inisialisasi();

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })


  $("#grid").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'NIK', 'Nama Pemohon', 'Alamat', 'Nama Pimpinan', 'Tanggal Berdiri', 'Ringkasan Proposal', 'Nominal Pengajuan'],
    colModel:[
        {name:'id', hidden:true},
        {name:'nik', width:100, sortable:false},
        {name:'nama_pmh', width:150, sortable:false},
        {name:'alamat_pmh', width:200, sortable:false},
        {name:'nama_pimp', width:100, sortable:false},
        {name:'tgl_lhr', width:100, formatter:'date', align:'center', sortable:false},
        {name:'ringkasan', width:300, sortable:false},
        {name:'nom_aju', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'}
       ],
    pager:'#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'240',
    recordtext:'{2} baris',
  });

  $("#grid").jqGrid('navGrid', '#pager', {
      add:false,
      edit:false,
      del:false,
      search:false,
      refresh:false,
    },{},{},{},{});
	
	$("#grid_file_adm").jqGrid({
    url: App.id() ? root+modul+'/get_fileupload_adm/'+App.id_proposal() : '',
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
    pager:'#pager_file_adm',
    rowNum:-1,
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

  $("#grid_file_adm").jqGrid('bindKeys', {});
  $("#grid_file_adm").jqGrid('navGrid', '#pager_file_adm', {
      add:false,
      edit:false,
      del:false,
      search:false,
      refresh:false,
    },{},{},{},{});
    
  $("#grid_file_mat").jqGrid({
    url: App.id() ? root+modul+'/get_fileupload_mat/'+App.id_proposal() : '',
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
    rowNum:-1,
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
      del:false,
      search:false,
      refresh:false,
    },{},{},{},{});

  
  $("#grd_tim_uji").jqGrid({
		url: App.id() ? root+modul+'/get_tim_uji/'+App.id_proposal() : '',
		datatype: App.id() ? 'json' : 'local',
		mtype: 'POST',
		colNames:['', 'NIP', 'Nama'],
		colModel:[
		  {name:'id', hidden:true},
		  {name:'nip', width:150},
		  {name:'nama', width:650, sortable:false},
		],
		pager:'#pgr_tim_uji',
		rowNum:-1,
		scroll:true,
		rownumbers:true,
		viewrecords:true,
		gridview:true,
		shrinkToFit:false,
    loadonce:true,
		width:'935',
		height:'300',
		recordtext:'{2} baris',
    caption:'Daftar Tim Penguji'
	  });

	  $("#grd_tim_uji").jqGrid('bindKeys', {});
	  $("#grd_tim_uji").jqGrid('navGrid', '#pgr_tim_uji', {
		add:false,
      edit:false,
      del:false,
      search:false,
      refresh:false,
		},{},{},{},{});
});
  
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });
    
  var JenisBantuan = function(value, text) {
    this.value = value;
    this.text = text;
  }

  var ModelDisposisi = function (){
    var self = this;
    self.modul = 'Disposisi';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_PROPOSAL']) ? $data['ID_PROPOSAL'] : 0 ?>');
    self.id_proposal = ko.observable('<?php echo isset($data['ID_PROPOSAL']) ? $data['ID_PROPOSAL'] : 0 ?>');
    self.no_proposal = ko.observable('<?php echo isset($data['NOMOR_PROPOSAL']) ? $data['NOMOR_PROPOSAL'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor Proposal tidak boleh kosong'},
      });
    self.no_disposisi = ko.observable('<?php echo isset($data['NOMOR_DISPOSISI']) ? $data['NOMOR_DISPOSISI'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor Disposisi tidak boleh kosong'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'},
      });
    self.opsiBantuan = ko.observableArray([
          <?php foreach ($jenis_bantuan as $key=>$val) { echo "new JenisBantuan('".$val['JENIS_BANTUAN']."', '".$val['JENIS_BANTUAN']."'),"; } ?> ]);
    self.bantuan = ko.observable('<?php echo isset($data['JENIS_BANTUAN']) ? $data['JENIS_BANTUAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Bantuan tidak boleh kosong'},
      });
    self.uji_adm = ko.observable('<?php echo isset($data['UJI_ADMINISTRASI']) ? $data['UJI_ADMINISTRASI'] : '' ?>');
    self.uji_mat = ko.observable('<?php echo isset($data['UJI_MATERIAL']) ? $data['UJI_MATERIAL'] : '' ?>');
    self.nom_aju = ko.observable('<?php echo isset($data['NOMINAL_DIAJUKAN']) ? $data['NOMINAL_DIAJUKAN'] : 0 ?>');
    self.hasil_uji_adm = ko.observable('<?php echo isset($data['HASIL_UJI_ADMINISTRASI']) ? $data['HASIL_UJI_ADMINISTRASI']:'' ?>')
      .extend({
        required: {params: true, message: 'Hasil Uji Administrasi belum dipilih'}
      });
    self.hasil_uji_mat = ko.observable('<?php echo isset($data['HASIL_UJI_MATERIAL']) ? $data['HASIL_UJI_MATERIAL']:'' ?>')
      .extend({
        required: {params: true, message: 'Hasil Uji Material belum dipilih'}
      });    
    self.hasil_disposisi = ko.observable('<?php echo isset($data['KEPUTUSAN']) ? $data['KEPUTUSAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Keputusan belum dipilih'},
      });
    self.cttn_disposisi = ko.observable('<?php echo isset($data['CATATAN']) ? $data['CATATAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Catatan Disposisi tidak boleh kosong'},
      });
    //self.tim_uji = ko.observable('<?php echo isset($data['ID_PEJABAT']) ? $data['ID_PEJABAT'] : '' ?>');
    //self.nm_timuji = ko.observable('<?php echo isset($data['NAMA_PEJABAT']) ? $data['NAMA_PEJABAT'] : '' ?>');
    self.tgl_pengujian = ko.observable('');
    self.tab_disposisi = ko.observable('')
      .extend({
        required: {params: true, message: 'Catatan Disposisi tidak boleh kosong dan Keputusan belum dipilih'},
      });
    
    self.cek_tab_disposisi = ko.computed(function(){
      if (self.hasil_disposisi() !== '' && self.cttn_disposisi() !== '') self.tab_disposisi(1); 
    })
    
    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + 'Rekomendasi';
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

  var App = new ModelDisposisi();

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
    var errmsg = [];  
    if (!App.isValid()){
      errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
      App.errors.showAllMessages();
    }

    if (errmsg.length > 0) {
      $.pnotify({
        title: 'Perhatian',
        text: errmsg.join('</br>'),
        type: 'warning'
      });
      return false;
    }
    return true;
  }

  App.save = function(createNew){
    if (!App.formValidation()){ return }

    var $frm = $('#frm'),
        data = JSON.parse(ko.toJSON(App));

    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        if (res.isSuccess){
          if (res.id) App.id(res.id);
          App.init_grid();
        }

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });

        if (createNew) location.href = '<?php echo base_url().$modul; ?>/form/';
      }
    });
  }
  
  App.init_grid = function(){
    var grid = $('#grid');
    var grid_file_adm = $('#grid_file_adm');
    var grid_file_mat = $('#grid_file_mat');
    var grd_tim_uji = $('#grd_tim_uji');
    
    if (App.id_proposal() > 0){
      grid.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rinci/' + App.id_proposal(), 'datatype': 'json'});
      grid.trigger('reloadGrid');
	  
	  grid_file_adm.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/get_fileupload_adm/' + App.id_proposal(), 'datatype': 'json'});
      grid_file_adm.trigger('reloadGrid');
	  
	  grid_file_mat.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/get_fileupload_mat/' + App.id_proposal(), 'datatype': 'json'});
      grid_file_mat.trigger('reloadGrid');
      
    grd_tim_uji.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/get_tim_uji/' + App.id_proposal(), 'datatype': 'json'});
      grd_tim_uji.trigger('reloadGrid');
    }
    else {
      grid.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grid_file_adm.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grid_file_mat.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_tim_uji.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }
  
  App.get_data_uji = function(){
    $.ajax({
      url: root+modul+'/get_data_uji',
      type: 'post',
      dataType: 'json',
      data: {id: App.id_proposal()},
      success: function(res, xhr){
        App.uji_adm(res['UJI_ADMINISTRASI']);
        App.uji_mat(res['UJI_MATERIAL']);
        App.hasil_uji_adm(res['HASIL_UJI_ADMINISTRASI']);
        App.hasil_uji_mat(res['HASIL_UJI_MATERIAL']);
        //App.tim_uji(res['ID_PEJABAT']);
        //App.nm_timuji(res['NAMA_PEJABAT']);
      }
    });
  }

	App.pilih_proposal = function(){
		if (!App.canSave() || App.isEdit()) { return; }
		var option = {multi:0, mode:'disposisi', bantuan:App.bantuan()};
				
		Dialog.pilihProposal(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
 			App.id_proposal(rs.id);
			App.no_proposal(rs.no);
			App.tgl_pengujian(rs.tgl);
			
			$('.datepicker#tgl').datepicker("option", "minDate", App.tgl_pengujian());

			App.init_grid();
			App.get_data_uji();

		});
	}

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  }

  App.query_tim_uji = function(option){
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
  
  ko.applyBindings(App);
  setTimeout(function(){
    App.init_grid();
  }, 500)
  
</script>
<fieldset>
<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>spm/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: no" >
      <label class="control-label" for="no">Nomor</label>
      <input type="text" id="no" class="span3" data-bind="value: no" required />
    </div>
    <div class="control-group pull-left span2" style="margin-left:20px" data-bind="validationElement: tgl" >
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" required />
    </div>
    <div class="control-group pull-left span3" style="margin-left:20px" data-bind="validationElement: id_spp" >
      <label class="control-label" for="no_spp" >Pilih SPP</label>
      <div class="controls input-append">
        <input type="text" id="no_spp" class="span3" readonly="1" data-bind="value: no_spp, executeOnEnter: pilih_spp" />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_spp" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: id_skpd">
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd" />
      <input type="text" class="span6" id="nama_skpd" readonly="1" data-bind="value: nm_skpd" />
    </div>
    <div class="control-group pull-right" data-bind="visible: keperluan() != 'UP', validationElement: sisa_pagu">
      <label class="control-label" data-bind="text: label_sisa_pagu" for="sisa_pagu">Sisa Anggaran</label>
      <input type="text" id="sisa_pagu" class="span3 currency" readonly="1" data-bind="numeralvalue: sisa_pagu" />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: deskripsi">
      <label for="deskripsi">Keterangan</label>
      <textarea rows="2" class="span8" id="deskripsi" data-bind="value: deskripsi" required></textarea>
    </div>
    <div class="controls pull-right" data-bind="visible: keperluan() != 'UP'" >
      <label id="lbl_sisa_gu" for="sisa_gu">Sisa SPJ</label>
      <input type="text" id="sisa_gu" class="span3 currency" readonly="1" data-bind="numeralvalue: sisa_gu" />
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
    <li class="active"><a href="#dspm">Detail SPM</a></li>
    <li><a href="#rspd">SPP dan SPD</a></li>
    <li data-bind="visible: $.inArray(keperluan(), ['UP', 'PP'])  < 0 && beban() === 'BL'" ><a href="#rkegiatan">Kegiatan</a></li>
    <li data-bind="visible: keperluan() !== 'UP' " ><a href="#rrekening">Rekening</a></li>
    <li data-bind="visible: keperluan() === 'LS' " ><a href="#rpajak">Pajak/Informasi</a></li>
  </ul>

  <div class="tab-content" style="height:330px;">
    <div class="tab-pane active" id="dspm">
      <div class="controls-row">
        <div class="control-group pull-left">
          <div class="controls-row" style="margin-top:10px; margin-bottom:10px">
            <label class="control-label" for="kaskpd">Kepala SKPD</label>
            <input type="text" id="kaskpd" class="span8" data-bind="attr : {'data-init': nm_kaskpd}, value: kaskpd, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Kepala SKPD', initSelection: init_select, query: query_pejabat_skpd }" />
          </div>

          <div class="controls-row" style="margin-top:10px; margin-bottom:10px">
            <label class="control-label" for="bk">Bendahara Pengeluaran</label>
            <input type="text" id="bk" class="span8" readonly="1" data-bind="attr : {'data-init': nm_bk}, value: bk, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Bendahara Pengeluaran', initSelection: init_select, query: query_pejabat_skpd }" />
          </div>

          <div class="controls-row">
            <div class="controls pull-left">
              <label class="control-label" for="penerima">Pihak Ketiga</label>
              <input type="text" id="penerima" class="span4" readonly="1" data-bind="value: penerima" />
            </div>
            <div class="controls pull-left span3">
              <label class="control-label" for="kontrak">Nomor Kontrak</label>
              <input type="text" id="kontrak" name="kontrak" class="span3" readonly="1" data-bind="value: kontrak" />
            </div>
          </div>
        </div>

        <div class="control-row pull-right">
          <div class="control-group pull-left" data-bind="validationElement: keperluan" style="width:100px" >
            <label class="control-label" >Keperluan</label>
            <label class="radio">
              <input type="radio" disabled data-bind="checked: keperluan" value="UP" />UP
            </label>
            <label class="radio">
              <input type="radio" disabled data-bind="checked: keperluan" value="GU" />GU
            </label>
			<label class="radio">
              <input type="radio" disabled data-bind="checked: keperluan" value="GU NIHIL" />GU Nihil
            </label>
			<label class="radio">
              <input type="radio" disabled data-bind="checked: keperluan" value="LS" />LS
            </label>
          </div>
          <div class="control-group pull-right" data-bind="validationElement: jenis_beban" style="width:180px" >
            <label class="control-label" >Beban</label>
            <label class="radio">
              <input type="radio" disabled data-bind="checked: jenis_beban" value="BTL" />Beban Tidak Langsung
            </label>
            <label class="radio">
              <input type="radio" disabled data-bind="checked: jenis_beban" value="BL"/>Beban Langsung
            </label>
          </div>
        </div>
      </div>

      <div class="controls-row">
        <div class="controls pull-left">
          <label class="control-label" for="bank">Nama Bank</label>
          <input type="text" id="bank" class="span4" readonly="1" data-bind="value: bank" >
        </div>
        <div class="controls span3 pull-left">
          <label class="control-label" for="norek">Rekening Bank</label>
          <input type="text" id="norek" class="span3" readonly="1" data-bind="value: norek" >
        </div>
        <div class="controls span4 pull-left">
          <label class="control-label" for="npwp">NPWP</label>
          <input type="text" id="npwp" class="span4" readonly="1" data-bind="value: npwp" />
        </div>
      </div>

      <div class="controls-row">
        <div class="controls pull-left">
          <label class="control-label" for="no_dpa">Nomor DPA</label>
          <input type="text" id="no_dpa" class="span4" readonly="1" data-bind="value: no_dpa" />
        </div>
        <div class="controls span2 pull-left">
          <label class="control-label" for="tgl_dpa">Tanggal DPA SKPD</label>
          <input type="text" id="tgl_dpa" class="span2" readonly="1" data-bind="value: tgl_dpa" />
        </div>
        <div class="controls span3 pull-left">
          <label class="control-label" for="pagu_dpa">Pagu DPA</label>
          <input type="text" id="pagu_dpa" class="span3 currency" readonly="1" data-bind="numeralvalue: pagu_dpa" />
        </div>
      </div>
    </div>

    <div class="tab-pane" id="rspd">
      <div class="controls-row pull-left" >
        <div class="controls-row">
          <label class="control-label" for="no_spp">Nomor SPP</label>
          <input type="text" id="no_spp" class="span2" readonly="1" data-bind="value: no_spp" />
        </div>
        <div class="controls-row">
          <label class="control-label" for="tgl_spp">Tanggal SPP</label>
          <input type="text" id="tgl_spp" class="span2" readonly="1" data-bind="value: tgl_spp" />
        </div>
        <div class="controls-row">
          <label class="control-label" for="nominal_spp">Nominal SPP</label>
          <input type="text" id="nominal_spp" class="span2 currency" readonly="1" data-bind="numeralvalue: nominal_spp" />
        </div>
      </div>
      <div class="controls-row pull-right">
        <table id="grd_spd"></table>
        <div id="pgr_spd"></div>
        <div class="controls-row pull-right" style="margin-top:5px;">
          <label style="float:left; margin-top:10px; margin-right:5px;" for="total_spd_sisa">Sisa SPD Keseluruhan</label>
          <input type="text" id="total_spd_sisa" class="span3 currency" readonly="1" data-bind="numeralvalue: total_spd" />
        </div>
      </div>
    </div>

    <div class="tab-pane" id="rkegiatan">
      <table id="grd_keg"></table>
      <div id="pgr_keg"></div>
    </div>

    <div class="tab-pane" id="rrekening">
      <table id="grd_rek"></table>
      <div id="pgr_rek"></div>
      <div class="controls-row pull-right" style="margin-top:5px;">
        <label style="float:left; margin-top:10px; margin-right:5px;" for="total_spp">Total Rekening</label>
        <input type="text" id="total_spp" class="span3 currency" readonly="1" data-bind="numeralvalue: total_rek" />
      </div>
    </div>



    <div class="tab-pane" id="rpajak">
      <table id="grd_pjk"></table>
      <div id="pgr_pjk"></div>
      <div class="controls-row pull-right" style="margin-top:5px;">
        <label style="float:left; margin-top:10px; margin-right:5px;" for="total_pjk">Total Pajak</label>
        <input type="text" id="total_pjk" class="span3 currency" readonly="1" data-bind="numeralvalue: total_pjk" />
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-right" data-bind="validationElement: total">
      <label style="float:left; margin-top:10px; margin-right:5px;" for="total">Total</label>
      <input type="text" id="total" class="span3 currency" readonly="1" data-bind="numeralvalue: total" />
    </div>
  </div>

  <div class="bottom-bar">
    <input type="button" id="prev" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
    <input type="button" id="next" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
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
      <button type="button" class="btn btn-primary" id="print" data-bind="enable: canPrint, click: print" >Cetak</button>
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
$(document).ready(function(){
  inisialisasi();

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#grd_spd").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', '', 'Nomor SPD', 'Tanggal SPD', 'Nominal', 'Sisa s/d Sekarang', 'Sisa Keseluruhan'],
    colModel:[
      {name:'idr', hidden:true},
      {name:'idspd', hidden:true},
      {name:'no', width:200, sortable:false},
      {name:'tgl',width:100, sortable:false, formatter:'date'},
      {name:'nom',width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'sisa',width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'sisa_all',width:150, sortable:false, formatter:'currency', align:'right'}
    ],
    pager: '#pgr_spd',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    width:'780',
    height:'230',
    loadComplete: function(){
      var idarr = $(this).jqGrid('getDataIDs');
      GetSisaSPD(idarr);
      TotalSPD();
      App.getSisaSKPD();
    },
  });
  $("#grd_spd").jqGrid('bindKeys');
  $("#grd_spd").jqGrid('navGrid','#pgr_spd',{
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:true,
  });

  $("#grd_keg").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Kode Kegiatan', 'Nama Kegiatan', 'Nominal', 'Sisa', 'Kontra Pos', 'Sisa UP', 'Pergeseran', ''],
    colModel:[
      {name:'idkeg', hidden:true},
      {name:'kdkeg', sortable:false, width:150},
      {name:'nmkeg', sortable:false, width:250},
      {name:'nom', sortable:false, width:150, formatter:'currency', align:'right'},
      {name:'sisa', sortable:false, width:150, formatter:'currency', align:'right'},
      {name:'cp', sortable:false, width:150, formatter:'currency', align:'right'},
      {name:'ssu', sortable:false, width:150, formatter:'currency', align:'right'},
      {name:'mp', sortable:false, width:150, formatter:'currency', align:'right'},
      {name:'batas', sortable:false, hidden:true}
    ],
    pager: '#pgr_keg',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    width:'935',
    height:'230',
    loadComplete: function(){
      var idarr = $(this).jqGrid('getDataIDs');
      GetSisaKegiatan(idarr);
      TotalRekening();
    },
  });
  $("#grd_keg").jqGrid('bindKeys');
  $("#grd_keg").jqGrid('navGrid','#pgr_keg',{
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:true,
  });

  $("#grd_rek").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', '', '', 'Kode Kegiatan', 'Kode Rekening', 'Nama Rekening', 'Nominal', 'Sisa', ''],
    colModel:[
      {name:'idr',hidden:true},
      {name:'idrek',hidden:true},
      {name:'idkeg',hidden:true},
      {name:'kdkeg',width:150},
      {name:'kdrek',width:100},
      {name:'nmrek',width:300},
      {name:'nom', width:150, formatter:'currency', align:'right'},
      {name:'sisa',width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'batas', hidden:true}
    ],
    pager: '#pgr_rek',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    width:'935',
    height:'230',
    loadComplete: function(){
      var idarr = $(this).jqGrid('getDataIDs');
      GetSisaRekening(idarr);
      TotalRekening();
    },
  });
  $("#grd_rek").jqGrid('bindKeys');
  $("#grd_rek").jqGrid('navGrid','#pgr_rek',{
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:true,
  });

  /*$("#grd_pfk").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Kode Rekening','Nama Rekening','Nominal'],
    colModel:[
        {name:'idrek',hidden:true},
        {name:'kdrek',width:100},
        {name:'nmrek',width:400},
        {name:'nom', width:150, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
    ],
    pager: '#pgr_pfk',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'230',
    loadComplete: function(){
      TotalPotongan();
    },
  });
  $("#grd_pfk").jqGrid('navGrid','#pgr_pfk',{
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:true,
  });*/

  $("#grd_pjk").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['','','Nama Pajak','Kode Rekening','Nama Rekening','Persen','Nominal','Informasi'],
    colModel:[
        {name:'idp',hidden:true},
        {name:'idrek',hidden:true},
        {name:'kdp',width:150},
        {name:'kdrek',width:100},
        {name:'nmrek',width:250},
        {name:'persen', width:100, formatter:'currency', align:'right'},
        {name:'nom', width:150, formatter:'currency', align:'right'},
        {name:'info',width:70, edittype:'checkbox', formatter:'checkbox', editoptions:{value:"1:0"}, formatoptions:{disabled:true}, align:'center'},
    ],
    pager: '#pgr_pjk',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'230',
    loadComplete: function(){
      TotalPajak();
    },
  });
  $("#grd_pjk").jqGrid('navGrid','#pgr_pjk',{
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:true,
  });

});

  function TotalSPD(){
    var total = $('#grd_spd').jqGrid('getCol', 'sisa_all', '', 'sum');
    App.total_spd(total);
  }

  function TotalRekening(){
    var total = i = idkeg = 0,
      totalkeg = [],
      datarek = $('#grd_rek').jqGrid('getRowData');
    for (i = 0; i < datarek.length; i++){
      idkeg = datarek[i].idkeg + '_';
      isNaN( totalkeg[idkeg] ) ? totalkeg[idkeg] = parseFloat(datarek[i].nom) : totalkeg[idkeg] += parseFloat(datarek[i].nom);
      total += parseFloat(datarek[i].nom);
    }
    App.total_rek(total);

    for (var x in totalkeg){
      $('#grd_keg').jqGrid('setCell', parseInt(x), 'nom', totalkeg[x]);
      HitungSisaKegiatan(parseInt(x))
    }
  }

  /*function TotalPotongan(){
    var total = $('#grd_pfk').jqGrid('getCol', 'nom', '', 'sum');
    App.total_pfk(total);
  }*/

  function TotalPajak(){
    var total = i = 0,
        data = $('#grd_pjk').jqGrid('getRowData');
    for (i = 0; i <= data.length - 1; i++){
      if (data[i].info == "0") total += parseFloat(data[i].nom);
    }
    App.total_pjk(total);
  }

  function HitungSisaKegiatan(id){
    var rs = $('#grd_keg').jqGrid('getRowData', id),
        sisa = rs.batas - rs.nom,
        style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
    $('#grd_keg').jqGrid('setRowData', id, {sisa: sisa}, style);
  }

  function HitungSisaRekening(id){
    var rs = $('#grd_rek').jqGrid('getRowData', id),
        sisa = rs.batas - rs.nom,
        style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
    $('#grd_rek').jqGrid('setRowData', id, {sisa: sisa}, style);
  }

  function GetSisaSPD(idarr){
    var len = idarr.length,
        rs = data = {};

    for (var i = 0; i < len; i++){
      rs = $('#grd_spd').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id_spp(),
        idr: idarr[i],
        id_skpd: App.id_skpd(),
        tanggal: App.tgl(),
        beban: App.beban(),
        arrspd: rs.idspd
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+'/spp/sisa_spd',
        data: data,
        success: function(res) {
          $('#grd_spd').jqGrid('setRowData', res.idr, {sisa:res.sisa, sisa_all:res.sisa_all});
          TotalSPD();
        },
      });
    }
  }

  function GetSisaKegiatan(idarr){
    var len = idarr.length,
        arrspd = $('#grd_spd').jqGrid('getCol', 'idspd'),
        rs = data = {};

    for (var i = 0; i < len; i++){
      rs = $('#grd_keg').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id_spp(),
        idr: idarr[i],
        id_skpd: App.id_skpd(),
        tanggal: App.tgl(),
        beban: App.beban(),
        id_kegiatan: rs.idkeg,
        arrspd: arrspd,
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+'/spp/sisa_kegiatan',
        data: data,
        success: function(res) {
          $('#grd_keg').jqGrid('setRowData', res.idr, {batas:res.sisa, cp:res.cp, ssu:res.ssu, mp:res.mp});
          HitungSisaKegiatan(res.idr);
        },
      });
    }
  }

  function GetSisaRekening(idarr){
    var len = idarr.length,
        arrspd = $('#grd_spd').jqGrid('getCol', 'idspd'),
        rs = data = {};

    for (var i = 0; i < len; i++){
      rs = $('#grd_rek').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id_spp(),
        idr: idarr[i],
        id_skpd: App.id_skpd(),
        tanggal: App.tgl(),
        beban: App.beban(),
        id_kegiatan: rs.idkeg,
        id_rekening: rs.idrek,
        arrspd: arrspd,
		id_spj: App.spj(),
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+'spp/sisa_rekening',
        data: data,
        success: function(res) {
			if(App.keperluan() =='LS')
				$('#grd_rek').jqGrid('setRowData', res.idr, {batas:res.batas,sisa:res.sisa});
			else if(App.keperluan() =='GU' || App.keperluan() =='GU NIHIL'){
				$('#grd_rek').jqGrid('setRowData', res.idr, {batas:(res.spj-res.sisa_gu)});
			}
          HitungSisaRekening(res.idr);
        },
      });
    }
  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelSPM = function (){
    var self = this;
    self.modul = 'SPM';
    self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_AKTIVITAS']) ? $data['ID_AKTIVITAS'] : 0 ?>');
    self.id_spp = ko.observable(<?php echo isset($data['ID_SPP']) ? $data['ID_SPP'] : '' ?>)
      .extend({
        required: {params: true, message: 'SPP belum dipilih'}
      });
    self.no_spp = ko.observable(<?php echo isset($data['NO_SPP']) ? json_encode($data['NO_SPP']) : '' ?>);
	self.spj = ko.observable(<?php echo isset($data['ID_SPJ']) ? $data['ID_SPJ'] : '' ?>);
    self.tgl_spp = ko.observable('<?php echo isset($data['TANGGAL_SPP']) ? format_date($data['TANGGAL_SPP']) : '' ?>');
    self.nominal_spp = ko.observable('<?php echo isset($data['NOMINAL_SPP']) ? $data['NOMINAL_SPP'] : '' ?>');
    self.no = ko.observable(<?php echo isset($data['NOMOR']) ? json_encode($data['NOMOR']) : '' ?>)
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'},
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
    self.beban = ko.observable('<?php echo isset($data['BEBAN']) ? $data['BEBAN'] : '' ?>');
    self.jenis_beban = ko.observable('<?php echo isset($data['JENIS_BEBAN']) ? $data['JENIS_BEBAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Beban belum dipilih'}
      });
    self.keperluan = ko.observable('<?php echo isset($data['KEPERLUAN']) ? $data['KEPERLUAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Keperluan belum dipilih'}
      });
    self.penerima = ko.observable(<?php echo isset($data['NAMA_PENERIMA']) ? json_encode($data['NAMA_PENERIMA']) : '' ?>);
    self.bank = ko.observable(<?php echo isset($data['NAMA_BANK']) ? json_encode($data['NAMA_BANK']) : '' ?>);
    self.kontrak = ko.observable(<?php echo isset($data['NO_KONTRAK']) ? json_encode($data['NO_KONTRAK']) : '' ?>);
    self.norek = ko.observable(<?php echo isset($data['NO_REKENING_BANK']) ? json_encode($data['NO_REKENING_BANK']) : '' ?>);
    self.npwp = ko.observable(<?php echo isset($data['NPWP']) ? json_encode($data['NPWP']) : '' ?>);
    self.no_dpa = ko.observable(<?php echo isset($data['NO_DPA']) ? json_encode($data['NO_DPA']) : '' ?>);
    self.tgl_dpa = ko.observable('<?php echo isset($data['TANGGAL_DPA']) ? format_date($data['TANGGAL_DPA']): date('d/m/Y') ?>');
    self.pagu_dpa = ko.observable(<?php echo isset($data['PAGU_DPA']) ? $data['PAGU_DPA'] : 0 ?>);
    self.bk = ko.observable(<?php echo isset($data['ID_BK']) ? $data['ID_BK']: '' ?>);
    self.nm_bk = ko.observable(<?php echo isset($data['BK_NAMA']) ? json_encode($data['BK_NAMA']) : '' ?>);
    self.kaskpd = ko.observable(<?php echo isset($data['ID_PA']) ? $data['ID_PA']: '' ?>);
    self.nm_kaskpd = ko.observable(<?php echo isset($data['PA_NAMA']) ? json_encode($data['PA_NAMA']) : '' ?>);
    self.batas_pagu = ko.observable(0);
    self.batas_gu = ko.observable(0);
    self.label_sisa_pagu = ko.observable('Sisa Pagu');
    self.total_spd = ko.observable(0);
    self.total_rek = ko.observable(0);
    //self.total_pfk = ko.observable(0);
    self.total_pjk = ko.observable(0);
    self.total = ko.computed(function(){
      return self.keperluan() === 'UP' ? self.nominal_spp() : self.total_rek() /*- self.total_pfk()*/ - self.total_pjk();
    });

    self.id_skpd.subscribe(function(){
      self.updatePejabat();
      self.getSisaSKPD();
    });

    self.tgl.subscribe(function(){
      self.getSisaSKPD();
    });

    self.beban.subscribe(function(new_beban){
      var $grdrek = $('#grd_rek');
      new_beban === 'BL' ? $grdrek.jqGrid('showCol', 'kdkeg') : $grdrek.jqGrid('hideCol', 'kdkeg');
    });

    self.sisa_pagu = ko.computed(function(){
      return self.batas_pagu() - self.total_rek();
    });

    self.sisa_pagu.extend({
//      min: {param: 0, message: 'Sisa Pagu tidak boleh negatif', onlyIf: function(){return (self.keperluan() != 'UP') } }
    });

    self.sisa_gu = ko.computed(function(){
      return self.keperluan === 'GU' ? self.batas_gu() - self.total_rek() : 0;
    });

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
    });

    self.isEdit = ko.computed(function(){
      return self.mode() === 'edit';
    });

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2;
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);
  }

  var App = new ModelSPM();

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
    var /*grdpfk = $('#grd_pfk'),*/ grdpjk = $('#grd_pjk'), errmsg = [];
    // cek jika ada baris di grid belum disimpan
//    checkGridRow(grdpfk, 'idr');
    checkGridRow(grdpjk, 'idr');
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

  App.init_grid = function(isSPP){
    var grd_spd = $('#grd_spd'),
        grd_kegiatan = $('#grd_keg'),
        grd_rekening = $('#grd_rek'),
        //grd_pfk = $('#grd_pfk'),
        grd_pjk = $('#grd_pjk');

    if (App.id() > 0 || App.id_spp() > 0){
      grd_spd.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/spd/' + App.id_spp() + '/1', 'datatype': 'json'});
      grd_spd.trigger('reloadGrid');

      if (App.beban() === 'BL'){
        grd_kegiatan.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/kegiatan/' + App.id_spp() + '/1', 'datatype': 'json'});
        grd_kegiatan.trigger('reloadGrid');
      }
      grd_rekening.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/rekening/' + App.id_spp() + '/1', 'datatype': 'json'});
      grd_rekening.trigger('reloadGrid');
      //grd_pfk.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/potongan/' + App.id_spp(), 'datatype': 'json'});
      //grd_pfk.trigger('reloadGrid');
      grd_pjk.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/pajak/' + App.id_spp(), 'datatype': 'json'});
      grd_pjk.trigger('reloadGrid');
    }
    else {
      grd_spd.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_kegiatan.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_rekening.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      //grd_pfk.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_pjk.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }

  }

  App.pilih_spp = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, tanggal:App.tgl(), id_skpd:App.id_skpd()};
    Dialog.pilihSPP(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      $.ajax({
        url: '<?php echo base_url().$modul."/spp/"?>' + rs.id,
        type: 'post',
        dataType: 'json',
        data: {},
        success: function(res, xhr){
          App.id_spp(res.id_spp);
          App.spj(res.id_spj);
          App.no_spp(res.no_spp);
          App.tgl_spp(res.tgl_spp);
          App.deskripsi(res.deskripsi);
          App.nominal_spp(res.nominal_spp);
          App.id_skpd(res.id_skpd);
          App.kd_skpd(res.kd_skpd);
          App.nm_skpd(res.nm_skpd);
          App.beban(res.beban);
          App.jenis_beban(res.jenis_beban);
          App.keperluan(res.keperluan);
          App.penerima(res.penerima);
          App.bank(res.bank);
          App.kontrak(res.kontrak);
          App.norek(res.norek);
          App.npwp(res.npwp);
          App.no_dpa(res.no_dpa);
          App.tgl_dpa(res.tgl_dpa);
          App.pagu_dpa(res.pagu_dpa);
          App.bk(res.bk);
          App.nm_bk(res.nm_bk);
          App.kaskpd(res.kaskpd);
          App.nm_kaskpd(res.nm_kaskpd);
          App.init_grid(true);
          App.getSisaSKPD();
        }
      });
    });
  }

  App.getSisaSKPD = function(){
    var arrspd = $('#grd_spd').jqGrid('getCol', 'idspd');

    data = {
      id: App.id_spp(),
      id_skpd: App.id_skpd(),
      tanggal: App.tgl(),
      beban: App.beban(),
      keperluan: App.keperluan(),
      arrspd: arrspd,
    };

    $.ajax({
      type: "post",
      dataType: "json",
      url: root+'/spp/sisa_skpd',
      data: data,
      success: function(res) {
        App.label_sisa_pagu(res.lbl_sisa_pagu);
        App.batas_pagu(parseFloat(res.sisa_pagu));
        App.batas_gu(parseFloat(res.sisa_gu));
      },
    });
  }

  App.updatePejabat = function(){
    App.default_pejabat_skpd(App.id_skpd, App.kaskpd, App.nm_kaskpd, 'KASKPD');
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
?>
    if (App.beban()) App.beban.valueHasMutated();
    App.init_grid();
  }, 500)
</script>
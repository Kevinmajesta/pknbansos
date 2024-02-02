<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>rka221/proses">
  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: id_skpd" required >
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd" />
      <div class="controls span8 input-append">
        <input type="text" class="span9" id="nama_skpd" readonly="1" data-bind="value: nm_skpd" />
        <span class="add-on" data-bind="visible: !isEdit() && !isSKPD, click: pilih_skpd" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: id_keg" >
      <label class="control-label" for="kd_keg">Kegiatan</label>
      <input type="text" class="span2" id="kd_keg" readonly="1" data-bind="value: kd_keg" />
      <div class="controls span8 input-append">
        <input type="text" class="span9" id="nm_keg" readonly="1" data-bind="value: nm_keg" />
        <span class="add-on" data-bind="visible: !isEdit(), click: pilih_kegiatan" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group" data-bind="validationElement: sasaran" >
      <label class="control-label" for="sasaran">Kelompok Sasaran Kegiatan</label>
      <textarea id="sasaran" rows="2" class="span10" data-bind="value: sasaran" ></textarea>
    </div>
  </div>

  <ul class="nav nav-tabs" id="tabs" style="margin-bottom:10px">
    <li class="active"><a href="#indikator">Indikator Kinerja</a></li>
    <li><a href="#rinci">Rincian Anggaran</a></li>
    <li class="control-group" data-bind="validationElement: tgl"><a class="control-label" href="#pembahasan">Pembahasan Anggaran</a></li>
    <li><a href="#lokasi">Lokasi</a></li>
    <li><a href="#sumberdana">Sumber Dana</a></li>
  </ul>

  <div class="tab-content" style="height:460px">
    <div class="tab-pane active" id="indikator">
      <table id="grd_indi"></table>
      <div id="pgr_indi"></div>
    </div>
    <div class="tab-pane" id="rinci">
      <table id="grd_rinci"></table>
      <div id="pgr_rinci"></div>
    </div>
    <div class="tab-pane" id="pembahasan">
      <div class="controls controls-row">
        <div class="controls pull-left">
          <label class="control-label" for="ket" >Keterangan</label>
          <textarea rows="2" class="span10" id="ket" data-bind="value: ket" ></textarea>
        </div>
        <div class="control-group pull-right" data-bind="validationElement: tgl">
          <label class="control-label" for="tgl">Tanggal</label>
          <input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" />
        </div>
      </div>
      <div class="controls">
        <label>Catatan Pembahasan</label>
        <table id="grd_bahas"></table>
        <div id="pgr_bahas"></div>
      </div>
    </div>
    <div class="tab-pane" id="lokasi">
      <table id="grd_lokasi"></table>
      <div id="pgr_lokasi"></div>
    </div>
    <div class="tab-pane" id="sumberdana">
      <table id="grd_sumberdana"></table>
      <div id="pgr_sumberdana"></div>
    </div>
  </div>

  <div class="controls-row">
    <div class="controls pull-left">
      <label class="control-label" for="pejabat">Pejabat SKPD</label>
      <input type="text" id="pejabat" class="span5" data-bind="attr : {'data-init': nm_pejabat}, value: ps, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat SKPD', initSelection: init_select, query: query_pejabat_skpd }" />
    </div>
    <div class="controls-row pull-right">
      <div class="controls pull-left">
        <label class="control-label" for="tahun_lalu">Jumlah Tahun Sebelumnya</label>
        <input type="text" id="tahun_lalu" class="currency" style="width:150px" data-bind="numeralvalue: tahun_lalu" />
      </div>
      <div class="controls pull-left" style="margin-left:20px;" >
        <label class="control-label" for="tahun_ini">Jumlah Tahun Ini</label>
        <input type="text" id="tahun_ini" class="currency" style="width:150px;" readonly="1" data-bind="numeralvalue: tahun_ini" />
      </div>
      <div class="controls pull-right" style="margin-left:20px;" >
        <label class="control-label" for="tahun_depan">Jumlah Tahun Berikutnya</label>
        <input type="text" id="tahun_depan" class="currency" style="width:150px;" data-bind="numeralvalue: tahun_depan" />
      </div>
    </div>
  </div>

  <div class="controls-row pull-right">
    <input type="button" id="prev" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
    <input type="button" id="next" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
    <input type="button" id="save" value="Simpan" class="btn btn-primary" data-bind="enable: canSave, click: save" />
    <div class="btn-group dropup">
      <button type="button" class="btn btn-primary" id="print" data-bind="enable: canPrint, click: print" >Cetak</button>
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint" >
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#">PDF</a></li>
        <li><a href="#">XLS</a></li>
      </ul>
    </div>
    <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
  </div>
</form>

<script>
<?php
  $cols[] = '';
  $cols[] = '';
  $cols[] = '';
  $cols[] = '';
  $cols[] = '';
  $cols[] = '';
  if ($this->data_model->perubahan)
  {
    $cols[] = 'Kode Rekening';
    $cols[] = 'Uraian';
    $cols[] = '';
    $cols[] = 'Volume Murni';
    $cols[] = 'Satuan';
    $cols[] = 'Tarif Murni';
    $cols[] = 'Jumlah Murni';
    $cols[] = 'Volume PAK';
    $cols[] = 'Satuan';
    $cols[] = 'Tarif PAK';
    $cols[] = 'Jumlah PAK';
    $cols[] = 'Bertambah/Berkurang';
  }
  else
  {
    $cols[] = 'Kode Rekening';
    $cols[] = 'Uraian';
    $cols[] = 'Volume';
    $cols[] = 'Satuan';
    $cols[] = 'Tarif';
    $cols[] = 'Jumlah';
  }
  $cols[] = 'Realisasi';
  $cols[] = 'Jumlah Tahun Berikutnya';
  $cols[] = 'Keterangan';
  $cols[] = '';

  $models[] = "{name:'idra', hidden:true, key:true},";
  $models[] = "{name:'iddr', hidden:true},";
  $models[] = "{name:'idrek', hidden:true},";
  $models[] = "{name:'idp', hidden:true},";
  $models[] = "{name:'lvl', hidden:true},";
  $models[] = "{name:'child', hidden:true},";
  if ($this->data_model->perubahan)
  {
    $models[] = "{name:'kdrek', width:80, editable:false, sortable:false},";
    $models[] = "{name:'uraian', width:155, sortable:false, editable:true, editrules:{}},";
    $models[] = "{name:'awal', hidden:true},";
    $models[] = "{name:'vol_a', width:60, sortable:false, editable:false, editrules:{}, formatter:'number', formatoptions: { defaultValue: ''}, align:'right'},";
    $models[] = "{name:'sat_a', width:50, sortable:false, editable:false, editrules:{}},";
    $models[] = "{name:'trf_a', width:100, sortable:false, editable:false, editruules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},";
    $models[] = "{name:'jml_a', width:100, sortable:false, formatter:'currency', align:'right'},";
    $models[] = "{name:'vol', width:60, sortable:false, editable:true, editrules:{}, formatter:'number', formatoptions: { defaultValue: ''}, align:'right'},";
    $models[] = "{name:'sat', width:50, sortable:false, editable:true, editrules:{}},";
    $models[] = "{name:'trf', width:100, sortable:false, editable:true, editruules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},";
    $models[] = "{name:'jml', width:100, sortable:false, formatter:'currency', align:'right'},";
    $models[] = "{name:'sub', width:100, sortable:false, formatter:'currency', align:'right'},";
  }
  else
  {
    $models[] = "{name:'kdrek', width:150, editable:false, sortable:false},";
    $models[] = "{name:'uraian', width:305, sortable:false, editable:true, editrules:{}},";
    $models[] = "{name:'vol', width:60, sortable:false, editable:true, editrules:{}, formatter:'number', formatoptions: { defaultValue: ''}, align:'right'},";
    $models[] = "{name:'sat', width:60, sortable:false, editable:true, editrules:{}},";
    $models[] = "{name:'trf', width:150, sortable:false, editable:true, editrules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},";
    $models[] = "{name:'jml', width:150, sortable:false, formatter:'currency', align:'right'},";
  }
  $models[] = "{name:'realisasi', width:150, sortable:false, formatter:'currency', align:'right',hidden:true},";
  $models[] = "{name:'jml_next', width:150, sortable:false, editable:true, editrules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right',hidden:true},";
  $models[] = "{name:'ket', width:200, sortable:false, editable:true, editrules:{}, formatter:'',hidden:true},";
  $models[] = "{name:'jml_old', hidden:true},";

  // ------ colNames grid Indikator
  $cols_indi[] = '';
  $cols_indi[] = 'Tipe';
  $cols_indi[] = 'Jenis Indikator';
  if ($this->data_model->perubahan)
  {
    $cols_indi[] = 'Tolok Ukur Murni';
    $cols_indi[] = 'Target Murni';
    $cols_indi[] = 'Jumlah Murni';
    $cols_indi[] = 'Tolok Ukur';
    $cols_indi[] = 'Target';
    $cols_indi[] = 'Jumlah';
  }
  else
  {
    $cols_indi[] = 'Tolok Ukur';
    $cols_indi[] = 'Target';
    $cols_indi[] = 'Jumlah';
  }

  // ------ colModels grid Indikator
  $models_indi[] = "{name:'id_indi', hidden:true},";
  $models_indi[] = "{name:'tipe', hidden:true},";
  $models_indi[] = "{name:'jenis', width:150, sortable:false},";
  if ($this->data_model->perubahan)
  {
    $models_indi[] = "{name:'tolok_a', width:350, sortable:false, editable:false},";
    $models_indi[] = "{name:'target_a', width:150, sortable:false, editable:false},";
    $models_indi[] = "{name:'jml_a', width:150, sortable:false, formatter:'currency', editable:false, align:'right'},";
    $models_indi[] = "{name:'tolok', width:350, sortable:false, editable:true, editrules:{}},";
    $models_indi[] = "{name:'target', width:150, sortable:false, editable:true, editrules:{}},";
    $models_indi[] = "{name:'jml', width:150, sortable:false, formatter:'currency', editable:true, editrules:{}, align:'right'},";
  }
  else
  {
    $models_indi[] = "{name:'tolok', width:350, sortable:false, editable:true, editrules:{}},";
    $models_indi[] = "{name:'target', width:150, sortable:false, editable:true, editrules:{}},";
    $models_indi[] = "{name:'jml', width:150, sortable:false, formatter:'currency', editable:true, editrules:{}, align:'right'},";
  }
?>
var last, last_bahas;
var i = 0;
var purge_indi = new Array();
var purge_pembahasan = new Array();
var purge_sumberdana = new Array();
var purge_lokasi = new Array();
var indikator =[
        {tipe:"0",jenis:"Capaian Program"},
        {tipe:"1",jenis:"Masukan"},
        {tipe:"2",jenis:"Keluaran"},
        {tipe:"3",jenis:"Hasil"}
      ];

$(document).ready(function() {
  var $grd = $('#grd_rinci');

  $('.currency').formatCurrency(fmtCurrency);
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();

  $('#tabs a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#grd_indi").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:[<?php foreach ($cols_indi as $col) echo "'$col', "; ?>],
    colModel:[
        <?php foreach ($models_indi as $model) echo $model."\n"; ?>
       ],
    pager:'#pgr_indi',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:'935',
    height:'400',
    recordtext:'{2} baris',
    loadComplete: clear_grid,
    onSelectRow: function(id){
          if(id && id!==last){
          $(this).restoreRow(last);
          last=id;
        }
      },
    ondblClickRow: edit_row,
  });

  $("#grd_indi").jqGrid('bindKeys', {"onEnter": edit_row});
  $("#grd_indi").jqGrid('navGrid', '#pgr_indi', {
    add:true,
    addtext: 'Sisip',
    addfunc:add_indi,
    edit:false,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:false,
    refreshtext:'Refresh',
    },{},{},{},{}
  );

  //$("#grd_rinci").jqGrid({
  grd = $("#grd_rinci").jqGrid({
    mtype: 'POST',
    colNames:[<?php foreach ($cols as $col) echo "'$col', "; ?>],
    colModel:[
        <?php foreach ($models as $model) echo $model."\n"; ?>
       ],
    pager:'#pgr_rinci',
    rowNum:1000000,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'400',
    recordtext:'{2} baris',
  });

  gridRKADPA.init({
      grd: $('#grd_rinci'),
      tipe: '<?php echo $this->modul_name ?>',
      id_skpd: App.id_skpd(),
      akses: <?php echo isset($akses) ? $akses : 0 ?>,
      perubahan: <?php echo $this->data_model->perubahan ? 'true' : 'false'; ?>,
      total: App.tahun_ini
  });
   

  /* $("#grd_rinci").jqGrid('bindKeys', {"onEnter": edit_row});
  $("#grd_rinci").jqGrid('navGrid', '#pgr_rinci', {
    add:true,
    addtext: 'Tambah',
    addfunc:add_rekening,
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:false,
    refreshtext:'Refresh',
    },{},{},{},{}
  ); */

  $("#grd_bahas").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['No', 'Pembahasan'],
    colModel:[
        {name:'no', hidden:true},
        {name:'ket', width:700, sortable:false, editable:true, editrules:{}, formatter:''}
      ],
    pager:'#pgr_bahas',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:'935',
    height:'300',
    recordtext:'{2} baris',
    ondblClickRow: edit_row,
  });
  $("#grd_bahas").jqGrid('bindKeys', {"onEnter": edit_row});
  $("#grd_bahas").jqGrid('navGrid', '#pgr_bahas', {
    add:true,
    addtext: 'Tambah',
    addfunc:add_bahasan,
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:false,
    refreshtext:'Refresh',
  },{},{},{},{});

  $("#grd_lokasi").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Lokasi', 'Nominal'],
    colModel:[
      {name:'lok', hidden:true},
      {name:'nama_lokasi', width:650, sortable:false},
      {name:'nom_lokasi', width:150, sortable:false, editable:true, editrules: {float:true, required: true}, formatter:'currency', align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},
    ],
    pager:'#pgr_lokasi',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:'935',
    height:'380',
    recordtext:'{2} baris',
    footerrow:true,
    loadComplete:function(){
        TotalNomLokasi();
        HitungTotal();
      },
    onSelectRow: function(id){
        if(id && id!==last){
          $(this).restoreRow(last);
          last=id;
        }
      },
    ondblClickRow: edit_row,
  });

  $("#grd_lokasi").jqGrid('bindKeys', {"onEnter": edit_row});
  $("#grd_lokasi").jqGrid('navGrid', '#pgr_lokasi', {
    add:true,
    addtext: 'Tambah',
    addfunc:add_lokasi,
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:false,
    refreshtext:'Refresh',
    },{},{},{},{}
  );

  $("#grd_sumberdana").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Sumber Dana', 'Nominal'],
    colModel:[
      {name:'idsd', hidden:true},
      {name:'nama', width:650, sortable:false},
      {name:'nom_sd', width:150, sortable:false, editable:true, editrules: {float:true, required: true}, formatter:'currency', align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},
    ],
    pager:'#pgr_sumberdana',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:'935',
    height:'380',
    recordtext:'{2} baris',
    footerrow:true,
    loadComplete:function(){
        TotalDana();
        HitungTotal();
      },
    onSelectRow: function(id){
        if(id && id!==last){
          $(this).restoreRow(last);
          last=id;
        }
      },
    ondblClickRow: edit_row,
  });

  $("#grd_sumberdana").jqGrid('bindKeys', {"onEnter": edit_row});
  $("#grd_sumberdana").jqGrid('navGrid', '#pgr_sumberdana', {
      add:true,
      addtext: 'Tambah',
      addfunc:add_sumberdana,
      edit:true,
      edittext: 'Ubah',
      editfunc:edit_row,
      del:true,
      deltext: 'Hapus',
      delfunc:del_row,
      search:false,
      refresh:false,
      refreshtext:'Refresh',
    },{},{},{},{}
  );

  function HitungTotal(){
    var total = 0,
        rowdata = $('#grd_rinci').jqGrid('getRowData');
    for (i = 0; i < rowdata.length; i++){
      if (rowdata[i].lvl === '5'){
        total += parseFloat(rowdata[i].jml);
      }
    }
    App.tahun_ini(total);
  };

  function add_indi()
  {
    var src = $("#grd_indi").jqGrid('getGridParam','selrow');
    if(src)
    {
      if(last == null)
      {
        last = src;
      }
      var rind = $("#grd_indi").jqGrid('getRowData', src);
      var data = {tipe:rind.tipe,jenis:rind.jenis};
      $('#grd_indi').jqGrid('restoreRow', last);
      $("#grd_indi").jqGrid('addRowData', "new_"+(i+1), data, 'after', last);
      $('#grd_indi').jqGrid('editRow', "new_"+(i+1), true,  null, null, 'clientArray', null, after_save);
      last = 'new_'+(i+1);
      ubah = true;
      i++;
    }
    else
    {
      alert("Silahkan pilih salah satu baris.");
    }
  }

  function add_rekening(){
    var $list = $(this),
    option = {multi:1, id_skpd:App.id_skpd(), mode:sub_modul},
    i = 0,
    rs = [];

    Dialog.pilihRekening(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        addRowSorted($list, {'id':'idrek', 'sortName':['kdrek']}, {'idrek':rs.idrek, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek});
      }
    });
  };

  function add_lokasi(){
    var $list = $(this),
    option = {multi:1},
    i = 0,
    rs = [];

    Dialog.pilihLokasi(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        addRowSorted($list, {'id':'lok', 'sortName':['lok']}, {'lok':rs.id, 'nama_lokasi':rs.lokasi});
      }
    });
  };

  function add_sumberdana(){
    var $list = $(this),
    option = {multi:1},
    i = 0,
    rs = [];

    Dialog.pilihSumberdana(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        addRowSorted($list, {'id':'idsd', 'sortName':['nama']}, {'idsd':rs.id, 'nama':rs.nama});
      }
    });
  };

  function add_bahasan(){
    /* ambil no urut terbesar + 1*/
    arr = $('#grd_bahas').jqGrid('getDataIDs');
    if (arr.length != 0){
      no = Math.max.apply(Math, arr) + 1;
    }
    else no = 1;

    $(this).jqGrid('saveRow', last_bahas);
    $(this).jqGrid('addRowData', no, {id: no, ket:''}, 'last');
    $(this).jqGrid('editRow', no, true, null, null, 'clientArray');
    last_bahas = no;
  }

  function TotalNomLokasi()
  {
    var total_nom_lokasi = $('#grd_lokasi').jqGrid('getCol', 'nom_lokasi', false, 'sum');
    $('#grd_lokasi').jqGrid('footerData','set',{lokasi:'Total',nom_lokasi:total_nom_lokasi});
    App.total_nom_lokasi(total_nom_lokasi);
  }

  function TotalDana()
  {
    var total_dana = $('#grd_sumberdana').jqGrid('getCol', 'nom_sd', false, 'sum');
    $('#grd_sumberdana').jqGrid('footerData','set',{nama:'Total Sumber Dana',nom_sd:total_dana});
    App.total_dana(total_dana);
  }

  function edit_row(id){
    $(this).jqGrid('saveRow', last);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
    last = id;
  };

  function after_save(){
    $(this).focus();
    TotalNomLokasi();
    TotalDana();
    HitungTotal();
  }

  function del_row(id){
    var rs = {},
        answer = false,
        rs = $(this).jqGrid('getRowData', id);
    switch ($(this).attr('id')){
      case 'grd_bahas' : kode = rs.no; break;
      case 'grd_sumberdana' : kode = rs.nama; break;
      case 'grd_indi' : kode = ''; break;
      case 'grd_lokasi' : kode = rs.nama_lokasi; break;
      default : kode = ''; break;
    }
    answer = confirm('Hapus ' + kode.trim() + ' dari daftar?');

    if(answer == true){
      if ($(this).attr('id') === 'grd_bahas'){
        purge_pembahasan.push(id);
        $(this).jqGrid('delRowData', id);
      }
      else if ($(this).attr('id') === 'grd_sumberdana'){
        purge_sumberdana.push(id);
        $(this).jqGrid('delRowData', id);
      }
      else if ($(this).attr('id') === 'grd_indi'){
        purge_indi.push(id);
        $(this).jqGrid('delRowData', id);
      }
      else if ($(this).attr('id') === 'grd_lokasi'){
        purge_lokasi.push(id);
        $(this).jqGrid('delRowData', id);
      }
      TotalNomLokasi();
      TotalDana();
      HitungTotal();
    }
  };

});

function clear_grid(){
  var $grid = $('#grd_indi'),
      rowdata = $grid.jqGrid('getRowData').length;
  if (rowdata === 0) {
    if (App.id() === 0) {
      for(var i=0;i<indikator.length;i++) { $("#grd_indi").jqGrid('addRowData',(i+1),indikator[i]); }
    }
  }
}


ko.validation.init({
  insertMessages: false,
  decorateElement: true,
  errorElementClass: 'error',
});

var ModelRKA = function (){
  var self = this;
  self.modul = 'RKA';
  self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
  self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
  self.id = ko.observable(<?php echo isset($data['ID_FORM_ANGGARAN'])?$data['ID_FORM_ANGGARAN']:0 ?>);
  self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD'])?$data['ID_SKPD']: 0 ?>)
    .extend({
      required: {params: true, message: 'SKPD belum dipilih'}
    });
  self.kd_skpd = ko.observable('<?php echo isset($data['KODE_SKPD_LKP'])?$data['KODE_SKPD_LKP']:'' ?>');
  self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD'])?$data['NAMA_SKPD']:'' ?>');
  self.id_keg = ko.observable(<?php echo isset($data['ID_KEGIATAN'])?$data['ID_KEGIATAN']: 0 ?>)
    .extend({
      required: {params: true, message: 'Kegiatan belum dipilih'}
    });
  self.kd_keg = ko.observable('<?php echo isset($data['KODE_KEGIATAN_SKPD'])?$data['KODE_KEGIATAN_SKPD']:'' ?>');
  self.nm_keg = ko.observable('<?php echo isset($data['NAMA_KEGIATAN'])?$data['NAMA_KEGIATAN']:'' ?>');
  self.ket = ko.observable('<?php echo isset($data['KETERANGAN'])?$data['KETERANGAN']:'' ?>');
  self.sasaran = ko.observable('<?php echo isset($data['KELOMPOK_SASARAN_KEGIATAN'])?$data['KELOMPOK_SASARAN_KEGIATAN']:'' ?>');
  self.tgl = ko.observable('<?php echo isset($data['TANGGAL_PEMBAHASAN'])? format_date($data['TANGGAL_PEMBAHASAN']): date('d/m/Y'); ?>')
    .extend({
      required: {params: true, message: 'Tanggal tidak boleh kosong'}
    });
  self.ps = ko.observable(<?php echo isset($data['ID_PEJABAT_SKPD'])?$data['ID_PEJABAT_SKPD']:0 ?>);
  self.nm_pejabat = ko.observable('<?php echo isset($data['NAMA_PEJABAT'])?$data['NAMA_PEJABAT']:''?>');
  self.tahun_lalu = ko.observable(<?php echo isset($data['PAGU_TAHUN_LALU'])?$data['PAGU_TAHUN_LALU']:0?>);
  self.tahun_ini = ko.observable(<?php echo isset($data['NOMINAL_ANGGARAN'])?$data['NOMINAL_ANGGARAN']:0?>);
  self.tahun_depan = ko.observable(<?php echo isset($data['PAGU_TAHUN_DEPAN'])?$data['PAGU_TAHUN_DEPAN']:0?>);
  self.total_dana = ko.observable(0);
  self.total_nom_lokasi = ko.observable(0);

  self.id_skpd.subscribe(function(){
    self.updatePejabat();
  });

  self.mode = ko.computed(function(){
    return self.id() > 0 ? 'edit' : 'new';
  });

  self.title = ko.computed(function(){
    return (self.mode() === 'edit' ? 'Edit RKA 2.2.1' : 'Entri RKA 2.2.1');
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

var App = new ModelRKA();

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

  if ($('#grd_lokasi').jqGrid('getDataIDs').length === 0) {
    errmsg.push('Lokasi Belum Diisi.');
  }
  else {
    if (App.tahun_ini() !== App.total_nom_lokasi())
      errmsg.push('Nominal Rincian Anggaran dengan Nominal Lokasi Tidak Sama.');
  }

  if ($('#grd_sumberdana').jqGrid('getDataIDs').length === 0) {
    errmsg.push('Sumber Dana Belum Diisi.');
  }
  else {
    if (App.tahun_ini() !== App.total_dana())
      errmsg.push('Nominal Rincian Anggaran dengan Nominal Sumber Dana Tidak Sama.');
  }

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

App.save = function(){
  if (!App.formValidation()){ return }

  var $frm = $('#frm'),
      data = JSON.parse(ko.toJSON(App));
      data_rinci = $('#grd_rinci').jqGrid('getRowData'),
      rincian = $.grep(data_rinci, function(e){ return isNaN(e.idra) || parseInt(e.idra) > 0 });
      data['rincian'] = JSON.stringify(rincian);
      data['indikator'] = JSON.stringify($('#grd_indi').jqGrid('getRowData'));
      data['lokasi'] = JSON.stringify($('#grd_lokasi').jqGrid('getRowData'));
      data['bahasan'] = JSON.stringify($('#grd_bahas').jqGrid('getRowData'));
      data['sumberdana'] = JSON.stringify($('#grd_sumberdana').jqGrid('getRowData'));
      data['purge_rincian'] = gridRKADPA.get_purge;
      data['purge_pembahasan'] = purge_pembahasan;
      data['purge_sumberdana'] = purge_sumberdana;
      data['purge_indi'] = purge_indi;
      data['purge_lokasi'] = purge_lokasi;

  $.ajax({
    url: $frm.attr('action'),
    type: 'post',
    dataType: 'json',
    data: data,
    success: function(res, xhr){
        if (res.id) App.id(res.id);

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });

        App.init_grid();
      }
  });

}

App.init_grid = function(){
  var grd_rinci = $('#grd_rinci'),
      grd_bahas = $('#grd_bahas'),
      grd_sd = $('#grd_sumberdana'),
      grd_indi = $('#grd_indi'),
      grd_lokasi = $('#grd_lokasi');

  if (App.id() > 0){
    grd_rinci.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/rincian/' + App.id() + '/' + App.id_keg(), 'datatype': 'json'});
    grd_rinci.trigger('reloadGrid');
    grd_bahas.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/bahasan/' + App.id() + '/' + App.id_keg(), 'datatype': 'json'});
    grd_bahas.trigger('reloadGrid');
    grd_sd.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/sumberdana/' + App.id() + '/' + App.id_keg(), 'datatype': 'json'});
    grd_sd.trigger('reloadGrid');
    grd_indi.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/indikator/' + App.id() + '/' + App.id_keg(), 'datatype': 'json'});
    grd_indi.trigger('reloadGrid');
    grd_lokasi.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/lokasi/' + App.id() + '/' + App.id_keg(), 'datatype': 'json'});
    grd_lokasi.trigger('reloadGrid');
  }
  else {
    grd_rinci.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    grd_bahas.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    grd_sd.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    grd_indi.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    grd_lokasi.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
  }
}

App.init_grid_by_fa = function(){
  $.ajax({
    url: root+modul+'/get_id_fa/',
    type: 'POST',
    dataType: 'json',
    data: {id_skpd:App.id_skpd(), id_keg:App.id_keg()},
    success: function(res){
      var id = res.id_fa;
      var grd_rinci = $('#grd_rinci'),
          grd_bahas = $('#grd_bahas'),
          grd_sd = $('#grd_sumberdana'),
          grd_indi = $('#grd_indi'),
          grd_lokasi = $('#grd_lokasi');

      setTimeout(function(){
        grd_rinci.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/rincian/' + id + '/' + App.id_keg(), 'datatype': 'json'});
        grd_rinci.trigger('reloadGrid');
        grd_bahas.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/bahasan/' + id + '/' + App.id_keg(), 'datatype': 'json'});
        grd_bahas.trigger('reloadGrid');
        grd_sd.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/sumberdana/' + id + '/' + App.id_keg(), 'datatype': 'json'});
        grd_sd.trigger('reloadGrid');
        grd_indi.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/indikator/' + id + '/' + App.id_keg(), 'datatype': 'json', 'loadComplete':clear_grid});
        grd_indi.trigger('reloadGrid');
        grd_lokasi.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/lokasi/' + id + '/' + App.id_keg(), 'datatype': 'json'});
        grd_lokasi.trigger('reloadGrid');
      }, 1000);
    }
  });
}

App.pilih_skpd = function(){
  var option = {multi:0};
  Dialog.pilihSKPD(option, function(obj, select){
    var rs = $(obj).jqGrid('getRowData', select[0].id);
    App.id_skpd(rs.id);
    App.kd_skpd(rs.kode);
    App.nm_skpd(rs.nama);

    <?php if ($this->data_model->perubahan) { ?>
    if (App.id_keg() > 0) App.init_grid_by_fa();
    <?php } ?>

  });
}

App.pilih_kegiatan = function(){

  var option = {multi:0, id_skpd:App.id_skpd(), mode:'rka221'};
  Dialog.pilihKegiatan(option, function(obj, select){
    var rs = $(obj).jqGrid('getRowData', select[0].id);
    App.id_keg(rs.id);
    App.kd_keg(rs.kode);
    App.nm_keg(rs.nama);

    <?php if ($this->data_model->perubahan) { ?>
    App.init_grid_by_fa();
    <?php } ?>
  });

}

App.updatePejabat = function(){
  App.default_pejabat_skpd(App.id_skpd, App.ps, App.nm_pejabat, 'KASKPD');
}

App.default_pejabat_skpd = function(skpd, ps, nama_pejabat, kode){
  $.ajax({
    url: "<?php echo base_url();?>pilih/pejabat_skpd",
    type: 'POST',
    dataType: 'json',
    data: {skpd:skpd, kode:kode},
    success: function(res){
        if (res && res.results[0]){
          ps(res.results[0].id);
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
if ($id_skpd !== 0){
?>
    App.id_skpd(<?php echo $id_skpd; ?>);
    App.kd_skpd('<?php echo $kode_skpd; ?>');
    App.nm_skpd('<?php echo $nama_skpd; ?>');
<?php
}
?>
  App.init_grid();
  App.tahun_ini();
}, 2000)

</script>
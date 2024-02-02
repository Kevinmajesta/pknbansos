<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>rka21/proses">
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

  <ul class="nav nav-tabs" id="tabs" style="margin-bottom:10px">
    <li class="active"><a href="#rinci">Rincian Anggaran</a></li>
    <li class="control-group" data-bind="validationElement: tgl"><a class="control-label" href="#pembahasan">Pembahasan Anggaran</a></li>
    <li><a href="#sumberdana">Sumber Dana</a></li>
  </ul>

  <div class="tab-content" style="height:460px">
    <div class="tab-pane active" id="rinci">
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
      <div class="controls pull-left" style="margin-left:20px;" >
        <label class="control-label" for="total">Jumlah Total</label>
        <input type="text" id="total" class="currency" style="width:150px;" readonly="1" data-bind="numeralvalue: total" />
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
  $cols[] = 'Kode Rekening';
  $cols[] = 'Uraian';
  if ($this->data_model->perubahan)
  {
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
    $cols[] = 'Volume';
    $cols[] = 'Satuan';
    $cols[] = 'Tarif';
    $cols[] = 'Jumlah';
  }
  $cols[] = 'Realisasi';
  $cols[] = 'Jumlah Tahun Berikutnya';
  $cols[] = 'Keterangan';
  $cols[] = '';
  $cols[] = '';

  $models[] = "{name:'idra', hidden:true, key:true},";
  $models[] = "{name:'iddr', hidden:true},";
  $models[] = "{name:'idrek', hidden:true},";
  $models[] = "{name:'idp', hidden:true},";
  $models[] = "{name:'lvl', hidden:true},";
  $models[] = "{name:'child', hidden:true},";
  $models[] = "{name:'kdrek', width:80, editable:false, sortable:false},";
  $models[] = "{name:'uraian', width:200, sortable:false, editable:true, editrules:{}},";
  if ($this->data_model->perubahan)
  {
    $models[] = "{name:'awal', hidden:true},";
    $models[] = "{name:'vol_a', width:60, sortable:false, editable:false, editrules:{}, formatter:'number', formatoptions: { defaultValue: ''}, align:'right'},";
    $models[] = "{name:'sat_a', width:60, sortable:false, editable:false, editrules:{}},";
    $models[] = "{name:'trf_a', width:150, sortable:false, editable:false, editruules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},";
    $models[] = "{name:'jml_a', width:150, sortable:false, formatter:'currency', align:'right'},";
    $models[] = "{name:'vol', width:60, sortable:false, editable:true, editrules:{}, formatter:'number', formatoptions: { defaultValue: ''}, align:'right'},";
    $models[] = "{name:'sat', width:60, sortable:false, editable:true, editrules:{}},";
    $models[] = "{name:'trf', width:150, sortable:false, editable:true, editruules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},";
    $models[] = "{name:'jml', width:150, sortable:false, formatter:'currency', align:'right'},";
    $models[] = "{name:'sub', width:150, sortable:false, formatter:'currency', editable:true, align:'right'},";
  }
  else
  {
    $models[] = "{name:'vol', width:60, sortable:false, editable:true, editrules:{}, formatter:'number', formatoptions: { defaultValue: ''}, align:'right'},";
    $models[] = "{name:'sat', width:60, sortable:false, editable:true, editrules:{}},";
    $models[] = "{name:'trf', width:150, sortable:false, editable:true, editrules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},";
    $models[] = "{name:'jml', width:150, sortable:false, formatter:'currency', align:'right'},";
  }
  $models[] = "{name:'realisasi', width:150, sortable:false, formatter:'currency', align:'right'},";
  $models[] = "{name:'jml_next', width:150, sortable:false, editable:true, editrules:{}, formatter:'currency', formatoptions: { defaultValue: ''}, align:'right'},";
  $models[] = "{name:'ket', width:200, sortable:false, editable:true, editrules:{}, formatter:''},";
  $models[] = "{name:'jml_old', hidden:true},";
  $models[] = "{name:'jml_next_old', hidden:true},";
?>
var last, last_bahas;
var purge_pembahasan = new Array();
var purge_sumberdana = new Array();

$(document).ready(function() {

  $('.currency').formatCurrency(fmtCurrency);
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();

  $('#tabs a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

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
    recordtext:'{2} baris'
  });

  gridRKADPA.init({
      grd: $('#grd_rinci'),
      tipe: '<?php echo $this->modul_name ?>',
      id_skpd: App.id_skpd(),
      akses: <?php echo isset($akses) ? $akses : 0 ?>,
      perubahan: <?php echo $this->data_model->perubahan ? 'true' : 'false'; ?>,
      total: App.total,
  });

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
    loadonce:true,
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

  $("#grd_sumberdana").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Sumber Dana', 'Nominal'],
    colModel:[
      {name:'idsd', hidden:true},
      {name:'nama', width:650, sortable:false},
      {name:'nom', width:150, sortable:false, editable:true, editrules: {float:true, required: true}, formatter:'currency', align:'right', editoptions:{onKeydown:'ForceNumericOnly(event)'}},
    ],
    pager:'#pgr_sumberdana',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
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
    App.total(total);
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

  function TotalDana()
  {
    var total_dana = $('#grd_sumberdana').jqGrid('getCol', 'nom', false, 'sum');
    $('#grd_sumberdana').jqGrid('footerData','set',{nama:'Total Sumber Dana',nom:total_dana});
    App.total_dana(total_dana);
  }

  function edit_row(id){
    $(this).jqGrid('saveRow', last);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
    last = id;
  };

  function after_save(){
    $(this).focus();
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
    }
    answer = confirm('Hapus ' + kode + ' dari daftar?');

    if(answer == true){
      if ($(this).attr('id') === 'grd_bahas'){
        purge_pembahasan.push(id);
        $(this).jqGrid('delRowData', id);
      }
      else if ($(this).attr('id') === 'grd_sumberdana'){
        purge_sumberdana.push(id);
        $(this).jqGrid('delRowData', id);
      }
      TotalDana();
      HitungTotal();
    }
  };
      
});

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
    self.id = ko.observable(<?php echo isset($data['ID_FORM_ANGGARAN']) ? $data['ID_FORM_ANGGARAN'] : 0 ?>);
    self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : '' ?>)
    .extend({
      required: {params: true, message: 'SKPD belum dipilih'}
    });
    self.kd_skpd = ko.observable('<?php echo isset($data['KODE_SKPD_LKP']) ? $data['KODE_SKPD_LKP'] : '' ?>');
    self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD']) ? $data['NAMA_SKPD'] : '' ?>');
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL_PEMBAHASAN'])? format_date($data['TANGGAL_PEMBAHASAN']): date('d/m/Y'); ?>')
    .extend({
      required: {params: true, message: 'Tanggal tidak boleh kosong'}
    });
    self.ps = ko.observable(<?php echo isset($data['ID_PEJABAT_SKPD'])?$data['ID_PEJABAT_SKPD']:0 ?>);
    self.nm_pejabat = ko.observable('<?php echo isset($data['NAMA_PEJABAT'])?$data['NAMA_PEJABAT']:''?>');
    self.ket = ko.observable('<?php echo isset($data['KETERANGAN'])?$data['KETERANGAN']:''?>');
    self.total = ko.observable(0);
    self.total_dana = ko.observable(0);

    self.id_skpd.subscribe(function(){
      self.updatePejabat();
    });

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit RKA 2.1' : 'Entri RKA 2.1');
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
    
    if ($('#grd_sumberdana').jqGrid('getDataIDs').length === 0) {
      errmsg.push('Sumber Dana Belum Diisi.');
    }
    else {
      if (App.total() !== App.total_dana())
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
      data['bahasan'] = JSON.stringify($('#grd_bahas').jqGrid('getRowData'));
      data['sumberdana'] = JSON.stringify($('#grd_sumberdana').jqGrid('getRowData'));
      data['purge_rincian'] = gridRKADPA.get_purge;
      data['purge_pembahasan'] = purge_pembahasan;
      data['purge_sumberdana'] = purge_sumberdana;

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
        grd_sd = $('#grd_sumberdana');

    if (App.id() > 0){
      grd_rinci.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/rincian/' + App.id(), 'datatype': 'json'});
      grd_rinci.trigger('reloadGrid');
      grd_bahas.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/bahasan/' + App.id(), 'datatype': 'json'});
      grd_bahas.trigger('reloadGrid');
      grd_sd.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/sumberdana/' + App.id(), 'datatype': 'json'});
      grd_sd.trigger('reloadGrid');
    }
    else {
      grd_rinci.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_bahas.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_sd.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }
  
  App.init_grid_by_fa = function(){
    $.ajax({
      url: root+modul+'/get_id_fa/',
      type: 'POST',
      dataType: 'json',
      data: {id_skpd:App.id_skpd()},
      success: function(res){
        var id = res.id_fa,
            grd_rinci = $('#grd_rinci'),
            grd_bahas = $('#grd_bahas'),
            grd_sd = $('#grd_sumberdana');

        grd_rinci.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/rincian/' + id, 'datatype': 'json'});
        grd_rinci.trigger('reloadGrid');
        grd_bahas.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/bahasan/' + id, 'datatype': 'json'});
        grd_bahas.trigger('reloadGrid');
        grd_sd.jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/sumberdana/' + id, 'datatype': 'json'});
        grd_sd.trigger('reloadGrid');
      }
    });
  }

  App.pilih_skpd = function(){
    var option = {multi:0, mode:'rka21'};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
      
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
    App.total();
  }, 2000)
</script>
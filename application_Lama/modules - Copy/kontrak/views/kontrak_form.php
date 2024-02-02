<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>
<form id="frm" method="post" action="<?php echo base_url(); ?>kontrak/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: nok" >
      <label class="control-label" for="nok">Nomor Kontrak</label>
      <input type="text" id="nok" class="span3" data-bind="value: nok" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tglk" >
      <label class="control-label" for="tglk">Tanggal Kontrak</label>
      <input type="text" id="tglk" class="span2 datepicker" data-bind="value: tglk" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: selisih" >
      <label class="control-label" for="tglselesai">Tanggal Selesai</label>
      <input type="text" id="tglselesai" class="span2 datepicker" data-bind="value: tglselesai" required />
      <span class="span1"><span data-bind="text: selisih"></span> hari</span>
    </div>
    <div class="control-group pull-right" data-bind="validationElement: nilaik" >
      <label class="control-label" for="nilaik">Nilai Kontrak</label>
      <input type="text" id="nilaik" class="span3 currency" readonly="1" data-bind="numeralvalue: nilaik" required />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: nobap" >
      <label class="control-label" for="nobap">Nomor BAP</label>
      <input type="text" id="nobap" class="span3" data-bind="value: nobap" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tglbap" >
      <label class="control-label" for="tglbap">Tanggal BAP</label>
      <input type="text" id="tglbap" class="span2 datepicker" data-bind="value: tglbap" required />
    </div>
    <div class="control-group pull-right" data-bind="validationElement: nominalbap" >
      <label class="control-label" for="nominalbap">Nilai BAP</label>
      <input type="text" id="nominalbap" class="span3 currency" readonly="1" data-bind="numeralvalue: nominalbap" required />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: id_skpd" >
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kd_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" required />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="nm_skpd" readonly="1" data-bind="value: nm_skpd, executeOnEnter: pilih_skpd" />
        <span class="add-on" data-bind="visible: !isEdit() && !isSKPD && canSave(),  click: pilih_skpd" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: id_keg" >
      <label class="control-label" for="kdkeg">Kegiatan</label>
      <input type="text" class="span2" id="kdkeg" readonly="1" data-bind="value: kd_keg, executeOnEnter: pilih_keg" required />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="nm_keg" readonly="1" data-bind="value: nm_keg, executeOnEnter: pilih_keg" required />
        <span class="add-on" data-bind="visible: canSave(), click: pilih_keg"><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <fieldset>
    <legend>Rekanan</legend>
    <div class="controls-row">
      <label class="control-label span2" for="nmperusahaan">Nama Perusahaan</label>
      <div class="control-group pull-left" data-bind="validationElement: nmperusahaan">
        <input type="text" id="nmperusahaan" class="span8" data-orig-title="" title="Nama Perusahaan belum diisi" data-bind="value: nmperusahaan" required="">
      </div>
    </div>
    <div class="controls-row">
      <label class="control-label span2" for="nmpimpinan">Nama Pimpinan</label>
      <div class="control-group pull-left" data-bind="validationElement: nmpimpinan">
        <input type="text" class="span8" id="nmpimpinan" data-bind="value: nmpimpinan" data-orig-title="" title="Nama Perusahaan belum diisi" required="" />
      </div>
    </div>
    <div class="controls-row">
      <label class="control-label span2" for="almperusahaan">Alamat Perusahaan</label>
      <div class="control-group pull-left" data-bind="validationElement: almperusahaan" >
        <input type="text" class="span8" id="almperusahaan" data-bind="value: almperusahaan" data-orig-title="" title="Alamat Perusahaan belum diisi" required="" />
      </div>
    </div>
    <div class="controls-row">
      <label class="control-label span2" for="npwp">NPWP</label>
      <div class="control-group pull-left" data-bind="validationElement: npwp" >
        <input type="text" class="span8" id="npwp" data-bind="value: npwp" data-orig-title="" title="NPWP belum diisi" required="" />
      </div>
    </div>
    <div class="controls-row">
      <label class="control-label span2" for="nmbank">Nama Bank</label>
      <div class="control-group pull-left" data-bind="validationElement: nmbank" >
        <input type="text" class="span8" id="nmbank" data-bind="value: nmbank" data-orig-title="" title="Nama Bank belum diisi" required="" />
      </div>
    </div>
    <div class="controls-row">
      <label class="control-label span2" for="norekb">No Rekening</label>
      <div class="control-group pull-left" data-bind="validationElement: norekb" >
        <input type="text" class="span8" id="norekb" data-bind="value: norekb" data-orig-title="" title="Nomor Rekening belum diisi" required="" />
      </div>
    </div>
  </fieldset>


  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
    <li class="active"><a href="#home">Rincian Kontrak</a></li>
  </ul>
  <div class="tab-content" style="height:295px">
    <div class="tab-pane active" id="home">
      <table id="list"></table>
      <div id="pager"></div>
    </div>
  </div>

  <div class="bottom-bar">
    <input type="button" value="Sebelumnya" class="btn btn-primary" data-bind="click: prev" />
    <input type="button" value="Berikutnya" class="btn btn-primary" data-bind="click: next" />
    <div class="btn-group dropup">
      <button type="button" class="btn btn-primary" data-bind="enable: canSave && !processing(), click: function(data, event){save(false, data, event) }" />Simpan</button>
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canSave">
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#" data-bind="enable: canSave, click: function(data, event){save(true, data, event) }" >Simpan & Buat Baru</a></li>
      </ul>
    </div>
    <!--<div class="btn-group dropup">
      <button type="button" class="btn btn-primary" data-bind="enable: canPrint, click: print" >Cetak</button>
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint">
        <span class="caret"></span>
      </button>
      <ul class="dropdown-menu">
        <li><a href="#">PDF</a></li>
        <li><a href="#">XLS</a></li>
      </ul>
    </div>-->
    <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
  </div>
</form>

<script>
var last = 0,
    purge = [];

$(document).ready(function() {
  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#list").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['', 'Kode Rekening','Nama Rekening', 'Anggaran', 'Nilai Kontrak', 'Nilai BAP','anggaran sblm','sisa anggaran'],
    colModel:[
        {name:'idrek', hidden:true},
        {name:'kdrek', width:100, sortable:false},
        {name:'nmrek', width:320, sortable:false},
        {name:'anggaran', width:150, sortable:false, formatter:'currency', align:'right'},
        {name:'nkontrak', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
        {name:'nbap', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
        {name:'kontrak', width:150, sortable:false, formatter:'currency', align:'right',hidden:true},
        {name:'sisa', width:150, sortable:false, formatter:'currency', align:'right', hidden:true}
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
    height:'200',
    recordtext:'{2} baris',
    loadComplete:function(){
      var idarr = $(this).jqGrid('getDataIDs');
      HitungTotal();
      GetSisaAnggaran(idarr);
    },
    onSelectRow: function(id){
      if(id && id!==last){
         $(this).restoreRow(last);
         last=id;
      }
    },
    ondblClickRow: edit_row,
  });

  $("#list").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#list").jqGrid('navGrid', '#pager', {
      add:true,
      addtext: 'Tambah',
      addfunc:add_row,
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

});

  function HitungTotal(){
    totalk = $('#list').jqGrid('getCol', 'nkontrak', '', 'sum');
    totalbap = $('#list').jqGrid('getCol', 'nbap', '', 'sum');
    App.nilaik(totalk);
    App.nominalbap(totalbap);
  };


  function add_row(){
    var id_skpd = App.id_skpd(),
         id_keg  = App.id_keg(),
         $list = $(this),
         i = 0, rs = [];

    if ((undefined === id_keg) || ('' === id_skpd)){
      var message = id_skpd ? '' : 'SKPD';
      message += (undefined === id_keg ? (message ? ' dan ' : '') + 'Kegiatan' : '');
      message += ' belum dipilih.';
      show_warning(message);
      return;
    }

    option = {multi:1,  mode:'anggaranbl', id_skpd: id_skpd, id_kegiatan: id_keg},

    Dialog.pilihRekening(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        addRowSorted($list, {'id':'idrek','sortName':['kdrek']}, {'idrek':rs.idrek, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek, 'anggaran':rs.anggaran,'kontrak':rs.kontrak,'sisa':rs.sisa});
        GetAnggaran([rs.idrek]);
        GetSisaAnggaran([rs.idrek]);
        //HitungSisaAnggaran(id);
      }
    });
  };

  function GetAnggaran(idarr){
    var len = idarr.length,
        rs = data = {};

    for (var i = 0; i < len; i++){
      rs = $('#list').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id(),
        idr: idarr[i],
        id_skpd: App.id_skpd(),
        id_kegiatan: App.id_keg(),
        id_rekening: rs.idrek
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/get_anggaran',
        data: data,
        success: function(res) {
          $('#list').jqGrid('setRowData', res.idr, {anggaran: res.anggaran});
        },
      });
    }
  }
  
    function GetSisaAnggaran(idarr){
    var len = idarr.length,
        //arrspd = $('#grd_proposal').jqGrid('getCol', 'idspd'),
        rs = data = {};

    for (var i = 0; i < len; i++){
      rs = $('#list').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id(),
        idr: idarr[i],
        id_skpd: App.id_skpd(),
        id_kegiatan: App.id_keg(),
        id_rekening: rs.idrek,
        nok : App.nok()
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/get_sisa_anggaran',
        data: data,
        success: function(res) {
          $('#list').jqGrid('setRowData', res.idr, {kontrak: res.kontrak});
        HitungSisaAnggaran(res.idr);
        },
      });
    }
  }
  
  function edit_row(id){
    $(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
    last = id;
  };

  function after_save(id){
    $(this).focus();
    HitungTotal();
    HitungSisaAnggaran(id);
  }
  
  function HitungSisaAnggaran(id){
    var rs = $('#list').jqGrid('getRowData', id),
        sisa = parseFloat(rs.anggaran) - parseFloat(rs.kontrak) - parseFloat(rs.nkontrak),
        style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
    $('#list').jqGrid('setRowData', id, {sisa: sisa}, style);
  }
  
  function del_row(id){
    var rs = {},
        answer = false,
        len = id.length;

    rs = $(this).jqGrid('getRowData', id);
    answer = confirm('Hapus ' + rs.kdrek + ' dari daftar?');

    if(answer == true){
      purge.push(id);
      $(this).jqGrid('delRowData', id);
      HitungTotal();
    }
  };

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelKONTRAK = function (){
    var self = this;
    self.modul = 'Kontrak';
    self.processing = ko.observable(false);
    self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID']) ? $data['ID'] : 0 ?>');
    self.nok = ko.observable(<?php echo isset($data['NO_KONTRAK']) ? json_encode($data['NO_KONTRAK']) : '' ?>)
      .extend({
        required: {params: true, message: 'Nomor kontrak tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor kontrak tidak boleh melebihi 50 karakter'},
      });
    self.tglk = ko.observable('<?php echo isset($data['TANGGAL_KONTRAK']) ? format_date($data['TANGGAL_KONTRAK']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal kontrak tidak boleh kosong'}
      });
    self.nilaik = ko.observable(<?php echo isset($data['NOMINAL_KONTRAK']) ? $data['NOMINAL_KONTRAK'] : 0 ?>);
    self.nobap = ko.observable('<?php echo isset($data['NO_BAP']) ? $data['NO_BAP'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor bap tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor bap tidak boleh melebihi 50 karakter'},
      });
    self.tglbap = ko.observable('<?php echo isset($data['TANGGAL_BAP']) ? format_date($data['TANGGAL_BAP']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal bap tidak boleh kosong'}
      });
    self.tglselesai = ko.observable('<?php echo isset($data['TANGGAL_SELESAI']) ? format_date($data['TANGGAL_SELESAI']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal selesai tidak boleh kosong'}
      });
    self.nominalbap = ko.observable(<?php echo isset($data['NOMINAL_BAP']) ? $data['NOMINAL_BAP'] : 0 ?>);
    self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : '' ?>)
      .extend({
        required: {params: true, message: 'SKPD belum dipilih'}
      });
    self.kd_skpd = ko.observable(<?php echo isset($data['KODE_SKPD_LKP']) ? json_encode($data['KODE_SKPD_LKP']) : '' ?>);
    self.nm_skpd = ko.observable(<?php echo isset($data['NAMA_SKPD']) ? json_encode($data['NAMA_SKPD']) : '' ?>);
    self.id_keg = ko.observable(<?php echo isset($data['ID_KEGIATAN']) ? $data['ID_KEGIATAN'] : '' ?>)
      .extend({
        required: {params: true, message: 'Kegiatan belum diisi'}
      });
    self.kd_keg = ko.observable(<?php echo isset($data['KODE_KEGIATAN_SKPD']) ? json_encode($data['KODE_KEGIATAN_SKPD']) :'' ?>);
    self.nm_keg = ko.observable(<?php echo isset($data['NAMA_KEGIATAN']) ? json_encode($data['NAMA_KEGIATAN']) :'' ?>);
    self.nmperusahaan = ko.observable(<?php echo isset($data['NAMA_PERUSAHAAN']) ? json_encode($data['NAMA_PERUSAHAAN']) :'' ?>)
      .extend({
        required: {params: true, message: 'Nama Perusahaan belum diisi'},
        maxLength: {params: 500, message: 'Nomor Perusahaan tidak boleh melebihi 500 karakter'}
      });
    self.nmpimpinan = ko.observable(<?php echo isset($data['NAMA_PIMPINAN']) ? json_encode($data['NAMA_PIMPINAN']) :'' ?>)
      .extend({
        required: {params: true, message: 'Nama Pemimpin belum diisi'},
        maxLength: {params: 100, message: 'Nama Pemimpin tidak boleh melebihi 100 karakter'}
      });
    self.almperusahaan = ko.observable(<?php echo isset($data['ALAMAT_PERUSAHAAN']) ? json_encode($data['ALAMAT_PERUSAHAAN']) :'' ?>)
      .extend({
        required: {params: true, message: 'Alamat perusahaan belum diisi'},
        maxLength: {params: 200, message: 'alamat perusahaan tidak boleh melebihi 200 karakter'}
      });
    self.npwp = ko.observable(<?php echo isset($data['NPWP']) ? json_encode($data['NPWP']) :'' ?>)
      .extend({
        required: {params: true, message: 'NPWP belum diisi'},
        maxLength: {params: 50, message: 'NPWP tidak boleh melebihi 50 karakter'}
      });
    self.nmbank = ko.observable(<?php echo isset($data['NAMA_BANK']) ? json_encode($data['NAMA_BANK']) :'' ?>)
      .extend({
        required: {params: true, message: 'Nama Bank belum diisi'},
        maxLength: {params: 100, message: 'Nama Bank tidak boleh melebihi 100 karakter'}
      });
    self.norekb = ko.observable(<?php echo isset($data['NO_REKENING_BANK']) ? json_encode($data['NO_REKENING_BANK']) :'' ?>)
      .extend({
        required: {params: true, message: 'Rekening bank belum diisi'},
        maxLength: {params: 50, message: 'Rekening tidak boleh melebihi 50 karakter'}
      });

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode()  === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
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

    self.selisih = ko.computed(function(){
      var a = moment(self.tglk(), "DD/MM/YYYY");
      var b = moment(self.tglselesai(), "DD/MM/YYYY");
      jmhr = b.diff(a, 'days');
      return jmhr;
    });
    
    self.selisih.extend({
        mustGreater: {params: 0, message: 'Tanggal Selesai Kontrak harus lebih muda dari Tanggal Kontrak'}
      });
      
    self.errors = ko.validation.group(self);
  }

  var App = new ModelKONTRAK();

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
    var grdrek = $('#list'), errmsg = [];
    // cek jika ada baris di grid belum disimpan
    checkGridRow(grdrek, 'idrek', after_save);
    // cek jika grid belum diisi
    if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
      errmsg.push('Belum ada Rekening yang di entri.');
    }
    if (!App.isValid()){
      errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
      App.errors.showAllMessages();
    }
    // cek jika ada sisa yang negatif
    if (checkGridMinus(grdrek, 'sisa')){
      errmsg.push('Ada Sisa Anggaran yang sisanya minus');
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
        data['rincian'] = JSON.stringify($('#list').jqGrid('getRowData'));
        data['purge'] = purge;

    App.processing(true);
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
    var grd_rinci = $('#list');
    if (App.nok() !=''){
      grd_rinci.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rinci/' + App.id(), 'datatype': 'json'});
      grd_rinci.trigger('reloadGrid');
    }
    else {
      grd_rinci.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }

  App.pilih_skpd = function(){
    if (!App.canSave()) { return; }
    var option = {multi:0};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
    });
  }

  App.pilih_keg = function(){
    if (!App.canSave()) { return; }
    var id_skpd = App.id_skpd();
    var option = {multi:0,id_skpd:id_skpd, mode:'rka'};
    if (!id_skpd) {
        message = 'SKPD belum dipilih';
        show_warning(message);
        return;
    }
    Dialog.pilihKegiatan(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_keg(rs.id);
      App.kd_keg(rs.kode);
      App.nm_keg(rs.nama);
    });
  }

  

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
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
    App.init_grid();
  }, 500)
</script>
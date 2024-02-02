<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>spj/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: no" >
      <label class="control-label" for="no">Nomor</label>
      <input type="text" id="no" class="span3" readonly="1" data-bind="value: no" required/>
    </div>
    <div class="control-group pull-left" style="margin-left:20px"  data-bind="validationElement: tgl">
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" id="tgl" class="span2 datepicker" readonly="1" data-bind="value: tgl" required placeholder="dd/mm/yyyy"/>
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="visible: !isSKPD, validationElement: sah" >
      <label class="control-label" for="sah">Tanggal Pengesahan</label>
      <input type="text" id="sah" class="span2 datepicker" data-bind="value: sah" required placeholder="dd/mm/yyyy"/>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: id_skpd" >
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd" />
      <div class="controls span5">
        <input type="text" class="span5" id="nama_skpd" readonly="1" data-bind="value: nm_skpd" />
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
        <textarea rows="2" class="span7" readonly="1" data-bind="value: deskripsi" required></textarea>
      </div>
      <div class="control-group controls-row" data-bind="validationElement: pekas" >
        <label class="control-label" for="kdrek_pekas">Rekening Bendahara</label>
        <input type="text" class="span2" id="kdrek_pekas" readonly="1" data-bind="value: kd_pekas" required readonly="1" />
        <div class="controls span5">
          <input type="text" class="span5" id="nmrek_pekas" readonly="1" data-bind="value: nm_pekas" required readonly="1" />
        </div>
      </div>
      <div class="controls-row" style="margin-bottom:10px;">
        <label class="control-label" for="bk">Bendahara Pengeluaran</label>
        <input type="text" id="bk" class="span8" readonly="1" data-bind="value: nm_bk" />
      </div>
    </div>
    <div class="controls-group pull-right">
      <div class="control-group span2" data-bind="validationElement: keperluan" >
        <fieldset>
          <h6>Keperluan</h6>
          <label class="radio">
            <input type="radio" data-bind="disable: isEdit, checked: keperluan" value="TU" />TU
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
    <li class="active" ><a href="#rincianspj">Rincian SPJ</a></li>
  </ul>

  <div class="tab-content" style="height:300px">
    <div class="tab-pane active" id="rincianspj">
      <table id="grdrek"></table>
      <div id="pgrrek"></div>
      <div class="controls pull-right" style="margin-top:5px;">
        <label style="float:left; margin-top:10px; margin-right:10px;" for="total_rek">Total Rekening</label>
        <input type="text" id="total_rek" class="span3 currency" readonly="1" data-bind="numeralvalue: total_rek" />
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
    <input type="button" id="save" value="Simpan" class="btn btn-primary" data-bind="enable:canSave && !processing(), click: function(data, event){save(false, data, event) }" />
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
var last_rek, batas = batas_all = 0;

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
    colNames:['','','','Kode Kegiatan', 'Kode Rekening', 'Nama Rekening', 'Nominal', 'Sisa s/d sekarang', 'Sisa keseluruhan', '', ''],
    colModel:[
        {name:'idr', hidden:true},
        {name:'idrek', hidden:true},
        {name:'idkeg', hidden:true},
        {name:'kdkeg', width:150, sortable: false},
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
  });
  $("#grdrek").jqGrid('bindKeys');
  $("#grdrek").jqGrid('navGrid', '#pgrrek', {
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:false,
    refreshtext:'Refresh',
  },{},{},{},{});

});

  function TotalRekening(){
    var total =  $('#grdrek').jqGrid('getCol', 'nom', '', 'sum');
    App.total_rek(total);
  }

  function HitungSisaRekening(id){
    var rs = $('#grdrek').jqGrid('getRowData', id),
        sisa = parseFloat(rs.batas) - parseFloat(rs.nom),
        sisa_all = parseFloat(rs.batas_all) - parseFloat(rs.nom),
        style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
    $('#grdrek').jqGrid('setRowData', id, {sisa: sisa, sisa_all: sisa_all}, style);
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
        id_kegiatan: rs.idkeg,
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
    self.no = ko.observable(<?php echo isset($data['NOMOR']) ? json_encode($data['NOMOR']) :"'(Auto)'" ?>)
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'}
      });
    self.sah = ko.observable('<?php echo isset($data['TANGGAL_PENGESAHAN']) ? format_date($data['TANGGAL_PENGESAHAN']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal pengesahan tidak boleh kosong'}
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
    self.keperluan = ko.observable('<?php echo isset($data['KEPERLUAN']) ? $data['KEPERLUAN'] : '' ?>')
      .extend({
        required: {params: true, message: 'Keperluan belum dipilih'}
      });
    self.beban = ko.observable('<?php echo isset($data['BEBAN']) ? $data['BEBAN'] : '' ?>')
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
    self.batas = ko.observable(0);
    self.batas_all = ko.observable(0);

    self.is_verifikasi = <?php echo isset($is_verifikasi) && $is_verifikasi ? 'true' : 'false' ?>;
    self.no_veri = ko.observable(<?php echo isset($data['NOMOR_VERIFIKASI']) ? json_encode($data['NOMOR_VERIFIKASI']) : '' ?>)
      .extend({
        required: {params: true, message: 'Nomor verifikasi tidak boleh kosong'},
      });
    self.tgl_veri = ko.observable('<?php echo isset($data['TANGGAL_VERIFIKASI']) ? format_date($data['TANGGAL_VERIFIKASI']) : format_date(date('m.d.y')) ?>')
      .extend({
        required: {params: true, message: 'Tanggal verfikasi tidak boleh kosong'},
        dateMustGreaterEqual: {params:self.tgl, message: 'Tanggal Verifikasi harus lebih besar atau sama dengan tanggal SPJ' },
      });
    self.putusan = ko.observable(<?php echo isset($data['VERIFIKASI']) ? $data['VERIFIKASI'] : '' ?>);
    self.catatan = ko.observable(<?php echo isset($data['CATATAN']) ? json_encode($data['CATATAN']) : '' ?>);
    self.pa = ko.observable('<?php echo isset($data['ID_PA']) ? $data['ID_PA'] : '' ?>');
    self.nm_pa = ko.observable(<?php echo isset($data['PA_NAMA']) ? json_encode($data['PA_NAMA']) : '' ?>);
    self.ppk = ko.observable('<?php echo isset($data['ID_PPK_SKPD']) ? $data['ID_PPK_SKPD'] : '' ?>');
    self.nm_ppk = ko.observable(<?php echo isset($data['PPK_NAMA']) ? json_encode($data['PPK_NAMA']) : '' ?>);

    self.isVerified = ko.computed(function(){
      return self.putusan() == 1 || self.putusan() == 2;
    });

    self.auto.subscribe(function(new_value){
      if (new_value) {
        self.no('(Auto)');
      }
      else {
        self.no('');
        $('#no').focus();
      }
    });

    self.beban.subscribe(function(new_beban){
      var grdkeg = $('#grdkeg');
      var grdrek = $('#grdrek');

      new_beban === 'BL' ? grdrek.jqGrid('showCol', 'kdkeg') : grdrek.jqGrid('hideCol', 'kdkeg');

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
    var grdrek = $('#grdrek'), grdpjk = $('#grdpjk'), errmsg = [];
    // cek jika ada baris di grid belum disimpan
    checkGridRow(grdrek, 'idr', null);
    // cek jika grid belum diisi
    if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
      errmsg.push('Belum ada Rekening yang di entri.');
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
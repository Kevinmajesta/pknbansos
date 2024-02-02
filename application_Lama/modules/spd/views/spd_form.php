<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>spd/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: no">
      <label class="control-label" for="no">Nomor</label>
      <input type="text" id="no" class="span3" data-bind="value: no"/>
    </div>
    <div class="control-group pull-left span2" data-bind="validationElement: tgl">
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" />
    </div>
    <div class="control-group pull-left span1" data-bind="validationElement: triwulan">
        <label class="control-label" for="triwulan">Triwulan</label>
        <select id="triwulan" class="span1" data-bind="options: opsitw, value: triwulan" /></select>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group" data-bind="validationElement: id_skpd" >
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" />
      <div class="controls span8 input-append">
        <input type="text" class="span8" id="nama_skpd" readonly="1" data-bind="value: nm_skpd, executeOnEnter: pilih_skpd" />
        <span class="add-on" data-bind="visible: !isEdit() && !isSKPD && canSave(), click: pilih_skpd" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row" >
    <div class="control-group" data-bind="validationElement: deskripsi" >
      <label class="control-label" for="deskripsi">Keterangan</label>
      <textarea rows="2" class="span10" data-bind="value: deskripsi" ></textarea>
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
    <li class="active"><a href="#skpd">SKPD</a></li>
    <li data-bind="visible: beban() === 'BL'"><a href="#kegiatan">Kegiatan</a></li>
    <li><a href="#rekening">Rekening</a></li>
  </ul>

  <div class="tab-content" style="height:310px">
    <div class="tab-pane active" id="skpd">
      <div class="control-group pull-left">
        <div class="controls-row">
          <label style="float: left;margin-top: 10px;margin-right: 5px" for="no_dpa">Nomor DPA</label>
          <input type="text" id="no_dpa" class="span4" data-bind="value: no_dpa" />
        </div>

        <div class="controls-row">
          <label class="control-label" for="sisa_dpa">Sisa <span data-bind="text: sisa"></span></label>
          <input type="text" class="span4 currency" id="sisa_dpa" readonly="1" data-bind="numeralvalue: sisa_dpa" />
        </div>

        <div class="controls-row">
          <label class="control-label" for="sisa_skpd">Sisa Anggaran SKPD</label>
          <input type="text" class="span4 currency" id="sisa_skpd" readonly="1" data-bind="numeralvalue: sisa_skpd" />
        </div>

        <div class="controls-row">
          <label class="control-label" for="pptk">PPTK</label>
      <input type="text" name="pptk" id="pptk" class="span8" data-bind="attr : {'data-init': nm_pptk}, value: pptk, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat PPTK', initSelection: init_select, query: query_pejabat_skpd }" />
        </div>

        <div class="controls-row" style="margin-top:10px;">
          <label class="control-label" for="bk">Bendahara Pengeluaran</label>
      <input type="text" name="bk" id="bk" class="span8" data-bind="attr : {'data-init': nm_bk}, value: bk, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Bendahara Pengeluaran', initSelection: init_select, query: query_pejabat_skpd }" />
        </div>
      </div>

      <div class="control-group pull-right">
        <div class="control-group pull-right" data-bind="validationElement: beban">
          <fieldset>
            <h6>Beban</h6>
            <label class="radio">
              <input type="radio" data-bind="disable: isEdit, checked: beban" value="BTL" />Beban Tidak Langsung
            </label>
            <label class="radio">
              <input type="radio" data-bind="disable: isEdit, checked: beban" value="BL" />Beban Langsung
            </label>
          </fieldset>
        </div>
      </div>
    </div>

    <div class="tab-pane" id="kegiatan">
      <table id="list"></table>
      <div id="pager"></div>
    </div>

    <div class="tab-pane" id="rekening">
      <table id="list2"></table>
      <div id="pager2"></div>
      <div class="controls-row" style="margin-top:5px;">
        <div class="controls pull-left">
          Isi Nominal sesuai Pagu :
          <a class="btn" href="#" data-bind="click: function(data, event){splitSPD(0, data, event)}" ><i class="icon-cog"></i>0%</a>
          <a class="btn" href="#" data-bind="click: function(data, event){splitSPD(25, data, event)}" ><i class="icon-cog"></i>25%</a>
          <a class="btn" href="#" data-bind="click: function(data, event){splitSPD(50, data, event)}" ><i class="icon-cog"></i>50%</a>
          <a class="btn" href="#" data-bind="click: function(data, event){splitSPD(75, data, event)}"><i class="icon-cog"></i>75%</a>
          <a class="btn" href="#" data-bind="click: function(data, event){splitSPD(100, data, event)}"><i class="icon-cog"></i>100%</a>
        </div>
        <div class="controls pull-right">
          <label style="float:left; margin-top:10px; margin-right:5px;" for="total_rek">Total Rekening</label>
          <input type="text" id="total_rek" class="span3 currency" readonly="1" data-bind="numeralvalue: total" />
        </div>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="controls pull-left">
      <label style="float:left; margin-top:10px; margin-right:5px;" for="bud">BUD/Kuasa BUD</label>
      <input type="text" id="bud" class="span6" data-bind="attr : {'data-init': nm_bud}, value: bud, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat BUD/Kuasa BUD', initSelection: init_select, query: query_pejabat_daerah }" />
    </div>
    <div class="control-group pull-right" data-bind="validationElement: total" >
     <label style="float:left; margin-top:10px; margin-right:5px;" for="total">Total</label>
     <input type="text" name="total" id="total" class="span3 currency" readonly="1" data-bind="numeralvalue: total" />
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
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" data-bind="enable: canPrint" >
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
  var lastkeg = lastrek = 0,
      newid = batas_dpa = batas_skpd = 0;
  var purge_keg = [], purge_rek = [];

$(document).ready(function(){
  inisialisasi();

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#list").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['id', 'Kode Kegiatan', 'Nama Kegiatan', 'Anggaran', 'SPD Sebelumnya', 'SPD Sekarang', 'Total Rekening', 'Total sd. SPD ini', 'Total SPD', 'Sisa Belum SPD', '', ''],
    colModel:[
      {name:'idkeg', hidden:true},
      {name:'kdkeg', width:120, sortable:false},
      {name:'nmkeg', width:250, sortable:false},
      {name:'pagu', width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'spd', width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'nom', width:150,sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
      {name:'nomrek', width:150,sortable:false, formatter:'currency', align:'right'},
      {name:'spdu2n', width:150,sortable:false, formatter:'currency', align:'right'},
      {name:'spdtot', width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'sisa', width:150,sortable:false, formatter:'currency', align:'right'},
      {name:'batas', hidden:true},
      {name:'totalx', hidden:true},
    ],
    pager: '#pager',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'210',
    loadComplete: function(){
      var idarr = $('#list').jqGrid('getDataIDs');
      GetSisaKegiatan(idarr);
      HitungTotal()
    },
    onSelectRow: function(id){
      if(id && id!==lastkeg){
         $(this).restoreRow(lastkeg);
         lastkeg = id;
      }
    },
    ondblClickRow: edit_row,
  });
  $("#list").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#list").jqGrid('navGrid','#pager',{
    add:true,
    addtext: 'Tambah',
    addfunc:add_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    search:false,
    refresh:false,
    refreshtext:'Refresh'
  });
  $("#list").jqGrid('navButtonAdd', "#pager", {
     caption: "Hitung Sisa", title: "Hitung Ulang Sisa", buttonicon: "ui-icon-refresh",
     onClickButton: refreshSisaKeg
  });

  $("#list2").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    mtype: 'POST',
    colNames:['','','','Kode Kegiatan', 'Kode Rekening','Nama Rekening','Anggaran', 'Nominal', 'Total SPD', 'Sisa', '', ''],
    colModel:[
      {name:'idr', hidden:true},
      {name:'idrek', hidden:true},
      {name:'idkeg', hidden:true},
      {name:'kdkeg', width:120, sortable:false},
      {name:'kdrek', width:120, sortable:false},
      {name:'nmrek', width:250, sortable:false},
      {name:'pagu', width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'nom', width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
      {name:'real', width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'sisa', width:150, sortable:false, formatter:'currency', align:'right'},
      {name:'batas', hidden:true},
      {name:'spd', hidden:true},
    ],
    pager: '#pager2',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'210',
    loadComplete: function(){
      var idarr = $('#list2').jqGrid('getDataIDs');
      GetSisaRekening(idarr);
      HitungTotal();
    },
    onSelectRow: function(id){
      if(id && id!==lastrek){
         $(this).restoreRow(lastrek);
         lastrek = id;
      }
    },
    ondblClickRow: edit_row,
  });
  $("#list2").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#list2").jqGrid('navGrid','#pager2',{
    add:true,
    addtext: 'Tambah',
    addfunc:add_row,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
    search:false,
    refresh:false,
    refreshtext:'Refresh'
  });
  $("#list2").jqGrid('navButtonAdd', "#pager2", {
     caption: "Hitung Sisa", title: "Hitung Ulang Sisa", buttonicon: "ui-icon-refresh",
     onClickButton: refreshSisaRek
  });

});

  function HitungTotal(){
    var total = i = idkeg = 0,
      totalkeg = [],
      datarek = $('#list2').jqGrid('getRowData');
    for (i = 0; i < datarek.length; i++){
      idkeg = datarek[i].idkeg + '_';
      isNaN( totalkeg[idkeg] ) ? totalkeg[idkeg] = parseFloat(datarek[i].nom) : totalkeg[idkeg] += parseFloat(datarek[i].nom);
      total += parseFloat(datarek[i].nom);
    }
    App.total(total);

    for (var x in totalkeg){
      $('#list').jqGrid('setCell', parseInt(x), 'nom', totalkeg[x]);
      $('#list').jqGrid('setCell', parseInt(x), 'nomrek', totalkeg[x]);
    }
  }

  function HitungSisaKegiatan(id){
    var rs = $('#list').jqGrid('getRowData', id),
        sisa = parseFloat(rs.batas) - parseFloat(rs.nom),
        spdu2n = parseFloat(rs.spd) + parseFloat(rs.nom),
        spdtot = parseFloat(rs.totalx) + parseFloat(rs.nom),
        style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
    $('#list').jqGrid('setRowData', id, {sisa:sisa, spdu2n:spdu2n, spdtot:spdtot}, style);
  }

  function HitungSisaRekening(id){
    var rs = $('#list2').jqGrid('getRowData', id),
        sisa = parseFloat(rs.batas) - parseFloat(rs.nom),
        real = parseFloat(rs.spd) + parseFloat(rs.nom),
        style = sisa < 0 ? {color: '#FF0000'} : {color: '#000000'};
    $('#list2').jqGrid('setRowData', id, {sisa: sisa, real:real}, style);
  }

  function GetSisaSKPD(){
    data = {
      id: App.id(),
      id_skpd: App.id_skpd(),
      beban: App.beban(),
    };

    $.ajax({
      type: "post",
      dataType: "json",
      data: data,
      url: root+modul+'/sisa_skpd',
      success: function(res) {
        App.batas_dpa(parseFloat(res.dpa));
        App.batas_skpd(parseFloat(res.skpd));
        HitungTotal();
      },
    });
  }

  function GetSisaKegiatan(idarr){
    var len = idarr.length;

    for (var i = 0; i < len; i++){
      rs = $('#list').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id(),
        id_skpd: App.id_skpd(),
        id_kegiatan: rs.idkeg,
        tanggal: App.tgl(),
        beban: App.beban(),
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/sisa_kegiatan',
        data: data,
        success: function(res) {
          $('#list').jqGrid('setRowData', res.idr, {pagu:res.pagu, spd:res.spd, spp:res.spp, spj:res.spj, tw1:res.tw1, tw2:res.tw2, tw3:res.tw3, tw4:res.tw4, batas:res.sisa, totalx:res.spd_all});
          HitungSisaKegiatan(res.idr);
        },
      });
    }
  }

  function GetSisaRekening(idarr){
    var len = idarr.length;

    for (var i = 0; i < len; i++){
      rs = $('#list2').jqGrid('getRowData', idarr[i]);

      data = {
        id: App.id(),
        idr: rs.idr,
        id_skpd: App.id_skpd(),
        beban: App.beban(),
        id_kegiatan: rs.idkeg,
        id_rekening: rs.idrek
      };

      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/sisa_rekening',
        data: data,
        success: function(res) {
          $('#list2').jqGrid('setRowData', res.idr, {pagu:res.pagu, spd:res.spd, spp:res.spp, spj:res.spj, batas: res.sisa});
          HitungSisaRekening(res.idr);
        },
      });
    }
  };

  function add_row(){
    var id_skpd = App.id_skpd(),
        beban = App.beban(),
        $list = $(this),
        option = rskeg = rsrek = srcid = [],
        i = 0,
        $keg = $('#list'),
        $rek = $('#list2');

    if (("" === beban) || (undefined === id_skpd)){
      var message = id_skpd ? '' : 'SKPD';
      message += ("" === beban ? (message ? ' dan ' : '') + 'Beban' : '');
      message += ' belum dipilih.';
      $.pnotify({
        text: message,
        type: 'warning',
      });
    }
    else if (beban === 'BL'){
      option = {multi:0, id_skpd:id_skpd, beban:beban, tanggal: App.tgl(), mode:'spd'};
      Dialog.pilihKegiatanAktivitas(option, function(obj, select){
        rskeg = $(obj).jqGrid('getRowData', select[0].id);

        // tampilkan dialog rekening sesuai kegiatan yang dipilih
        option = {multi:1, id_skpd:id_skpd, beban:beban, id_kegiatan:rskeg.id, mode:'spd'};
        Dialog.pilihRekening(option, function(obj, select){
          // tambahkan kegiatan yang dipilih
          addRowSorted($keg, {'id':'idkeg', 'sortName':['kdkeg']}, {'idkeg':rskeg.id, 'kdkeg':rskeg.kodes, 'nmkeg':rskeg.nama});
          GetSisaKegiatan([rskeg.id]);

          // tambahkan rekening yang dipilih
          for (i = 0; i < select.length; i++){
            rsrek = $(obj).jqGrid('getRowData', select[i].id);
            newid = newid - 1;
            addRowSorted($rek, {'id':'idr', 'sortName':['kdkeg', 'kdrek']}, {'idr':newid, 'idrek':rsrek.idrek, 'kdrek':rsrek.kdrek, 'nmrek':rsrek.nmrek, 'idkeg':rskeg.id, 'kdkeg':rskeg.kodes});
            GetSisaRekening([newid]);
          }
        });
      });
    }
    else {
      var option = {multi:1, id_skpd:id_skpd, beban:beban, mode:'spd'};
      Dialog.pilihRekening(option, function(obj, select){
        for (i = 0; i < select.length; i++){
          rsrek = $(obj).jqGrid('getRowData', select[i].id);
          newid = newid - 1;
          addRowSorted($rek, {'id':'idr', 'sortName':['kdrek']}, {'idr':newid, 'idrek':rsrek.idrek, 'kdrek':rsrek.kdrek, 'nmrek':rsrek.nmrek});
          GetSisaRekening([newid]);
        }
      });
    }
  };

  function edit_row(id){
    var last = $(this).attr('id') === 'list' ? lastkeg : lastrek;
    $(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);

    $(this).attr('id') === 'list' ? lastkeg = id: lastrek = id;
  };

  function after_save(id){
    $(this).focus();
    HitungTotal();
    HitungSisaKegiatan(id);
    HitungSisaRekening(id);
  }

  function del_row(id){
    var rs = {},
        answer = false,
        kode = '';

    rs = $(this).jqGrid('getRowData', id);
    switch ($(this).attr('id')){
      case 'list' : kode = rs.kdkeg; break;
      case 'list2' : kode = rs.kdkeg !== '' ? rs.kdkeg + '.' + rs.kdrek : rs.kdrek; break;
    }
    answer = confirm('Hapus ' + kode + ' dari daftar?');

    if(answer == true){
      if ($(this).attr('id') === 'list'){
        purge_keg.push(id);
        $(this).jqGrid('delRowData', id);
        row = $('#list2').jqGrid('getRowData');
        removed = $.grep(row, function(value){
          return value.idkeg === id;
        });
        for (i = 0; i <= removed.length - 1; i++){
          purge_rek.push(removed[i].idr);
          $('#list2').jqGrid('delRowData', removed[i].idr);
        }
      }
      else if ($(this).attr('id') === 'list2'){
        idkeg = $('#list2').jqGrid('getRowData', id).idkeg;
        purge_rek.push(id);
        $(this).jqGrid('delRowData', id);
        datarek = $('#list2').jqGrid('getRowData');
        cek = $.grep(datarek, function(value){
          return value.idkeg === idkeg;
        });
        if (cek.length === 0){
          $('#list').jqGrid('delRowData', idkeg);
        }
      }
    }
    HitungTotal();
  };

  function refreshSisaKeg(){
    var idarr = $('#list').jqGrid('getDataIDs');
    GetSisaKegiatan(idarr);
    HitungTotal()
  }

  function refreshSisaRek(){
    var idarr = $('#list2').jqGrid('getDataIDs');
    GetSisaRekening(idarr);
    HitungTotal();
  }

  //ko
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelSPD = function (){
    var self = this;
    self.modul = 'SPD';
    self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses:0 ?>);
    //default redaksi sisa DPA
    self.sisa = ko.observable('DPA');
    self.opsitw = [1, 2, 3, 4];
    self.no_dpa = ko.observable(<?php echo isset($data['NO_DPA']) ? json_encode($data['NO_DPA']) : '' ?>);
    self.id = ko.observable('<?php echo isset($data['ID_AKTIVITAS']) ? $data['ID_AKTIVITAS'] : 0 ?>');
    self.no = ko.observable(<?php echo isset($data['NOMOR']) ? json_encode($data['NOMOR']) : '' ?>)
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'}
      });
    self.triwulan = ko.observable(<?php echo isset($data['TRIWULAN']) ? $data['TRIWULAN'] : '' ?>)
      .extend({
        integer: {params: true, message: 'Triwulan harus diisi bilangan bulat'},
        required: {params: true, message: 'Triwulan tidak boleh kosong'}
      });
    self.total = ko.observable(0)
      .extend({
        required: {params: true, message: 'Total tidak boleh kosong'},
        notEqual: {params: 0, message: 'Total tidak boleh bernilai 0'},
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
    self.beban = ko.observable('<?php echo isset($data['BEBAN']) ? $data['BEBAN']:'' ?>')
      .extend({
        required: {params: true, message: 'Beban belum dipilih'},
      });
    self.pptk = ko.observable('<?php echo isset($data['ID_BK']) ? $data['ID_BK']:'' ?>');
    self.nm_pptk = ko.observable(<?php echo isset($data['PPTK_NAMA']) ? json_encode( $data['PPTK_NAMA'] ) : '' ?>);
    self.bk = ko.observable('<?php echo isset($data['ID_BK']) ? $data['ID_BK'] : '' ?>');
    self.nm_bk = ko.observable(<?php echo isset($data['BK_NAMA']) ? json_encode( $data['BK_NAMA'] ) : '' ?>);
    self.bud = ko.observable(<?php echo isset($data['ID_BUD']) ? $data['ID_BUD'] :'' ?>);
    self.nm_bud = ko.observable(<?php echo isset($data['BUD_NAMA']) ? json_encode( $data['BUD_NAMA'] ) : '' ?>);
    self.batas_skpd = ko.observable(0);
    self.batas_dpa = ko.observable(0);

    self.id_skpd.subscribe(function(){
      var grd = $('#list');
      var grd2 = $('#list2');
      self.updatePejabat();
      self.hapusRincian(grd);
      self.hapusRincian(grd2);

      HitungTotal();
      GetSisaSKPD();
    });

    self.beban.subscribe(function(newBeban){
      var grd = $('#list');
      var grd2 = $('#list2');
      self.hapusRincian(grd);
      self.hapusRincian(grd2);

      grd2.jqGrid('hideCol', 'kdkeg');
      switch (newBeban) {
        case 'BTL' : self.sisa('BTL'); break;
        case 'BL' : self.sisa('BL'); grd2.jqGrid('showCol', 'kdkeg'); break;
        case 'KB' : self.sisa('Pembiayaan'); break;
        default : self.sisa('DPA')
      }

      GetSisaSKPD();
      self.updateNoDPA();
    });

    self.sisa_skpd = ko.computed(function(){;
      return self.batas_skpd() - self.total();
    });

    self.sisa_dpa = ko.computed(function(){
      return self.batas_dpa() - self.total();
    });

    self.updateNoDPA = function(){
      switch (self.beban()){
        case 'BTL' : self.no_dpa(self.kd_skpd() + '.00.00.5.1'); break;
        case 'BL' : self.no_dpa(self.kd_skpd() + '.00.00.5.2'); break;
        case 'KB' : self.no_dpa(self.kd_skpd() + '.00.00.6.2'); break;
        default : self.no_dpa(self.kd_skpd() + '.00.00'); break;
      }
    };

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

  var App = new ModelSPD();

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
    var grdkeg = $('#list'), grdrek = $('#list2'), errmsg = [];
    // cek jika ada baris di grid belum disimpan
    checkGridRow(grdkeg, 'idkeg', after_save);
    checkGridRow(grdrek, 'idr', after_save);
    // cek jika grid belum diisi
    if (App.beban() === 'BL' && grdkeg.jqGrid('getGridParam', 'reccount') === 0) {
      errmsg.push('Belum ada Kegiatan yang di entri.');
    }
    if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
      errmsg.push('Belum ada Rekening yang di entri.');
    }
    // cek jika ada sisa yang negatif
    if (checkGridMinus(grdkeg, 'sisa')){
      errmsg.push('Ada Kegiatan yang sisanya minus');
    }
    if (checkGridMinus(grdrek, 'sisa')){
      errmsg.push('Ada Rekening yang sisanya minus');
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

  App.save = function(createNew){
    if (!App.formValidation()){ return }

    var $frm = $('#frm'),
      data = JSON.parse(ko.toJSON(App));
      data['keg'] = JSON.stringify($('#list').jqGrid('getRowData'));
      data['rek'] = JSON.stringify($('#list2').jqGrid('getRowData'));
      data['purge_keg'] = purge_keg;
      data['purge_rek'] = purge_rek;

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
    var grd_kegiatan = $('#list'),
        grd_rekening = $('#list2');

    if (App.id() > 0){
      if (App.beban() === 'BL'){
        grd_kegiatan.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/kegiatan/' + App.id(), 'datatype': 'json'});
        grd_kegiatan.trigger('reloadGrid');
      }
      grd_rekening.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rekening/' + App.id(), 'datatype': 'json'});
      grd_rekening.trigger('reloadGrid');
    }
    else {
      grd_kegiatan.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_rekening.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }

  App.splitSPD = function(persen){
    var grdrek = $('#list2'),
        rowdata = grdrek.jqGrid('getRowData'),
        nomspd = 0;

    for (i = 0; i < rowdata.length; i++){
      nomspd = rowdata[i].pagu * persen / 100;
      grdrek.jqGrid('setRowData', rowdata[i].idr, {nom:nomspd});
      HitungSisaRekening(rowdata[i].idr);
    }
    HitungTotal();
  }

  App.pilih_skpd = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0};
    Dialog.pilihSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_skpd(rs.id);
      App.kd_skpd(rs.kode);
      App.nm_skpd(rs.nama);
      App.updateNoDPA();
    });
  }

  App.updatePejabat = function(){
    App.default_pejabat_skpd(App.id_skpd, App.pptk, App.nm_pptk, 'PPTK');
    App.default_pejabat_skpd(App.id_skpd, App.bk, App.nm_bk, 'BK');
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

  App.hapusRincian = function(grid){
    grid.jqGrid('clearGridData');
    HitungTotal();
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

  App.query_pejabat_daerah = function(option){
    var id_skpd = App.id_skpd();
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
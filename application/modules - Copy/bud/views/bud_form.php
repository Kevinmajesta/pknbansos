<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>bud/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: no" >
      <label class="control-label" for="no">Nomor</label>
      <input type="text" id="no" class="span3" data-bind="value: no" required />
    </div>
    <div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl" >
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" required />
    </div>
  </div>

  <div class="control-group" data-bind="validationElement: deskripsi" >
    <label class="control-label" for="deskripsi">Keterangan</label>
    <textarea rows="2" class="span10" id="deskripsi" data-bind="value: deskripsi" required ></textarea>
  </div>

  <div class="controls-row" style="margin-top:10px; margin-bottom:10px;">
     <label class="control-label" for="bud">BUD/Kuasa BUD</label>
     <input type="text" id="bud"  class="span10" data-bind="attr : {'data-init': nm_bud}, value: bud, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat BUD/Kuasa BUD', initSelection: init_select, query: query_pejabat_daerah }" />
  </div>

  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px;">
    <li class="active"><a href="#home">Rincian Bendahara Umum Daerah</a></li>
    <li><a href="#sumberdana">Saldo Sumber Dana</a></li>
  </ul>

  <div class="tab-content" style="height:350px">
    <div class="tab-pane active" id="home">
      <table id="list"></table>
      <div id="pager"></div>

      <div class="btn-group" style="margin-top: 5px;">
        <button type="button" class="btn" data-bind="click: pilih_sts" >STS</button>
        <button type="button" class="btn" data-bind="click: pilih_sp2d" >SP2D</button>
        <button type="button" class="btn" data-bind="click: pilih_cp" >Kontra Pos</button>
        <button type="button" class="btn" data-bind="click: pilih_sd" >Sumber Dana</button>
        <button type="button" class="btn" data-bind="click: pilih_spfk" >Setoran PFK</button>
        <button type="button" class="btn" data-bind="click: pilih_spjk" >Setoran Pajak</button>
        <button type="button" class="btn" data-bind="click: pilih_ssu" >Setoran Sisa</button>
      </div>
    </div>
    <div class="tab-pane" id="sumberdana">
      <table id="sd"></table>
      <div id="pager_sd"></div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-right" style="margin-top:5px;" >
      <label style="float:left;margin-top:10px; margin-right:10px;" for="belanja">Total Pengeluaran</label>
      <input type="text" id="belanja" class="span3 currency" readonly="1" data-bind="numeralvalue: total_belanja" />
    </div>
    <div class="control-group pull-right" style="margin-top:5px; margin-right:10px"  >
      <label style="float:left;margin-top:10px; margin-right:10px;" for="terima">Total Penerimaan</label>
      <input type="text" id="terima" class="span3 currency" readonly="1" data-bind="numeralvalue: total_terima" />
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
        <li><a href="#">PDF</a></li>
        <li><a href="#">XLS</a></li>
      </ul>
    </div>
    <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
  </div>
</form>

<script>
var last = newid = 0; purge = [];

$(document).ready(function() {
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
    colNames:['','','','','','','','','Nomor','Tipe','Nomor Aktivitas','Kode Rekening','Nama Rekening','Penerimaan','Pengeluaran','Kode SKPD','Nama SKPD','Keterangan'],
    colModel:[
        {name:'idr', hidden:true},
        {name:'idsd', hidden:true},
        {name:'idsts', hidden:true},
        {name:'idsp2d', hidden:true},
        {name:'idcp', hidden:true},
        {name:'idspfk', hidden:true},
        {name:'idspjk', hidden:true},
        {name:'idssu', hidden:true},
        {name:'nobku', width:50, sortable:false, editable:true, editrules: {required: true}, align: 'center'},
        {name:'tipe', width:60, sortable:false, align: 'center'},
        {name:'no', width:150, sortable:false},
        {name:'kdrek', width:100, sortable:false},
        {name:'nmrek', width:150, sortable:false},
        {name:'debet', width:120, sortable:false, align:'right', formatter: 'currency'},
        {name:'kredit',  width:120, sortable:false, align:'right', formatter: 'currency'},
        {name:'kdskpd', width:100, sortable:false},
        {name:'nmskpd', width:250, sortable:false},
        {name:'ket', width:300, sortable:false}
       ],
    pager: '#pager',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'260',
    recordtext:'{2} baris',
    loadComplete:function(){
      HitungTotal();
    },
    onSelectRow: function(id){
      if(id && id !== last){
         $(this).restoreRow(last);
         lastpdf = id;
      }
    },
    ondblClickRow: edit_row,
  });
  $("#list").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#list").jqGrid('navGrid','#pager',{
      add: false,
      edit: false,
      del:true,
      deltext: 'Hapus',
      delfunc:del_row,
      search:false,
      refresh:false,
    },{},{},{},{});

  $("#sd").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['','Nama Sumber Dana','Saldo Awal', 'Saldo Akhir'],
    colModel:[
        {name:'id',hidden:true},
        {name:'nama',width:420, sortable:false},
        {name:'awal',width:220, sortable:false, align:'right',formatter: 'currency'},
        {name:'akhir',width:220, sortable:false, align:'right',formatter: 'currency'}
       ],
    pager: '#pager_sd',
    rowNum:-1,
    scroll:true,
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    loadonce:true,
    width:'935',
    height:'270',
    recordtext:'{2} baris',
    footerrow : true,
    userDataOnFooter : true,
    loadComplete:function(){
      HitungTotal();
    },
    beforeRequest: function(){
      $(this).jqGrid('setGridParam', {search: true, postData: {tanggal: App.tgl()}});
    },
  });

  $("#sd").jqGrid('navGrid','#pager_sd',{
      add: false,
      edit: false,
      del:false,
      search:false,
      refresh:true,
      refreshtext:'Refresh'
    });

});

  function HitungTotal(){
    var bud = $('#list').jqGrid('getRowData'),
        budlen = bud.length,
        sd = sdlen = [],
        idsdArr = [],
        idsd = oldidsd = 0,
        terima = keluar = akhir = rs = 0;

    for (var i = 0; i < budlen; i++){
      // hitung total penerimaan dan pengeluaran
      terima += parseFloat(bud[i].debet);
      keluar += parseFloat(bud[i].kredit);

      // hitung total per sumber dana
      idsd = bud[i].idsd
      if (idsd != oldidsd){
        idsdArr.push({id:idsd, total:0});
      }
      $.grep(idsdArr, function (value){
        if (value.id == idsd){
          value.total += bud[i].debet - bud[i].kredit;
        }
      });
      oldidsd = idsd;
    }
    App.total_belanja(keluar);
    App.total_terima(terima);

    if ($.isFunction( $('#sd').jqGrid ) && $('#sd').jqGrid('getRowData').length > 0 ) {
      sd = $('#sd').jqGrid('getRowData');
      sdlen = sd.length;

      // tampilkan total per sumber dana
      for (var j = 0; j < sdlen; j++){
        idsd = sd[j].id;
        rs = $.grep(idsdArr, function (value){
          if (value.id == idsd){
            return parseFloat(value.total);
          }
        });
        if (rs.length) {
          akhir = rs[0].total + parseFloat(sd[j].awal);
          $('#sd').jqGrid('setRowData', idsd, {akhir:akhir});
        }
      }
      akhir = $('#sd').jqGrid('getCol', 'akhir', '', 'sum');
      $('#sd').jqGrid('footerData','set', {'akhir':akhir});
    }
  }

  function edit_row(id){
    $(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
    $(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);
    last = id;
  };

  function after_save(id){
    var $grid = $(this);
    $grid.focus();
    HitungTotal();
  }

  function del_row(id){
    var rs = {},
        answer = false;

    rs = $(this).jqGrid('getRowData', id);
      answer = confirm('Hapus ' + rs.nobku + ' dari daftar?');

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

  var ModelBUD = function (){
    var self = this;
    self.modul = 'BUD';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses:0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_AKTIVITAS']) ? $data['ID_AKTIVITAS']:0 ?>');
    self.no = ko.observable('<?php echo isset($data['NOMOR']) ? $data['NOMOR']:'' ?>')
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']): date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'}
      });
    self.deskripsi = ko.observable(<?php echo isset($data['DESKRIPSI']) ? json_encode($data['DESKRIPSI']):'' ?>)
      .extend({
        required: {params: true, message: 'Deskripsi tidak boleh kosong'}
      });
    self.bud = ko.observable('<?php echo isset($data['ID_BUD']) ? $data['ID_BUD']:'' ?>');
    self.nm_bud = ko.observable('<?php echo isset($data['NAMA_BUD']) ? $data['NAMA_BUD']:'' ?>');
    self.total_belanja = ko.observable(0);
    self.total_terima = ko.observable(0);

    self.mode = ko.computed(function(){
      return self.id() > 0 ? 'edit' : 'new';
    });

    self.title = ko.computed(function(){
      return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
    });

    self.canPrint = ko.computed(function(){
      return self.akses_level() >= 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3;
    });

    self.errors = ko.validation.group(self);

  }

  var App = new ModelBUD();

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
    checkGridRow(grdrek, 'idr', null);
    // cek jika grid belum diisi
    if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
      errmsg.push('Belum ada Aktivitas yang di entri.');
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
        data['rincian'] = JSON.stringify($('#list').jqGrid('getRowData'));
        data['purge'] = purge;

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

        if (createNew) location.href = root+modul+'/form/';
      }
    });

  }

  App.init_grid = function(){
    var grdbud = $('#list'), grdsd = $('#sd');

    if (App.id() > 0){
      grdbud.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rinci/' + App.id(), 'datatype': 'json'});
      grdbud.trigger('reloadGrid');
    }
    else {
      grdbud.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
    grdsd.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/sumber_dana/' + App.id(), 'datatype': 'json'});
    grdsd.trigger('reloadGrid');
  }

  App.pilih_sts = function(){
    var $list = $('#list'),
        option = {multi:1, tanggal:App.tgl(), mode:'bud'},
        i = 0,
        rs = [];

    Dialog.pilihSTS(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        addRowSorted($list, {'id':'idr', sortName:['idsts']},
          {'id':newid, 'tipe':'STS', 'idsts':rs.id, 'no':rs.no, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek,
           debet:rs.nom, kredit:0, 'ket':rs.ket});
      }
      HitungTotal();
    });
  };

  App.pilih_sp2d = function(){
    var $list = $('#list'),
        option = {multi:1, tanggal:App.tgl(), mode:'bud'},
        i = debet = kredit = 0,
        rs = [];

    Dialog.pilihSP2D(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        debet = parseFloat(rs.pjk) + parseFloat(rs.pfk);
        kredit = rs.rincian = 0 ? rs.brutto : rs.rincian
        addRowSorted($list, {'id':'idsp2d', sortName:['idsp2d']},
          {'id':newid, 'tipe':'SP2D', 'idsp2d':rs.id, 'no':rs.no, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek,
           debet:debet, kredit:kredit, 'kdskpd':rs.kdskpd, 'nmskpd':rs.nmskpd, 'ket':rs.ket});
      }
      HitungTotal();
    });
  };

  App.pilih_cp = function(){
    var $list = $('#list'),
        option = {multi:1, tanggal:App.tgl(), mode:'bud'},
        i = 0,
        rs = [];

    Dialog.pilihCP(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        addRowSorted($list, {'id':'idr', sortName:['idcp']},
          {'id':newid, 'tipe':'Kontra Pos', 'idcp':rs.id, 'no':rs.no, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek,
           debet:rs.nom, kredit:0, 'ket':rs.ket});
      }
      HitungTotal();
    });
  };

  App.pilih_sd = function(){
    var $list = $('#list'),
        option = {multi:1},
        i = 0,
        rs = [];

    Dialog.pilihSumberdana(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        addRowSorted($list, {'id':'idsd', sortName:[]},
          {'id':newid, 'tipe':'Permindahbukuan', 'idsd':rs.id, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek, debet:0, kredit:0});
      }
      HitungTotal();
    });
  };

  App.pilih_spfk = function(){
    var $list = $('#list'),
        option = {multi:1, tanggal:App.tgl(), mode:'bud'},
        i = 0,
        rs = [];

    Dialog.pilihSPFK(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        addRowSorted($list, {'id':'idspfk', sortName:[idspfk]},
          {'id':newid, 'tipe':'Setor PFK', 'idspfk':rs.id, 'no':rs.no, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek,
           debet:0, kredit:rs.nom, 'ket':rs.ket});
      }
      HitungTotal();
    });
  };

  App.pilih_spjk = function(){
    var $list = $('#list'),
        option = {multi:1, tanggal:App.tgl(), mode:'bud'},
        i = 0,
        rs = [];

    Dialog.pilihSPJK(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        addRowSorted($list, {'id':'idspjk', sortName:[idspjk]},
          {'id':newid, 'tipe':'Setor Pajak', 'idspjk':rs.id, 'no':rs.no, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek,
           debet:0, kredit:rs.nom, 'kdskpd':rs.kdskpd, 'nmskpd':rs.nmskpd, 'ket':rs.ket});
      }
      HitungTotal();
    });
  };

  App.pilih_ssu = function(){
    var $list = $('#list'),
        option = {multi:1, tanggal:App.tgl(), mode:'bud'},
        i = 0,
        rs = [];

    Dialog.pilihSSU(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        newid--;
        addRowSorted($list, {'id':'idssu', sortName:['idssu']},
          {'id':newid, 'tipe':'Setor Sisa', 'idssu':rs.id, 'no':rs.no, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek,
           debet:rs.nom, kredit:0, 'ket':rs.ket});
      }
      HitungTotal();
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

  ko.applyBindings(App);
  setTimeout(function(){
    App.init_grid();
  }, 500)
</script>
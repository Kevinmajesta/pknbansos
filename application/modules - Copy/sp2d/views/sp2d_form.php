<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>sp2d/proses">
  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: no" >
      <div class="controls">
      <label class="control-label" for="no">Nomor</label>
      <input type="text" id="no" class="span3" data-bind="value: no" required />
      </div>
    </div>
    <div class="control-group pull-left span2" style="margin-left:20px" data-bind="validationElement: tgl" >
      <label class="control-label" for="tgl">Tanggal</label>
      <input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" required />
    </div>
    <div class="control-group pull-left span3" style="margin-left:20px" data-bind="validationElement: id_spm" >
      <label class="control-label" for="no_spm" >Pilih SPM</label>
      <div class="controls input-append">
        <input type="text" id="no_spm" class="span3" readonly="1" data-bind="value: no_spm, executeOnEnter: pilih_spm" />
        <span class="add-on" data-bind="visible: !isEdit() && canSave(), click: pilih_spm" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: id_skpd">
      <label class="control-label" for="kode_skpd">SKPD</label>
      <input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd" />
      <input type="text" class="span10" id="nama_skpd" readonly="1" data-bind="value: nm_skpd" />
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: id_sd" >
      <label class="control-label" for="nama_sd">Sumber Dana</label>
      <div class="controls input-append">
        <input type="text" class="span5" id="nama_sd" readonly="1" data-bind="value: nm_sd, executeOnEnter: pilih_sd" required />
        <span class="add-on" data-bind="click: pilih_sd, visible: canSave()" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
    <div class="control-group pull-right span6" data-bind="validationElement: id_pekas" >
      <label class="control-label" for="kdrek_pekas">Rekening Bendahara</label>
      <input type="text" class="span2" id="kdrek_pekas" readonly="1" data-bind="value: kd_pekas, executeOnEnter: pilih_sd_skpd" />
      <div class="controls span4 input-append">
        <input type="text" class="span4" id="nmrek_pekas" readonly="1" data-bind="value: nm_pekas, executeOnEnter: pilih_sd_skpd" />
        <span class="add-on" data-bind="click: pilih_sd_skpd, visible: canSave()" ><i class="icon-folder-open"></i></span>
      </div>
    </div>
  </div>

  <div class="controls-row">
    <div class="control-group pull-left" data-bind="validationElement: deskripsi" >
      <label for="deskripsi">Keterangan</label>
      <textarea rows="4" class="span8" id="deskripsi" data-bind="value: deskripsi" required ></textarea>
    </div>
    <div class="controls-group pull-right">
      <div class="controls-row">
        <label class="control-label" for="sisa_gu">SP2D UP/GU belum SPJ</label>
        <input type="text" id="sisa_gu" class="span3 currency" readonly="1" data-bind="numeralvalue: sisa_gu" />
      </div>
      <div class="controls-row">
        <label class="control-label" for="sisa_tu">SP2D TU belum SPJ</label>
        <input type="text" id="sisa_tu" class="span3 currency" readonly="1" data-bind="numeralvalue: sisa_tu" />
      </div>
    </div>
  </div>

  <ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
    <li class="active"><a href="#dspm">Detail SPM</a></li>
    <li data-bind="visible: keperluan() !== 'UP' "><a href="#rrekening">Rekening</a></li>
    <li data-bind="visible: keperluan() === 'LS' " ><a href="#rpajak">Pajak/Informasi</a></li>
  </ul>

  <div class="tab-content" style="height:360px;">
    <div class="tab-pane active" id="dspm">
      <div class="controls-row">
        <div class="control-group pull-left">
          <div class="controls-row">
            <div class="controls pull-left">
              <label class="control-label" for="tgl_spm">Tanggal SPM</label>
              <input type="text" id="tgl_spm" class="span2" readonly="1" data-bind="value: tgl_spm"/>
            </div>
          </div>

          <div class="controls-row" style="margin-bottom:10px">
            <label class="control-label" for="kaskpd">Kepala SKPD</label>
            <input type="text" id="kaskpd" class="span8" readonly="1" data-bind="attr : {'data-init': nm_kaskpd}, value: kaskpd, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Kepala SKPD', initSelection: init_select, query: query_pejabat_skpd }" />
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
          <input type="text" id="tgl_dpa" class="span2 datepicker" readonly="1" data-bind="value: tgl_dpa" />
        </div>
        <div class="controls span3 pull-left">
          <label class="control-label" for="pagu_dpa">Pagu DPA</label>
          <input type="text" id="pagu_dpa" class="span3 currency" readonly="1" data-bind="numeralvalue: pagu_dpa" />
        </div>
      </div>
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
    <div class="controls pull-left">
      <label style="float:left; margin-top:10px; margin-right:5px;" for="bud">BUD/Kuasa BUD</label>
      <input type="text" id="bud" class="span6" data-bind="attr : {'data-init': nm_bud}, value: bud, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat BUD/Kuasa BUD', initSelection: init_select, query: query_pejabat_daerah }" />
    </div>
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

<script type="text/javascript">

$(document).ready(function() {
  inisialisasi();

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });

  $('#myTab a').click(function(e) {
    e.preventDefault();
    $(this).tab('show');
  })

  $("#grd_rek").jqGrid({
    url: '',
    datatype: 'local',
    mtype: 'POST',
    colNames:['','','','Kode Kegiatan','Kode Rekening','Nama Rekening','Nominal'],
    colModel:[
        {name:'idr',hidden:true},
        {name:'idkeg',hidden:true},
        {name:'idr',hidden:true},
        {name:'kdkeg',width:250},
        {name:'kdrek',width:100},
        {name:'nmrek',width:250},
        {name:'nom', width:150, formatter:'currency', align:'right'},
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
        {name:'nmrek',width:300},
        {name:'nom', width:150, formatter:'currency', align:'right'},
    ],
    pager: '#pgr_pfk',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    width:'935',
    height:'230',
    loadComplete: function(){
      //TotalPotongan();
    },
  });
  $("#grd_pfk").jqGrid('bindKeys');
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
        {name:'nmrek',width:300},
        {name:'persen', width:100, formatter:'currency', align:'right'},
        {name:'nom', width:150, formatter:'currency', align:'right'},
        {name:'info',width:70, formatter:'checkbox', editoptions:{value:"1:0"}, formatoptions:{disabled:true}, align:'center'},
    ],
    pager: '#pgr_pjk',
    rowNum:-1,
    scroll:1,
    rownumbers:true,
    viewrecords: true,
    gridview: true,
    shrinkToFit:false,
    width:'935',
    height:'230',
    loadComplete: function(){
      TotalPajak();
    },
  });
  $("#grd_pjk").jqGrid('bindKeys');
  $("#grd_pjk").jqGrid('navGrid','#pgr_pjk',{
    add:false,
    del:false,
    edit:false,
    search:false,
    refresh:true,
  });

});

  function TotalRekening(){
    var total = $('#grd_rek').jqGrid('getCol', 'nom', '', 'sum');
    App.total_rek(total);
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

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });

  var ModelSP2D = function (){
    var self = this;
    self.modul = 'SP2D';
    self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_AKTIVITAS']) ? $data['ID_AKTIVITAS'] : 0 ?>');
    self.no = ko.observable(<?php echo isset($data['NOMOR']) ? json_encode($data['NOMOR']) : '' ?>)
      .extend({
        required: {params: true, message: 'Nomor tidak boleh kosong'},
        maxLength: {params: 50, message: 'Nomor tidak boleh melebihi 50 karakter'},
      });
    self.nominal = ko.observable(<?php echo isset($data['NOMINAL']) ? $data['NOMINAL'] : 0 ?>);
    self.id_skpd = ko.observable(<?php echo isset($data['ID_SKPD']) ? $data['ID_SKPD'] : '' ?>)
      .extend({
        required: {params: true, message: 'SKPD belum dipilih'}
      });
    self.kd_skpd = ko.observable(<?php echo isset($data['KODE_SKPD_LKP']) ? json_encode($data['KODE_SKPD_LKP']) : '' ?>);
    self.nm_skpd = ko.observable(<?php echo isset($data['NAMA_SKPD']) ? json_encode($data['NAMA_SKPD']) : '' ?>);
    self.deskripsi = ko.observable(<?php echo isset($data['DESKRIPSI']) ? json_encode($data['DESKRIPSI']): '' ?>)
      .extend({
        required: {params: true, message: 'Deskripsi tidak boleh kosong'}
      });
    self.id_spm = ko.observable(<?php echo isset($data['ID_SPM']) ? $data['ID_SPM'] : '' ?>)
      .extend({
        required: {params: true, message: 'SPM belum dipilih'}
      });
    self.no_spm = ko.observable(<?php echo isset($data['NOMOR_SPM']) ? json_encode($data['NOMOR_SPM']) : '' ?>);
    self.tgl_spm = ko.observable('<?php echo isset($data['TANGGAL_SPM']) ? format_date($data['TANGGAL_SPM']) : '' ?>');
    self.tgl_spm_veri = ko.observable('<?php echo isset($data['TANGGAL_VERIFIKASI_SPM']) ? format_date($data['TANGGAL_VERIFIKASI_SPM']) : '' ?>');
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal tidak boleh kosong'},
        dateMustGreaterEqual: {params:self.tgl_spm_veri, message: 'Tanggal SP2D harus lebih besar atau sama dengan tanggal Verifikasi SPM' },
      });
    self.id_spp = ko.observable(<?php echo isset($data['ID_SPP']) ? $data['ID_SPP'] : '' ?>);
    self.id_sd = ko.observable(<?php echo isset($data['ID_SUMBER_DANA']) ? $data['ID_SUMBER_DANA'] : '' ?>)
      .extend({
        required: {params: true, message: 'Sumber Dana belum dipilih'}
      });;
    self.nm_sd = ko.observable(<?php echo isset($data['NAMA_SUMBER_DANA']) ? json_encode($data['NAMA_SUMBER_DANA']) : '' ?>);
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
    self.tgl_dpa = ko.observable('<?php echo isset($data['TANGGAL_DPA']) ? format_date($data['TANGGAL_DPA']) : date('d/m/Y') ?>');
    self.pagu_dpa = ko.observable('<?php echo isset($data['PAGU_DPA']) ? $data['PAGU_DPA'] : 0 ?>');
    self.bk = ko.observable(<?php echo isset($data['ID_BK']) ? $data['ID_BK'] : '' ?>);
    self.nm_bk = ko.observable(<?php echo isset($data['BK_NAMA']) ? json_encode($data['BK_NAMA']) : '' ?>);
    self.kaskpd = ko.observable(<?php echo isset($data['ID_KEPALA_SKPD']) ? $data['ID_KEPALA_SKPD'] : '' ?>);
    self.nm_kaskpd = ko.observable(<?php echo isset($data['KASKPD_NAMA']) ? json_encode($data['KASKPD_NAMA']) : '' ?>);
    self.bud = ko.observable(<?php echo isset($data['ID_BUD']) ? $data['ID_BUD'] : '' ?>);
    self.nm_bud = ko.observable(<?php echo isset($data['BUD_NAMA']) ? json_encode($data['BUD_NAMA']) :'' ?>);
    self.id_pekas = ko.observable(<?php echo isset($data['ID_REKENING_PEKAS']) ? $data['ID_REKENING_PEKAS'] : '' ?>)
      .extend({
        required: {params: true, message: 'Rekening Bendahara belum dipilih'}
      });;
    self.kd_pekas = ko.observable(<?php echo isset($data['KODE_REKENING_PEKAS']) ? json_encode($data['KODE_REKENING_PEKAS']) : '' ?>);
    self.nm_pekas = ko.observable(<?php echo isset($data['NAMA_REKENING_PEKAS']) ? json_encode($data['NAMA_REKENING_PEKAS']) : '' ?>);
    self.batas_gu = ko.observable(0);
    self.batas_tu = ko.observable(0);
    self.total_rek = ko.observable(0);
    //self.total_pfk = ko.observable(0);
    self.total_pjk = ko.observable(0);
    self.total = ko.computed(function(){
      return self.keperluan() === 'UP' ? self.nominal() : self.total_rek() - /*self.total_pfk() -*/ self.total_pjk();
    });

    self.id_skpd.subscribe(function(){
      self.updatePejabat();
      self.updateSumberDana();
      self.getSisaSKPD();
    });

    self.tgl.subscribe(function(){
      self.getSisaSKPD();
    });

    self.beban.subscribe(function(new_beban){
      var $grdrek = $('#grd_rek');
      new_beban === 'BL' ? $grdrek.jqGrid('showCol', 'kdkeg') : $grdrek.jqGrid('hideCol', 'kdkeg');
    });

    self.sisa_gu = ko.computed(function(){
      return self.keperluan === 'GU' ? self.batas_gu() + self.total() : self.batas_gu();
    });

    self.sisa_tu = ko.computed(function(){
      return self.keperluan === 'TU' ? self.batas_tu() + self.total() : self.batas_tu();
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

  var App = new ModelSP2D();

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
    var grd_rekening = $('#grd_rek'),
        //grd_pfk = $('#grd_pfk'),
        grd_pjk = $('#grd_pjk');

    if (App.id_spp() > 0){
      grd_rekening.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/rekening/' + App.id_spp(), 'datatype': 'json'});
      grd_rekening.trigger('reloadGrid');
      //grd_pfk.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/potongan/' + App.id_spp(), 'datatype': 'json'});
      //grd_pfk.trigger('reloadGrid');
      grd_pjk.jqGrid('setGridParam', {'url': '<?php echo base_url(); ?>spp/pajak/' + App.id_spp(), 'datatype': 'json'});
      grd_pjk.trigger('reloadGrid');
    }
    else {
      grd_rekening.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      //grd_pfk.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
      grd_pjk.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
    }
  }

  App.pilih_spm = function(){
    if (!App.canSave() || App.isEdit()) { return; }
    var option = {multi:0, tanggal:App.tgl(), id_skpd:App.id_skpd()};
    Dialog.pilihSPM(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      $.ajax({
        url: '<?php echo base_url().$modul."/spm/"?>' + rs.id,
        type: 'post',
        dataType: 'json',
        data: {},
        success: function(res, xhr){
          App.id_spp(res.id_spp);
          App.id_spm(res.id_spm);
          App.no_spm(res.no_spm);
          App.tgl_spm(res.tgl_spm);
          App.tgl_spm_veri(res.tgl_spm_veri);
          App.nominal(res.nominal);
          App.deskripsi(res.deskripsi);
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
          App.pagu_dpa(parseFloat(res.pagu_dpa));
          App.kaskpd(res.kaskpd);
          App.nm_kaskpd(res.nm_kaskpd);
          App.bk(res.bk);
          App.nm_bk(res.nm_bk);
          App.init_grid();
        }
      });
    });
  }

  App.getSisaSKPD = function(){
    var arrspd = $('#list2').jqGrid('getCol', 'idspd');

    data = {
      id: App.id(),
      id_skpd: App.id_skpd(),
      tanggal: App.tgl(),
      beban: App.beban(),
      keperluan: App.keperluan(),
    };

    $.ajax({
      type: "post",
      dataType: "json",
      url: root+modul+'/sisa_skpd',
      data: data,
      success: function(res) {
        App.batas_gu(parseFloat(res.sisa_gu));
        App.batas_tu(parseFloat(res.sisa_tu));
      },
    });
  }

  App.pilih_sd = function(){
    if (!App.canSave()) { return; }
    var option = {multi:0};
    Dialog.pilihSumberdana(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_sd(rs.id);
      App.nm_sd(rs.nama);
    });
  }

  App.pilih_sd_skpd = function(){
    if (!App.canSave()) { return; }
    var option = {multi:0, id_skpd: App.id_skpd()};
    Dialog.pilihSumberdanaSKPD(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      App.id_pekas(rs.idrek);
      App.kd_pekas(rs.kdrek);
      App.nm_pekas(rs.nmrek);
    });
  }

  App.updateSumberDana = function(){
    App.default_sumberdana(App.id_skpd, App.id_sd, App.nm_sd);
    App.default_sumberdana_skpd(App.id_skpd, App.id_pekas, App.kd_pekas, App.nm_pekas);
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

  App.default_sumberdana = function(skpd, id_sd, nm_sd, id_rek, kd_rek, nm_rek){
    id_rek = id_rek || null;
    kd_rek = kd_rek || null;
    nm_rek = nm_rek || null;

    $.ajax({
      url: "<?php echo base_url();?>pilih/sumberdana_def",
      type: 'POST',
      dataType: 'json',
      data: {skpd:skpd},
      success: function(res){
        if (res && res.results[0]){
          id_sd(res.results[0].id);
          nm_sd(res.results[0].nama);
          id_rek ? id_rek(res.results[0].idrek) : '';
          kd_rek ? kd_rek(res.results[0].kdrek) : '';
          nm_rek ? nm_rek(res.results[0].nmrek) : '';
        }
      }
    });
  }

  App.default_sumberdana_skpd = function(skpd, id_rek, kd_rek, nm_rek){
    $.ajax({
      url: "<?php echo base_url();?>pilih/sumberdana_skpd_def",
      type: 'POST',
      dataType: 'json',
      data: {skpd:skpd},
      success: function(res){
        if (res && res.results[0]){
          id_rek(res.results[0].idrek);
          kd_rek(res.results[0].kdrek);
          nm_rek(res.results[0].nmrek);
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
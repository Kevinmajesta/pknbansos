<div class="row-fluid">
  <div class="span12">
    <fieldset>
      <legend id="bc" data-bind="text: title"></legend>
    </fieldset>
    
    <div class="row-fluid">
      <form id="frm" method="post" action="<?php echo base_url(); ?>proposal/<?php echo $link_proses;?>">
        <fieldset class="span3">
          <div class="controls-row">
            <div class="control-group pull-left span10" data-bind="validationElement: no" >
              <label class="control-label" for="no">Nomor Proposal</label>
              <input type="text" class="span12" id="no" data-bind="value: no" required />
            </div>
          </div>
          <div class="controls-row" >
            <div class="control-group pull-left span10" data-bind="validationElement: tgl" >
              <label class="control-label" for="tgl">Tanggal Proposal Masuk</label>
              <input type="text" class="span12 datepicker" id="tgl" data-bind="value: tgl" required />
            </div>
          </div>
          <div class="controls-row" >
            <div class="control-group pull-left span10" data-bind="validationElement: bantuan" >
              <label class="control-label" for="bantuan">Jenis Bantuan</label>
              <select id="bantuan" class="span12" data-bind="options: opsiBantuan, optionsValue:'value', optionsText:'text', value: bantuan"></select>
            </div>
          </div>
          <div class="controls-row" >
            <div class="control-group pull-left span10" data-bind="validationElement: nom_aju" >
              <label class="control-label" for="nom_aju">Nominal Pengajuan</label>
              <input type="text" class="span12 currency" id="nom_aju" data-bind="numeralvalue: nom_aju" required />
            </div>
          </div>
        </fieldset>

        <fieldset class="span9">
          <div class="controls-row" >
            <div class="control-group pull-left span5" data-bind="validationElement: kategori" >
              <label class="control-label" for="kategori">Kategori Pemohon</label>
              <select id="kategori" class="span12" data-bind="options: opsiKategori, optionsValue:'value', optionsText:'text', value: kategori"></select>
            </div>
          </div>
          <fieldset>
            <legend></legend>
            <div class="controls-row" data-bind="visible: nik_vis">
              <div class="control-group">
                <label class="control-label" for="nik" data-bind="text: nik_text"></label>
                <input type="text" class="span10" id="nik" data-bind="value: nik, enable: nik_vis"  />
              </div>
            </div>
            <div class="controls-row" data-bind="visible: nama_pmh_vis">
              <div class="control-group">
                <label class="control-label" for="nama_pmh" data-bind="text: nama_pmh_text"></label>
                <input type="text" class="span10" id="nama_pmh" data-bind="value: nama_pmh, enable: nama_pmh_vis"  />
              </div>
            </div>
            <div class="controls-row" data-bind="visible: alamat_pmh_vis">
              <div class="control-group">
                <label class="control-label" for="alamat_pmh" data-bind="text: alamat_pmh_text"></label>
                <textarea class="span10" rows="3" id="alamat_pmh" data-bind="value: alamat_pmh, enable: alamat_pmh_vis" ></textarea>
              </div>
            </div>
            <div class="controls-row" data-bind="visible: tgl_lhr_vis">
              <div class="control-group pull-left span4">
                <label class="control-label" for="tgl_lhr" data-bind="text: tgl_lhr_text"></label>
                <input type="text" class="span10 datepicker" id="tgl_lhr" data-bind="value: tgl_lhr, enable: tgl_lhr_vis"  />
              </div>
            </div>
            <div class="controls-row" data-bind="visible: nama_pimp_vis">
              <div class="control-group">
                <label class="control-label" for="nama_pimp" data-bind="text: nama_pimp_text"></label>
                <input type="text" class="span10" id="nama_pimp" data-bind="value: nama_pimp, enable: nama_pimp_vis"  />
              </div>
            </div>
            <div class="controls-row" data-bind="visible: bidang_vis">
              <div class="control-group">
                <label class="control-label" for="bidang" data-bind="text: bidang_text">Pekerjaan</label>
                <input type="text" class="span10" id="bidang" data-bind="value: bidang, enable: bidang_vis" />
              </div>
            </div>
            <div class="controls-row">
              <div class="control-group" >
                <label class="control-label" for="ringkasan">Ringkasan Proposal</label>
                <textarea class="span10" rows="5" id="ringkasan" data-bind="value: ringkasan"></textarea>
              </div>
            </div>
          </fieldset>
        </fieldset>
      </form>

      <div class="controls-row pull-right">
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
        <input type="button" id="kirim" value="Kirim" class="btn btn-primary" data-bind="enable: canKirim, click: kirim" />
        <input type="button" id="back" value="Kembali" class="btn btn-primary" data-bind="click: back" />
      </div>
    </div>
  </div>
</div>

<div id="dialogProposal" style="display: none;">
  <div class="controls-row">
    <div class="control-group pull-left" >
      <label class="control-label" for="pejabat_skpd">Pejabat Penerima</label>
      <select name="pejabat_skpd" id="pejabat_skpd" class="span4">
      </select>
    </div>
  </div>
  <div class="controls-row">
    <div class="control-group pull-left" >
      <label class="control-label" for="jabatan_skpd">Jabatan</label>
      <input type="text" id="jabatan_skpd" name="jabatan_skpd" class="span4" readonly="1" />
    </div>
  </div>
  <div class="controls-row">
    <div class="control-group pull-left" >
      <label class="control-label" for="nip_pejabat_skpd">NIP</label>
      <input type="text" id="nip_pejabat_skpd" name="nip_pejabat_skpd" class="span4" readonly="1"/>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
  $.getJSON('<?php echo base_url();?>proposal/get_jabatan_skpd/', function(json) {
    $('<option value="0" class="options">Pilih Pejabat SKPD</option>').appendTo('#pejabat_skpd');
    $.each(json.rows, function(id,val){
      $("#jabatan_skpd").val('');
      $("#nip_pejabat_skpd").val('');
      $('<option value="' + val.ID_PEJABAT_SKPD+ '" class="options">' + val.NAMA_PEJABAT+'</option>').appendTo('#pejabat_skpd');
    });
  });

  $('#pejabat_skpd').click(function(){
    var jabat = $('#pejabat_skpd').val();
    $("#jabatan_skpd").val();
    $("#nip_pejabat_skpd").val();
    $.getJSON('<?php echo base_url();?>proposal/get_data_pejabat_skpd/'+jabat, function(json) {
      $("#jabatan_skpd").val(json.jabatan);
      $("#nip_pejabat_skpd").val(json.nip);
    });
  });

  $('.currency')
    .blur(function(){ $(this).formatCurrency(fmtCurrency); })
    .focus(function(){ $(this).toNumber(fmtCurrency); });
    
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
  
});

  function dialogProposal(doc){
    $('#dialogProposal').dialog({
        title:'Filter',
        height:300,
        width:350,
        modal:true,
        autoOpen:false,
        closeOnEscape:true,
        buttons: {
          "Tutup":
          {
            text: 'Tutup',
            class: 'btn primary',
            click: function () {
              $(this).dialog('close');
            }
          },
          "Pilih":
          {
            text: 'Cetak',
            class: 'btn primary',
            click: function () {
              var pejabat = $('#pejabat_skpd').val();
              var jabatan  = encodeURIComponent($("#jabatan_skpd").val());
              var nama  = encodeURIComponent($("#pejabat_skpd option:selected").text());
              var nip    = encodeURIComponent($("#nip_pejabat_skpd").val());
              
              if (pejabat > 0) {
                preview({"tipe":"form","format":doc,"id": App.id(),"jabatan":jabatan,"nama":nama,"nip":nip})
                $(this).dialog('close');
              } else {
                alert('Pilih Pejabat SKPD Terlebih Dahulu');
              }
            }
          }
        }
    });
  }

  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });
    
  var JenisBantuan = function(value, text) {
    this.value = value;
    this.text = text;
  }

  var Kategori = function(value, text) {
    this.value = value;
    this.text = text;
  }

  var ModelProposal = function (){
    var self = this;
    self.modul = 'Proposal';
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID_PROPOSAL']) ? $data['ID_PROPOSAL'] : 0 ?>');
    self.no = ko.observable('<?php echo isset($data['NOMOR']) ? $data['NOMOR'] : '' ?>')
      .extend({
        required: {params: true, message: 'Nomor Proposal tidak boleh kosong'},
        maxLength: {params: 100, message: 'Nomor Proposal tidak boleh melebihi 100 karakter'},
      });
    self.tgl = ko.observable('<?php echo isset($data['TANGGAL']) ? format_date($data['TANGGAL']) : date('d/m/Y') ?>')
      .extend({
        required: {params: true, message: 'Tanggal Proposal Masuk tidak boleh kosong'},
      });
    self.opsiBantuan = ko.observableArray([
          <?php foreach ($jenis_bantuan as $key=>$val) { echo "new JenisBantuan('".$val['JENIS_BANTUAN']."', '".$val['JENIS_BANTUAN']."'),"; } ?> ]);
    self.bantuan = ko.observable('<?php echo isset($data['JENIS_BANTUAN']) ? $data['JENIS_BANTUAN'] : '' ?>');
    self.opsiKategori = ko.observableArray([]);
    self.kategoris = ko.computed(function() {
        if (self.bantuan() == '' || self.bantuan() == undefined)
        {
          return self.opsiKategori.removeAll();
        }
        else
        {
          $.ajax({
            url: root+modul+'/get_kategori/',
            type: 'post',
            dataType: 'json',
            data: {bantuan : self.bantuan()},
            success: function(res, xhr){
              if (res != null) {
                self.opsiKategori.removeAll();
                $.each(res, function(i,val) {
                  return self.opsiKategori.push(new Kategori(val,val));
                });
              }
            }
          });
        }
      });
    self.kategori = ko.observable('<?php echo isset($data['KATEGORI']) ? $data['KATEGORI'] : '' ?>');
    self.nom_aju = ko.observable('<?php echo isset($data['NOMINAL_DIAJUKAN']) ? $data['NOMINAL_DIAJUKAN'] : 0 ?>')
      .extend({
        required: {params: true, message: 'Nominal Pengajuan tidak boleh kosong'},
      });
    self.nik = ko.observable('');
    self.nama_pmh = ko.observable('');
    self.alamat_pmh = ko.observable('');
    self.tgl_lhr = ko.observable('<?php echo date('d/m/Y') ?>');
    self.nama_pimp = ko.observable('');
    self.bidang = ko.observable('');
    self.ringkasan = ko.observable('');
    self.posted = ko.observable(<?php echo isset($data['POSTED']) ? $data['POSTED'] : 0;?>);
    
    self.nik_vis = ko.observable(true);
    self.nik_text = ko.observable('');
    self.nama_pmh_vis = ko.observable(true);
    self.nama_pmh_text = ko.observable('');
    self.alamat_pmh_vis = ko.observable(true);
    self.alamat_pmh_text = ko.observable('');
    self.tgl_lhr_vis = ko.observable(true);
    self.tgl_lhr_text = ko.observable('');
    self.nama_pimp_vis = ko.observable(true);
    self.nama_pimp_text = ko.observable('');
    self.bidang_vis = ko.observable(true);
    self.bidang_text = ko.observable('');
        
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
      return self.akses_level() >= 2 && self.mode() === 'edit';
    });

    self.canSave = ko.computed(function(){
      return self.akses_level() >= 3 && self.posted() === 0;
    });
    
    self.canKirim = ko.computed(function(){
      return self.posted() === 0 && self.id() > 0;
    });

    self.errors = ko.validation.group(self);

  }

  var App = new ModelProposal();

  setTimeout(function(){
    App.kategori('<?php echo isset($data['KATEGORI']) ? $data['KATEGORI'] : '' ?>');
    App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
    App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
    App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
    App.tgl_lhr('<?php echo isset($data['TANGGAL_LAHIR']) ? format_date($data['TANGGAL_LAHIR']) : date('d/m/Y') ?>');
    App.nama_pimp('<?php echo isset($data['NAMA_PIMPINAN']) ? $data['NAMA_PIMPINAN'] : '' ?>');
    App.bidang('<?php echo isset($data['BIDANG']) ? $data['BIDANG'] : '' ?>');
    App.ringkasan('<?php echo isset($data['RINGKASAN']) ? $data['RINGKASAN'] : '' ?>');
  },2000);

  App.kategori.subscribe(function() {
    if (App.bantuan() === 'Bantuan Sosial') {
      switch (App.kategori()) {
        case 'Individu/Keluarga':
              App.nik_vis(true); App.nik_text('NIK'); App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Pemohon'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(true); App.tgl_lhr_text('Tanggal Lahir'); App.tgl_lhr('<?php echo isset($data['TANGGAL_LAHIR']) ? format_date($data['TANGGAL_LAHIR']) : date('d/m/Y') ?>');
              App.nama_pimp_vis(false); App.nama_pimp_text(''); App.nama_pimp('');
              App.bidang_vis(true); App.bidang_text('Pekerjaan'); App.bidang('<?php echo isset($data['BIDANG']) ? $data['BIDANG'] : '' ?>');
              break;
        case 'Masyarakat':
              App.nik_vis(true); App.nik_text('NIK'); App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Pemohon'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(false); App.tgl_lhr_text(''); App.tgl_lhr('');
              App.nama_pimp_vis(false); App.nama_pimp_text(''); App.nama_pimp('');
              App.bidang_vis(false); App.bidang_text(''); App.bidang('');
              break;
        case 'Lembaga Non Pemerintahan':
              App.nik_vis(true); App.nik_text('NIK'); App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Lembaga'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat Lembaga'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(true); App.tgl_lhr_text('Tanggal Pendirian'); App.tgl_lhr('<?php echo isset($data['TANGGAL_LAHIR']) ? format_date($data['TANGGAL_LAHIR']) : date('d/m/Y') ?>');
              App.nama_pimp_vis(true); App.nama_pimp_text('Nama Ketua Lembaga'); App.nama_pimp('<?php echo isset($data['NAMA_PIMPINAN']) ? $data['NAMA_PIMPINAN'] : '' ?>');
              App.bidang_vis(true); App.bidang_text('Bidang'); App.bidang('<?php echo isset($data['BIDANG']) ? $data['BIDANG'] : '' ?>');
              break;
      }
    } else if (App.bantuan() === 'Hibah') {
      switch (App.kategori()) {
        case 'Pemerintah':
              App.nik_vis(false); App.nik_text(''); App.nik('');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Instansi'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat Kantor'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(false); App.tgl_lhr_text(''); App.tgl_lhr('');
              App.nama_pimp_vis(false); App.nama_pimp_text('Nama Kepala Satuan Kerja'); App.nama_pimp('');
              App.bidang_vis(false); App.bidang_text(''); App.bidang('');
              break;
        case 'Pemerintah Daerah Lain':
              App.nik_vis(false); App.nik_text(''); App.nik('');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Pemerintah Daerah'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat Kantor'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(false); App.tgl_lhr_text(''); App.tgl_lhr('');
              App.nama_pimp_vis(false); App.nama_pimp_text('Nama Kepala Daerah'); App.nama_pimp('');
              App.bidang_vis(false); App.bidang_text(''); App.bidang('');
              break;
        case 'Perusahaan Daerah':
              App.nik_vis(true); App.nik_text('NIK'); App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Perusahaan'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat Kantor'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(true); App.tgl_lhr_text('Tanggal Pendirian'); App.tgl_lhr('<?php echo isset($data['TANGGAL_LAHIR']) ? format_date($data['TANGGAL_LAHIR']) : date('d/m/Y') ?>');
              App.nama_pimp_vis(true); App.nama_pimp_text('Nama Pimpinan'); App.nama_pimp('<?php echo isset($data['NAMA_PIMPINAN']) ? $data['NAMA_PIMPINAN'] : '' ?>');
              App.bidang_vis(true); App.bidang_text('Bidang'); App.bidang('<?php echo isset($data['BIDANG']) ? $data['BIDANG'] : '' ?>');
              break;
        case 'Masyarakat':
              App.nik_vis(true); App.nik_text('NIK'); App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Pemohon'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(false); App.tgl_lhr_text(''); App.tgl_lhr('');
              App.nama_pimp_vis(true); App.nama_pimp_text('Nama Ketua Kelompok'); App.nama_pimp('<?php echo isset($data['NAMA_PIMPINAN']) ? $data['NAMA_PIMPINAN'] : '' ?>');
              App.bidang_vis(true); App.bidang_text('Bidang'); App.bidang('<?php echo isset($data['BIDANG']) ? $data['BIDANG'] : '' ?>');
              break;
        case 'Organisasi Kemasyarakatan':
              App.nik_vis(true); App.nik_text('NIK'); App.nik('<?php echo isset($data['NIK']) ? $data['NIK'] : '' ?>');
              App.nama_pmh_vis(true); App.nama_pmh_text('Nama Organisasi Massa'); App.nama_pmh('<?php echo isset($data['NAMA_PEMOHON']) ? $data['NAMA_PEMOHON'] : '' ?>');
              App.alamat_pmh_vis(true); App.alamat_pmh_text('Alamat Sekretariat'); App.alamat_pmh('<?php echo isset($data['ALAMAT_PEMOHON']) ? $data['ALAMAT_PEMOHON'] : '' ?>');
              App.tgl_lhr_vis(true); App.tgl_lhr_text('Tanggal Pendirian'); App.tgl_lhr('<?php echo isset($data['TANGGAL_LAHIR']) ? format_date($data['TANGGAL_LAHIR']) : date('d/m/Y') ?>');
              App.nama_pimp_vis(true); App.nama_pimp_text('Nama Ketua Organisasi'); App.nama_pimp('<?php echo isset($data['NAMA_PIMPINAN']) ? $data['NAMA_PIMPINAN'] : '' ?>');
              App.bidang_vis(true); App.bidang_text('Bidang'); App.bidang('<?php echo isset($data['BIDANG']) ? $data['BIDANG'] : '' ?>');
              break;
      }
    } else {
      App.nik_vis(false); App.nik_text(''); App.nik('');
      App.nama_pmh_vis(false); App.nama_pmh_text(''); App.nama_pmh('');
      App.alamat_pmh_vis(false); App.alamat_pmh_text(''); App.alamat_pmh('');
      App.tgl_lhr_vis(false); App.tgl_lhr_text(''); App.tgl_lhr('');
      App.nama_pimp_vis(false); App.nama_pimp_text(''); App.nama_pimp('');
      App.bidang_vis(false); App.bidang_text(''); App.bidang('');
    }
  });

  App.prev = function(){
    show_prev(modul, App.id());
  }

  App.next = function(){
    show_next(modul, App.id());
  }

  App.print = function(data, event){
    var doc = event.target.getAttribute('doc-type') || 'pdf';
    dialogProposal(doc);
    $('#dialogProposal').dialog('open');
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
          setTimeout(function(){
            if (res.id) App.id(res.id);
            App.nama_pmh(res.nama_pmh);
            App.alamat_pmh(res.alamat_pmh);
            App.tgl_lhr(res.tgl_lhr);
            App.nama_pimp(res.nama_pimp);
            App.bidang(res.bidang);
          }, 1000);
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
  
  App.kirim = function(){
    $.ajax({
      url: root+modul+'/kirim/',
      type: 'post',
      dataType: 'json',
      data: {id: App.id()},
      success: function(res, xhr){
        if(res.isSuccess == true) App.posted(1);
        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
      }
    });
  }

  ko.applyBindings(App);
  
</script>
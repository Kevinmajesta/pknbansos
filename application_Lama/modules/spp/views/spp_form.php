<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url(); ?>spp/proses">
	<div class="controls-row">
		<div class="control-group pull-left" data-bind="validationElement: no" >
			<label class="control-label" for="no">Nomor</label>
			<input type="text" id="no" class="span3" data-bind="value: no" required />
		</div>
		<div class="control-group pull-left" style="margin-left:20px" data-bind="validationElement: tgl" >
			<label class="control-label" for="tgl">Tanggal</label>
			<input type="text" id="tgl" class="span2 datepicker" data-bind="value: tgl" required />
		</div>
		<div class="controls pull-left span3" data-bind="visible: keperluan() === 'LS' && beban() !== 'BTL' ">
			<label class="control-label" for="kontrak">Nomor Kontrak</label>
			<div class="controls input-append">
				<input type="text" id="kontrak" name="kontrak" class="span3" readonly="1" data-bind="value: kontrak, executeOnEnter: pilih_kontrak" />
				<span class="add-on" data-bind="click: pilih_kontrak, visible: canSave()" ><i class="icon-folder-open"></i></span>
			</div>
		</div>
		<div class="controls pull-left span3" data-bind="visible: keperluan() === 'GU' || keperluan() === 'GU NIHIL' ">
			<label class="control-label" for="spj">SPJ</label>			
			<!--<input type="text" id="spj" class="span3" data-bind="attr : {'data-init': nm_spj}, value: spj, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Nomor SPJ', initSelection: init_select, query: query_spj }, change: ambil_spj" />-->
			<input type="text" id="spj" name="spj" class="span2" hidden="true" data-bind="value: spj, visible:false"/>
			<div class="controls input-append">				
				<input type="text" id="nm_spj" name="nm_spj" class="span3" readonly="1" data-bind="value: nm_spj, executeOnEnter: pilih_spj" />
				<span class="add-on" data-bind="click: pilih_spj, visible:  !isEdit() && !isSKPD && canSave()" ><i class="icon-folder-open"></i></span>
			</div>			
		</div>
	</div>

	<div class="controls-row">
		<div class="control-group pull-left" data-bind="validationElement: id_skpd">
			<label class="control-label" for="kode_skpd">SKPD</label>
			<input type="text" class="span2" id="kode_skpd" readonly="1" data-bind="value: kd_skpd, executeOnEnter: pilih_skpd" />
			<div class="controls span6 input-append">
				<input type="text" class="span6" id="nama_skpd" readonly="1" data-bind="value: nm_skpd, executeOnEnter: pilih_skpd" />
				<span class="add-on" data-bind="visible: !isEdit() && !isSKPD && canSave(), click: pilih_skpd" ><i class="icon-folder-open"></i></span>
			</div>
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
		<div class="controls pull-right" data-bind="visible: keperluan() != 'UP' " >
			<label class="control-label" for="sisa_gu">Sisa SPJ</label>
			<input type="text" id="sisa_gu" class="span3 currency" readonly="1" data-bind="numeralvalue: sisa_gu" />
		</div>
	</div>

	<ul class="nav nav-tabs" id="myTab" style="margin-bottom:10px">
		<li class="active"><a href="#dspp">Detail SPP</a></li>
		<li data-bind="visible: $.inArray(keperluan(), ['PP'])  < 0"><a href="#rspd">Referensi SPD</a></li>
		<li data-bind="visible: $.inArray(keperluan(), ['UP', 'PP']) < 0 && beban() === 'BL'" ><a href="#rkegiatan">Kegiatan</a></li>
		<li data-bind="visible: keperluan() !== 'UP' "><a href="#rrekening">Rekening</a></li>
		<li data-bind="visible: keperluan() === 'LS' " ><a href="#rpajak">Pajak/Informasi</a></li>
	</ul>

	<div class="tab-content" style="height:375px;">
		<div class="tab-pane active" id="dspp">
			<div class="controls-row">
				<div class="control-group pull-left">
					<div class="controls-row">
						<label class="control-label" for="bk">Bendahara Pengeluaran</label>
						<input type="text" id="bk" class="span8" data-bind="attr : {'data-init': nm_bk}, value: bk, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Bendahara Pengeluaran', initSelection: init_select, query: query_pejabat_skpd }" />
					</div>

					<div class="controls-row" style="margin-top:10px; margin-bottom:10px">
						<label class="control-label" for="pptk">PPTK</label>
						<input type="text" id="pptk" class="span8" data-bind="attr : {'data-init': nm_pptk}, value: pptk, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat PPTK', initSelection: init_select, query: query_pejabat_skpd }" />
					</div>

					<div class="controls-row">
						<div class="controls pull-left">
							<label class="control-label" for="penerima">Pihak Ketiga</label>
							<input type="text" id="penerima" class="span7" data-bind="value: penerima" />
						</div>
					</div>
				</div>

				<div class="control-row pull-right">
					<div class="control-group pull-left" data-bind="validationElement: keperluan" style="width:100px" >
						<label class="control-label" >Keperluan</label>
						<label class="radio">
							<input type="radio" data-bind="disable: isEdit, checked: keperluan" value="UP" />UP
						</label>
						<label class="radio">
							<input type="radio" data-bind="disable: isEdit, checked: keperluan" value="LS" />LS
						</label>
						<label class="radio">
							<input type="radio" data-bind="disable: isEdit, checked: keperluan" value="GU" />GU
						</label>
						<label class="radio">
							<input type="radio" data-bind="disable: isEdit, checked: keperluan" value="GU NIHIL"  />GU Nihil
						</label>
					</div>
					<div class="control-group pull-right" data-bind="validationElement: jenis_beban" style="width:180px" >
						<label class="control-label" >Beban</label>
						<label class="radio">
							<input type="radio" data-bind="disable: isEdit(), checked: jenis_beban" value="BTL" />Beban Tidak Langsung
						</label>
						<label class="radio" data-bind="visible: $.inArray(keperluan(), ['GU', 'GU NIHIL'])  < 0">
							<input type="radio" data-bind="disable: isEdit(), checked: jenis_beban" value="BL"/>Beban Langsung
						</label>
					</div>
				</div>
			</div>

			<div class="controls-row">
				<div class="controls pull-left">
					<label class="control-label" for="bank">Nama Bank</label>
					<input type="text" id="bank" class="span4" data-bind="value: bank" >
				</div>
				<div class="controls span3 pull-left">
					<label class="control-label" for="norek">Rekening Bank</label>
					<input type="text" id="norek" class="span3" data-bind="value: norek" >
				</div>
				<div class="controls span4 pull-left">
					<label class="control-label" for="npwp">NPWP</label>
					<input type="text" id="npwp" class="span4" data-bind="value: npwp" />
				</div>
			</div>

			<div class="controls-row">
				<div class="controls pull-left">
					<label class="control-label" for="no_dpa">Nomor DPA</label>
					<input type="text" id="no_dpa" class="span4" data-bind="value: no_dpa" />
				</div>
				<div class="controls span2 pull-left">
					<label class="control-label" for="tgl_dpa">Tanggal DPA SKPD</label>
					<input type="text" id="tgl_dpa" class="span2 datepicker" data-bind="value: tgl_dpa" />
				</div>
				<div class="controls span3 pull-left">
					<label class="control-label" for="pagu_dpa">Pagu DPA</label>
					<input type="text" id="pagu_dpa" class="span3 currency" data-bind="numeralvalue: pagu_dpa" />
				</div>
			</div>
		</div>

		<div class="tab-pane" id="rspd">
			<table id="grd_spd"></table>
			<div id="pgr_spd"></div>
			<div class="controls-row pull-right" style="margin-top:5px;">
				<label style="float:left; margin-top:10px; margin-right:5px;" for="total_spd_sisa">Sisa SPD Keseluruhan</label>
				<input type="text" id="total_spd_sisa" class="span3 currency" readonly="1" data-bind="numeralvalue: total_spd" />
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
		<div class="controls pull-left">
		  <label style="float:left; margin-top:10px; margin-right:5px;" for="pa">Pengguna Anggaran</label>
		  <input type="text" id="pa" class="span6" data-bind="attr : {'data-init': nm_pa}, value: pa, select2: { minimumInputLength: 0, containerCss: {'margin-left':'0px'}, placeholder: 'Pejabat Pengguna Anggaran', initSelection: init_select, query: query_pejabat_skpd }" />
		</div>
		<div class="control-group pull-right" data-bind="validationElement: total">
		  <label style="float:left; margin-top:10px; margin-right:5px;" for="total">Total</label>
		  <input type="text" id="total" class="span3 currency" data-bind="numeralvalue: total, attr: {readonly: App.keperluan() == 'UP' ? null : 1}" />
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

<script >
var lastspd, lastrek, lastpjk, lastpfk,
    newid = 0,sisaAll=0,
    purge_spd = [], purge_rek = [], purge_pjk = [], purge_pfk = [];

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
		colNames:['', '', 'Nomor SPD','Tanggal SPD','Nominal'],
		colModel:[
			{name:'idr', hidden:true},
			{name:'idspd', hidden:true},
			{name:'no', width:200, sortable:false},
			{name:'tgl',width:80, formatter:'date', align:'center', sortable:false},
			{name:'nom',width:150, sortable:false, formatter:'currency', align:'right'}
		],
		pager: '#pgr_spd',
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
			var idarr = $(this).jqGrid('getDataIDs');
			GetSisaSPD(idarr);
			TotalSPD();
		},
		onSelectRow: function(id){
			if(id && id!==lastspd){
				$(this).restoreRow(lastspd);
				lastspd=id;
			}
		},
	});
	$("#grd_spd").jqGrid('bindKeys');
	$("#grd_spd").jqGrid('navGrid','#pgr_spd',{
		add:true,
		addtext: 'Tambah',
		addfunc:add_spd,
		del:true,
		deltext: 'Hapus',
		delfunc:del_row,
		edit:false,
		search:false,
		refresh:false,
	});
	$("#grd_spd").jqGrid('navButtonAdd', "#pgr_spd", {
		caption: "Hitung Sisa", title: "Hitung Ulang Sisa", buttonicon: "ui-icon-refresh",
		onClickButton: refreshSisaSPD
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
		loadonce:true,
		width:'935',
		height:'210',
		loadComplete: function(){
			var idarr = $(this).jqGrid('getDataIDs');
			GetSisaKegiatan(idarr);
			if (App.mode() === 'new') App.getDPA();
		},
	});
	$("#grd_keg").jqGrid('bindKeys');
	$("#grd_keg").jqGrid('navGrid','#pgr_keg',{
		add:true,
		addtext: 'Tambah',
		addfunc:add_rincian,
		del:true,
		deltext: 'Hapus',
		delfunc:del_row,
		edit:false,
		edittext: 'Ubah',
		search:false,
		refresh:false,
		refreshtext:'Refresh'
	});
	$("#grd_keg").jqGrid('navButtonAdd', "#pgr_keg", {
		caption: "Hitung Sisa", title: "Hitung Ulang Sisa", buttonicon: "ui-icon-refresh",
		onClickButton: refreshSisaKeg
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
			{name:'kdkeg',width:150, sortable:false},
			{name:'kdrek',width:100, sortable:false},
			{name:'nmrek',width:300, sortable:false},
			{name:'nom',width:150, sortable:false, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
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
		loadonce:true,
		width:'935',
		height:'210',
		loadComplete: function(){
			var idarr = $(this).jqGrid('getDataIDs');
			GetSisaRekening(idarr);
			TotalRekening();
		},
		onSelectRow: function(id){
			if(id && id!==lastrek){
				$(this).restoreRow(lastrek);
				lastrek=id;
			}
		},
		ondblClickRow: edit_row,
	});
	$("#grd_rek").jqGrid('bindKeys', { "onEnter": edit_row});
	$("#grd_rek").jqGrid('navGrid','#pgr_rek',{
		add:true,
		addtext: 'Tambah',
		addfunc:add_rincian,
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
	$("#grd_rek").jqGrid('navButtonAdd', "#pgr_rek", {
		caption: "Hitung Sisa", title: "Hitung Ulang Sisa", buttonicon: "ui-icon-refresh",
		onClickButton: refreshSisaRek
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
    height:'210',
    loadComplete: function(){
      TotalPotongan();
    },
    onSelectRow: function(id){
      if(id && id!==lastpfk){
         $(this).restoreRow(lastpfk);
         lastpfk = id;
      }
    },
    ondblClickRow: edit_row,
  });
  $("#grd_pfk").jqGrid('bindKeys', { "onEnter": edit_row});
  $("#grd_pfk").jqGrid('navGrid','#pgr_pfk',{
    add:true,
    addtext: 'Tambah',
    addfunc:add_pfk,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    edit:false,
    search:false,
    refresh:false,
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
			{name:'nom', width:150, editable:true, editrules: {number:true, required: true}, formatter:'currency', align:'right'},
			{name:'info',width:70, editable:true, edittype:'checkbox', formatter:'checkbox', editoptions:{value:"1:0"}, formatoptions:{disabled:true}, align:'center'},
		],
		pager: '#pgr_pjk',
		rowNum:1000000,
		scroll:true,
		rownumbers:true,
		viewrecords: true,
		gridview: true,
		shrinkToFit:false,
		loadonce:true,
		width:'935',
		height:'210',
		loadComplete: function(){
			TotalPajak();
		},
		onSelectRow: function(id){
			if(id && id!==lastpjk){
				$(this).restoreRow(lastpjk);
				lastpjk = id;
			}
		},
		ondblClickRow: edit_row,
	});
	$("#grd_pjk").jqGrid('bindKeys', { "onEnter": edit_row});
	$("#grd_pjk").jqGrid('navGrid','#pgr_pjk',{
		add:true,
		addtext: 'Tambah',
		addfunc:add_pjk,
		del:true,
		deltext: 'Hapus',
		delfunc:del_row,
		edit:false,
		search:false,
		refresh:false,
	});
  
});

	get_spd = function(data){
		var grd_spd = $('#grd_spd'),
		load = grd_spd.jqGrid('getGridParam', 'loadComplete'),
		deferSPD = new $.Deferred();
		grd_spd.jqGrid('setGridParam', {'loadComplete' : function(){ deferSPD.resolve()} });
		grd_spd.trigger('reloadGrid');
		grd_spd.jqGrid('setGridParam', {'loadComplete' : load});
		return deferSPD.promise();
	}

	function cekDialog(){
		var id_skpd = App.id_skpd(),
        beban = App.beban(),
        result = true;
		if ((undefined === beban) || ('' === id_skpd)){
			var message = id_skpd ? '' : 'SKPD';
			message += (undefined === beban ? (message ? ' dan ' : '') + 'Beban' : '');
			message += ' belum dipilih.';
			$.pnotify({
				title: 'Perhatian',
				text: message,
				type: 'warning',
			});		
			result = false;
		}
		return result;
	}

	function add_spd(){
		if (!cekDialog()) return;
		var $grd = $(this),
		option = {multi:1, id:App.id(), tanggal:App.tgl(), id_skpd:App.id_skpd(), beban:App.beban(), keperluan:App.keperluan()};
		Dialog.pilihSPD(option, function(obj, select){
			for (i = 0; i < select.length; i++){
				var rs = $(obj).jqGrid('getRowData', select[i].id);
				newid --;
				var aDate = rs.tgl.split('/');
				var bbDate = new Date(aDate[2], aDate[1]-1, aDate[0]);
				addRowSorted($grd, {'id':'idr', 'sortName':['no']}, {'idr':newid, 'idspd':rs.id, 'no':rs.no, 'tgl':bbDate, 'nom':rs.nom});
				GetSisaSPD([newid]);
			}
			TotalSPD();
		});
	}

	function add_rincian(){
		if (!cekDialog()) return;
		var $grdkeg = $('#grd_keg'),
			$grdrek = $('#grd_rek'),
			arrspd = $('#grd_spd').jqGrid('getCol', 'idspd');

		if (App.beban() === 'BL'){
			option = {multi:0, id_skpd:App.id_skpd(), tanggal:App.tgl(), id:App.id(), arrspd:arrspd, keperluan:App.keperluan(), mode:'spp'};
			Dialog.pilihKegiatanAktivitas(option, function(obj, select){
				id_keg = select[0].id;
				rskeg = $(obj).jqGrid('getRowData', select[0].id);

				// tampilkan dialog rekening sesuai kegiatan yang dipilih
				option = {multi:1, id_skpd:App.id_skpd(), beban:App.beban(), keperluan:App.keperluan(), id_kegiatan:id_keg, tanggal:App.tgl(), id:App.id(), arrspd:arrspd, mode:'spp'};
				Dialog.pilihRekening(option, function(obj, select){
					if (select.length > 0){
						addRowSorted($grdkeg, {'id':'idkeg', 'sortName':['kdkeg']}, {'idkeg':rskeg.id, 'kdkeg':rskeg.kodes, 'nmkeg':rskeg.nama});
						GetSisaKegiatan([rskeg.id]);
					}
					for (i = 0; i < select.length; i++){
						rsrek = $(obj).jqGrid('getRowData', select[i].id);
						newid = newid - 1;
						addRowSorted($grdrek, {'id':'idr', 'sortName':['kdkeg', 'kdrek']}, {'idr':newid, 'idrek':rsrek.idrek, 'kdrek':rsrek.kdrek, 'nmrek':rsrek.nmrek, 'idkeg':rskeg.id, 'kdkeg':rskeg.kodes, 'nmkeg':rskeg.nama});
						GetSisaRekening([newid]);
					}
				});
			});
		}
		else {
			var option = {multi:1, id_skpd:App.id_skpd(), beban:App.jenis_beban(), keperluan:App.keperluan(), tanggal:App.tgl(), id:App.id(), arrspd:arrspd, mode:'spp'};
			Dialog.pilihRekening(option, function(obj, select){
				for (i = 0; i < select.length; i++){
					rsrek = $(obj).jqGrid('getRowData', select[i].id);
					newid = newid - 1;
					addRowSorted($grdrek, {'id':'idr', 'sortName':['kdrek']}, {'idr':newid, 'idrek':rsrek.idrek, 'kdrek':rsrek.kdrek, 'nmrek':rsrek.nmrek});
					GetSisaRekening([newid]);
				}
			});
		}
	}

	function add_pfk(){
		var $grid = $(this),
        option = {multi:1},
        i = 0,
        rs = [];

		Dialog.pilihPotongan(option, function(obj, select){
			for (i = 0; i < select.length; i++){
				var rs = $(obj).jqGrid('getRowData', select[i].id);
				addRowSorted($grid, {'id':'idrek', 'sortName':['kdrek']}, {'idrek':rs.id, 'kdrek':rs.kode, 'nmrek':rs.nama});
			}
		});
	}

	function add_pjk(){
		var $grid = $(this),
			option = {multi:1},
			i = 0,
			rs = [];

		Dialog.pilihPajak(option, function(obj, select){
			for (i = 0; i < select.length; i++){
				var rs = $(obj).jqGrid('getRowData', select[i].id);
				var nom = App.total_rek() * rs.persen / 100;
				addRowSorted($grid, {'id':'idp', 'sortName':['kdp']}, {'idp':rs.id, 'idrek':rs.idrek, 'kdp':rs.nama, 'kdrek':rs.kdrek, 'nmrek':rs.nmrek, 'persen':rs.persen, 'nom':nom,'info':'1'});
			}
		});
	}
  
	function TotalSPD(){
		var total = $('#grd_spd').jqGrid('getCol', 'nom', '', 'sum');
		var total_rek = $('#grd_rek').jqGrid('getCol', 'nom', '', 'sum');
		//App.total_spd(total-total_rek);
		//App.total_spd(sisaAll);
		App.total_spd(total);
		App.GetSisaSKPD();
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
		if (total != 0){
			App.total_rek(total);
		}

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
				id: App.id(),
				idr: idarr[i],
				id_skpd: App.id_skpd(),
				tanggal: App.tgl(),
				beban: App.beban(),
				arrspd: rs.idspd
			};

			$.ajax({
				type: "post",
				dataType: "json",
				url: root+modul+'/sisa_spd',
				data: data,
				success: function(res) {
					$('#grd_spd').jqGrid('setRowData', res.idr, {sisa:res.sisa, sisa_all:res.sisa_all});
					TotalSPD();
					sisaAll = sisaAll+res.sisa;
				},
			});
		} 
		TotalSPD();
	}

	function GetSisaKegiatan(idarr){
		var len = idarr.length,
        arrspd = $('#grd_spd').jqGrid('getCol', 'idspd'),
        rs = data = {};

		for (var i = 0; i < len; i++){
			rs = $('#grd_keg').jqGrid('getRowData', idarr[i]);

			data = {
				id: App.id(),
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
				url: root+modul+'/sisa_kegiatan',
				data: data,
				success: function(res) {
					$('#grd_keg').jqGrid('setRowData', res.idr, {batas:res.sisa});
					HitungSisaKegiatan(res.idr);
				},
			});
		}
	}

	function GetSisaRekening(idarr){
		var len = idarr.length,
			arrspd = $('#grd_spd').jqGrid('getCol', 'idspd'),
			rs = {},  data = {};

		for (var i = 0; i < len; i++){
			rs = $('#grd_rek').jqGrid('getRowData', idarr[i]);

			data = {
				id: App.id(),
				idr: idarr[i],
				id_skpd: App.id_skpd(),
				tanggal: App.tgl(),
				keperluan: App.keperluan(),
				beban: App.beban(),
				id_spj: (App.spj())?App.spj():0,
				id_kegiatan: rs.idkeg,
				id_rekening: rs.idrek,
				arrspd: arrspd,
			};

			$.ajax({
				type: "post",
				dataType: "json",
				url: root+modul+'/sisa_rekening',
				data: data,
				success: function(res) {
					
					if(App.keperluan() =='LS')
						$('#grd_rek').jqGrid('setRowData', res.idr, {batas:res.sisa});
					else if(App.keperluan() =='GU' || App.keperluan() =='GU NIHIL'){
						//if(App.id() > 0)
							$('#grd_rek').jqGrid('setRowData', res.idr, {batas:(res.spj-res.sisa_gu)});
					}
					HitungSisaRekening(res.idr);
					TotalRekening();
				},
			});
		}
	}
	
	function after_save(id){
		$(this).focus();
		switch ($(this).attr('id')) {
			case 'grd_rek' : TotalRekening();TotalSPD(); HitungSisaRekening(id); break;
			case 'grd_pjk' : TotalPajak(); break;
			//case 'grd_pfk' : TotalPotongan(); break;
		}
	}

	function edit_row(id){
		var last;
		switch( $(this).attr('id') ) {
			case 'grd_rek' : last = lastrek; break;
			case 'grd_pjk' : last = lastpjk; break;
			//case 'grd_pfk' : last = lastpfk; break;
		}

		$(this).jqGrid('saveRow', last, null, 'clientArray', null, after_save);
		$(this).jqGrid('editRow', id, true, null, null, 'clientArray', null, after_save);

		switch( $(this).attr('id') ) {
			case 'grd_rek' : lastrek = id; break;
			case 'grd_pjk' : lastpjk = id; break;
			//case 'grd_pfk' : lastpfk = id; break;
		}
	};

	function del_row(id){
		var rs = {},
			answer = false,
			kode = '';

		rs = $(this).jqGrid('getRowData', id);
		switch ($(this).attr('id')){
		  case 'grd_spd' : kode = rs.no; break;
		  case 'grd_keg' : kode = rs.kdkeg; break;
		  case 'grd_rek' : kode = rs.kdkeg !== '' ? rs.kdkeg + '.' + rs.kdrek : rs.kdrek; break;
		  //case 'grd_pfk' : kode = rs.kdrek; break;
		  case 'grd_pjk' : kode = rs.kdrek; break;
		}
		answer = confirm('Hapus ' + kode + ' dari daftar?');

		if(answer == true){
		  if ($(this).attr('id') === 'grd_spd'){
			purge_spd.push(id);
			$(this).jqGrid('delRowData', id);
			TotalSPD();
		  }
		  else if ($(this).attr('id') === 'grd_keg'){
			$(this).jqGrid('delRowData', id);
			row = $('#grd_rek').jqGrid('getRowData');
			removed = $.grep(row, function(value){
			  return value.idkeg === id;
			});
			for (i = 0; i <= removed.length - 1; i++){
			  purge_rek.push(removed[i].idr);
			  $('#grd_rek').jqGrid('delRowData', removed[i].idr);
			}
			TotalRekening();
		  }
		  else if ($(this).attr('id') === 'grd_rek'){
			idkeg = $('#grd_rek').jqGrid('getRowData', id).idkeg;
			purge_rek.push(id);
			$(this).jqGrid('delRowData', id);
			datarek = $('#grd_rek').jqGrid('getRowData');
			cek = $.grep(datarek, function(value){
			  return value.idkeg === idkeg;
			});
			if (cek.length === 0){
			  $('#grd_keg').jqGrid('delRowData', idkeg);
			}
			TotalRekening();
		  }
		  /*else if ($(this).attr('id') === 'grd_pfk'){
			purge_pfk.push(id);
			$(this).jqGrid('delRowData', id);
			TotalPotongan();
		  }*/
		  else if ($(this).attr('id') === 'grd_pjk'){
			purge_pjk.push(id);
			$(this).jqGrid('delRowData', id);
			TotalPajak();
		  }
		}
	};

	function refreshSisaSPD(){
		var idarr = $('#grd_spd').jqGrid('getDataIDs');
		GetSisaSPD(idarr);
		TotalSPD();
	}

	function refreshSisaKeg(){
		var idarr = $('#grd_keg').jqGrid('getDataIDs');
		GetSisaKegiatan(idarr);
	}

	function refreshSisaRek(){
		var idarr = $('#grd_rek').jqGrid('getDataIDs');
		GetSisaRekening(idarr);
		TotalRekening();
	}


	$("#check_no").click(function(){
		if ($('#check_no').is(':checked')) {
			$('#no').attr('disabled',false);
		} else {
			$('#no').attr('disabled',true);
		}
	});

	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: 'error',
	});

  var ModelSPP = function (){
    var self = this;
    self.modul = 'SPP';
    self.isSKPD = <?php echo $id_skpd == 0 ? 'false' : 'true'; ?>;
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses:0 ?>);
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
	  
	self.config_kodeskpd = ko.observable(<?php echo isset($config_kodeskpd) ? json_encode($config_kodeskpd) : '' ?>);
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
    self.beban = ko.observable('<?php echo isset($data['BEBAN']) ? $data['BEBAN']:'' ?>')
      .extend({
        required: {params: true, message: 'Beban belum dipilih'},
      });
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
    self.kontrak = ko.observable('<?php echo isset($data['NO_KONTRAK']) ? $data['NO_KONTRAK'] : '' ?>');
    self.norek = ko.observable(<?php echo isset($data['NO_REKENING_BANK']) ? json_encode($data['NO_REKENING_BANK']) : '' ?>);
    self.npwp = ko.observable(<?php echo isset($data['NPWP']) ? json_encode($data['NPWP']) : '' ?>);
    self.no_dpa = ko.observable(<?php echo isset($data['NO_DPA']) ? json_encode($data['NO_DPA']) : '' ?>);
    self.tgl_dpa = ko.observable('<?php echo isset($data['TANGGAL_DPA'])? format_date($data['TANGGAL_DPA']) : date('d/m/Y') ?>');
    self.pagu_dpa = ko.observable(<?php echo isset($data['PAGU_DPA'])?$data['PAGU_DPA'] : 0 ?>);
    self.bk = ko.observable(<?php echo isset($data['ID_BK']) ? $data['ID_BK'] : '' ?>);
    self.nm_bk = ko.observable(<?php echo isset($data['BK_NAMA']) ? json_encode($data['BK_NAMA']) : '' ?>);
    self.pptk = ko.observable(<?php echo isset($data['ID_PPTK']) ? $data['ID_PPTK'] : '' ?>);
    self.nm_pptk = ko.observable(<?php echo isset($data['PPTK_NAMA']) ? json_encode($data['PPTK_NAMA']) : '' ?>);
	self.spj = ko.observable(<?php echo isset($data['ID_SPJ']) ? $data['ID_SPJ'] : '' ?>);
    self.nm_spj = ko.observable(<?php echo isset($data['NOMOR_SPJ']) ? json_encode($data['NOMOR_SPJ']) : '' ?>);
    self.pa = ko.observable(<?php echo isset($data['ID_PA']) ? $data['ID_PA'] : '' ?>);
    self.nm_pa = ko.observable(<?php echo isset($data['PA_NAMA']) ? json_encode($data['PA_NAMA']) : '' ?>);
    self.batas_pagu = ko.observable(0);
    self.batas_gu = ko.observable(0);
    self.label_sisa_pagu = ko.observable('Sisa Anggaran');
    self.total_spd = ko.observable(0);
    self.total_rek = ko.observable(0);
    //self.total_pfk = ko.observable(0);
    self.total_pjk = ko.observable(0);
    self.total = ko.observable(<?php echo isset($data['NOMINAL']) ? $data['NOMINAL'] : 0 ?>)
      .extend({
        required: {params: true, message: 'Total tidak boleh kosong'},
        notEqual: {params: 0, message: 'Total tidak boleh bernilai 0', onlyIf: function(){return (self.keperluan() != 'UP') }},
      });
	
    self.id_skpd.subscribe(function(){
      if (self.keperluan() === 'UP') return;
      var spp = $('#grd_rek'),
          spd = $('#grd_spd'),
          //pfk = $('#grd_pfk'),
          pjk = $('#grd_pjk');
      self.hapusRincian(spd);
      self.hapusRincian(spp);
      //self.hapusRincian(pfk);
      self.hapusRincian(pjk);
      self.updatePejabat();
      self.getDPA();
      self.GetSisaSKPD();
    });

    self.tgl.subscribe(function(){
      self.GetSisaSKPD();
    });
	
    self.beban.subscribe(function(new_beban){
      var $grdrek = $('#grd_rek');
      new_beban === 'BL' ? $grdrek.jqGrid('showCol', 'kdkeg') : $grdrek.jqGrid('hideCol', 'kdkeg');
	  //GetSisaSKPD();
    });
	
	self.keperluan.subscribe(function(new_keperluan){
		
		var grid = $("#grd_rek"),gid = $.jgrid.jqID(grid[0].id),$tadd = $('#add_' + gid),$tdel = $('#del_' + gid);
		var $grdrek = $('#grd_rek');
		if(new_keperluan === 'GU' || new_keperluan === 'GU NIHIL'){ 
			$tadd.hide();
			$tdel.hide();
			App.ambil_spj();
		}
		else if(new_keperluan === 'LS'){
			$tadd.show();
			$tdel.show();
		}		
    }); 

    self.jenis_beban.subscribe(function(){
      if (self.jenis_beban() === 'GJ'){
        self.beban('BTL');
        self.keperluan('LS');
      }
      else {
        self.beban(self.jenis_beban());
      }

      if (self.keperluan() === 'UP') return;
      var spp = $('#grd_rek'),
          spd = $('#grd_spd'),
          //pfk = $('#grd_pfk'),
          pjk = $('#grd_pjk');

      self.hapusRincian(spd);
      self.hapusRincian(spp);
      //self.hapusRincian(pfk);
      self.hapusRincian(pjk);
      self.getDPA();
      self.GetSisaSKPD();
    });

    self.keperluan.subscribe(function(){
      switch (self.keperluan()) {
        case 'UP' : self.jenis_beban('BTL'); break;
        case 'LS' : self.jenis_beban('BL'); break;
        case 'GU' : self.jenis_beban('BTL'); break;
        case 'GU NIHIL' : self.jenis_beban('BTL'); break;
      }

      self.getDPA();
      self.GetSisaSKPD();
    });

    self.hitung_total = ko.computed(function(){
      if( self.keperluan() != 'UP') {
        self.total( self.total_rek() - self.total_pjk());
      }
      return self.total();
    });

    self.sisa_pagu = ko.computed(function(){
      return self.batas_pagu() - self.total();
      //return self.batas_pagu() - self.total_rek();
    });
    self.sisa_pagu.extend({
//      min: {param: 0, message: 'Sisa Pagu tidak boleh negatif', onlyIf: function(){return (self.keperluan() != 'UP') } }
    });

    self.sisa_gu = ko.computed(function(){
		return self.keperluan() === 'LS' ? self.batas_gu() : (self.batas_gu() - self.total());
		//return self.batas_gu() - self.total();
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

	var App = new ModelSPP();

	App.prev = function(){
		show_prev(modul, App.id());
	}

	App.next = function(){
		show_next(modul, App.id());
	}

	App.print = function(data, event){
		var doc = event.target.getAttribute('doc-type') || 'pdf';
		preview({"tipe":"form", "format":doc, "id": App.id(), "keperluan": App.keperluan(), "jenis_beban": App.jenis_beban()});
	}

	App.back = function(){
		location.href = root+modul;
	}

	App.formValidation = function(){
    var grdspd = $('#grd_spd'),
        grdkeg = $('#grd_keg'),
        grdrek = $('#grd_rek'),
        errmsg = [];
    
		// cek jika ada baris di grid belum disimpan
		checkGridRow(grdspd, 'idr', after_save);
		checkGridRow(grdrek, 'idr', after_save);
		
		// cek jika grid belum diisi
		if (grdspd.jqGrid('getGridParam', 'reccount') === 0) {			
			// error jika keperluan selain UP/PP
			if ( $.inArray( App.keperluan(), ['UP', 'PP'] ) < 0 ) {
				errmsg.push('Belum ada SPD yang di entri.');
			}
		}
    
		// hanya dicek jika keperluan bukan UP
		if (App.keperluan() != 'UP') {
			if (App.beban() === 'BL' && grdkeg.jqGrid('getGridParam', 'reccount') === 0) {
				errmsg.push('Belum ada Kegiatan yang di entri.');
			}
			if (grdrek.jqGrid('getGridParam', 'reccount') === 0) {
				errmsg.push('Belum ada Rekening yang di entri.');
			}
		  
			// cek jika ada sisa yang negatif
			if (checkGridMinus(grdspd, 'sisa')){
				errmsg.push('Ada SPD yang sisanya minus');
			}
			if (checkGridMinus(grdkeg, 'sisa')){
				errmsg.push('Ada Kegiatan yang sisanya minus');
			}
			
			if (checkGridMinus(grdrek, 'sisa')){
				errmsg.push('Ada Rekening yang sisanya minus');
			}
		}
	
		if (App.sisa_pagu() < 0){
			errmsg.push('Sisa Anggaran tidak boleh minus');
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
        data['rincian'] = JSON.stringify($('#grd_rek').jqGrid('getRowData'));
        data['spd'] = JSON.stringify($('#grd_spd').jqGrid('getRowData'));
        data['pfk'] = JSON.stringify($('#grd_pfk').jqGrid('getRowData'));
        data['pjk'] = JSON.stringify($('#grd_pjk').jqGrid('getRowData'));
        data['purge_spd'] = purge_spd;
        data['purge_rek'] = purge_rek;
        //data['purge_pfk'] = purge_pfk;
        data['purge_pjk'] = purge_pjk;

		$.ajax({
			url: $frm.attr('action'),
			type: 'post',
			dataType: 'json',
			data: data,
			success: function(res, xhr){
				if (res.isSuccess){
					if (res.id) App.id(res.id);
					App.init_grid();
					//$('#no').val(res.nomor);
					$('#no').attr('disabled',true);
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
		var grd_spd = $('#grd_spd'),
			grd_kegiatan = $('#grd_keg'),
			grd_rekening = $('#grd_rek'),
			gid = $.jgrid.jqID(grd_rekening[0].id),
			$tadd = $('#add_' + gid),
			$tdel = $('#del_' + gid),
			grd_pajak = $('#grd_pjk');

		if (App.id() > 0){
			grd_spd.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/spd/' + App.id(), 'datatype': 'json'});
			get_spd().then(function(){	
				//grd_spd.trigger('reloadGrid');
				if (App.beban() === 'BL'){
					grd_kegiatan.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/kegiatan/' + App.id(), 'datatype': 'json'});
					grd_kegiatan.trigger('reloadGrid');
				}
				grd_rekening.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/rekening/' + App.id(), 'datatype': 'json'});
				grd_rekening.trigger('reloadGrid');
				//grd_pfk.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/potongan/' + App.id(), 'datatype': 'json'});
				//grd_pfk.trigger('reloadGrid');
				grd_pajak.jqGrid('setGridParam', {'url': '<?php echo base_url().$modul; ?>/pajak/' + App.id(), 'datatype': 'json'});
				grd_pajak.trigger('reloadGrid');
				App.GetSisaSKPD();				
				if(App.keperluan() === 'GU' || App.keperluan() === 'GU NIHIL'){ 
					$tadd.hide();
					$tdel.hide();
				}
				else{
					$tadd.show();
					$tdel.show();
				}
			});
		}
		else {
			data = {pagu:'0',nominal:'0',sisa:'0'};
			grd_spd.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
			grd_kegiatan.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
			grd_rekening.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
			//grd_pfk.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
			grd_pajak.jqGrid('setGridParam', {'url': '', 'datatype': 'local'});
		}
	}

	App.pilih_skpd = function(){
		if (!App.canSave() || App.isSKPD || App.isEdit()) { return; }
		var option = {multi:0};
		Dialog.pilihSKPD(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.id_skpd(rs.id);
			App.kd_skpd(rs.kode);
			App.nm_skpd(rs.nama);
		});		
	}
	
	App.ambil_spj = function(){
		if(App.spj()){
			$('#grd_rek').jqGrid('clearGridData')
			$('#grd_rek').jqGrid('setGridParam', {'url': '<?php echo base_url($modul) ?>/ambil_data_spj/' + App.spj()+'/'+App.id_skpd(), 'datatype': 'json'});
			$('#grd_rek').trigger('reloadGrid');
		}
	}

	App.pilih_kontrak = function(){
		if (!App.canSave() || App.isEdit()) { return; }
		if (!App.canSave()) { return; }
		var option = {multi:0};
		Dialog.pilihKontrak(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.kontrak(rs.no);
			App.penerima(rs.pimpinan);
			App.bank(rs.bank);
			App.norek(rs.norek);
			App.npwp(rs.npwp);
		});
	}
	
	App.pilih_spj = function(){
		if (!App.canSave() || App.isEdit()) { return; }
		if (!App.canSave()) { return; }
		var option = {multi:0,id_skpd:App.id_skpd()};
		Dialog.pilihSPJSPP(option, function(obj, select){
			var rs = $(obj).jqGrid('getRowData', select[0].id);
			App.spj(rs.id_aktivitas);
			App.nm_spj(rs.nomor);
			App.ambil_spj();
		});
	}

	App.getDPA = function(){
		var arrkeg = $('#grd_keg').jqGrid('getCol', 'idkeg');
		data = {
			id_skpd: App.id_skpd(),
			beban: App.beban(),
			arrkeg: arrkeg,
		};
		$.ajax({
			type: "post",
			dataType: "json",
			url: root+modul+'/dpa',
			data: data,
			success: function(res) {
				App.no_dpa(res.no_dpa);
				if (res.tgl_dpa) App.tgl_dpa(res.tgl_dpa);
				App.pagu_dpa(parseFloat(res.pagu_dpa));
			},
		});
	}

	App.GetSisaSKPD = function(){
		var arrspd = $('#grd_spd').jqGrid('getCol', 'idspd');

		data = {
			id: App.id(),
			id_skpd: App.id_skpd(),
			tanggal: App.tgl(),
			keperluan: App.keperluan(),
			spj: App.spj(),
			beban: App.beban(),
			arrspd: arrspd,
		};

		$.ajax({
			type: "post",
			dataType: "json",
			url: root+modul+'/sisa_skpd',
			data: data,
			success: function(res) {
				//App.label_sisa_pagu(res.lbl_sisa_pagu);
				App.batas_pagu(parseFloat(res.sisa_pagu));
				App.batas_gu(parseFloat(res.sisa_gu));
			},
		});
	}

	App.updatePejabat = function(){
		App.default_pejabat_skpd(App.id_skpd, App.bk, App.nm_bk, 'BK');
		App.default_pejabat_skpd(App.id_skpd, App.pptk, App.nm_pptk, 'PPTK');
		App.default_pejabat_skpd(App.id_skpd, App.pa, App.nm_pa, 'PA');
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
		TotalSPD();
		TotalRekening();
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
	
	App.query_spj = function(option){
		var id_skpd = App.id_skpd();
		$.ajax({
			url: "<?php echo base_url()?>pilih/spj",
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
				//App.ambil_spj();
			}
		});
		App.ambil_spj();
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
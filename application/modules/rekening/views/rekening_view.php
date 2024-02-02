	<fieldset>
		<legend>Daftar Rekening</legend>
	</fieldset>

  <div class="row">
    <div class="span8 pull-left">
      <div id="filter" class="form-inline"></div>
      <div id="apply" style="margin-bottom:10px;"></div>
    </div>
    <div class="input-append pull-right">
      <input type="text" class="span4" id="q" />
      <span class="add-on"><i class="icon-search"></i></span>
      <span class="add-on" id="searchAdvance"><i class="icon-play"></i></span><!---- search advance  --->
    </div>
  </div>

	<table id="grid"></table>
	<div id="pager"></div>
	<script type="text/javascript">
	$(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		var KODE_REKENING;
		var TIPE;
		var KELOMPOK;
		var JENIS;
		var OBJEK;
		var RINCIAN;
		var SUB1;
		var SUB2;
		var SUB3;
		var ID_REKENING_x;
		var ID_PARENT_REKENING_x;
		var ID_MASTER_REKENING_x;
		var KODE_REKENING_x;
		var KODE_REKENING_y;
		var NAMA_REKENING_x;
		var TIPE_x;
		var KELOMPOK_x;
		var JENIS_x;
		var OBJEK_x;
		var RINCIAN_x;
		var RINCIAN_y;
		var SUB1_x;
		var SUB2_x;
		var SUB3_x;
		var KATEGORI_x;
		var PAGU_x;
		
		$("#grid").jqGrid({
			url:'<?php echo base_url()?>rekening/get_daftar',
			editurl:'<?php echo base_url()?>rekening/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','ID PARENT REKENING','TIPE','KELOMPOK','JENIS','OBJEK','RINCIAN','SUB 1','SUB 2','SUB 3','KODE REKENING','NAMA REKENING','KATEGORI REKENING','PAGU'],
			colModel:[
				{name:'id',index:'ID_REKENING',width:5,search:false,hidden:true},
				{name:'ID_PARENT_REKENING',index:'ID_PARENT_REKENING',width:5,search:false,hidden:true},
				{name:'TIPE',index:'TIPE',width:36,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE = $(this).val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
										
									 }
								  }
							   ]
				}},
				{name:'KELOMPOK', index:'KELOMPOK',width:86,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $(this).val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'JENIS',index:'JENIS',width:45,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $(this).val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'OBJEK',index:'OBJEK',width:50,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $(this).val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'RINCIAN', index:'RINCIAN',width:70,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $(this).val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'SUB1',index:'SUB1',width:50,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $(this).val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'SUB2',index:'SUB2',width:50,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $(this).val();
										SUB3 = $("[name^='SUB3']").val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'SUB3', index:'SUB3',width:50,editable:true,edittype:'text',
					editoptions:{
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										TIPE =  $("[name^='TIPE']").val();
										KELOMPOK = $("[name^='KELOMPOK']").val();
										JENIS = $("[name^='JENIS']").val();
										OBJEK = $("[name^='OBJEK']").val();
										RINCIAN = $("[name^='RINCIAN']").val();
										SUB1 = $("[name^='SUB1']").val();
										SUB2 = $("[name^='SUB2']").val();
										SUB3 = $(this).val();
										
										if(!KELOMPOK){
											KODE_REKENING = TIPE;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!JENIS){
											KODE_REKENING = TIPE+'.'+KELOMPOK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!OBJEK){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!RINCIAN){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB1){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB2){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}else if(!SUB3){
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);	
										}else{
											KODE_REKENING = TIPE+'.'+KELOMPOK+'.'+JENIS+'.'+OBJEK+'.'+RINCIAN+'.'+SUB1+'.'+SUB2+'.'+SUB3;
											$("[name^='KODE_REKENING']").val(KODE_REKENING);
										}
											
									 }
								  }
							   ]
				}},
				{name:'KODE_REKENING',index:'KODE_REKENING',width:130,editable:true,edittype:'text',editoptions:{readonly: true,size:20},editrules:{required:true}},
				{name:'NAMA_REKENING', index:'NAMA_REKENING',width:300,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true}},
				{name:'KATEGORI',index:'KATEGORI',width:175,editable:true,edittype:'select',editoptions:{dataUrl:'<?php echo base_url()?>rekening/getselect_kategori',class:'span2'},editrules:{required:true}},
				{name:'PAGU',index:'PAGU',width:100,editable:false,edittype:'text',editoptions:{size:20},formatter: "currency", formatoptions:{decimalSeparator:".", thousandsSeparator:","},align:"right"}
			],
			rowNum:15,
			rowList:[15,30,45],
			rownumbers:true,
			pager:'#pager',
			viewrecords:true,
			gridview:true,
			multiselect:true,
			multiboxonly:true,
			width:999,
			height:350,
			caption:'Rekening',
			ondblClickRow:edit_row,
			onSelectRow:restore_row
		});
		
		$("#grid").jqGrid( 'navGrid', '#pager', { 
    <?php
    if($akses=='3'){
    echo "
		add: true,
		addtext: 'Tambah',
		addfunc: append_row,
		edit: true,
		edittext: 'Ubah',
		editfunc: edit_row,
		del: true,
		deltext: 'Hapus',
		delfunc: del_row,
		search: false,
		searchtext: 'Cari',
    	";
  }
  else{
  echo "
  add:false,
  edit:false,
  del:false,
  search:false,
  ";
  }
  ?>
		refresh: true,
		refreshtext: 'Refresh',
    beforeRefresh: function(){
      $('#q').val('');
      var q = $('#q').val();
      var $grid = $(this);
      var mlen = $('#field').length;
      
      if (mlen === 1) { 
        var lenopt = $('#field').find('option:disabled').length;
        for (var i=0; i<lenopt; i++) {
          var field = $('#field').find('option:disabled').eq(i).val();
          $('#cek_'+field).attr('checked', false);
          $('#flt_'+field).remove();
          $('#key_'+field).remove();
        }
      }
            
      var postdata = $grid.jqGrid('getGridParam', 'postData');
      delete postdata.filters;
      delete postdata.m;
      delete postdata.q;
      $grid.jqGrid('setGridParam', {search: true, postData: postdata});
      }
		},{},{},{},{})
    <?php
    if($akses !='1'){
    ?>
		.navSeparatorAdd('#pager')
		.navButtonAdd('#pager',{
			caption:'',
			onClickButton: function(){ print_list("pdf"); },
			title:'Cetak Daftar (PDF)',
			buttonicon:'ui-icon-pdf',
			position:'last'
		})
		.navButtonAdd('#pager',{
			caption:'',
			onClickButton: function(){ print_list("xls"); },
			title:'Cetak Daftar (XLS)',
			buttonicon:'ui-icon-xls',
			position:'last'
		});
		;
     <?php
  }
  ?> 
		
    function print_list(doc){
      var $grid = $('#grid');
      var postdata = $grid.jqGrid('getGridParam', 'postData');

      preview({"tipe":"daftar", "format":doc, "m":postdata['m'], "q":postdata['q']});
    }
    
		function append_row(){
			if(data_dasar=='3'){
				var id = $("#grid").jqGrid('getGridParam','selrow');
				var ret = $("#grid").jqGrid('getRowData',id);
				var data = {id:ret.id};
				
				$('#grid').jqGrid('restoreRow', last);
				$("#grid").jqGrid('addRowData', "new", data, 'after', id);
				$('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
				last = null;
				
				$("[name^='ID_PARENT_REKENING']").val(ID_PARENT_REKENING_x);
				$("[name^='KODE_REKENING']").val(KODE_REKENING_x);
				$("[name^='TIPE']").val(TIPE_x);
				$("[name^='KELOMPOK']").val(KELOMPOK_x);
				$("[name^='JENIS']").val(JENIS_x);
				$("[name^='OBJEK']").val(OBJEK_x);
				$("[name^='RINCIAN']").val(RINCIAN_x);
				$("[name^='SUB1']").val(SUB1_x);
				$("[name^='SUB2']").val(SUB2_x);
				$("[name^='SUB3']").val(SUB3_x);
				$("[name^='KATEGORI']").val(ID_MASTER_REKENING_x);
				$("[name^='PAGU']").val(0);
			}else{
				$.pnotify({
					title: 'Gagal',
					text: 'Tidak bisa tambah data',
					type: 'info'
				});
			}
		}
	
		function edit_row(id){
			if(data_dasar=='3'){
				$('#grid').jqGrid('restoreRow', last);
				$('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				last = id;
				
				var id_row = $('#grid').jqGrid('getGridParam','selrow');
				var ret = $('#grid').jqGrid('getRowData',id_row);

				$.post("<?php echo base_url()?>rekening/get_data_id/"+ret.id,{
												},function(data){
												data_edit = $.parseJSON(data);
												$.each(data_edit.opt, function() {
													ID_REKENING_x = this.ID_REKENING;
												});											
											});
			}else{
				$.pnotify({
					title: 'Gagal',
					text: 'Tidak bisa ubah data',
					type: 'info'
				});
			}							
		}
	
		function del_row(id){			
			if(data_dasar=='3'){
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					var purge = new Array();
					var purge_kode = new Array();
					var ids = $('#grid').jqGrid('getGridParam','selarrrow');
					var row = ids.length;
					for(var c=0;c<ids.length; c++) {
						var idc = $('#grid').jqGrid('getRowData',ids[c]);
						purge.push(idc.id);
						purge_kode.push(idc.KODE_REKENING);
					}
					
					$.ajax({
						url: '<?php echo base_url()?>rekening/hapus', 
						data: { id: purge,kode: purge_kode},
						success: function(response){
							var msg = $.parseJSON(response);
							$.pnotify({
							  title: msg.isSuccess ? 'Sukses' : 'Gagal',
							  text: msg.message,
							  type: msg.isSuccess ? 'info' : 'error'
							});
							if(msg.isSuccess == true) {
								$("#grid").jqGrid('delRowData', id);
							}
							$('#grid').trigger('reloadGrid');
						},
						type: "post", 
						dataType: "html"
					});
				}
			}else{
				$.pnotify({
					title: 'Gagal',
					text: 'Tidak bisa hapus data',
					type: 'info'
				});
			}
		}
		
		function restore_row(id){
			if(id && id !== last){
				$('#grid').jqGrid('restoreRow', last);
				last = null;
			}
			
			//var id = $('#grid').jqGrid('getGridParam','selrow');
			var ret = $('#grid').jqGrid('getRowData',id);
			var ID_REKENING = ret.id;
			
			$.post("<?php echo base_url()?>rekening/session_id",{
				'ID_REKENING':ID_REKENING
				},function(data){
			});
			
			$.post("<?php echo base_url()?>rekening/get_data_id/"+ret.id,{
				},function(data){
				data_edit = $.parseJSON(data);
				$.each(data_edit.opt, function() {
					ID_REKENING_x = this.ID_REKENING;
					ID_PARENT_REKENING_x = this.ID_PARENT_REKENING;
					ID_MASTER_REKENING_x = this.ID_MASTER_REKENING;
					KODE_REKENING_x = this.KODE_REKENING;
					NAMA_REKENING_x = this.NAMA_REKENING;
					TIPE_x = this.TIPE;
					KELOMPOK_x = this.KELOMPOK;
					JENIS_x = this.JENIS;
					OBJEK_x = this.OBJEK;
					RINCIAN_x = this.RINCIAN;
					SUB1_x = this.SUB1;
					SUB2_x = this.SUB2;
					SUB3_x = this.SUB3;
					KATEGORI_x = this.KATEGORI;
					PAGU_x = this.PAGU;
					//alert(TIPE_x+'.'+KELOMPOK_x+'.'+JENIS_x+'.'+OBJEK_x+'.'+RINCIAN_x);
					
				});
			});
		}

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
			var msg = $.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});
			if(msg.id &&  msg.id != id)
				$("#"+id).attr("id", msg.id);
			$('#grid').trigger("reloadGrid");
		}
	
		function errorfunc(id, resp){
			var msg = $.parseJSON(resp.responseText);
			if(msg.error)
				$.pnotify({
				  title: 'Gagal',
				  text: msg.error,
				  type: 'error'
				});	
				$('#message').addClass('red');
				$('#grid').jqGrid('restoreRow', id);
				$('#grid').trigger("reloadGrid");
		}
		
    $('#q').keypress(function (e) {
      if (e.which == 13) {
        var q = $('#q').val();
        var $grid = $('#grid');
        
        var postdata = $grid.jqGrid('getGridParam', 'postData');
        $.extend(postdata,{filters: '', m: 's', q: q});
        $grid.jqGrid('setGridParam', {search: true, postData: postdata});
        $grid.trigger("reloadGrid",[{page:1}]);
      }
    });
    
      // ----- search advance ---- >>
    var fields = <?php echo json_encode($fields); ?>;
    DialogSearch.init(fields);

	});

	</script>
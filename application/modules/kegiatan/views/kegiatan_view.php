	<fieldset>
    <legend>Daftar Kegiatan</legend>
      <select name="field" id="field" class="span2">
		<option value='NAMA_KEGIATAN'>Nama Kegiatan</option>
		<option value='KODE_URUSAN'>Kode Urusan</option>
		<option value='KODE_BIDANG'>Kode Bidang</option>
		<option value='KODE_PROGRAM'>Kode Program</option>
		<option value='NAMA_PROGRAM'>Nama Program</option>
		<option value='KODE_KEGIATAN'>Kode Kegiatan</option>
      </select>
      <select name='oper' id='oper' class="span2">
        <option value="cn">Memuat</option>
        <option value="bw">Diawali</option>
      </select>
      <input type="text" name="string" id="string" class="span7">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
  </fieldset>
	<table id="grid"></table>
	<div id="pager"></div>
	
	<script type="text/javascript">
  var modul = 'kegiatan';
  
	jQuery(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		var ID_URUSAN=0;
		var ID_BIDANG=0;
		var ID_SKPD=0;
		var x;
		var bid;
		var sk;
		var prog;
		var data_edit;
		var ID_URUSAN_x;
		var ID_URUSAN_y;
		var KODE_URUSAN_x;
		var ID_BIDANG_x;
		var ID_BIDANG_y;
		var KODE_BIDANG_x;
		var ID_SKPD_x;
		var ID_SKPD_y;
		var KODE_SKPD_x;
		var ID_PROGRAM_x;
		var ID_PROGRAM_y;
		var ID_PROGRAM_z;
		var ID_PROGRAM_r;
		var KODE_PROGRAM_x;
		var KODE_PROGRAM_y;
		var ID_KEGIATAN_x;
		var KODE_KEGIATAN_x;
		var ID_URUSAN_a;
	
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>kegiatan/get_daftar',
			editurl:'<?php echo base_url()?>kegiatan/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','URUSAN','BIDANG','SKPD','NAMA PROGRAM','ID PROGRAM','PROGRAM','KODE KEGIATAN','NAMA KEGIATAN'],
			colModel:[
				{name:'id',index:'ID_KEGIATAN',width:5,search:false,hidden:true},
				{name:'URUSAN',index:'URUSAN',width:70,editable:true,edittype:'select',sortable: false,
					editoptions:{dataUrl:'<?php echo base_url()?>kegiatan/getselect_urusan',
						dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										if(ID_URUSAN_y){
											ID_URUSAN = ID_URUSAN_y;
										}else if(ID_URUSAN_a){
											ID_URUSAN = ID_URUSAN_a;
										}else{
											ID_URUSAN = jQuery(this).val();
										}
										
										if(ID_URUSAN_a){ //u/ tambah data
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_bidang/"+ID_URUSAN,{
												},function(data){
												bid = jQuery.parseJSON(data);
												jQuery("[name^='BIDANG']").html('');
												jQuery.each(bid.opt, function() {
													x = new Option(this.KODE_BIDANG, this.ID_BIDANG);
													jQuery("[name^='BIDANG']").append(x);
												});
												jQuery("[name^='BIDANG']").val(ID_BIDANG_a);
											});	
										}else if(ID_URUSAN_x){
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_bidang/"+ID_URUSAN,{
												},function(data){
												bid = jQuery.parseJSON(data);
												jQuery("[name^='BIDANG']").html('');
												jQuery.each(bid.opt, function() {
													x = new Option(this.KODE_BIDANG, this.ID_BIDANG);
													jQuery("[name^='BIDANG']").append(x);
												});
												jQuery("[name^='BIDANG']").val(ID_BIDANG_x);
											});											
										}else if(ID_URUSAN_y){ //jika tdk ada id kegiatan
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_bidang/"+ID_URUSAN_y,{
												},function(data){
												bid = jQuery.parseJSON(data);
												jQuery("[name^='BIDANG']").html('');
												jQuery.each(bid.opt, function() {
													x = new Option(this.KODE_BIDANG, this.ID_BIDANG);
													jQuery("[name^='BIDANG']").append(x);
												});
												jQuery("[name^='BIDANG']").val(ID_BIDANG_y);
											});

										}
										
									 }
								  }
							   ],
							   class: 'span1'
				}},
				{name:'BIDANG', index:'BIDANG',width:70,editable:true,edittype:'select',sortable: false,
					editoptions:{value:"0: ",
						dataEvents:[
								  {	type: 'change',
									 fn: function(e, ID_BIDANG_x) {
										if(ID_BIDANG_x){
										ID_BIDANG = ID_BIDANG_x;
										}else if(ID_BIDANG_a){
										ID_BIDANG = ID_BIDANG_a;
										}else{
										ID_BIDANG = jQuery(this).val();
										} 
										
										if(ID_BIDANG_a){//u/ tambah data
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_program/"+ID_BIDANG,{
												},function(data){
												prog = jQuery.parseJSON(data);
												jQuery("[name^='PROGRAM']").html('');
												jQuery.each(prog.opt, function() {
													x = new Option(this.KODE_PROGRAM, this.ID_PROGRAM);
													jQuery("[name^='PROGRAM']").append(x);
												});
												jQuery("[name^='PROGRAM']").val(ID_PROGRAM_a);
												
											});
										}else if(!ID_BIDANG_a){//u/ tambah data
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_program_2/"+ID_PROGRAM_a,{
												},function(data){
												prog = jQuery.parseJSON(data);
												jQuery("[name^='PROGRAM']").html('');
												jQuery.each(prog.opt, function() {
													x = new Option(this.KODE_PROGRAM, this.ID_PROGRAM);
													jQuery("[name^='PROGRAM']").append(x);
													
													//ID_PROGRAM_z = this.ID_PROGRAM;
												});
												jQuery("[name^='PROGRAM']").val(ID_PROGRAM_a);
											
											});
										}else if(ID_BIDANG){//ada id keg
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_program/"+ID_BIDANG,{
												},function(data){
												prog = jQuery.parseJSON(data);
												jQuery("[name^='PROGRAM']").html('');
												jQuery.each(prog.opt, function() {
													x = new Option(this.KODE_PROGRAM, this.ID_PROGRAM);
													jQuery("[name^='PROGRAM']").append(x);
												});
												jQuery("[name^='PROGRAM']").val(ID_PROGRAM_x);
												
											});
										}else if(ID_PROGRAM_y){
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_program_2/"+ID_PROGRAM_y,{
												},function(data){
												prog = jQuery.parseJSON(data);
												jQuery("[name^='PROGRAM']").html('');
												jQuery.each(prog.opt, function() {
													x = new Option(this.KODE_PROGRAM, this.ID_PROGRAM);
													jQuery("[name^='PROGRAM']").append(x);
													
													ID_PROGRAM_z = this.ID_PROGRAM;
												});
												jQuery("[name^='PROGRAM']").val(ID_PROGRAM_z);
											
											});
										}else if((!ID_BIDANG) && (!ID_PROGRAM_y)){
											jQuery.post("<?php echo base_url()?>kegiatan/getselect_program_3",{
												},function(data){
												prog = jQuery.parseJSON(data);
												jQuery("[name^='PROGRAM']").html('');
												jQuery.each(prog.opt, function() {
													x = new Option(this.KODE_PROGRAM, this.ID_PROGRAM);
													jQuery("[name^='PROGRAM']").append(x);
													
													ID_PROGRAM_r = this.ID_PROGRAM;
												});
												jQuery("[name^='PROGRAM']").val(ID_PROGRAM_r);
												
											});
										
										}
									 }
								  }
							   ],
							   class: 'span1'
				}},
				{name:'SKPD',index:'SKPD',width:50,editable:true,edittype:'select',editoptions:{value:"0: "},hidden:true,sortable: false},
				{name:'NAMA_PROGRAM',index:'NAMA_PROGRAM',width:5,sortable: false,search:false,hidden:true, classes: 'cvteste',style: "border: 0; background-color: #ffff66;" },
				{name:'ID_PROGRAM',index:'ID_PROGRAM',width:5,search:false,hidden:true,sortable: false},
				{name:'PROGRAM',index:'PROGRAM',width:70,sortable: false,editable:true,edittype:'select',editoptions:
				{dataUrl:'<?php echo base_url()?>kegiatan/getselect_program_x',class:'span1'},editrules:{required:true}},
				{name:'KODE_KEGIATAN', index:'KODE_KEGIATAN',width:90,editable:true,edittype:'text',editoptions:{size:30,class:'span1'},editrules:{required:true},sortable: false},
				{name:'NAMA_KEGIATAN',index:'NAMA_KEGIATAN',width:350,editable:true,edittype:'text',editoptions:{size:50,class:'span5'},editrules:{required:true},sortable: false},
			],
			/*rowNum:10,
			rowList:[10,20,30],*/
			rowList: [],        // disable page size dropdown
			pgbuttons: false,     // disable page control like next, back button
			pgtext: null,         // disable pager text like 'Page 0 of 10'
			//viewrecords: false,
			rowNum:1000000,
			scroll:true,
			rownumbers:false,
			pager:'#pager',
			viewrecords:true,
			multiselect:true,
			multiboxonly:true,
			gridview:true,
			width:930,
			height:245,
			grouping:true, 
			groupingView : { 
				groupField: ['NAMA_PROGRAM'],
				groupColumnShow: [false],
				groupDataSorted : [false],
				groupText: ['<b>{0} </b>']
			},
			ondblClickRow:edit_row,
			onSelectRow:restore_row
		});
		
		jQuery("#grid").jqGrid( 'navGrid', '#pager', { 
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
		},{},{},{},{})
    <?php
    if($akses !='1'){
    ?>
  .navSeparatorAdd('#pager')
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('pdf') },
    title:'Cetak Daftar (PDF)',
    buttonicon:'ui-icon-pdf',
    position:'last'
  })
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('xls') },
    title:'Cetak Daftar (XLS)',
    buttonicon:'ui-icon-xls',
    position:'last'
  });
    ;
  <?php
  }
  ?> 
		
		
    function print_list(doc){
			var grid = jQuery("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');

     preview({"tipe":"daftar", "format":doc, "search":postdata['_search'], "searchField":postdata['searchField'], "searchOper":postdata['searchOper'], "searchString":postdata['searchString']});
    }
    
		function append_row(){
			if(data_dasar=='3'){
				var id = jQuery("#grid").jqGrid('getGridParam','selrow');
				var ret = jQuery("#grid").jqGrid('getRowData',id);
				var data = {id:ret.id};
				
				jQuery('#grid').jqGrid('restoreRow', last);
				jQuery("#grid").jqGrid('addRowData', "new", data, 'after', id);
				//jQuery("#grid").jqGrid('addRowData', "new", true);
				jQuery('#grid').jqGrid('editRow', "new", true, oneditfunc_a, null, null, null, aftersavefunc, errorfunc, null);
				last = null;
				
				jQuery("[name^='URUSAN']").val(ID_URUSAN_a);
			}else{
				alert('Tidak bisa tambah data');
			}
		}
		
		function oneditfunc_a(id){
			window.setTimeout(function() {
				jQuery("[name^='URUSAN']").val(ID_URUSAN_a);
				jQuery('#'+id+'_URUSAN').trigger('change');
				jQuery('#'+id+'_BIDANG').trigger('change', ID_BIDANG_a); //ada id kegiatan
			}, 1000);
		}
	
		function edit_row(id){
			if(data_dasar=='3'){
				jQuery('#grid').jqGrid('restoreRow', last);
				jQuery('#grid').jqGrid('editRow', id, true, oneditfunc, null, null, null, aftersavefunc, errorfunc, null);
				last = id;
				
				var id_row = jQuery('#grid').jqGrid('getGridParam','selrow');
				var ret = jQuery('#grid').jqGrid('getRowData',id_row);
				ID_URUSAN_x = ret.URUSAN;
				
				
				
				if(ret.id){ //jika ada id
				jQuery.post("<?php echo base_url()?>kegiatan/get_data_id/"+ret.id,{ 
												},function(data){
												data_edit = jQuery.parseJSON(data);
												jQuery.each(data_edit.opt, function() {
													ID_BIDANG_x = this.ID_BIDANG;
													ID_SKPD_x = this.ID_SKPD;
													ID_PROGRAM_x = this.ID_PROGRAM;
												});		
											});
				}
				else{ //jika tidak ada id kegiatan
				jQuery.post("<?php echo base_url()?>kegiatan/get_data_id_program/"+ret.ID_PROGRAM,{ 
												},function(data){
												data_edit = jQuery.parseJSON(data);
												jQuery.each(data_edit.opt, function() {
													ID_URUSAN_y = this.ID_URUSAN;
													ID_BIDANG_y = this.ID_BIDANG;
													ID_SKPD_y = this.ID_SKPD;
													ID_PROGRAM_y = this.ID_PROGRAM;
													KODE_PROGRAM_y = this.KODE_PROGRAM;
												});											
											});
										
				}
			}
			else{
				alert('Tidak bisa ubah data');
			}
		}
	
		function oneditfunc(id){
			window.setTimeout(function() {
				jQuery('#'+id+'_URUSAN').trigger('change');
				jQuery('#'+id+'_BIDANG').trigger('change', ID_BIDANG_x); //ada id kegiatan
			}, 1000);
		}
	
		function del_row(id){
			if(data_dasar=='3'){
				var id = jQuery('#grid').jqGrid('getGridParam','selrow'); 
				var data = jQuery('#grid').jqGrid('getRowData',id);
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					jQuery.ajax({
						url: '<?php echo base_url()?>kegiatan/hapus', 
						data: { id: data.id},
						success: function(response){
								var msg = jQuery.parseJSON(response);
							$.pnotify({
							  title: msg.isSuccess ? 'Sukses' : 'Gagal',
							  text: msg.message,
							  type: msg.isSuccess ? 'info' : 'error'
							});
							if(msg.isSuccess == true) {
								jQuery("#grid").jqGrid('delRowData', id);
							}
							jQuery('#grid').trigger('reloadGrid');
						},
						type: "post", 
						dataType: "html"
					});
				}
			}else{
				alert('Tidak bisa hapus data');
			}
		}
		
		function restore_row(id){
			if(id && id !== last){
				jQuery('#grid').jqGrid('restoreRow', last);
				last = null;
			}
			
			var id = jQuery('#grid').jqGrid('getGridParam','selrow');
			var ret = jQuery('#grid').jqGrid('getRowData',id);
			var ID_KEGIATAN = ret.id;
			
			if(ID_KEGIATAN != undefined) {
				if(ID_KEGIATAN != ''){
					jQuery.post("<?php echo base_url()?>kegiatan/session_id",{
						'ID_KEGIATAN':ID_KEGIATAN
						},function(data){
					});
					
					jQuery.post("<?php echo base_url()?>kegiatan/get_data_id/"+ret.id,{
						},function(data){
						data_edit = jQuery.parseJSON(data);
						jQuery.each(data_edit.opt, function() {
							ID_URUSAN_a = this.ID_URUSAN;
							ID_BIDANG_a = this.ID_BIDANG;
							ID_PROGRAM_a = this.ID_PROGRAM;
							KODE_PROGRAM_a = this.KODE_PROGRAM;
							ID_KEGIATAN_a = this.ID_KEGIATAN;
							KODE_KEGIATAN_a = this.KODE_KEGIATAN;
							NAMA_KEGIATAN_a = this.NAMA_KEGIATAN;
						});
					});
				}
				else{
					jQuery.post("<?php echo base_url()?>kegiatan/get_data_id_program/"+ret.ID_PROGRAM,{ 
						},function(data){
						data_edit = jQuery.parseJSON(data);
						jQuery.each(data_edit.opt, function() {
							ID_URUSAN_a = this.ID_URUSAN;
							ID_BIDANG_a = this.ID_BIDANG;
							//ID_SKPD_a = this.ID_SKPD;
							ID_PROGRAM_a = this.ID_PROGRAM;
							//KODE_PROGRAM_a = this.KODE_PROGRAM;
						});											
					});	
				}
			}
		}

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
			var msg = jQuery.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});
			if(msg.id &&  msg.id != id)
				jQuery("#"+id).attr("id", msg.id);
			jQuery('#grid').trigger('reloadGrid');
		}
	
		function errorfunc(id, resp){
			var msg = jQuery.parseJSON(resp.responseText);
			if(msg.error)
				$.pnotify({
				  title: 'Gagal',
				  text: msg.error,
				  type: 'error'
				});
			jQuery('#grid').jqGrid('restoreRow', id);
			//jQuery('#grid').trigger('reloadGrid');
		}
		
		jQuery('#filter').click(function(){
			var field 	= jQuery("#field").val();
			var oper 	= jQuery("#oper").val();
			var string 	= jQuery("#string").val();
			
			var grid = jQuery("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');
			jQuery.extend (postdata,
						   {filters:'',
							searchField: field,
							searchOper: oper,
							searchString: string});
			grid.jqGrid('setGridParam', { search: true, postData: postdata });
			grid.trigger("reloadGrid",[{page:1}]);
		}); 
		
		jQuery('#string').keypress(function (e) {
			if (e.which == 13) {
				jQuery('#filter').click();
			}
		});

    
					
	});

	</script>
	
	<STYLE>
        .cvteste {
            background-color: #ffff66 !important;
        }
	</STYLE>
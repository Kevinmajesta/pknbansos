	<fieldset>
    <legend>Daftar Program</legend>
      <select name="field" id="field" class="span2">
			<option value='NAMA_PROGRAM'>Nama Program</option>	
			<option value='KODE_URUSAN'>Urusan</option>
			<option value='KODE_BIDANG'>Bidang</option>
			<option value='KODE_PROGRAM'>Program</option>
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
  var modul = 'program';
	jQuery(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		var ID_URUSAN=0;
		var ID_BIDANG=0;
		var ID_SKPD=0;
		var x;
		var bid;
		var sk;
		var data_edit;
		var ID_URUSAN_x;
		var KODE_URUSAN_x;
		var ID_BIDANG_x;
		var KODE_BIDANG_x;
		var ID_SKPD_x;
		var KODE_SKPD_x;
		var ID_PROGRAM_x;
		var KODE_PROGRAM_x;
	
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>program/get_daftar',
			editurl:'<?php echo base_url()?>program/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','URUSAN','BIDANG','NAMA BIDANG','SKPD','PROGRAM','NAMA'],
			colModel:[
				{name:'id',index:'ID_PROGRAM',width:5,search:false,hidden:true},
				{name:'URUSAN',index:'URUSAN',width:100,editable:true,edittype:'select',sortable:false,
					editoptions:{
							dataUrl:'<?php echo base_url()?>program/getselect_urusan',
							dataEvents:[
								  {	type: 'change',
									 fn: function(e) {
										ID_URUSAN = jQuery(this).val();
										jQuery.post("<?php echo base_url()?>program/getselect_bidang/"+ID_URUSAN,{
											},function(data){
											bid = jQuery.parseJSON(data);
											jQuery("[name^='BIDANG']").html('');
											jQuery.each(bid.opt, function() {
												x = new Option(this.KODE_BIDANG, this.ID_BIDANG);
												jQuery("[name^='BIDANG']").append(x);
											});
											jQuery("[name^='BIDANG']").val(ID_BIDANG_x);
											
										});
									 }
								  }
							   ],
							 class: 'span1'
				}},
				{name:'BIDANG', index:'BIDANG',width:100,sortable:false,editable:true,edittype:'select',
					editoptions:{value:"0: ",class:'span1'}},
				{name:'NAMA_BIDANG', index:'NAMA_BIDANG',width:100,sortable:false,editable:true,edittype:'select',
					editoptions:{value:"0: "}},
				{name:'SKPD',index:'SKPD',width:100,editable:true,edittype:'select',
					editoptions:{value:"0: "},hidden:true},
				{name:'KODE_PROGRAM',index:'KODE_PROGRAM',width:350,editable:true,edittype:'text',editoptions:{size:30},editrules:{required:true},sortable: false},
				{name:'NAMA_PROGRAM',index:'NAMA_PROGRAM',width:690,editable:true,edittype:'text',editoptions:{size:100,class:'span6'},editrules:{required:true},sortable: false}
			],
			/*rowNum:10,
			rowList:[10,20,30],*/
			rowList: [],        // disable page size dropdown
			pgbuttons: false,     // disable page control like next, back button
			pgtext: null,         // disable pager text like 'Page 0 of 10'
			rowNum:1000000,
			scroll:true,
			rownumbers:false,
			pager:'#pager',
			viewrecords:true,
			multiselect:true,
			multiboxonly:true,
			gridview:true,
			width:1000,
			height:250,
			grouping:true, 
			groupingView : { 
				groupField: ['NAMA_BIDANG'],
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

     preview({"tipe":"daftar", "format":doc, "search":postdata['_search'], "searchField":postdata['searchField'], "searchOper":postdata['searchOper'], "searchString":postdata['searchString']});
    }
		
		function append_row(){
			if(data_dasar=='3'){
				
				var id = jQuery("#grid").jqGrid('getGridParam','selrow');
				if(id)
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					var hasil = "";
					for(var u=0;u<jml.length;u++)
					{
						if(jml[u] == "new"){
							hasil = hasil + jml[u];
						}
					}
					
					var ada = hasil.search('new');
					if(ada != -1){
						alert('Input Program belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
						jQuery("#grid").jqGrid('addRowData', "new", true, 'after', id);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
					}
				}
				else
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					pos = jml.length - 1;
					if(jml[pos] == "new"){
						alert('Input Program belum tersimpan..!!');
					}
					else{
						jQuery("#grid").jqGrid('addRowData', "new", true);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
					}
				}
				last = null;
			}else{
				alert('Tidak bisa tambah data');
			}
		}
	
		function edit_row(id){
			if(data_dasar=='3'){
				jQuery('#grid').jqGrid('restoreRow', last);
				jQuery('#grid').jqGrid('editRow', id, true, oneditfunc, null, null, null, aftersavefunc, errorfunc, null);
				last = id;
				
				var id_row = jQuery('#grid').jqGrid('getGridParam','selrow');
				var ret = jQuery('#grid').jqGrid('getRowData',id_row);
				ID_URUSAN_x = ret.URUSAN;

				jQuery.post("<?php echo base_url()?>program/get_data_id/"+ret.id,{
												},function(data){
												data_edit = jQuery.parseJSON(data);
												jQuery.each(data_edit.opt, function() {
													ID_BIDANG_x = this.ID_BIDANG;
												});											
											});
			}else{
				alert('Tidak bisa ubah data');
			}
		}
	
		function del_row(id){
			if(data_dasar=='3'){
				var rt = jQuery("#grid").jqGrid('getRowData', id); 
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{ 
          console.log(rt);
					jQuery("#grid").jqGrid('delRowData', id);
					jQuery.ajax({
						url: '<?php echo base_url()?>program/hapus', 
						data: { ID_PROGRAM: id},
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
	
		function oneditfunc(id){
			window.setTimeout(function() {
				jQuery('#'+id+'_URUSAN').trigger('change');
			}, 1000);
		}
		
		function restore_row(id){
			if(id && id !== last){
				jQuery('#grid').jqGrid('restoreRow', last);
				last = null;
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
			jQuery('#grid').trigger("reloadGrid");	
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
			jQuery('#grid').trigger("reloadGrid");
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
	<fieldset>
    <legend>Daftar Pejabat Daerah</legend>
      <select name="field" id="field" class="span2">
		<option value='nama'>Nama</option>
		<option value='jabatan'>Jabatan</option>
		<option value='nip'>NIP</option>
		<option value='aktif'>Status</option>
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
	jQuery(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		jQuery("#string").focus();
		
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>pejabat_daerah/get_daftar',
			editurl:'<?php echo base_url()?>pejabat_daerah/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['JABATAN','NAMA','NIP','STATUS'],
			colModel:[
				{name:'jabatan',index:'jabatan',width:200,editable:true,editoptions:{size:50,class:'autocomplete'},editrules:{required:true}},
				{name:'nama',index:'nama',width:200,editable:true,editoptions:{size:50},editrules:{required:true}},
				{name:'nip', index:'nip',width:150,editable:true,edittype:'text',editoptions:{size:50}},
				{name:'aktif',index:'aktif',width:120,editable:true, edittype:"select",editoptions:{value:"1:Aktif;0:Tidak Aktif",class:"span2"}}
				
			],
			rowNum:10,
			rowList:[10,20,30],
			rownumbers:true,
			pager:'#pager',
			viewrecords:true,
			gridview:true,
			width:930,
			height:250,
			ondblClickRow:edit_row,
			onSelectRow: restore_row
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
		},{},{},{},{});
		
		
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
						alert('Input Pejabat Daerah belum tersimpan..!!');
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
						alert('Input Pejabat Daerah belum tersimpan..!!');
					}
					else{
						jQuery("#grid").jqGrid('addRowData', "new", true);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
						jQuery(".autocomplete").autocomplete('<?php echo base_url()?>pejabat_daerah/get_jabatan');
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
				jQuery('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				jQuery(".autocomplete").autocomplete('<?php echo base_url()?>pejabat_daerah/get_jabatan');
				last = id;
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
					jQuery.ajax({
						url: '<?php echo base_url()?>pejabat_daerah/hapus', 
						data: { id: id},
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
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});

			jQuery('#grid').jqGrid('restoreRow', last);
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
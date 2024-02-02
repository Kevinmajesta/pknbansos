	<fieldset>
		<legend>Urusan</legend>
	</fieldset>
	<table id="grid"></table>
	<div id="pager"></div>
	
	<script type="text/javascript">
	jQuery(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
	
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>urusan/get_daftar',
			editurl:'<?php echo base_url()?>urusan/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['URUSAN','NAMA'],
			colModel:[
				{name:'kode',index:'kode',width:150,editable:true,edittype:'text',editoptions:{size:20},editrules:{required:true, integer:true}},
				{name:'nama', index:'nama',width:300,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}}
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
		
		jQuery("#grid").jqGrid('navGrid', '#pager', {
    <?php
    if($akses=='3'){
    echo "
		add:true,
		addtext: 'Tambah',
		addfunc: append_row,
		edit:true,
		edittext: 'Ubah',
		editfunc:edit_row,
		del:true,
		deltext:'Hapus',
		delfunc:del_row,
		search:false,
		searchtext:'Cari',
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
		refresh:true,
		refreshtext:'Refresh',
		},{},{},{},{});
		
		function append_row(){
			if(data_dasar=='3'){
				jml = jQuery("#grid").jqGrid('getDataIDs');
				pos = jml.length - 1;
				if(jml[pos] == "new"){
					alert('Input Urusan belum tersimpan..!!');
				}
				else{
					jQuery('#grid').jqGrid('restoreRow', last);
					jQuery("#grid").jqGrid('addRowData', "new",true);
					jQuery('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
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
				last = id;
			}else{
				alert('Tidak bisa ubah data');
			}
		}
		
		function del_row(id){
			if(data_dasar=='3'){
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					jQuery('#grid').jqGrid('delRowData', id);
					jQuery.ajax({
						url: '<?php echo base_url()?>urusan/hapus', 
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
				jQuery('#grid').jqGrid('restoreRow',last);
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
				jQuery('#grid').trigger('reloadGrid');
		}
				
	});

	</script>
	<div id="searchformsumberdana">
		<div class='frm_jgrid'>	
			<table id="gridbidang2"></table>
			<div id="pagerbidang2"></div>
		</div>
	</div>
	<div id="bidangtambahan"></div>	
<script type="text/javascript">
jQuery(document).ready(function() {
	var last;
	var ret = jQuery("#skpdTable").jqGrid('getGridParam','selrow');

	jQuery("#gridbidang2").jqGrid({
		url:'<?php echo base_url()?>skpd/get_bidangskpd/',
		editurl:'<?php echo base_url()?>skpd/proses_form_gridbidang_skpd',
		datatype: "json",
        mtype:'POST',
        colNames:['ID', 'KODE BAGIAN SKPD', 'NAMA BAGIAN SKPD','ID SKPD'],
        colModel:[{name:'id', index:'ID_UNIT_KERJA', width:10, hidden:true,editable:true},
		{name:'KODE_UNIT_KERJA', index:'KODE_UNIT_KERJA', width:60,editable:true},
		{name:'NAMA_UNIT_KERJA', index:'NAMA_UNIT_KERJA', width:130,editable:true},
		{name:'ID_SKPD', index:'ID_SKPD', hidden:true}],
		rowNum:10,
		rowList:[10,20,30],
		rownumbers:true,
		pager:'#pagerbidang2',
		viewrecords:true,
		gridview:true,
		width:670,
		height:240,
		caption:'Bagian SKPD',
		ondblClickRow:edit_row,
		onSelectRow:restore_row
	});

	jQuery("#gridbidang2").jqGrid( 'navGrid', '#pagerbidang2', { 
		add: true,
		addtext: 'Tambah',
		addfunc: addbidtambahan2,
		edit:true,
		edittext: 'Ubah',
		editfunc:edit_row,
		del: true,
		deltext:'Hapus',
		delfunc:del_row,
		search: false,
		refresh: true,
		refreshtext: 'Refresh'
	},{},{},{},{});
		
	function addbidtambahan2()
	{
		jml = jQuery("#gridbidang2").jqGrid('getDataIDs');
		pos = jml.length - 1;
		if(jml[pos] == "new"){
			alert('Input Bagian SKPD belum tersimpan..!!');
		}
		else{
			jQuery('#gridbidang2').jqGrid('restoreRow', last);
			jQuery("#gridbidang2").jqGrid('addRowData', "new", true);
			jQuery('#gridbidang2').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
		}
		last=null;
	}
	
	function edit_row(id){
		jQuery('#gridbidang2').jqGrid('restoreRow', last);
		jQuery('#gridbidang2').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
		last = id;
	}
	
	function restore_row(id){
		if(id && id !== last){
			jQuery('#gridbidang2').jqGrid('restoreRow',last);
			last = null;
		}
	}
	
	function del_row(id){
		jQuery('#gridbidang2').jqGrid('delRowData', id);
			jQuery.ajax({
				url: '<?php echo base_url()?>skpd/hapus_bidangtambahan_skpd', 
				data: { id: id},
				success: function(response){
						var msg = jQuery.parseJSON(response);
						if(msg.isSuccess)
						{
							$.pnotify({
								title: 'Sukses',
								text: msg.message,
								type: 'info'
							});
						}
						else
						{
							$.pnotify({
								title: 'Gagal',
								text: msg.message,
								type: 'error'
							});
						}
						jQuery('#gridbidang2').trigger('reloadGrid');
						jQuery('#skpdTable').trigger('reloadGrid');
					},
				type: "post", 
				dataType: "html"
			});
	}
	
	function aftersavefunc(id, resp){
		var msg = jQuery.parseJSON(resp.responseText);
		if(msg.isSuccess){
			$.pnotify({
				title: 'Sukses',
				text: msg.message,
				type: 'info'
			});
			if(msg.id &&  msg.id != id)
				jQuery("#"+id).attr("id", msg.id);
				jQuery('#gridbidang2').trigger('reloadGrid');
				jQuery('#skpdTable').trigger('reloadGrid');
		}
	}

	function errorfunc(id, resp){
		var msg = jQuery.parseJSON(resp.responseText);
		if(msg.error)
			$.pnotify({
				title: 'Gagal',
				text: msg.message,
				type: 'error'
			});
			jQuery('#gridbidang2').trigger('reloadGrid');
	}
		
	jQuery('#filter').click(function(){
		var field 	= jQuery("#field").val();
		var oper 	= jQuery("#oper").val();
		var string 	= jQuery("#string").val();
		
		var grid = jQuery("#gridbidang2");
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
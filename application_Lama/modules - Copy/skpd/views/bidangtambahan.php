<div id="searchformbidang">
	<div class='frm_jgrid'>
		<table id="gridbidang" class="scroll"></table>
		<div id="pagerbidang" class="scroll"></div>
	</div>
</div>
<div id="bidangtambahan"></div>	
<script type="text/javascript">
jQuery(document).ready(function() {
	var last;
	var ret = jQuery("#skpdTable").jqGrid('getGridParam','selrow');

	jQuery("#gridbidang").jqGrid({
		url:'<?php echo base_url()?>skpd/get_bidangtambahan/',
		editurl:'<?php echo base_url()?>skpd/proses_form_gridbidang',
		datatype: "json",
        mtype:'POST',
        colNames:['ID', 'KODE BIDANG', 'NAMA BIDANG'],
        colModel:[{name:'id', index:'ID_BIDANG', width:140, hidden:true,editable:true},
		{name:'KODE_BIDANG_LKP', index:'KODE_BIDANG_LKP', width:60,editable:true},
		{name:'NAMA_BIDANG', index:'NAMA_BIDANG', width:200,editable:true}],
        rowNum:10,
		rownumbers:true,
        pager: '#pagerbidang',
		sortorder: "asc",
		multiselect:true,
        viewrecords: true,
        gridview:true,
        width:540,
        height:240,
	});

	jQuery("#gridbidang").jqGrid( 'navGrid', '#pagerbidang', { 
		add: true,
		addtext: 'Tambah',
		addfunc: addbidtambahan,
		edit: false,
		del: true,
		deltext: 'Hapus',
		delfunc:del_row,
		search: false,
		refresh: false,
		refreshtext: 'Refresh'
	},{},{},{},{});
		
	function addbidtambahan()
	{
		set_dialog_bidang_tambahan();
		jQuery('#bidangtambahan').dialog('open');
	}
	
	function set_dialog_bidang_tambahan()
	{
		jQuery('#bidangtambahan').dialog({
			title:'Pilih Bidang Tambahan',
			height:440,
			width:600,
			modal:true,
			autoOpen:false,
			closeOnEscape:true,
			buttons: [
					{
						text: "Pilih",
						class: 'btn btn-primary', 
						click: pilih_bidang_tambahan,
					},
					{
						text: "Tutup",
						class: 'btn btn-primary', 
						click: function() { 
							$(this).dialog("close"); 
						} 
					}
			]
		}).load('<?php echo base_url();?>bidang/pilihtambahan');
	}
	
	function pilih_bidang_tambahan()
	{
		var isi_bidang_tambahan = jQuery("#gridbidang").jqGrid('getDataIDs');
		var idd = jQuery("#gridbidangtambahan").jqGrid('getGridParam','selarrrow');
		if(idd.length > 0)
		{
			for(var i=0;i<idd.length;i++)
			{
				if (jQuery.inArray(idd[i], isi_bidang_tambahan) >= 0)
					{
					}
				else
				{
				var rd = jQuery("#gridbidangtambahan").jqGrid('getRowData', idd[i]); 
						bidangtambahan = {ID_BIDANG:rd.id,ID_SKPD: <?php echo $this->session->userdata('ID_SKPD_BIDANG')?>}; 
						jQuery.post('<?php echo base_url()?>skpd/proses_form_gridbidang', bidangtambahan , function(data) {
						jQuery("#gridbidang").trigger("reloadGrid");
					});
				}
			}
			jQuery('#gridbidangtambahan').trigger("reloadGrid");
			jQuery('#skpdTable').trigger('reloadGrid');
			jQuery(this).dialog('close');
		}
		else
		{
			alert("Silahkan pilih salah satu data.");
		}
	}
	
	function del_row(id){
		var ids = String(jQuery('#gridbidang').jqGrid('getGridParam','selarrrow')).split(',');
		var row = ids.length;
		if (row>0)
		{
			answer = confirm('Hapus Bidang Tambahan ?')
			if(answer == true)
			for(var i=0;i<row;i++)
			{
					jQuery.post('<?php echo base_url();?>skpd/hapus_bidangtambahan', {'id':ids[i]},function(resp){
					var msg = jQuery.parseJSON(resp);
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
					jQuery("#gridbidang").trigger("reloadGrid");
					jQuery('#skpdTable').trigger('reloadGrid');
				});
			}
		}
	}
	
	function errorfunc(id, resp){
		var msg = jQuery.parseJSON(resp.responseText);
		if(msg.error){
			$.pnotify({
				title: 'Gagal',
				text: msg.message,
				type: 'error'
			});
		}
		/* showmessage(msg.error);
		jQuery('#message').addClass('red'); */
	}	
});

</script>
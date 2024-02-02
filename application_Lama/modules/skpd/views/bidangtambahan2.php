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
		url:'<?php echo base_url()?>skpd/get_bidangtambahan2',
		editurl:'',
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
        width:'540',
        height:'100%',
	});

	jQuery("#gridbidang").jqGrid( 'navGrid', '#pagerbidang', { 
		add: false,
		edit: false,
		del: false,
		search: false,
		refresh: false
	},{},{},{},{});
	
});

</script>
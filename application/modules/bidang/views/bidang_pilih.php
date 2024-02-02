<div id="searchformbidangtambahan">
	<div class='frm_jgrid'>
		<select name='sfield' id='sfield' class='span2'>
			<option value='kode_bidang_lkp'>Kode Bidang</option>
			<option value='nama_bidang'>Nama Bidang</option>
		</select>
		<select name='soper' id='soper' class='span2'>
			<option value='cn'>Memuat</option>
			<option value='bw'>Diawali</option>
		</select>
		<input type='text' name='svalue' id='svalue' size="40" class='span2'/>
		<a class="btn btn-primary" id="filter2" ><i class="icon-search icon-white" ></i> Filter</a>

		<table id="gridbidangtambahan" class="scroll"></table>
		<div id="pagerbidangtambahan" class="scroll"></div>
	</div>
</div>	
<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery("#gridbidangtambahan").jqGrid({
		url:'<?php echo base_url()?>bidang/get_bidang',
		datatype: "json",
        mtype:'POST',
        colNames:['ID', 'KODE BIDANG', 'NAMA BIDANG'],
        colModel:[{name:'id', index:'ID_BIDANG', width:140,editable:true, hidden:true},
		{name:'KODE_BIDANG_LKP', index:'KODE_BIDANG_LKP', width:60},
		{name:'NAMA_BIDANG', index:'NAMA_BIDANG',editable:true, width:200}],
        rowNum:10,
		rowList:[10,20,30],
		rownumbers:true,
        pager: '#pagerbidangtambahan',
		sortorder: "asc",
		multiselect:true,
        viewrecords: true,
        gridview:true,
        width:'540',
        height:'100%',
	});

	jQuery("#gridbidangtambahan").jqGrid( 'navGrid', '#pagerbidangtambahan', { 
		add: false,
		edit: false,
		del: false,
		search: false,
		refresh: true,
		refreshtext: 'Refresh'
	});
	
	jQuery('#filter2').click(function(){
		var field 	= jQuery("#sfield").val();
		var oper 	= jQuery("#soper").val();
		var string 	= jQuery("#svalue").val();
		
		var grid = jQuery("#gridbidangtambahan");
		var postdata = grid.jqGrid('getGridParam','postData');
		jQuery.extend (postdata,
					   {filters:'',
						searchField: field,
						searchOper: oper,
						searchString: string});
		grid.jqGrid('setGridParam', { search: true, postData: postdata });
		grid.trigger("reloadGrid",[{page:1}]);
	});
	
	jQuery('#svalue').keypress(function (e) {
		if (e.which == 13) {
			jQuery('#filter2').click();
		}
	}); 
});

</script>
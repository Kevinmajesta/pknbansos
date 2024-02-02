<div id="searchformskpd">
	<div class='frm_jgrid'>
		<select name='skpd_field' id='skpd_field' class='span2'>
			<option value="KODE_SKPD_LKP">Kode SKPD</option>
			<option value='NAMA_SKPD'>Nama SKPD</option>
		</select>
		<select name='skpd_oper' id='skpd_oper' class='span2'>
			<option value='cn'>Memuat</option>
			<option value='bw'>Diawali</option>
		</select>
		<input type='text' name='skpd_value' id='skpd_value' class="span2"/>
		<a class="btn btn-primary" href="#" id="filter_skpd2"><i class="icon-search icon-white"></i> Filter</a>
		<table id="gridSKPD" class="scroll"></table>
		<div id="pagerSKPD" class="scroll"></div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.button').button();
	jQuery("#gridSKPD").jqGrid({
		url:'<?php echo base_url()?>skpd/get_skpd_kua',
		datatype: "json",
        mtype:'POST',
        colNames:['ID SKPD','Kode SKPD', 'Nama SKPD'],
        colModel:[	
					{name:'id', index:'id', width:30,hidden:true},
					{name:'kode', index:'kode', width:30},
					{name:'nama', index:'nama', width:140,}],
        rowNum:10,
		rownumbers:true,
        pager: '#pagerSKPD',
		sortname:'kode',
		sortorder: "asc",
        viewrecords: true,
        gridview:true,
        width:'525',
        height:'230',
        caption: "Data SKPD" 
	});

	jQuery("#gridSKPD").jqGrid( 'navGrid', '#pagerSKPD', { 
		add: false,
		edit: false,
		del: false,
		search: false,
		refresh: true,
		refreshtext: 'Refresh'
	});
	
	jQuery('#filter_skpd2').click(function(){
		var field 	= jQuery("#skpd_field").val();
		var oper 	= jQuery("#skpd_oper").val();
		var string 	= jQuery("#skpd_value").val();
		
		var grid = jQuery("#gridSKPD");
		var postdata = grid.jqGrid('getGridParam','postData');
		jQuery.extend (postdata,
					   {filters:'',
						searchField: field,
						searchOper: oper,
						searchString: string});
		grid.jqGrid('setGridParam', { search: true, postData: postdata });
		grid.trigger("reloadGrid",[{page:1}]);
	});
	
	jQuery('#skpd_value').keypress(function (e) {
		if (e.which == 13) {
			jQuery('#filter_skpd2').click();
		}
	});
});

</script>
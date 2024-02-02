<select name="sfield" id="sfield" class="span2">
	<option value='nama_rekening'>Nama Rekening</option>
	<option value='kode_rekening'>Kode Rekening</option>									
</select>
<select name='soper' id='soper' class="span2">
	<option value='cn'>Memuat</option>
	<option value='bw'>Diawali</option>
</select>
<input type="text" name="svalue" id="svalue" class="span3">
<a class="btn btn-primary" href="#" id="filter2"><i class="icon-search icon-white"></i> Filter</a>
			
<table id="gridrekening" class="scroll"></table>
<div id="pagerrekening" class="scroll"></div>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery("#gridrekening").jqGrid({
		url:'<?php echo base_url()?>rekening/get_daftar_rekening',
		datatype: "json",
        mtype:'POST',
		colNames:['ID','KODE REKENING','NAMA REKENING','LEVEL REKENING'],
			colModel:[
				{name:'ID_REKENING',index:'ID_REKENING',width:50,editable:true,hidden:true},
				{name:'KODE_REKENING',index:'KODE_REKENING',width:75,editable:true,edittype:'text',editoptions:{size:20}},
				{name:'NAMA_REKENING', index:'NAMA_REKENING',width:210,editable:true,edittype:'text',editoptions:{size:50}},
				{name:'LEVEL_REKENING', index:'LEVEL_REKENING',width:50,hidden:true}
			],
        rowNum:10,
		rowList:[10,20,30],
		<?php
			if(isset($stat))
			{
				echo"
						multiselect:true,
					";
			}
		?>
		rownumbers:true,
        pager: '#pagerrekening',
		sortorder: "asc",
        viewrecords: true,
        gridview:true,
        width:'600',
        height:'225',
        //caption: "Data Rekening"
    rowattr: RowColor,
		ondblClickRow:function(){
			var idr = jQuery("#gridrekening").jqGrid('getGridParam','selrow');
			if(idr.length > 0){
				for (var i=0;i<idr.length;i++){
					var rr = jQuery("#gridrekening").jqGrid('getRowData', idr[i]); 
					if(rr.LEVEL_REKENING > 4){
						if(i == 0){
							id=rr.ID_REKENING;
							kode=rr.KODE_REKENING;
							nama = rr.NAMA_REKENING;
						}else{
							id +=rr.ID_REKENING;
							kode += rr.KODE_REKENING;
							nama += rr.NAMA_REKENING;
						}
					}
				}
				
				jQuery('#new_koderekening').val(kode);
				jQuery('#new_namarekening').val(nama);
				jQuery('#new_idrekening').val(id);
				
				var ids = jQuery("#grid").jqGrid('getGridParam','selrow'); 
				jQuery('#'+ids+'_koderekening').val(kode);
				jQuery('#'+ids+'_namarekening').val(nama);
				jQuery('#'+ids+'_idrekening').val(id);
 			
				jQuery("#rekening").dialog('close');
			}
			else{
				alert("Silahkan pilih salah satu data.");
			}
		}
	});

	jQuery("#gridrekening").jqGrid( 'navGrid', '#pagerrekening', { 
		add: false,
		edit: false,
		del: false,
		search: false,
		refresh: true,
		refreshtext: 'Refresh'
	});
	
	function RowColor(rek)
  {
    lvl = parseInt(rek.LEVEL_REKENING);
    switch (lvl){
      case 1 : return {"class": "row-level row-level-1"}; break;
      case 2 : return {"class": "row-level row-level-2"}; break;
      case 3 : return {"class": "row-level row-level-3"}; break;
      case 4 : return {"class": "row-level row-level-4"}; break;
      default : return '';
    }
  }
    
	jQuery('#filter2').click(function(){
		var field 	= jQuery("#sfield").val();
		var oper 	= jQuery("#soper").val();
		var string 	= jQuery("#svalue").val();
		
		var grid = jQuery("#gridrekening");
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
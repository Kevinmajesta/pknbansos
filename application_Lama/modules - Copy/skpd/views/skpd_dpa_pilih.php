<div id="searchformskpd">
	<div class='frm_jgrid'>
		<table class='tablefilterpilih' width='525'>
			<tr>
				<td>
					<select name='skpd_field' id='skpd_field' class='search1'>
						<option value='NAMA_SKPD'>Nama SKPD</option>
						<option value="KODE_SKPD_LKP">Kode SKPD</option>
					</select>
				</td>
				<td>
					<select name='skpd_oper' id='skpd_oper' class='search2'>
						<option value='cn'>Memuat</option>
						<option value='bw'>Diawali</option>
					</select>
				</td>
				<td>:</td>
				<td><input type='text' name='skpd_value' id='skpd_value' size="45"/></td>
				<td><input type='button' id='filter_skpd2' value='Filter' class='buttonfilter' /></td>
			</tr>
		</table>
		<table id="gridSKPD" class="scroll"></table>
		<div id="pagerSKPD" class="scroll"></div>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.button').button();
	jQuery("#gridSKPD").jqGrid({
		url:'<?php echo base_url()?>skpd/get_skpd_dpa',
		datatype: "json",
        mtype:'POST',
        colNames:['Kode SKPD', 'Nama SKPD', 'PEJABAT', 'FORM ANGGARAN'],
        colModel:[	{name:'kode', index:'kode', width:30},
					{name:'nama', index:'nama', width:140},
					{name:'pejabat_skpd', index:'pejabat_skpd', width:35,hidden:true},
					{name:'form_anggaran', index:'form_anggaran', width:35,hidden:true}],
        rowNum:10,
		rownumbers:true,
        pager: '#pagerSKPD',
		sortname:'kode',
		sortorder: "asc",
        viewrecords: true,
        gridview:true,
        width:'525',
        height:'230',
        caption: "Data SKPD",
		ondblClickRow:function(){
			var id = jQuery("#gridSKPD").jqGrid('getGridParam','selrow'); 
			var rs = jQuery("#gridSKPD").jqGrid('getRowData', id); 
			var kode = rs.kode;
			var nama = rs.nama;
			var pejabat_skpd = rs.pejabat_skpd;
			var form_anggaran = rs.form_anggaran;
			jQuery('#ID_SKPD').val(id);
			jQuery('#KODE_SKPD').val(kode);
			jQuery('#NAMA_SKPD').val(nama);
			jQuery('#PEJABAT_SKPD').val(pejabat_skpd);
			jQuery('#FORM_ANGGARAN').val(form_anggaran);
			lastsel = null;
			jQuery('.kosong').remove();
			jQuery('.options').remove();
			jQuery('#KODE_KEGIATAN_SKPD').val('');
			jQuery('#KODE_KEGIATAN').val('');
			jQuery('#NAMA_KEGIATAN').val('');
			var id_skpd = jQuery('#ID_SKPD').val();
			
			//cari pejabat daerah
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd/get_pejabat_daerah/', function(json) {
				jQuery.each(json.rows, function(id, val){
					jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_DAERAH');
				});
			});

			jQuery.getJSON('<?php echo base_url();?>dpa_skpd/get_rincian_anggaran/'+id_skpd, function(json) {
				jQuery.each(json.rows, function(id, val){
					rincian = {ID_REKENING:val.ID_REKENING, ID_PARENT_REKENING:val.ID_PARENT_REKENING, KODE_REKENING:val.KODE_REKENING, NAMA_REKENING:val.NAMA_REKENING, PAGU:val.PAGU, LEVEL:val.LEVEL};
					jQuery("#tableRincianAnggaran").jqGrid('addRowData',val.ID,rincian);
					RowColor();
					
				});
				if(json.surplus){
					jQuery('#SURPLUS').val(json.surplus.SURPLUS);
					jQuery('#SURPLUS').formatCurrency({symbol:''});
					jQuery('#SURPLUS').autoNumeric({vMax: '999999999999999'}); 
				}
				if(json.netto){
					jQuery('#NETTO').val(json.netto.NETTO);
					jQuery('#NETTO').formatCurrency({symbol:''});
					jQuery('#NETTO').autoNumeric({vMax: '999999999999999'}); 
				}
			});
			
			jQuery('#id_pjb').val(pejabat_skpd);
			jQuery('#id_rka').val(form_anggaran);
			//jQuery(this).dialog('close');
			jQuery('#SKPD').dialog('close');
		}
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
	
	function RowColor()
	{
		var idcolor = jQuery('#tableRincianAnggaran').jqGrid('getDataIDs');
		for(var i=0; i<=idcolor.length; i++)
		{
			var rowcolor = jQuery('#tableRincianAnggaran').jqGrid('getRowData', idcolor[i]);
			if(rowcolor.LEVEL < 3)
			{
				var rows = jQuery('#'+idcolor[i], jQuery('#tableRincianAnggaran')).find('td');
                rows.css("color", "#666666");
                rows.css("background-color", "#DEDEDE");
                rows.css("font-weight", "bold");
			}
		}
    }
});

</script>
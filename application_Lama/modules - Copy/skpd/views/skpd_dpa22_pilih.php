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
		url:'<?php echo base_url()?>skpd/get_skpd_dpa22',
		datatype: "json",
        mtype:'POST',
        colNames:['Kode SKPD', 'Nama SKPD', 'Form Anggaran'],
        colModel:[	{name:'kode', index:'kode', width:30},
					{name:'nama', index:'nama', width:140},
					{name:'form_anggaran', index:'form_anggaran', width:5,search:false, hidden:true}],
        //rowNum:10,
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
			var form_anggaran = rs.form_anggaran;
			jQuery('#ID_SKPD').val(id);
			jQuery('#KODE_SKPD').val(kode);
			jQuery('#NAMA_SKPD').val(nama);
			jQuery('#ID_FORM_ANGGARAN').val(form_anggaran);
			ubah = true;
			lastsel = null;
			jQuery('.kosong').remove();
			jQuery('.options').remove();
			jQuery('#KODE_KEGIATAN_SKPD').val('');
			jQuery('#KODE_KEGIATAN').val('');
			jQuery('#NAMA_KEGIATAN').val('');
			jQuery("#tableRincianAnggaran").clearGridData();
			
			//cari pejabat skpd
			var id_skpd = jQuery('#ID_SKPD').val();
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd22/get_pejabat/'+id_skpd, function(json) {
				jQuery.each(json.rows, function(id, val){
					jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_SKPD');
				});
			});
			
			//cari pejabat daerah
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd22/get_pejabat_daerah/'+id_skpd, function(json) {
			jQuery('<option value="" class="options"></option>').appendTo('#PEJABAT_DAERAH');
			jQuery.each(json.rows, function(id,val){
				jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_DAERAH');
			});
			var id_pejabat_daerah = jQuery('#id_pjb_daerah').val();
			jQuery('#PEJABAT_DAERAH').val(id_pejabat_daerah);
			});
			
			//cari rincian anggaran
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd22/get_rincian_anggaran/'+id_skpd, function(json) {
				jQuery.each(json.rows, function(id, val){
					rincian = {ID_FORM_ANGGARAN:val.ID_FORM_ANGGARAN, KODE_PROGRAM:val.KODE_PROGRAM, NAMA_PROGRAM:val.NAMA_PROGRAM, KODE_KEGIATAN_LKP:val.KODE_KEGIATAN_LKP, NAMA_KEGIATAN:val.NAMA_KEGIATAN, LOKASI:val.LOKASI, NAMA_SUMBER_DANA:val.NAMA_SUMBER_DANA, TRIWULAN_1:val.TRIWULAN_1, TRIWULAN_2:val.TRIWULAN_2, TRIWULAN_3:val.TRIWULAN_3, TRIWULAN_4:val.TRIWULAN_4, NOMINAL_ANGGARAN:val.NOMINAL_ANGGARAN, LEVEL:val.LEVEL, JUMLAH:val.JUMLAH};
					jQuery("#tableRincianAnggaran").jqGrid('addRowData',val.ID_FORM_ANGGARAN,rincian);
					
					var NOMINAL = val.JUMLAH;
					jQuery('#JML_TOTAL').val(NOMINAL);
					
					jQuery('#JML_TOTAL').formatCurrency({symbol:''});
					jQuery('#JML_TOTAL').autoNumeric({vMax: '999999999999999'});
					
					RowColor();
				});
			});
			
			jQuery('#id_rka').val(form_anggaran);

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
			if(rowcolor.LEVEL < 2)
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
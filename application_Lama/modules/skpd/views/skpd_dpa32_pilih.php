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
		url:'<?php echo base_url()?>skpd/get_skpd_dpa32',
		datatype: "json",
        mtype:'POST',
        colNames:['Kode SKPD', 'Nama SKPD', 'FORM ANGGARAN'],
        colModel:[	{name:'kode', index:'kode', width:30},
					{name:'nama', index:'nama', width:140},
					{name:'form_anggaran', index:'form_anggaran', width:5,search:false, hidden:true}],
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
			jQuery('#FORM_ANGGARAN').val(form_anggaran);
			ubah = true;
			lastsel = null;
			jQuery('.kosong').remove();
			jQuery('.options').remove();
			var id_skpd = jQuery('#ID_SKPD').val();
			//cari pejabat skpd
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd32/get_pejabat/'+id_skpd, function(json) {
				jQuery.each(json.rows, function(id, val){
					jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_SKPD');
				});
			});
			
			//cari pejabat daerah
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd32/get_pejabat_daerah/', function(json) {
				jQuery('<option value="" class="options"></option>').appendTo('#PEJABAT_DAERAH');
				jQuery.each(json.rows, function(id, val){
					jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_DAERAH');
				});
			});
			
			//cari rincian anggaran
			jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {datatype:'json'});
			jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {url:'<?php echo base_url();?>dpa_skpd32/get_daftar_rincian/'+form_anggaran})
			jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {gridComplete:function(){
				var idr = jQuery('#tableRincianAnggaran').jqGrid('getDataIDs');
				for(var i=0; i<idr.length; i++)
				{
					jQuery('#tableRincianAnggaran').jqGrid('setCell', idr[i], 'ID_RINCIAN_ANGGARAN', null, null, null, true);
				}
			}
			}).trigger('reloadGrid');
			
			//cari anggaran kas
			jQuery('#tableAnggaranKas').jqGrid('setGridParam', {datatype:'json'});
			jQuery('#tableAnggaranKas').jqGrid('setGridParam', {url:'<?php echo base_url();?>dpa_skpd32/get_anggaran_kas/'+form_anggaran})
			jQuery('#tableAnggaranKas').jqGrid('setGridParam', {gridComplete:function(){
				var idr = jQuery('#tableAnggaranKas').jqGrid('getDataIDs');
				for(var i=0; i<idr.length; i++)
				{
					jQuery('#tableAnggaranKas').jqGrid('setCell', idr[i], 'JANUARI', null, null, null, true);
				}
			}
			}).trigger('reloadGrid');
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
});

</script>
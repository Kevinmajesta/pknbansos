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
		url:'<?php echo base_url()?>skpd/get_skpd_dpa221',
		datatype: "json",
        mtype:'POST',
        colNames:['Kode SKPD', 'Nama SKPD'],
        colModel:[	{name:'kode', index:'kode', width:30},
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
        caption: "Data SKPD",
		ondblClickRow:function(){
			var id = jQuery("#gridSKPD").jqGrid('getGridParam','selrow'); 
			var rs = jQuery("#gridSKPD").jqGrid('getRowData', id); 
			var kode = rs.kode;
			var nama = rs.nama;
			jQuery('#ID_SKPD').val(id);
			jQuery('#KODE_SKPD').val(kode);
			jQuery('#NAMA_SKPD').val(nama);
			ubah = true;
			lastsel = null;
			jQuery('.kosong').remove();
			jQuery('.options').remove();
			jQuery('#KODE_KEGIATAN_SKPD').val('');
			jQuery('#KODE_KEGIATAN').val('');
			jQuery('#NAMA_KEGIATAN').val('');
			var id_skpd = jQuery('#ID_SKPD').val();
			
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd221/get_pejabat/'+id_skpd, function(json) {
				jQuery.each(json.rows, function(id, val){
					jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_SKPD');
				});
			});
			jQuery.getJSON('<?php echo base_url();?>dpa_skpd221/get_pejabat_daerah/', function(json) {
				jQuery('<option value="" class="options"></option>').appendTo('#PEJABAT_DAERAH');
				jQuery.each(json.rows, function(id, val){
					jQuery('<option value="' + val.id + '" class="options">' + val.jabatan + ' - '+val.nama+'</option>').appendTo('#PEJABAT_DAERAH');
				});
			});
			
			/* dialog kegiatan SKPD */
			function pilih_kegiatan()
			{
				var idk = jQuery("#gridgiat").jqGrid('getGridParam','selrow'); 
				if(idk)
				{
					var skpd = jQuery('#ID_SKPD').val();
					var kegiatan = jQuery('#ID_KEGIATAN').val();
					jQuery.getJSON('<?php echo base_url();?>dpa_skpd221/cek_murni/'+skpd+'/'+kegiatan, function(json){
						if(json.rows.status == true)
						{
							jQuery('#tableIndikatorKinerja').jqGrid('setGridParam', {datatype:'json'});
							jQuery('#tableIndikatorKinerja').jqGrid('setGridParam', {url:'<?php echo base_url();?>dpa_skpd221/get_daftar_indikator/' + json.rows.form + '/' + true})
							jQuery('#tableIndikatorKinerja').jqGrid('setGridParam', {loadComplete:function(){
									var idi = jQuery('#tableIndikatorKinerja').jqGrid('getDataIDs');
									for(var i=0; i<idi.length; i++)
									{
										jQuery('#tableIndikatorKinerja').jqGrid('setCell', idi[i], 'ID_INDIKATOR_KINERJA', null, null, null, true);
									}
								}
								}).trigger('reloadGrid');
								
							jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {datatype:'json'});
							jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {rowNum:-1});
							jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {url:'<?php echo base_url();?>dpa_skpd221/get_daftar_rincian/' + json.rows.form+ '/' + true})
							jQuery('#tableRincianAnggaran').jqGrid('setGridParam', {gridComplete:function(){
									var idr = jQuery('#tableRincianAnggaran').jqGrid('getDataIDs');
									for(var i=0; i<idr.length; i++)
									{
										jQuery('#tableRincianAnggaran').jqGrid('setCell', idr[i], 'ID_RINCIAN_ANGGARAN', null, null, null, true);
									}
								}
								}).trigger('reloadGrid');
								
							jQuery('#tableAnggaranKas').jqGrid('setGridParam', {datatype:'json'});
							jQuery('#tableAnggaranKas').jqGrid('setGridParam', {url:'<?php echo base_url();?>dpa_skpd221/get_anggaran_kas/' + json.rows.form+ '/' + true})
							jQuery('#tableAnggaranKas').jqGrid('setGridParam', {gridComplete:function(){
									var ids = jQuery('#tableAnggaranKas').jqGrid('getDataIDs');
									for(var i=0; i<ids.length; i++)
									{
										jQuery('#tableAnggaranKas').jqGrid('setCell', ids[i], 'JANUARI', null, null, null, true);
									}
								}
								}).trigger('reloadGrid');
							jQuery('#id_rka').val(json.rows.form);
						}
					});
					jQuery(this).dialog('close');
				}
				else 
				{ 
					alert("Silahkan pilih salah satu data.");
				} 
			}
			jQuery('#kegiatanSKPD').dialog({
				title:'Pilih Kegiatan SKPD',
				height:450,
				width:650,
				modal:true,
				autoOpen:false,
				closeOnEscape:true,
				buttons: {
							'Pilih': pilih_kegiatan,
							'Tutup': function() { jQuery(this).dialog('close'); }
						}
			}).load('<?php echo base_url();?>kegiatan/pilih_dpa221');
				
			jQuery('#kegiatanSKPD').dialog('open');
			/*end dialog kegiatan SKPD*/
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
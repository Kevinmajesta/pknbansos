	<div class='cntr_form'>
		<div class="title_form">Entry Pejabat SKPD</div>
		<form class='form' enctype="multipart/form-data" action="<?php echo base_url()?>skpd/proses_form_pejabat" method="POST">
			<fieldset class='fieldset' id='fd_skpd'>
				<legend class='legend'>SKPD</legend>
					<div class='fd_left'>
						<div><label>Kode SKPD :</label>
						<input type="text" id="KODE_SKPD" name="KODE_SKPD" size="50" readonly="1" value="<?php echo isset($user_data["KODE_SKPD"]) ? $user_data["KODE_SKPD"] : ""; ?>" />
						<input type="hidden" id="ID_SKPD" name="ID_SKPD" value="<?php echo isset($user_data["ID_SKPD"]) ? $user_data["ID_SKPD"] : ""; ?>" />
						<?php echo form_button( array('name' => 'btn_skpd', 'id' => 'btn_skpd', 'content' => 'Pilih SKPD') );?>
						</div>
					</div>
					<div class='fd_left'>
						<div><label>Nama SKPD :</label>
						<input type='text' id='NAMA_SKPD' name='NAMA_SKPD' size="50" value='<?php echo isset($user_data["NAMA_SKPD"]) ? $user_data["NAMA_SKPD"] : ""; ?>'></div>
					</div>
			</fieldset>

			<fieldset class='fieldset' id='fd_pejabat_skpd'>
				<legend class='legend'>Pejabat SKPD</legend>
					<div class='fd_left'>
						<div><label>Jabatan<b class='wajib'>*</b> :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["JABATAN"]) ? $user_data["JABATAN"] : ""; ?>' name='JABATAN' />
						</div>
						<div><label>Nama Pejabat<b class='wajib'>*</b> :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["NAMA_PEJABAT"]) ? $user_data["NAMA_PEJABAT"] : ""; ?>' name='NAMA_PEJABAT' />
						</div>
						<div><label>NIP :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["NIP"]) ? $user_data["NIP"] : ""; ?>' name='NIP' />
						</div>
						<div><label>Status :</label>
								<?php
									$AKTIF = array(
										'1' => 'Aktif',
										'0' => 'Tidak Aktif'
									);
									$js = 'id="AKTIF" name="AKTIF"';
									echo form_dropdown('AKTIF', $AKTIF, (isset($user_data['AKTIF']) ? $user_data['AKTIF'] : ''), $js);
								?>
						</div>
					</div>
			</fieldset>
			
			<div id='searchformskpd'>
				<div class='frm_jgrid'>
					<div class="ptoggle"><a class="uppane" title="Hide/Show Pane"></a></div>
					<table class='form dpane headpane'>
						<tr>
							<td><input type='checkbox' name='ck_teks_skpd' id='ck_teks_skpd'></td>
							<td>
								<select name='op_teks_skpd' id='op_teks_skpd'>
									<option value='KODE_SKPD_LKP'>Kode SKPD</option>
									<option value='NAMA_SKPD'>Nama SKPD</option>
								</select>
							</td>
							<td>
								<select name='op_filter_skpd' id='op_filter_skpd'>
									<option value='bw'>Diawali</option>
									<option value='cn'>Memuat</option>
								</select>
							</td>
							<td>:</td>
							<td colspan='3'><input type='text' name='in_teks_skpd' id='in_teks_skpd' size="50"/></td>
						</tr>
						<tr> <input type='button' id='filter_skpd2' value='Filter' /> </tr>
					</table>
				</div>
				<div class='frm_jgrid'>	
					<table id="skpdTable" class="scroll"></table>
					<div id="skpdPager" class="scroll"></div>
				</div>
			</div>
			
			<div id='cntr_button'>
				<input type='hidden' value='<?php echo isset($user_data['update']) ? $user_data['update'] : ""; ?>' name='update' />
				<input type='hidden' value='<?php echo isset($user_data['ID_PEJABAT_SKPD']) ? $user_data['ID_PEJABAT_SKPD'] : ""; ?>' name='ID_PEJABAT_SKPD' />

				<?php
				$extra_attribut2 = 'id="btn_save" class="ui-button ui-widget ui-state-default ui-corner-all "';	
				echo form_submit('skpd_submit','Simpan',$extra_attribut2);
				
				echo form_reset('skpd_reset','Batal',$extra_attribut2);
				?>
				
				<?php
					if (isset($user_data['ID_PEJABAT_SKPD']))
					{
						echo '<input type="button" value="Tambah Data" name="tambah_data_update"  id="btn_tambah_update"/>';
					}
					else
					{
						echo '<input type="button" value="Tambah Data" name="tambah_data_insert"  id="btn_tambah_insert"/>';
					}
				?>
			</div>
			<div class='clear'></div>
		</form>			
	</div>

	<script type="text/javaScript">

	jQuery(document).ready(function(){

		/* SKPD */		
		jQuery('#btn_skpd').click(function() {		
				jQuery('#skpdTable').trigger('reloadGrid');
				jQuery('#searchformskpd').dialog('open');
		});

		jQuery('#searchformskpd').dialog({
			title:'Pilih SKPD',
			height:400,
			width:600,
			modal:true,
			autoOpen:false,
			closeOnEscape:true,
			buttons: {
				
				'Pilih': function(){
					var id = jQuery("#skpdTable").jqGrid('getGridParam','selrow'); 
							if (id) 
							{ 
							var rs = jQuery("#skpdTable").jqGrid('getRowData',id); 
							jQuery('#ID_SKPD').val(rs.id);
							jQuery('#KODE_SKPD').val(rs.kode);
							jQuery('#NAMA_SKPD').val(rs.nama);
							jQuery('#KODE_SKPD_LBL').val(rs.kode+' - '+rs.nama);
							jQuery('#searchformskpd').dialog('close');
							} 
							else { alert("Tolong pilih barisnya.");} 
				},
				'Tutup': function() {
					jQuery(this).dialog('close');
				}
				
			}
		 });	

		jQuery("#skpdTable").jqGrid({ 
			url:'<?php echo base_url()?>skpd/daftar_subskpd', 
			datatype: "json", 
			mtype: "POST",
			colNames:['ID','KODE','NAMA'], 
			colModel:[ 
				{name:'id',index:'ID_SKPD', width:20, search:false, hidden:true}, 
				{name:'kode',index:'KODE_SKPD', width:60, align:"left", editable:true, edittype:'text', editoptions: {size:10, maxlength: 10},editrules: {required:true}}, 
				{name:'nama',index:'NAMA_SKPD', width:400, align:"left", editable:true, edittype:'text', editoptions: {size:100, maxlength: 200},editrules: {required:true}}
				], 
			rowNum:10, 
			rownumbers: true,
			rowList:[10,20,30], 
			pager: '#skpdPager', 
			sortname: 'ID_SKPD', 
			viewrecords: true, 
			multiselect: true,
			multiboxonly: true,
			shrinkToFit: false,
			sortorder: "asc", 
			width: 525,
			height: '100%',
			ondblClickRow:function(id){		
				var rs = jQuery('#skpdTable').getRowData(id);
				
				jQuery('#ID_SKPD').val(rs.id);
				jQuery('#KODE_SKPD').val(rs.kode);
				jQuery('#NAMA_SKPD').val(rs.nama);
				jQuery('#KODE_SKPD_LBL').val(rs.kode+' - '+rs.nama);
				jQuery('#searchformskpd').dialog('close');
				
			}			
			//caption:"Daftar SKPD" 
		}).navGrid('#skpdPager'
			,{add:false,edit:false,del:false,view:false,search:false,refresh:true}
		); 
		
		jQuery('#filter_skpd2').click(function(){
			var filter=''; var data = {};
					
			if(jQuery('#ck_teks_skpd').attr('checked')){
						filter+='/op_teks_skpd/'+jQuery('#op_teks_skpd').val(); data.op_teks_skpd = jQuery('#op_teks_skpd').val();
						filter+='/op_filter_skpd/'+jQuery('#op_filter_skpd').val(); data.op_filter_skpd = jQuery('#op_filter_skpd').val();
						filter+='/in_teks_skpd/'+jQuery('#in_teks_skpd').val(); data.in_teks_skpd = jQuery('#in_teks_skpd').val();
					}
			
			jQuery.post('<?php echo base_url()?>skpd/filter_skpd',data,function(rs){
					jQuery('#skpdTable').trigger('reloadGrid');
				});	
				
			});
		
		jQuery("#bsdata").click(function(){ 
			jQuery("#skpdTable").jqGrid('searchGrid',
			{add:false,edit:false,del:false,search:true}, 
			{sopt:['cn','bw','eq','ne','lt','gt','ew','le','ge']},
		{multipleSearch:true} ); }); 
		
		$("#btn_tambah_insert").click( function(){
			var JABATAN	= jQuery('input[name=JABATAN]').val();
			var NAMA_PEJABAT	= jQuery('input[name=NAMA_PEJABAT]').val();
			var NIP	= jQuery('input[name=NIP]').val();
			var AKTIF = $("#AKTIF").val();
			var ID_SKPD	= jQuery('input[name=ID_SKPD]').val();
					
			jQuery.post("<?php echo base_url()?>skpd/proses_pejabat_form_insert",{
			'JABATAN':JABATAN,
			'NAMA_PEJABAT':NAMA_PEJABAT,
			'NIP':NIP,
			'AKTIF':AKTIF,
			'ID_SKPD':ID_SKPD
			},function(data){
				 alert(NAMA_PEJABAT + " berhasil ditambahkan");
			});
			   
			$('.form').clearForm();
		});
		
		$("#btn_tambah_update").click( function(){
			var ID_PEJABAT_SKPD	= jQuery('input[name=ID_PEJABAT_SKPD]').val();
			var JABATAN	= jQuery('input[name=JABATAN]').val();
			var NAMA_PEJABAT	= jQuery('input[name=NAMA_PEJABAT]').val();
			var NIP	= jQuery('input[name=NIP]').val();
			var AKTIF = $("#AKTIF").val();
			var ID_SKPD	= jQuery('input[name=ID_SKPD]').val();
			
			jQuery.post("<?php echo base_url()?>skpd/proses_pejabat_form_update",{
			'ID_PEJABAT_SKPD':ID_PEJABAT_SKPD,
			'JABATAN':JABATAN,
			'NAMA_PEJABAT':NAMA_PEJABAT,
			'NIP':NIP,
			'AKTIF':AKTIF,
			'ID_SKPD':ID_SKPD
			},function(data){
				 alert(NAMA_PEJABAT + " berhasil diupdate");
			});
			   
			$('.form').clearForm();
		});
	})
	</script>
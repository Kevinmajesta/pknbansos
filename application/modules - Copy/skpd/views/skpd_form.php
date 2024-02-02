	<div class='cntr_form'>
		<div class="title_form">Entry SKPD</div>
		<form class='form' id="frmEntri" enctype="multipart/form-data" action="<?php echo base_url()?>skpd/proses_form" method="POST">
			<fieldset class='fieldset' id='fd_urusan'>
				<legend class='legend'>Urusan</legend>
					<div class='fd_left'>
						<div><label>Kode Urusan :</label>
						<input type="text" id="KODE_URUSAN" name="KODE_URUSAN" size="50" readonly="1" value="<?php echo isset($user_data["KODE_URUSAN"]) ? $user_data["KODE_URUSAN"] : ""; ?>" />
						<input type="hidden" id="ID_URUSAN" name="ID_URUSAN" value="<?php echo isset($user_data["ID_URUSAN"]) ? $user_data["ID_URUSAN"] : ""; ?>" />
						<?php echo form_button( array('name' => 'btn_urusan','class'=> 'button2', 'id' => 'btn_urusan', 'content' => 'Pilih Urusan') );?>
						</div>
					</div>
					<div class='fd_left'>
						<div><label>Nama Urusan :</label>
						<input type='text' id='NAMA_URUSAN' name='NAMA_URUSAN' size="90" readonly="1" value='<?php echo isset($user_data["NAMA_URUSAN"]) ? $user_data["NAMA_URUSAN"] : ""; ?>'></div>
					</div>
			</fieldset>

			<fieldset class='fieldset' id='fd_bidang'>
				<legend class='legend'>Bidang</legend>
					<div class='fd_left'>
						<div><label>Kode Bidang :</label>
						<input type="text" id="KODE_BIDANG" name="KODE_BIDANG" size="50" readonly="1" value="<?php echo isset($user_data["KODE_BIDANG"]) ? $user_data["KODE_BIDANG"] : ""; ?>" />
						<input type="hidden" id="ID_BIDANG" name="ID_BIDANG" value="<?php echo isset($user_data["ID_BIDANG"]) ? $user_data["ID_BIDANG"] : ""; ?>" />
						<?php echo form_button( array('name' => 'btn_bidang', 'id' => 'btn_bidang','class'=>'button2', 'content' => 'Pilih Bidang') );?>
						</div>
					</div>
					<div class='fd_left'>
						<div><label>Nama Bidang :</label>
						<input type='text' id='NAMA_BIDANG' name='NAMA_BIDANG' size="90" readonly="1" value='<?php echo isset($user_data["NAMA_BIDANG"]) ? $user_data["NAMA_BIDANG"] : ""; ?>'></div>
					</div>
			</fieldset>
			
			<fieldset class='fieldset' id='fd_skpd'>
				<legend class='legend'>SKPD</legend>
					<div class='fd_left'>
						<div><label>Kode SKPD<b class='wajib'>*</b> :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["KODE_SKPD"]) ? $user_data["KODE_SKPD"] : ""; ?>' name='KODE_SKPD' id="KODE_SKPD" >
						</div>
						<div><label>Nama SKPD<b class='wajib'>*</b> :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["NAMA_SKPD"]) ? $user_data["NAMA_SKPD"] : ""; ?>' name='NAMA_SKPD' id="NAMA_SKPD" >
						</div>
						<div><label>Lokasi :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["LOKASI"]) ? $user_data["LOKASI"] : ""; ?>' name='LOKASI' id="LOKASI">
						</div>
						<div><label>Alamat :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["ALAMAT"]) ? $user_data["ALAMAT"] : ""; ?>' name='ALAMAT' id="ALAMAT" >
						</div>
						<div><label>NPWP :</label>
							<input type='text' size="50"  value='<?php echo isset($user_data["NPWP"]) ? $user_data["NPWP"] : ""; ?>' name='NPWP' id="NPWP">
						</div>
						<div>
							<input type="hidden" id="PLAFON_BTL" name="PLAFON_BTL" value="0.00" />
						</div>
					</div>
			</fieldset>
			
			<div id='searchformurusan'>
				<div class='frm_jgrid'>
					<div class="ptoggle"><a class="uppane" title="Hide/Show Pane"></a></div>
					<table class='tablefilterpilih'>
						<tr>
							<td><input type='checkbox' name='ck_teks_urusan' id='ck_teks_urusan'></td>
							<td>
								<select name='op_teks_urusan' id='op_teks_urusan'>
									<option value='KODE_URUSAN'>Kode Urusan</option>
									<option value='NAMA_URUSAN'>Nama Urusan</option>
								</select>
							</td>
							<td>
								<select name='op_filter_urusan' id='op_filter_urusan'>
									<option value='bw'>Diawali</option>
									<option value='cn'>Memuat</option>
								</select>
							</td>
							<td>:</td>
							<td colspan='3'><input type='text' name='in_teks_urusan' id='in_teks_urusan' size="40"/></td>
							<td><input type='button' class='button2' id='filter_urusan2' value='Filter' /></td></tr>
					</table>
				</div>
				<div class='frm_jgrid'>	
					<table id="urusanTable" class="scroll"></table>
					<div id="urusanPager" class="scroll"></div>
				</div>
			</div>
			
			<div id='searchformbidang'>
				<div class='frm_jgrid'>
					<div class="ptoggle"><a class="uppane" title="Hide/Show Pane"></a></div>
					<table class='tablefilterpilih'>
						<tr>
							<td><input type='checkbox' name='ck_teks_bidang' id='ck_teks_bidang'></td>
							<td>
								<select name='op_teks_bidang' id='op_teks_bidang'>
									<option value='KODE_BIDANG'>Kode Bidang</option>
									<option value='NAMA_BIDANG'>Nama Bidang</option>
								</select>
							</td>
							<td>
								<select name='op_filter_bidang' id='op_filter_bidang'>
									<option value='bw'>Diawali</option>
									<option value='cn'>Memuat</option>
								</select>
							</td>
							<td>:</td>
							<td colspan='3'><input type='text' name='in_teks_bidang' id='in_teks_bidang' size="40"/></td>
							<td><input type='button' class='button2' id='filter_bidang2' value='Filter' /></td></tr>
					</table>
				</div>
				<div class='frm_jgrid'>	
					<table id="bidangTable" class="scroll"></table>
					<div id="bidangPager" class="scroll"></div>
				</div>
			</div>
			
			<div id='cntr_button'>
				<input type='hidden' name='data' id='data' value='' />
				<input type='hidden' value='<?php echo isset($user_data['update']) ? $user_data['update'] : ""; ?>' name='update' />
				<input type='hidden' value='<?php echo isset($user_data['ID_SKPD']) ? $user_data['ID_SKPD'] : ""; ?>' name='ID_SKPD' />
				<?php
				$extra_attribut1 = 'id="btnSave" class="button1" style="margin-right:0.3em"';	
				$extra_attribut2 = 'id="btnBack" class="button1"';	
				echo form_button('skpd_submit','Simpan',$extra_attribut1);
				
				echo form_reset('skpd_reset','Batal',$extra_attribut2);
				?>
				
				<?php
					if (isset($user_data['ID_SKPD']))
					{
						echo '<input type="button" value="Tambah Data" class="button1" style="margin-right:-0.3em" name="tambah_data_update"  id="btn_tambah_update"/>';
					}
					else
					{
						echo '<input type="button" value="Tambah Data" name="tambah_data_insert" class="button1" style="margin-right:-0.3em" id="btn_tambah_insert"/>';
					}
				?>
			</div>
			<div class='clear'></div>
		</form>			
	</div>
		
	<script type="text/javaScript">

	jQuery(document).ready(function(){
		var last;
		
		jQuery('#frmEntri').ajaxForm();
		
		jQuery('#btnBack').click( function(){ location.href = "<?php echo base_url()?>skpd"; });
	
		jQuery('#btnSave').click( function(){
			save();
		}); 
		
		function showmessage(msg){
			jQuery('#message').html(msg).show(100).delay(5000).hide(500);
		}
		
		function save(addNew){
		var gridData = jQuery("#grid").getRowData();
		var jsonData = JSON.stringify(gridData);
		jQuery('#data').val(jsonData);

		var options = {
			beforeSubmit: function(arr, $form, options) {
				// The array of form data takes the following form:
				// [ { name: 'username', value: 'jresig' }, { name: 'password', value: 'secret' } ]
				
				// Validate data 
				// if not valid then return false to cancel submit
				
			if (jQuery('#KODE_SKPD').val() == ''){
				showmessage('Kode SKPD harus di isi');
				return false;
			}	
			if (jQuery('#NAMA_SKPD').val() == ''){
				showmessage('Nama SKPD harus di isi');
				return false;
			}
			if (jQuery('#LOKASI').val() == ''){
				showmessage('Lokasi harus di isi');
				return false;
			}
			if (jQuery('#ALAMAT').val() == ''){
				showmessage('Alamat harus di isi');
				return false;
			}
			if (jQuery('#NPWP').val() == ''){
				showmessage('NPWP harus di isi');
				return false;
			}
			
			},
			success: function(resp, stat, data) {
				var msg = jQuery.parseJSON(resp);
				if(msg.success){
					showmessage(msg.success);
					
					location.href="<?php echo base_url()?>skpd/index";
				}
			}
		};

		jQuery('#frmEntri').ajaxSubmit(options);	
		return false;
	}
	
	
	
		/* Pilih Urusan */
		jQuery('#btn_urusan').click(function() {		
				jQuery('#urusanTable').trigger('reloadGrid');
				jQuery('#searchformurusan').dialog('open');
		});

		jQuery('#searchformurusan').dialog({
				title:'Pilih Urusan',
				height:400,
				width:570,
				modal:true,
				autoOpen:false,
				closeOnEscape:true,
				buttons: {
					'Pilih': function(){
						var id = jQuery("#urusanTable").jqGrid('getGridParam','selrow'); 
								if (id) 
								{ 
								var rs = jQuery("#urusanTable").jqGrid('getRowData',id); 
								jQuery('#ID_URUSAN').val(rs.id);
								jQuery('#KODE_URUSAN').val(rs.kode);
								jQuery('#NAMA_URUSAN').val(rs.nama);
								jQuery('#KODE_URUSAN_LBL').val(rs.kode+' - '+rs.nama);
								
									var id = jQuery("#urusanTable").jqGrid('getGridParam','selrow'); 
									if (id) { var ret = jQuery("#urusanTable").jqGrid('getRowData',id);
										//alert(ret.id);
										var url = "<?php echo base_url()?>skpd/daftar_subbidang"+"/"+ret.id;
										jQuery('#bidangTable').setGridParam({url:url}).trigger("reloadGrid");
										jQuery("#bidangTable").jqGrid('setGridParam').trigger("reloadGrid",[{page:1}]);
										
									} else { 
									alert("Please select row");}
								
								jQuery('#searchformurusan').dialog('close');
								} 
								else { alert("Tolong pilih barisnya.");} 
					},
					'Tutup': function() {
						jQuery(this).dialog('close');
					},
				}
			 });	

		jQuery("#urusanTable").jqGrid({ 
				url:'<?php echo base_url()?>skpd/daftar_suburusan', 
				datatype: "json", 
				mtype: "POST",
				colNames:['ID','KODE URUSAN','NAMA URUSAN'], 
				colModel:[ 
					{name:'id',index:'id_urusan', width:20, search:false, hidden:true}, 
					{name:'kode',index:'kode_urusan', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:10, maxlength: 10},editrules: {required:true}}, 
					{name:'nama',index:'nama_urusan', width:340, align:"left", editable:true, edittype:'text', editoptions: {size:100, maxlength: 200},editrules: {required:true}}], 
				rowNum:10, 
				rownumbers: true,
				rowList:[10,20,30], 
				pager: '#urusanPager', 
				sortname: 'id_urusan', 
				viewrecords: true, 
				multiselect: true,
				multiboxonly: true,
				shrinkToFit: false,
				sortorder: "asc", 
				width: 540,
				height: '100%',
				ondblClickRow:function(id){		
					var rs = jQuery('#urusanTable').getRowData(id);
					jQuery('#ID_URUSAN').val(rs.id);
					jQuery('#KODE_URUSAN').val(rs.kode);
					jQuery('#NAMA_URUSAN').val(rs.nama);
					jQuery('#KODE_URUSAN_LBL').val(rs.kode+' - '+rs.nama);
					
					var id = jQuery("#urusanTable").jqGrid('getGridParam','selrow'); 
					if (id) { var ret = jQuery("#urusanTable").jqGrid('getRowData',id);
						//alert(ret.id);
						var url = "<?php echo base_url()?>skpd/daftar_subbidang"+"/"+ret.id;
						jQuery('#bidangTable').setGridParam({url:url}).trigger("reloadGrid");
						jQuery("#bidangTable").jqGrid('setGridParam').trigger("reloadGrid",[{page:1}]);
						
					} else { 
					alert("Please select row");}
					
					jQuery('#searchformurusan').dialog('close');
					
				}			
				//caption:"Daftar Urusan" 
			}).navGrid('#urusanPager'
				,{add:false,edit:false,del:false,view:false,search:false,refresh:true}
			); 
			
		jQuery('#filter_urusan2').click(function(){
				var filter=''; var data = {};
						
				if(jQuery('#ck_teks_urusan').attr('checked')){
							filter+='/op_teks_urusan/'+jQuery('#op_teks_urusan').val(); data.op_teks_urusan = jQuery('#op_teks_urusan').val();
							filter+='/op_filter_urusan/'+jQuery('#op_filter_urusan').val(); data.op_filter_urusan = jQuery('#op_filter_urusan').val();
							filter+='/in_teks_urusan/'+jQuery('#in_teks_urusan').val(); data.in_teks_urusan = jQuery('#in_teks_urusan').val();
						}
				
				jQuery.post('<?php echo base_url()?>skpd/filter_urusan',data,function(rs){
						jQuery('#urusanTable').trigger('reloadGrid');
					});	
					
				});
			
			jQuery("#bsdata").click(function(){ 
				jQuery("#urusanTable").jqGrid('searchGrid',
				{add:false,edit:false,del:false,search:true}, 
				{sopt:['cn','bw','eq','ne','lt','gt','ew','le','ge']},
			{multipleSearch:true} ); }); 
			
			
			/* Pilih Bidang */
			jQuery('#btn_bidang').click(function() {		
				jQuery('#bidangTable').trigger('reloadGrid');
				jQuery('#searchformbidang').dialog('open');
			});

			jQuery('#searchformbidang').dialog({
				title:'Pilih Bidang',
				height:400,
				width:570,
				modal:true,
				autoOpen:false,
				closeOnEscape:true,
				buttons: {
					'Pilih': function(){
						var id = jQuery("#bidangTable").jqGrid('getGridParam','selrow'); 
								if (id) 
								{ 
								var rs = jQuery("#bidangTable").jqGrid('getRowData',id); 
								jQuery('#ID_BIDANG').val(rs.id);
								jQuery('#KODE_BIDANG').val(rs.kode);
								jQuery('#NAMA_BIDANG').val(rs.nama);
								jQuery('#KODE_BIDANG_LBL').val(rs.kode+' - '+rs.nama);
								jQuery('#searchformbidang').dialog('close');
								} 
								else { alert("Tolong pilih barisnya.");} 
					},
					'Tutup': function() {
						jQuery(this).dialog('close');
					}
				}
			 });	
			
			jQuery("#bidangTable").jqGrid({ 
				url:'<?php echo base_url()?>skpd/daftar_subbidang', 
				datatype: "json", 
				mtype: "POST",
				colNames:['ID','KODE BIDANG','NAMA BIDANG'], 
				colModel:[ 
					{name:'id',index:'ID_BIDANG', width:20, search:false, hidden:true}, 
					{name:'kode',index:'KODE_BIDANG', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:10, maxlength: 10},editrules: {required:true}}, 
					{name:'nama',index:'NAMA_BIDANG', width:340, align:"left", editable:true, edittype:'text', editoptions: {size:100, maxlength: 200},editrules: {required:true}}], 
				rowNum:10, 
				rownumbers: true,
				rowList:[10,20,30], 
				pager: '#bidangPager', 
				sortname: 'ID_BIDANG', 
				viewrecords: true, 
				multiselect: true,
				multiboxonly: true,
				shrinkToFit: false,
				sortorder: "asc", 
				width: 540,
				height: '100%',
				ondblClickRow:function(id){		
					var rs = jQuery('#bidangTable').getRowData(id);
					jQuery('#ID_BIDANG').val(rs.id);
					jQuery('#KODE_BIDANG').val(rs.kode);
					jQuery('#NAMA_BIDANG').val(rs.nama);
					jQuery('#KODE_BIDANG_LBL').val(rs.kode+' - '+rs.nama);
					jQuery('#searchformbidang').dialog('close');
					
				}			
				//caption:"Daftar Bidang" 
			}).navGrid('#bidangPager'
				,{add:false,edit:false,del:false,view:false,search:false,refresh:true}
			); 
			
			jQuery('#filter_bidang2').click(function(){
				var filter=''; var data = {};
						
				if(jQuery('#ck_teks_bidang').attr('checked')){
							filter+='/op_teks_bidang/'+jQuery('#op_teks_bidang').val(); data.op_teks_bidang = jQuery('#op_teks_bidang').val();
							filter+='/op_filter_bidang/'+jQuery('#op_filter_bidang').val(); data.op_filter_bidang = jQuery('#op_filter_bidang').val();
							filter+='/in_teks_bidang/'+jQuery('#in_teks_bidang').val(); data.in_teks_bidang = jQuery('#in_teks_bidang').val();
						}
				
				jQuery.post('<?php echo base_url()?>skpd/filter_bidang',data,function(rs){
						jQuery('#bidangTable').trigger('reloadGrid');
					});	
					
				});
			
			jQuery("#bsdata").click(function(){ 
				jQuery("#bidangTable").jqGrid('searchGrid',
				{add:false,edit:false,del:false,search:true}, 
				{sopt:['cn','bw','eq','ne','lt','gt','ew','le','ge']},
			{multipleSearch:true} ); }); 
			
			$("#btn_tambah_insert").click( function(){
				var ID_BIDANG	= jQuery('input[name=ID_BIDANG]').val();
				var KODE_SKPD	= jQuery('input[name=KODE_SKPD]').val();
				var NAMA_SKPD	= jQuery('input[name=NAMA_SKPD]').val();
				var LOKASI	= jQuery('input[name=LOKASI]').val();
				var ALAMAT	= jQuery('input[name=ALAMAT]').val();
				var NPWP = jQuery('input[name=NPWP]').val();
				var PLAFON_BTL	= jQuery('input[name=PLAFON_BTL]').val();
				
				jQuery.post("<?php echo base_url()?>skpd/proses_form_insert",{
				'ID_BIDANG':ID_BIDANG,
				'KODE_SKPD':KODE_SKPD,
				'NAMA_SKPD':NAMA_SKPD,
				'LOKASI':LOKASI,
				'ALAMAT':ALAMAT,
				'NPWP':NPWP,
				'PLAFON_BTL':PLAFON_BTL
				},function(data){
					 alert(NAMA_SKPD + " berhasil ditambahkan");
				});
				   
				$('.form').clearForm();
			});
			
			$("#btn_tambah_update").click( function(){
				var ID_SKPD	= jQuery('input[name=ID_SKPD]').val();
				var ID_BIDANG	= jQuery('input[name=ID_BIDANG]').val();
				var KODE_SKPD	= jQuery('input[name=KODE_SKPD]').val();
				var NAMA_SKPD	= jQuery('input[name=NAMA_SKPD]').val();
				var LOKASI	= jQuery('input[name=LOKASI]').val();
				var ALAMAT	= jQuery('input[name=ALAMAT]').val();
				var NPWP = jQuery('input[name=NPWP]').val();
				var PLAFON_BTL	= jQuery('input[name=PLAFON_BTL]').val();
				
				jQuery.post("<?php echo base_url()?>skpd/proses_form_update",{
				'ID_SKPD':ID_SKPD,
				'ID_BIDANG':ID_BIDANG,
				'KODE_SKPD':KODE_SKPD,
				'NAMA_SKPD':NAMA_SKPD,
				'LOKASI':LOKASI,
				'ALAMAT':ALAMAT,
				'NPWP':NPWP,
				'PLAFON_BTL':PLAFON_BTL
				},function(data){
					 alert(NAMA_PEJABAT + " berhasil diupdate");
				});
				   
				$('.form').clearForm();
			});
		
		jQuery("#message").ajaxError(function(event, request, settings){
			var msg = jQuery.parseJSON(request.responseText);
			if(msg.error){
				showmessage(msg.error);
			}
	});	

	})
	</script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/libs/site/script.js"></script>
	
	<div class='cntr_form' id="wrapper-form">
		<div class="title_form">Entry Bidang</div>
		<form class='form' id="frmEntri" enctype="multipart/form-data" action="<?php echo base_url()?>bidang/proses_form" method="POST">
			<fieldset class='fieldset' id='fd_fungsi'>
				<legend class='legend'>Fungsi</legend>
					<div class='fd_left'>
						<div><label>Kode Fungsi :</label>
						<input type="text" id="KODE_FUNGSI" name="KODE_FUNGSI" size="50" readonly="1" value="<?php echo isset($user_data["KODE_FUNGSI"]) ? $user_data["KODE_FUNGSI"] : ""; ?>" class="{required:true,messages:{required:'Kode Fungsi belum dipilih'}}"/>
						<input type="hidden" id="ID_FUNGSI" name="ID_FUNGSI" value="<?php echo isset($user_data["ID_FUNGSI"]) ? $user_data["ID_FUNGSI"] : ""; ?>" />
						<?php echo form_button( array('name' => 'btn_fungsi','class'=>'button2', 'id' => 'btn_fungsi', 'content' => 'Pilih Fungsi') );?>
						</div>
					</div>
					<div class='fd_left'>
						<div><label>Nama Fungsi :</label>
						<input type='text' id='NAMA_FUNGSI' name='NAMA_FUNGSI' size="50" readonly="1" value='<?php echo isset($user_data["NAMA_FUNGSI"]) ? $user_data["NAMA_FUNGSI"] : ""; ?>'></div>
					</div>
			</fieldset>

			<fieldset class='fieldset' id='fd_urusan'>
				<legend class='legend'>Urusan</legend>
					<div class='fd_left'>
						<div><label>Kode Urusan :</label>
						<input type="text" id="KODE_URUSAN" name="KODE_URUSAN" size="50" readonly="1" value="<?php echo isset($user_data["KODE_URUSAN"]) ? $user_data["KODE_URUSAN"] : ""; ?>" class="{required:true,messages:{required:'Kode Urusan belum dipilih'}}"/>
						<input type="hidden" id="ID_URUSAN" name="ID_URUSAN" value="<?php echo isset($user_data["ID_URUSAN"]) ? $user_data["ID_URUSAN"] : ""; ?>" />
						<?php echo form_button( array('name' => 'btn_urusan','class'=>'button2', 'id' => 'btn_urusan', 'content' => 'Pilih Urusan') );?>
						</div>
					</div>
					<div class='fd_left'>
						<div><label>Nama Urusan :</label>
						<input type='text' id='NAMA_URUSAN' name='NAMA_URUSAN' readonly="1" size="50" value='<?php echo isset($user_data["NAMA_URUSAN"]) ? $user_data["NAMA_URUSAN"] : ""; ?>'></div>
					</div>
			</fieldset>

			<fieldset class='fieldset' id='fd_bidang'>
				<legend class='legend'>Bidang</legend>
					<div class='fd_left'>
						<div><label>Kode Bidang<b class='wajib'>*</b> :</label>
							<input type='text' size="50" value='<?php echo isset($user_data["KODE_BIDANG"]) ? $user_data["KODE_BIDANG"] : ""; ?>' name='KODE_BIDANG' class='{required:true,messages:{required:"Kode Biadng belum terisi"}}' >
						</div>
						<div><label>Nama Bidang<b class='wajib'>*</b> :</label>
							<input type='text' size="50" value='<?php echo isset($user_data["NAMA_BIDANG"]) ? $user_data["NAMA_BIDANG"] : ""; ?>' name='NAMA_BIDANG' class='{required:true,messages:{required:"Nama Bidang belum terisi"}}' >
						</div>
					</div>
			</fieldset>
			
			<div id='searchformfungsi'>
				<div class='frm_jgrid'>
					<div class="ptoggle"><a class="uppane" title="Hide/Show Pane"></a></div>
					<table class='tablefilterpilih'>
						<tr>
							<td><input type='checkbox' name='ck_teks_fungsi' id='ck_teks_fungsi'></td>
							<td>
								<select name='op_teks_fungsi' id='op_teks_fungsi'>
									<option value='KODE_FUNGSI'>Kode Fungsi</option>
									<option value='NAMA_FUNGSI'>Nama Fungsi</option>
								</select>
							</td>
							<td>
								<select name='op_filter_fungsi' id='op_filter_fungsi'>
									<option value='bw'>Diawali</option>
									<option value='cn'>Memuat</option>
								</select>
							</td>
							<td>:</td>
							<td colspan='3'><input type='text' name='in_teks_fungsi' id='in_teks_fungsi' size="40"/></td>
							<td>
							<input type='button' class='button2' id='filter_fungsi2' value='Filter' /></td></tr>
					</table>
				</div>
				<div class='frm_jgrid'>	
					<table id="fungsiTable" class="scroll"></table>
					<div id="fungsiPager" class="scroll"></div>
				</div>
			</div>
			
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
			
			<div id='cntr_button'>
				<input type='hidden' name='data' id='data' value='' />
				<input type='hidden' value='<?php echo isset($user_data['update']) ? $user_data['update'] : ""; ?>' name='update' />
				<input type='hidden' value='<?php echo isset($user_data['ID_BIDANG']) ? $user_data['ID_BIDANG'] : ""; ?>' name='ID_BIDANG' />
				<?php
				$extra_attribut1 = 'id="btn_save" class="button1" style="margin-left:0.3em"';	
				$extra_attribut2 = 'id="btn_back" class="button1" style="margin-left:0.3em"';	
				echo form_button('bidang_button','Simpan',$extra_attribut1);
				
				echo form_reset('bidang_reset','Batal',$extra_attribut2);
				?>
				
				<?php
					if (isset($user_data['ID_PEJABAT_DAERAH']))
					{
						echo '<input type="button" value="Tambah Data" name="tambah_data_update" class="button1" style="margin-right:-0.3em" id="btn_tambah_update"/>';
					}
					else
					{
						echo '<input type="button" value="Tambah Data"  class="button1" style="margin-right:-0.3em" name="tambah_data_insert"  id="btn_tambah_insert"/>';
					}
				?>
			</div>
			<div class='clear'></div>
		</form>			
	</div>
		
	<script type="text/javaScript">

	jQuery(document).ready(function(){
		jQuery('#frmEntri').ajaxForm();		
		
		/* Pilih Fungsi */
		jQuery('#btn_fungsi').click(function() {		
				jQuery('#fungsiTable').trigger('reloadGrid');
				jQuery('#searchformfungsi').dialog('open');
		});

		jQuery('#searchformfungsi').dialog({
			title:'Pilih Fungsi',
			height:400,
			width:570,
			modal:true,
			autoOpen:false,
			closeOnEscape:true,
			buttons: {
				'Pilih': function(){
							var id = jQuery("#fungsiTable").jqGrid('getGridParam','selrow'); 
									if (id) 
									{ 
									var rs = jQuery("#fungsiTable").jqGrid('getRowData',id); 
									jQuery('#ID_FUNGSI').val(rs.id);
									jQuery('#KODE_FUNGSI').val(rs.kode);
									jQuery('#NAMA_FUNGSI').val(rs.nama);
									jQuery('#KODE_FUNGSI_LBL').val(rs.kode+' - '+rs.nama);
									
										var id = jQuery("#fungsiTable").jqGrid('getGridParam','selrow'); 
										if (id) { var ret = jQuery("#fungsiTable").jqGrid('getRowData',id);
											//alert(ret.id);
											var url = "<?php echo base_url()?>bidang/daftar_suburusan"+"/"+ret.id;
											jQuery('#urusanTable').setGridParam({url:url}).trigger("reloadGrid");
											jQuery("#urusanTable").jqGrid('setGridParam').trigger("reloadGrid",[{page:1}]);
											
										} else { 
										alert("Please select row");}
									
									jQuery('#searchformfungsi').dialog('close');
									} 
									else { alert("Tolong pilih barisnya.");} 
							},
				'Tutup': function() {
					jQuery(this).dialog('close');
				}			
			}
		 });
		 
		jQuery("#fungsiTable").jqGrid({ 
				url:'<?php echo base_url()?>bidang/daftar_subfungsi', 
				datatype: "json", 
				mtype: "POST",
				colNames:['ID','KODE FUNGSI','NAMA FUNGSI'], 
				colModel:[ 
					{name:'id',index:'ID_FUNGSI', width:20, search:false, hidden:true}, 
					{name:'kode',index:'KODE_FUNGSI', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:10, maxlength: 10},editrules: {required:true}}, 
					{name:'nama',index:'NAMA_FUNGSI', width:340, align:"left", editable:true, edittype:'text', editoptions: {size:100, maxlength: 200},editrules: {required:true}}], 
				rowNum:10, 
				rownumbers: true,
				rowList:[10,20,30], 
				pager: '#fungsiPager', 
				sortname: 'ID_FUNGSI', 
				viewrecords: true, 
				multiselect: true,
				multiboxonly: true,
				shrinkToFit: false,
				sortorder: "asc", 
				width: 540,
				height: '100%',
				ondblClickRow:function(id){		
					var rs = jQuery('#fungsiTable').getRowData(id);
					jQuery('#ID_FUNGSI').val(rs.id);
					jQuery('#KODE_FUNGSI').val(rs.kode);
					jQuery('#NAMA_FUNGSI').val(rs.nama);
					jQuery('#kode_fungsi_lbl').val(rs.kode+' - '+rs.nama);
					
					var id = jQuery("#fungsiTable").jqGrid('getGridParam','selrow'); 
					if (id) { var ret = jQuery("#fungsiTable").jqGrid('getRowData',id);
						//alert(ret.id);
						var url = "<?php echo base_url()?>bidang/daftar_suburusan"+"/"+ret.id;
						jQuery('#urusanTable').setGridParam({url:url}).trigger("reloadGrid");
						jQuery("#urusanTable").jqGrid('setGridParam').trigger("reloadGrid",[{page:1}]);
						
					} else { 
					alert("Please select row");}
					
					jQuery('#searchformfungsi').dialog('close');
					
				}			
				//caption:"Daftar Fungsi" 
			}).navGrid('#fungsiPager'
				,{add:false,edit:false,del:false,view:false,search:false,refresh:true}
			); 
			
			jQuery('#filter_fungsi2').click(function(){
				var filter=''; var data = {};
						
				if(jQuery('#ck_teks_fungsi').attr('checked')){
							filter+='/op_teks_fungsi/'+jQuery('#op_teks_fungsi').val(); data.op_teks_fungsi = jQuery('#op_teks_fungsi').val();
							filter+='/op_filter_fungsi/'+jQuery('#op_filter_fungsi').val(); data.op_filter_fungsi = jQuery('#op_filter_fungsi').val();
							filter+='/in_teks_fungsi/'+jQuery('#in_teks_fungsi').val(); data.in_teks_fungsi = jQuery('#in_teks_fungsi').val();
						}
				
				jQuery.post('<?php echo base_url()?>bidang/filter_fungsi',data,function(rs){
						jQuery('#fungsiTable').trigger('reloadGrid');
					});	
					
				});
			
			jQuery("#bsdata").click(function(){ 
				jQuery("#fungsiTable").jqGrid('searchGrid',
				{add:false,edit:false,del:false,search:true}, 
				{sopt:['cn','bw','eq','ne','lt','gt','ew','le','ge']},
			{multipleSearch:true} ); }); 
			
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
									jQuery('#searchformurusan').dialog('close');
									} 
									else { alert("Tolong pilih barisnya.");} 
							},
				'Tutup': function() {
					jQuery(this).dialog('close');
				}
			}
		 });

		jQuery("#urusanTable").jqGrid({ 
				url:'<?php echo base_url()?>bidang/daftar_suburusan', 
				datatype: "json", 
				mtype: "POST",
				colNames:['ID','KODE URUSAN','NAMA URUSAN'], 
				colModel:[ 
					{name:'id',index:'ID_URUSAN', width:20, search:false, hidden:true}, 
					{name:'kode',index:'KODE_URUSAN', width:100, align:"left", editable:true, edittype:'text', editoptions: {size:10, maxlength: 10},editrules: {required:true}}, 
					{name:'nama',index:'NAMA_URUSAN', width:400, align:"left", editable:true, edittype:'text', editoptions: {size:100, maxlength: 200},editrules: {required:true}}], 
				rowNum:10, 
				rownumbers: true,
				rowList:[10,20,30], 
				pager: '#urusanPager', 
				sortname: 'ID_URUSAN', 
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
					jQuery('#kode_urusan_lbl').val(rs.kode+' - '+rs.nama);
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
				
				jQuery.post('<?php echo base_url()?>bidang/filter_urusan',data,function(rs){
						jQuery('#urusanTable').trigger('reloadGrid');
					});	
					
				});
			
			jQuery("#bsdata").click(function(){ 
				jQuery("#urusanTable").jqGrid('searchGrid',
				{add:false,edit:false,del:false,search:true}, 
				{sopt:['cn','bw','eq','ne','lt','gt','ew','le','ge']},
			{multipleSearch:true} ); }); 
			
		//Save
		jQuery('#btn_save').click( function(){
			save();
		}); 
		
		function save(addNew){
			var gridData = jQuery("#grid").getRowData();
			var jsonData = JSON.stringify(gridData);
			jQuery('#data').val(jsonData);
			
			var options = {
				beforeSubmit: function(arr, $form, options) {
					
				if (jQuery('#ID_URUSAN').val() == ''){
					showmessage('Urusan harus dipilih');
					return false;
				}	
				if (jQuery('#ID_FUNGSI').val() == ''){
					showmessage('Fungsi harus dipilih');
					return false;
				}
				if (jQuery('#KODE_BIDANG').val() == ''){
					showmessage('Kode Bidang harus di isi');
					return false;
				}
				if (jQuery('#NAMA_BIDANG').val() == ''){
					showmessage('Nama Bidang harus di isi');
					return false;
				}
				},
				success: function(resp, stat, data) {
					var msg = jQuery.parseJSON(resp);
					if(msg.success){
						showmessage(msg.success);
						
						location.href="<?php echo base_url()?>bidang/index";
					}
				}
			};

			jQuery('#frmEntri').ajaxSubmit(options);	
				return false;
		}
			
			//Tambah Data Insert
			$("#btn_tambah_insert").click( function(){
				var ID_URUSAN	= jQuery('input[name=ID_URUSAN]').val();
				var ID_FUNGSI	= jQuery('input[name=ID_FUNGSI]').val();
				var KODE_BIDANG	= jQuery('input[name=KODE_BIDANG]').val();
				var NAMA_BIDANG	= jQuery('input[name=NAMA_BIDANG]').val();
				
				jQuery.post("<?php echo base_url()?>bidang/proses_form_insert",{
				'ID_URUSAN':ID_URUSAN,
				'ID_FUNGSI':ID_FUNGSI,
				'KODE_BIDANG':KODE_BIDANG,
				'NAMA_BIDANG':NAMA_BIDANG
				},function(data){
					 showmessage(NAMA_BIDANG + " berhasil ditambahkan");
				});
				   
				$('.form').clearForm();
			});
			
			//Tambah Data Update
			$("#btn_tambah_update").click( function(){
				var ID_BIDANG	= jQuery('input[name=ID_BIDANG]').val();
				var ID_URUSAN	= jQuery('input[name=ID_URUSAN]').val();
				var ID_FUNGSI	= jQuery('input[name=ID_FUNGSI]').val();
				var KODE_BIDANG	= jQuery('input[name=KODE_BIDANG]').val();
				var NAMA_BIDANG	= jQuery('input[name=NAMA_BIDANG]').val();
				
				jQuery.post("<?php echo base_url()?>bidang/proses_form_update",{
				'ID_BIDANG':ID_BIDANG,
				'ID_URUSAN':ID_URUSAN,
				'ID_FUNGSI':ID_FUNGSI,
				'KODE_BIDANG':KODE_BIDANG,
				'NAMA_BIDANG':NAMA_BIDANG
				},function(data){
					 showmessage(NAMA_BIDANG + " berhasil diupdate");
				});
				   
				$('.form').clearForm();
			});
		
			
			function showmessage(msg){
				jQuery('#message').html(msg).show(100).delay(5000).hide(500);
			}

			jQuery("#message").ajaxError(function(event, request, settings){
				var msg = jQuery.parseJSON(request.responseText);
				if(msg.error){
					showmessage(msg.error);
				}
			});
	})
	</script>
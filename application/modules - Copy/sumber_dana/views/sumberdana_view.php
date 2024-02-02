   <fieldset>
    <legend>Daftar Sumber Dana</legend>
      <select name="field" id="field" class="span2">
		<option value='namasumber'>Nama Sumber Dana</option>
		<option value='namabank'>Nama Bank</option>
		<option value='norekening'>No Rekening</option>
		<option value='koderekening'>Kode Rekening</option>
		<option value='namarekening'>Nama Rekening</option>
	  </select>
      <select name='oper' id='oper' class="span2">
		<option value='cn'>Memuat</option>
		<option value='bw'>Diawali</option>
	  </select>
      <input type="text" name="string" id="string" class="span7">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
    </fieldset>
			
	<table id="grid"></table>
	<div id="pager"></div>

	<div id="rekening"></div>

	<script type="text/javascript">
	jQuery(document).ready(function() {
		var last;
		var idrekening;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>sumber_dana/get_daftar',
			editurl:'<?php echo base_url()?>sumber_dana/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['NAMA SUMBER DANA','ID REKENING','KODE REKENING','NAMA REKENING','NAMA BANK','REKENING BANK'],
			colModel:[
				{name:'namasumber',index:'namasumber',width:200,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true}},
				{name:'idrekening',index:'idrekening',width:200,editable:true,edittype:'text',hidden:true},
				{name:'koderekening', index:'koderekening',width:120,editable:true,edittype:'button',editoptions: {value:'pilih rekening',
				class:'btn btn-primary',dataEvents: [{type: 'click', fn: addrekening}]}},
				{name:'namarekening',index:'namarekening',width:200,editable:true,edittype:'text',editoptions:{size:50,readonly:'readonly'},editrules:{required:true}},
				{name:'namabank',index:'namabank',width:200,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true}},
				{name:'norekening',index:'norekening',width:200,editable:true,edittype:'text',editoptions:{size:20},editrules:{required:true}}

			],
			rowNum:10,
			rowList:[10,20,30],
			rownumbers:true,
			pager:'#pager',
			sortorder:'asc',
			viewrecords:true,
			gridview:true,
			width:930,
			height:'250',
			ondblClickRow: edit_row,
			onSelectRow:restore_row
			
		});
		
		jQuery("#grid").jqGrid( 'navGrid', '#pager', { 
    <?php
    if($akses=='3'){
    echo "
		add: true,
		addtext: 'Tambah',
		addfunc: append_row,
		edit: true,
		edittext: 'Ubah',
		editfunc: edit_row,
		del: true,
		deltext: 'Hapus',
		delfunc: del_row,
		search: false,
		searchtext: 'Cari',
    	";
      }
      else{
      echo "
      add:false,
      edit:false,
      del:false,
      search:false,
      ";
      }
      ?>
		refresh: true,
		refreshtext: 'Refresh'
		});	
		
		function append_row(){
			if(data_dasar=='3'){
				var id = jQuery("#grid").jqGrid('getGridParam','selrow');
				if(id)
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					var hasil = "";
					for(var u=0;u<jml.length;u++)
					{
						if(jml[u] == "new"){
							hasil = hasil + jml[u];
						}
					}
					
					var ada = hasil.search('new');
					if(ada != -1){
						alert('Input Sumber Dana belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
						jQuery("#grid").jqGrid('addRowData', "new",true, 'after', id);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
					}
				}
				else
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					pos = jml.length - 1;
					if(jml[pos] == "new"){
						alert('Input Sumber Dana belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
						jQuery("#grid").jqGrid('addRowData', "new",true);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
					}
				}
				last = null;
			}else{
				alert('Tidak bisa tambah data');
			}
		}
		
		function edit_row(id){
			if(data_dasar=='3'){
				if(id !== last)
				{
					jQuery('#grid').jqGrid('restoreRow', last);
					last = id;
					jQuery('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				}
			}else{
				alert('Tidak bisa ubah data');
			}
		}
		
		function del_row(id){
			if(data_dasar=='3'){
				var rt = jQuery("#grid").jqGrid('getRowData', id); 
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					jQuery.ajax({
						url: '<?php echo base_url()?>sumber_dana/hapus', 
						data: { id: id},
						success: function(response){
							var msg = jQuery.parseJSON(response);
							$.pnotify({
							  title: msg.isSuccess ? 'Sukses' : 'Gagal',
							  text: msg.message,
							  type: msg.isSuccess ? 'info' : 'error'
							});
							if(msg.isSuccess == true) {
								jQuery("#grid").jqGrid('delRowData', id);
							}
							jQuery('#grid').trigger('reloadGrid');
						},
						type: "post", 
						dataType: "html"
					});
				}

			}else{
				alert('Tidak bisa hapus data');
			}		
		}
		
		function restore_row(id){
			if(id && id !== last){
				jQuery('#grid').jqGrid('saveRow', last,aftersavefunc, null, {idrekening:idrekening}, null, errorfunc);
			}
		}

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
			var msg = jQuery.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});
			if(msg.id &&  msg.id != id)
				jQuery("#"+id).attr("id", msg.id);
			jQuery('#grid').trigger("reloadGrid");
			last = null;
		}
		
		function errorfunc(id, resp){
			var msg = jQuery.parseJSON(resp.responseText);
			if(msg.error)
				$.pnotify({
				  title: 'Gagal',
				  text: msg.error,
				  type: 'error'
				});
			jQuery('#grid').trigger("reloadGrid");
		}
		
		jQuery('#filter').click(function(){
			var field 	= jQuery("#field").val();
			var oper 	= jQuery("#oper").val();
			var string 	= jQuery("#string").val();
			
			var grid = jQuery("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');
			jQuery.extend (postdata,
						   {filters:'',
							searchField: field,
							searchOper: oper,
							searchString: string});
			grid.jqGrid('setGridParam', { search: true, postData: postdata });
			grid.trigger("reloadGrid",[{page:1}]);
		}); 
		
		jQuery('#string').keypress(function (e) {
			if (e.which == 13) {
				jQuery('#filter').click();
			}
		});
				
		function pilih_rekening()
		{
			//var isi_rekening = jQuery("#grid").jqGrid('getDataIDs');
			var idr = jQuery("#gridrekening").jqGrid('getGridParam','selrow');
			//var ids = jQuery("#grid").jqGrid('getGridParam','selarrrow');
			if(idr.length > 0){
						for (var i=0;i<idr.length;i++){
							var rr = jQuery("#gridrekening").jqGrid('getRowData', idr[i]); 
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
						
						jQuery('#new_koderekening').val(kode);
						jQuery('#new_namarekening').val(nama);
						jQuery('#new_idrekening').val(id);
						
						var ids = jQuery("#grid").jqGrid('getGridParam','selrow'); 
						jQuery('#'+ids+'_koderekening').val(kode);
						jQuery('#'+ids+'_namarekening').val(nama);
						jQuery('#'+ids+'_idrekening').val(id);
				
						//jQuery("#grid").jqGrid('setCell','new','koderekening',kode, null,null);
						//jQuery("#grid").jqGrid('setCell','new','namarekening',nama,null,null);
						//jQuery("#grid").jqGrid('setCell','new','idrekening',id,null,null);
 			
				jQuery(this).dialog('close');
			}else{
				alert("Silahkan pilih salah satu data.");
			}
		}
		
		function addrekening()
		{
			jQuery('#rekening').dialog('open');
		}
		
		jQuery('#rekening').dialog({
			title:'Pilih Rekening',
			height:425,
			width:650,
			modal:true,
			autoOpen:false,
			closeOnEscape:true,
			buttons: {
					'Pilih': {click: pilih_rekening, text:'Pilih', class:'btn btn-primary'},
					'Tutup': {class:'btn btn-primary', text:'Tutup', click:function() { jQuery(this).dialog('close'); }}
						}
		}).load('<?php echo base_url();?>rekening/pilih');	
		
	});
	</script>
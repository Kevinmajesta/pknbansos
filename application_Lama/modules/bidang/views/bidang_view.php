	
	<fieldset>
    <legend>Daftar Bidang</legend>
						<select name='field' id='field' class="span2">
							<option value='b.nama_bidang'>Nama</option>
							<option value='f.kode_fungsi'>Fungsi</option>
							<option value='u.kode_urusan'>Urusan</option>
							<option value='b.kode_bidang'>Bidang</option>							
						</select>
					
						<select name='oper' id='oper' class="span2">
							<option value='cn'>Memuat</option>
							<option value='bw'>Diawali</option>							
						</select>
					<input type='text' name='string' id='string' size="30" class="span7" />
					<a class="btn btn-primary" id="filter" ><i class="icon-search icon-white" ></i> Filter</a>
					
  </fieldset>					
			<table id="grid"></table>
			<div id="pager"></div>
	

	<input type='hidden' size="10" name='DATA_DASAR_LOGIN' id='DATA_DASAR_LOGIN' value='<?php echo isset($user_data["DATA_DASAR_LOGIN"]) ? $user_data["DATA_DASAR_LOGIN"] : ""; ?>' }>
	

	<script type="text/javascript">
  var modul = 'bidang';
  
	jQuery (document).ready(function() {
		var last = newid = 0;	
		var data_dasar = jQuery('#DATA_DASAR_LOGIN').val();		
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>bidang/get_daftar',
			editurl:'<?php echo base_url()?>bidang/proses_form',
			datatype:"json",
			mtype:"POST",
			colNames:['ID','ID_FUNGSI','FUNGSI','URUSAN','BIDANG','NAMA'],
			colModel:[
				{name:'id',index:'id',width:50,editable:true,hidden:true},
				{name:'idfungsi',index:'idfungsi',width:50,editable:true,hidden:true},
				{name:'fungsi',index:'fungsi',width:100,editable:false,edittype:'text',editoptions:{size:20},sortable:false},
				{name:'urusan', index:'urusan',width:100,editable:true,edittype:'select',sortable:false,editoptions:{dataUrl:'<?php echo base_url()?>bidang/getselect' }},
				{name:'bidang',index:'bidang',width:100,editable:true,edittype:'text',sortable:false,editoptions:{size:20}, editrules:{number:true}},
				{name:'nama',index:'nama',width:400,editable:true,sortable:false,edittype:'text',editoptions:{size:50}}
			],
			/*rowNum:10,
			rowList:[10,20,30],*/
			rowNum:1000000,
			scroll:true,
			rownumbers:false,
			pager:'#pager',
			viewrecords:true,
			gridview:true,
			width:999,
			height:245,
			
			ondblClickRow:function(id){
				var id = jQuery("#grid").jqGrid('getGridParam','selrow');
				var ret = jQuery('#grid').jqGrid('getRowData', id);
				
				if(ret.fungsi != '')
				 {
				 return false;
				 }
				 else
				 {
					edit_row(id);
				 }
			},
			onSelectRow:restore_row,
			gridComplete: RowColor
			
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
		refreshtext: 'Refresh',
		},{},{},{},{})
    <?php
    if($akses !='1'){
    ?>
    .navSeparatorAdd('#pager')
    .navButtonAdd('#pager',{
      caption:'',
      onClickButton: function(){ print_list("pdf"); },
      title:'Cetak Daftar (PDF)',
      buttonicon:'ui-icon-pdf',
      position:'last'
    })
    .navButtonAdd('#pager',{
      caption:'',
      onClickButton: function(){ print_list("xls"); },
      title:'Cetak Daftar (XLS)',
      buttonicon:'ui-icon-xls',
      position:'last'
    });
    ;
  <?php
  }
  ?> 
		
    function print_list(doc){
      var $grid = $('#grid');
      var postdata = $grid.jqGrid('getGridParam', 'postData');
     
      preview({"tipe":"daftar", "format":doc, 'search': postdata._search, 'searchField': postdata.searchField, 'searchOper': postdata.searchOper, 'searchString': postdata.searchString});
    }

  function onSuccess(resp){
		var o = $.parseJSON(resp.responseText);
		if (o.isSuccess){
		  $.pnotify({
			text: o.message,
			type: 'success'
		  });
		  return true;
		}
	}

	function onError(id, resp){
		var o = $.parseJSON(resp.responseText);
		$.pnotify({
			text: o.message ? o.message : 'Server tidak bisa diakses',
			type: resp.status = 200 ? 'info' : 'error'
		  });
		return true;
	}
	
	function onAfterSave(id, resp){
		var o = $.parseJSON(resp.responseText),
			newid = o.id,
			$t = $(this)[0],
			ind = $(this).jqGrid("getInd", id,true);

		$(ind).attr("id", newid);
		if ($t.p.selrow === id) {
		  $t.p.selrow = newid;
		}
		if ($.isArray($t.p.selarrrow)) {
		  var i = $.inArray(id, $t.p.selarrrow);
		  if (i>=0) {
			$t.p.selarrrow[i] = newid;
		  }
		}
		if ($t.p.multiselect) {
		  var newCboxId = "jqg_" + $t.p.id + "_" + newid;
		  $("input.cbox",ind)
			.attr("id", newCboxId)
			.attr("name", newCboxId);
		}

		$(this).jqGrid('setRowData', newid, {'id':newid});
		last = id;
	}
	
	function onAfterDelComplete(resp, data){
		var o = $.parseJSON(resp.responseText);
		if (o.isSuccess){
		  $.pnotify({
			text: o.message,
			type: 'success'
		  });
		  return true;
		}
	}

		
	function append_row(){			
			if(data_dasar=='1'){				
				var id  = jQuery('#grid').jqGrid('getGridParam','selrow');
				var ret = jQuery('#grid').jqGrid('getRowData',id);
				var data = {idfungsi:ret.idfungsi};
				if(id==null){
					alert('Silahkan pilih Fungsi terlebih dahulu.');	
				}
				else{
					jQuery('#grid').jqGrid('restoreRow', last);
					jQuery("#grid").jqGrid('addRowData', "new", data,'after',id);
					editparameters = {
						'keys':true,			
						  'successfunc':onSuccess,
						  'errorfunc':onError,
						  'aftersavefunc':onAfterSave,			
						  'restoreAfterError' : false
					}			
					jQuery('#grid').jqGrid('editRow', "new", editparameters);	 
					//jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
					last = null;
				}
			}else{
				alert('Tidak bisa tambah data');
			}
	}
	
	function edit_row(id){
			if(data_dasar=='1'){
				var awal = id.substr(0,9);
				if (awal =='id_fungsi'){
					alert('Fungsi Tidak Dapat Dirubah.');	
				}
				else{
					jQuery('#grid').jqGrid('restoreRow', last);
					editparameters = {
							"keys" : true,			
							"successfunc" : onSuccess,
							'errorfunc':onError,
							"url" : null,
							"extraparam" : {},
							"aftersavefunc" : null,
							"afterrestorefunc" : null,
							"restoreAfterError" : true,
							"mtype" : "POST"
					}
					 jQuery('#grid').jqGrid('editRow', id, editparameters);						
					last = id;
				}	
			}else{
				alert('Tidak bisa ubah data');
			}
	}
	
	
	function del_row(id){
			if(data_dasar=='1'){
				var awal = id.substr(0,9);
				if (awal =='id_fungsi'){
					alert('Fungsi Tidak Dapat Dihapus.');	
				}
				else{
					var answer = confirm('Hapus dari daftar?');
					if(answer == true)
					{						
						  $.ajax({
						  type: "post",
						  dataType: "json",
						  url: root+'bidang/hapus',
						  data: {id: id},
							success: function(res) {
							  $.pnotify({
								title: res.isSuccess ? 'Sukses' : 'Gagal',
								text: res.message,
								type: res.isSuccess ? 'info' : 'error'
							  });
							  if (true == res.isSuccess){
								jQuery('#grid').jqGrid('delRowData', id);
							  };
							},
						  });
					}
				}	
			}else{
				alert('Tidak bisa hapus data');
			}
		}
	
		function save_row(id)
		{
			if(id && id != last){
			  $(this).jqGrid('saveRow', id, {'successfunc':onSuccess, 'aftersavefunc':onAfterSave, 'restoreAfterError':false, 'reloadAfterSubmit':true });
			  last = null;
			}
		}
	
		function restore_row(id){
			if(id && id !== last){
				jQuery('#grid').jqGrid('restoreRow', last);
				last = null;
			}
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
	
		
		
		function RowColor()
		{
			var idcolor = jQuery('#grid').jqGrid('getDataIDs');
			for(var i=0; i<=idcolor.length; i++)
			{
				var rowcolor = jQuery('#grid').jqGrid('getRowData', idcolor[i]);
				if(rowcolor['fungsi'] != '' )
				{
					var rows = jQuery('#'+idcolor[i], jQuery('#grid')).find('td');
					rows.css("color", "#666666");
					rows.css("background-color", "#ffff66");
					rows.css("font-weight", "bold");
				}
			}
		}
		
	});

	</script>
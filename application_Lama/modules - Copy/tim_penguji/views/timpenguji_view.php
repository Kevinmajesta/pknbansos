	<fieldset>
    <legend>Daftar Tim Penguji</legend>
      <select name="field" id="field" class="span2">
        <option value="r.nama_pejabat">Nama Pejabat</option>
        <option value="r.nip">NIP</option>
        <option value="s.nama_skpd">Nama SKPD</option>
      </select>
      <select name='oper' id='oper' class="span2">
        <option value="cn">Memuat</option>
        <option value="bw">Diawali</option>
      </select>
      <input type="text" name="string" id="string" class="span4">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
  </fieldset>
	<table id="grid"></table>
	<div id="pager"></div>
	<div id="skpd"></div>
	<script type="text/javascript">
	$(document).ready(function() {
		var last;
		var idskpd;
		var data_dasar = <?php echo $this->session->userdata('group');?>;
		$("#grid").jqGrid({
			url:'<?php echo base_url()?>tim_penguji/get_daftar',
			editurl:'<?php echo base_url()?>tim_penguji/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','NAMA','NIP','ID SKPD','SKPD'],
			colModel:[
				{name:'id', index:'id',width:50,editable:true,hidden:true},
				{name:'nama_pejabat',index:'nama_pejabat',width:300,editable:true,edittype:'text',editoptions:{size:150},editrules:{required:true, integer:false}},
				{name:'nip',index:'nip',width:150,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}},
				{name:'idskpd',index:'idskpd',width:80,editable:true,edittype:'text',hidden:true},
				{name:'namaskpd',index:'namaskpd',sortable:false,width:250,editable:true,edittype:'button',editoptions: {value:'Pilih SKPD',
				class:'btn btn-primary',dataEvents: [{type: 'click', fn: addSKPD}]}},
			],
			rowNum:10,
			rowList:[10,20,30],
			rownumbers:true,
			pager:'#pager',
			sortorder:'asc',
			viewrecords:true,
			gridview:true,
			width:800,
			height:250,
			caption:'Tim Penguji',
			ondblClickRow: edit_row,
			onSelectRow: restore_row
		});
		
		$("#grid").jqGrid( 'navGrid', '#pager', { 
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
		});
		
		function append_row(){
			if(data_dasar=='1'){
				jml = $("#grid").jqGrid('getDataIDs');
				pos = jml.length - 1;
				if(jml[pos] == "new"){
					alert('Input Penguji belum tersimpan..!!');
				}
				else{
					$('#grid').jqGrid('restoreRow', last);
					$("#grid").jqGrid('addRowData', "new",true);
					$('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
					$('#grid').jqGrid('setSelection', "new", false);
				}
				last = null;
			}
			else{
				$.pnotify({
				  title: 'Perhatian',
				  text: 'Tidak bisa tambah data',
				  type: 'warning'
				});
			}
		}
	
		function edit_row(id){
			if(data_dasar=='1'){
				if(id !== last)
				{
				  $('#grid').jqGrid('restoreRow', last);
				  last = id;
				  $('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				}
			}
			else{
				$.pnotify({
				  title: 'Perhatian',
				  text: 'Tidak bisa ubah data',
				  type: 'warning'
				});
			}
		}
		
		function del_row(id){
			if(data_dasar=='1'){
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					$('#grid').jqGrid('delRowData', id);
					$.ajax({
						url: '<?php echo base_url()?>tim_penguji/hapus', 
						data: { id: id},
						success: function(response){
								var msg = $.parseJSON(response);
								$.pnotify({
								  title: msg.isSuccess ? 'Sukses' : 'Gagal',
								  text: msg.message,
								  type: msg.isSuccess ? 'info' : 'error'
								});
								$('#grid').trigger('reloadGrid');
							},
						type: "post", 
						dataType: "html"
					});
				}
			}
			else{
				$.pnotify({
				  title: 'Perhatian',
				  text: 'Tidak bisa hapus data',
				  type: 'warning'
				});
			}
		}
		
		function restore_row(id){
			if(id && id !== last){
				$('#grid').jqGrid('saveRow', last, null, null, {idskpd:idskpd}, aftersavefunc, errorfunc);
			}
		}

		function aftersavefunc(id, resp){
			var msg = $.parseJSON(resp.responseText);
			  $.pnotify({
				title: msg.isSuccess ? 'Sukses' : 'Gagal',
				text: msg.message,
				type: msg.isSuccess ? 'info' : 'error'
			  });
			$('#grid').trigger("reloadGrid");
			last = null;			
		}
		
		function errorfunc(id, resp){
			var msg = $.parseJSON(resp.responseText);
			  $.pnotify({
				title: 'Gagal',
				text: msg.error,
				type: 'error'
			  });
			$('#grid').trigger('reloadGrid');
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		function addSKPD(e)
		{
			var option = {multi:0, tree:1};
			Dialog.pilihSKPD(option, function(obj, select){
				if (select.length === 0) return false;

				var rs = $(obj).jqGrid('getRowData', select[0].id);

				var ids = $("#grid").jqGrid('getGridParam','selrow');
				$('#'+ids+'_namaskpd').val(rs.nama);
				$('#'+ids+'_idskpd').val(rs.id);
			});
		}  
	
		$('#filter').click(function(){
			var field 	= $("#field").val();
			var oper 	= $("#oper").val();
			var string 	= $("#string").val();
			
			var grid = $("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');
			$.extend (postdata,
						   {filters:'',
							searchField: field,
							searchOper: oper,
							searchString: string});
			grid.jqGrid('setGridParam', { search: true, postData: postdata });
			grid.trigger("reloadGrid",[{page:1}]);
		}); 
		
		$('#string').keypress(function (e) {
			if (e.which == 13) {
				$('#filter').click();
			}
		}); 
  
	});
	</script>
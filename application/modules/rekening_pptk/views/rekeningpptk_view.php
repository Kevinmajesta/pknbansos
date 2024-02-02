   <fieldset>
    <legend>Daftar Rekening PPTK</legend>
      <select name="field" id="field" class="span2">
		<option value='koderekening'>Kode Rekening</option>
		<option value='namarekening'>Nama Rekening</option>
		<option value='kodekegiatan'>Kode Kegiatan</option>
		<option value='namakegiatan'>Nama Kegiatan</option>
		<option value='namapejabat'>Nama Pejabat</option>
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

	<div id="pejabat"></div>
	<table id="gridpejabat"></table>

	<script type="text/javascript">
	jQuery(document).ready(function() {
		var last;
		var idrekening;
		var id_skpd = <?php echo isset($id_skpd) ? $id_skpd : 0; ?>;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
		
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>rekening_pptk/get_daftar',
			editurl:'<?php echo base_url()?>rekening_pptk/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID REKENING','KODE REKENING','NAMA REKENING','ID KEGIATAN','KODE KEGIATAN','NAMA KEGIATAN','ID PPTK','NAMA PEJABAT'],
			colModel:[
				{name:'idrekening',index:'idrekening',width:200,editable:true,edittype:'text',hidden:true},
				{name:'koderekening', index:'koderekening',width:120,editable:true,edittype:'button',editoptions: {
				class:'btn btn-primary',dataEvents: [{type: 'click', fn: addrekening}]}},
				{name:'namarekening',index:'namarekening',width:200,editable:true,edittype:'text',editoptions:{size:50,readonly:'readonly'},editrules:{required:true}},
				{name:'idkegiatan',index:'idkegiatan',width:200,editable:true,edittype:'text',hidden:true},
				{name:'kodekegiatan', index:'kodekegiatan',width:120,editable:true,edittype:'button',editoptions: {
				class:'btn btn-primary',dataEvents: [{type: 'click', fn: addkegiatan}]}},
				{name:'namakegiatan',index:'namakegiatan',width:200,editable:true,edittype:'text',editoptions:{size:50,readonly:'readonly'},editrules:{required:true}},
				{name:'idpptk',index:'idpptk',width:200,editable:true,edittype:'text',hidden:true},
				{name:'namapejabat', index:'namapejabat',width:120,editable:true,edittype:'text',editoptions: {
        class:'autocomplete',dataInit: function (elem) { value: daftarNamaPejabat(elem)}
        }},

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
			onSelectRow:restore_row,
      gridComplete: setfocusrow,
      loadComplete: setfocusrow,
			
		});
		
    $("#grid").jqGrid('bindKeys', { "onEnter": edit_row});
		jQuery("#grid").jqGrid( 'navGrid', '#pager', { 
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
						alert('Input Rekening PPTK belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
						jQuery("#grid").jqGrid('addRowData', "new",true, 'after', id);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);

						jQuery('#new_koderekening').val('pilih rekening');
						jQuery('#new_kodekegiatan').val('pilih kegiatan');
					}
				}
				else
				{
					jml = jQuery("#grid").jqGrid('getDataIDs');
					pos = jml.length - 1;
					if(jml[pos] == "new"){
						alert('Input Rekening PPTK belum tersimpan..!!');
					}
					else{
						jQuery('#grid').jqGrid('restoreRow', last);
						jQuery("#grid").jqGrid('addRowData', "new",true);
						jQuery('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);

						jQuery('#new_koderekening').val('pilih rekening');
						jQuery('#new_kodekegiatan').val('pilih kegiatan');
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
				}
        jQuery('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, afterrestorefunc);
        $('#'+id+'_namarekening').focus();
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
						url: '<?php echo base_url()?>rekening_pptk/hapus', 
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
				jQuery('#grid').jqGrid('saveRow', last,aftersavefunc, null, {idrekening:idrekening}, null, errorfunc, afterrestorefunc);
			}
      console.log('restore_row');
    }

		function aftersavefunc(id, resp){
			console.log('aftersavefunc');
      jQuery('#grid').jqGrid('restoreRow', last);
      $("#grid").jqGrid('bindKeys', { "onEnter": edit_row});
			var msg = jQuery.parseJSON(resp.responseText);
			$.pnotify({
			  title: msg.isSuccess ? 'Sukses' : 'Gagal',
			  text: msg.message,
			  type: msg.isSuccess ? 'info' : 'error'
			});
			if(msg.id &&  msg.id != id)
				jQuery("#"+id).attr("id", msg.id);

      jQuery('#grid').trigger('reloadGrid');

      setfocusrow(msg.id);

      last = null;
		}
		
		function errorfunc(id, resp){
      console.log('errorfunc');
			var msg = jQuery.parseJSON(resp.responseText);
			if(msg.error)
				$.pnotify({
				  title: 'Gagal',
				  text: msg.error,
				  type: 'error'
				});
			jQuery('#grid').trigger("reloadGrid");
		}
    
    function afterrestorefunc(){
      console.log('afterrestorefunc');
      $(this).focus();
      $("#grid").jqGrid('bindKeys', { "onEnter": edit_row});
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
		
    function setfocusrow(id) {
      setTimeout(function(){
        jQuery('#grid').jqGrid('setSelection',id);
      },500);
    }
    
		function addrekening(id)
		{
      var option = {multi:0, mode:'bkbt', tree:1};
      Dialog.pilihRekening(option, function(obj, select){
        var rs = $(obj).jqGrid('getRowData', select[0].id);
        jQuery('#new_koderekening').val(rs.kdrek);
        jQuery('#new_namarekening').val(rs.nmrek);
        jQuery('#new_idrekening').val(rs.idrek);

        var ids = jQuery("#grid").jqGrid('getGridParam','selrow'); 
        jQuery('#'+ids+'_koderekening').val(rs.kdrek);
        jQuery('#'+ids+'_namarekening').val(rs.nmrek);
        jQuery('#'+ids+'_idrekening').val(rs.idrek);
      });
		}
				
		function addkegiatan(id)
		{
      var option = {multi:0, id_skpd:id_skpd};
      Dialog.pilihKegiatan(option, function(obj, select){
        var rs = $(obj).jqGrid('getRowData', select[0].id);
        jQuery('#new_kodekegiatan').val(rs.kode);
        jQuery('#new_namakegiatan').val(rs.nama);
        jQuery('#new_idkegiatan').val(rs.id);

        var ids = jQuery("#grid").jqGrid('getGridParam','selrow'); 
        jQuery('#'+ids+'_kodekegiatan').val(rs.kode);
        jQuery('#'+ids+'_namakegiatan').val(rs.nama);
        jQuery('#'+ids+'_idkegiatan').val(rs.id);
      });
		}
    
    function getData(request, response) {
      $.ajax({
          url: '<?php echo base_url()?>rekening_pptk/get_pejabat',
          type: 'POST',
          dataType: 'json',
          data: { q: request.term, maxResult: 20 },
          success: function (data) {
              response($.map(data, function (item) {
                  return { id: item.id, nama: item.nama };
              }))
          }
      });
    }

    function daftarNamaPejabat(elem) {
      var ids = jQuery("#grid").jqGrid('getGridParam','selrow');
      $("#grid").jqGrid('unbindKeys');
      $(elem).autocomplete({ 
        source: getData,
        minLength: 2, 
        autosearch: true,
        select: function (event, ui) {
          $('#new_idpptk').val(ui.item.id);
          $('#'+ids+'_idpptk').val(ui.item.id);
          $(elem).val(ui.item.nama);
          return false;
        }
      }).data("ui-autocomplete")._renderItem = function (ul, item) {
        return $("<li>")
        .append("<a>" + item.nama + "</a>")
        .appendTo(ul);
      };
    }    
	});
	</script>
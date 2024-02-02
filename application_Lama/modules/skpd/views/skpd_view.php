	<fieldset>
    <legend>Daftar SKPD</legend>
  </fieldset>

  <div class="row">
    <div class="span8 pull-left">
      <div id="filter" class="form-inline"></div>
      <div id="apply" style="margin-bottom:10px;"></div>
    </div>
    <div class="input-append pull-right">
      <input type="text" class="span4" id="q" />
      <span class="add-on"><i class="icon-search"></i></span>
      <span class="add-on" id="searchAdvance"><i class="icon-play"></i></span><!---- search advance  --->
    </div>
  </div>
			
			<table id="skpdTable"></table>
			<div id="skpdpager"></div>
			
		</div>
	</div>
	<div id="bidang"></div>	
	<div id="bidangskpd"></div>

	
	
	<script type="text/javascript">
    var modul = 'skpd';

	jQuery(document).ready(function() {
		var last = newid = 0;	
		var data_dasar = <?php echo $this->session->userdata('group');?>;		
		var bidang;
		var idbidangtambahan;
		var idbidangskpd;
		
		// var myelem;
/********************************* SKPD *****************************************************/
		jQuery("#skpdTable").jqGrid({
			url:'<?php echo base_url()?>skpd/get_daftar',
			editurl:'<?php echo base_url()?>skpd/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','ID BIDANG','URUSAN','BIDANG','SKPD','NAMA','LOKASI','ALAMAT','NPWP','BIDANG TAMBAHAN','idtambahan','BAGIAN SKPD','idtambahanskpd','PLAFON_BTL'],
			colModel:[
				{name:'id',index:'id',width:25,editable:true,search:false,hidden:true},
				{name:'idbidang',index:'idbidang',width:25,editable:true,search:false,hidden:true},
				{name:'urusan',index:'urusan',width:70,editable:false,sortable:false},
				{name:'bidang', index:'bidang',width:70,editable:false,edittype:'text',editoptions:{size:20},sortable:false},
				{name:'skpd',index:'skpd',width:60,editable:true,edittype:'text',editoptions:{size:20}, editrules:{number:true},sortable:false},
				{name:'namaskpd',index:'namaskpd',width:300,editable:true,edittype:'textarea',editoptions:{rows:'2',cols:'17',size:100},sortable:false},
				{name:'lokasi',index:'lokasi',width:250,editable:true,edittype:'textarea',editoptions:{rows:'2',cols:'12',size:100},sortable:false},
				{name:'alamat',index:'alamat',width:300,editable:true,edittype:'textarea',editoptions:{rows:'2',cols:'17',size:100},sortable:false},
				{name:'npwp',index:'npwp',width:250,editable:true,edittype:'textarea',editoptions:{rows:'2',cols:'12',size:50},sortable:false},
				{name:'namabidang',index:'namabidang',width:250,sortable:false,editable:true,edittype:'button',editoptions: {value:'bidang tambahan',
				class:'btn btn-primary',dataEvents: [{type: 'click', fn: addbidang}]}},
				{name:'id_bidang',index:'id_bidang',width:80,editable:true,edittype:'text',hidden:true},
				{name:'namabidangskpd',index:'namabidangskpd',sortable:false,width:250,editable:true,edittype:'button',editoptions: {value:'bagian skpd',
				class:'btn btn-primary',dataEvents: [{type: 'click', fn: addbidangskpd}]}},
				{name:'id_bidangskpd',index:'id_bidangskpd',width:80,editable:true,edittype:'text',hidden:true},
				{name:'PLAFON_BTL',index:'PLAFON_BTL',width:250,editable:true,hidden:true,editoptions: { value:"0.00" }}
			
			],
			rowNum:1000000,
			scroll:true,			
			rownumbers:false,
			pager:'#skpdpager',
			sortorder:'asc',
			viewrecords:true,
			multiselect:true,
			multiboxonly:true,
			gridview:true,
			width:999,
			height:'240',			
			gridComplete: RowColor,
			
/*********************************************** Sub Grid Pejabat SKPD *****************************************************************/
			subGrid:true,
			subGridRowExpanded: function(subgrid_id,row_id){
				var ret = jQuery("#skpdTable").jqGrid('getRowData',row_id);
				var tablePejabat,pagerPejabat;
				tablePejabat = subgrid_id+"_t";
				pagerPejabat = "p_"+tablePejabat;
				jQuery("#"+subgrid_id).html("<table id='"+tablePejabat+"' class='scroll'></table><div id='"+pagerPejabat+"' class='scroll'></div>");
				jQuery("#"+tablePejabat).jqGrid({
					url:'<?php echo base_url()?>skpd/get_daftar_pejabat'+'/'+ret.id,
					editurl:'<?php echo base_url()?>skpd/proses_form_pejabat',
					datatype:'json',
					mtype:'POST',
					colNames:['ID','JABATAN','NAMA PEJABAT','NIP','STATUS'],
					colModel:[
					{name:'ID_PEJABAT_SKPD',editable:true,index:'ID_PEJABAT_SKPD',width:5,search:false,hidden:true},					
					{name:'JABATAN',index:'JABATAN',width:300,editable:true,editoptions:{size:50,class:'autocomplete'},editrules:{required:true}},
					{name:'NAMA_PEJABAT', index:'NAMA_PEJABAT',width:280,editable:true,edittype:'text',editoptions:{size:20}},
					{name:'NIP',index:'NIP',width:200,editable:true,edittype:'text',editoptions:{size:20}},
					{name:'AKTIF',index:'AKTIF',width:100,editable:true,edittype:'select',editoptions:{value:"1:Aktif;0:Tidak Aktif"}},
					],					
					rownumbers:true,
					pager:"#"+pagerPejabat,
					sortorder:'asc',
					viewrecords:true,
					gridview:true,
					width:920,
					height:'100%',
					ondblClickRow:edit_row2,
					onSelectRow:restore_row2
					});
					
					jQuery("#"+tablePejabat).jqGrid( 'navGrid', "#"+pagerPejabat, {
           <?php
          if($akses=='3'){
          echo "
					add: true,
					addtext: 'Tambah',
					addfunc: append_row2,
					edit: true,
					edittext: 'Ubah',
					editfunc: edit_row2,
					del: true,
					deltext: 'Hapus',
					delfunc: del_row2,
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
					},{},{},{},{});
					
					function onSuccess2(resp){
						var o = $.parseJSON(resp.responseText);
						if (o.isSuccess){
						  $.pnotify({
							text: o.message,
							type: 'success'
						  });
						  return true;
						}
					}

					function onError2(id, resp){						
						var o = $.parseJSON(resp.responseText);						
						$.pnotify({
							text: o.message ? o.message : 'Server tidak bisa diakses',
							type: resp.status = 200 ? 'info' : 'error'
						  });
						return true;
					}
		
					function onAfterSave2(id, resp){
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
					
					function append_row2(){
						if(data_dasar=='1')
						{
							jQuery("#"+tablePejabat).jqGrid('restoreRow', last);
							jQuery("#"+tablePejabat).jqGrid('addRowData', ret.id, true);
							editparam= {
								'keys':true,			
								  'successfunc':onSuccess2,
								  'errorfunc':onError2,
								  'aftersavefunc':onAfterSave2,			
								  'restoreAfterError' : false
							}								
							jQuery("#"+tablePejabat).jqGrid('editRow', ret.id, editparam);
							
							//jQuery(".autocomplete").autocomplete('<?php echo base_url()?>skpd/get_jabatan');							
							last = null;
						}
						else
						{ 
							alert('Tidak bisa tambah data Pejabat SKPD');
						}
					}
	
					function edit_row2(id){
						if(data_dasar=='1')
						{
							jQuery("#"+tablePejabat).jqGrid('restoreRow', last);
							editparam = {
									"keys" : true,			
									"successfunc" : onSuccess2,
									'errorfunc':onError2,
									"url" : null,
									"extraparam" : {},
									"aftersavefunc" : onAfterSave2,
									"afterrestorefunc" : null,
									"restoreAfterError" : true,
									"mtype" : "POST"
							}
							jQuery("#"+tablePejabat).jqGrid('editRow', id,  editparam);
							//jQuery(".autocomplete").autocomplete('<?php echo base_url()?>skpd/get_jabatan');
							last = id;
						}
						else
						{
							alert('Tidak bisa ubah data Pejabat SKPD');
						}
					}
					
					function del_row2(id){
						
						if(data_dasar=='1')
						{
							var answer = confirm('Hapus pejabat dari daftar?');
							if(answer == true)
							{						
								  $.ajax({
								  type: "post",
								  dataType: "json",
								  url: root+'skpd/hapus_pejabat',
								  data: {id: id},
									success: function(res) {
									  $.pnotify({
										title: res.isSuccess ? 'Sukses' : 'Gagal',
										text: res.message,
										type: res.isSuccess ? 'info' : 'error'
									  });
									  if (true == res.isSuccess){
										jQuery("#"+tablePejabat).jqGrid('delRowData', id);
									  };
									},
								  });
							}
						}
						else
						{
							alert('Tidak bisa hapus data Pejabat SKPD');
						}
					}
	
					function restore_row2(id){
						if(id && id !== last){
						jQuery("#"+tablePejabat).jqGrid('restoreRow', last);
						last = null;
						}
					}					
	
					function errorfunc(id, resp){
						var o = $.parseJSON(resp.responseText);
						$.pnotify({
							text: o.message ? o.message : 'Server tidak bisa diakses',
							type: resp.status = 200 ? 'info' : 'error'
						  });
						jQuery("#"+tablePejabat).trigger("reloadGrid");
					}
	
			},
/************************************************************** end Sub grid pejabat SKPD **************************************************/

			ondblClickRow:function(id){
				var id = jQuery("#skpdTable").jqGrid('getGridParam','selrow');
				var ret = jQuery('#skpdTable').jqGrid('getRowData', id);
				
				if(ret.skpd != '')
				 {
					edit_row(id);
				 }
				 else
				 {
					return false;
				 }
				 
				 jQuery.post("<?php echo base_url()?>skpd/session_id",{
					'ID_SKPD':id
					},function(data){
				});
			},
			onSelectRow:restore_row

		});
		
		jQuery("#skpdTable").jqGrid( 'navGrid', '#skpdpager', {
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
    beforeRefresh: function(){
      $('#q').val('');
      var q = $('#q').val();
      var $grid = $(this);
      var mlen = $('#field').length;
      
      if (mlen === 1) { 
        var lenopt = $('#field').find('option:disabled').length;
        for (var i=0; i<lenopt; i++) {
          var field = $('#field').find('option:disabled').eq(i).val();
          $('#cek_'+field).attr('checked', false);
          $('#flt_'+field).remove();
          $('#key_'+field).remove();
        }
      }
            
      var postdata = $grid.jqGrid('getGridParam', 'postData');
      delete postdata.filters;
      delete postdata.m;
      delete postdata.q;
      $grid.jqGrid('setGridParam', {search: true, postData: postdata});
      }
		},{},{},{},{})
    <?php
    if($akses !='1'){
    ?>
    .navSeparatorAdd('#skpdpager')
    .navButtonAdd('#skpdpager',{
      caption:'',
      onClickButton: function(){ print_list("pdf"); },
      title:'Cetak Daftar (PDF)',
      buttonicon:'ui-icon-pdf',
      position:'last'
    })
    .navButtonAdd('#skpdpager',{
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
		var $grid = $('#skpdTable');
		var postdata = $grid.jqGrid('getGridParam', 'postData');

		//preview({"tipe":"daftar", "format":doc, "search":postdata['_search'], "searchField":postdata['searchField'], "searchOper":postdata['searchOper'], "searchString":postdata['searchString']});
		preview({"tipe":"daftar", "format":doc, "m":postdata.m, "q":postdata.q});
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
				var id = jQuery("#skpdTable").jqGrid('getGridParam','selrow');
				if (id!=null)
				{
					var cm = jQuery("#skpdTable").jqGrid('getColProp','namabidang');
					var cms = jQuery("#skpdTable").jqGrid('getColProp','namabidangskpd');
					cm.editable = false;
					cms.editable = false;
					var ret = jQuery("#skpdTable").jqGrid('getRowData',id);
					var data = {idbidang:ret.idbidang};
					jQuery('#skpdTable').jqGrid('restoreRow', last);
					jQuery("#skpdTable").jqGrid('addRowData', "new", data, 'after', id);					 
					editparameters = {
						'keys':true,			
						  'successfunc':onSuccess,
						  'errorfunc':onError,
						  'aftersavefunc':onAfterSave,			
						  'restoreAfterError' : false
					}			
					jQuery('#skpdTable').jqGrid('editRow', "new", editparameters);	 										
					last = null;	
					var cm = jQuery("#skpdTable").jqGrid('getColProp','namabidang');
					var cms = jQuery("#skpdTable").jqGrid('getColProp','namabidangskpd');
					cm.editable = true;	
					cms.editable = true;	
				}else{
					alert('Pilih Bidang terlebih dahulu dengan klik Nama Bidang pada Tabel');
				}
			}else{
				alert('Tidak bisa tambah data SKPD');
			}
		}
		
		function edit_row(id){
			if(data_dasar=='1'){
				jQuery('#skpdTable').jqGrid('restoreRow', last);
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
				jQuery('#skpdTable').jqGrid('editRow', id, editparameters);							
				last = id;				
			}else{
				alert('Tidak bisa ubah data SKPD');
			}
		}
		
		function del_row(id){
			if(data_dasar=='1'){			
					var answer = confirm('Hapus dari daftar?');
					if(answer == true)
					{						
						  $.ajax({
						  type: "post",
						  dataType: "json",
						  url: root+'skpd/hapus',
						  data: {id: id},
							success: function(res) {
							  $.pnotify({
								title: res.isSuccess ? 'Sukses' : 'Gagal',
								text: res.message,
								type: res.isSuccess ? 'info' : 'error'
							  });
							  if (true == res.isSuccess){
								jQuery('#skpdTable').jqGrid('delRowData', id);
							  };
							},
						  });
					}
			}else{
				alert('Tidak bisa hapus data SKPD');
			}
		}
		
		function restore_row(id){
			if(id && id !== last){
				jQuery('#skpdTable').jqGrid('restoreRow', last);
				last = null;
			}
		}

		function aftersavefunc(id, resp){
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
		
		function errorfunc(id, resp){
			var msg = jQuery.parseJSON(resp.responseText);
			if(msg.error)
				$.pnotify({
					title: 'Gagal',
					text: msg.message,
					type: 'error'
				});
				/* showmessage(msg.error);
				jQuery('#message').addClass('red'); */
				jQuery('#skpdTable').jqGrid('restoreRow', id);
				jQuery('#skpdTable').trigger('reloadGrid');
		}		
		
		function RowColor()
		{
			var idcolor = jQuery('#skpdTable').jqGrid('getDataIDs');
			for(var i=0; i<=idcolor.length; i++)
			{
				var rowcolor = jQuery('#skpdTable').jqGrid('getRowData', idcolor[i]);
				if(rowcolor['urusan'] != '' || rowcolor['bidang'] != '')
				{
					var rows = jQuery('#'+idcolor[i], jQuery('#skpdTable')).find('td');
					rows.css("color", "#666666");
					rows.css("background-color", "#ffff66");
					rows.css("font-weight", "bold");
					rows.filter('.ui-sgcollapsed').html('');
				}
			}
		}
		
/************************************************** Dialog bidang ***************************************************************/
		
		function addbidang()
		{
			set_dialog_bidang_tambahan();
			jQuery('#bidang').dialog('open');
		}	
		
		function set_dialog_bidang_tambahan()
		{
			jQuery('#bidang').dialog({
				title:'Bidang Tambahan',
				height:400,
				width:570,
				modal:true,
				autoOpen:false,
				closeOnEscape:true,
				resizable: false,
				zIndex: 550,
				buttons:[ 
						{
							text: "Tutup",
							class: 'btn btn-primary', 
							click: function() { 
								$(this).dialog("close"); 
							} 
						}
				]
			}).load("<?php echo base_url();?>skpd/bidangtambahan/");
		}
		
		function addbidangskpd()
		{
			set_dialog_bidang_skpd();
			jQuery('#bidangskpd').dialog('open');
		}	
		
		function set_dialog_bidang_skpd()
		{
			jQuery('#bidangskpd').dialog({
				title:'Bagian SKPD',
				height:440,
				width:720,
				zIndex: 250,
				modal:true,
				autoOpen:false,
				closeOnEscape:true,
				buttons:[ 
						{
							text: "Tutup",
							class: 'btn btn-primary', 
							click: function() { 
								$(this).dialog("close"); 
							} 
						}
				]
			}).load("<?php echo base_url();?>skpd/bidangskpd/");
		}
		
/***********************************************************end dialog bidang*********************************************************/

    $('#q').keypress(function (e) {
      if (e.which == 13) {
        var q = $('#q').val();
        var $grid = $('#skpdTable');
        
        var postdata = $grid.jqGrid('getGridParam', 'postData');
        $.extend(postdata,{filters: '', m: 's', q: q});
        $grid.jqGrid('setGridParam', {search: true, postData: postdata});
        $grid.trigger("reloadGrid",[{page:1}]);
      }
    });

    // ----- search advance ---- >>
    var fields = <?php echo json_encode($fields); ?>;
    var grid = $('#skpdTable');
    DialogSearch.init(fields, grid);

	});

	</script>
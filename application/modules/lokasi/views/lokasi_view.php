	<fieldset >
    <legend>Daftar Lokasi</legend>
      <select name="field" id="field" class="span2">
        <option value="lokasi">Lokasi</option>
      </select>
      <select name='oper' id='oper' class="span2">
        <option value="cn">Memuat</option>
        <option value="bw">Diawali</option>
      </select>
      <input type="text" name="string" id="string" class="span7">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
  </fieldset>
	<table id="grid" ></table>
	<div id="pager" ></div>

	<script type="text/javascript">
	jQuery(document).ready(function() {
		var last;
		var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
	
		jQuery("#grid").jqGrid({
			url:'<?php echo base_url()?>lokasi/get_daftar',
			editurl:'<?php echo base_url()?>lokasi/proses_form',
			datatype:'json',
			mtype:'POST',
			colNames:['ID','LOKASI'],
			colModel:[
				{name:'id', index:'idlokasi',width:50,editable:true,hidden:true},
				{name:'lokasi',index:'lokasi',width:500,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}}
			],
			rowNum:10,
			rowList:[10,20,30],
			rownumbers:true,
			pager:'#pager',
			sortname:'ID_LOKASI',
			sortorder:'asc',
			viewrecords:true,
			gridview:true,
			width:999,
			height:'100%',
			caption:'Lokasi',
/************************************start sub lokasi**************************************************/								
			subGrid:true,
			subGridRowExpanded: function(subgrid_id,row_id){
				var ret = jQuery("#grid").jqGrid('getRowData',row_id);
				var tableLokasi,pagerLokasi;
				tableLokasi = subgrid_id+"_t";
				pagerLokasi = "p_"+tableLokasi;
				jQuery("#"+subgrid_id).html("<table id='"+tableLokasi+"' class='scroll'></table><div id='"+pagerLokasi+"' class='scroll'></div>");
				jQuery("#"+tableLokasi).jqGrid({
					url:'<?php echo base_url()?>lokasi/get_daftar_sublokasi'+'/'+row_id,
					editurl:'<?php echo base_url()?>lokasi/proses_form_sublokasi',
					datatype:'json',
					mtype:'POST',
					colNames:['SUB LOKASI'],
					colModel:[
					{name:'lokasi',index:'lokasi',width:500,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}}
					],
					rowNum:10,
					rowList:[10,20,30],
					rownumbers:true,
					pager:"#"+pagerLokasi,
					sortorder:'asc',
					viewrecords:true,
					gridview:true,
					width:940,
					height:'100%',
					ondblClickRow:edit_row_lokasi,
					onSelectRow:restore_row_lokasi,
					/************************************start kampung **************************************************/
					subGrid:true,
					subGridRowExpanded: function(subgrid_id,row_id){
						var ret = jQuery("#"+tableLokasi).jqGrid('getRowData',row_id);
						var tableLokasi2,pagerLokasi2;
						tableLokasi2 = subgrid_id+"_t";
						pagerLokasi2 = "p_"+tableLokasi;
						jQuery("#"+subgrid_id).html("<table id='"+tableLokasi2+"' class='scroll'></table><div id='"+pagerLokasi2+"' class='scroll'></div>");
						jQuery("#"+tableLokasi2).jqGrid({
							url:'<?php echo base_url()?>lokasi/get_daftar_sublokasi_kampung'+'/'+row_id,
							editurl:'<?php echo base_url()?>lokasi/proses_form_sublokasi_kampung',
							datatype:'json',
							mtype:'POST',
							colNames:['SUB LOKASI'],
							colModel:[
							{name:'lokasi',index:'lokasi',width:500,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}}
							],
							rowNum:10,
							rowList:[10,20,30],
							rownumbers:true,
							pager:"#"+pagerLokasi2,
							sortorder:'asc',
							viewrecords:true,
							gridview:true,
							width:880,
							height:'100%',
							ondblClickRow:edit_row_lokasi2,
							onSelectRow:restore_row_lokasi2,	
							/************************************start RW **************************************************/	
							subGrid:true,
							subGridRowExpanded: function(subgrid_id,row_id){
								var ret = jQuery("#"+tableLokasi2).jqGrid('getRowData',row_id);
								var tableLokasi3,pagerLokasi3;
								tableLokasi3 = subgrid_id+"_t";
								pagerLokasi3 = "p_"+tableLokasi2;
								jQuery("#"+subgrid_id).html("<table id='"+tableLokasi3+"' class='scroll'></table><div id='"+pagerLokasi3+"' class='scroll'></div>");
								jQuery("#"+tableLokasi3).jqGrid({
									url:'<?php echo base_url()?>lokasi/get_daftar_sublokasi_rw'+'/'+row_id,
									editurl:'<?php echo base_url()?>lokasi/proses_form_sublokasi_rw',
									datatype:'json',
									mtype:'POST',
									colNames:['SUB LOKASI'],
									colModel:[
									{name:'lokasi',index:'lokasi',width:500,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}}
									],
									rowNum:10,
									rowList:[10,30,30],
									rownumbers:true,
									pager:"#"+pagerLokasi3,
									sortorder:'asc',
									viewrecords:true,
									gridview:true,
									width:820,
									height:'100%',
									ondblClickRow:edit_row_lokasi3,
									onSelectRow:restore_row_lokasi3,
									/************************************start RT **************************************************/
									subGrid:true,
									subGridRowExpanded: function(subgrid_id,row_id){
										var ret = jQuery("#"+tableLokasi3).jqGrid('getRowData',row_id);
										var tableLokasi4,pagerLokasi4;
										tableLokasi4 = subgrid_id+"_t";
										pagerLokasi4 = "p_"+tableLokasi3;
										jQuery("#"+subgrid_id).html("<table id='"+tableLokasi4+"' class='scroll'></table><div id='"+pagerLokasi4+"' class='scroll'></div>");
										jQuery("#"+tableLokasi4).jqGrid({
											url:'<?php echo base_url()?>lokasi/get_daftar_sublokasi_rt'+'/'+row_id,
											editurl:'<?php echo base_url()?>lokasi/proses_form_sublokasi_rt',
											datatype:'json',
											mtype:'POST',
											colNames:['SUB LOKASI'],
											colModel:[
											{name:'lokasi',index:'lokasi',width:500,editable:true,edittype:'text',editoptions:{size:50},editrules:{required:true, integer:false}}
											],
											rowNum:10,
											rowList:[10,40,40],
											rownumbers:true,
											pager:"#"+pagerLokasi4,
											sortorder:'asc',
											viewrecords:true,
											gridview:true,
											width:760,
											height:'100%',
											ondblClickRow:edit_row_lokasi4,
											onSelectRow:restore_row_lokasi4,
											
										});
										jQuery("#"+tableLokasi4).jqGrid( 'navGrid', "#"+pagerLokasi4, { 
											<?php
                      if($akses=='3'){
                      echo "
                      add: true,
											addtext: 'Tambah',
											addfunc: append_row_lokasi4,
											edit: true,
											edittext: 'Ubah',
											editfunc: edit_row_lokasi4,
											del: true,
											deltext: 'Hapus',
											delfunc: del_row_lokasi4,
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
										function append_row_lokasi4(){
											if(data_dasar == '3'){
												var ret = jQuery("#"+tableLokasi4).jqGrid('getRowData',row_id);
												var data = {idparent:row_id};
												if(row_id != 'new')
												{
													/*jQuery("#"+tableLokasi4).jqGrid('restoreRow', last);
													jQuery("#"+tableLokasi4).jqGrid('addRowData', "new", data);
													jQuery("#"+tableLokasi4).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc, errorfunc, null);
													last = null;*/
													jml = jQuery("#"+tableLokasi4).jqGrid('getDataIDs');
													pos = jml.length - 1;
													if(jml[pos] == "new"){
														alert('Input Sub Lokasi belum tersimpan..!!');
													}
													else{
														jQuery("#"+tableLokasi4).jqGrid('restoreRow', last);
														jQuery("#"+tableLokasi4).jqGrid('addRowData', "new", data);
														jQuery("#"+tableLokasi4).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc_lokasi4, errorfunc_lokasi4, null);
													}
													last=null;
												}
												else
												{
                          $.pnotify({
                            title: 'Perhatian',
                            text: 'Silahkan input lokasi terlebih dahulu.',
                            type: 'warning'
                          });
												}
											}
											else{
                        $.pnotify({
                          title: 'Perhatian',
                          text: 'Tidak bisa tambah data',
                          type: 'warning'
                        });
											}
										}
										
										function edit_row_lokasi4(id){
											if(data_dasar == '3'){
												jQuery("#"+tableLokasi4).jqGrid('restoreRow', last);
												jQuery("#"+tableLokasi4).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc_lokasi4, errorfunc_lokasi4, null);
												last = id;
											}else{
                        $.pnotify({
                          title: 'Perhatian',
                          text: 'Tidak bisa ubah data',
                          type: 'warning'
                        });
											}
										}
										
										function del_row_lokasi4(id){
											if(data_dasar == '3'){
												var answer = confirm('Hapus dari daftar?');
												if(answer == true)
												{
													jQuery("#"+tableLokasi4).jqGrid('delRowData', id);
													jQuery.ajax({
														url: '<?php echo base_url()?>lokasi/hapus', 
														data: { id: id},
														success: function(response){
																var msg = jQuery.parseJSON(response);
                                $.pnotify({
                                  title: msg.isSuccess ? 'Sukses' : 'Gagal',
                                  text: msg.message,
                                  type: msg.isSuccess ? 'info' : 'error'
                                });
																jQuery("#"+tableLokasi4).trigger('reloadGrid');
															},
														type: "post", 
														dataType: "html"
													});
												}
											}else{
                        $.pnotify({
                          title: 'Perhatian',
                          text: 'Tidak bisa hapus data',
                          type: 'warning'
                        });
											}
						
										}
						
										function restore_row_lokasi4(id){
											if(id && id !== last){
											jQuery("#"+tableLokasi4).jqGrid('restoreRow', last);
											last = null;
											}
										}

										function aftersavefunc_lokasi4(id, resp){
											console.log('aftersavefunc_lokasi4');
											var msg = jQuery.parseJSON(resp.responseText);
                      $.pnotify({
                        title: msg.isSuccess ? 'Sukses' : 'Gagal',
                        text: msg.message,
                        type: msg.isSuccess ? 'info' : 'error'
                      });
											if(msg.id &&  msg.id != id)
											jQuery("#"+id).attr("id", msg.id);
											jQuery('#'+tableLokasi4).trigger('reloadGrid');
										}
						
										function errorfunc_lokasi4(id, resp){
											var msg = jQuery.parseJSON(resp.responseText);
                      $.pnotify({
                        title: 'Gagal',
                        text: msg.error,
                        type: 'error'
                      });
											jQuery('#'+tableLokasi4).trigger('reloadGrid');
										}
									},
									/************************************end RT **************************************************/
									subGridBeforeExpand:function(pID, id){
										if(id == 'new')
										{
                      $.pnotify({
                        title: 'Perhatian',
                        text: 'Silahkan input lokasi terlebih dahulu.',
                        type: 'warning'
                      });
											return false;
										}
									}
								});
								jQuery("#"+tableLokasi3).jqGrid( 'navGrid', "#"+pagerLokasi3, { 
									<?php
                  if($akses=='3'){
                  echo "
                  add: true,
									addtext: 'Tambah',
									addfunc: append_row_lokasi3,
									edit: true,
									edittext: 'Ubah',
									editfunc: edit_row_lokasi3,
									del: true,
									deltext: 'Hapus',
									delfunc: del_row_lokasi3,
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
								function append_row_lokasi3(){
									if(data_dasar == '3'){
										var ret = jQuery("#"+tableLokasi3).jqGrid('getRowData',row_id);
										var data = {idparent:row_id};
										if(row_id != 'new')
										{
											/*jQuery("#"+tableLokasi3).jqGrid('restoreRow', last);
											jQuery("#"+tableLokasi3).jqGrid('addRowData', "new", data);
											jQuery("#"+tableLokasi3).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc, errorfunc, null);
											last = null;*/
											jml = jQuery("#"+tableLokasi3).jqGrid('getDataIDs');
											pos = jml.length - 1;
											if(jml[pos] == "new"){
												alert('Input Sub Lokasi belum tersimpan..!!');
											}
											else{
												jQuery("#"+tableLokasi3).jqGrid('restoreRow', last);
												jQuery("#"+tableLokasi3).jqGrid('addRowData', "new", data);
												jQuery("#"+tableLokasi3).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc_lokasi3, errorfunc_lokasi3, null);
											}
											last=null;
										}
										else
										{
                      $.pnotify({
                        title: 'Perhatian',
                        text: 'Silahkan input lokasi terlebih dahulu.',
                        type: 'warning'
                      });
										}
									}
									else{
                    $.pnotify({
                      title: 'Perhatian',
                      text: 'Tidak bisa tambah data',
                      type: 'warning'
                    });
									}
								}
								
								function edit_row_lokasi3(id){
									if(data_dasar == '3'){
										jQuery("#"+tableLokasi3).jqGrid('restoreRow', last);
										jQuery("#"+tableLokasi3).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc_lokasi3, errorfunc_lokasi3, null);
										last = id;
									}else{
                    $.pnotify({
                      title: 'Perhatian',
                      text: 'Tidak bisa ubah data',
                      type: 'warning'
                    });
									}
								}
								
								function del_row_lokasi3(id){
									if(data_dasar == '3'){
										var answer = confirm('Hapus dari daftar?');
										if(answer == true)
										{
											jQuery("#"+tableLokasi3).jqGrid('delRowData', id);
											jQuery.ajax({
												url: '<?php echo base_url()?>lokasi/hapus', 
												data: { id: id},
												success: function(response){
														var msg = jQuery.parseJSON(response);
                            $.pnotify({
                              title: msg.isSuccess ? 'Sukses' : 'Gagal',
                              text: msg.message,
                              type: msg.isSuccess ? 'info' : 'error'
                            });
														jQuery("#"+tableLokasi3).trigger('reloadGrid');
													},
												type: "post", 
												dataType: "html"
											});
										}
									}else{
                    $.pnotify({
                      title: 'Perhatian',
                      text: 'Tidak bisa hapus data',
                      type: 'warning'
                    });
									}
				
								}
				
								function restore_row_lokasi3(id){
									if(id && id !== last){
									jQuery("#"+tableLokasi3).jqGrid('restoreRow', last);
									last = null;
									}
								}

								function aftersavefunc_lokasi3(id, resp){
									console.log('aftersavefunc_lokasi3');
									var msg = jQuery.parseJSON(resp.responseText);
                  $.pnotify({
                    title: msg.isSuccess ? 'Sukses' : 'Gagal',
                    text: msg.message,
                    type: msg.isSuccess ? 'info' : 'error'
                  });
									if(msg.id &&  msg.id != id)
									jQuery("#"+id).attr("id", msg.id);
									jQuery('#'+tableLokasi3).trigger('reloadGrid');
								}
				
								function errorfunc_lokasi3(id, resp){
									var msg = jQuery.parseJSON(resp.responseText);
                  $.pnotify({
                    title: 'Gagal',
                    text: msg.error,
                    type: 'error'
                  });
									jQuery('#'+tableLokasi3).trigger('reloadGrid');
								}
							},
							/************************************end RW **************************************************/		
							subGridBeforeExpand:function(pID, id){
								if(id == 'new')
								{
                  $.pnotify({
                    title: 'Perhatian',
                    text: 'Silahkan input lokasi terlebih dahulu.',
                    type: 'warning'
                  });
									return false;
								}
							}
						});
						jQuery("#"+tableLokasi2).jqGrid( 'navGrid', "#"+pagerLokasi2, { 
            <?php
            if($akses=='3'){
            echo "
							add: true,
							addtext: 'Tambah',
							addfunc: append_row_lokasi2,
							edit: true,
							edittext: 'Ubah',
							editfunc: edit_row_lokasi2,
							del: true,
							deltext: 'Hapus',
							delfunc: del_row_lokasi2,
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
						function append_row_lokasi2(){
							if(data_dasar == '3'){
								var ret = jQuery("#"+tableLokasi2).jqGrid('getRowData',row_id);
								var data = {idparent:row_id};
								if(row_id != 'new')
								{
									/*jQuery("#"+tableLokasi2).jqGrid('restoreRow', last);
									jQuery("#"+tableLokasi2).jqGrid('addRowData', "new", data);
									jQuery("#"+tableLokasi2).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc, errorfunc, null);
									last = null;*/
									jml = jQuery("#"+tableLokasi2).jqGrid('getDataIDs');
									pos = jml.length - 1;
									if(jml[pos] == "new"){
										alert('Input Sub Lokasi belum tersimpan..!!');
									}
									else{
										jQuery("#"+tableLokasi2).jqGrid('restoreRow', last);
										jQuery("#"+tableLokasi2).jqGrid('addRowData', "new", data);
										jQuery("#"+tableLokasi2).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc_lokasi2, errorfunc_lokasi2, null);
									}
									last=null;
								}
								else
								{
                  $.pnotify({
                    title: 'Perhatian',
                    text: 'Silahkan input lokasi terlebih dahulu.',
                    type: 'warning'
                  });
								}
							}
							else{
                $.pnotify({
                  title: 'Perhatian',
                  text: 'Tidak bisa tambah data',
                  type: 'warning'
                });
							}
						}
						
						function edit_row_lokasi2(id){
							if(data_dasar == '3'){
								jQuery("#"+tableLokasi2).jqGrid('restoreRow', last);
								jQuery("#"+tableLokasi2).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc_lokasi2, errorfunc_lokasi2, null);
								last = id;
							}else{
                $.pnotify({
                  title: 'Perhatian',
                  text: 'Tidak bisa ubah data',
                  type: 'warning'
                });
							}
						}
						
						function del_row_lokasi2(id){
							if(data_dasar == '3'){
								var answer = confirm('Hapus dari daftar?');
								if(answer == true)
								{
									jQuery("#"+tableLokasi2).jqGrid('delRowData', id);
									jQuery.ajax({
										url: '<?php echo base_url()?>lokasi/hapus', 
										data: { id: id},
										success: function(response){
												var msg = jQuery.parseJSON(response);
                        $.pnotify({
                          title: msg.isSuccess ? 'Sukses' : 'Gagal',
                          text: msg.message,
                          type: msg.isSuccess ? 'info' : 'error'
                        });
												jQuery("#"+tableLokasi2).trigger('reloadGrid');
											},
										type: "post", 
										dataType: "html"
									});
								}
							}else{
                $.pnotify({
                  title: 'Perhatian',
                  text: 'Tidak bisa hapus data',
                  type: 'warning'
                });
							}
		
						}
		
						function restore_row_lokasi2(id){
							if(id && id !== last){
							jQuery("#"+tableLokasi2).jqGrid('restoreRow', last);
							last = null;
							}
						}

						function aftersavefunc_lokasi2(id, resp){
							console.log('aftersavefunc_lokasi2');
							var msg = jQuery.parseJSON(resp.responseText);
              $.pnotify({
                title: msg.isSuccess ? 'Sukses' : 'Gagal',
                text: msg.message,
                type: msg.isSuccess ? 'info' : 'error'
              });
							if(msg.id &&  msg.id != id)
							jQuery("#"+id).attr("id", msg.id);
							jQuery('#'+tableLokasi2).trigger('reloadGrid');
						}
		
						function errorfunc_lokasi2(id, resp){
							var msg = jQuery.parseJSON(resp.responseText);
              $.pnotify({
                title: 'Gagal',
                text: msg.error,
                type: 'error'
              });
							jQuery('#'+tableLokasi2).trigger('reloadGrid');
						}
					},
					
					/************************************end kampung **************************************************/

					subGridBeforeExpand:function(pID, id){
						if(id == 'new')
						{
              $.pnotify({
                title: 'Perhatian',
                text: 'Silahkan input lokasi terlebih dahulu.',
                type: 'warning'
              });
							return false;
						}
					}
				});
				jQuery("#"+tableLokasi).jqGrid( 'navGrid', "#"+pagerLokasi, { 
					<?php
          if($akses=='3'){
          echo "
          add: true,
					addtext: 'Tambah',
					addfunc: append_row_lokasi,
					edit: true,
					edittext: 'Ubah',
					editfunc: edit_row_lokasi,
					del: true,
					deltext: 'Hapus',
					delfunc: del_row_lokasi,
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
				function append_row_lokasi(){
					if(data_dasar=='3'){
						//var ret = jQuery("#grid").jqGrid('getRowData',row_id);
						//var id = jQuery("#grid").jqGrid('getGridParam','selrow');
						var ret = jQuery("#grid").jqGrid('getRowData',row_id);
						var data = {idparent:row_id};
						if(row_id != 'new')
						{
							/*jQuery("#"+tableLokasi).jqGrid('restoreRow', last);
							jQuery("#"+tableLokasi).jqGrid('addRowData', "new", data);
							jQuery("#"+tableLokasi).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc, errorfunc, null);
							last = null;*/
							jml = jQuery("#"+tableLokasi).jqGrid('getDataIDs');
							pos = jml.length - 1;
							if(jml[pos] == "new"){
								alert('Input Sub Lokasi belum tersimpan..!!');
							}
							else{
								jQuery("#"+tableLokasi).jqGrid('restoreRow', last);
								jQuery("#"+tableLokasi).jqGrid('addRowData', "new",data);
								jQuery("#"+tableLokasi).jqGrid('editRow', "new", true, null, null, null,data, aftersavefunc_lokasi, errorfunc_lokasi, null);
							}
							last=null;
						}
						else
						{
              $.pnotify({
                title: 'Perhatian',
                text: 'Silahkan input lokasi terlebih dahulu.',
                type: 'warning'
              });
						}
					}else{
            $.pnotify({
              title: 'Perhatian',
              text: 'Tidak bisa tambah data',
              type: 'warning'
            });
					}
				}

				function edit_row_lokasi(id){
					if(data_dasar=='3'){
						jQuery("#"+tableLokasi).jqGrid('restoreRow', last);
						jQuery("#"+tableLokasi).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc_lokasi, errorfunc_lokasi, null);
						last = id;
					}else{
            $.pnotify({
              title: 'Perhatian',
              text: 'Tidak bisa ubah data',
              type: 'warning'
            });
					}
				}
				
				function del_row_lokasi(id){
					if(data_dasar=='3'){
						var answer = confirm('Hapus dari daftar?');
						if(answer == true)
						{
							jQuery("#"+tableLokasi).jqGrid('delRowData', id);
							jQuery.ajax({
								url: '<?php echo base_url()?>lokasi/hapus', 
								data: { id: id},
								success: function(response){
										var msg = jQuery.parseJSON(response);
                    $.pnotify({
                      title: msg.isSuccess ? 'Sukses' : 'Gagal',
                      text: msg.message,
                      type: msg.isSuccess ? 'info' : 'error'
                    });
										jQuery("#"+tableLokasi).trigger('reloadGrid');
									},
								type: "post", 
								dataType: "html"
							});
						}
					}else{
            $.pnotify({
              title: 'Perhatian',
              text: 'Tidak bisa hapus data',
              type: 'warning'
            });
					}
				}

				function restore_row_lokasi(id){
					if(id && id !== last){
					jQuery("#"+tableLokasi).jqGrid('restoreRow', last);
					last = null;
					}
				}

				function aftersavefunc_lokasi(id, resp){
					console.log('aftersavefunc_lokasi');
					var msg = jQuery.parseJSON(resp.responseText);
          $.pnotify({
            title: msg.isSuccess ? 'Sukses' : 'Gagal',
            text: msg.message,
            type: msg.isSuccess ? 'info' : 'error'
          });
					if(msg.id &&  msg.id != id)
					jQuery("#"+id).attr("id", msg.id);
					jQuery('#'+tableLokasi).trigger('reloadGrid');
				}

				function errorfunc_lokasi(id, resp){
					var msg = jQuery.parseJSON(resp.responseText);
          $.pnotify({
            title: 'Gagal',
            text: msg.error,
            type: 'error'
          });
					jQuery('#'+tableLokasi).trigger('reloadGrid');
				}

			},
/************************************end sub lokasi**************************************************/			
			
			ondblClickRow: edit_row,
			onSelectRow: restore_row,
			subGridBeforeExpand:function(pID, id){
				if(id == 'new')
				{
          $.pnotify({
            title: 'Perhatian',
            text: 'Silahkan input lokasi terlebih dahulu.',
            type: 'warning'
          });
					return false;
				}
			}
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
		});
		
		function append_row(){
			if(data_dasar=='3'){
				/*jQuery('#grid').jqGrid('restoreRow', last);
				jQuery("#grid").jqGrid('addRowData', "new", true);
				jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
				last = null;*/
				jml = jQuery("#grid").jqGrid('getDataIDs');
				pos = jml.length - 1;
				if(jml[pos] == "new"){
					alert('Input Wilayah belum tersimpan..!!');
				}
				else{
					jQuery('#grid').jqGrid('restoreRow', last);
					jQuery("#grid").jqGrid('addRowData', "new",true);
					jQuery('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
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
			if(data_dasar=='3'){
				jQuery('#grid').jqGrid('restoreRow', last);
				jQuery('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
				last = id;
			}else{
        $.pnotify({
          title: 'Perhatian',
          text: 'Tidak bisa ubah data',
          type: 'warning'
        });
			}
		}
		
		function del_row(id){
			if(data_dasar=='3'){
				var answer = confirm('Hapus dari daftar?');
				if(answer == true)
				{
					jQuery('#grid').jqGrid('delRowData', id);
					jQuery.ajax({
						url: '<?php echo base_url()?>lokasi/hapus', 
						data: { id: id},
						success: function(response){
								var msg = jQuery.parseJSON(response);
                $.pnotify({
                  title: msg.isSuccess ? 'Sukses' : 'Gagal',
                  text: msg.message,
                  type: msg.isSuccess ? 'info' : 'error'
                });
								jQuery('#grid').trigger('reloadGrid');
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
				jQuery('#grid').jqGrid('restoreRow', last);
				last = null;
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
        jQuery("#new").attr("id", msg.id);
        jQuery('#grid').trigger('reloadGrid');
		}
		
		function errorfunc(id, resp){
			var msg = jQuery.parseJSON(resp.responseText);
      $.pnotify({
        title: 'Gagal',
        text: msg.error,
        type: 'error'
      });
      jQuery('#grid').trigger('reloadGrid');
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		    
    jQuery('#home').click(function(){
      location.href='<?php echo base_url();?>';
    });
  
	});

	</script>
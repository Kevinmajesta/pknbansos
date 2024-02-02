<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<style type="text/css">
	.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
	* html .cf { zoom: 1; }
	*:first-child+html .cf { zoom: 1; }

	/* html { margin: 0; padding: 0; } */
	/* body { font-size: 100%; margin: 0; padding: 1.75em; font-family: 'Helvetica Neue', Arial, sans-serif; } */

	h1 { font-size: 1.75em; margin: 0 0 0.6em 0; }

	a { color: #2996cc; }
	a:hover { text-decoration: none; }

	p { line-height: 1.5em; }
	.small { color: #666; font-size: 0.875em; }
	.large { font-size: 1.25em; }

	/**
	 * Nestable
	 */

	.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 600px; list-style: none; font-size: 13px; line-height: 20px; }

	.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
	.dd-list .dd-list { padding-left: 30px; }
	.dd-collapsed .dd-list { display: none; }

	.dd-item,
	.dd-empty,
	.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

	.dd-handle { display: block; height: 40px; margin: 5px 0; padding: 9px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
		background: #fafafa;
		background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
		background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
		background:         linear-gradient(top, #fafafa 0%, #eee 100%);
		-webkit-border-radius: 3px;
				border-radius: 3px;
		box-sizing: border-box; -moz-box-sizing: border-box;
	}
	.dd-handle:hover { color: #2ea8e5; background: #fff; }

	.dd-item > button { display: block; position: relative; cursor: pointer; float: left; left: 4px;width: 25px; height: 30px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
	.dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
	.dd-item > button[data-action="collapse"]:before { content: '-'; }

	.dd-placeholder,
	.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
	.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
		background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), 
						  -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
		background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), 
							 -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
		background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff), 
								  linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
		background-size: 60px 60px;
		background-position: 0 0, 30px 30px;
	}

	.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
	.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
	.dd-dragel .dd-handle {
		-webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
				box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
	}

	/**
	 * Nestable Extras
	 */

	.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }

	#nestable-menu { padding: 0; margin: 20px 0; }

	#nestable-output,
	#nestable2-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }

	#nestable2 .dd-handle {
		color: #fff;
		border: 1px solid #999;
		background: #bbb;
		background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
		background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);
		background:         linear-gradient(top, #bbb 0%, #999 100%);
	}
	#nestable2 .dd-handle:hover { background: #bbb; }
	#nestable2 .dd-item > button:before { color: #fff; }

	@media only screen and (min-width: 800px) { 

		.dd { float: left; width: 64%; }
		.dd + .dd { margin-left: 2%; }

	}

	.dd-hover > .dd-handle { background: #2ea8e5 !important; }

	/**
	 * Nestable Draggable Handles width: 400px;
	 */

	.dd3-content { 
		display: block; 
		height: 40px; 
		margin: 5px 0; 
		padding: 5px 10px 5px 40px; 
		color: #333; 
		text-decoration: none; 
		font-size: 14px; 
		font-weight: bold; 
		border: 1px solid #ccc;
		background: #fafafa;
		background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
		background: -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
		background: linear-gradient(top, #fafafa 0%, #eee 100%);
		-webkit-border-radius: 3px;
				border-radius: 3px;
		box-sizing: border-box; -moz-box-sizing: border-box;
	}
	.dd3-content:hover { color: #000; background: #fff; }

	.dd-dragel > .dd3-item > .dd3-content { margin: 0; }

	.dd3-item > button { margin-left: 30px; }

	.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 35px; text-indent: 100%; white-space: nowrap; overflow: hidden;
		border: 1px solid #aaa;
		background: #ddd;
		background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
		background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
		background:         linear-gradient(top, #ddd 0%, #bbb 100%);
		border-top-right-radius: 0;
		border-bottom-right-radius: 0;
	}
	.dd3-handle:before { content: '='; display: block; position: absolute; left: 0; top: 8px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
	.dd3-handle:hover { background: #ddd; }
	

</style>
<h4><?php echo 'Menu'; ?><!--<legend id="bc" data-bind="text: title"></legend>--></h4>
<form>	
	<fieldset>
		<button data-toggle="modal" href="#form-content" data-bind="click: parent_menu" value="0" class="btn btn-success">
			Buat Menu
		</button>
		<button data-toggle="modal" data-bind="enable: canSave, click: save_menu" class="btn btn-success">
			Simpan Menu
		</button>
	</fieldset><br/>
	<div class="dd" id="nestable3">
		<ol class="dd-list">
			<?php
			foreach($group_menu as $x => $hasil)
			{
				if($hasil['AKTIF'] == '1')
				{
					$aktif = 'Aktif';
				}
				else 
				{
					$aktif = 'Tidak Aktif';
				}
			?>
				<li class="dd-item dd3-item" data-id="<?php echo $hasil['ID']; ?>">
					<div class="dd-handle dd3-handle">Drag</div>
					<div class="dd3-content">
							<div class="control-group pull-left" style="margin-top:4px">
								<?php echo $hasil['TITLE'];?>
							</div>
							<div class="control-group pull-right">
								<button data-toggle="modal" href="" class="btn btn-link" style="text-decoration: none;color:black;" value="<?php echo $aktif; ?>" ><?php echo $aktif; ?></button>
								<button data-toggle="modal" href="#form-content" data-bind="click: parent_menu" class="btn btn-link" value="<?php echo $hasil['ID']; ?>" >Ubah</button>
								<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $hasil['ID']; ?>" >Hapus</button>
							</div>
					</div>
					<?php
					if($hasil['child']){
					?>
					<ol class="dd-list">
						<?php
						foreach($hasil['child'] as $sub)
						{
							if($sub['TITLE'] != '---')
							{
								$title = $sub['TITLE'];
								$edit  = 'Edit';
								if($sub['AKTIF'] == '1')
								{
									$aktif = 'Aktif';
								}
								else
								{
									$aktif = 'Tidak Aktif';
								}
							}
							else
							{
								$title = '======================';
								$edit  = '';
								$aktif = '';
							}
						?>
						<li class="dd-item dd3-item" data-id="<?php echo $sub['ID']; ?>">
							<div class="dd-handle dd3-handle">Drag</div>
							<div class="dd3-content">
								<div class="control-group pull-left" style="margin-top:4px">
									<?php echo $title;?>
								</div>
								<div class="control-group pull-right">
									<button data-toggle="modal" href="" class="btn btn-link" style="text-decoration: none;color:black;" value="<?php echo $aktif; ?>" ><?php echo $aktif; ?></button>
									<?php 
									if($sub['TITLE'] != '---')
									{
										?>
										<button data-toggle="modal" href="#form-content" data-bind="click: parent_menu" class="btn btn-link" value="<?php echo $sub['ID']; ?>" >Ubah</button>
										<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub['ID']; ?>" >Hapus</button>
										<?php
									}
									else{
									?>
										<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub['ID']; ?>" >Hapus</button>
										<?php
									}
									?>
								</div>								
							</div>
							<?php
							if($sub['child']){
							?>
							<ol class="dd-list">
								<?php
								foreach($sub['child'] as $sub2)
								{
									if($sub2['TITLE'] != '---')
									{
										$title = $sub2['TITLE'];
										$edit  = 'Edit';
										if($sub2['AKTIF'] == '1')
										{
											$aktif = 'Aktif';
										}
										else
										{
											$aktif = 'Tidak Aktif';
										}
									}
									else
									{
										$title = '======================';
										$edit  = '';
										$aktif  = '';
									}
								?>
								<li class="dd-item dd3-item" data-id="<?php echo $sub2['ID']; ?>">
									<div class="dd-handle dd3-handle">Drag</div>
									<div class="dd3-content">
										<div class="control-group pull-left" style="margin-top:4px">
											<?php echo $title;?>
										</div>
										<div class="control-group pull-right">
											<button data-toggle="modal" href="" class="btn btn-link" style="text-decoration: none;color:black;" value="<?php echo $aktif; ?>" ><?php echo $aktif; ?></button>
											<?php 
											if($sub2['TITLE'] != '---')
											{
												?>
												<button data-toggle="modal" href="#form-content" data-bind="click: parent_menu" class="btn btn-link" value="<?php echo $sub2['ID']; ?>" >Ubah</button>
												<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub2['ID']; ?>" >Hapus</button>
												<?php
											}
											else{
											?>
												<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub2['ID']; ?>" >Hapus</button>
												<?php
											}
											?>
										</div>
									</div>
									<?php
									if($sub2['child']){
									?>
									<ol class="dd-list">
										<?php
										foreach($sub2['child'] as $sub3)
										{
											if($sub3['TITLE'] != '---')
											{
												$title = $sub3['TITLE'];
												$edit  = 'Edit';
												if($sub3['AKTIF'] == '1')
												{
													$aktif = 'Aktif';
												}
												else
												{
													$aktif = 'Tidak Aktif';
												}
											}
											else
											{
												$title = '======================';
												$edit  = '';
												$aktif  = '';
											}
										?>
										<li class="dd-item dd3-item" data-id="<?php echo $sub3['ID']; ?>">
											<div class="dd-handle dd3-handle">Drag</div>
											<div class="dd3-content">
												<div class="control-group pull-left" style="margin-top:4px">
													<?php echo $title;?>
												</div>
												<div class="control-group pull-right">
													<button data-toggle="modal" href="" class="btn btn-link" style="text-decoration: none;color:black;" value="<?php echo $aktif; ?>" ><?php echo $aktif; ?></button>
													<?php 
													if($sub3['TITLE'] != '---')
													{
														?>
														<button data-toggle="modal" href="#form-content" data-bind="click: parent_menu" class="btn btn-link" value="<?php echo $sub3['ID']; ?>" >Ubah</button>
														<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub3['ID']; ?>" >Hapus</button>
														<?php
													}
													else{
													?>
														<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub3['ID']; ?>" >Hapus</button>
														<?php
													}
													?>
												</div>
											</div>
											<?php
											if($sub3['child']){
											?>
											<ol class="dd-list">
												<?php
												foreach($sub3['child'] as $sub4)
												{
													if($sub4['TITLE'] != '---')
													{
														$title = $sub4['TITLE'];
														$edit  = 'Edit';
														if($sub4['AKTIF'] == '1')
														{
															$aktif = 'Aktif';
														}
														else
														{
															$aktif = 'Tidak Aktif';
														}
													}
													else
													{
														$title = '======================';
														$edit  = '';
														$aktif  = '';
													}
												?>
												<li class="dd-item dd3-item" data-id="<?php echo $sub4['ID']; ?>">
													<div class="dd-handle dd3-handle">Drag</div>
													<div class="dd3-content">
														<div class="control-group pull-left" style="margin-top:4px">
															<?php echo $title;?>
														</div>
														<div class="control-group pull-right">
															<button data-toggle="modal" href="" class="btn btn-link" style="text-decoration: none;color:black;" value="<?php echo $aktif; ?>" ><?php echo $aktif; ?></button>
															<?php 
															if($sub4['TITLE'] != '---')
															{
																?>
																<button data-toggle="modal" href="#form-content" data-bind="click: parent_menu" class="btn btn-link" value="<?php echo $sub4['ID']; ?>" >Ubah</button>
																<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub4['ID']; ?>" >Hapus</button>
																<?php
															}
															else{
															?>
																<button data-toggle="modal" data-bind="click: deleted" class="btn btn-link" value="<?php echo $sub4['ID']; ?>" >Hapus</button>
																<?php
															}
															?>
														</div>
													</div>
												</li>
												<?php
												}
												?>
											</ol>
											<?php
											}
											?>
										</li>
										<?php
										}
										?>
									</ol>
									<?php
									}
									?>
								</li>
								<?php
								}
								?>
							</ol>
							<?php
							}
							?>
						</li>
						<?php
						}
						?>
					</ol>
					<?php
					}
					?>
				</li>
			<?php
			}
			?>
		</ol>
	</div>	
</form>	
<div id="form-content" class="modal hide fade in" style="display: none;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal"><i class="icon-arrow-up"></i></a>
		<h3><legend id="bc" data-bind="text: title"></legend></h3>
	</div>
	<div id="spinner" data-bind="visible: spinner === 1">
		<img src="<?php echo base_url()?>assets/img/ajax-loader.gif" alt="Loading..."/>
	</div>
	<form id="frm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>group/menu_proses">
		<div class="control-group" data-bind="validationElement: parent" id="parents">
			<label class="control-label" for="inputParent" >
				Parent
			</label>
			<div class="controls">
				<select id="parent" placeholder="parent" data-bind="value: parent" class="selectpicker">
					<option value="0">-root-</option>
				</select>
			</div>
		</div>
		<div class="control-group" id="tipes">
			<label class="control-label" for="inputtipe">
				Tipe
			</label>
			<div class="controls">
				<input type="radio" data-bind="checked: is_checked" value="1" />Menu Baru
				<input type="radio" data-bind="checked: is_checked" value="0" />Separator
			</div>
		</div>
		<div class="control-group" data-bind="validationElement: n_menu,visible: is_checked() == true">
			<label class="control-label" for="inputUsername" >
				Nama Menu
				</label>
			<div class="controls">
				<input type="text" id="n_menu" placeholder="nama menu" data-bind="value: n_menu">
			</div>
		</div>
		<div class="control-group" data-bind="visible: is_checked() == true">
			<label class="control-label" for="inputName">
				Link
			</label>
			<div class="controls">
				<input type="text" id="links" placeholder="Link" data-bind="value: links,visible: is_checked() == true" >
			</div>
		</div>
		<div class="control-group" data-bind="validationElement: aktif,visible: is_checked() == true">
			<label class="control-label" for="inputSKPD">
				Status
			</label>
			<div class="controls">
				<input type="radio" data-bind="checked: aktif" value="1" />(aktif)
				<input type="radio" data-bind="checked: aktif" value="0" />(tidak aktif)
			</div>
		</div>
	</form>
	<div class="modal-footer">
		<input class="btn btn-success" type="submit" value="Simpan" id="submit" data-bind="enable: canSave, click: save">
		<a href="#" class="btn" data-dismiss="modal">Tutup</a>
	</div>
</div>    
<textarea id="nestable3-output" class="span6" style="height: 200px;display: none;"></textarea>
<script>
	
	function refresh (timeoutPeriod)
	{
		refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
	}
	
	var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
			console.log($('#nestable3-output').val());
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };
	$('#nestable3').nestable({
        group: 1
    })
    .on('change', updateOutput);
    
	updateOutput($('#nestable3').data('output', $('#nestable3-output')));
	console.log($('#nestable3-output').val());
    $('#nestable3').nestable();
	$('.dd').nestable('collapseAll');
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: 'error',
	});  
 
	var ModelGroup = function (){
		var self = this;
		self.modul = 'Menu';
		self.akses_level = ko.observable(03);
		self.id = ko.observable('');
		self.n_menu = ko.observable('');
		self.is_checked = ko.observable(true);
		self.ids= ko.observable("");
		self.parent = ko.observable('0');
		self.aktif = ko.observable('0');
		self.links  = ko.observable('#');
		self.parent_title = ko.observable('');
		self.parentEdit = ko.observable('0');
		self.spinner = ko.observable('1');
    
		self.id_menu = function(data, element)
		{           
			self.ids($(element.target).val());
			var idm = $(element.target).val();
			data =
			{
				id: idm,
			};
			$.ajax({
				url: '<?php echo base_url();?>group/menu_by_id',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(res)
				{
					if (res)
					{
						self.n_menu(res.title);
						self.aktif(res.aktif);
						self.links(res.links);
						self.parent(res.parent_id); 
                    
						//add child
						if(res.parent_id == '0')
						{
							self.n_menu('');
							self.parent_title(res.title);
							self.parent(res.parent_id);
						}
					}
				}
            }); 
		}
    
		self.parent_menu = function(data, element)
		{           
           
			self.ids($(element.target).val());
			var idm = $(element.target).val();
			data =
			{
				id: idm,
			};
			if(idm != 0)
			{
				$.ajax(
				{
					url: '<?php echo base_url();?>group/menu_by_id',
					type: 'post',
					dataType: 'json',
					data: data,
					success: function(res)
					{
						if (res)
						{
							self.n_menu(res.title);
							self.aktif(res.aktif);
							self.links(res.links);
							self.parentEdit('1');
							$("#parents").hide();
							$("#tipes").hide();
							$("select#parent option").remove();
							if(res.parent_id == '0'){								
								$('<option value="0" class="">--root--</option>').appendTo('#parent');
							}
							else{
								$.getJSON('<?php echo base_url();?>group/data_parent_by_id/'+res.parent_id, function(json) {
									$.each(json.rows, function(id,val){
										$('<option value="' + val.id+ '" class="">' + val.title+'</option>').appendTo('#parent');
									});
								}); 
							} 
							self.parent(res.parent_id);
						}
					}
				});	
			}
			else
			{
				self.n_menu('');
				self.links('#');
				self.parentEdit('0');
				$("#parents").show();
				$("#tipes").show();
				//self.tipe(1);
				self.aktif(1);
				self.spinner(0);
				$("select#parent option").remove();
				$('<option value="0" class="">--root--</option>').appendTo('#parent');
				$.getJSON('<?php echo base_url();?>group/parent_by_id/'+0, function(json) {
					$.each(json.rows, function(id,val){
						$('<option value="' + val.id+ '" class="">' + val.title+'</option>').appendTo('#parent');
					});
				});
			}
		}
        
		self.mode = ko.computed(function(){
		  return self.parentEdit() > 0 || self.parent() > 0 ? 'edit' : 'new';
		});

		self.title = ko.computed(function(){
		  return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
		});     
		
		 self.isEdit = ko.computed(function(){
		  return self.mode() === 'edit';
		});

		self.canPrint = ko.computed(function(){
		  return self.akses_level() >= 2;
		});

		self.canSave = ko.computed(function(){
		  return self.akses_level() >= 3;
		});

		self.errors = ko.validation.group(self);
	}  

	var App = new ModelGroup();

	App.back = function(){
		location.href = root+modul;
	}

	App.save = function(){		
		var $frm = $('#frm'),
		  data = JSON.parse(ko.toJSON(App));
		  
		if (!App.isValid()) {
		  App.errors.showAllMessages();
		  return ;
		}
		
		$.ajax({
		  url: $frm.attr('action'),
		  type: 'post',
		  dataType: 'json',
		  data: data,
		  success: function(res, xhr){
			if (res.id) App.id(res.id);
			
			if (res.isSuccess)
			{
			  refresh('2400');
			}
			
			$.pnotify({
			  title: res.isSuccess ? 'Sukses' : 'Gagal',
			  text: res.message,
			  type: res.isSuccess ? 'info' : 'error'
			});
		  }
		});
	}
	
	App.save_menu = function(){				  
		if (!App.isValid()) {
		  App.errors.showAllMessages();
		  return ;
		}
		
		var data =
		{
			id: $('#nestable3-output').val(),
		};
		$.ajax({
		  url: '<?php echo base_url();?>group/simpan_menu',
		  type: 'post',
		  dataType: 'json',
		  data: data,
		  success: function(res, xhr){
			if (res.id) App.id(res.id);
			
			if (res.isSuccess)
			{
			  refresh('2400');
			}
			
			$.pnotify({
			  title: res.isSuccess ? 'Sukses' : 'Gagal',
			  text: res.message,
			  type: res.isSuccess ? 'info' : 'error'
			}); 
		  }
		});
	}
  
	App.deleted = function(data, element){
    
		var idm = $(element.target).val();
		data =
		{
			id: idm,
		};
	
		var agree=confirm("Apakah Anda yakin akan menghapus menu?");
		if (agree)
		{
			$.ajax({
				url: '<?php echo base_url();?>group/delete_menu',
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(res, xhr){
					if (res.id) App.id(res.id);
			  
					if (res.isSuccess)
					{
						refresh('2400');
					}
			  
					$.pnotify({
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
				}
			});
		}
	}   

	App.init_select = function(element, callback){
		var data = {'text': $(element).attr('data-init')};
		callback(data);
	} 
 
	ko.applyBindings(App);

	$('#spinner').ajaxStart(function ()
    {
		$(this).fadeIn('fast');
    }).ajaxStop(function ()
    {
		$(this).stop().fadeOut('fast');
    });
</script>
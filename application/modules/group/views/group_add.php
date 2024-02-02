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
	.dd-handle { pointer-events: none; } 
	

</style>
<h4><legend id="bc" data-bind="text: title"></legend></h4>

<form id="frm" method="post" action="<?php echo base_url(); ?>group/group_proses">
	<div class="control-group" data-bind="validationElement: gname">
		<label class="control-label" for="inputGname" >Group Name</label>
		<div class="controls">
			<input type="text" id="gname" name="gname" placeholder="Group Name" data-bind="value: gname">
		</div>
	</div>
	  
	<div class="control-group">
		<label class="control-label" for="inputGDesc" >Deskripsi</label>
		<div class="controls">
			<input type="text" id="gdesc" name="gdesc" placeholder="Deskripsi" data-bind="value: gdesc">
			<div class="control-group pull-right" style="margin-right:340px;">
				<input type="button" id="save" value="Simpan" class="btn btn-primary" data-bind="enable: canSave, click: save" />
				<input type="button" id="save" value="Kembali" class="btn btn-primary" data-bind="click: back" />
			</div>
		</div>
		
	</div>
	
	<div class="control-group">
		
	</div>
	
	<div class="dd" id="nestable3">
		<ol class="dd-list">
			<?php
			$i=0;
      //print_r($group_menus);
			foreach($group_menus as $x => $hasil)
			{
			?>
				<li class="dd-item dd3-item" data-id="<?php echo $hasil['ID']; ?>">
					<div class="dd-handle dd3-handle">Drag</div>
					<div class="dd3-content">
						<div class="control-group pull-left" style="margin-top:4px">
							<input type="checkbox" value="<?php echo $hasil['ID'] ?>" name="parent[]" <?php if($hasil['is_checked'] == '1') echo "checked"; ?>> 
							<?php echo $hasil['TITLE'];?>
						</div>
					</div>
					<?php
					$u=0;
					if($hasil['child']){
					?>
					<ol class="dd-list">
						<?php
						foreach($hasil['child'] as $sub)
						{
						?>
						<li class="dd-item dd3-item" data-id="<?php echo $sub['ID']; ?>">
							<div class="dd-handle dd3-handle">Drag</div>
							<div class="dd3-content">
								<div class="control-group pull-left" style="margin-top:4px">
									<input type="hidden" value="<?php echo $sub['ID'] ?>" name="child[]"/> 
									<?php echo $sub['TITLE'];?>
								</div>
								<div class="control-group pull-right">									
									<?php 
									if($sub['TITLE'] != '---')
									{
										?>
										<!--<select data-bind="options: App.opsi, optionsCaption: 'Tidak Ada Akses...', optionsText: 'text', optionsValue: 'id'" name="val[]">-->
										<select name="val[]">
											<?php
												if($sub['akses']=='' || $sub['akses']=='0'){
													echo"
														<option value='0' selected>Tidak Ada Akses</option>
														<option value='1'>Lihat</option>
														<option value='2'>Cetak</option>
														<option value='3'>Ubah</option>
													";
												}
												else if($sub['akses']=='1'){
													echo"
														<option value='0' >Tidak Ada Akses</option>
														<option value='1' selected>Lihat</option>
														<option value='2'>Cetak</option>
														<option value='3'>Ubah</option>
													";
												}
												else if($sub['akses']=='2'){
													echo"
														<option value='0'>Tidak Ada Akses</option>
														<option value='1'>Lihat</option>
														<option value='2' selected>Cetak</option>
														<option value='3'>Ubah</option>
													";
												}
												else if($sub['akses']=='3'){
													echo"
														<option value='0'>Tidak Ada Akses</option>
														<option value='1'>Lihat</option>
														<option value='2'>Cetak</option>
														<option value='3' selected>Ubah</option>
													";
												} 
											?>
										</select>
										<?php
									}
									?>
								</div>								
							</div>
						</li>
						<?php
							$u+=1;
						}
						?>
					</ol>
					<?php
					}
					?>
				</li>
			<?php
				$i+=1;
			}
			?>
		</ol>
	</div>	
	<input type="hidden" name="id" value="<?php echo isset($data['ID'])?$data['ID']:0 ?>">
</form>	

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
	
    $('#nestable3').nestable();
	$('.dd').nestable('collapseAll');
	//$('#nestable3').nestable('no-drag');
	$('#nestable3').draggable({ disabled: true });
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: 'error',
	});  
 
	var ModelGroup = function (){
		var self = this;
		self.modul = 'Group';
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
		
		self.gname = ko.observable('<?php echo isset($data['NAME'])?$data['NAME']:'' ?>')
		.extend({
			required: {params: true, message: 'Nama Group tidak boleh kosong'}
		 });
		self.gdesc = ko.observable('<?php echo isset($data['DESCRIPTION'])?$data['DESCRIPTION']:'' ?>');
		self.opsi = ko.observableArray([{id:1, text:'Lihat'}, {id:2, text:'Cetak'}, {id:3, text:'Semua'}]);
		      
		        
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
		  //data = JSON.parse(ko.toJSON(App));
		  data = $('#frm').serialize();		  
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
			
			/* if (res.isSuccess)
			{
			  refresh('2400');
			} */
			
			$.pnotify({
			  title: res.isSuccess ? 'Sukses' : 'Gagal',
			  text: res.message,
			  type: res.isSuccess ? 'info' : 'error'
			});
		  }
		});
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
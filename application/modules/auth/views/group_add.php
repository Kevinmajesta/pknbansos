<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<style>
	ul
	{
		margin: 0;
	}

	#menu
	{
		/*float: left;*/
		width: 570px;
	}

	#menu li.menu
	{
		list-style: none;
		margin: 0 0 4px 0;
		padding: 3px;
		background-color: #ffffff;
		border: #CCCCCC solid 1px;
		color: #7d7d7d;
		font-size: 16px;
	}

	#menu ul li ul li
	{
		list-style: none;
		margin: 0 0 15px 15px;
		padding: 3px;
		background-color: #ffffff;
		color: #7d7d7d;
		font-size: 16px;
	}
  
	.legend{
		width: 450px;
		font-style:Calibri;
		font-weight:bold;
		font-size:16px;
		padding:3px 47px ;
		border:1 solid #A6C9E2;
		border-radius: 1px 1px 1px 1px;	
		margin-left:6px;
	}
	.legend2{
		width: 100px;
		font-style:Calibri;
		font-weight:bold;
		font-size:14px;
		padding:2px 2px ;
		border:0 solid #A6C9E2;
		border-radius: 2px 2px 2px 2px;
		margin-left:1px;
	}
		
	.fieldset{
		border:0 solid #A6C9E2;
		margin:2px;
		font-family:Calibri;
		font-size:16px;
		text-decoration:none;
		border-radius: 2px 2px 2px 2px;
	}
	
	.fieldset2{
		border:1px solid #A6C9E2;
		margin:2px;
		margin-top:5px;
		margin-bottom:5px;
		font-family:Calibri;
		font-size:1.2em;
		text-decoration:none;
		border-radius: 5px 5px 5px 5px;
	}
	
	.fd_left{
		/*float:left;
		width:280px;*/
	}

	.fd_left div{
		/*padding:1px;*/
	}

	.form>.fieldset>.fd_left div label{
		width:200px;
		float:left;	
		border-bottom:1px solid #eceff3;
		/*font-weight:bold;*/
		font-size:16px;
		margin-right:3px;
		
	}

	.tabs>.fieldset>.fd_left div label{
		width:200px;
		float:left;	
		border-bottom:1px solid #eceff3;
		/*font-weight:bold;*/
		font-size:16px;
		margin-right:3px;
		
	}

	.form>.fieldset>.fd_left div label.error{
		font-weight:normal;
	}
	
	.form>.fieldset>.fd_left div
	{
		margin:3px;
		clear:left;
		float:left;
	}
	
	.fd_kanan{
		clear:none;
		margin-top: 2px;
		margin-left:1em;
		float:right!important;
	}


	.fd_kanan div label{
		width:80px;
		float:right;	
		border-bottom:1px solid #eceff3;
		font-size:16px;
		margin-right:-12px;
	}

	.fd_kanan div label.error{
		font-weight:normal;
	}

	.fd_kanan label.coba
	{
		clear:both;
		float:right;
	}

	.fd_kanan1 div
	{
		margin:3px;
		clear:right;
		float:right;
	}

	.fd_kiri div label{
		width:120px;
		float:left;	
		border-bottom:1px solid #eceff3;
		font-size:16px;
		margin-right:1px;		
	}

	.fd_kiri div label.error{
		font-weight:normal;
	}
	.fd_kiri div
	{
		margin:1px;
		clear:left;
		float:left;
	}

</style>

<h4><?php //echo $caption; ?></h4>
<h4><legend id="bc" data-bind="text: title"></legend></h4>

<form id="frm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>group/group_proses">
	<div class="control-group" data-bind="validationElement: username">
		<label class="control-label" for="inputUsername" >
			Username
		</label>
		<div class="controls">
			<input type="text" id="username" placeholder="Username" data-bind="value: username" readonly="1">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="inputName">
			Nama Operator
		</label>
		<div class="controls">
			<input type="text" id="name" placeholder="Nama Operator" data-bind="value: name" readonly="1">
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="inputAkses">
		  Hak Akses
		</label>
		
			
		
		<div class="controls">
			<div id='menu'>
				<ul data-bind="foreach: Menus" >
					<li class="menu" >
						<fieldset class='fieldset' id='fd_kiri_kanan'>
							<div class='fd_kiri'>
								<div>
									<label class="checkbox inline">
										<input type="checkbox" data-bind="checked: is_checked, value: parent_id" /> 
										<span data-bind="text: title"></span>
									</label>
								</div>
							</div>
							<div class='fd_kanan'>
								<div>
									<select data-bind="options: App.opsi, value: akses, optionsCaption: 'Pilih...', optionsText: 'text', optionsValue: 'id'" disabled="disabled">
						</select>
								</div>
							</div>
						</fieldset>
						<div data-bind="visible: is_checked() == false">
							<ul data-bind="foreach: child" class="legend">
								<li data-bind="visible: child_title !== '---'" class="menu">
									<fieldset class='fieldset' id='fd_kiri_kanan'>
										<div class='fd_kiri'>
											<div>
												<label class="checkbox inline">
													<span data-bind="text: child_title"></span>
												</label>
											</div>
										</div>
										<div class='fd_kanan'>
											<div>
												<select data-bind="options: App.opsi, value: akses, optionsText: 'text', optionsValue: 'id'" disabled="disabled">
												</select>
											</div>
										</div>
									</fieldset>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>			
		</div>
		
	</div>
	<div class="control-group">
		<div class="controls">
			<input type="button" id="save" value="Kembali" class="btn btn-primary" data-bind="click: back" />
		</div>
	</div>
</form>

<script type="text/javascript">
	jQuery(document).ready(function()
    {        
        jQuery("#navigasi").treeview({
			collapsed: false,
			unique: true,
			persist: "location"
        });

    });
	
	ko.validation.init({
		insertMessages: false,
		decorateElement: true,
		errorElementClass: 'error',
	});  
 
	var ModelGroup = function (){
		var self = this;
		self.modul = 'Group';
		self.title = 'Privacy Setting';
		self.akses_level = ko.observable(03);
		self.id = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
		self.username = ko.observable('<?php echo isset($isi['USERNAME'])?$isi['USERNAME']:'' ?>')
				.extend({
					required: {params: true, message: 'Username tidak boleh kosong'}
				});
		self.name = ko.observable('<?php echo isset($isi['NAME'])?$isi['NAME']:'' ?>');
		
		self.mode = ko.computed(function(){
			return self.id() > 0 ? 'edit' : 'new';
		});
		
		self.isEdit = ko.computed(function(){
			return self.mode() === 'edit';
		});

		self.canPrint = ko.computed(function(){
			return self.akses_level() >= 2;
		});

		self.errors = ko.validation.group(self);
		
		self.opsi = ko.observableArray([{id:0, text:'Tidak Ada Akses'},{id:1, text:'Lihat'}, {id:2, text:'Cetak'}, {id:3, text:'Semua'}]);
		self.allSelected = ko.observable(false);
		self.selectAll = function() {
			var all = self.allSelected();

			//check all parent dan akses jadi 3 (semua)
			ko.utils.arrayForEach(Menus, function(Menus) {
			   Menus.is_checked(!all); 
			   Menus.akses('3'); 
			   
			   //check all child dan akses jadi 3 (semua)
			   ko.utils.arrayForEach(Menus.child, function(item)
				 {
					item.is_checked(!all);
					item.akses('3');
				 });
			});
			return true;
		};  
	} 

	var App = new ModelGroup();

	App.isValid = function(){
		var Status = true;

		if (!App.gname()) Status = false;

		return Status;
	}
 
	App.back = function(){
		location.href = root+modul;
	}

	App.init_select = function(element, callback){
		var data = {'text': $(element).attr('data-init')};
		callback(data);
	} 
	
	var Menus = 
	[
		<?php 
		if($group_menus)
		{
			$data = json_decode($group_menus);
			foreach($data as $value => $parent)
			{
			?>
				{
					parent_id : "<?php echo $parent->ID; ?>",
					title : "<?php echo $parent->TITLE; ?>",
					is_checked : ko.observable("<?php if($parent->is_checked == '0') echo false; else echo true; ?>"),
					akses: ko.observable("<?php echo $parent->akses; ?>"),
					child:[
						<?php
						foreach($parent->child as $child) 
						{
						?>
							{
								child_id : "<?php echo $child->ID; ?>",
								child_title : "<?php echo $child->TITLE; ?>",
								<?php 
									if(isset($child->is_checked))
									{
										if($child->is_checked == '0') $cek = false; else $cek = true;
									}
									else $cek = false;
								?>
								is_checked : ko.observable("<?php echo $cek; ?>"),	
								<?php 
									if(isset($child->akses))
									{
										$akses = $child->akses;
									}
									else $akses = '0';
								?>									
								akses: ko.observable("<?php echo $akses; ?>"),
							},
						<?php
						}
						?>
					],												    
				},	
			<?php
			}
		}
		?>
	];
 
	ko.applyBindings(App);	
</script>
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//print_r($data);
?>
<h4><legend id="bc" data-bind="text: title"></legend></h4>

<form id="frm" class="" method="post" action="<?php echo base_url(); ?>group/user_proses">
	<ul class="nav nav-tabs" id="myTab" >
		<li class="control-group active" data-bind="validationElement: tab_account"><a class="control-label" href="#home">Account</a></li>
    <?php if (!isset($data['ID'])) { ?>
    <li class="control-group" data-bind="validationElement: tab_password"><a class="control-label" href="#profile">Password</a></li>
		<?php } ?>
	</ul>
	<div class="tab-content form-horizontal" style="height:430px">
		<div class="tab-pane active" id="home">
			<div class="control-group" data-bind="validationElement: username">
				<label class="control-label" for="inputUsername" >
					Username
				</label>
				<div class="controls">
					<input type="text" id="username" placeholder="Username" data-bind="value: username">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputName">
					Nama Operator
				</label>
				<div class="controls">
					<input type="text" id="name" placeholder="Nama Operator" data-bind="value: name">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputName">
					Icon
				</label>
				<div class="controls">
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-new thumbnail" style="width: 63px; height: 68px;">
							<?php
							if(isset($data['ICON']))
							{

							?>
							<img src="<?php echo base_url().'assets/img/user/'.$data['ICON']; ?>" />
							<?php

							}
							else
							{
							?>
							<img src="http://www.placehold.it/63x68/EFEFEF/AAAAAA" />
							<?php
							}
							?>
						</div>
						<?php
						if(isset($data['ICON']))
						{
						?>
						<span data-bind="click: hapus_icon"><i class="icon-remove" title="hapus icon"></i></span>
						<?php
						}
						?>
						<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px;"></div>
						<span class="btn btn-file">
							<span class="fileupload-new">
								Select image
							</span>
							<span class="fileupload-exists">
								Change
							</span>
							<input type="file" id="image" name="image" data-bind="value: image">
						</span>
						<a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					</div>Ukuran Max 100kb
				</div>
      </div>
				<div class="control-group" data-bind="validationElement: email">
					<label class="control-label" for="inputEmail">
						Email
					</label>
					<div class="controls">
						<input type="email" id="email" placeholder="Email" data-bind="value: email">
					</div>
				</div>
				<div class="control-group" data-bind="validationElement: guser">
					<label class="control-label" for="inputGUser">
						Group User
					</label>
					<div class="controls">
						<select id="guser" data-bind="value: guser">
							<?php 
							if(isset($data['ID']))
							{
								if($groups)			
								{
									echo '<option value="" selected="selected">==Pilih Group==</option>';
									foreach($groups as $groups)
									{
										$selected = ($data['GROUP_ID'] == $groups['ID']) ? 'selected="selected"' : '';

										echo '<option value="'.$groups['ID'].'" '.$selected.'>'.$groups['NAME'].'</option>';
									}
								}
							}
							else
							{
								if($groups)			
								{
									echo '<option value="">==Pilih Group==</option>';
									foreach($groups as $groups)
									{
										//$selected = ($groups['ID'] == '2') ? 'selected="selected"' : '';
										echo '<option value="'.$groups['ID'].'">'.$groups['NAME'].'</option>';
									}
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="control-group" data-bind="validationElement: id_skpd">
					<label class="control-label" for="inputSKPD">
						SKPD
					</label>
					<div class="controls">
						<input type="text" class="span3" id="nm_skpd" readonly="1" data-bind="value: nm_skpd" />
						<span class="add-on" data-bind="click: pilih_skpd" ><i class="icon-folder-open"></i></span>
						<span style="margin-left: -40px;" class="add-on" data-bind="click: hapus_skpd, visible: !id_skpd()==''" ><i class="icon-remove" title="hapus SKPD"></i></span>
					</div>
				</div>
		</div>
		<div class="tab-pane" id="profile">
			<?php
			if(isset($data['ID']))
			{
			}
			else{
			?>
			<div data-bind="visible: mode() === 'new'">
				<div class="control-group" data-bind="validationElement: passwd">
					<label class="control-label" for="inputPassword">
						Password
					</label>
					<div class="controls">
						<input type="password" id="passwd" placeholder="Password" data-bind="value: passwd">
					</div>
				</div>
				<div class="control-group" data-bind="validationElement: repasswd">
					<label class="control-label" for="inputPassword" data-bind="validationElement: repasswd">
						Ulangi Password
					</label>
					<div class="controls">
						<input type="password" id="repasswd" placeholder="Ulangi Password" data-bind="value: repasswd">
					</div>
				</div>
			</div>	
			<?php
			}
			?>
		</div>	
	</div>
		
	<div class="control-group form-horizontal">
		<div class="controls">
			<input type="submit" id="save2" value="Simpan" class="btn btn-primary" data-bind="enable: canSave, click: save" />
			<input type="button" id="save" value="Kembali" class="btn btn-primary" data-bind="click: back" />
			<!--<button type="submit" class="btn">
				Tambah
			</button>-->
		</div>
	</div>
</form>
<!--<pre data-bind="text: ko.toJSON($root, null, 2)"></pre>-->
<script>
	var last;
	var purge = new Array();

	$('#myTab a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	})

	function refresh (timeoutPeriod)
	{
		refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
	}
  
	//ko
	ko.validation.init({
		insertMessages: true,
		decorateElement: true,
		errorElementClass: 'error',
	});
   
	var ModelUser = function (){
		var self = this;
		self.modul = 'User';
		self.akses_level = ko.observable(03);
		self.id = ko.observable('<?php echo isset($data['ID'])?$data['ID']:0 ?>');
		self.username = ko.observable('<?php echo isset($data['USERNAME'])?$data['USERNAME']:'' ?>')
			.extend({
				required: {params: true, message: 'Username tidak boleh kosong'}
			});
		self.email = ko.observable('<?php echo isset($data['EMAIL'])?$data['EMAIL']:'' ?>')
			.extend({
				required: {params: true, message: 'Email tidak boleh kosong'},
				email: {params: true, message: 'Alamat Email tidak valid'}
			});
		self.name = ko.observable('<?php echo isset($data['NAME'])?$data['NAME']:'' ?>');
		self.image = ko.observable('');
		self.url = '<?php echo base_url()."group/upload"; ?>';
		//self.url2 = "http://localhost/bootstrap/group/upload";
		<?php
		if(!isset($data['ID']))
		{
		?>
		self.passwd = ko.observable('')
			.extend({
				required: {params: true, message: 'Password tidak boleh kosong'},
				minLength: {params: 8, message: 'Password minimal 8 karakter'},
			});
		self.repasswd = ko.observable('')
			.extend({
				required: {params: true, message: 'Ulangi Password tidak boleh kosong'},
				equal: {params: self.passwd, message: 'Password harus sama'},
			});
		<?php
			}
		?>
		self.id_skpd = ko.observable(<?php echo isset($data['SKPD_ID'])?$data['SKPD_ID']:'' ?>);
		self.status = ko.observable('<?php echo isset($data['STATUS'])?$data['STATUS']:'1' ?>');
		self.nm_skpd = ko.observable('<?php echo isset($data['NAMA_SKPD'])?$data['NAMA_SKPD']:'' ?>');
		self.guser = ko.observable('<?php echo isset($data['GROUP_ID']) ? $data['GROUP_ID']:'' ?>')
			.extend({
				required: {params: true, message: 'Pilih Group'},
			});
		self.displaypassword = ko.observable(false);
		<?php
			if(isset($data['ID']))
			{
		?>
		/*self.old_passwd = ko.observable('')
			.extend({
				required: {params: true, message: 'Password Lama tidak boleh kosong', onlyIf: self.displaypassword},
					});
		self.passwd = ko.observable('')
			.extend({
				required: {params: true, message: 'Password baru tidak boleh kosong', onlyIf: self.displaypassword}, 
				minLength: {params: 8, message: 'Password minimal 8 karakter'},
					});
		self.repasswd = ko.observable('')
			.extend({
				required: {params: true, message: 'Ulangi Password baru tidak boleh kosong', onlyIf: self.displaypassword},
				equal: {params: self.passwd, message: 'Password harus sama'},
					});		*/
	
		<?php
			}
		?>
    self.tab_account = ko.observable('')
      .extend({
        required: {params: true, message: 'Isian Account belum lengkap'},
      });
    
    self.cek_tab_account = ko.computed(function(){
      if (self.username() !== '' || self.email() !== '' || self.guser() !== '') self.tab_account(1); 
    })
	
    <?php if (!isset($data['ID'])) { // if #1 ?>
    self.tab_password = ko.observable('')
      .extend({
        required: {params: true, message: 'Isian Password belum lengkap'},
      });
    
    self.cek_tab_password = ko.computed(function(){
      if (self.passwd() !== '' || self.repasswd() !== '') self.tab_password(1); 
    })
    <?php } // end if #1?>

		self.mode = ko.computed(function(){
			return self.id() > 0 ? 'edit' : 'new';
		});

		self.title = ko.computed(function(){
			return (self.mode() === 'edit' ? 'Edit ' : 'Entri ') + self.modul;
		});

		self.isEdit = ko.computed(function(){
			return self.mode() === 'edit';
		});

		self.isEnable = ko.computed(function(){
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

	var App = new ModelUser();
  
  /*App.isValid = function(){
    var Status = true;

    //if (!App.id_skpd()) Status = false;

    return Status;
  }*/
 
	App.prev = function(){
		show_prev(modul, App.id());
	}

	App.next = function(){
		show_next(modul, App.id());
	}

	App.print = function(){
		preview({"tipe":"form", "id": App.id()});
	}

	App.back = function(){
		location.href = root+modul;
	}

	App.save = function(){
		<?php
		if(!isset($data['ID']))
		{
		?>
			if(App.passwd() == "")
			{
				$.pnotify(
				{
					title: 'Gagal',
					text: 'Password Masih Kosong',
					type: 'error'
				});
			}
			if(App.passwd().length < 8)
			{
				$.pnotify(
				{
					title: 'Gagal',
					text: 'Password minimal 8 karakter',
					type: 'error'
				});
			}
			if(App.repasswd() == "")
			{
				$.pnotify(
				{
					title: 'Gagal',
					text: 'Ulangi Password Masih Kosong',
					type: 'error'
				});
			}
			
			if(App.repasswd() != App.passwd())
			{
				$.pnotify(
				{
					title: 'Gagal',
					text: 'Password harus sama',
					type: 'error'
				});
			}
		<?php
		}
		?>
		var $frm = $('#frm'),
		data = JSON.parse(ko.toJSON(App));

		if (!App.isValid()) {
			App.errors.showAllMessages();;
			return ;
		}
		/*var fileInput = $("input[name='image']");
		var file = fileInput.get(0).files[0];*/

		var file = document.getElementById('image').files[0];
		var formData = new FormData($('form#frm')[0]);
		//formData.append("image", file);

		//tanpa image
		
		//alert(App.passwd());
		
		
		
		if(App.image() == "")
		{
			$.ajax(
			{
				url: $frm.attr('action'),
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(res, xhr)
				{
					if (res.id) App.id(res.id);

					$.pnotify(
					{
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
				}
			});
		}
		//dengan image
		else
		{
			$.ajax(
			{
				url: App.url,
				type: 'post',
				dataType: 'json',
				//data: data,
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(res)
				{
					$.pnotify(
					{
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
					
					if(res.isSuccess == true)
					{
						$.ajax(
						{
							url: $frm.attr('action'),
							type: 'post',
							dataType: 'json',
							data: $.extend(data,{icon:res.filename}),
							success: function(res, xhr)
							{
								if (res.id) App.id(res.id);

								$.pnotify(
								{
									title: res.isSuccess ? 'Sukses' : 'Gagal',
									text: res.message,
									type: res.isSuccess ? 'info' : 'error'
								});
							}
						});
					}

				}
			});
		}
		setTimeout(function() { window.location = root+modul}, 2000);
	}

	App.pilih_skpd = function(){
		var option = {multi:0};
		Dialog.pilihSKPD(option, function(obj, select){
		var rs = $(obj).jqGrid('getRowData', select[0].id);
		App.id_skpd(rs.id);
		//App.kd_skpd(rs.kode);
		App.nm_skpd(rs.nama);
		});
	}
  
	App.hapus_skpd = function(){
	App.id_skpd('');
	App.nm_skpd('');
	}
  
  App.hapus_icon = function(){
    var agree=confirm("Apakah Anda yakin akan menghapus icon?");
    if(agree)
    {
      $.ajax(
            {
              url: '<?php echo base_url()?>group/icon',
              type: 'post',
              dataType: 'json',
              data: {id:App.id()},
              success: function(res, xhr)
              {
                if (res.id) App.id(res.id);

                $.pnotify(
                  {
                    title: res.isSuccess ? 'Sukses' : 'Gagal',
                    text: res.message,
                    type: res.isSuccess ? 'info' : 'error'
                  });
                  refresh('2400');
              }
            });
    }
    
  }

  App.init_select = function(element, callback){
    var data = {'text': $(element).attr('data-init')};
    callback(data);
  }

  App.query_pejabat_skpd = function(option){
    var id_skpd = App.id_skpd();
    $.ajax({
      url: "<?php echo base_url()?>pilih/pejabat_skpd",
      type: 'POST',
      dataType: 'json',
      data: {
        'q': option.term,
        'skpd': id_skpd,
      },
      success: function (data) {
        option.callback({
            results: data.results
        });
      }
    });
  };
  
  
 
ko.applyBindings(App);

</script>
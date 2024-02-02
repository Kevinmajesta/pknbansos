<fieldset>
  <legend id="bc" data-bind="text: title"></legend>
</fieldset>

<form id="frm" method="post" action="<?php echo base_url().$modul; ?>/proses" class="form-horizontal" >
  <div class="control-group" data-bind="validationElement: nama" >
    <label class="control-label" for="nama">Nama Pemda</label>
    <div class="controls">
      <input type="text" id="nama" class="span4" data-bind="value: nama" required/>
      <div class="pull-right" >
        <input type="button" value="Simpan" class="btn btn-primary" data-bind="click: save" />
      </div>
    </div>
  </div>

  <div class="control-group" data-bind="validationElement: lokasi" >
    <label class="control-label" for="lokasi">Lokasi</label>
    <div class="controls">
      <input type="text" class="span4" id="lokasi" data-bind="value: lokasi" />
    </div>
  </div>
     
	<div class="control-group" >
		<label class="control-label" for="inputName">Logo</label>
		<div class="controls">
			<div class="fileupload fileupload-new" data-provides="fileupload">
				<div class="fileupload-new thumbnail" style="width: 100px; height: 100px;">
					
					<?php
						if(isset($data['LOGO']))
						{
							?>
							<img src="<?php echo base_url().'assets/img/logo-city.jpg'; ?>" />
							<?php
						}
						else
						{
							?>
							<img src="<?php echo base_url().'assets/img/AAAAAA.gif'; ?>" />
							<?php
						}
					?>
				</div>
				
				<div class="fileupload-preview fileupload-exists thumbnail" style="width: 100px; height: 100px;"></div>
				<div>
					<span class="btn btn-default btn-file">
						<span class="fileupload-new">Select image</span>
						<span class="fileupload-exists">Change</span>
						<input type="file" accept="image/jpeg" id="image" name="image" data-bind="value: image">
					</span>
					<a href="#" class="btn btn-default fileupload-exists" data-dismiss="fileupload">Remove</a>
				</div>
				<?php
					if(isset($data['LOGO']))
					{
						?>
						<span data-bind="click: hapus_icon"><i class="icon-remove" title="hapus icon"></i></span> hapus icon
						<?php
					}
				?>
			</div>
		</div>
	</div>
	

</form>

<script>
	$.fn.forceNumeric = function () {
		return this.each(function () {
			$(this).keydown(function (e) {
				var key = e.which || e.keyCode;

				if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
				// numbers   
				key >= 48 && key <= 57 ||
				// Numeric keypad
				key >= 96 && key <= 105 ||
				// comma, period and minus, . on keypad
				key == 190 || key == 109 || key == 110 ||
				// Backspace and Tab and Enter
				key == 8 || key == 9 || key == 13 ||
				// Home and End
				key == 35 || key == 36 ||
				// left and right arrows
				key == 37 || key == 39 ||
				// Del and Ins
				key == 46 || key == 45)
				return true;

				return false;
			});
		});
	}
  
  
  ko.validation.init({
    insertMessages: false,
    decorateElement: true,
    errorElementClass: 'error',
  });
  
	function refresh (timeoutPeriod)
	{
		refresh = setTimeout(function(){window.location.reload(true);},timeoutPeriod);
	}

  var ModelPemda = function (){
    var self = this;
    self.modul_display = '<?php echo $modul_display ?>';
    self.processing = ko.observable(false);
    self.nama = ko.observable(<?php echo isset($data['NAMA_PEMDA']) ? json_encode($data['NAMA_PEMDA']) : "''" ?>)
      .extend({
        required: {params: true, message: 'Nama Pemda belum diisi.'},
        maxLength: {params: 50, message: 'Nama Pemda tidak boleh melebihi 200 karakter'},
      });
    self.lokasi = ko.observable(<?php echo isset($data['LOKASI']) ? json_encode($data['LOKASI']): "''" ?>)
      .extend({
        maxLength: {params: 50, message: 'Lokasi tidak boleh melebihi 50 karakter'},
      });
	
    self.title = ko.computed(function(){
      return self.modul_display;
    });
	
    self.title = ko.computed(function(){
      return self.modul_display;
    });
	
	self.image = ko.observable('');
	self.url = '<?php echo base_url()."pemda/upload"; ?>';

    self.errors = ko.validation.group(self);
  }

  var App = new ModelPemda();
  
	App.hapus_icon = function(){
		var agree=confirm("Apakah Anda yakin akan menghapus icon?");
		if(agree)
		{
			$.ajax({
			  url: '<?php echo base_url()?>pemda/icon',
			  type: 'post',
			  dataType: 'json',
			  data: {nama:App.nama()},
			  success: function(res, xhr)
			  {
				if (res.nama) App.nama(res.nama);

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

	App.formValidation = function(){
		var errmsg = [];
		if (!App.isValid()){
			errmsg.push('Ada kolom yang belum diisi dengan benar. Silakan diperbaiki.');
			App.errors.showAllMessages();
		}

		if (errmsg.length > 0) {
			message = errmsg.join('</br>');
			show_warning(message);
			return false;
		}
		return true;
	}

	App.save = function(){
		if (!App.formValidation()){ return }

		var $frm = $('#frm'),
		data = JSON.parse(ko.toJSON(App));
		var file = document.getElementById('image').files[0];
		var formData = new FormData($('form#frm')[0]);

		App.processing(true);
		if(App.image() == "")
		{
			$.ajax({
				url: $frm.attr('action'),
				type: 'post',
				dataType: 'json',
				data: data,
				success: function(res, xhr)
				{
					if (res.nama) App.nama(res.nama);

					$.pnotify(
					{
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
				}
			});
		}
		else{
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
								if (res.nama) App.nama(res.nama);

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
	}

  ko.applyBindings(App);
</script>
<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//print_r($data);
?>
<h4><?php echo $caption; ?><!--<legend id="bc" data-bind="text: title"></legend>--></h4>

<form id="frm" class="form-horizontal" method="post" action="<?php echo base_url(); ?>group/menu_proses">
  <div class="control-group" data-bind="validationElement: n_menu">
    <label class="control-label" for="inputUsername" >
      Nama Menu
    </label>
    <div class="controls">
      <input type="text" id="n_menu" placeholder="nama menu" data-bind="value: n_menu">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputName">
      Link
    </label>
    <div class="controls">
      <input type="text" id="links" placeholder="Link" data-bind="value: links" >
    </div>
  </div>
  
  <div data-bind="visible: mode() === 'edit'">
  <div class="control-group" data-bind="validationElement: parent">
    <label class="control-label" for="inputGUser">
      Parent Menu
    </label>
    <div class="controls">
	  <select id="parent" data-bind="value: parent">
	  <?php 
	  
	  	if(isset($data['ID']))
		{
			if($parent)			
			{
				echo '<option value="" selected="selected">==Pilih Group==</option>';
				foreach($parent as $parent)
				{
          			
					if($data['PARENT_ID'] == $parent['ID']) $selected =  'selected="selected"';
					else $selected = '';
					
          			echo '<option value="'.$parent['ID'].'" '.$selected.'>'.$parent['TITLE'].'</option>';
					
				}
			}
		}
		/*else
		{
			if($parent)			
			{
				echo '<option value="">==Pilih Group==</option>';
				foreach($parent as $parent)
				{
					//$selected = ($groups['ID'] == '2') ? 'selected="selected"' : '';
					echo '<option value="'.$parent['ID'].'">'.$parent['TITLE'].'</option>';
				}
			}
		}*/
	  ?>
	  </select>
    </div>
  </div>
  </div>
  
  <div class="control-group" data-bind="validationElement: aktif">
    <label class="control-label" for="inputSKPD">
      Status
    </label>
    <div class="controls">
	
      <input type="radio" data-bind="checked: aktif" value="1" />(aktif)
      <input type="radio" data-bind="checked: aktif" value="0" />(tidak aktif)
      </label>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
	<input type="submit" id="save" value="Simpan" class="btn btn-primary" data-bind="enable: canSave, click: save" />
	 <input type="button" id="save" value="Kembali" class="btn btn-primary" data-bind="click: back" />
      <!--<button type="submit" class="btn">
        Tambah
      </button>-->
    </div>
  </div>
</form>
<pre data-bind="text: ko.toJSON($root, null, 2)"></pre>
<script>
  var last;
  var purge = new Array();
  
  
  //ko
  ko.validation.init({
    insertMessages: true,
    decorateElement: true,
    errorElementClass: 'error',
  });
  
 
  var ModelUser = function (){
    var self = this;
    self.modul = 'Group';
    //self.akses_level = ko.observable(03);
    self.akses_level = ko.observable(<?php echo isset($akses) ? $akses : 0 ?>);
    self.id = ko.observable('<?php echo isset($data['ID'])?$data['ID']:'' ?>');
    self.idn = ko.observable('<?php echo isset($idn)?$idn:'' ?>')
    self.n_menu = ko.observable('<?php echo isset($data['TITLE'])?$data['TITLE']:'' ?>')
      .extend({
        required: {params: true, message: 'Nama Menu tidak boleh kosong'}
      });
	  self.links = ko.observable('<?php echo isset($data['LINK'])?$data['LINK']:'' ?>');
    self.parent = ko.observable('<?php echo isset($data['PARENT_ID'])?$data['PARENT_ID']:'' ?>');
	
    self.aktif = ko.observable('<?php echo isset($data['AKTIF']) ? $data['AKTIF']:'1' ?>');
	
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
    location.href = root+'group/menu';
  }

  App.save = function(){
    var $frm = $('#frm'),
      data = JSON.parse(ko.toJSON(App));

    if (!App.isValid()) {
      App.errors.showAllMessages();;
      return ;
    }

    $.ajax({
      url: $frm.attr('action'),
      type: 'post',
      dataType: 'json',
      data: data,
      success: function(res, xhr){
        if (res.id) App.id(res.id);

        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
      }
    });

  }

 
ko.applyBindings(App);

</script>
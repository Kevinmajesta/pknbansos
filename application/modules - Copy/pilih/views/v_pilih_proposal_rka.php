<div class="controls-row">
    <div class="control-group pull-left" style="margin-right:10px;">
      <label class="control-label" for="bantuan">Jenis Bantuan</label>
      <select id="bantuan" class="span2">
        <?php foreach ($jenis_bantuan as $key=>$val) echo '<option value="'.$val['JENIS_BANTUAN'].'">'.$val['JENIS_BANTUAN'].'</option>';?>
      </select>
    </div>
    <div class="control-group pull-left">
      <label class="control-label" for="kategori">Kategori</label>
      <select id="kategori" class="span3">
      </select>
    </div>
    <div class="input-append pull-right">
      <input type="text" class="span4" id="str<?php echo $dialogname;?>" />
      <span class="add-on" id="bfilter<?php echo $dialogname;?>"><i class="icon-search"></i></span>
    </div>
</div>

<table id="grdDialog<?php echo $dialogname;?>"></table>
<div id="pgrDialog<?php echo $dialogname;?>"></div>

<script>
$(document).ready(function(){
  function set_kategori(bantuan)
  {
    $.ajax({
      url: root+'proposal'+'/get_kategori/',
      type: 'post',
      dataType: 'json',
      data: {bantuan : bantuan},
      success: function(res, xhr){
        if (res != null) {
          var option = '';
          for (var i=0; i<res.length; i++)
          {
            option += '<option value="'+res[i]+'">'+res[i]+'</option>';
          }
          $('#kategori').html(option);
        }
      }
    });
  }
  
  function set_grid(bantuan, kategori)
  {    
    var $grid = $('#grdDialog<?php echo $dialogname;?>');
    var postdata = $grid.jqGrid('getGridParam', 'postData');
    $.extend(postdata,{bantuan: bantuan, kategori: kategori});
    $grid.jqGrid('setGridParam', {postData: postdata});
    $grid.trigger("reloadGrid",[{page:1}]);
  }

  $('#bantuan').change(function(){
    var bantuan = $(this).val();
    set_kategori(bantuan);
    
    setTimeout(function(){
      var kategori = $('#kategori').val();
      set_grid(bantuan, kategori);
    }, 500);
  });
  
  $('#kategori').change(function(){
    var bantuan = $('#bantuan').val(),
        kategori = $(this).val();
    set_grid(bantuan, kategori);
  });
  
  setTimeout(function(){
    var bantuan = $('#bantuan').val();
    set_kategori(bantuan);
  }, 500);
  
  setTimeout(function(){
    var bantuan = $('#bantuan').val(),
        kategori = $('#kategori').val();
    set_grid(bantuan, kategori);
  }, 1000);
});
</script>
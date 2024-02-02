<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
  <select name="field" id="field" class="span2">
        <option value='NAMA_PEJABAT'>Nama Pejabat</option>
				<option value='NIP'>NIP</option>
        <option value='JABATAN'>Jabatan</option>
  </select>
      <select name='oper' id='oper' class="span2">
        <option value="cn">Memuat</option>
        <option value="bw">Diawali</option>
      </select>
      <input type="text" name="string" id="string" class="span7">
      <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
</fieldset>

<table id="grid"></table>
<div id="pager"></div>
	<input type='hidden' size="10" name='SYS_ADMIN_LOGIN' id='SYS_ADMIN_LOGIN' value='<?php echo isset($user_data["SYS_ADMIN_LOGIN"]) ? $user_data["SYS_ADMIN_LOGIN"] : ""; ?>' }>
<script>
$(document).ready(function() {
var last;
$('#string').focus();

  $("#grid").jqGrid({
    url:root+modul+'/get_daftar',
    datatype:'json',
    mtype:'POST',
    colNames:['ID_TIM_ANGGARAN','JABATAN','NAMA','NIP'],
    colModel:[{name:'ID_TIM_ANGGARAN',index:'ID_PEJABAT',width:5,search:false,hidden:true},
			{name:'JABATAN',index:'JABATAN',width:200,sortable: true},
			{name:'NAMA_PEJABAT', index:'NAMA_PEJABAT',width:300,sortable: true},
			{name:'NIP',index:'NIP',width:200,sortable: true}
		],
    pager:'#pager',
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    autowidth:true,
    sortorder:'asc',
    height:250
  });

  $("#grid").jqGrid('navGrid', '#pager', {
  <?php
    if($akses=='3'){
    echo "
    add:true,
    addtext: 'Tambah',
    addfunc:add_row,
    edit:false,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
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
    refresh:true,
    refreshtext:'Refresh',
  },{},{},{},{});

  	 
  function add_row(){
    var option = {},
      i = 0,
      rs = [];

    Dialog.pilihPejabatDaerah(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        save_row(rs.id, rs.idp,rs.jabat, rs.nama, rs.nip);
      }      
    });

  }
 
  

  function save_row(id, idp, jabat, nama, nip)
  {
    $.ajax({
      type: "post",
      dataType: "json",
      url: root+modul+"/proses",
      data: {id:id, idp:idp, jabat:jabat, nama:nama, nip:nip},
      success: function(res) {
        $("#grid").trigger("reloadGrid");
        $.pnotify({
          title: res.isSuccess ? 'Sukses' : 'Gagal',
          text: res.message,
          type: res.isSuccess ? 'info' : 'error'
        });
      },
    });
  }

  function del_row(id){
    if(id)
    {
      var rs = $(this).jqGrid('getRowData', id),
        answer = confirm('Hapus Pejabat' + rs.nama+ '?');
      if(answer == true)
      {
        $.ajax({
          type: "post",
          dataType: "json",
          url: root+modul+"/proses",
          data: {id:id, kd:rs.nama, oper:'del'},
          success: function(res) {
            $('#grid').jqGrid('delRowData', id);
            $.pnotify({
              title: res.isSuccess ? 'Sukses' : 'Gagal',
              text: res.message,
              type: res.isSuccess ? 'info' : 'error'
            });
                      $("#grid").trigger("reloadGrid");
          },

        });
      }
    }
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

});
</script>
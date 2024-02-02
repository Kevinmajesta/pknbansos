<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
</fieldset>

<table id="grid"></table>
<div id="pager"></div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
  
  
  $("#grid").jqGrid({
    url:root+modul+'/get_daftar',
    datatype:'json',
    mtype:'POST',
    colNames:['No. Kontrak', 'Tgl. Kontrak', 'No. BAP', 'Tgl. BAP', 'Nilai Kontrak', 'Nama Perusahaan', 'Nama Pimpinan','Nama Bank'],
    colModel:[
        {name:'nok',index:'nok', width:80,sortable: true},
        {name:'tglk', index:'tglk', width:80, formatter:'date', align:'center',sortable: true},
        {name:'nobap', index:'nobap', width:80,sortable: true},
        {name:'tglbap', index:'tglbap',  width:80, formatter:'date', align:'center',sortable: true},
        {name:'nilaik', index: 'nilaik', width:150, formatter:'currency', align:'right',sortable: true},
        {name:'nmperusahaan', index:'nmperusahaan', width:200,sortable: true},
        {name:'nmpimpinan', index:'nmpimpinan', width:200},
		{name:'nmbank', index:'nmbank', width:200}
    ],
    pager:'#pager',
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    width:935,
    height:250,
    ondblClickRow:edit_row
  });

  $("#grid").jqGrid('bindKeys', {
    'onEnter':edit_row
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    <?php
    if($akses=='3'){
    echo "
    add:true,
    addtext: 'Tambah',
    addfunc:function(){
      location.href = root+modul+'/form';
    },
    edit:true,
    edittext: 'Ubah',
    editfunc:edit_row,
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
    refresh:false
  },{},{},{},{})
  <?php
    if($akses !='1'){
    ?>
  .navSeparatorAdd('#pager')
  .navButtonAdd('#pager',{
    caption:'Refresh',
    onClickButton: function(){
    var grid = $("#grid");
    var postdata = grid.jqGrid('getGridParam','postData');
     $.extend (postdata,
               {filters:'',
              searchField: '',
              searchOper: '',
              searchString: ''});
      grid.jqGrid('setGridParam', { search: false, postData: postdata });
      $('#grid').setGridParam({ page: 1, datatype: "json" }).trigger('reloadGrid');
    },
    title:'Refresh',
    buttonicon:'ui-icon-refresh',
    position:'last'
  });
   ;
  <?php
  }
  ?> 
		

  function edit_row(id){
    location.href = root+modul+'/form/'+ id;;
  }

  function del_row(id){
    var rs = {},
      answer = false,
      rek = '',
      len = id.length;

    rs = $(this).jqGrid('getRowData', id);
    rek = rs.nok;
    answer = confirm('Hapus kontrak ' + rek + ' dari daftar?');

    if(answer == true){
      $.ajax({
      type: "post",
      dataType: "json",
      url: root+modul+'/hapus',
      data: {id: id},
        success: function(res) {
          if (res.isSuccess) show_info(res.message, 'Sukses');
          else show_error(res.message, 'Gagal');

          if (true == res.isSuccess){
            jQuery('#grid').jqGrid('delRowData', id);
          };
        },
      });
    }
  }
  

});
</script>
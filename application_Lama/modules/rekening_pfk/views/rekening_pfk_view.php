<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
      <select name="field" id="field" class="span2">
        <option value="nmrek">Nama Rekening</option>
        <option value="kdrek">Kode Rekening</option>
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

<script>
$(document).ready(function() {

  $("#grid").jqGrid({
    url:root+modul+'/get_daftar',
    datatype:'json',
    mtype:'POST',
    colNames:['', 'Kode Rekening', 'Nama Rekening'],
    colModel:[
      {name:'idrek', hidden:true},
      {name:'kdrek', width:150},
      {name:'nmrek', width:550}
    ],
    pager:'#pager',
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    shrinkToFit:false,
    autowidth:true,
    height:250
  });

  $("#grid").jqGrid('navGrid', '#pager', {
    add:true,
    addtext: 'Tambah',
    addfunc:add_row,
    edit:false,
    del:true,
    deltext: 'Hapus',
    delfunc:del_row,
    search:false,
    refresh:true,
    refreshtext:'Refresh',
  },{},{},{},{})
  .navSeparatorAdd('#pager')
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list("pdf"); },
    title:'Cetak Daftar (PDF)',
    buttonicon:'ui-icon-pdf',
    position:'last'
  })
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list("xls"); },
    title:'Cetak Daftar (XLS)',
    buttonicon:'ui-icon-xls',
    position:'last'
  });

  function print_list(doc){
    var $grid = $('#grid');
    var postdata = $grid.jqGrid('getGridParam', 'postData');
   
    preview({"tipe":"daftar", "format":doc, 'search': postdata._search, 'searchField': postdata.searchField, 'searchOper': postdata.searchOper, 'searchString': postdata.searchString});
  }

  function add_row(){
    var $list = $(this),
      option = {multi:1, tree:1, lvl:5},
      i = 0,
      rs = [];

    Dialog.pilihRekening(option, function(obj, select){
      for (i = 0; i < select.length; i++){
        var rs = $(obj).jqGrid('getRowData', select[i].id);
        save_row(rs.idrek, rs.kdrek);
      }      
    });

  }

  function save_row(id, kd)
  {
    $.ajax({
      type: "post",
      dataType: "json",
      url: root+modul+"/proses",
      data: {id:id, kd:kd},
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
        answer = confirm('Hapus rekening ' + rs.kdrek + '?');
      if(answer == true)
      {
        $.ajax({
          type: "post",
          dataType: "json",
          url: root+modul+"/proses",
          data: {id:id, kd:rs.kdrek, oper:'del'},
          success: function(res) {
            $('#grid').jqGrid('delRowData', id);
            $.pnotify({
              title: res.isSuccess ? 'Sukses' : 'Gagal',
              text: res.message,
              type: res.isSuccess ? 'info' : 'error'
            });
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
    
    if (string !== '') {
      var postdata = grid.jqGrid('getGridParam','postData');
      jQuery.extend (postdata,
               {filters:'',
              searchField: field,
              searchOper: oper,
              searchString: string});
      grid.jqGrid('setGridParam', { search: true, postData: postdata });
    }
    grid.trigger("reloadGrid",[{page:1}]);
  });
  
  jQuery('#string').keypress(function (e) {
    if (e.which == 13) {
      jQuery('#filter').click();
    }
  }); 
    
});
</script>
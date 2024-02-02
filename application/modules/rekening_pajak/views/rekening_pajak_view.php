<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
 <select name="field" id="field" class="span2">
    <option value='nmpajak'>Nama Pajak</option>
    <option value='kdpajak'>Kode Pajak</option>
		<option value='nmrek'>Nama Rekening</option>
		<option value='persen'>Persen</option>
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
var last, newid;

  $("#grid").jqGrid({
    url:root+modul+'/get_daftar',
    editurl:root+modul+'/proses',
    datatype:'json',
    mtype:'POST',
    colNames:['','','Kode Pajak', 'Nama Pajak', 'Nama Rekening', 'Persen'],
    colModel:[
      {name:'id', hidden:true, key: true},
      {name:'idrek', width:80, editable:true, hidden:true},
      {name:'kdpajak', width:80, editable:true, edittype:'text', editrules:{required:true}, editoptions: { title: 'Kode Pajak'}},
      {name:'nmpajak', width:270, editable:true, edittype:'text', editrules:{required:true}, editoptions: { title: 'Nama Pajak'}},
      {name:'nmrek', width:450},
      {name:'persen', width:70, editable:true, edittype:'text', formatter:'currency', align:'right', editrules:{required:true}, editoptions: { title: 'Persen'}}
    ],
    pager:'#pager',
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    viewrecords:true,
    gridview:true,
    onSelectRow: function(id){
      if(id && id!==last){
         $(this).restoreRow(last);
         last=id;
      }
    },
    loadonce:true,
    shrinkToFit:false,
    autowidth:true,
    height:250,
    <?php
    if($akses=='3'){
    echo "
    ondblClickRow:edit_row
    ";}?>
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
    addfunc:add_row,
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
  })
   <?php
    if($akses !='1'){
    ?>
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('pdf') },
    title:'Cetak Daftar (PDF)',
    buttonicon:'ui-icon-pdf',
    position:'last'
  })
  .navButtonAdd('#pager',{
    caption:'',
    onClickButton: function(){ print_list('xls') },
    title:'Cetak Daftar (XLS)',
    buttonicon:'ui-icon-xls',
    position:'last'
  });
   ;
  <?php
  }
  ?> 
  
  function print_list(doc){
			var grid = jQuery("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');

     preview({"tipe":"daftar", "format":doc, "search":postdata['_search'], "searchField":postdata['searchField'], "searchOper":postdata['searchOper'], "searchString":postdata['searchString']});
    }
  

  function onSuccess(resp){
    var o = $.parseJSON(resp.responseText);
    if (o.isSuccess){
      $.pnotify({
        text: o.message,
        type: 'success'
      });
      return true;
    }
  }

  function onError(id, resp){
    var o = $.parseJSON(resp.responseText);
    $.pnotify({
        text: o.message ? o.message : 'Server tidak bisa diakses',
        type: resp.status = 200 ? 'info' : 'error'
      });
    return true;
  }

  function onAfterSave(id, resp){
    var o = $.parseJSON(resp.responseText),
        newid = o.id,
        $t = $(this)[0],
        ind = $(this).jqGrid("getInd", id,true);

    $(ind).attr("id", newid);
    if ($t.p.selrow === id) {
      $t.p.selrow = newid;
    }
    if ($.isArray($t.p.selarrrow)) {
      var i = $.inArray(id, $t.p.selarrrow);
      if (i>=0) {
        $t.p.selarrrow[i] = newid;
      }
    }
    if ($t.p.multiselect) {
      var newCboxId = "jqg_" + $t.p.id + "_" + newid;
      $("input.cbox",ind)
        .attr("id", newCboxId)
        .attr("name", newCboxId);
    }

    $(this).jqGrid('setRowData', newid, {'id':newid});
    last = id;
  }

  function onAfterDelComplete(resp, data){
    var o = $.parseJSON(resp.responseText);
    if (o.isSuccess){
      $.pnotify({
        text: o.message,
        type: 'success'
      });
      return true;
    }
  }

  function edit_row(id){
    last = id;
    $('#grid').jqGrid('restoreRow', last);
    editparameters = {
        "keys" : true,
        "successfunc" : onSuccess,
        'errorfunc':onError,
        "url" : null,
        "extraparam" : {},
        "aftersavefunc" : null,
        "afterrestorefunc" : null,
        "restoreAfterError" : true,
        "mtype" : "POST"
    }
    $('#grid').jqGrid('editRow', id, editparameters);
 }

  function add_row(){
    var $list = $(this),
          option = {multi:0, tree:1, lvl:5},
          i = 0,
          rs = [];
    newid--;

    Dialog.pilihRekening(option, function(obj, select){
      var rs = $(obj).jqGrid('getRowData', select[0].id);
      addRowSorted($list, {'id':'id','sortName':['idrek']}, {'id':newid, 'idrek':rs.idrek, 'nmrek':rs.nmrek});
      editparameters = {
        'keys':true,
          'successfunc':onSuccess,
          'errorfunc':onError,
          'aftersavefunc':onAfterSave,
          'restoreAfterError' : false
      }
      $list.jqGrid('editRow', newid, editparameters);
    });
  }

  function save_row(id){
    if(id && id != last){
      $(this).jqGrid('saveRow', id, {'successfunc':onSuccess, 'aftersavefunc':onAfterSave, 'restoreAfterError':false, 'reloadAfterSubmit':true });
      last = null;
    }
  }

  function del_row(id){
    var rs = {},
        answer = false,
        rek = '',
        len = id.length;

    rs = $(this).jqGrid('getRowData', id);
    rek = rs.kdpajak;
    answer = confirm('Hapus rekening ' + rek + ' dari daftar?');

    if(answer == true){
      $.ajax({
        type: "post",
        dataType: "json",
        url: root+modul+'/hapus',
        data: {id: id},
        success: function(res) {
          $.pnotify({
            title: res.isSuccess ? 'Sukses' : 'Gagal',
            text: res.message,
            type: res.isSuccess ? 'info' : 'error'
          });
          if (true == res.isSuccess){
            jQuery('#grid').jqGrid('delRowData', id);
          };
        },
      });
    }
  }
  
  $('#filter').click(function(){
      var field   = $("#field").val();
      var oper   = $("#oper").val();
      var string   = $("#string").val();

      var grid = $("#grid");
      var postdata = grid.jqGrid('getGridParam','postData');
      $.extend (postdata,
               {filters:'',
              searchField: field,
              searchOper: oper,
              searchString: string});
      grid.jqGrid('setGridParam', { search: true, postData: postdata });
      //grid.trigger("reloadGrid",[{page:1}]);
      grid.setGridParam({ page: 1, datatype: "json" }).trigger('reloadGrid');
      
         
    });

    $('#string').keypress(function (e) {
      if (e.which == 13) {
        $('#filter').click();
      }
    });

});
</script>
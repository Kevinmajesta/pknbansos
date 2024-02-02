<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
</fieldset>

<div class="row">
  <div class="span8 pull-left">
    <div id="filter" class="form-inline"></div>
    <div id="apply" style="margin-bottom:10px;"></div>
  </div>
  <div class="input-append pull-right">
    <input type="text" class="span4" id="q" />
    <span class="add-on"><i class="icon-search"></i></span>
    <span class="add-on" id="searchAdvance"><i class="icon-play"></i></span><!---- search advance  --->
  </div>
</div>
<table id="grid"></table>
<div id="pager"></div>

<table id="grid"></table>
<div id="pager"></div>

<script>
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();

  $("#grid").jqGrid({
    url:root+modul+'<?php echo $link_daftar;?>',
    datatype:'json',
    mtype:'POST',
    colNames:['Nomor Rekomendasi', 'Tanggal Rekomendasi', 'Nomor Uji', 'Nomor Proposal', 'Nama Pemohon', 'Alamat Pemohon', 'Nominal Pengajuan', 'Status', 'Catatan'],
    colModel:[
        {name:'no_disposisi', width:120},
        {name:'tgl', width:150, align:'center', formatter:'date'},
        {name:'no_uji', width:100},
        {name:'no_proposal', width:100},
        {name:'nama_pmh', width:150},
        {name:'alamat_pmh', width:200},
        {name:'nom_aju', width:150, formatter:'currency', align:'right'},
        {name:'keputusan', width:100, sortable:false},
        {name:'catatan', width:100, sortable:false, hidden:true},
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
    sortname:'no',
    sortorder:'asc',
    ondblClickRow:edit_row,
    loadComplete:title_value,
		footerrow:true,
		gridComplete:function(){
      setTimeout(function(){ SumNominalFooter(); }, 500);
    }		
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
    refresh:true,
    refreshtext:'Refresh',
    beforeRefresh: function(){ 
      $('#q').val('');
      var q = $('#q').val();
			var $grid = $('#grid');
      var mlen = $('#field').length;
      
      if (mlen === 1) { 
        var lenopt = $('#field').find('option:disabled').length;
        for (var i=0; i<lenopt; i++) {
          var field = $('#field').find('option:disabled').eq(i).val();
          $('#cek_'+field).attr('checked', false);
          $('#flt_'+field).remove();
          $('#key_'+field).remove();
        }
      }
            
      var postdata = $grid.jqGrid('getGridParam', 'postData');
      delete postdata.filters;
      delete postdata.m;
      delete postdata.q;
      delete postdata.multipleSearch;
      delete postdata.searchDataValue;
      $grid.jqGrid('setGridParam', {search: true, postData: postdata});
			$grid.trigger("reloadGrid",[{page:1}]);
    }
  },{},{},{},{})
  <?php
    if($akses !='1'){
    ?>
  .navSeparatorAdd('#pager')
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

  function title_value()
  {
    var $grid = $('#grid');
    var ids = $grid.jqGrid('getDataIDs');
    for (var i=0;i<ids.length;i++) {
        var id=ids[i];
        var rowData = $grid.jqGrid('getRowData',id);
        var catatan = rowData['catatan'];
        $grid.setCell(id,'keputusan','','',{'title':catatan});
    }
  }

  function edit_row(id){
    location.href = root+modul+'/form/'+id;
  }

  function del_row(id){
    var answer = confirm('Hapus dari daftar?');
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

  function print_list(doc){
    var $grid = $('#grid');
    var postdata = $grid.jqGrid('getGridParam', 'postData');

    preview({"tipe":"daftar", "format":doc, "m":postdata['m'], "q":postdata['q']});
  }

  $('#q').keypress(function (e) {
    if (e.which == 13) {
      var q = $('#q').val();
			var $grid = $('#grid');
      
      var postdata = $grid.jqGrid('getGridParam', 'postData');
      $.extend(postdata,{filters: '', m: 's', q: q});
      $grid.jqGrid('setGridParam', {search: true, postData: postdata});
			$grid.trigger("reloadGrid",[{page:1}]);
    }
  });
	
	function SumNominalFooter()
  {		
		var SumNomAju = $("#grid").jqGrid('getCol', 'nom_aju', false, 'sum');
    $("#grid").jqGrid('footerData','set',{akhir:'',nom_aju:SumNomAju});
  }

  // ----- search advance ---- >>
  var fields = <?php echo json_encode($fields); ?>;
  DialogSearch.init(fields);
});

</script>
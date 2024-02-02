  <fieldset>
    <legend>Daftar Rekening Bendahara</legend>
    <select name="field" id="field" class="span2">
      <option value='namasumber'>Nama Rekening Bendahara</option>
      <option value='namabank'>Nama Bank</option>
      <option value='norekening'>No Rekening</option>
      <option value='koderekening'>Kode Rekening</option>
      <option value='namarekening'>Nama Rekening</option>
    </select>
    <select name='oper' id='oper' class="span2">
      <option value='cn'>Memuat</option>
      <option value='bw'>Diawali</option>
    </select>
    <input type="text" name="string" id="string" class="span7">
    <a class="btn btn-primary" href="#" id="filter"><i class="icon-search icon-white"></i> Filter</a>
  </fieldset>

  <table id="grid"></table>
  <div id="pager"></div>

  <script type="text/javascript">
  $(document).ready(function() {
    var last;
    var modul= 'rekening_bendahara';
    var idrekening;
    var idskpd;
    var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;

    $("#grid").jqGrid({
      url:'<?php echo base_url()?>rekening_bendahara/get_daftar',
      editurl:'<?php echo base_url()?>rekening_bendahara/proses_form',
      datatype:'json',
      mtype:'POST',
      colNames:['','','Kode SKPD','Nama SKPD','Nama Rekening Bendahara', 'Kode Rekening', 'Nama Rekening', 'Nama Bank', 'Rekening Bank'],
      colModel:[
        {name:'idrekening', hidden:true, editable:true},
        {name:'idskpd', hidden:true,editable:true},
        {name:'kdskpd', width:105, editable:true, edittype:'button', editoptions: {value:'pilih skpd', class:'btn btn-primary', dataEvents: [{type: 'click', fn: addSKPD}]}},
        {name:'nmskpd', width:180, editable:true, edittype:'text', editoptions:{size:50, readonly:'readonly'}, editrules:{required:true}},
        {name:'namasumber', width:200, editable:true, edittype:'text', editoptions:{size:50}, editrules:{required:true}},
        {name:'koderekening', width:130, editable:true, edittype:'button', editoptions: {value:'pilih rekening', class:'btn btn-primary', dataEvents: [{type: 'click', fn: addrekening}]}},
        {name:'namarekening', width:200, editable:true, edittype:'text', editoptions:{size:50, readonly:'readonly'}, editrules:{required:true}},
        {name:'namabank', width:200, editable:true, edittype:'text', editoptions:{size:50}, editrules:{required:true}},
        {name:'norekening', width:200, editable:true, edittype:'text', editoptions:{size:20}, editrules:{required:true}}
      ],
      rowNum:10,
      rowList:[10,20,30],
      rownumbers:true,
      pager:'#pager',
      sortorder:'asc',
      viewrecords:true,
      gridview:true,
      shrinkToFit:false,
      width:930,
      height:250,
      ondblClickRow: edit_row,
      onSelectRow:restore_row
    });

    $("#grid").jqGrid( 'navGrid', '#pager', {
    <?php
    if($akses=='3'){
    echo "
      add: true,
      addtext: 'Tambah',
      addfunc: append_row,
      edit: true,
      edittext: 'Ubah',
      editfunc: edit_row,
      del: true,
      deltext: 'Hapus',
      delfunc: del_row,
      search: false,
      searchtext: 'Cari',
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
      refresh: true,
      refreshtext: 'Refresh'
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
			
    function print_list(doc){
			var grid = jQuery("#grid");
			var postdata = grid.jqGrid('getGridParam','postData');

     preview({"tipe":"daftar", "format":doc, "search":postdata['_search'], "searchField":postdata['searchField'], "searchOper":postdata['searchOper'], "searchString":postdata['searchString']});
    }

    
    
      
    
    function append_row(){
      if(data_dasar=='3'){
        var id = $("#grid").jqGrid('getGridParam', 'selrow');
      /* ambil id_skpd */
      /*if (App.id_skpd() == 0) {
        getSKPD();
        return;
      }
      else 
      */  
      <?php if ($id_skpd)
        echo "param = {idskpd: $id_skpd, kdskpd: '$kode_skpd', nmskpd: '$nama_skpd'};";
      else 
        echo "param = {}";
      ?>

        if(id)
        {
          jml = $("#grid").jqGrid('getDataIDs');
          var hasil = "";
          for(var u=0;u<jml.length;u++)
          {
            if(jml[u] == "new"){
              hasil = hasil + jml[u];
            }
          }

          var ada = hasil.search('new');
          if(ada != -1){
            alert('Input Rekening Bendahara belum tersimpan..!!');
          }
          else{
            $('#grid').jqGrid('restoreRow', last);
            $("#grid").jqGrid('addRowData', "new",param, 'after', id);
            $('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
            $('#grid').jqGrid('setSelection', "new", false);
          }
        }
        else
        {
          jml = $("#grid").jqGrid('getDataIDs');
          pos = jml.length - 1;
          if(jml[pos] == "new"){
            alert('Input Rekening Bendahara belum tersimpan..!!');
          }
          else{
            $('#grid').jqGrid('restoreRow', last);
            $("#grid").jqGrid('addRowData', "new",param);
            $('#grid').jqGrid('editRow', "new", true, null, null, null,null, aftersavefunc, errorfunc, null);
            $('#grid').jqGrid('setSelection', "new", false);
          }
        }
        last = null;
      }else{
        alert('Tidak bisa tambah data');
      }
    }

    function edit_row(id){
      if(data_dasar=='3'){
        if(id !== last)
        {
          $('#grid').jqGrid('restoreRow', last);
          last = id;
          $('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
        }
      }else{
        alert('Tidak bisa ubah data');
      }
    }

    function del_row(id){
      if(data_dasar=='3'){
        var rt = $("#grid").jqGrid('getRowData', id);
        var answer = confirm('Hapus dari daftar?');
        if(answer == true)
        {
          $.ajax({
            url: '<?php echo base_url()?>rekening_bendahara/hapus',
            data: { id: id},
            success: function(response){
              var msg = $.parseJSON(response);
              $.pnotify({
                title: msg.isSuccess ? 'Sukses' : 'Gagal',
                text: msg.message,
                type: msg.isSuccess ? 'info' : 'error'
              });
              if(msg.isSuccess == true) {
                $("#grid").jqGrid('delRowData', id);
              }
              $('#grid').trigger('reloadGrid');
            },
            type: "post",
            dataType: "html"
          });
        }

      }else{
        alert('Tidak bisa hapus data');
      }
    }

    function restore_row(id){
      if(id && id !== last){
        $('#grid').jqGrid('saveRow', last, null, null, {idrekening:idrekening,idskpd:idskpd}, aftersavefunc, errorfunc);
      }
    }

    function aftersavefunc(id, resp){
      console.log('aftersavefunc');
      var msg = $.parseJSON(resp.responseText);
      $.pnotify({
        title: msg.isSuccess ? 'Sukses' : 'Gagal',
        text: msg.message,
        type: msg.isSuccess ? 'info' : 'error'
      });
      if(msg.id &&  msg.id != id)
        $("#"+id).attr("id", msg.id);
      $('#grid').trigger("reloadGrid");
      last = null;
    }

    function errorfunc(id, resp){
      var msg = $.parseJSON(resp.responseText);
      if(msg.error)
        $.pnotify({
          title: 'Gagal',
          text: msg.error,
          type: 'error'
        });
      $('#grid').trigger("reloadGrid");
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
      grid.trigger("reloadGrid",[{page:1}]);
    });

    $('#string').keypress(function (e) {
      if (e.which == 13) {
        $('#filter').click();
      }
    });

    function addrekening(e)
    {
      var option = {multi:0, mode:'kas', tree:1};
      Dialog.pilihRekening(option, function(obj, select){
        if (select.length === 0) return false;

        var rs = $(obj).jqGrid('getRowData', select[0].id);

        var ids = $("#grid").jqGrid('getGridParam','selrow');
        $('#'+ids+'_koderekening').val(rs.kdrek);
        $('#'+ids+'_namarekening').val(rs.nmrek);
        $('#'+ids+'_idrekening').val(rs.idrek);
      });
    }
    
    function addSKPD(e)
    {
    /* ambil id_skpd */
      /*if (App.id_skpd() == 0) {
        getSKPD();
        return;
      }
      else param = {idskpd: App.id_skpd(), kdskpd: App.kd_skpd(), nmskpd: App.nm_skpd()};*/
      
      var option = {multi:0, tree:1};
      Dialog.pilihSKPD(option, function(obj, select){
        if (select.length === 0) return false;

        var rs = $(obj).jqGrid('getRowData', select[0].id);

        var ids = $("#grid").jqGrid('getGridParam','selrow');
        $('#'+ids+'_kdskpd').val(rs.kode);
        $('#'+ids+'_nmskpd').val(rs.nama);
        $('#'+ids+'_idskpd').val(rs.id);
      });
      
    }  
    
          function getSKPD(){
      var option = {multi:0};
      Dialog.pilihSKPD(option, function(obj, select){
        var rs = $(obj).jqGrid('getRowData', select[0].id);
        param = {idskpd: rs.id, kdskpd: rs.kode, nmskpd: rs.nama};
      });
  }
    
  
  var ModelRB = function (){
  var self = this;
  self.modul = 'Rekening Bendahara';
  self.id_skpd = ko.observable(0);
    self.kd_skpd = ko.observable('');
    self.nm_skpd = ko.observable('');
  }
  
  var App = new ModelRB();
  
  ko.applyBindings(App);
  setTimeout(function(){
<?php
if ($id_skpd !== 0){
?>
    App.id_skpd(<?php echo $id_skpd; ?>);
    App.kd_skpd('<?php echo $kode_skpd; ?>');
    App.nm_skpd('<?php echo $nama_skpd; ?>');

   $('#grid').jqGrid('hideCol', 'nmskpd');
   $('#grid').jqGrid('hideCol', 'kdskpd');
<?php
}
?>
    //if(App.beban()) App.beban.valueHasMutated();
    //App.init_grid();
  }, 500)
    
    
  });
  </script>
  <fieldset>
    <legend>Tahun Anggaran</legend>
  </fieldset>

  <table id="grid"></table>
  <div id="pager"></div>

<script type="text/javascript">
$(document).ready(function() {
  $.datepicker.setDefaults($.datepicker.regional['id']);
  var last = newid = 0;	
  var data_dasar = <?php echo isset($akses) ? $akses : 0; ?>;
  
  jQuery("#grid").jqGrid({
    url:root+modul+'<?php echo $daftar_url;?>',
    editurl:root+modul+'<?php echo $ubah_url?>',
    datatype:'json',
    mtype:'POST',
    colNames:['Tahun Anggaran', 'Status Anggaran Pertama', 'Status Kini'],
    colModel:[
        {name:'tahun', index:'tahun', width:150, editable:true, edittype:'text', editoptions:{size:3,class:'span2',maxlength: 4,onKeydown:'ForceNumericOnly(event)'}, editrules:{required:true}},
        {name:'status_awal', index:'status_awal', width:700, editable:true, edittype:'select', editoptions:{class:'span2'}, editrules:{}},
        {name:'status_kini', index:'status_kini', width:700, editable:true, edittype:'select', editoptions:{class:'span2'}, editrules:{}}
    ],
    rowNum:10,
    rowList:[10,20,30],
    rownumbers:true,
    pager:'#pager',
    sortorder:'asc',
    sortname:'tahun',
    viewrecords:true,
    gridview:true,
    width:930,
    height:250,
    
    /************************************start status anggaran**************************************************/
    subGrid:true,
    subGridRowExpanded: function(subgrid_id,row_id){
      var ret = jQuery("#grid").jqGrid('getRowData',row_id);
      var status,pagerStatus;
      status = subgrid_id+"_t";
      pagerStatus = "p_"+status;
      jQuery("#"+subgrid_id).html("<table id='"+status+"' class='scroll'></table><div id='"+pagerStatus+"' class='scroll'></div>");
      jQuery("#"+status).jqGrid({
        url:root+modul+'/get_daftar_status'+'/'+ret.tahun,
        editurl:root+modul+'/proses_form_status',
        datatype:'json',
        mtype:'POST',
        colNames:['', 'Status', 'Tgl RKA', 'Tgl Perda APBD', 'No APBD', 'Tgl Perkada', 'No Perkada', 'Tgl DPA APBD', 'No DPA'],
        colModel:[
            {name:'tahun', index:'tahun', edittype:'text', hidden:true},
            {name:'status', index:'status', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2'}, editrules:{required:true}},
            {name:'tgl_rka', index:'tgl_rka', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2',dataInit: function(el) { setTimeout(function() { $(el).datepicker(); }, 200); } }, formatter:'date', align:'center'},
            {name:'tgl_apbd', index:'tgl_apbd', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2',dataInit: function(el) { setTimeout(function() { $(el).datepicker(); }, 200); }}, formatter:'date', align:'center'},
            {name:'no_apbd', index:'no_apbd', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2'}},
            {name:'tgl_perkada', index:'tgl_perkada', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2',dataInit: function(el) { setTimeout(function() { $(el).datepicker(); }, 200); }}, formatter:'date', align:'center'},
            {name:'no_perkada', index:'no_perkada', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2'}},
            {name:'tgl_dpa', index:'tgl_dpa', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2',dataInit: function(el) { setTimeout(function() { $(el).datepicker(); }, 200); }}, formatter:'date', align:'center'},
            {name:'no_dpa', index:'no_dpa', width:110, editable:true, edittype:'text', editoptions:{size:3,class:'span2'}},
        ],
        rowNum:10,
        rowList:[10,20,30],
        rownumbers:true,
        pager:"#"+pagerStatus,
        sortorder:'asc',
        viewrecords:true,
        gridview:true,
        height:'100%',
        autowidth:true,
        ondblClickRow:edit_row_status,
        onSelectRow:restore_row_status,
      });
      jQuery("#"+status).jqGrid( 'navGrid', "#"+pagerStatus, { 
      <?php
        if($akses=='3'){
        echo "
        add: true,
        addtext: 'Tambah',
        addfunc: append_row_status,
        edit: true,
        edittext: 'Ubah',
        editfunc: edit_row_status,
        del: true,
        deltext: 'Hapus',
        delfunc: del_row_status,
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
      });
      function append_row_status(){
        if(data_dasar=='3'){
          var ret = jQuery("#grid").jqGrid('getRowData',row_id);
          var data = {tahun:row_id};
          if(row_id != 'new')
          {
            jml = jQuery("#"+status).jqGrid('getDataIDs');
            pos = jml.length - 1;
            if(jml[pos] == "new"){
              alert('Input status belum tersimpan..!!');
            }
            else{
              jQuery("#"+status).jqGrid('restoreRow', last);
              jQuery("#"+status).jqGrid('addRowData', "new", data);
              jQuery("#"+status).jqGrid('editRow', "new", true, null, null, null, data, aftersavefunc_status, errorfunc_status, null);
            }
            last=null;
          }
          else
          {
            alert('Silahkan input status terlebih dahulu.');
          }
        }else{
          alert('Tidak bisa tambah data');
        }
      }

      function edit_row_status(id){
        if(data_dasar=='3'){
          jQuery("#"+status).jqGrid('restoreRow', last);
          jQuery("#"+status).jqGrid('editRow', id, true, null, null, null, null, aftersavefunc_status, errorfunc_status, null);
          last = id;
        }else{
          alert('Tidak bisa ubah data');
        }
      }
      
      function del_row_status(id){
        if(data_dasar=='3'){
          var answer = confirm('Hapus status dari daftar?');
          if(answer == true)
          {							
            jQuery.ajax({
              url: root+modul+'/hapus_status', 
              data: { id: id},
              success: function(response){
                var msg = jQuery.parseJSON(response);
                $.pnotify({
                  title: msg.isSuccess ? 'Sukses' : 'Gagal',
                  text: msg.message,
                  type: msg.isSuccess ? 'info' : 'error'
                });
                if(msg.isSuccess == true) {
                  jQuery("#"+status).jqGrid('delRowData', id);
                }
                jQuery("#"+status).trigger('reloadGrid');
              },
              type: "post", 
              dataType: "html"
            });
          }
        }else{
          alert('Tidak bisa hapus data');
        }
      }

      function restore_row_status(id){
        if(id && id !== last){
//						jQuery("#"+status).jqGrid('restoreRow', last);
          jQuery("#"+status).jqGrid('saveRow', last, aftersavefunc, null, null, null, errorfunc, null);
          last = null;
        }
        
        var id = jQuery("#"+status).jqGrid('getGridParam','selrow');
        var ret = jQuery("#"+status).jqGrid('getRowData',id);
        var STATUS = ret.status;
        jQuery.post(root+modul+"/session_status",{
          'STATUS':STATUS
          },function(data){
        });					
			}

      function aftersavefunc_status(id, resp){
        console.log('aftersavefunc');
        var msg = jQuery.parseJSON(resp.responseText);
        $.pnotify({
          title: msg.isSuccess ? 'Sukses' : 'Gagal',
          text: msg.message,
          type: msg.isSuccess ? 'info' : 'error'
        });
        if(msg.id &&  msg.id != id)
        jQuery("#"+id).attr("id", msg.id);
        jQuery('#'+status).trigger('reloadGrid');					
      }

      function errorfunc_status(id, resp){
        var msg = jQuery.parseJSON(resp.responseText);
        if(msg.error)
          $.pnotify({
            title: 'Gagal',
            text: msg.error,
            type: 'error'
          });
        jQuery('#'+status).trigger('reloadGrid');
      }
    },
        
    /************************************end sub status**************************************************/				
    ondblClickRow:edit_row,
    onSelectRow:restore_row
  });
  
  jQuery("#grid").jqGrid( 'navGrid', '#pager', { 
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
  refreshtext: 'Refresh',
  },{},{},{},{});
  
  function append_row(){
    if(data_dasar=='3'){
      jQuery('#grid').jqGrid('setColProp', 'status_awal', {editable: false});
      jQuery('#grid').jqGrid('setColProp', 'status_kini', {editable: false});

      jml = jQuery("#grid").jqGrid('getDataIDs');
      pos = jml.length - 1;
      if(jml[pos] == "new"){
        alert('Input Tahun Anggaran belum tersimpan..!!');
      }
      else{
        jQuery('#grid').jqGrid('restoreRow', last);
        jQuery("#grid").jqGrid('addRowData', "new", true);
        jQuery('#grid').jqGrid('editRow', "new", true, null, null, null, null, aftersavefunc, errorfunc, null);
      }
      last=null;
    }else{
      alert('Tidak bisa tambah data');
    }
  }

  function edit_row(id){
    if(data_dasar=='3'){
      jQuery('#grid').jqGrid('setColProp', 'status_awal', {editable: true});
      jQuery('#grid').jqGrid('setColProp', 'status_awal', {editoptions: {dataUrl:root+modul+'/getselect/'+id}});
	  jQuery('#grid').jqGrid('setColProp', 'status_kini', {editable: true});
      jQuery('#grid').jqGrid('setColProp', 'status_kini', {editoptions: {dataUrl:root+modul+'/getselect/'+id}});
      jQuery('#grid').jqGrid('restoreRow', last);
      jQuery('#grid').jqGrid('editRow', id, true, null, null, null, null, aftersavefunc, errorfunc, null);
      last = id;
    }else{
      alert('Tidak bisa ubah data');
    }							
  }

  function del_row(id){
    if(data_dasar=='3'){
      var answer = confirm('Hapus Tahun Anggaran dari daftar?');
      if(answer == true)
      {					
        jQuery.ajax({
          url: root+modul+'/hapus', 
          data: { tahun: id},
          success: function(response){
            var msg = jQuery.parseJSON(response);
            $.pnotify({
              title: msg.isSuccess ? 'Sukses' : 'Gagal',
              text: msg.message,
              type: msg.isSuccess ? 'info' : 'error'
            });
            if (msg.isSuccess == true) {
              jQuery('#grid').jqGrid('delRowData', id);
            }
            jQuery('#grid').trigger('reloadGrid');
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
      jQuery('#grid').jqGrid('restoreRow', last);
      last = null;
    }
    
    var id = jQuery('#grid').jqGrid('getGridParam','selrow');
    var ret = jQuery('#grid').jqGrid('getRowData',id);
    var TAHUN = ret.tahun;
    
    jQuery.post(root+modul+'/session_tahun',{
      'TAHUN':TAHUN
      },function(data){
    });
    
  }

  function aftersavefunc(id, resp){
    console.log('aftersavefunc');
    var msg = jQuery.parseJSON(resp.responseText);
    $.pnotify({
      title: msg.isSuccess ? 'Sukses' : 'Gagal',
      text: msg.message,
      type: msg.isSuccess ? 'info' : 'error'
    });
    if(msg.id &&  msg.id != id)
      jQuery("#"+id).attr("id", msg.id);
    jQuery('#grid').trigger("reloadGrid");
  }

  function errorfunc(id, resp){
    var msg = jQuery.parseJSON(resp.responseText);
    if(msg.error)
      $.pnotify({
        title: 'Gagal',
        text: msg.error,
        type: 'error'
      });	
      jQuery('#message').addClass('red');
      jQuery('#grid').jqGrid('restoreRow', id);
      jQuery('#grid').trigger("reloadGrid");
  }
});
</script>
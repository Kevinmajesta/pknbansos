numeral.language('id');
var fmtCurrency = {colorize:true, symbol: '', decimalSymbol: ',', digitGroupSymbol:'.'};

$(function($){
  $.datepicker.regional['id'] = {
    closeText: 'Tutup',
    prevText: '&#x3c;mundur',
    nextText: 'maju&#x3e;',
    currentText: 'hari ini',
    monthNames: ['Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','Nopember','Desember'],
    monthNamesShort: ['Jan','Feb','Mar','Apr','Mei','Jun',
    'Jul','Agus','Sep','Okt','Nop','Des'],
    dayNames: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
    dayNamesShort: ['Min','Sen','Sel','Rab','kam','Jum','Sab'],
    dayNamesMin: ['Mg','Sn','Sl','Rb','Km','jm','Sb'],
    dateFormat: 'dd/mm/yy', firstDay: 0,
    isRTL: false
  };
});

function inisialisasi(){
  $.datepicker.setDefaults($.datepicker.regional['id']);
  $('.datepicker').datepicker();
}

(function( $ ) {
  $.fn.terbilang = function( options ) {
    var opt = {
      style  : 4, //style 1=UPPER CASE,2=lower case,3=Title Case,4=Sentence case
      input_type  : "form", //input type (form or text)
      output : "output",  //element's id to show the output
      awalan  : "",  //prefix output
      akhiran : "rupiah",  //postfix output
      on_error  : function( msg ) {
        alert( "Error: " + msg );
      }
    };
    if( options ) {
      $.extend( opt, options );
    }
    this.each( function( ) {
      var self = this;
      if (opt.input_type=="form"){
        $( this ).bind( "keyup", function( e ) {
          _tobilang( this );
        } );
      }
    } );
    var _tobilang = function( self ) {
      var hasil ="";
      if (opt.input_type=="form"){
        var angka=$(self).val();
      }else{
        var angka=$(self).text();
      }
      if(self<0) {
        hasil = opt.awalan + " minus "+ _bilang(angka) + " " + opt.akhiran;
      } else {
        hasil = opt.awalan + " " + _bilang(angka) + " " + opt.akhiran;
      }
      switch (opt.style) {
        case 1:
          hasil = hasil.toUpperCase();
          break;
        case 2:
          hasil = hasil.toLowerCase();
          break;
        case 3:
          hasil = _ucwords(hasil);
          break;
        default:
          hasil = _ucfirst(_ltrim(hasil));
          break;
      }
      $('#'+opt.output).val(hasil);
    }
    var _bilang = function( self ) {
      var x = Math.abs(self);
      angka = new Array("nol", "satu", "dua", "tiga", "empat", "lima","enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
      var temp = "";
      if (x <12) {
        temp = " "+angka[Math.floor(x)];
      } else if (x <20) {
        temp = _bilang(x - 10)+ " belas";
      } else if (x <100) {
        temp = _bilang(x/10)+" puluh"+ _bilang(x % 10);
      } else if (x <200) {
        temp = " seratus" + _bilang(x - 100);
      } else if (x <1000) {
        temp = _bilang(x/100) + " ratus" + _bilang(x % 100);
      } else if (x <2000) {
        temp = " seribu" + _bilang(x - 1000);
      } else if (x <1000000) {
        temp = _bilang(x/1000) +" ribu" + _bilang(x % 1000);
      } else if (x <1000000000) {
        temp = _bilang(x/1000000)+ " juta" + _bilang(x % 1000000);
      } else if (x <1000000000000) {
        temp = _bilang(x/1000000000) + " milyar" + _bilang(x % 1000000000);
      } else if (x <1000000000000000) {
        temp = _bilang(x/1000000000000) +" trilyun" + _bilang(x % 1000000000000);
      }
      return temp;
    }
    var _ltrim = function (str, chars) {
      chars = chars || "\\s";
      return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
    }
    var _ucwords = function( str ) {
      return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
      });
    }
    var _ucfirst = function( str ) {
      var f = str.charAt(0).toUpperCase();
      return f + str.substr(1).toLowerCase();
    }
    _tobilang( this );
  };
})( jQuery );

function preview(param){
  $.ajax({
    type: "post",
    dataType: "json",
    data: param,
    url: root+'preview/'+modul,
    success: function(res, stat){
      if (stat == 'success'){
        if (res.isSuccessful) {
          url = root+'preview/view/'+res.id+'/'+res.nama;
          window.open( url ,'_blank');
        }
        else {
          // TODO: show information error
          $.pnotify({
            text: res.message,
            type: 'error'
          });
        }
      }
    },
    error: function(res, stat, error){
      // TODO: show information error
    }
  })
};

function show_prev(modul, id) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/prev/'+id,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris pertama.',
          type: 'warning'
        });
      }
    },
  });
}

function show_next(modul, id) {
  $.ajax({
    type: "post",
    dataType: "json",
    url: root+modul+'/next/'+id,
    data: {id: id},
    success: function(data) {
      if (true == data.isSuccessful){
        location.href = root+modul+'/form/'+data.id;
      }
      else{
        $.pnotify({
          text: 'Anda telah berada di baris terakhir.',
          type: 'warning'
        });
      }
    },
  });
}

/*
  type : info, warning, error
*/
function show_message(msg, title, type){
  type = type || 'info';
  msg = msg || '';
  title = title || '';

  $.pnotify({
    title: title,
    text: msg,
    type: type
  });
}

function show_info(msg, title) {
  msg = msg || '';
  title = title || 'Informasi';
  show_message(msg, title, 'info');
}

function show_warning(msg, title){
  msg = msg || '';
  title = title || 'Peringatan';
  show_message(msg, title, 'warning');
}

function show_error(msg, title){
  msg = msg || '';
  title = title || 'Error';
  show_message(msg, title, 'error');
}

var Dialog = (function () {
  var nama, dlg, grid, pgr, el, multi, tree, lvl,
      PILIH = [],
      opt = {
        'datatype':'json',
        'mtype':'post',
        'rowNum':'10000000',
        'scroll':true,
        'rownumbers':true,
        'viewrecords':true,
        'gridview':true,
        'autowidth':true,
        'height':'250',
        'recordtext':'{2} baris',
        'shrinkToFit':false,
      };

  function initDialog(opt, callback, param){
    nama = param.name;
    dlg = 'dlg' + param.name;
    grid = '#grdDialog' + param.name;
    pgr = '#pgrDialog' + param.name;
    el = '#' + dlg;
    url = '';
    tree = opt.tree ? opt.tree : 0;
    lvl = opt.lvl ? opt.lvl : 0;
    multi = opt.multi ? opt.multi : 0;

    if(!$(el).length){ $('body').append('<div id="' + dlg + '"></div') }
    $(el).dialog({
      title: param.title,
      height: 450,
      width: 780,
      modal: true,
      resizable: false,
      autoOpen: true,
      closeOnEscape: true,
      open:dialogOpen,
      close: function( event, ui ) {
        event.preventDefault();
        if (opt.element) setTimeout(function(){ $('#'+opt.element).focus(); }, 100);
      },
      buttons: {
        'Pilih' : {
          text: 'Pilih',
          click: function(){
                    callback(grid, PILIH);
                    $(this).dialog('close');
                 },
          class: 'btn btn-primary',
        },
        'Tutup' : {
          text: 'Tutup',
          click: function(){
                    $(this).dialog('close');
                 },
          class: 'btn',
        }
      }
    });
    url = root + 'pilih/' + (param.modul ? param.modul + '/' : '') + param.name;
    $.post(url, opt, loadSuccess, 'json');;
  }

  function loadSuccess(data, status){
    if (data.html){
      $(el).html(data.html);
      $('#bfilter'+nama).click(function(){
        $(grid).trigger("reloadGrid",[{page:1}]);
      });

      $('#str'+nama).keypress(function(e){
        c = e.which ? e.which : e.keyCode;
        switch(c){
         // enter key
         case 13 : $(grid).trigger("reloadGrid",[{page:1}]); break;
         // down arrow
         case 40 : selectFirstRow(); e.preventDefault(); break;
        }
      });

      $('#str'+nama).focus();
    }

    if (data.grid){
      $.extend(opt, data.grid);
      $(grid).jqGrid(opt);
      $(grid).jqGrid('setGridParam', {
        beforeRequest:gridBeforeRequest,
        beforeSelectRow: gridBeforeSelectRow,
        onSelectRow: gridSelectRow,
        onSelectAll: gridSelectAll,
        ondblClickRow: gridDoubleClick,
        loadComplete: gridLoadComplete,
        gridComplete: gridComplete,
        rowattr: gridRowAttr,
      });
      $(grid).bind('keydown', function (e) {
        e.preventDefault();
        if (e.keyCode === 13) {
          if (multi === 1) {
            buttons = $(el).dialog('option', 'buttons');
            buttons['Pilih'].click.apply($(el));
          }
        }
      });
      $(grid).jqGrid('navGrid', pgr, {
        add:false,
        edit:false,
        del:false,
        search:false,
        refresh:true,
        refreshText:'Refresh',
        beforeRefresh: function(){ // --- by @gus 
          $('#str'+nama).val('');
        }
      })
      .jqGrid('bindKeys', {
        'onEnter': function(id){
          buttons = $(el).dialog('option', 'buttons');
          buttons['Pilih'].click.apply($(el));
        }
      });
    }
  }

  function dialogOpen(){
    $(grid).jqGrid('clearGridData');
    // reset
    PILIH = [];
  }

  function gridBeforeRequest(){
    gridFilter();
    // reset
    PILIH = [];
  }

  function gridBeforeSelectRow(id, e){
    if (tree === 1){
      var row = $(grid).jqGrid('getRowData', id);
      if (row.lvl < lvl) return false;
    }

    return true;
  }

  function selectFirstRow(){
    var rows = $(grid).jqGrid('getDataIDs');
    if (rows) {
      $(grid).focus();
      $(grid).jqGrid('setSelection', rows[0], true);
      var cb = $('#jqg_'+ grid.substring(1) +'_'+rows[0]);
      if (multi === 1) {cb.blur(); cb.focus(); }
    }
  }

  function gridSelectRow(id, status){
    if (multi == 1) {
      // Did they select a row or de-select a row?
      if (status == true){
        var data = {id:id};
        PILIH.push(data);
      }
      else {
        // Filter through the array returning every value EXCEPT the id I want removed.
        PILIH = $.grep(PILIH, function(value) {
          return value.id != id;
        });
      }
     }
     else {
       PILIH = [{id:id}];
     }
  }

  function gridSelectAll(id, status){
    if (status == true){
      for (single_id in id){
        // jika checkbox di disable tidak perlu diproses
        var cb = $('#jqg_'+ grid.substring(1) +'_'+id[single_id]);
        if (cb.attr('disabled')) continue;

        // If the value is NOT in the array, then add it to the array.
        cek = $.grep(PILIH, function(value){ return value.id == id[single_id];});
        if (cek.length == 0){
          var data = {id:id[single_id]};
          PILIH.push(data);
        }
      }
    }
    else {
      for (single_id in id){
        // If the value is in the array, then take it out.
        PILIH = $.grep(PILIH, function (value){
          return value.id != id[single_id];
        });
      }
    }
  }

  function gridDoubleClick(){
    if (multi === 1) return;

    buttons = $(el).dialog('option', 'buttons');
    buttons['Pilih'].click.apply($(el));
  }

  function gridFilter(){
    var q = $('#str'+nama).val();
    var postdata = $(grid).jqGrid('getGridParam', 'postData');
    $.extend(postdata,{filters: '', q: q});
    $(grid).jqGrid('setGridParam', {postData: postdata});
  }

  function gridLoadComplete(){

  }

  function gridComplete(){
    if (tree === 1){
      // disable the checkbox if multiselect
      if (multi === 1){
        var cbs = $("tr.row-level > td > input.cbox", grid);
        cbs.attr("disabled", true);
      }
    }
  }

  function gridRowAttr(rd){
    // beri warna tiap level jika hirarki (tree = 1)
    if (tree === 1) {
      switch (rd.lvl){
        case 1 : return {"class": "row-level row-level-1 ui-state-disabled"}; break;
        case 2 : return {"class": "row-level row-level-2 ui-state-disabled"}; break;
        case 3 : return {"class": "row-level row-level-3 ui-state-disabled"}; break;
        case 4 : return {"class": "row-level row-level-4 ui-state-disabled"}; break;
      }
    }
	else if (tree === 2) {
      switch (rd.lvl){
        case 1 : return {"class": "row-level row-level-1 ui-state-disabled"}; break;
        case 2 : return {"class": "row-level row-level-2 ui-state-disabled"}; break;
        case 3 : return {"class": "row-level row-level-3 ui-state-disabled"}; break;
        case 4 : return {"class": "row-level row-level-4 ui-state-disabled"}; break;
        case 5 : return {"class": "row-level row-level-5 ui-state-disabled"}; break;
      }
    }
  }

  function pilihRekening(opt, callback){
    // param : multi [0, 1]
    //         id_skpd
    //         keperluan
    //         jenis
    //         beban
    //         id_aktivitas
    //         id_kegiatan
    //         tanggal
    //         mode
    var param = {
      name: 'rekening',
      title: 'Pilih Rekening',
      modul: 'pilih_rekening',
    }
    initDialog(opt, callback, param);
  }
  
	function pilihRekeningRKA(opt, callback){
    var param = {
      name: 'rekeningRKA',
      title: 'Pilih Rekening',
      modul: 'pilih_rekening',
    }
    initDialog(opt, callback, param);
  }
  
	function pilihRekeningSPJ(opt, callback){
		var param = {
			name: 'rekeningSPJ',
			title: 'Pilih Rekening',
			modul: 'pilih_rekening',
		}
		initDialog(opt, callback, param);
	}

  function pilihPejabatDaerah(opt, callback){
    // opt : multi [0, 1]
    var param = {
      name: 'pejabatdaerah',
      title: 'Pilih Pejabat Daerah',
    }
    initDialog(opt, callback, param);
  }

  function pilihPejabatSKPD(opt, callback){
    // opt : multi [0, 1]
    //         id_skpd
    var param = {
      name: 'pejabatskpd',
      title: 'Pilih Pejabat SKPD',
    }
    initDialog(opt, callback, param);
  }
  
  function pilihPejabatPenguji(opt, callback){
    // opt : multi [0, 1]
    //         id_skpd
    var param = {
      name: 'pejabatpenguji',
      title: 'Pilih Pejabat Penguji',
    }
    initDialog(opt, callback, param);
  }

  function pilihSKPD(opt, callback)
  {
    // opt : multi [0, 1]
    var param = {
      name: 'skpd',
      title: 'Pilih SKPD'
    }
    initDialog(opt, callback, param);
  }

  function pilihKegiatan(opt, callback)
  {
    // opt : multi [0, 1]
    //       id_skpd
    //       mode
    var param = {
      name: 'kegiatan',
      title: 'Pilih Kegiatan',
      modul: 'pilih_kegiatan',
    }
    initDialog(opt, callback, param);
  }

  function pilihKegiatanAktivitas(opt, callback)
  {
    // param : multi [0, 1]
    //         id_skpd
    //         tanggal
    //         mode
    var param = {
      name: 'kegiatanAktivitas',
      title: 'Pilih Kegiatan',
      modul: 'pilih_kegiatan',
    }
    initDialog(opt, callback, param);
  }

  function pilihSumberdana(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sumberdana',
      title: 'Pilih Sumber Dana'
    }
    initDialog(opt, callback, param);
  }

  function pilihSumberdanaSKPD(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sumberdanaskpd',
      title: 'Pilih Rekening Bendahara'
    }
    initDialog(opt, callback, param);
  }

  function pilihPajak(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'pajak',
      title: 'Pilih Pajak'
    }
    initDialog(opt, callback, param);
  }

  function pilihPotongan(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'potongan',
      title: 'Pilih Potongan'
    }
    initDialog(opt, callback, param);
  }

  function pilihKontrak(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'kontrak',
      title: 'Pilih Kontrak'
    }
    initDialog(opt, callback, param);
  }
  
  function pilihSPJSPP(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spjspp',
      title: 'Pilih Nomor SPJ'
    }
    initDialog(opt, callback, param);
  }

  function pilihLokasi(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'lokasi',
      title: 'Pilih Lokasi',
      modul: 'pilih_lokasi'
    }
    initDialog(opt, callback, param);
  }

  function pilihSP2D(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sp2d',
      title: 'Pilih SP2D',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSPM(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spm',
      title: 'Pilih SPM',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSPP(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spp',
      title: 'Pilih SPP',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSPJ(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spj',
      title: 'Pilih SPJ',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSPJK(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spjk',
      title: 'Pilih Setoran Pajak',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSPFK(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'spfk',
      title: 'Pilih Setoran PFK',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSSU(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'ssu',
      title: 'Pilih Setoran Sisa',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSTS(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'sts',
      title: 'Pilih STS',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihCP(opt, callback)
  {
    // param : multi [0, 1]
    var param = {
      name: 'cp',
      title: 'Pilih Kontra Pos',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSPD(opt, callback)
  {
    // param : multi [0, 1]
    //   id
    //   tanggal
    //   keperluan
    //   beban
    var param = {
      name: 'spd',
      title: 'Pilih SPD',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihSKRD(opt, callback)
  {
    // param : multi [0, 1]
    //   id_skpd
    var param = {
      name: 'skrd',
      title: 'Pilih SKRD',
      modul: 'pilih_aktivitas',
    }
    initDialog(opt, callback, param);
  }

  function pilihProposal(opt, callback)
  {
    // param : multi [0, 1]
    //   id_skpd
    var param = {
      name: 'proposal',
      title: 'Pilih Proposal',
      modul: 'pilih_proposal',
    }
    initDialog(opt, callback, param);
  }
  
  function pilihUraian(opt, callback)
  {
    // param : multi [0, 1]
    //   id_skpd
    var param = {
      name: 'rekeningUraian',
      title: 'Pilih Uraian',
      modul: 'pilih_rekening',
    }
    initDialog(opt, callback, param);
  }

  return {
    pilihRekening:pilihRekening,
    pilihRekeningRKA:pilihRekeningRKA,
    pilihRekeningSPJ:pilihRekeningSPJ,
    pilihPejabatDaerah: pilihPejabatDaerah,
    pilihPejabatSKPD: pilihPejabatSKPD,
    pilihPejabatPenguji: pilihPejabatPenguji,
    pilihKegiatan:pilihKegiatan,
    pilihKegiatanAktivitas:pilihKegiatanAktivitas,
    pilihSKPD:pilihSKPD,
    pilihSumberdana:pilihSumberdana,
    pilihSumberdanaSKPD:pilihSumberdanaSKPD,
    pilihKontrak:pilihKontrak,
    pilihSPJSPP:pilihSPJSPP,
    pilihPajak:pilihPajak,
    pilihPotongan:pilihPotongan,
    pilihLokasi:pilihLokasi,
    pilihSP2D:pilihSP2D,
    pilihSPM:pilihSPM,
    pilihSPP:pilihSPP,
    pilihSPJ:pilihSPJ,
    pilihSPJK:pilihSPJK,
    pilihSPFK:pilihSPFK,
    pilihSSU:pilihSSU,
    pilihSTS:pilihSTS,
    pilihCP:pilihCP,
    pilihSPD:pilihSPD,
    pilihSKRD:pilihSKRD,
    pilihProposal:pilihProposal,
    pilihUraian:pilihUraian,
  }
}());

var Daftar = (function () {
	var  $grd, $pgr, $search,
        opt = {
			grid: '#grid',
			pager: '#pager',
			search: '#search',
			akses: 0,
			add: true,
			edit: true,
			del: true,
			url: '',
			url_add: '',
			url_del: '',
			data: {
				url:'',
				datatype:'json',
				mtype:'POST',
				colNames:null,
				colModel:null,
				rownumbers:true,
				viewrecords:true,
				gridview:true,
				shrinkToFit:false,
				autowidth:true,
				height:300,
			},
        };

	function initialize(options){
		var pgropt = {
            addtext: 'Tambah',
            edittext: 'Ubah',
            deltext: 'Hapus',
            refreshtext: 'Refresh',
        };
		
		if (typeof options === "object") {
			$.extend(true, opt, options);
		}
		
		opt.akses = parseInt(opt.akses);
		$grid = $(opt.grid);

		$pager = $(opt.pager);
		$search = $(opt.search);
		opt.data.url = opt.url;
		opt.data.pager = opt.pager;
		if (options.pageropt &&  typeof options.pageropt === "object") {
			$.extend(true, pgropt, options.pageropt);
		}
		pgropt.add = opt.add && canEdit();
		pgropt.addfunc = add_row;
		pgropt.edit = opt.edit && canEdit();
		pgropt.editfunc = edit_row;
		pgropt.del = opt.del && canEdit();
		pgropt.delfunc =  (opt.verifikasi ? undo_row : del_row);
		pgropt.search = false;
		pgropt.refresh = true;
		pgropt.beforeRefresh = function(){
			$('#q').val('');
			var q = $('#q').val();
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
			delete postdata.sa;
			$.extend(postdata,{filters: '', m: postdata.m, q: postdata.q});
			$grid.jqGrid('setGridParam', {search: true, postData: postdata});
		};
    
    
		/* init jqGrid */
		$grid.jqGrid(opt.data);
		if(opt.akses !='1'){		
			$grid.jqGrid('navGrid', opt.pager, pgropt,{},{},{},{})
			.navSeparatorAdd(opt.pager)
			.navButtonAdd(opt.pager,{
				caption:'',
				onClickButton: function(){ print_list('pdf') },
				title:'Cetak Daftar (PDF)',
				buttonicon:'ui-icon-pdf',
				position:'last'
			})
			.navButtonAdd(opt.pager,{
				caption:'',
				onClickButton: function(){ print_list('xls') },
				title:'Cetak Daftar (XLS)',
				buttonicon:'ui-icon-xls',
				position:'last'
			});
		}  
		else{
			$grid.jqGrid('navGrid', opt.pager, pgropt,{},{},{},{});
		}
		
		$grid.jqGrid('bindKeys', {
			'onEnter':edit_row
		});
		$grid.jqGrid('setGridParam', {
			beforeRequest:gridBeforeRequest,
			ondblClickRow: edit_row,
			loadComplete: gridLoadComplete,
			gridComplete: gridComplete,
		});

		/* init keypress untuk pencarian */
		$('#q').keypress(function(e){
			c = e.which ? e.which : e.keyCode;
			switch(c){
			// enter key
				case 13 : $('#grid').trigger("reloadGrid",[{page:1}]); break;
			}
		});

		/* sembunyikan kolom skpd untuk user skpd */
		if (opt.skpd !== 0 ) $grid.jqGrid('hideCol', ['kdskpd', 'nmskpd']);
	}

	function gridBeforeRequest(){		
		var slen = $('#q').length;
		if(slen==1){
			var q = $('#q').val();
			var postdata = $grid.jqGrid('getGridParam', 'postData');
			$.extend(postdata,{filters: '', m: 's', q: q});
			$grid.jqGrid('setGridParam', {search: true, postData: postdata});
		}
	}

	function gridLoadComplete(){

	}

	function gridComplete(){

	}

	function canSee(){
		return opt.akses === 1 || canPrint() ||canEdit() ? true : false;
	}
    
	function canPrint(){
		return opt.akses === 2 || canEdit() ? true : false;
	}  

	function canEdit(){
		return opt.akses === 3 ? true : false;
	}

	function canDelete(){
		return opt.akses === 3 ? true : false;
	}

	function add_row(id){
		if (!canEdit()) return false;
		var id = $grid.jqGrid('getDataIDs');
		if (opt.modul === 'rka21' && opt.skpd !== 0 && id.length > 0)
		{
			location.href = opt.url_add+'/'+id[0];
			console.log (opt.modul);
			console.log (opt.skpd);
			console.log (id.length);
		}
		else{
			location.href = opt.url_add;
		}
	}

	function edit_row(id){
		if (!canEdit() && !canPrint() && !canSee()) return false;
		location.href = opt.url_add+'/'+id;
	}

	function del_row(id){
		if (!canEdit()) return false;
		var answer = confirm('Hapus dari daftar?');
		if(answer == true){
			$.ajax({
				type: "post",
				dataType: "json",
				url: opt.url_del,
				data: {id: id},
				success: function(res) {
					$.pnotify({
						title: res.isSuccess ? 'Sukses' : 'Gagal',
						text: res.message,
						type: res.isSuccess ? 'info' : 'error'
					});
					if (true == res.isSuccess){
						$grid.jqGrid('delRowData', id);
					};
				},
			});
		}
	}

	function print_list(doc){
		if (!canPrint()) return false;
		var postdata = $grid.jqGrid('getGridParam', 'postData');

		preview({"tipe":"daftar", "format":doc, "m":postdata.m, "q":postdata.q});
	}

	return {
		init:initialize,
	}
}());

// param :
//  grid = grid jquery object
//  col = object contains id, sortName, sortOrder
//  toAdd = data to add
function addRowSorted(grid, col, toAdd) {
  var data = grid.jqGrid("getRowData"), // ambil data yang ada di grid
      datalen = data.length,
      sortlen = col.sortName.length,
      sortorder = typeof(col.sortOrder) === "undefined" ? "asc" : col.sortOrder, // default order : asc
      id = new_id = 0,
      src = dst = '',
      i = j = 0;

  new_id = toAdd[col.id];
  for (i = 0; i < datalen; i++) {
    id = data[i][col.id];
    if (sortlen > 0) {
      src = dst = '';
      for (var j = 0; j < sortlen; j++) {
        src = src + data[i][col.sortName[j]].toLowerCase();
        dst = dst + toAdd[col.sortName[j]].toLowerCase();
      }
      if (sortorder == "desc" && src < dst) {
        grid.jqGrid('addRowData', new_id, toAdd, 'before', id);
        return;
      }
      else if (sortorder == "asc" && src > dst) {
        grid.jqGrid('addRowData', new_id, toAdd, 'before', id);
        return;
      }
      else if (src === dst) { return; }
    }
  }
  //The data is empty or it should be last, add it at the end.
  grid.jqGrid('addRowData', new_id, toAdd, 'last');
}

// cek apakah di grid ada yang masih di edit, simpan jika ada
// grd : objek jqgrid
// idcol : kolom yang digunakan sebagai primary key
// aftersave : callback function
function checkGridRow(grd, idcol, aftersave){
  var row = grd.jqGrid('getGridParam', 'savedRow');
  if (row.length > 0) {
    for (i = 0; i < row.length; i++){
      grd.jqGrid('saveRow', row[i][idcol], null, 'clientArray', null, aftersave);
    }
  }
}

// cek apakah di grid ada yang nilainya minus
// grd : objek jqgrid
// cekcol : kolom yang hendak dicek nilainya
// return true jika ada yang minus
// return false jika tidak ada yang minus
function checkGridMinus(grd, cekcol){
  var row = grd.jqGrid('getRowData');
  if (row.length > 0) {
    for (i = 0; i < row.length; i++){
      if  (row[i][cekcol] < 0) return true;
    }
  }
  return false;
}

// Knockout binding handler
ko.bindingHandlers.select2 = {
  init: function(element, valueAccessor, allBindingsAccessor) {
      var obj = valueAccessor();
      $(element).select2(obj);

      ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
          $(element).select2('destroy');
      });
  },
  update: function(element) {
      $(element).trigger('change');
  }
};

var formatNumber = function (element, valueAccessor, allBindingsAccessor, format) {
    // Provide a custom text value
    var value = valueAccessor(), allBindings = allBindingsAccessor();
    var numeralFormat = allBindingsAccessor.numeralFormat || format;
    var strNumber = ko.utils.unwrapObservable(value);
    if (strNumber !== '') {
        return numeral(strNumber).format(numeralFormat);
    }
    return '';
};

ko.bindingHandlers.numeraltext = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));
    }
};

ko.bindingHandlers.numeralvalue = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));

        //handle the field changing
        ko.utils.registerEventHandler(element, "change", function () {
            var observable = valueAccessor(),
                val = $(element).val(),
                nom = numeral().unformat(val);
            observable(nom);
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0,0.00)"));
    }
};

ko.bindingHandlers.percenttext = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).text(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));
    }
};

ko.bindingHandlers.percentvalue = {
    init: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));

        //handle the field changing
        ko.utils.registerEventHandler(element, "change", function () {
            var observable = valueAccessor();
            observable($(element).val());
        });
    },
    update: function (element, valueAccessor, allBindingsAccessor) {
        $(element).val(formatNumber(element, valueAccessor, allBindingsAccessor, "(0.000 %)"));
    }
};


ko.bindingHandlers.executeOnEnter = {
  init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
    var allBindings = allBindingsAccessor();
    $(element).keypress(function (event) {
      var keyCode = (event.which ? event.which : event.keyCode);
      if (keyCode === 13) {
        allBindings.executeOnEnter.call(viewModel);
        return false;
      }
      return true;
    });
  }
};

ko.bindingHandlers.expand = {
    init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
        if ($(element).hasClass('ui-expander')) {
            var expander = element;
            var head = $(expander).find('.ui-expander-head');
            var content = $(expander).find('.ui-expander-content');
            
            $(head).click(function () {
                $(head).toggleClass('ui-expander-head-collapsed');
                $(content).toggle();
            });
    }
    }
};

ko.validation.rules['integer'] = {
    validator: function (val, validate) {
        if (!validate) {return true; }
        return val === null || val === "" || (validate && /^-?\d*$/.test(val));
    },
    message: 'Must be an integer value'
};

ko.validation.rules['mustGreater'] = {
    validator: function (val, otherVal) {
        return val > otherVal;
    },
    message: 'The field must greater than {0}'
};

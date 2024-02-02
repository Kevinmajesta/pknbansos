
var gridRKADPA = (function () {
  var  opt = {
            tipe: 'rka21',
            perubahan: false,
            akses: 0,
            grd : [],
        },
        pager,
        last = 0,
        grd_dirty = false,
        purge = [],
        datadef = {trf: 0, jml: 0, jml_next: 0, realisasi: 0, jml_old: 0, jml_next_old: 0},
        datalanjutandef =  {trf: 0, jml: 0, real_0: 0, saldo_0: 0, real_1: 0, saldo_1: 0, real_2: 0, saldo_2: 0, jml_old: 0, saldo_0_old: 0, saldo_1_old: 0, saldo_2_old: 0, real_0_old: 0, real_1_old: 0, real_2_old: 0},
        autocomplete_satuan = {
            class:'autocomplete',
            dataInit: function (elem) {
              value: list_satuan(elem);
            }
        },
        autocomplete_uraian = {
            class:'autocomplete',
            dataInit: function (elem) {
              value: list_uraian(elem);
            }
        },
        auto_update_jml = {
            dataEvents: [{ 
              type: 'focusout', fn: function(e) { 
                var id = opt.grd.jqGrid('getGridParam', 'selrow');
                update_jml_by_key(id); 
              } 
            }]
        },
        auto_insert_row = {
            dataEvents: [{ 
              type: 'keypress', fn: function(e) { 
                if(e.keyCode == 9) {
                  var id = opt.grd.jqGrid('getGridParam', 'selrow');
                  insert_row(id);
                } 
              } 
            }]
        };

  var init = function (options){
    var cols = [], col = [];
    if (typeof options == "object") $.extend(opt, options);

    if (typeof opt.grd != "object") {
      alert('Grid Rincian RKA belum didefinisikan');
      return;
    }

    cols = opt.grd.jqGrid('getGridParam', 'colModel');
    for (var i = 1; i < cols.length; i++){
    // untuk set editoption : autocomplete kolom satuan di grid
      if (cols[i].name === 'sat'){
        col = cols[i];
        col.editoptions = autocomplete_satuan;
      }
      
    // untuk set editoption : update kolom jml di grid setelah edit vol atau tarif
      if (opt.tipe === 'rka1' || opt.tipe ===  'rka21' || opt.tipe === 'rka221') {
        if (cols[i].name === 'vol' || cols[i].name === 'trf') {
          col = cols[i];
          col.editoptions = auto_update_jml;
        }
        
        if (cols[i].name === 'ket') {
          col = cols[i];
          col.editoptions = auto_insert_row;
        }
      }
      
      if (opt.tipe === 'rka31' && opt.perubahan == true) {
        if (cols[i].name === 'jml') {
          col = cols[i];
          col.editoptions = auto_update_jml;
        }
      }

      if (opt.tipe === 'rka31' || opt.tipe === 'rka32') {
        if (cols[i].name === 'ket') {
          col = cols[i];
          col.editoptions = auto_insert_row;
        }
      }
      
      if (cols.length > 0) opt.grd.jqGrid('setGridParam', {'colModel' : cols}).trigger('reloadGrid');
    }

    // autocomplete uraian, hanya untuk RKA 221
    if (opt.tipe == 'rka221'){
      cols = opt.grd.jqGrid('getGridParam', 'colModel');
      for (var i = 1; i < cols.length; i++){
        if (cols[i].name === 'uraian'){
          col = cols[i];
          col.editoptions = autocomplete_uraian;
          opt.grd.jqGrid('setGridParam', {'colModel' : cols});
          opt.grd.trigger('reloadGrid');
          break;
        }
      }
    }

    pager = opt.grd.jqGrid('getGridParam', 'pager');
    opt.grd.jqGrid('setGridParam', {
        beforeRequest:gridBeforeRequest,
        beforeSelectRow: gridBeforeSelectRow,
        onSelectRow: gridSelectRow,
        ondblClickRow: gridDoubleClick,
        loadComplete: gridLoadComplete,
        gridComplete: gridComplete,
        rowattr: gridRowAttr,
    })
    //.jqGrid('bindKeys', { 'onEnter': edit_row })
    .jqGrid('navGrid', pager, {
      add: canEdit(),
      addtext: 'Tambah',
      addfunc: add_row,
      edit: canEdit(),
      edittext: 'Sisip',
      editfunc: insert_row,
      del: canEdit(),
      deltext: 'Hapus',
      delfunc:del_row,
      search:false,
      refresh:false,
    },{},{},{},{});
	console.log(canEdit(),'canEdit');

  }

  function gridBeforeRequest() {

  }

  function gridBeforeSelectRow(id, e) {
    var row = opt.grd.jqGrid('getRowData', id);
    if (row.lvl == 0) return true
    else return false;
  }

  function gridSelectRow(id) {
    if (id && id !== last) {
      $(this).restoreRow(last);
      last = id;
    }
  }

  function gridDoubleClick(id) {
    var row = opt.grd.jqGrid('getRowData', id);
    if (row.lvl == 0) {
      edit_row(id);
      return true;
    }
    else return false;
  }

  function  gridLoadComplete() {
    HitungTotal();
  }

  function gridComplete() {
    gridUnbindKeys();
    gridBindKeys();

    HitungTotal();
    if (opt.tipe === 'rkal' || opt.tipe === 'dpal') {
      tab_lanjutan_pelaksanaan();
    }
  }

  function gridRowAttr(rek) {
    var lvl = parseInt(rek.lvl);
    switch (lvl){
      case 1 : return {"class": "row-level row-level-1"}; break;
      case 2 : return {"class": "row-level row-level-2"}; break;
      case 3 : return {"class": "row-level row-level-3"}; break;
      case 4 : return {"class": "row-level row-level-4"}; break;
      case 5 : return {"class": "row-level row-level-5"}; break;
      case 6 : return rek.idra < 0 ? {"class": "row-level row-level-6"} : ''; break;
      case 7 : return rek.idra < 0 ? {"class": "row-level row-level-7"} : ''; break;
      default : return '';
    }
  }

  function gridUnbindKeys(){
    opt.grd.jqGrid('unbindKeys');
  }

  function gridBindKeys(){
    opt.grd.jqGrid('bindKeys', { 'onEnter': edit_row });
  }

  function getNewid(){
    return 'new_' + parseFloat((new Date().getTime() + '').slice(7))+ Math.round(Math.random() * 100);
  }

  function canAdd() {
    //if (opt.tipe==='dpa1' || opt.tipe==='dpa21' || opt.tipe==='dpa221' || opt.tipe==='dpa31'|| opt.dpa==='rkadpa') {
    if (opt.tipe==='dpa1' || opt.tipe==='dpa21' || opt.tipe==='dpa221' || opt.tipe==='dpa31' || opt.tipe==='dpa32'|| opt.tipe==='dpal') {
      return false;
    }
    else {
      return opt.akses === 3 ? true : false;
    }
  }

  function canEdit() {
	
    if (opt.tipe==='dpa1' || opt.tipe==='dpa21' || opt.tipe==='dpa221' || opt.tipe==='dpa31' || opt.tipe==='dpa32'|| opt.tipe==='dpal') {
      return false;
    }
    else {
      return opt.akses === 3 ? true : false;
    }
  }

  function canDelete() {
    //if (opt.tipe==='dpa1' || opt.tipe==='dpa21' || opt.tipe==='dpa221' || opt.tipe==='dpa31' || opt.dpa ==='rkadpa') {
    if (opt.tipe==='dpa1' || opt.tipe==='dpa21' || opt.tipe==='dpa221' || opt.tipe==='dpa31' || opt.tipe==='dpa32'|| opt.tipe==='dpal') {
      return false;
    }
    else {
      return opt.akses === 3 ? true : false;
    }
  }

  function add_row() {
	$(opt.grd).jqGrid('saveRow',last, false, 'clientArray');
    var self = this;
    if (!canAdd()) return false;

    var option = {multi:1, mode:opt.tipe === 'rkal' ? 'rka221' : opt.tipe, tree:1, level:5};
    Dialog.pilihRekeningRKA(option, function(obj, select){
      if (select.length === 0) return;
      addRekening(obj, select);
    });
  }

  function insert_row(id){
    var row = [], newdata = [], newid = 0;
    if (!canAdd()) return false;
    var lvl = opt.grd.jqGrid('getCell', id, 'lvl');
    if (lvl !== '0') return false;

    opt.grd.jqGrid('resetSelection');
    row = opt.grd.jqGrid('getRowData', id);

    newid = getNewid();
    if (opt.tipe==='rkal') {
      newdata = {idra: newid, idrek:row.idrek, idp: row.idp, lvl:0, kdrek: '', uraian: '', vol: 0, };
      $.extend(newdata, datalanjutandef);
    }
    else{
      newdata = {idra: newid, idrek:row.idrek, idp: row.idp, lvl:0, kdrek: '', uraian: '', ket: '', vol: 0};
      $.extend(newdata, datadef);
    }

    if (last) opt.grd.jqGrid('restoreRow', last);
    opt.grd.jqGrid('saveRow', id, false, 'clientArray', null, after_save_row, null, afterrestorefunc);
    opt.grd.jqGrid('addRowData', newid, newdata, 'after', id);
    opt.grd.jqGrid('setSelection', newid);
    updateParent(opt.grd, newdata, '+');
    opt.grd.jqGrid('editRow', newid, true, bind_uraian, null, 'clientArray', null, after_save_row, null, afterrestorefunc);
    $('#'+newid+'_uraian').focus();
    opt.grd.closest(".ui-jqgrid-bdiv").scrollLeft(0);
    $('#hitung_grd_rinci').addClass('ui-state-disabled');

    last = newid;
    return newid;
  }
  
  function update_jml_by_key(id){
    var row = opt.grd.jqGrid('getRowData', id);
    jml_a = parseFloat(row.jml_a);
    
    if (opt.tipe === 'rka31' || opt.tipe === 'rka32') {
      jml = parseFloat($('#'+id+'_jml').val());
    }
    else {
      vol = parseFloat($('#'+id+'_vol').val());
      trf = parseFloat($('#'+id+'_trf').val());
      jml = parseFloat(vol * trf);
    }
    
    sub = jml - jml_a;
    if (opt.tipe === 'rka31' || opt.tipe === 'rka32') {
      opt.grd.jqGrid('setRowData', id, {sub: sub});
    }
    else {
      opt.grd.jqGrid('setRowData', id, {jml: jml, sub: sub});
    }
  }

  function uraian_change(e){
    var idx = e.currentTarget.id,
         pos = idx.indexOf('_uraian'),
         pre = idx.substring(0, pos),
         vol = $('#'+pre+'_vol'),
         uraian = $('#'+pre+'_uraian').val();

    var x = get_volume(uraian);
    //if(prex == 'new'){
      if (x) vol.val(x);
    //}
  }

  function get_volume(uraian){
    if (uraian && uraian.length == 0) return false;

    if (b = uraian.match(/\d+/g)){
      var x = 1;
      for(var i = 0; i < b.length; i++) {
        x = x*b[i];
      }
      return x;
    }
    return false;
  }

  function bind_uraian(id){
    $('#'+id+'_uraian').change(uraian_change);
  }

  function check_row(){
    var ri = opt.grd.jqGrid('getGridParam', 'savedRow');
    if(ri.length > 0){
      if(ri[0].id){
        $(opt.grd).jqGrid('saveRow', ri[0].id, function(){opt.grd.jqGrid('restoreRow', last);}, 'clientArray', null, after_save);
      }
    }
  }

  function edit_row(id){
    var row = opt.grd.jqGrid('getRowData', id);
    if (opt.tipe === 'rka31' || opt.tipe === 'rka32') {
      jml_old = parseFloat(row.jml_old);
      if (opt.perubahan == true)
        jml_old = parseFloat(row.jml);
    }
    else {
      jml_old = parseFloat(row.vol) * parseFloat(row.trf);
    }
    opt.grd.jqGrid('setRowData', id, {jml_old: jml_old});
    if (row.lvl != 0) return false;
    if (!canEdit()) return false;
    $(opt.grd).jqGrid('editRow', id, true, bind_uraian, null, 'clientArray', null, after_save_row, null, afterrestorefunc);
    $('#hitung_grd_rinci').addClass('ui-state-disabled');
    last = id;
  }

  function del_row(id) {
    if (!canDelete()) return false;

    var row = $(this).jqGrid('getRowData', id);

    if (opt.perubahan && row.awal == 1) {
      show_warning( 'Uraian dari status awal tidak boleh dihapus' );
      return;
    }
    row.jml = -row.jml;
    row.jml_old = 0;
    row.jml_next = 0;

    if (opt.tipe==='rkal') {
      row.real_0 = -row.real_0;
      row.real_1 = -row.real_1;
      row.real_2 = -row.real_2;

      row.real_0_old = 0;
      row.real_1_old = 0;
      row.real_2_old = 0;
    }
    updateParent($(this), row, '-');
    opt.grd.jqGrid('delRowData', id);
    if (row.iddr !== '') purge.push(id); // Hanya yang memiliki iddr saja yg ditambahkan
    HitungTotal();
    if (opt.tipe==='rkal') {
      tab_lanjutan_pelaksanaan();
    }
  }
  
	function hitung_ulang() {
		var rowdata = opt.grd.jqGrid('getRowData');
		rowdata.reverse();
		
		var jml5 = 0, jml4 = 0, jml3 = 0, jml2 = 0, jml1 = 0;
		var real5 = 0, real4 = 0, real3 = 0, real2 = 0, real1 = 0;
		var jml_next5 = 0, jml_next4 = 0, jml_next3 = 0, jml_next2 = 0, jml_next1 = 0;
		var jml_a5 = 0, jml_a4 = 0, jml_a3 = 0, jml_a2 = 0, jml_a1 = 0;
		for (var i=0; i<rowdata.length; i++) {
			if (rowdata[i]['lvl'] === '0') {
				jml5 = jml5 + parseFloat(rowdata[i]['jml']);
				if(rowdata[i]['realisasi']=='') real5 = real5 + 0;
				else real5 = real5 + parseFloat(rowdata[i]['realisasi']);
				jml_next5 = jml_next5 + parseFloat(rowdata[i]['jml_next']);
				jml_a5 = jml_a5 + parseFloat(rowdata[i]['jml_a']);
				//sub5 = jml_a5 - jml5;
				sub5 = jml5 - jml_a5;

				id5 = rowdata[i]['idrek'];        
				opt.grd.jqGrid('setRowData', -id5, {jml_a: jml_a5, jml: jml5, sub: sub5, realisasi: real5, jml_next: jml_next5});
			}
      
			if (rowdata[i]['lvl'] === '5') {
				jml4 = jml4 + jml5;
				real4 = real4 + real5;
				jml_next4 = jml_next4 + jml_next5;
				jml_a4 = jml_a4 + jml_a5;
				//sub4 = jml_a4 - jml4;
				sub4 = jml4 - jml_a4;

				id4 = rowdata[i]['idp'];
				opt.grd.jqGrid('setRowData', -id4, {jml_a: jml_a4, jml: jml4, sub: sub4, realisasi: real4, jml_next: jml_next4});
				jml5 = 0; real5 = 0; jml_next5 = 0; jml_a5 = 0;
			}
      
			if (rowdata[i]['lvl'] === '4') {
				jml3 = jml3 + jml4;
				real3 = real3 + real4;
				jml_next3 = jml_next3 + jml_next4;
				jml_a3 = jml_a3 + jml_a4;
				//sub3 = jml_a3 - jml3;
				sub3 = jml3 - jml_a3;

				id3 = rowdata[i]['idp'];
				opt.grd.jqGrid('setRowData', -id3, {jml_a: jml_a3, jml: jml3, sub: sub3, realisasi: real3, jml_next: jml_next3});
				jml4 = 0; real4 = 0; jml_next4 = 0; jml_a4 = 0;
			}

			if (rowdata[i]['lvl'] === '3') {
				jml2 = jml2 + jml3;
				real2 = real2 + real3;
				jml_next2 = jml_next2 + jml_next3;
				jml_a2 = jml_a2 + jml_a3;
				//sub2 = jml_a2 - jml2;
				sub2 = jml2 - jml_a2;

				id2 = rowdata[i]['idp'];
				opt.grd.jqGrid('setRowData', -id2, {jml_a: jml_a2, jml: jml2, sub: sub2, realisasi: real2, jml_next: jml_next2});
				jml3 = 0; real3 = 0; jml_next3 = 0; jml_a3 = 0;
			}

			if (rowdata[i]['lvl'] === '2') {
				jml1 = jml1 + jml2;
				real1 = real1 + real2;
				jml_next1 = jml_next1 + jml_next2;
				jml_a1 = jml_a1 + jml_a2;
				//sub1 = jml_a1 - jml1;
				sub1 = jml1 - jml_a1;

				id1 = rowdata[i]['idp'];
				opt.grd.jqGrid('setRowData', -id1, {jml_a: jml_a1, jml: jml1, sub: sub1, realisasi: real1, jml_next: jml_next1});
				jml2 = 0; real2 = 0; jml_next2 = 0; jml_a2 = 0;
			}
		}
	}
	
	function pilih_proposal2(id){
		var option = {multi:1, mode:'rka'};
		var $grid = $('#grd_rinci'),
		i = 0,
		v = 1,
		row = [],
		newd = [],
		selrowid = opt.grd.jqGrid ('getGridParam', 'selrow');
		uraian = $('#'+selrowid+'_uraian').val();

		if(selrowid==null){
			alert('Silahkan pilih Rekening terlebih dahulu.');	
			console.log(selrowid);
		}
		else{
			console.log(selrowid);
			var rrlvl5 = [];
			jml = opt.grd.jqGrid('getDataIDs');
			for (var i=0; i < jml.length; i++)
			{
				var rr = grd.jqGrid('getRowData', jml[i]);
				rrlvl5.push(rr.idpro);
			}
			
			Dialog.pilihProposal(option, function(obj, select){
				for (i = 0; i < select.length; i++){  
					var row = $(obj).jqGrid('getRowData', select[i].id);
					if ($.inArray(String(row.id), rrlvl5) < 0)
					{					
						var uraian = row.nama + ' - ' + row.alamat,
						tarif = row.nom;
						idpro = row.id;
						newid = insert_row(selrowid);
						newdata = {idra: newid, idrek:row.idrek, idp: row.id, idpro : row.id, lvl:0, kdrek: '', uraian:row.nama + ' - ' + row.alamat, ket: '', jml:(row.vol*row.nom), vol: 1, trf:row.nom, jml_next: 0, realisasi: 0, jml_old: 0};

						opt.grd.jqGrid('restoreRow', newid);
						//addRowSorted($grid, {'id':'idpro', 'sortName':['uraian']},{'tarif':row.nom, 'uraian':rs.nama + ' - ' + rs.alamat,});
						opt.grd.jqGrid('setRowData', newid, newdata);

						console.log(row);
						console.log(uraian);
						console.log(tarif);
						console.log(idpro);
						console.log(newdata);
						console.log(newid);
						last = newid;    
						selrowid=newid;
						newd.push(newid);
					}
				}
				for (i=0;i<newd.length;i++){
					after_save(newd[i]);
				}
			}); 
		}      
	};
  
  function after_save_row(id) {
    $('#hitung_grd_rinci').removeClass('ui-state-disabled');
    if (opt.tipe === 'rka31' || opt.tipe === 'rka32') {
      after_save(id);
    }
    else {
      var old_row = opt.grd.jqGrid('getRowData', id);
      jml = parseFloat(old_row.jml_old);
      opt.grd.jqGrid('setRowData', id, {jml: jml});
      
      after_save(id);
    }
  }
  
  function after_save(id) {
    var row = opt.grd.jqGrid('getRowData', id);
    jml_old = parseFloat(row.jml);
    jml_next_old = parseFloat(row.jml_next);

    if (opt.tipe==='rkal') {
      saldo_0_old = parseFloat(row.saldo_0_old);
      saldo_1_old = parseFloat(row.saldo_1_old);
      saldo_2_old = parseFloat(row.saldo_l2_old);

      real_0 = parseFloat(row.real_0);
      real_1 = parseFloat(row.real_1);
      real_2 = parseFloat(row.real_2);

      real_0_old = parseFloat(row.real_0_old);
      real_1_old = parseFloat(row.real_1_old);
      real_2_old = parseFloat(row.real_2_old);

      saldo_0 = parseFloat(row.saldo_0);
      saldo_1 = parseFloat(row.saldo_1);
      saldo_2 = parseFloat(row.saldo_2);
    }

    if (opt.tipe === 'rka31' || opt.tipe === 'rka32') {
      jml = parseFloat(row.jml);
    } else {
      /*idx = row.idra;
      posx = idx.indexOf('_');
      prex = idx.substring(0, posx);*/

      vol = parseFloat(row.vol);
      /*if(prex == 'new'){
        x = get_volume(row.uraian);
        if (x) vol = parseFloat(x);
      }*/
      trf = parseFloat(row.trf);
      jml = vol * trf;
    }

    jml_a = parseFloat(row.jml_a);
    sub = jml - jml_a;
    jml_next = parseFloat(row.jml_next);

    if (opt.tipe==='rkal') {
      opt.grd.jqGrid('setRowData', id, {vol:vol, jml: jml, jml_old: jml_old, sub: sub, saldo_0:(jml - real_0), saldo_1:(jml - real_0)-real_1, saldo_2:(jml - real_0 - real_1) - real_2, real_0_old:real_0, real_1_old:real_1, real_2_old:real_2});
    }
    else if(opt.tipe === 'rka31' || opt.tipe === 'rka32'){
      opt.grd.jqGrid('setRowData', id, {jml: jml, jml_old: jml_old, sub: sub, jml_next: jml_next, jml_next_old: jml_next_old});
    }
    else{
      opt.grd.jqGrid('setRowData', id, {vol:vol, jml: jml, jml_old: jml_old, sub: sub, jml_next: jml_next, jml_next_old: jml_next_old});
    }

    row.jml = jml;
    if (opt.tipe !== 'rka31' && opt.tipe !== 'rka32') row.jml_old = jml_old;
    if (opt.tipe==='rkal') {
      row.real_0 = real_0;
      row.real_1 = real_1;
      row.real_2 = real_2;

      row.real_0_old = real_0_old;
      row.real_1_old = real_1_old;
      row.real_2_old = real_2_old;

      row.saldo_0 = 0;
      row.saldo_1 = 0;
      row.saldo_2 = 0;

      row.jml_old = jml_old;
      row.saldo_0_old = saldo_1_old;
      row.saldo_1_old = saldo_1_old;
      row.saldo_2_old = saldo_2_old;
    }
    updateParent(opt.grd, row, '')
    opt.grd.focus();
    opt.grd.jqGrid('setSelection', id);

    HitungTotal();

    if (opt.tipe==='rkal') {
      tab_lanjutan_pelaksanaan();
    }

    grd_dirty = true;
  }

  tab_lanjutan_pelaksanaan = function(){
    var data = [],
         grdl = $("#grd_lanjutanpelaksanaan"),
         arrdata = opt.grd.jqGrid('getDataIDs');
    
    // ambil baris pertama dari grid rincian
    if (arrdata.length <= 0) return;
    var row = opt.grd.jqGrid('getRowData', arrdata[0]);

    // bersihkan dulu grid lanjutan
    grdl.clearGridData();
    data.push({
        id: 0,
        tahun: 0,
        pagu: row.jml,
        real: row.jml - row.saldo_0,
        saldo: row.saldo_0,
    });

    data.push({
        id: 1,
        tahun: 1,
        pagu: row.saldo_0,
        real: row.saldo_0 - row.saldo_1,
        saldo: row.saldo_1,
    });

    if(row.real_2 != 0 || row.real_2 !=''){
      data.push({
          id: 2,
          tahun: 2,
          pagu: row.saldo_1,
          real: row.saldo_1 - row.saldo_2,
          saldo: row.saldo_2
      });
    }
    grdl.jqGrid('addRowData', 0, data);
  }

  function afterrestorefunc(id) {
    $('#hitung_grd_rinci').removeClass('ui-state-disabled');
    var row = opt.grd.jqGrid('getRowData', id);
    jml = parseFloat(row.vol) * parseFloat(row.trf);
    if (opt.tipe === 'rka31' || opt.tipe === 'rka32') jml = parseFloat(row.jml);
    sub = parseFloat(jml) - parseFloat(row.jml_a);
    opt.grd.jqGrid('setRowData', id, {jml: jml, sub: sub}); // hitung ulang jml dan sub
    opt.grd.focus();
  }

  function editable(id) {
    return ($("tr#"+id).attr("editable") == "1");
  }

  function getParent(data, grd_rek, idp){
    var result = [], parent = [], ada, rek, odata, newparent;
    // jika idp sudah ada di array data, hentikan proses
    ada = $.grep(data, function(e){ return e.idrek == idp});
    if (data.length > 0 && ada.length > 0){
      return result;
    }
    else {
      // ambil parent dari grd_rek
      rek = $(grd_rek).jqGrid('getRowData', idp);

      if (rek.idp !== ''){
        parent = $(grd_rek).jqGrid('getRowData', idp);
        odata = {idra: -parent.idrek, idrek: parent.idrek, idp: parent.idp, lvl: parent.lvl, kdrek: parent.kdrek, uraian: parent.nmrek, ket: '', child: 0, vol: ''};
        opt.tipe == 'rkal' ? $.extend(odata, datalanjutandef) : $.extend(odata, datadef);
        result.push( odata );
        // ulangi sampai tidak ada parent lagi
        newparent = getParent(data, grd_rek, rek.idp);
        if (newparent.length > 0) return newparent.concat(result);
        else return result;
      }
      else if (rek.idp == ''){
        // sudah tidak ada parent lagi
        odata = {idra: -rek.idrek, idrek: rek.idrek, idp: rek.idp, lvl: rek.lvl, kdrek: rek.kdrek, uraian: rek.nmrek, ket: '', child: 0, vol: ''};
        opt.tipe == 'rkal' ? $.extend(odata, datalanjutandef) : $.extend(odata, datadef);
        result.push( odata );
        return result;
      }
    }
  }

  // menggabungkan dua array, array rincian dan array rekening baru
  function mergeArray(data, newdata){
    if (data.length == 0) return newdata;  // jika array rincian masih kosong, kembalikan array rekening baru
    var found = false;
    for (var i = 0; i < data.length; i++){
      if (data[i].lvl == 0) continue;
      if (data[i].kdrek < newdata[0].kdrek) continue;
      // array rekening baru disisipkan di lokasi yang sesuai
      for (j = newdata.length - 1; j >= 0; j--){
        data.splice(i, 0, newdata[j]);
      }
      found = true;
      break;
    }

    if (found) return data;
    else return data.concat(newdata);  // array rekening baru posisinya di baris terakhir
  }

  function addRekening(grd_rek, pilih){
    var data = [], newdata = [], added = [], terpilih, ada, odata, newid, idx;
   data = opt.grd.jqGrid('getRowData');

    // proses untuk setiap rekening yang dipilih
    for( var i = 0; i < pilih.length; i++){
      terpilih = $(grd_rek).jqGrid('getRowData', pilih[i].id);
      // jika rekening sudah ada, lanjutkan ke rekening berikutnya
      ada = $.grep(data, function(e) { return e.idrek == terpilih.idrek });
      if (data.length > 0 && ada.length > 0) continue;

      // tambahkan parent
      newdata = getParent(data, grd_rek, terpilih.idp);
      // tambahkan rekening
      odata = {idra: -terpilih.idrek, idrek: terpilih.idrek, idp: terpilih.idp, lvl: terpilih.lvl, kdrek: terpilih.kdrek, uraian: terpilih.nmrek, ket: '', child: 1, vol: ''};
      opt.tipe == 'rkal' ? $.extend(odata, datalanjutandef) : $.extend(odata, datadef);
      newdata.push(odata);
      // tambahkan uraian
      newid = getNewid();
      odata = {idra: newid, idrek: terpilih.idrek, idp: terpilih.idp, lvl: 0, kdrek: '', uraian: '', ket: '', vol: 0};
      opt.tipe == 'rkal' ? $.extend(odata, datalanjutandef) : $.extend(odata, datadef);
      newdata.push(odata);
      added.push(odata);
      // taruh newdata di lokasi yang sesuai
      data = mergeArray(data, newdata);
    }
    for (var i=0; i < added.length; i++)
    {
      var idx = added[i].idp;
      while(ada = $.grep(data, function(e){ return e.idrek == idx})){
        if (ada.length === 0) break;
        ada[0].child = parseFloat(ada[0].child) + 1;
        idx = ada[0].idp;
      }
    }
    opt.grd.jqGrid('clearGridData').
      jqGrid('setGridParam', {data:data}).
      trigger('reloadGrid');
  }

  function updateParent(grd, data, mode){
    var idp = data.idrek, child, newchild, realisasi,
    jml = real = real_ = next = jml_a = sub = 0,
    newdata = [];

    /* ambil rekening parent */
    while(idp > 0){
      result = grd.jqGrid('getRowData', -idp);
      //if (!result.idra) break;

      switch (mode) {
        case '+' : newchild = 1; break;
        case '-' : newchild = -1; break;
        default : newchild = 0; break;
      }

      child = parseFloat(result.child) + newchild;

      if (child === 0) {
        /* hapus parent yang sudah tidak punya anak */
        grd.jqGrid('delRowData', result.idra);
      }
      else {
        realisasi = 0;
        if (data.lvl === '5' && oldidr != data.idrek){
          realisasi = parseFloat(result.realisasi);
        }

        jml = parseFloat(result.jml) - parseFloat(data.jml_old) + parseFloat(data.jml);
        sub = parseFloat(jml) - parseFloat(result.jml_a);
        real = parseFloat(result.realisasi) + parseFloat(realisasi);
        next = parseFloat(result.jml_next) - parseFloat(data.jml_next_old) + parseFloat(data.jml_next);

        if(opt.tipe=='rkal'){
          real_0 = parseFloat(result.real_0) - parseFloat(data.real_0_old)  + parseFloat(data.real_0);
          real_1 = parseFloat(result.real_1) - parseFloat(data.real_1_old)  + parseFloat(data.real_1);
          real_2 = parseFloat(result.real_2) - parseFloat(data.real_2_old)  + parseFloat(data.real_2);

          saldo_0_old = real_0;
          saldo_1_old = real_1;
          saldo_2_old = real_2;

          saldo_0 = jml - real_0;
          saldo_1 = saldo_0 - real_1;
          saldo_2 = saldo_1 - real_2;

          newdata = {vol:'', trf:'', jml:jml, child:child, real_0: real_0, real_1: real_1, real_2: real_2, saldo_0: saldo_0, saldo_1: saldo_1, saldo_2: saldo_2, saldo_0_old: saldo_0_old, saldo_1_old: saldo_1_old, saldo_2_old: saldo_2_old, real_0_old: real_0, real_1_old: real_1, real_2_old: real_2};
        }
        else{
          newdata = {vol:'', trf:'', jml:jml, jml_next:next, sub: sub, child:child};
          if (result.lvl > 0){
            $.extend(newdata, {realisasi:real});
          }
        }

        oldidr = data.idrek;
        grd.jqGrid('setRowData', result.idra, newdata);
      }
      idp = result.idp;
    };
  }

  function list_uraian(elem) {
    var idx, pos, id, row;
    idx = elem.id;
    pos = idx.indexOf('_uraian');
    id = idx.substring(0, pos);
    row = opt.grd.jqGrid('getRowData', id);
    console.log(id);

    $(elem).autocomplete({
      minLength: 1,
      source: root+'pilih/uraian/'+row.idrek,
      open: function( event, ui) { gridUnbindKeys(); },
      close: function( event, ui ) { gridBindKeys(); },
      select: function( event, ui ) {
        event.preventDefault();
        $(elem).val( ui.item.uraian ); // uraian
        $('#' + id + '_vol').val( ui.item.volume ) ; // volume
        $('#' + id + '_sat').val( ui.item.satuan ) ; // satuan
        $('#' + id + '_trf').val( ui.item.harga ) ; // tarif
        return false;
      }
    })
    .data('ui-autocomplete')._renderItem = function( ul, item ) {
      return $('<li>')
        .append('<a><div style="font-size:11px"><strong>' + item.uraian + '</strong></div><div style="font-size:11px; text-align:right">' + item.harga_rp + '</div></a>' )
        .appendTo( ul );
    };
  }

  function list_satuan(elem) {
    $(elem).autocomplete({
      minLength: 1,
      source: root+'pilih/satuan',
      open: function( event, ui) { gridUnbindKeys(); },
      close: function( event, ui ) { gridBindKeys(); },
      select: function( event, ui ) {
        event.preventDefault();
        $(elem).val( ui.item.satuan );
        return false;
      }
    })
    .data('ui-autocomplete')._renderItem = function( ul, item ) {
      return $('<li>')
        .append('<a style="font-size:11px;">' + item.satuan + '</a>' )
        .appendTo( ul );
    };
  }

  function HitungTotal(){
    var sisa = 0, sisa_keg = 0,
         rows = $('#grd_rinci').jqGrid('getDataIDs'),
         row = $('#grd_rinci').jqGrid('getRowData', rows[0]),
         total = total = row.jml ? parseFloat(row.jml) : 0;
    if (opt.total) opt.total(total);
  }

  return {
    init: init,
    insert_row: insert_row,
    after_save: after_save,
    get_purge: purge,
	hitung_ulang: hitung_ulang,
    check_row: check_row,
    update_jml_by_key: update_jml_by_key,

    grd_dirty : function(){return grd_dirty;},
    set_dirty :function(value){grd_dirty = value;},
  };
}());


var DialogSearch = (function(){
  var data = [];
  var objFilters = { 'string' : {'cn' : {'teks' : 'memuat', 'keyword' : 'inputtext'},
                              'bw' : {'teks' : 'diawali', 'keyword' : 'inputtext'},
                              'ew' : {'teks' : 'diakhiri', 'keyword' : 'inputtext'}},
                  'date' : {'eq' : {'teks' : 'sama dengan', 'keyword' : 'inputdate'},
                            'lt' : {'teks' : 'kurang dari', 'keyword' : 'inputdate'},
                            'gt' : {'teks' : 'lebih dari', 'keyword' : 'inputdate'},
                            'in' : {'teks' : 'diantara', 'keyword' : 'inputdate'}},
                  'predefined' : {'eq' : {'teks' : 'sama dengan', 'keyword' : 'select'},
                                  'ne' : {'teks' : 'tidak sama dengan', 'keyword' : 'select'}},
                  'numeric' : {'eq' : {'teks' : 'sama dengan', 'keyword' : 'inputnum'},
                                'lt' : {'teks' : 'kurang dari', 'keyword' : 'inputnum'},
                                'gt' : {'teks' : 'lebih dari', 'keyword' : 'inputnum'},
                                'in' : {'teks' : 'diantara', 'keyword' : 'inputnum'},
                                'ne' : {'teks' : 'tidak sama dengan', 'keyword' : 'inputnum'}}
                };

  function initialize(fields, grid){
    $('#searchAdvance').click(function() {
      slen = $('#q').length;
      mlen = $('#field').length;
      
      if (slen === 1) {
        $('#q').replaceWith('<select id="field" class="span2"><option value=""></option></select>');
        $('#apply').append('<button class="btn btn-primary" type="button" id="searchData">Cari</button>');
      } else if (mlen === 1) {
        $('#field').replaceWith('<input type="text" class="span4" id="q" />');
        $('#filter').empty();
        $('#apply').empty();

        $('#q').keypress(function (e) {
          if (e.which == 13) {
            var q = $('#q').val();
            if (typeof grid == 'undefined')
              var $grid = $('#grid');
            else
              var $grid = grid;
            
            var postdata = $grid.jqGrid('getGridParam', 'postData');
            delete postdata.sa;
            $.extend(postdata,{filters: '', m: 's', q: q});
            $grid.jqGrid('setGridParam', {search: true, postData: postdata});
            $grid.trigger("reloadGrid",[{page:1}]);
          }
        });
      }

      $.each(fields, function(i, val){
        $('#field').append('<option value="'+i+'">'+val['name']+'</option>');
      });
      
      data.push(fields);
    
      changeField(data);
      searchData(grid);
    });
    
  }

  function changeField(data) {
    $('#field').change(function(){
      var field = $(this).val(),
          fieldText = $('#field').find(":selected").text(),
          kategori = data[0][field]['kategori'],
          options = data[0][field]['options'];
      $(this).children('option[value=' + this.value + ']').attr('disabled', true);
      
      addLabel(field, fieldText);

      var oper = addFilter(field, kategori);
      
      var keyword = objFilters[kategori][oper]['keyword'];
      addKeyword(oper, keyword, field, false, options);
      changeKeyword(field, kategori, options);
      cekbox(field, kategori);
      
    });
  }
  
  function addLabel(field, fieldText)
  {
    var htmlLabel = '<div id="'+field+'" style="margin-bottom: 10px;">';
        htmlLabel += '<label class="checkbox" style="margin-right:10px;"><input type="checkbox" checked="true" id="cek_'+field+'" value="'+field+'" style="margin-right:5px;">' + fieldText + '</label></div>';
    $('#filter').append(htmlLabel);
  }
  
  function addFilter(field, kategori)
  {
    var oper = objFilters[kategori];
    var htmlFilter = '<span id="flt_'+field+'"><select id="op_'+field+'" class="span2" style="margin-right:5px;">';
    $.each(oper, function(i,val) {
      htmlFilter += '<option value="'+i+'">'+val['teks']+'</option>';
    });
    htmlFilter += '</select></span>';
    $('#'+field).append(htmlFilter);
    
    var oper = $('#op_'+field).find(':selected').val();
    
    return oper;
  }

  function addKeyword(oper, keyword, field, change, options)
  {
    if (oper === 'in')
    {
      if (keyword === 'inputnum') {
        var key = '<span id="key_'+field+'"><input type="text" id="str_'+field+'1" class="span2" style="margin-right:5px;"><label>sd</label><input type="text" id="str_'+field+'2" class="span2" style="margin-left:5px;"></span>';
      }
      else if (keyword === 'inputdate') {
        var key = '<span id="key_'+field+'"><input type="text" id="str_'+field+'1" class="span2 datepicker" style="margin-right:5px; z-indez:1"><label>sd</label><input type="text" id="str_'+field+'2" class="span2 datepicker" style="margin-left:5px;"></span>';
      }
    } else {
      if (keyword === 'inputtext') {
        var key = '<span id="key_'+field+'"><input type="text" id="str_'+field+'" class="span2"></span>';
      }
      if (keyword === 'inputnum') {
        var key = '<span id="key_'+field+'"><input type="text" id="str_'+field+'" class="span2"></span>';
      }
      else if (keyword === 'inputdate') {
        var key = '<span id="key_'+field+'"><input type="text" id="str_'+field+'" class="span2 datepicker"></span>';
      }
      else if (keyword === 'select') {
        var key = '<span id="key_'+field+'"><select id="str_'+field+'" class="span2">';
        $.each(options, function(i,val) {
          key += '<option value="'+i+'">'+val+'</option>';
        });
        key += '</select></span>';
      }
    }
    
    if (change === false) {
      $('#'+field).append(key);
    } else if (change === true) {
      $('#key_'+field).replaceWith(key);
    }

    $.datepicker.setDefaults($.datepicker.regional['id']);
    $('.datepicker').datepicker();
  }
  
  function changeKeyword(field, kategori, options)
  {
    $('#op_'+field).change(function(){
      var oper = $(this).val(),
          keyword = objFilters[kategori][oper]['keyword'];
      addKeyword(oper, keyword, field, true, options);
    });
  }

  function cekbox(field, kategori)
  {
    $('#cek_'+field).click(function() {
      if ($(this).is(":checked")) {
        var oper = addFilter(field, kategori);

        var keyword = objFilters[kategori][oper]['keyword'],
            options = data[0][field]['options'];
        addKeyword(oper, keyword, field, false, options);
        changeKeyword(field, kategori);
      }
      else if ($(this).not(":checked")) {
        $('#flt_'+field).remove();
        $('#key_'+field).remove();
      }
    });
  }
  
  function searchData(grid)
  {
    $('#searchData').click(function() {
      var len = $('#filter div').length, q = [];
      for (i=0; i<len; i++) {
        var field = $('#filter div:eq('+i+')').attr('id'), kategori = data[0][field]['kategori'];
        if ($('#cek_'+field).is(':checked'))
        {
          var operator = $('#op_'+field).val(), str = $('#str_'+field).val(), str1 = $('#str_'+field+'1').val(), str2 = $('#str_'+field+'2').val();
          var dataValue = {searchField : field, searchOper : operator, searchString : str, searchString1 : str1, searchString2 : str2, searchKategori : kategori};
          q.push(dataValue);
        }
      }

 			if (typeof grid == 'undefined')
        var $grid = $("#grid");
      else
        var $grid = grid;
        
      var postdata = $grid.jqGrid('getGridParam', 'postData');
      $.extend(postdata, {filters: '', m: 'm', q: q, sa: 'y'});
      $grid.jqGrid('setGridParam', {search: true, postData: postdata});
      $grid.trigger("reloadGrid",[{page:1}]);
      
    });
  }
  
  return {
    init:initialize,
  }
})();

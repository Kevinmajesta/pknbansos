<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
</fieldset>

<div class="row">
  <div class="span7 pull-left">
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

<script>
$(document).ready(function() {
  var  options = <?php echo json_encode($grid); ?>;
  Daftar.init(options);
  
  // ----- search advance ---- >>
  var fields = <?php echo json_encode($grid['fields']); ?>;
  DialogSearch.init(fields);
});
</script>
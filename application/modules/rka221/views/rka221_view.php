<fieldset>
  <legend><?php echo $breadcrumbs;?></legend>
</fieldset>

<div class="controls-row">
    <div class="input-append pull-right">
      <input type="text" class="span4" id="q" />
      <span class="add-on"><i class="icon-search"></i></span>
    </div>
</div>

<table id="grid"></table>
<div id="pager"></div>

<script>
$(document).ready(function() {
  var  options = <?php echo json_encode($grid); ?>;
  Daftar.init(options);
});
</script>
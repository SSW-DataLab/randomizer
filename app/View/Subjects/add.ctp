<?php echo $this->Html->script('userView', array('inline' => false)); ?>
<h1><?= $settings['project_label']; ?></h1>
<form role="form" id="addYouthForm">
  <div class="form-group">
    <label for="externalID"><?= $settings['external_id_label']; ?>:</label>
    <input type="password" class="form-control" id="externalID" data-bind='value:externalID'></input>
  </div>
  <div class="form-group">
    <label for="confirmExternalID">Re-enter <?= $settings['external_id_label']; ?> for confirmation:</label>
    <input type="password" class="form-control" id="confirmExternalID" data-bind='value:confirmExternalID'></input>
  </div>
  <div class="form-group">
    <label for="siteSelect">Site to which <?= $settings['external_id_label']; ?> belongs:</label>
    <select class="form-control" id='siteSelect' data-bind='value: siteSelect, options: siteList, optionsCaption: "Select site for <?= $settings['external_id_label']; ?>", optionsText: function(item) {return item.Site.site_name;}'></select>
  </div>
  <div class="checkbox">
    <label>
      <input type="checkbox" id="certify" data-bind='checked: certify'> I certify this individual meets the inclusionary criteria for this study and is ready for random assignment.
    </label>
  </div>
  <p class='text-info' data-bind='text: addError'></p>
  <button type="button" class="btn btn-default" data-bind='click: submitClick'>Submit</button>
</form>
<div id="addResult" style='display:none'>
    <p data-bind='text:addResult'></p>
    <a href='<?= $baseURL?>/subjects/add'>Add Another Individual</a> or <a href='<?= $baseURL?>/users/logout'>log out.</a>
</div>
<script type="text/javascript">
var externalIDLabel = '<?= $settings['external_id_label']; ?>';
var baseURL = '<?= $baseURL?>';
</script>
<?php
defined('C5_EXECUTE') or die("Access Denied.");

$cp = FilePermissions::getGlobal();
if ((!$cp->canAddFile()) && (!$cp->canSearchFiles())) {
	die(t("Unable to access the file manager."));
}
Loader::model('file_list');

if (isset($_REQUEST['searchInstance'])) {
	$searchInstance = Loader::helper('text')->entities($_REQUEST['searchInstance']);
} else {
	$searchInstance = $page . time();
}
$ocID = Loader::helper('text')->entities($_REQUEST['ocID']);
$disable_choose = Loader::helper('text')->entities($_REQUEST['disable_choose']);

$cnt = Loader::controller('/dashboard/file_manager/search');
$fileList = $cnt->getRequestedSearchResults();
$files = $fileList->getPage();
$pagination = $fileList->getPagination();
$searchRequest = $cnt->get('searchRequest');
$columns = $cnt->get('columns');

$alType = 'false';
if ($disable_choose == 1) { 
	$alType = 'BROWSE';
}

ob_start();
Loader::element('file_manager/search_results', array('ocID' => $ocID, 'searchInstance' => $searchInstance, 'searchRequest' => $searchRequest, 'columns' => $columns, 'searchType' => 'DIALOG', 'files' => $files, 'fileList' => $fileList), 'legacy_updated_file_manager'); 
$searchForm = ob_get_contents();
ob_end_clean();

$v = View::getInstance();
$v->outputHeaderItems();
$uh = Loader::helper('concrete/urls');


?>

<?php if (!isset($_REQUEST['refreshDialog'])) { ?> 
	<div id="ccm-<?php echo $searchInstance?>-overlay-wrapper">
<?php } ?>
<div id="ccm-<?php echo $searchInstance?>-search-overlay" class="ccm-ui">
	<input type="hidden" name="dialogAction" value="<?= $uh->getToolsURL('files/search_dialog', 'legacy_updated_file_manager'); ?>?ocID=<?php echo $ocID?>&amp;searchInstance=<?php echo $searchInstance?>&amp;disable_choose=<?php echo $disable_choose?>" />

<div class="ccm-pane-options" id="ccm-<?php echo $searchInstance?>-pane-options">

<div class="ccm-file-manager-search-form"><?php Loader::element('file_manager/search_form_advanced', array('searchInstance' => $searchInstance, 'searchRequest' => $searchRequest, 'searchType' => 'DIALOG'), 'legacy_updated_file_manager'); ?></div>
</div>

<?php echo $searchForm?>

</div>

<?php if (!isset($_REQUEST['refreshDialog'])) { ?> 
	</div>
<?php } ?>
<?php
print '<script type="text/javascript">
$(function() {
	ccm_activateFileManager(\'' . $alType . '\', \'' . $searchInstance . '\');
});
</script>';
?>

<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}
if (!EvolutionCMS()->hasPermission('delete_document')) {
    EvolutionCMS()->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id == 0) {
    EvolutionCMS()->webAlertAndQuit($_lang["error_no_id"]);
}

/*******ищем родителя чтобы к нему вернуться********/
$document = \EvolutionCMS\Models\SiteContent::withTrashed()->findOrFail($id);
$pid = ($document->parent == 0 ? $id : $document->parent);

/************ а заодно и путь возврата (сам путь внизу файла) **********/
$sd = isset($_REQUEST['dir']) ? '&dir=' . $_REQUEST['dir'] : '&dir=DESC';
$sb = isset($_REQUEST['sort']) ? '&sort=' . $_REQUEST['sort'] : '&sort=createdon';
$pg = isset($_REQUEST['page']) ? '&page=' . (int)$_REQUEST['page'] : '';
$add_path = $sd . $sb . $pg;

/*****************************/

// check permissions on the document
$udperms = new EvolutionCMS\Legacy\Permissions();
$udperms->user = EvolutionCMS()->getLoginUserID('mgr');
$udperms->document = $id;
$udperms->role = $_SESSION['mgrRole'];

if (!$udperms->checkPermissions()) {
    EvolutionCMS()->webAlertAndQuit($_lang["access_permission_denied"]);
}

$children = $document->getAllChildren($document);

// invoke OnBeforeDocFormDelete event
EvolutionCMS()->invokeEvent("OnBeforeDocFormDelete",
    array(
        "id" => $id,
        "children" => $children
    ));

$documentDeleteIds = $children;
array_unshift($documentDeleteIds, $id);

foreach ($documentDeleteIds as $deleteId) {
    if (EvolutionCMS()->getConfig('site_start') == $deleteId) {
        EvolutionCMS()->webAlertAndQuit("Document is 'Site start' and cannot be deleted!");
    }

    if (EvolutionCMS()->getConfig('site_unavailable_page') == $deleteId) {
        EvolutionCMS()->webAlertAndQuit("Document is used as the 'Site unavailable page' and cannot be deleted!");
    }

    if (EvolutionCMS()->getConfig('error_page') == $deleteId) {
        EvolutionCMS()->webAlertAndQuit("Document is used as the 'Site error page' and cannot be deleted!");
    }

    if (EvolutionCMS()->getConfig('unauthorized_page') == $deleteId) {
        EvolutionCMS()->webAlertAndQuit("Document is used as the 'Site unauthorized page' and cannot be deleted!");
    }
}

$site_content_table = (new \EvolutionCMS\Models\SiteContent())->getTable();
DB::table($site_content_table)
    ->whereIn('id', $documentDeleteIds)
    ->update(['deleted' => 1,
        'deletedby'=>EvolutionCMS()->getLoginUserID('mgr'),
        'deletedon'=>time()]);

// invoke OnDocFormDelete event
EvolutionCMS()->invokeEvent("OnDocFormDelete",
    array(
        "id" => $id,
        "children" => $children
    ));

// Set the item name for logger
$_SESSION['itemname'] = $document->pagetitle;

// empty cache
EvolutionCMS()->clearCache('full');

// finished emptying cache - redirect
$header = "Location: index.php?a=3&id=$pid&r=1" . $add_path;
header($header);
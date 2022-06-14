<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />
    Please use the EVO Content Manager instead of accessing this file directly.");
}

// invoke OnManagerRegClientStartupHTMLBlock event
$evtOut = $modx->invokeEvent('OnManagerMainFrameHeaderHTMLBlock');
$modx_textdir = $modx_textdir ?? null;
$onManagerMainFrameHeaderHTMLBlock = is_array($evtOut) ? implode("\n", $evtOut) : '';
if (!isset($modx->config['mgr_jquery_path'])) {
    $modx->config['mgr_jquery_path'] = 'media/script/jquery/jquery.min.js';
}
if (!isset($modx->config['mgr_date_picker_path'])) {
    $modx->config['mgr_date_picker_path'] = 'media/script/air-datepicker/datepicker.inc.php';
}

$body_class = '';
$theme_modes = array('', 'lightness', 'light', 'dark', 'darkness');
$theme_mode = $_COOKIE['MODX_themeMode'] ?? '';
if ($theme_mode && !empty($theme_modes[$theme_mode])) {
    $body_class .= ' ' . $theme_modes[$theme_mode];
} elseif (!empty($theme_modes[$modx->config['manager_theme_mode'] ?? 3])) {
    $body_class .= ' ' . $theme_modes[$modx->config['manager_theme_mode'] ?? 3];
}

$css = 'media/style/' . $modx->config['manager_theme'] . '/style.css?v=' . $modx->recentUpdate;

$style_path = MODX_MANAGER_PATH . 'media/style/';
if ($modx->config['manager_theme'] === 'default') {
    if (!file_exists($style_path . $modx->config['manager_theme'] . '/css/styles.min.css')
        && is_writable($style_path . $modx->config['manager_theme'] . '/css')) {
        $files = array(
            'bootstrap' => $style_path . 'common/bootstrap/css/bootstrap.min.css',
            'font-awesome' => $style_path . 'common/font-awesome/css/font-awesome.min.css',
            'fonts' => $style_path . $modx->config['manager_theme'] . '/css/fonts.css',
            'forms' => $style_path . $modx->config['manager_theme'] . '/css/forms.css',
            'mainmenu' => $style_path . $modx->config['manager_theme'] . '/css/mainmenu.css',
            'tree' => $style_path . $modx->config['manager_theme'] . '/css/tree.css',
            'custom' => $style_path . $modx->config['manager_theme'] . '/css/custom.css',
            'tabpane' => $style_path . $modx->config['manager_theme'] . '/css/tabpane.css',
            'contextmenu' => $style_path . $modx->config['manager_theme'] . '/css/contextmenu.css',
            'index' => $style_path . $modx->config['manager_theme'] . '/css/index.css',
            'main' => $style_path . $modx->config['manager_theme'] . '/css/main.css'
        );
        $evtOut = $modx->invokeEvent('OnBeforeMinifyCss', array(
            'files' => $files,
            'source' => 'manager',
            'theme' => $modx->config['manager_theme']
        ));
        switch (true) {
            case empty($evtOut):
            case is_array($evtOut) && count($evtOut) === 0:
                break;
            case is_array($evtOut) && count($evtOut) === 1:
                $files = $evtOut[0];
                break;
            default:
                $modx->webAlertAndQuit(sprintf($_lang['invalid_event_response'], 'OnBeforeMinifyManagerCss'));
        }
        require_once MODX_BASE_PATH . 'assets/lib/Formatter/CSSMinify.php';
        $minifier = new Formatter\CSSMinify($files);
        $css = $minifier->minify();
        file_put_contents(
            $style_path . $modx->config['manager_theme'] . '/css/styles.min.css',
            $css
        );
    }
    if (file_exists($style_path . $modx->config['manager_theme'] . '/css/styles.min.css')) {
        $css = 'media/style/' . $modx->config['manager_theme'] . '/css/styles.min.css?v=' . $modx->recentUpdate;
    }
}

?>
<!DOCTYPE html>
<html lang="<?= $modx_lang_attribute ?: 'en' ?>" dir="<?= $modx_textdir === 'rtl' ? 'rtl' : 'ltr' ?>">
<head>
    <title>Evolution CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= $modx_manager_charset ?>"/>
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width"/>
    <meta name="theme-color" content="#1d2023"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="stylesheet" type="text/css" href="<?= $css ?>"/>
    <script type="text/javascript" src="media/script/tabpane.js"></script>
    <?= sprintf('<script type="text/javascript" src="%s"></script>' . "\n", $modx->config['mgr_jquery_path']) ?>
    <?php if ($modx->config['show_picker'] != "0") { ?>
        <script src="media/style/<?= $modx->config['manager_theme'] ?>/js/color.switcher.js"
                type="text/javascript"></script>
    <?php } ?>
    <!-- OnManagerMainFrameHeaderHTMLBlock -->
    <?= $onManagerMainFrameHeaderHTMLBlock . "\n" ?>

    <script type="text/javascript">
      if (!evo) {
        var evo = {};
      }
      var actions;
      var actionStay = [];
      var dontShowWorker = false;
      var documentDirty = false;
      var timerForUnload;
      var managerPath = '';

      evo.lang = {
        saving: '<?= $_lang['saving'] ?>',
        error_internet_connection: '<?= addslashes($_lang['error_internet_connection']) ?>',
        warning_not_saved: '<?= addslashes($_lang['warning_not_saved']) ?>'
      };
      evo.style = {
        actions_file: '<?= $_style['actions_file'] ?>',
        actions_pencil: '<?= $_style['actions_pencil'] ?>',
        actions_reply: '<?= $_style['actions_reply'] ?>',
        actions_plus: '<?= $_style['actions_plus'] ?>'
      };
      evo.urlCheckConnectionToServer = '<?= MODX_MANAGER_URL ?>includes/version.inc.php';
    </script>
    <script src="media/script/main.js"></script>
    <script>
        <?php
        if (isset($_REQUEST['r']) && preg_match('@^[0-9]+$@', $_REQUEST['r'])) {
            echo 'doRefresh(' . $_REQUEST['r'] . ");\n";
        }
        ?>
    </script>
</head>
<body <?= ($modx_textdir ? ' class="rtl"' : '') ?> class="<?= $body_class ?>" data-evocp="color">

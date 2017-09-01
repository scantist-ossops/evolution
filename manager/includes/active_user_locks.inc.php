<?php
if (IN_MANAGER_MODE != "true") {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}

$lockElementId = intval($lockElementId);

if ($lockElementId > 0) {
    ?>
    <script>
      // Trigger unlock when leaving window
      var form_save = false;

      window.addEventListener('unload', unlockThisElement, false);

      function unlockThisElement()
      {
        var stay = document.getElementById('stay');
        // Trigger unlock
        if ((stay && stay.value !== '2') || !form_save) {
          var url = '<?php echo MODX_MANAGER_URL; ?>index.php?a=67&type=<?php echo $lockElementType;?>&id=<?php echo $lockElementId;?>&o=' + Math.random();
          if (navigator.sendBeacon) {
            navigator.sendBeacon(url)
          } else {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, false);
            xhr.send()
          }
          if (top.mainMenu) top.mainMenu.reloadtree()
        }
      }
    </script>
    <?php
}
?>
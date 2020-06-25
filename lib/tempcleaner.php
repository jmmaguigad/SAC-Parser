<?php
array_map('unlink', array_filter((array) array_merge(glob("../tmp/*"))));
echo "Please close this tab.";
?>
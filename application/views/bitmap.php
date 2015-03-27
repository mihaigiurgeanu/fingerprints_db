<?php
header("Content-type: image/bmp");
header("Expires: Mon, 1 Jan 2000 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$size= filesize($filename);
header("Content-Length: $size bytes");

log_message('debug', "Sending $filename with size $size");
readfile($filename);
?>
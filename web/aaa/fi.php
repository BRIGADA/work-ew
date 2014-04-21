<?php 

$finfo = finfo_open(FILEINFO_MIME_TYPE); //

echo finfo_file($finfo, $argv[1]);
finfo_close($finfo);
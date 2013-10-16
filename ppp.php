<?php

$f = file_get_contents('web/uploads/manifest.amf');
echo strlen($f), PHP_EOL;
echo strlen(json_encode($f)),PHP_EOL;
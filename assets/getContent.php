<?php
header("Access-Control-Allow-Origin: *");
echo str_replace ('\"', '"', file_get_contents($_REQUEST['url']));

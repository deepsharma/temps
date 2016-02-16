<?php
header("Access-Control-Allow-Origin: *");
if(file_exists('registered_enterprise.txt'))
{
	$file=file_get_contents('registered_enterprise.txt');
	if($file)
	{
			echo $file;die;
	}else{
			echo "none";die;
	}
}else
{
	echo "none";die;
}
?>
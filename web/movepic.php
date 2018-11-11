<?php
	$url =iconv('utf-8','gb2312',$_POST['url']);
	$type="data\\".iconv('utf-8','gb2312', $_POST['type']);
	$newurl=$type."\\".basename($url);
	if(!file_exists($type))//判断文件夹是否存在
	{
		mkdir($type);
	}
	if(@copy($url,$newurl))
	{
		unlink($url);
	}
?>
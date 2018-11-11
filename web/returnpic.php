<?php
$dir = "./data/"; 
$dir_list = scandir($dir,1);
foreach ($dir_list as $r)
{
	$newdir=$dir.$r;
	$r = iconv('gb2312','utf-8',$r);
	if ($r == '.' || $r == '..'||$r=='所有名单')
	{
		continue;
	}
	else
	{
		
		if(file_exists($newdir))//判断文件夹是否存在
		{
			deldir($newdir);
		}
		
	}
}


$src = "./data/".iconv('utf-8','gb2312',"所有名单");
$des = "./data/".iconv('utf-8','gb2312',"剩余名单");
@mkdir($des);
$src_list = scandir($src);
foreach ($src_list as $r)
{
	@copy($src."/".$r,$des."/".$r);
}
	


function deldir($dir) {
  //先删除目录下的文件：
  $dh=opendir($dir);
  while ($file=readdir($dh)) {
    if($file!="." && $file!="..") {
      $fullpath=$dir."/".$file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          deldir($fullpath);
      }
    }
  }
  
  closedir($dh);
  //删除当前文件夹：
  if(rmdir($dir)) {
    return true;
  } else {
    return false;
  }
}
?>
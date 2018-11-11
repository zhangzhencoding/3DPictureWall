<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>抽奖</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<?php

if(empty($_GET["type"])){$type="所有名单";}
else{$type=$_GET["type"];};
?>
<body>
	<div class='luck-back'>
	<h1 id="title"></h1>
		<div class="luck-pic-content ce-pack-end">
			<div class="luck-set">
				<div class="luck-list-title">
					<span><?php echo $type; ?></span>
				</div>
				<div class="luck-pic-list">
		
<?php
$dir = iconv('utf-8','gb2312',"./data/".$type."/");  //要获取的目录
//先判断指定的路径是不是一个文件夹
if (is_dir($dir)){
	if ($dh = opendir($dir)){
		while (($file = readdir($dh))!= false){
			if($file=='.'||$file=='..')
			{
				continue;
			}
			//文件名的全路径 包含文件名
			$filePath = $dir.$file;
			$filePath=iconv('gb2312','utf-8', $filePath);
			$houzhui = substr(strrchr($file, '.'), 1);
			$name = basename($file,".".$houzhui);
			$name = iconv('gb2312','utf-8', $name);
			echo "<div class='imagelist' ><img src='".$filePath."'><span class='name'>".$name."</span></div>";
		}
		closedir($dh);
	}
}

?>          
            


				</div>
				<div class="luck-list-btn">
					<a href="index.php">返回</a>
					<a href="3D.php">3D留言墙</a>
					<a href="view.php?type=所有名单">所有名单</a>
					<a href="view.php?type=剩余名单">剩余名单</a>
					<?php
					$dir = "./data/"; 
					$dir_list = scandir($dir,1);
					foreach ($dir_list as $r)
					{
						$r = iconv('gb2312','utf-8',$r);
						if ($r == '.' || $r == '..'||$r=='剩余名单'||$r=='所有名单')
						{
							continue;
						}
						
						echo "<a href='view.php?type=".$r."'>".$r."</a>";


					}

					
					?>
				</div>
			</div>
		</div>
	</div>
	<script src="js/jquery-2.2.1.min.js" type="text/javascript"></script>
		<script>
$(function () {
	document.getElementById('title').innerHTML=localStorage.getItem("title");	
});


	</script>

</body>
</html>
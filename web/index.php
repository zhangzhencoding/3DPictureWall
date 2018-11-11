<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>抽奖</title>
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<div class='luck-back'>
	<h1 id="title"></h1>
		<div class="luck-content ce-pack-end">
			<div id="luckuser" class="slotMachine">
				<div class="slot">
					<span class="name">姓名</span>
				</div>
			</div>
			<div class="luck-content-btn">
			参加人数：
			<a class="rs" id="cj" >3</a><br>
			中奖人数：
			<a class="sub" id="sub" onclick="sub()">-</a>
			<a class="rs" id="rs" >3</a>
			<a class="add" id="add" onclick="add()">+</a>
			<br>奖项：
			<input class="type" type="text" id="type" value="">
			<br><br>
				<a id="start" class="start" onclick="start()">开始</a>
			</div>
			<div class="luck-user">
				<div class="luck-user-title">
					<span>中奖名单</span>
				</div>
				<ul class="luck-user-list"></ul>
				<div class="luck-user-btn">
					<a id="view" href="view.php">展示</a>
				</div>
			</div>
		</div>
	</div>
	<?php
	$dir = iconv('utf-8','gb2312',"./data/剩余名单/"); //要获取的目录
	$namelist=array();
	$picnamelist=array();
	$piclist=array();
	//先判断指定的路径是不是一个文件夹
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh))!= false){
				if($file=='.'||$file=='..')
				{
					continue;
				}
				//文件名的全路径 包含文件名
				//$file=iconv('gb2312','utf-8', $file);//乱码
				$filePath =iconv('gb2312','utf-8',$dir).iconv('gb2312','utf-8', $file);//乱码
				$houzhui = substr(strrchr($file, '.'), 1);
				$filname = basename(iconv('gb2312','utf-8', $file),".".$houzhui);
				array_push($namelist,$filname);
				array_push($piclist,$filePath);
				array_push($picnamelist,$filname.".".$houzhui);
				
			}
			closedir($dh);
		}
	}
?>   
	
	<script src="js/jquery-2.2.1.min.js" type="text/javascript"></script>

	<script>

var piclist =eval('<?php echo json_encode($piclist);?>');
var namelist =eval('<?php echo json_encode($namelist);?>');
var picnamelist =eval('<?php echo json_encode($picnamelist);?>');
var pictxt = $('.slot');
var nametxt = $('.name');
var pcount = piclist.length;//参加人数
var runing = true;
var trigger = true;
//var inUser = (Math.floor(Math.random() * 10000)) % 5 + 1;
var num = 0;
//设置单次抽奖人数

$(function () {
	pictxt.css('background-image','url(img/defaultpic.png)');
	nametxt.html("<a href='set.php'>请设置</a>");
	$('#cj').text(pcount);	
	document.getElementById('title').innerHTML=localStorage.getItem("title");	
});

// 减
function sub() {
	$rs=parseInt(document.getElementById("rs").innerHTML);
	if($rs<=1){
		alert("人数不能为0");
	}
	else{
	$('#rs').text($rs-1);	
	}
}
// 加
function add() {
	$('#rs').text(parseInt(document.getElementById("rs").innerHTML)+1);	
}

// 开始停止
function start() {
	var Lotterynumber=document.getElementById("rs").innerHTML; 
	var type=document.getElementById("type").value;
	if(type==''){
		alert("请设置本次抽奖奖项");
		black;
	}
	else{
	$('#view').attr("href","view.php?type="+type);
	if (runing) {

		if ( pcount < Lotterynumber ) {
			alert("抽奖人数不足"+Lotterynumber+"人");
		}else{
			runing = false;
			$('#start').text('停止');
			startNum()
		}

	} else {
		$('#start').text('自动抽取中('+ Lotterynumber+')');
		zd();
	}
	}
	
}

// 开始抽奖

function startLuck() {
	runing = false;
	$('#btntxt').removeClass('start').addClass('stop');
	startNum()
}

// 循环参加名单
function startNum() {
	num = Math.floor(Math.random() * pcount);
	pictxt.css('background-image','url('+piclist[num]+')');
	nametxt.html(namelist[num]);
	t = setTimeout(startNum, 0);
}

// 停止跳动
function stop() {
	pcount = piclist.length-1;
	clearInterval(t);
	t = 0;
}

// 打印中奖人

function zd() {
	var Lotterynumber=document.getElementById("rs").innerHTML; 
	var type=document.getElementById("type").value;
	if (trigger) {

		trigger = false;
		var i = 0;

		if ( pcount >= Lotterynumber ) {
			stopTime = window.setInterval(function () {
				if (runing) {
					runing = false;
					$('#btntxt').removeClass('start').addClass('stop');
					startNum();
				} else {
					runing = true;
					$('#btntxt').removeClass('stop').addClass('start');
					stop();

					i++;
					Lotterynumber--;

					$('#start').text('自动抽取中('+ Lotterynumber+')');

					if ( i == document.getElementById("rs").innerHTML ) {
						console.log("抽奖结束");
						window.clearInterval(stopTime);
						$('#start').text("开始");
						Lotterynumber =document.getElementById("rs").innerHTML;
						trigger = true;
					};

					// if ( Lotterynumber == inUser) {
						// // 指定中奖人
						// nametxt.css('background-image','url(img/7.jpg)');
						// nametxt.html('指定中奖人');
						// $('.luck-user-list').prepend("<li><div class='portrait' style='background-image:url(img/7.jpg)'></div><div class='luckuserName'>指定中奖人</div></li>");
						// $('.modality-list ul').append("<li><div class='luck-img' style='background-image:url(img/7.jpg)'></div><p>指定中奖人</p></li>");
						// inUser = 9999;
					// }else{
						//打印中奖者名单
						$('.luck-user-list').prepend("<li><div class='portrait' style='background-image:url(./data/所有名单/"+picnamelist[num]+")'></div><div class='luckuserName'>"+namelist[num]+"</div></li>");
						$('.modality-list ul').append("<li><div class='luck-img' style='background-image:url(./data/"+type+"/"+picnamelist[num]+")'></div><p>"+namelist[num]+"</p></li>");
						//将已中奖者从数组中"删除",防止二次中奖
						$.ajax({  
						type:'POST',  
						url:"movepic.php",  
						data:{url:piclist[num],type:type},  
						success: function(data){  
						piclist.splice($.inArray(piclist[num], piclist), 1);
						namelist.splice($.inArray(namelist[num], namelist), 1);
						}  
						});   



					//};
				}
			},1000);
		};
	}
}




	</script>

</body>
</html>
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
			<div class="luck-set">
				<div class="luck-set-title">
					<span>系统设置</span>
				</div>
				<ul class="luck-user-list">
				<li>标题：<input class="inputtitle" id="settitle" type="text" value=""></li>
				<li>logo：<input  id="setlogoimg" type="file" style="display:none"><input class="inputtitle" id="setlogoimg1" type="text" value=""> </li>
				<li>背景色：<input class="inputtitle" id="rq" type="text" value=""></li>
				</ul>
				
				<div class="luck-set-btn">
					<a onclick="save()">保存</a>
					<a href="index.php">返回</a>
					<a onclick="returnpic()">重置名单</a>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript"> 
 
</script>
	<script src="js/jquery-2.2.1.min.js" type="text/javascript"></script>
	<script>
$(function () {
	document.getElementById('title').innerHTML=localStorage.getItem("title");	
	document.getElementById('settitle').value=localStorage.getItem("title");
});
$('input[id=lefile]').change(function() { 
$('#photoCover').val($(this).val()); 
});
function save()
{
var settitle=document.getElementById("settitle").value;
localStorage.setItem("title", settitle);
document.getElementById('title').innerHTML=localStorage.getItem("title");
}

function returnpic()
{
	$.ajax({  
	type:'POST',  
	url:"returnpic.php",  
	data:{},  
	success: function(data){  
	alert("重置成功");
	}  
	});  
}

	</script>

</body>
</html>
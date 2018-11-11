<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>抽奖</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/animate.min.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<script src="js/jquery.min.js"></script>
	<script src="js/three.js"></script>
	<script src="js/tween.min.js"></script>
	<script src="js/TrackballControls.js"></script>
	<script src="js/CSS3DRenderer.js"></script>
		<style>
			html, body {
				height: 100%;
			}


			a {
				color: #ffffff;
			}

			#info {
				position: absolute;
				width: 100%;
				color: #ffffff;
				padding: 5px;
				font-family: Monospace;
				font-size: 13px;
				font-weight: bold;
				text-align: center;
				z-index: 1;
			}


			.element {
				width: 100px;/* 120 160 */
				height: 100px;
				box-shadow: 0px 0px 12px rgba(0,255,255,0.5);
				border: 1px solid rgba(127,255,255,0.25);
				text-align: center;
				cursor: default;
			}

			.element:hover {
				box-shadow: 0px 0px 12px rgba(0,255,255,0.75);
				border: 1px solid rgba(127,255,255,0.75);
			}
				.element img{
					width:200px;
					height:200px;
				}

				.element .number {
					position: absolute;
					top: 20px;
					right: 20px;
					font-size: 12px;
					color: rgba(127,255,255,0.75);
				}

				.element .symbol {
					position: absolute;
					top: 40px;
					left: 0px;
					right: 0px;
					font-size: 60px;
					font-weight: bold;
					color: rgba(255,255,255,0.75);
					text-shadow: 0 0 10px rgba(0,255,255,0.95);
				}

				.element .details {
					position: absolute;
					bottom: 15px;
					left: 0px;
					right: 0px;
					font-size: 12px;
					color: rgba(127,255,255,0.75);
				}

			button {
				color: rgba(127,255,255,0.75);
				background: transparent;
				outline: 1px solid rgba(127,255,255,0.75);
				border: 0px;
				padding: 5px 10px;
				cursor: pointer;
			}
			button:hover {
				background-color: rgba(0,255,255,0.5);
			}
			button:active {
				color: #000000;
				background-color: rgba(0,255,255,0.75);
			}
			.show_info{position:fixed;background-color:rgba(0,0,0,.6);padding:10px;width:300px;margin:0 auto;left: 0;right:0;border-radius: 5px;box-shadow: 0 0 10px 0 #fff;top:30%;}
			.show_info img{display:block;margin:auto;border-radius: 5px;box-shadow: 0 0 10px 0 #888; width:250px;}
			.show_info .intro{color:#fff;text-indent:20px;margin-top:10px;height:65px;overflow:hidden;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;}
			.show_info .info_my{text-align: center;}
			.show_info .info_my > *{display:inline-block !important;vertical-align: middle;}
			.show_info .info_my .info_mem{color:#fff;max-width:120px;}
			.show_info .info_my .info_mem > div{text-align: left;}
			.show_info .info_my .info_mem > div.nickname{max-width: 120px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;}
		</style>
		
	</head>
	<body>
	<div class='luck-back'>

		<div id="container"></div>
		<div class="luck-list-btn">
			<a  id="table" style="display:none;">表格</a >
			<a  id="sphere" style="display:none;">球球</a >
			<a  id="helix" style="display:none;">螺旋</a >
			<a  id="grid" style="display:none;">格子</a >
			<a id="view" href="view.php">返回</a>
		</div>

		<div class="show_info animated" style="display:none;">
			<div class="info_my">
				<img id="luckpic"  />
				<br>
				<div class="info_mem">
					<div id="luckname" class="luckname">称呼</div>
				</div>
			</div>
			<div id="intro" class="intro">想说的话</div>
		</div>
</div>
	<?php
	$dir = iconv('utf-8','gb2312',"./data/所有名单/"); //要获取的目录
	$namelist=array();
	$picnamelist=array();
	$piclist=array();
	$A=array();
	$B=array();
	//先判断指定的路径是不是一个文件夹
	if (is_dir($dir))
	{
		if ($dh = opendir($dir))
		{
			while (($file = readdir($dh))!= false)
			{
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
				
				

				/**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
				$filePath =iconv('utf-8','gb2312',"留言表.xls"); //要获取的excel文件
				if(file_exists($filePath))
				{
					require_once 'Classes/PHPExcel.php';
					$PHPExcel = new PHPExcel();
					$PHPReader = new PHPExcel_Reader_Excel2007();
					if(!$PHPReader->canRead($filePath))
					{
						$PHPReader = new PHPExcel_Reader_Excel5();
						if(!$PHPReader->canRead($filePath))
						{
						echo 'no Excel';
						return ;
						}
					}

					$PHPExcel = $PHPReader->load($filePath);
					/**读取excel文件中的第一个工作表*/
					$currentSheet = $PHPExcel->getSheet(0);
					/**取得最大的列号*/
					$allColumn = $currentSheet->getHighestColumn();
					/**取得一共有多少行*/
					$allRow = $currentSheet->getHighestRow();
					/**从第二行开始输出，因为excel表中第一行为列名*/
					for($currentRow = 2;$currentRow <= $allRow;$currentRow++)
					{
					/**从第A列开始输出*/
						for($currentColumn= 'A';$currentColumn<='B'; $currentColumn++)
						{
							$val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();/**ord()将字符转为十进制数*/

							//echo $val;
							/**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
							//echo iconv('utf-8','gb2312', $val)."\t";

							if(!empty($val))
							{
								switch($currentColumn){
									case 'A':array_push($A,$val);break;
									case 'B':array_push($B,$val);break;
								}
							}
						}
					}
				}
			}
			closedir($dh);
		}
	}
?> 
		<script>
		

var piclist =eval('<?php echo json_encode($piclist);?>');
var namelist =eval('<?php echo json_encode($namelist);?>');
var picnamelist =eval('<?php echo json_encode($picnamelist);?>');
var A =eval('<?php echo json_encode($A);?>');
var B =eval('<?php echo json_encode($B);?>');
var personArray = new Array;
var messageArray = new Array;



var CurPersonNum = 0;
// animate
var _in = ['bounceIn','bounceInDown','bounceInLeft','bounceInRight','bounceInUp','fadeIn','fadeInDown','fadeInDownBig','fadeInLeft','fadeInLeftBig','fadeInRight','fadeInRightBig','fadeInUp','fadeInUpBig','rotateIn','rotateInDownLeft','rotateInDownRight','rotateInUpLeft','rotateInUpRight','slideInDown','slideInLeft','slideInRight'];
var _out = ['bounceOut','bounceOutDown','bounceOutLeft','bounceOutRight','bounceOutUp','fadeOut','fadeOutDown','fadeOutDownBig','fadeOutLeft','fadeOutLeftBig','fadeOutRight','fadeOutRightBig','fadeOutUp','fadeOutUpBig','rotateOut','rotateOutDownLeft','rotateOutDownRight','rotateOutUpLeft','rotateOutUpRight','slideOutDown','slideOutLeft','slideOutRight'];

// 模拟推送数据
var s = setInterval(function(){
	// get animate
	var rand_in = parseInt(Math.random() * _in.length,10);
	var rand_out = parseInt(Math.random() * _out.length,10);
	if(CurPersonNum >= piclist.length){
		CurPersonNum = 0;
	}
	$('.show_info').show();
	$('.show_info').addClass(_in[rand_in]);
	document.getElementById('luckname').innerHTML=namelist[CurPersonNum];
	var name1=namelist[CurPersonNum];
	if(messageArray.find(function (x) {return x.name ==name1})){
		var message=messageArray.find(function (x) {return x.name ==name1}).message;
	}
	else{
		var message="";
	}
	document.getElementById('intro').innerHTML=message;
	document.getElementById('luckpic').src=piclist[CurPersonNum];
	setTimeout(function(){
		$('.show_info').removeClass(_in[rand_in]);
		// 更改展示的图片
		var img = document.getElementsByClassName('element')[CurPersonNum].getElementsByTagName('img')[0];
		//img.setAttribute('src','img/b.png');
		++CurPersonNum;
		setTimeout(function(){
			$('.show_info').addClass(_out[rand_out]);
			setTimeout(function(){
				$('.show_info').removeClass(_out[rand_out]);
				$('.show_info').hide();
			},1000);
		},1500);
	},5000);
},8500);

// 生成数据
for(var i=0;i<piclist.length;i++){
	personArray.push({
		image: piclist[i],name:namelist[i]
	});
}
for(var i=0;i<A.length;i++){
	messageArray.push({
		name: A[i],message:B[i]
	});
}



var table = new Array;
for (var i = 0; i < personArray.length; i++) {
	table[i] = new Object();
	if (i < personArray.length) {
		table[i] = personArray[i];
		table[i].src = personArray[i].thumb_image;
	} 
	table[i].p_x = i % 10 + 1;
	table[i].p_y = Math.floor(i / 10) + 1;
}


/*var table = [
	"H", "Hydrogen", "1.00794", 1, 1,
	"He", "Helium", "4.002602", 18, 1,
	"Li", "Lithium", "6.941", 1, 2,
	"Be", "Beryllium", "9.012182", 2, 2,
	"B", "Boron", "10.811", 13, 2,
	"C", "Carbon", "12.0107", 14, 2,
	"N", "Nitrogen", "14.0067", 15, 2,
	"O", "Oxygen", "15.9994", 16, 2,
	"F", "Fluorine", "18.9984032", 17, 2,
	"Ne", "Neon", "20.1797", 18, 2,
	"Na", "Sodium", "22.98976...", 1, 3,
	"Mg", "Magnesium", "24.305", 2, 3,
	"Al", "Aluminium", "26.9815386", 13, 3,
	"Si", "Silicon", "28.0855", 14, 3,
	"P", "Phosphorus", "30.973762", 15, 3,
	"S", "Sulfur", "32.065", 16, 3,
	"Cl", "Chlorine", "35.453", 17, 3,
	"Ar", "Argon", "39.948", 18, 3,
	"K", "Potassium", "39.948", 1, 4,
	"Ca", "Calcium", "40.078", 2, 4,
	"Sc", "Scandium", "44.955912", 3, 4,
	"Ti", "Titanium", "47.867", 4, 4,
	"V", "Vanadium", "50.9415", 5, 4,
	"Cr", "Chromium", "51.9961", 6, 4,
	"Mn", "Manganese", "54.938045", 7, 4,
	"Fe", "Iron", "55.845", 8, 4,
	"Co", "Cobalt", "58.933195", 9, 4,
	"Ni", "Nickel", "58.6934", 10, 4,
	"Cu", "Copper", "63.546", 11, 4,
	"Zn", "Zinc", "65.38", 12, 4,
	"Ga", "Gallium", "69.723", 13, 4,
	"Ge", "Germanium", "72.63", 14, 4,
	"As", "Arsenic", "74.9216", 15, 4,
	"Se", "Selenium", "78.96", 16, 4,
	"Br", "Bromine", "79.904", 17, 4,
	"Kr", "Krypton", "83.798", 18, 4,
	"Rb", "Rubidium", "85.4678", 1, 5,
	"Sr", "Strontium", "87.62", 2, 5,
	"Y", "Yttrium", "88.90585", 3, 5,
	"Zr", "Zirconium", "91.224", 4, 5,
	"Nb", "Niobium", "92.90628", 5, 5,
	"Mo", "Molybdenum", "95.96", 6, 5,
	"Tc", "Technetium", "(98)", 7, 5,
	"Ru", "Ruthenium", "101.07", 8, 5,
	"Rh", "Rhodium", "102.9055", 9, 5,
	"Pd", "Palladium", "106.42", 10, 5,
	"Ag", "Silver", "107.8682", 11, 5,
	"Cd", "Cadmium", "112.411", 12, 5,
	"In", "Indium", "114.818", 13, 5,
	"Sn", "Tin", "118.71", 14, 5,
	"Sb", "Antimony", "121.76", 15, 5,
	"Te", "Tellurium", "127.6", 16, 5,
	"I", "Iodine", "126.90447", 17, 5,
	"Xe", "Xenon", "131.293", 18, 5,
	"Cs", "Caesium", "132.9054", 1, 6,
	"Ba", "Barium", "132.9054", 2, 6,
	"La", "Lanthanum", "138.90547", 4, 9,
	"Ce", "Cerium", "140.116", 5, 9,
	"Pr", "Praseodymium", "140.90765", 6, 9,
	"Nd", "Neodymium", "144.242", 7, 9,
	"Pm", "Promethium", "(145)", 8, 9,
	"Sm", "Samarium", "150.36", 9, 9,
	"Eu", "Europium", "151.964", 10, 9,
	"Gd", "Gadolinium", "157.25", 11, 9,
	"Tb", "Terbium", "158.92535", 12, 9,
	"Dy", "Dysprosium", "162.5", 13, 9,
	"Ho", "Holmium", "164.93032", 14, 9,
	"Er", "Erbium", "167.259", 15, 9,
	"Tm", "Thulium", "168.93421", 16, 9,
	"Yb", "Ytterbium", "173.054", 17, 9,
	"Lu", "Lutetium", "174.9668", 18, 9,
	"Hf", "Hafnium", "178.49", 4, 6,
	"Ta", "Tantalum", "180.94788", 5, 6,
	"W", "Tungsten", "183.84", 6, 6,
	"Re", "Rhenium", "186.207", 7, 6,
	"Os", "Osmium", "190.23", 8, 6,
	"Ir", "Iridium", "192.217", 9, 6,
	"Pt", "Platinum", "195.084", 10, 6,
	"Au", "Gold", "196.966569", 11, 6,
	"Hg", "Mercury", "200.59", 12, 6,
	"Tl", "Thallium", "204.3833", 13, 6,
	"Pb", "Lead", "207.2", 14, 6,
	"Bi", "Bismuth", "208.9804", 15, 6,
	"Po", "Polonium", "(209)", 16, 6,
	"At", "Astatine", "(210)", 17, 6,
	"Rn", "Radon", "(222)", 18, 6,
	"Fr", "Francium", "(223)", 1, 7,
	"Ra", "Radium", "(226)", 2, 7,
	"Ac", "Actinium", "(227)", 4, 10,
	"Th", "Thorium", "232.03806", 5, 10,
	"Pa", "Protactinium", "231.0588", 6, 10,
	"U", "Uranium", "238.02891", 7, 10,
	"Np", "Neptunium", "(237)", 8, 10,
	"Pu", "Plutonium", "(244)", 9, 10,
	"Am", "Americium", "(243)", 10, 10,
	"Cm", "Curium", "(247)", 11, 10,
	"Bk", "Berkelium", "(247)", 12, 10,
	"Cf", "Californium", "(251)", 13, 10,
	"Es", "Einstenium", "(252)", 14, 10,
	"Fm", "Fermium", "(257)", 15, 10,
	"Md", "Mendelevium", "(258)", 16, 10,
	"No", "Nobelium", "(259)", 17, 10,
	"Lr", "Lawrencium", "(262)", 18, 10,
	"Rf", "Rutherfordium", "(267)", 4, 7,
	"Db", "Dubnium", "(268)", 5, 7,
	"Sg", "Seaborgium", "(271)", 6, 7,
	"Bh", "Bohrium", "(272)", 7, 7,
	"Hs", "Hassium", "(270)", 8, 7,
	"Mt", "Meitnerium", "(276)", 9, 7,
	"Ds", "Darmstadium", "(281)", 10, 7,
	"Rg", "Roentgenium", "(280)", 11, 7,
	"Cn", "Copernicium", "(285)", 12, 7,
	"Uut", "Unutrium", "(284)", 13, 7,
	"Fl", "Flerovium", "(289)", 14, 7,
	"Uup", "Ununpentium", "(288)", 15, 7,
	"Lv", "Livermorium", "(293)", 16, 7,
	"Uus", "Ununseptium", "(294)", 17, 7,
	"Uuo", "Ununoctium", "(294)", 18, 7
];*/

var camera, scene, renderer;
var controls;

var objects = [];
var targets = { table: [], sphere: [], helix: [], grid: [] };

init();
animate();

function init() {

	camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
	camera.position.z = 3000;

	scene = new THREE.Scene();

	// table

	for ( var i = 0; i < table.length; i ++ ) {

		var element = document.createElement( 'div' );
		element.className = 'element';
		element.style.backgroundColor = 'rgba(0,127,127,' + ( Math.random() * 0.5 + 0.25 ) + ')';

		var img = document.createElement('img');
		img.src = table[ i ].image;
		element.appendChild( img );


		/*var number = document.createElement( 'div' );
		number.className = 'number';
		number.textContent = (i/5) + 1;
		element.appendChild( number );

		var symbol = document.createElement( 'div' );
		symbol.className = 'symbol';
		symbol.textContent = table[ i ];
		element.appendChild( symbol );

		var details = document.createElement( 'div' );
		details.className = 'details';
		details.innerHTML = table[ i + 1 ] + '<br>' + table[ i + 2 ];
		element.appendChild( details );*/

		var object = new THREE.CSS3DObject( element );
		object.position.x = Math.random() * 4000 - 2000;
		object.position.y = Math.random() * 4000 - 2000;
		object.position.z = Math.random() * 4000 - 2000;
		scene.add( object );

		objects.push( object );

		// 表格需要坐标进行排序的
		var object = new THREE.Object3D();
		// object.position.x = ( table[ i + 3 ] * 140 ) - 1330;
		// object.position.y = - ( table[ i + 4 ] * 180 ) + 990;
		object.position.x = ( table[ i ].p_x * 210 ) - 1330;
		object.position.y = - ( table[ i ].p_y * 210 ) + 990;

		targets.table.push( object );

	}

	// sphere

	var vector = new THREE.Vector3();
	var spherical = new THREE.Spherical();

	for ( var i = 0, l = objects.length; i < l; i ++ ) {

		var phi = Math.acos( -1 + ( 2 * i ) / l );
		var theta = Math.sqrt( l * Math.PI ) * phi;

		var object = new THREE.Object3D();

		spherical.set( 900, phi, theta );

		object.position.setFromSpherical( spherical );

		vector.copy( object.position ).multiplyScalar( 2 );

		object.lookAt( vector );

		targets.sphere.push( object );

	}

	// helix

	var vector = new THREE.Vector3();
	var cylindrical = new THREE.Cylindrical();

	for ( var i = 0, l = objects.length; i < l; i ++ ) {

		var theta = i * 0.4 + Math.PI;
		var y = - ( i * 20 ) + 450;

		var object = new THREE.Object3D();

		// 参数一 圈的大小 参数二 左右间距 参数三 上下间距
		cylindrical.set( 800, theta, y );

		object.position.setFromCylindrical( cylindrical );

		vector.x = object.position.x * 2;
		vector.y = object.position.y;
		vector.z = object.position.z * 2;

		object.lookAt( vector );

		targets.helix.push( object );

	}

	// grid

	for ( var i = 0; i < objects.length; i ++ ) {

		var object = new THREE.Object3D();

		object.position.x = ( ( i % 5 ) * 400 ) - 800; // 400 图片的左右间距  800 x轴中心店
		object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 300 ) + 500;  // 500 y轴中心店
		object.position.z = ( Math.floor( i / 25 ) ) * 200 - 800;// 300调整 片间距 800z轴中心店

		targets.grid.push( object );

	}

	//渲染
	renderer = new THREE.CSS3DRenderer();
	renderer.setSize( window.innerWidth, window.innerHeight );
	renderer.domElement.style.position = 'absolute';
	document.getElementById( 'container' ).appendChild( renderer.domElement );

	// 鼠标控制
	controls = new THREE.TrackballControls( camera, renderer.domElement );
	controls.rotateSpeed = 0.5;
	controls.minDistance = 500;
	controls.maxDistance = 6000;
	controls.addEventListener( 'change', render );

	// 自动更换
	var ini = 0;
	setInterval(function(){
		ini = ini > 3 ? 0 : ini;
		++ini;
		switch(ini){
			case 4:
				transform( targets.table, 1000 );
			break;
			case 1:
				transform( targets.sphere, 1000 );
			break;
			case 2:
				transform( targets.helix, 1000 );
			break;
			case 3:
				transform( targets.grid, 1000 );
			break;
		}	
	},8000);

	var button = document.getElementById( 'table' );
	button.addEventListener( 'click', function ( event ) {
		transform( targets.table, 1000 );
	}, false );

	var button = document.getElementById( 'sphere' );
	button.addEventListener( 'click', function ( event ) {
		transform( targets.sphere, 2000 );
	}, false );

	var button = document.getElementById( 'helix' );
	button.addEventListener( 'click', function ( event ) {
		transform( targets.helix, 2000 );
	}, false );

	var button = document.getElementById( 'grid' );
	button.addEventListener( 'click', function ( event ) {
		transform( targets.grid, 2000 );
	}, false );

	transform( targets.table, 2000 );

	//

	window.addEventListener( 'resize', onWindowResize, false );

}

function transform( targets, duration ) {

	TWEEN.removeAll();

	for ( var i = 0; i < objects.length; i ++ ) {

		var object = objects[ i ];
		var target = targets[ i ];

		new TWEEN.Tween( object.position )
			.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )
			.start();

		new TWEEN.Tween( object.rotation )
			.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
			.easing( TWEEN.Easing.Exponential.InOut )
			.start();

	}

	new TWEEN.Tween( this )
		.to( {}, duration * 2 )
		.onUpdate( render )
		.start();

}

function onWindowResize() {

	camera.aspect = window.innerWidth / window.innerHeight;
	camera.updateProjectionMatrix();

	renderer.setSize( window.innerWidth, window.innerHeight );

	render();

}

function animate() {

	// 让场景通过x轴或者y轴旋转  & z
	// scene.rotation.x += 0.011;
	scene.rotation.y += 0.008;

	requestAnimationFrame( animate );

	TWEEN.update();

	controls.update();

	// 渲染循环
	render();

}

function render() {

	renderer.render( scene, camera );

}



</script>
		
	</body>
</html>

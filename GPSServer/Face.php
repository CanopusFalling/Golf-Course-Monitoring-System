<html>
<body>
<head>
<title>FaCe</title>
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"> 
<link rel="stylesheet" href="Styles.css">
</head>
</head>
<H1>
FaCe
</H1>

<svg width="300" height="200">
	<rect x="100" y="10" width="110" height="30"/>
	<rect x="70" y="40" width="170" height="30"/>
	<rect x="170" y="70" width="50" height="90"/>
	<circle cx="140" cy="90" r="15"/>
	<polygon points="110,70 70,150 110,150 170,140 170,70"/>
	<ellipse  cx="160" cy="140" rx="50" ry="45"/>
	<rect x="110" y="160" width="40" height="10"/>
</svg>

<?php
for($i = 0; $i<=999; $i++){
	echo "
	<svg width='300' height='200'>
		<rect x='100' y='10' width='110' height='30'/>
		<rect x='70' y='40' width='170' height='30'/>
		<rect x='170' y='70' width='50' height='90'/>
		<circle cx='140' cy='90' r='15'/>
		<polygon points='110,70 70,150 110,150 170,140 170,70'/>
		<ellipse  cx='160' cy='140' rx='50' ry='45'/>
		<rect x='110' y='160' width='40' height='10'/>
	</svg>";
}
?>
</body>
</html>
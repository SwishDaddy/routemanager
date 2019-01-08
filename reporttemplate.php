<!doctype html>

	<?php
	include "api/includes.php";
	if (!logincheck()) {
		header("Location: /");
		die();
	};

	headermarkup("Reports");

?>

	<body>

		<img src="<?php echo $sourceurl ?>img/reportheader.png" style="width:100%;margin-top:-50px;"></img>
		<div class="container" style="margin-top:-50px;">
			<div style="text-align:right;font-size:14px;font-weight:bold;">
				<span style="color:white;">Denver International Airport (DIA)</span>
			</div>
			<div style="text-align:right;font-size:14px;font-weight:bold;color:white;">
				<span style="color:white;">Report Date: ###REPORTDATE1###</span>
			</div>
			<h3 style="text-align:center;font-weight:bold;">
				###REPORTTITLE1###
			</h3>
			<div style="margin-top:40px;">
				####TABLHTML###
			</div>
			
		</div>

	</body>
	
</html>

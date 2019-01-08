<?php
	include "api/includes.php";
	headermarkup("Home");
?>

<style>
.iframe-container {
  overflow: hidden;
  padding-top: 56.25%;
  position: relative;
}

.iframe-container iframe {
   border: 0;
   height: 100%;
   left: 0;
   position: absolute;
   top: 0;
   width: 100%;
}
</style>

<body>


<?php globalnav(); ?>

<div class="container" style="margin-top:50px;">
	<div class="row centeralign">

		<?php if (logincheck()) {

		?>

		<div class="row">

			<?php

			$arrroles = rolecheck();

			if (in_array ("driver" , $arrroles)) {

			?>

			<div class="col-sm-4">
				<h2><img src="img\minibus_grey.png"></img> Driver Sheets</h2>
				<p>Enter Route Sheet Data.</p>
				<p><a class="btn btn-default" href="drivers.php" role="button">Get Started &raquo;</a></p>
			</div>



			<?php
			};

				if (in_array ("manager" , $arrroles)) {
			?>

				<div class="col-sm-4">
					<h2><img src="img\printer.png"></img> Reports</h2>
					<p>Create and View Reports.</p>
					<p><a class="btn btn-default" href="reports.php" role="button">Get Started &raquo;</a></p>
				</div>

			<?php
			};

				if (in_array ("comments" , $arrroles)) {
			?>

				<div class="col-sm-4">
					<h2><img src="img\message_edit.png"></img> Comments</h2>
					<p>Manage Feedback</p>
					<p><a class="btn btn-default" href="comments.php" role="button">Get Started &raquo;</a></p>
				</div>

			<?php }; ?>
		</div>

		<div class="row" style="margin-top:40px;">
			<?php
				if (in_array ("fluids" , $arrroles)) {
			?>
				<div class="col-sm-4">
					<h2><img src="img\gauge.png"></img> Fluids</h2>
					<p>Fuel, Oil, etc.</p>
					<p><a class="btn btn-default" href="fluids.php" role="button">Get Started &raquo;</a></p>
				</div>
			<?php };

				if (in_array ("accidents" , $arrroles)) {
			?>
				<div class="col-sm-4">
					<h2><img src="img\trafficlight.png"></img> Accidents</h2>
					<p>Document Accidents</p>
					<p><a class="btn btn-default" href="accidents.php" role="button">Get Started &raquo;</a></p>
				</div>
			<?php };

				if (in_array ("admin" , $arrroles)) {
			?>
				<div class="col-sm-4">
					<h2><img src="img\gears.png"></img> Admin</h2>
					<p>Advanced Functions.</p>
					<p><a class="btn btn-default" href="admin.php" role="button">Get Started &raquo;</a></p>
				</div>
			<?php }; ?>

		</div>

		<?php
		}
		else
		{ ?>
			<h2>Please Sign In to Access your Services.</h2>
			<br /><br />

			<span id = "iewarning" style="display:none;">
				We detect that you are using Internet Explorer.
				<br >
				For a better experience, we recommend Google Chrome, or at least a browser other than Internet Explorer.
			</span>

			<hr /><br />

			<button id="btn_toggle_infosheet" class="btn btn-primary">View Data Sheet</button>

		<?php } ?>

	</div>

	<?php echo footer(); ?>
</div> <!-- /container -->

<?php jsscripts(""); ?>

</body>

<script>

$("#btn_toggle_infosheet").click(function() {
	window.open("routemanager_infosheet.pdf?v=<?php echo $ver ?>");
});

$("#iewarning").hide();

var ua = window.navigator.userAgent;
var is_ie = /MSIE|Trident/.test(ua);

if ( is_ie ) {
	$("#iewarning").show();
}

</script>
</html>

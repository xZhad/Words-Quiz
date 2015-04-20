<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Login";

if(isset($_SESSION['username'])) {
	header("Location: " . APP_URL);
	die();
}

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
$page_css[] = "lockscreen.min.css";
$no_main_header = true;
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->

<!-- MAIN PANEL -->
<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div class="lockscreen animated flipInY">
		<div class="logo">
			<h1 class="semi-bold"><img src="<?php echo ASSETS_URL; ?>/img/logo-o.png" alt="" />Admin</h1>
		</div>
		<div>
			<img src="<?php echo ASSETS_URL; ?>/img/softllamalogo.gif" alt="" width="84" height="108" style="margin: 20px 10px 20px 10px;"/>
			<div>
				<br />
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-user fa-lg fa-fw"></i></span>
					<input class="form-control input-lg" placeholder="username" type="text" name="username" id="username">
				</div>
				<br />
				<div class="input-group">
					<span class="input-group-addon"><i class="fa fa-lock fa-lg fa-fw"></i></span>
					<input class="form-control input-lg" placeholder="password" type="password" name="password" id="password">
				</div>
				<br />
				<div class="input-group" style="float: right;">
					<button class="btn btn-primary" type="submit" id="login">
						<i class="fa fa-sign-in fa-lg fa-fw"></i> sign in
					</button>
				</div>
			</div>

		</div>
	</form>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php
	//include required scripts
	include("inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S)
<script src="..."></script>-->

<script type="text/javascript">
	jQuery(function($) {

		$('#login').on('click', function(e) {
			e.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'http://quiz.softllama.com/api/v1/login',
				dataType: 'json',
				data: {
					username:	$('#username').val(),
					password:	$('#password').val()
				},
				async: true,
				success: function(data) {
					if(data.error == false) {
						$.smallBox({
							title : "Success",
							content : "<i class='fa fa-check'></i> <i>logged in as " + data.username + "</i>",
							color : "#659265",
							iconSmall : "fa fa-thumbs-up fa-2x fadeInRight animated",
							timeout : 4000
						});
						setTimeout(function() {
							window.location.href = "<?php echo APP_URL; ?>";
						}, 2000);
					}
					else {
						$.smallBox({
							title : "Error",
							content : "<i class='fa fa-warning'></i> <i>wrong username or password...</i>",
							color : "#C46A69",
							iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
							timeout : 4000
						});
					}
				},
				error: function(data) {
					$.smallBox({
						title : "Error",
						content : "<i class='fa fa-warning'></i> <i>an unexpected error ocurred :(</i>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
						timeout : 4000
					});
				}
			});
		});

	})
</script>

<?php
	//include footer
	include("inc/google-analytics.php");
?>
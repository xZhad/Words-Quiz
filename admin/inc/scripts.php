		<!--================================================== -->

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo ASSETS_URL; ?>/js/plugin/pace/pace.min.js"></script>

		<!-- These scripts will be located in Header So we can add scripts inside body (used in class.datatables.php) -->
		<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script>
			if (!window.jQuery) {
				document.write('<script src="<?php echo ASSETS_URL; ?>/js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo ASSETS_URL; ?>/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script> -->

		<!-- IMPORTANT: APP CONFIG -->
		<script src="js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>

		<!-- BOOTSTRAP JS -->
		<script src="<?php echo ASSETS_URL; ?>/js/bootstrap/bootstrap.min.js"></script>

		<!-- CUSTOM NOTIFICATION -->
		<script src="<?php echo ASSETS_URL; ?>/js/notification/SmartNotification.min.js"></script>

		<!-- JARVIS WIDGETS -->
		<script src="<?php echo ASSETS_URL; ?>/js/smartwidgets/jarvis.widget.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

		<!-- SPARKLINES -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/sparkline/jquery.sparkline.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>

		<!-- JQUERY MASKED INPUT -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/masked-input/jquery.maskedinput.min.js"></script>

		<!-- JQUERY SELECT2 INPUT -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/select2/select2.min.js"></script>

		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>

		<!-- browser msie issue fix -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>

		<!-- FastClick: For mobile devices -->
		<script src="<?php echo ASSETS_URL; ?>/js/plugin/fastclick/fastclick.min.js"></script>

		<!--[if IE 8]>
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="<?php echo ASSETS_URL; ?>/js/app.min.js"></script>

		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo ASSETS_URL; ?>/js/speech/voicecommand.min.js"></script>

		<script type="text/javascript">
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();

				$('#logout').on('click', function(e) {
					e.preventDefault();
					$.SmartMessageBox({
						title : "<i class=\"fa fa-sign-out txt-color-orangeDark\"></i> Logout <span class=\"txt-color-orangeDark\"><strong><?php echo $_SESSION['username']; ?></strong></span> ?",
						content : "You can improve your security further after logging out by closing this opened browser",
						buttons : '[No][Yes]'
					}, function(ButtonPressed) {
						if (ButtonPressed === "Yes") {
							$.ajax({
								type: 'POST',
								url: 'http://quiz.softllama.com/api/v1/logout',
								dataType: 'json',
								data: {
									username:	"<?php echo $_SESSION['username']; ?>"
								},
								async: true,
								success: function(data) {
									if(data.error == false) {
										$.smallBox({
											title : "Bye!",
											content : "<i class='fa fa-check'></i> <i>signing out.</i>",
											color : "#659265",
											iconSmall : "fa fa-thumbs-up fa-2x fadeInRight animated",
											timeout : 4000
										});
										setTimeout(function() {
											window.location.href = "<?php echo APP_URL; ?>/login.php";
										}, 2000);
									}
									else {
										$.smallBox({
											title : "Error",
											content : "<i class='fa fa-warning'></i> <i>what happened? I don't know...</i>",
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
						}
					});

				});

			})
		</script>
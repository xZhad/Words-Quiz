<!DOCTYPE html>
<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
	<head>
		<meta charset="utf-8" />
		<title>Quiz</title>
		<meta name="keywords" content="HTML5,CSS3,Template" />
		<meta name="description" content="" />
		<meta name="Author" content="Andrea Sarpi" />

		<!-- mobile settings -->
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

		<!-- WEB FONTS -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet" type="text/css" />

		<!-- CORE CSS -->
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/font-awesome.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/animate.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/superslides.css" rel="stylesheet" type="text/css" />

		<!-- THEME CSS -->
		<link href="assets/css/essentials.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/layout.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/layout-responsive.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/color_scheme/orange.css" rel="stylesheet" type="text/css" /><!-- orange: default style -->

		<!-- COUNTDOWN CSS -->
		<link href="assets/css/countdown.css" rel="stylesheet" type="text/css" />

		<!-- Morenizr -->
		<script type="text/javascript" src="assets/plugins/modernizr.min.js"></script>
	</head>
	<body>

		<div class="special-page container">

			<div id="success" class="alert alert-success" style="width:500px;text-align:center;top:-60px;right:0px;position:fixed;">
				<i class="fa fa-check-circle"></i>
				<strong>Well done!</strong>
			</div>
			<div id="error" class="alert alert-danger" style="width:500px;text-align:center;top:-60px;left:0px;position:fixed;">
				<i class="fa fa-frown-o"></i>
				<strong>Oh snap!</strong>
			</div>

			<div id="game" class="row" style="display:none;">
				<div class="col-md-8 col-md-offset-2">
					<div style="display:inline-block;position:fixed;top:0;bottom:0;left:0;right:0;width:700px;height:360px;margin:auto;">
						<div class="center-box" style="margin-top:0px;!important;background-color:rgba(0,0,0,0.5)">

							<h1 id="statement" style="font-size:36px;text-transform:none;"></h1>

							<hr />

							<div class="row">

								<div class="col-md-6 text-center">
									<h2 id="ans-1-block">
										<a id="ans-1" class="btn btn-primary btn-lg" href="#" style="font-size:24px;"></a>
									</h2>
									<h1 id="wrong" style="display:none;">
										<i class="btn btn-danger btn-lg"><h1><strong id="wrong-count"></strong></h1></i>
									</h1>
								</div>

								<div class="col-md-6 text-center">
									<h2 id="ans-2-block">
										<a id="ans-2" class="btn btn-primary btn-lg" href="#" style="font-size:24px;"></a>
									</h2>
									<h1 id="correct" style="display:none;">
										<i class="btn btn-success btn-lg"><h1><strong id="correct-count"></strong></h1></i>
									</h1>
								</div>

							</div>

							<h2 id="progress" style="margin:0px;"></h2>

						</div>
					</div>
				</div>
			</div>

		</div>

		<section id="slider" class="nomargin-top fixed full-screen" data-autoplay="false">
			<ul class="slides-container">
				<li>
					<span class="overlay"></span>
					<div style="background-image: url('assets/images/covers/cover_1.jpg');" class="fullscreen-img"></div>
				</li>
				<li>
					<span class="overlay"></span>
					<div style="background-image: url('assets/images/covers/cover_2.jpg');" class="fullscreen-img"></div>
				</li>
				<li>
					<span class="overlay"></span>
					<div style="background-image: url('assets/images/covers/cover_3.jpg');" class="fullscreen-img"></div>
				</li>
				<li>
					<span class="overlay"></span>
					<div style="background-image: url('assets/images/covers/cover_4.jpg');" class="fullscreen-img"></div>
				</li>
			</ul>
		</section>

		<div id="preload" style="display:none;">
			<img src="assets/images/covers/cover_1.jpg" width="1" height="1" alt="Image 01" />
			<img src="assets/images/covers/cover_2.jpg" width="1" height="1" alt="Image 02" />
			<img src="assets/images/covers/cover_3.jpg" width="1" height="1" alt="Image 03" />
			<img src="assets/images/covers/cover_4.jpg" width="1" height="1" alt="Image 04" />
		</div>


		<!-- JAVASCRIPT FILES -->
		<script type="text/javascript" src="assets/plugins/jquery-2.0.3.min.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery.easing.1.3.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery.cookie.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery.appear.js"></script>
		<script type="text/javascript" src="assets/plugins/jquery.isotope.js"></script>

		<script type="text/javascript" src="assets/plugins/notification/SmartNotification.min.js"></script>
		<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script type="text/javascript" src="assets/plugins/superslides/dist/jquery.superslides.min.js"></script>

		<script type="text/javascript" src="assets/js/scripts.js"></script>

		<script type="text/javascript">

			var words = "";
			var index = 0;
			var option = 0;
			var wrongs = 0;
			var corrects = 0;
			var flag = 0;

			$('#slider').superslides({
				pagination: false,
				animation: 'fade'
			});

			$(document).ready(function() {

				$('#ans-1').on('click', function(e) {
					e.preventDefault();
					answer(1);
				});

				$('#ans-2').on('click', function(e) {
					e.preventDefault();
					answer(2);
				});

				load();

				prepare();

			});

			function prepare() {
				$('#slider').superslides('start');
				$('#slider').superslides('stop');

				document.getElementById("progress").innerHTML = (index+1)+"/"+(words.length);

				option = Math.floor((Math.random() * 2) + 1);

				document.getElementById("statement").innerHTML = words[index].statement;

				if(option == 1) {
					document.getElementById("ans-1").innerHTML = words[index].correct;
					document.getElementById("ans-2").innerHTML = words[index].wrong;
				} else {
					document.getElementById("ans-1").innerHTML = words[index].wrong;
					document.getElementById("ans-2").innerHTML = words[index].correct;
				}

				setTimeout(function() {
					$("#game").fadeTo(500,1);
				}, 500);
			}

			function answer(ans) {
				if(flag == 0) {
					flag = 1;
					if(ans == option) {
						corrects++;
						$("#success").css({top:-60}).animate({"top":"0px"}, 500);
						setTimeout(function() {
							$("#success").css({top:0}).animate({"top":"-60px"}, 500);
						}, 1500);
					} else {
						wrongs++;
						$("#error").css({top:-60}).animate({"top":"0px"}, 500);
						setTimeout(function() {
							$("#error").css({top:0}).animate({"top":"-60px"}, 500);
						}, 1500);
					}

					setTimeout(function() {
						$("#statement").fadeTo(500,0);
						$("#ans-1").fadeTo(500,0);
						$("#ans-2").fadeTo(500,0);
					}, 500);

					index++;

					setTimeout(function() {
						if(index < words.length) {

							document.getElementById("progress").innerHTML = (index+1)+"/"+(words.length);

							option = Math.floor((Math.random() * 2) + 1);

							document.getElementById("statement").innerHTML = words[index].statement;

							if(option == 1) {
								document.getElementById("ans-1").innerHTML = words[index].correct;
								document.getElementById("ans-2").innerHTML = words[index].wrong;
							} else {
								document.getElementById("ans-1").innerHTML = words[index].wrong;
								document.getElementById("ans-2").innerHTML = words[index].correct;
							}

							$('#slider').superslides('start');
							$('#slider').superslides('stop');
							$("#statement").fadeTo(500,1);
							$("#ans-1").fadeTo(500,1);
							$("#ans-2").fadeTo(500,1);

						} else {
							$('#ans-1-block').hide();
							$('#ans-2-block').hide();

							document.getElementById("statement").innerHTML = "¡Fin del juego!";
							document.getElementById("wrong-count").innerHTML = wrongs;
							document.getElementById("correct-count").innerHTML = corrects;

							$("#statement").fadeTo(1000,1);
							$('#wrong').fadeTo(1000,1);
							$('#correct').fadeTo(1000,1);
						}
					}, 1500);

					setTimeout(function() {
						flag = 0;
					}, 2200);

				}
			}

			function load() {
				$.ajax({
					type: 'GET',
					url: 'http://quiz.softllama.com/api/v1/game/words',
					dataType: 'json',
					async: false,
					success: function(data) {
						if(data.error == false) {
							words = data.data;
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

		</script>

	</body>
</html>
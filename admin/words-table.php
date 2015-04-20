<?php

//initilize the page
require_once("inc/init.php");

//require UI configuration (nav, ribbon, etc.)
require_once("inc/config.ui.php");

/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Tabla de Palabras";

if(!isset($_SESSION['username'])) {
	header("Location: " . APP_URL . "/login.php");
	die();
}

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
$page_css[] = "your_style.css";
include("inc/header.php");

//include left panel (navigation)
//follow the tree in inc/config.ui.php
$page_nav["dashboard"]["active"] = true;
include("inc/nav.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- MAIN PANEL -->
<div id="main" role="main">
	<?php
		//configure ribbon (breadcrumbs) array("name"=>"url"), leave url empty if no url
		//$breadcrumbs["New Crumb"] => "http://url.com"
		include("inc/ribbon.php");
	?>

	<!-- MAIN CONTENT -->
	<div id="content">

		<div class="row">
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
				<h1 class="page-title txt-color-blueDark">
					<i class="fa fa-home fa-fw "></i>Palabras <span>> Tabla</span>
				</h1>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
					<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">

						<header>
							<h2 style="width:100%;">
								<span class="widget-icon"> <i class="fa fa-table"></i> Palabras</span>
								<div class="input-group" style="float:right;margin-right:16px;">
								<a href="javascript:add();" class="btn btn-primary btn-xs" type="submit">
									<i class="fa fa-plus fa-lg fa-fw"></i> Nuevo
								</a>
							</div>
							</h2>
						</header>

						<div>
							<div class="widget-body no-padding">

								<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">

									<thead>
										<tr>
											<th>Editar</th>
											<th>Enunciado</th>
											<th>R. Correcta</th>
											<th>R. Incorrecta</th>
											<th>Fecha de Edición</th>
											<th>Editada Por</th>
											<th>Eliminar</th>
										</tr>
									</thead>

									<tbody id="table-body"></tbody>

								</table>

							</div>
							<!-- end widget content -->

						</div>
						<!-- end widget div -->

					</div>
			</div>
		</div>

		<div id="new-dialog" title="<div class='widget-header'><h4><i class='fa fa-plus'></i> Nueva Palabra</h4></div>">
			<form>
				<fieldset>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-question fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Enunciado" type="text" name="new-statement" id="new-statement">
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-check fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Correcta" type="text" name="new-correct" id="new-correct">
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-times fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Incorrecta" type="text" name="new-wrong" id="new-wrong">
					</div>
				</fieldset>
			</form>
		</div>

		<div id="edit-dialog" title="<div class='widget-header'><h4><i class='fa fa-pencil-square-o'></i> Editar Palabra</h4></div>">
			<form>
				<fieldset>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-question fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Enunciado" type="text" name="edit-statement" id="edit-statement">
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-check fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Correcta" type="text" name="edit-correct" id="edit-correct">
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-times fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Incorrecta" type="text" name="edit-wrong" id="edit-wrong">
					</div>
				</fieldset>
			</form>
		</div>

		<div id="delete-dialog" title="<div class='widget-header'><h4><i class='fa fa-times'></i> Eliminar Palabra</h4></div>">
			<form>
				<fieldset>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-question fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Enunciado" type="text" name="delete-statement" id="delete-statement" disabled>
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-check fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Correcta" type="text" name="delete-correct" id="delete-correct" disabled>
					</div>
					<br />
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-times fa-lg fa-fw"></i></span>
						<input class="form-control input-lg" placeholder="Incorrecta" type="text" name="delete-wrong" id="delete-wrong" disabled>
					</div>
				</fieldset>
			</form>
		</div>

	</div>
	<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- ==========================CONTENT ENDS HERE ========================== -->

<!-- PAGE FOOTER -->
<?php
	include("inc/footer.php");
?>
<!-- END PAGE FOOTER -->

<?php
	//include required scripts
	include("inc/scripts.php");
?>

<!-- PAGE RELATED PLUGIN(S)
<script src="..."></script>-->

<script>

	var time = 100;
	var body = "";
	var current = "";

	$(document).ready(function() {

		load();

		$.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
			_title : function(title) {
				if (!this.options.title) {
					title.html("&#160;");
				} else {
					title.html(this.options.title);
				}
			}
		}));

		$('#new-dialog').dialog({
			autoOpen : false,
			width : 600,
			resizable : false,
			modal : true,
			buttons : [{
				html : "<i class='fa fa-times'></i>&nbsp; Cancelar",
				"class" : "btn btn-default",
				click : function() {
					$('#new-statement').val("");
					$('#new-correct').val("");
					$('#new-wrong').val("");
					$(this).dialog("close");
				}
			}, {
				html : "<i class='fa fa-plus'></i>&nbsp; Agregar",
				"class" : "btn btn-primary",
				click : function() {
					if($('#new-statement').val() != "" && $('#new-correct').val() != "" && $('#new-wrong').val() != "") {
						$.ajax({
							type: 'POST',
							url: 'http://quiz.softllama.com/api/v1/words',
							beforeSend: function (request)
							{
								request.setRequestHeader("Auth", "<?php echo $_SESSION['api_key']; ?>");
							},
							dataType: 'json',
							data: {
								statement:	$('#new-statement').val(),
								correct:	$('#new-correct').val(),
								wrong:		$('#new-wrong').val()
							},
							async: true,
							success: function(data) {
								if(data.error == false) {
									$.smallBox({
										title : "Success",
										content : "<i class='fa fa-check'></i> <i>creada palabra " + data.data.correct + "</i>",
										color : "#659265",
										iconSmall : "fa fa-thumbs-up fa-2x fadeInRight animated",
										timeout : 4000
									});
									setTimeout(function() {
										body = body + "<tr><td style=\"text-align:center\"><a href=\"javascript:edit("+data.data.id+");\" class=\"btn btn-success btn-circle\"><i class=\"glyphicon glyphicon-edit\"></i></a></td><td>"+data.data.statement+"</td><td>"+data.data.correct+"</td><td>"+data.data.wrong+"</td><td>"+data.data.datetime+"</td><td>"+data.data.username+"</td><td style=\"text-align:center\"><a href=\"javascript:remove("+data.data.id+");\" class=\"btn btn-danger btn-circle\"><i class=\"glyphicon glyphicon-remove\"></i></a></td></tr>"
										document.getElementById('table-body').innerHTML = body;
									}, time*2);
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
					else {
						$.smallBox({
							title : "Error",
							content : "<i class='fa fa-warning'></i> <i>Todos los campos son obligatorios</i>",
							color : "#C46A69",
							iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
							timeout : 4000
						});
					}
					$('#new-statement').val("");
					$('#new-correct').val("");
					$('#new-wrong').val("");
					$(this).dialog("close");
				}
			}]
		});


		$('#edit-dialog').dialog({
			autoOpen : false,
			width : 600,
			resizable : false,
			modal : true,
			buttons : [{
				html : "<i class='fa fa-times'></i>&nbsp; Cancelar",
				"class" : "btn btn-default",
				click : function() {
					$('#edit-statement').val("");
					$('#edit-correct').val("");
					$('#edit-wrong').val("");
					$(this).dialog("close");
				}
			}, {
				html : "<i class='fa fa-plus'></i>&nbsp; Guardar",
				"class" : "btn btn-success",
				click : function() {
					if($('#edit-statement').val() != "" && $('#edit-correct').val() != "" && $('#edit-wrong').val() != "") {
						$.ajax({
							type: 'PUT',
							url: 'http://quiz.softllama.com/api/v1/words/'+current,
							beforeSend: function (request)
							{
								request.setRequestHeader("Auth", "<?php echo $_SESSION['api_key']; ?>");
							},
							dataType: 'json',
							data: {
								statement:	$('#edit-statement').val(),
								correct:	$('#edit-correct').val(),
								wrong:		$('#edit-wrong').val()
							},
							async: true,
							success: function(data) {
								if(data.error == false) {
									$.smallBox({
										title : "Success",
										content : "<i class='fa fa-check'></i> <i>palabra actualizada " + data.data.correct + "</i>",
										color : "#659265",
										iconSmall : "fa fa-thumbs-up fa-2x fadeInRight animated",
										timeout : 4000
									});
									setTimeout(function() {
										load();
									}, time*2);
								}
								else {
									$.smallBox({
										title : "Error",
										content : "<i class='fa fa-warning'></i> <i>what happened? I don't know...</i>",
										color : "#C46A69",
										iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
										timeout : 4000
									});
									$('#edit-statement').val("");
									$('#edit-correct').val("");
									$('#edit-wrong').val("");
									$('#edit-dialog').dialog("close");
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
								$('#edit-statement').val("");
								$('#edit-correct').val("");
								$('#edit-wrong').val("");
								$('#edit-dialog').dialog("close");
							}
						});
					}
					else {
						$.smallBox({
							title : "Error",
							content : "<i class='fa fa-warning'></i> <i>Todos los campos son obligatorios</i>",
							color : "#C46A69",
							iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
							timeout : 4000
						});
					}
					$('#edit-statement').val("");
					$('#edit-correct').val("");
					$('#edit-wrong').val("");
					$(this).dialog("close");
				}
			}]
		});

		$('#delete-dialog').dialog({
			autoOpen : false,
			width : 600,
			resizable : false,
			modal : true,
			buttons : [{
				html : "<i class='fa fa-times'></i>&nbsp; Cancelar",
				"class" : "btn btn-default",
				click : function() {
					$('#delete-statement').val("");
					$('#delete-correct').val("");
					$('#delete-wrong').val("");
					$(this).dialog("close");
				}
			}, {
				html : "<i class='fa fa-times'></i>&nbsp; Eliminar",
				"class" : "btn btn-danger",
				click : function() {
					if($('#delete-statement').val() != "" && $('#delete-correct').val() != "" && $('#delete-wrong').val() != "") {
						$.ajax({
							type: 'DELETE',
							url: 'http://quiz.softllama.com/api/v1/words/'+current,
							beforeSend: function (request)
							{
								request.setRequestHeader("Auth", "<?php echo $_SESSION['api_key']; ?>");
							},
							dataType: 'json',
							async: true,
							success: function(data) {
								if(data.error == false) {
									$.smallBox({
										title : "Success",
										content : "<i class='fa fa-check'></i> <i>palabra eliminada " + $('#delete-correct').val() + "</i>",
										color : "#659265",
										iconSmall : "fa fa-thumbs-up fa-2x fadeInRight animated",
										timeout : 4000
									});
									setTimeout(function() {
										load();
									}, time*2);
								}
								else {
									$.smallBox({
										title : "Error",
										content : "<i class='fa fa-warning'></i> <i>what happened? I don't know...</i>",
										color : "#C46A69",
										iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
										timeout : 4000
									});
									$('#delete-statement').val("");
									$('#delete-correct').val("");
									$('#delete-wrong').val("");
									$('#delete-dialog').dialog("close");
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
								$('#delete-statement').val("");
								$('#delete-correct').val("");
								$('#delete-wrong').val("");
								$('#delete-dialog').dialog("close");
							}
						});
					}
					else {
						$.smallBox({
							title : "Error",
							content : "<i class='fa fa-warning'></i> <i>Todos los campos son obligatorios</i>",
							color : "#C46A69",
							iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
							timeout : 4000
						});
					}
					$('#delete-statement').val("");
					$('#delete-correct').val("");
					$('#delete-wrong').val("");
					$(this).dialog("close");
				}
			}]
		});

	});

	function add() {
		$('#new-statement').val("");
		$('#new-correct').val("");
		$('#new-wrong').val("");
		$('#new-dialog').dialog("open");
		return false;
	}

	function edit(id) {
		current = id;
		$.ajax({
			type: 'GET',
			url: 'http://quiz.softllama.com/api/v1/words/'+id,
			beforeSend: function (request)
			{
				request.setRequestHeader("Auth", "<?php echo $_SESSION['api_key']; ?>");
			},
			dataType: 'json',
			async: true,
			success: function(data) {
				if(data.error == false) {
					$('#edit-statement').val(data.data.statement);
					$('#edit-correct').val(data.data.correct);
					$('#edit-wrong').val(data.data.wrong);
					$('#edit-dialog').dialog("open");
				}
				else {
					$.smallBox({
						title : "Error",
						content : "<i class='fa fa-warning'></i> <i>what happened? I don't know...</i>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
						timeout : 4000
					});
					$('#edit-statement').val("");
					$('#edit-correct').val("");
					$('#edit-wrong').val("");
					$('#edit-dialog').dialog("close");
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
				$('#edit-statement').val("");
				$('#edit-correct').val("");
				$('#edit-wrong').val("");
				$('#edit-dialog').dialog("close");
			}
		});
		return false;
	}

	function remove(id) {
		current = id;
		$.ajax({
			type: 'GET',
			url: 'http://quiz.softllama.com/api/v1/words/'+id,
			beforeSend: function (request)
			{
				request.setRequestHeader("Auth", "<?php echo $_SESSION['api_key']; ?>");
			},
			dataType: 'json',
			async: true,
			success: function(data) {
				if(data.error == false) {
					$('#delete-statement').val(data.data.statement);
					$('#delete-correct').val(data.data.correct);
					$('#delete-wrong').val(data.data.wrong);
					$('#delete-dialog').dialog("open");
				}
				else {
					$.smallBox({
						title : "Error",
						content : "<i class='fa fa-warning'></i> <i>what happened? I don't know...</i>",
						color : "#C46A69",
						iconSmall : "fa fa-thumbs-down fa-2x fadeInRight animated",
						timeout : 4000
					});
					$('#delete-statement').val("");
					$('#delete-correct').val("");
					$('#delete-wrong').val("");
					$('#delete-dialog').dialog("close");
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
				$('#delete-statement').val("");
				$('#delete-correct').val("");
				$('#delete-wrong').val("");
				$('#delete-dialog').dialog("close");
			}
		});
		return false;
	}

	function load() {
		$.ajax({
			type: 'GET',
			url: 'http://quiz.softllama.com/api/v1/words',
			beforeSend: function (request)
			{
				request.setRequestHeader("Auth", "<?php echo $_SESSION['api_key']; ?>");
			},
			dataType: 'json',
			data: {
				username:	"<?php echo $_SESSION['username']; ?>"
			},
			async: true,
			success: function(data) {
				if(data.error == false) {
					body = "";
					$.each(data.data, function(index, value) {
						setTimeout(function() {
							body = body + "<tr><td style=\"text-align:center\"><a href=\"javascript:edit("+value.id+");\" class=\"btn btn-success btn-circle\"><i class=\"glyphicon glyphicon-edit\"></i></a></td><td>"+value.statement+"</td><td>"+value.correct+"</td><td>"+value.wrong+"</td><td>"+value.datetime+"</td><td>"+value.username+"</td><td style=\"text-align:center\"><a href=\"javascript:remove("+value.id+");\" class=\"btn btn-danger btn-circle\"><i class=\"glyphicon glyphicon-remove\"></i></a></td></tr>"
							document.getElementById('table-body').innerHTML = body;
						}, time*(index+1));
					});
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

<?php
	//include footer
	include("inc/google-analytics.php");
?>
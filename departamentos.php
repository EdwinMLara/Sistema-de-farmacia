<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
	}

	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	$active_facturas="";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="";
	$active_departamentos="active";
	$active_reportes_entradas="";
	$title="Usuarios | Simple Invoice";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<?php include("head.php");?>
  </head>
  <body>
 	<?php
	include("navbar.php");
	?> 
    <div class="container">
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="btn-group pull-right">
					<button type='button' class="btn btn-info" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus" ></span> Nuevo Departamento</button>
				</div>
				<h4><i class="glyphicon glyphicon-search"></i> Buscar Departamentos</h4>
			</div>			
			<div class="panel-body">
				<?php
					include("modal/registro_depto.php");
					include("modal/editar_depto.php");
				?>
				<form class="form-horizontal" role="form" id="datos_cotizacion">
					<div class="form-group row">
						<label for="q" class="col-md-2 control-label">Departamento:</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="q" placeholder="Departamento" onkeyup='load(1);'>
						</div>
						<div class="col-md-3">
							<button type="button" class="btn btn-default" onclick='load(1);'>
								<span class="glyphicon glyphicon-search" ></span> Buscar</button>
								<span id="loader"></span>
						</div>
					</div>
				</form>
				<div id="resultados"></div><!-- Carga los datos ajax -->
				<div class='outer_div'></div><!-- Carga los datos ajax -->
			</div>
		</div>
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/departamentos.js"></script>
  </body>
</html>
<script>
	$( "#guardar_departamento" ).submit(function( event ) {
		$('#guardar_datos').attr("disabled", true);
		var parametros = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "ajax/nuevo_departamento.php",
			data: parametros,
			beforeSend: function(objeto){
				$("#resultados_ajax").html('<img src="img/ajax-loader.gif"> Cargando...');
			},
			success: function(datos){
				$("#resultados_ajax").html(datos);
				$('#guardar_datos').attr("disabled", false);
				load(1);
			}
		});
		event.preventDefault();
	})

	$( "#editar_departamento" ).submit(function( event ) {
		$('#actualizar_datos2').attr("disabled", true);
		var parametros = $(this).serialize();
		$.ajax({
			type: "POST",
			url: "ajax/editar_departamento.php",
			data: parametros,
			beforeSend: function(objeto){
				$("#resultados_ajax2").html('<img src="img/ajax-loader.gif"> Cargando...');
			},
			success: function(datos){
				$("#resultados_ajax2").html(datos);
				$('#actualizar_datos2').attr("disabled", false);
				load(1);
			}
		});
	  event.preventDefault();
	})

	function obtener_datos(id){
		var nombre = $("#nombre"+id).val();
			
		$("#mod_id").val(id);
		$("#nombre2").val(nombre);
	}
</script>
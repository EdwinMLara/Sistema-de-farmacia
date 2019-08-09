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
	$active_reportes_salidas="active";
	$title="Reportes de Salidas | Simple Invoice";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("head.php");?>
	</head>
	<body>
		<?php	include("navbar.php");	?>
		<div class="container">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".reporte-salidas"><i class="glyphicon glyphicon-list-alt"></i> Generar Reporte</button>
						<?php
							include("modal/reporte_salidas.php");
						?>
					</div>
					<h4><i class="glyphicon glyphicon-search"></i> Buscar Salida de Producto</h4>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" id="datos_cotizacion">
						<div class="form-group row">
							<label for="q" class="col-md-2 control-label">Buscar Salida de Producto</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="q" placeholder="Buscar Salida de Producto" onkeyup='load(1);'>
							</div>
							<div class="col-md-3">
								<button type="button" class="btn btn-info" onclick='load(1);'>
									<span class="glyphicon glyphicon-search" ></span> Buscar
								</button>
								<span id="loader"></span>
							</div>
						</div>
					</form>
					<div id="resultados"></div><!-- Carga los datos ajax -->
					<div class="outer_div"></div><!-- Carga los datos ajax -->
				</div>
			</div>		 
		</div>
		<hr>
		<?php	include("footer.php");	?>
		<script type="text/javascript" src="js/VentanaCentrada.js"></script>
		<script type="text/javascript" src="js/reporte_salidas.js"></script>
		<script language="javascript" type="text/javascript">			
			$("#datos_reporte").submit(function(){
				var date1 = $("#date1").val();
				var date2 = $("#date2").val();
					  
				if (date1==""){
					alert("Debes seleccionar la fecha inicial");
					$("#date1").focus();
					return false;
				}else if (date2==""){
					alert("Debes seleccionar la fecha final");
					$("#date2").focus();
					return false;
				}
				VentanaCentrada('./pdf/documentos/reporte_salidas_pdf.php?date1='+date1+'&date2='+date2,'Reporte_Entradas','','1024','768','true');
			})
		</script>
	</body>
</html>
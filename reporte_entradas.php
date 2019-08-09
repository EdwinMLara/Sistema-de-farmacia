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
	$active_reportes_entradas="active";
	$title="Reportes de Entradas | Simple Invoice";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("head.php");?>
		<link rel="stylesheet" href="css/select2.css">
	</head>
	<body>
		<?php	include("navbar.php");	?>
		<div class="container">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".reporte-entradas"><i class="glyphicon glyphicon-list-alt"></i> Generar Reporte</button>
						<?php
							include("modal/reporte_entradas.php");
						?>
						<!-- -->
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#nuevaEntrada">
							<span class="glyphicon glyphicon-plus"></span> Entradas de Producto
						</button>
						<div class="modal fade" id="nuevaEntrada" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-edit"></i> Agregar nuevo producto</h4>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" method="post" id="guardar_producto" name="guardar_producto">
											<div id="resultados_ajax_productos"></div>
											<div class="form-group">
												<label for="codigo_producto" class="col-sm-3 control-label">Producto</label>
												<div class="col-sm-8">
													<select name="codigo_producto" id="codigo_producto" style="width:300px;" required>
														<option></option>
														<?php
															$sqlp=mysqli_query($con,"SELECT * FROM products");
															while($rowp=mysqli_fetch_array($sqlp)){
														?>
														<option value="<?php echo $rowp['codigo_producto']; ?>"><?php echo $rowp['nombre_producto']; ?></option>
														<?php }	?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="cantidad" class="col-sm-3 control-label">Cantidad</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad del producto" required pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8">
												</div>
											</div>
											<div class="form-group">
												<label for="precio" class="col-sm-3 control-label">Precio</label>
												<div class="col-sm-8">
													<input type="text" class="form-control" id="precio" name="precio" placeholder="Precio del producto" required pattern="^[0-9]{1,5}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="8">
												</div>
											</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
										<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
									</div>
										</form>
								</div>
							</div>
						</div>
					</div>
					<h4><i class="glyphicon glyphicon-search"></i> Buscar Entrada de Producto</h4>
				</div>
				<div class="panel-body">
					<form class="form-horizontal" role="form" id="datos_cotizacion">
						<div class="form-group row">
							<label for="q" class="col-md-2 control-label">Buscar Entrada de Producto</label>
							<div class="col-md-5">
								<input type="text" class="form-control" id="q" placeholder="Buscar Entrada de Producto" onkeyup='load(1);'>
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
		<script type="text/javascript" src="js/reporte_entradas.js"></script>
		<script type="text/javascript" src="js/select2.js"></script>
		<script>
			$(document).ready(function(){
				$("#codigo_producto").select2();
			});
		</script>
		<script language="javascript" type="text/javascript">
			$( "#guardar_producto" ).submit(function( event ) {
				$('#guardar_datos').attr("disabled", true);  
				var parametros = $(this).serialize();
				$.ajax({
					type: "POST",
					url: "ajax/nuevo_reavastecimiento.php",
					data: parametros,
					beforeSend: function(objeto){
						$("#resultados_ajax_productos").html("Mensaje: Cargando...");
					},
					success: function(datos){
						$("#resultados_ajax_productos").html(datos);
						$('#guardar_datos').attr("disabled", false);
						load(1);
					}
				});
				event.preventDefault();
			})
			
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
				VentanaCentrada('./pdf/documentos/reporte_entradas_pdf.php?date1='+date1+'&date2='+date2,'Reporte_Entradas','','1024','768','true');
			})
		</script>
	</body>
</html>
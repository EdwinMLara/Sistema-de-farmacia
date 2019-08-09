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
	$active_reportes_entradas="";
	$title="Reportes de Entradas | Simple Invoice";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("head.php");?>
	</head>
	<body>
		<?php include("navbar.php");	?>
		<div class="container">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4><i class="glyphicon glyphicon-search"></i> Entregas de Productos</h4>
				</div>
				<div class="panel-body">
					<?php
						if(!$_POST){
					?>
					<form class="form-horizontal" role="form" action="reporte_entrega.php" method="POST">
						<div class="form-inline">
							<h3 class="text-primary">Generar Reporte </h3>
							<table style="width: 100%">
								<tr>
									<td>
										<label>Desde:</label>
										<input type="date" class="form-control" placeholder="Inicio" name="date1"/>
									</td>
									<td>
										<label>Hasta</label>
										<input type="date" class="form-control" placeholder="Final" name="date2"/>
									</td>
									<td>
										<button type="submit" class="btn btn-success">
											<span class="glyphicon glyphicon-print"></span> Generar Reporte
										</button>
									</td>
								</tr>
							</table>
						</div>
					</form>
					<?php
						}else{
							$date1=$_POST['date1'];
							$date2=$_POST['date2'];
							$sql=mysqli_query($con,"SELECT id_factura,numero_factura,fecha_factura,id_cliente FROM facturas WHERE  fecha_factura BETWEEN '$date1' AND '$date2'");
					?>
					<form class="form-horizontal" role="form" action="reporte_entrega.php" method="POST">
						<div class="form-inline">
							<h3 class="text-primary">Generar Reporte </h3>
							<table style="width: 100%">
								<tr>
									<td>
										<label>Desde:</label>
										<input type="date" class="form-control" placeholder="Inicio" name="date1" value="<?php echo $date1 ?>">
									</td>
									<td>
										<label>Hasta</label>
										<input type="date" class="form-control" placeholder="Final" name="date2" value="<?php echo $date2 ?>">
									</td>
									<td>
										<button type="submit" class="btn btn-success">
											<span class="glyphicon glyphicon-list-alt"></span> Generar Reporte
										</button>
									</td>
								</tr>
							</table>
						</div>
					</form>
					<hr>
					<form class="form-horizontal" method="post" id="datos_reporte" name="datos_reporte">
						<?php
							$date1=$_POST['date1'];
							$date2=$_POST['date2'];
						?>
						<input type="hidden" class="form-control" placeholder="Inicio" name="date1" id="date1" value="<?php echo date($date1); ?>">
						<input type="hidden" class="form-control" placeholder="Final" name="date2" id="date2" value="<?php echo date($date2); ?>">
						<button type="submit" class="btn btn-info" id="imprimir_reporte">
							<span class="glyphicon glyphicon-print" ></span> Imprimir
						</button>
					</form>
					<hr>
					<?php
						while($rowf=mysqli_fetch_array($sql)){
							$id_cliente=$rowf['id_cliente'];
							$numero_factura=$rowf['numero_factura'];
							$sqlc=mysqli_query($con,"SELECT * FROM clientes WHERE id_cliente=$id_cliente");
							while($rowc=mysqli_fetch_array($sqlc)){
								$depto_id=$rowc['depto_id'];
								$sqld=mysqli_query($con,"SELECT depto_id, nombre FROM departamentos WHERE depto_id=$depto_id");
								while($rowd=mysqli_fetch_array($sqld)){
					?>
							<table style="width: 100%;" border="2px">
								<tr>
									<td style="width: 5%; text-align: center"><strong>Entrega:</strong></td>
									<td style="width: 5%; text-align: center"><?php echo $rowf['numero_factura']; ?></td>
									<td style="width: 15%; text-align: center"><strong>Nombre Cliente:</strong></td>
									<td style="width: 20%; text-align: center"><?php echo $rowc['nombre_cliente']; ?></td>
									<td style="width: 10%; text-align: center"><strong>Departamento:</strong></td>
									<td style="width: 20%; text-align: center"><?php echo $rowd['nombre']; ?></td>
									<td style="width: 10%; text-align: center"><strong>Fecha Entrega:</strong></td>
									<td style="width: 10%; text-align: center"><?php echo  $rowf['fecha_factura']; ?></td>
								</tr>
							</table>
							
							<table style="width: 100%;">
								<tr style="font-weight: bold;">
									<td style="width: 10%; text-align: center">Codigo:</td>
									<td style="width: 40%; text-align: center">Producto:</td>
									<td style="width: 50%; text-align: center">Cantidad:</td>
								</tr>
								<?php
									$sqldp=mysqli_query($con,"SELECT * FROM detalle_factura WHERE numero_factura='$numero_factura'");
									while($rowdp=mysqli_fetch_array($sqldp)){
										$id_producto=$rowdp['id_producto'];
										$sqlp=mysqli_query($con,"SELECT * FROM products WHERE id_producto=$id_producto");
										while($rowp=mysqli_fetch_array($sqlp)){
								?>
								<tr>
									<td style="text-align: center"><?php echo $rowp['codigo_producto']; ?></td>
									<td style="text-align: center"><?php echo $rowp['nombre_producto']; ?></td>
									<td style="text-align: center"><?php echo $rowdp['cantidad']; ?></td>
								</tr>
								<?php
										}
									}
								?>
							</table>
					<?php
							
								}
							}
						}
					} //FIN ELSE
					?>
				</div>
			</div>
		</div>
		<hr>
		<?php	include("footer.php");	?>
		<script type="text/javascript" src="js/VentanaCentrada.js"></script>
		<script type="text/javascript" src="js/reporte_entradas.js"></script>
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
				VentanaCentrada('./pdf/documentos/reporte_entregas_pdf.php?date1='+date1+'&date2='+date2,'Reporte_Entregas','','1024','768','true');
			})
		</script>
	</body>
</html>
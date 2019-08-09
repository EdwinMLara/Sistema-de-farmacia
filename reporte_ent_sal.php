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
	$title="Reporte de Entradas - Salidas | Simple Invoice";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include("head.php"); ?>
	</head>
	<body>
		<?php include("navbar.php"); ?>
		<div class="container">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h4><i class="glyphicon glyphicon-search"></i>Reporte de Entradas - Salidas</h4>
				</div>
				<div class="panel-body">
					<?php
						if(!$_POST){
					?>
					<form class="form-horizontal" role="form" action="reporte_ent_sal.php" method="POST">
						<div class="form-inline">
							<h3 class="text-primary">Generar Reporte </h3>
							<hr style="border-top:1px dotted #000;"/>
							<label>Producto:</label>
							<select class="form-control" name="id_producto">
								<option value="0">-- Seleccione Un Producto --</option>
								<?php
									$sqlp=mysqli_query($con,"SELECT * FROM products");
									while($row=mysqli_fetch_array($sqlp)){
										echo '<option value='.$row['id_producto'].'>'.$row['nombre_producto'].'</option>';
									}
								?>
							</select>
							<label>Desde:</label>
							<input type="date" class="form-control" placeholder="Inicio" name="date1"/>
							<label>Hasta</label>
							<input type="date" class="form-control" placeholder="Final" name="date2"/>
							<button type="submit" class="btn btn-success">
								<span class="glyphicon glyphicon-print"></span> Generar Reporte
							</button>
						</div>
					</form>
					<?php
						}else{
							$id_producto=$_POST['id_producto'];
							$date1=$_POST['date1'];
							$date2=$_POST['date2'];
							if ($id_producto==0) {
								$sqlproducto="SELECT * FROM products WHERE date_added BETWEEN '$date1' AND '$date2'";
							}else{
								$sqlproducto="SELECT * FROM products WHERE id_producto=$id_producto AND date_added BETWEEN '$date1' AND '$date2'";
							}
					?>
						<form class="form-horizontal" role="form" action="reporte_ent_sal.php" method="POST">
							<div class="form-inline">
								<h3 class="text-primary">Generar Reporte </h3>
								<hr style="border-top:1px dotted #000;"/>
								<label>Producto:</label>
								<select class="form-control" name="id_producto">
									<option value="0">-- Seleccione Un Producto --</option>
									<?php
										$sqlp=mysqli_query($con,"SELECT * FROM products");
										while($row=mysqli_fetch_array($sqlp)){
											if ( $row['id_producto'] == $id_producto ){
												echo "<option value='".$row['id_producto']."'selected='selected'>".$row['nombre_producto']."</option>";
											}else {
												echo "<option value='".$row['id_producto']."'>".$row['nombre_producto']."</option>";
											}
										}
									?>
								</select>
								<label>Desde:</label>
								<input type="date" class="form-control" placeholder="Inicio" name="date1" value="<?php echo date($date1); ?>">
								<label>Hasta</label>
								<input type="date" class="form-control" placeholder="Final" name="date2" value="<?php echo date($date2); ?>">
								<button type="submit" class="btn btn-success">
									<span class="glyphicon glyphicon-list-alt"></span> Generar Reporte
								</button>
							</div>
						</form>
						<hr>
						<form class="form-horizontal" method="post" id="datos_reporte" name="datos_reporte">
							<?php
								$id_producto=$_POST['id_producto'];
								$date1=$_POST['date1'];
								$date2=$_POST['date2'];
							?>
							<input type="hidden" class="form-control" placeholder="Producto" name="id_producto" id="id_producto" value="<?php echo $id_producto; ?>">
							<input type="hidden" class="form-control" placeholder="Inicio" name="date1" id="date1" value="<?php echo date($date1); ?>">
							<input type="hidden" class="form-control" placeholder="Final" name="date2" id="date2" value="<?php echo date($date2); ?>">
							<button type="submit" class="btn btn-success" id="imprimir_reporte">
								<span class="glyphicon glyphicon-print" ></span> Imprimir
							</button>
						</form>
							<table class="table">
								<tr>
									<td><strong>Producto</strong></td>
									<td><strong>Entradas</strong></td>
									<td><strong>Salidas</strong></td>
									<td><strong>Inventario</strong></td>
								</tr>
								<?php
									$sumatoria_entrada=0;
									$sumatoria_salida=0;
									$queryp=mysqli_query($con, $sqlproducto);
									while($row=mysqli_fetch_array($queryp)){
										$nombre_producto=$row['nombre_producto'];
										$codigo_producto=$row['codigo_producto'];
										$queryen=mysqli_query($con, "SELECT p.id_producto, p.codigo_producto, dp.codigo_producto, dp.status, sum(dp.cantidad) AS total_entrada FROM detalle_productos dp, products p WHERE dp.codigo_producto=p.codigo_producto AND p.codigo_producto=$codigo_producto AND dp.status='1'");
										while($rowen=mysqli_fetch_array($queryen)){
											$total_entrada=$rowen['total_entrada'];
											$querysal=mysqli_query($con, "SELECT p.id_producto, p.codigo_producto, dp.codigo_producto, dp.status, sum(dp.cantidad) AS total_salida FROM detalle_productos dp, products p WHERE dp.codigo_producto=p.codigo_producto AND p.codigo_producto=$codigo_producto AND dp.status='2'");
											while($rowsal=mysqli_fetch_array($querysal)){
												$total_salida=$rowsal['total_salida'];
								?>
								<tr>
									<td><?php echo $nombre_producto; ?></td>
									<td><?php echo $total_entrada; ?></td>
									<td><?php echo $total_salida; ?></td>
									<td><?php echo $total_entrada-$total_salida; ?></td>
								</tr>
								<?php
											$sumatoria_entrada+=$total_entrada;
											$sumatoria_salida+=$total_salida;
											}
										}
									}
									$total_entrada=$sumatoria_entrada;
									$total_salida=$sumatoria_salida;
									
								?>
								<tr>
									<td><strong>Totales:</strong></td>
									<td><strong><?php echo $total_entrada ?></strong></td>
									<td><strong><?php echo $total_salida ?></strong></td>
									<td><strong><?php echo $diferencia=$total_entrada-$total_salida ?></strong></td>
								</tr>
							</table>
					<?php
						}
					?>
				</div>
			</div>
		</div>
		<hr>
		<?php include("footer.php"); ?>
		<script type="text/javascript" src="js/VentanaCentrada.js"></script>
		<script language="javascript" type="text/javascript">
			$("#datos_reporte").submit(function(){
				var date1 = $("#date1").val();
				var date2 = $("#date2").val();
				var id_producto = $("#id_producto").val();
				VentanaCentrada('./pdf/documentos/reporte_ent_sal_pdf.php?date1='+date1+'&date2='+date2+'&id_producto='+id_producto,'Reporte','','1024','768','true');
			});
		</script>
	</body>
</html>
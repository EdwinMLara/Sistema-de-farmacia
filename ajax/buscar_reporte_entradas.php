<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	//Archivo de funciones PHP
	include("../funciones.php");
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	/*if (isset($_GET['id'])){
		$id_producto=intval($_GET['id']);
		$query=mysqli_query($con, "select * from detalle_factura where id_producto='".$id_producto."'");
		$count=mysqli_num_rows($query);
		if ($count==0){
			if ($delete1=mysqli_query($con,"DELETE FROM products WHERE id_producto='".$id_producto."'")){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente.
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
			</div>
			<?php
			
		}
			
		} else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se pudo eliminar éste  producto. Existen cotizaciones vinculadas a éste producto. 
			</div>
			<?php
		}
	}*/
	/********************************************************/
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$sTable = "products, detalle_productos";
		//$sWhere = "";
		$sWhere = "WHERE products.codigo_producto=detalle_productos.codigo_producto AND detalle_productos.status='1'";
		if ($_GET['q'] != "" ){
			$sWhere.= "AND (products.nombre_producto LIKE '%$q%')";
		}
		
		//$sWhere= "ORDER BY detalle_productos.detalle_date_added ASC";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './buscar_reporte_entradas.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			?>
			<div class="table-responsive">
				<table class="table">
					<tr class="info">
						<th>Código</th>
						<th>Producto</th>
						<th>Estado</th>
						<th>Cantidad</th>
						<th>Agregado</th>
						<th>Precio Entrada</th>
					</tr>
					<?php
					while ($row=mysqli_fetch_array($query)){
						$codigo_producto=$row['codigo_producto'];
						$nombre_producto=$row['nombre_producto'];
						$status_producto=$row['status'];
						if ($status_producto==1){$estado="Entrada";}
						else {$estado="Salida";}
						$cantidad=$row['cantidad'];
						$detalle_date_added= date('d/m/Y', strtotime($row['detalle_date_added']));
						$precio=$row['precio'];
					?>
					<tr>
						<td><?php echo $codigo_producto; ?></td>
						<td><?php echo $nombre_producto; ?></td>
						<td><?php echo $estado;?></td>
						<td><?php echo $cantidad;?></td>
						<td><?php echo $detalle_date_added;?></td>
						<td><?php echo $precio ;?></td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="6">
							<span class="pull-right">
								<?php
									echo paginate($reload, $page, $total_pages, $adjacents);
								?>
							</span>
						</td>
					</tr>
				</table>
			</div>
			<?php
		}
	}
?>
<?php
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	//Archivo de funciones PHP
	include("../funciones.php");
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';

	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$aColumns = array(
			'facturas.id_factura', 'facturas.numero_factura', 'facturas.fecha_factura', 'facturas.id_cliente',
			'clientes.id_cliente', 'clientes.nombre_cliente', 'clientes.depto_id',
			'departamentos.depto_id', 'departamentos.nombre',
			'detalle_factura.numero_factura', 'detalle_factura.id_producto', 'detalle_factura.cantidad',
			'products.id_producto', 'products.nombre_producto');
		$sTable = "facturas, clientes, departamentos, detalle_factura, products";
		$sWhere = "";
		/*$sWhere = "WHERE
			(facturas.id_cliente=clientes.id_cliente) AND 
			(clientes.depto_id=departamentos.depto_id) AND
			(facturas.numero_factura=detalle_factura.numero_factura) AND
			(detalle_factura.id_producto=products.id_producto)";
		
		if ($_GET['q'] != "" ){
			$sWhere.= "AND (products.nombre_producto LIKE '%$q%')";
		}*/
		if ( $_GET['q'] != "" ){
			$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns) ; $i++ ){
					$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
				}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ') AND';
			$sWhere .= "
				(facturas.id_cliente=clientes.id_cliente) AND 
				(clientes.depto_id=departamentos.depto_id) AND
				(facturas.numero_factura=detalle_factura.numero_factura) AND
				(detalle_factura.id_producto=products.id_producto)";
		}else{
			$sWhere = " WHERE
				(facturas.id_cliente=clientes.id_cliente) AND 
				(clientes.depto_id=departamentos.depto_id) AND
				(facturas.numero_factura=detalle_factura.numero_factura) AND
				(detalle_factura.id_producto=products.id_producto)";
		}
		$sWhere.=" ORDER BY facturas.fecha_factura DESC";
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
						<th>Código		</th>
						<th>Producto	</th>
						<th>Estado		</th>
						<th>Cantidad	</th>
						<th>Salida		</th>
						<th>Factura		</th>
						<th>Cliente		</th>
						<th>Departamento</th>
					</tr>
					<?php
					while ($row=mysqli_fetch_array($query)){
						$codigo_producto=$row['codigo_producto'];
						$nombre_producto=$row['nombre_producto'];
						$status_producto=2;
						if ($status_producto==1){$estado="Entrada";}
						else {$estado="Salida";}
						$cantidad=$row['cantidad'];
						$fecha_factura= date('d/m/Y', strtotime($row['fecha_factura']));
						$numero_factura=$row['numero_factura'];
						$nombre_cliente=$row['nombre_cliente'];
						$nombre_depto=$row['nombre'];
					?>
					<tr>
						<td><?php echo $codigo_producto; ?></td>
						<td><?php echo $nombre_producto; ?></td>
						<td><?php echo $estado;?></td>
						<td><?php echo $cantidad;?></td>
						<td><?php echo $fecha_factura;?></td>
						<td><?php echo $numero_factura ;?></td>
						<td><?php echo $nombre_cliente ;?></td>
						<td><?php echo $nombre_depto ;?></td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td colspan="8">
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
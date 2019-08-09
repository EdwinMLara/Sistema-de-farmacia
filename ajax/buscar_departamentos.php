<?php
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$depto_id=intval($_GET['id']);
		$query=mysqli_query($con, "select * from departamentos where depto_id='".$depto_id."'");
		$rw_user=mysqli_fetch_array($query);
		$count=$rw_user['depto_id'];
		if ($depto_id!=1){
			if ($delete1=mysqli_query($con,"DELETE FROM departamentos WHERE depto_id='".$depto_id."'")){
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
				<strong>Error!</strong> No se puede borrar el usuario administrador. 
			</div>
			<?php
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$aColumns = array('nombre');//Columnas de busqueda
		$sTable = "departamentos";
		$sWhere = "";
		if ( $_GET['q'] != "" ){
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ ){
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		$sWhere.=" order by depto_id desc";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './departamentos.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
?>
			<div class="table-responsive">
				<table class="table">
					<tr class="info">
						<th>ID</th>
						<th>Nombres</th>
						<th><span class="pull-right">Acciones</span></th>
					</tr>
					<?php
						while ($row=mysqli_fetch_array($query)){
							$depto_id=$row['depto_id'];
							$nombre=$row['nombre'];
					?>
					<input type="hidden" value="<?php echo $row['nombre'];?>" id="nombre<?php echo $depto_id;?>">
					<tr>
						<td><?php echo $depto_id; ?></td>
						<td><?php echo $nombre; ?></td>
						<td ><span class="pull-right">
							<a href="#" class='btn btn-default' title='Editar departamento' onclick="obtener_datos('<?php echo $depto_id;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a> 
							<a href="#" class='btn btn-default' title='Borrar departamento' onclick="eliminar('<? echo $depto_id; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span>
						</td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=9><span class="pull-right">
					<?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>
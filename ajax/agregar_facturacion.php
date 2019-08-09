<?php

	include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	$session_id= session_id();
	if (isset($_POST['id'])){$id=$_POST['id'];}
	if (isset($_POST['cantidad'])){$cantidad=$_POST['cantidad'];}

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	//Archivo de funciones PHP
	include("../funciones.php");
	if (!empty($id) and !empty($cantidad)){
		$insert_tmp=mysqli_query($con, "INSERT INTO tmp (id_producto,cantidad_tmp,session_id) VALUES ('$id','$cantidad','$session_id')");
	}
	if (isset($_GET['id'])){//codigo elimina un elemento del array
		$id_tmp=intval($_GET['id']);	
		$delete=mysqli_query($con, "DELETE FROM tmp WHERE id_tmp='".$id_tmp."'");
	}
?>
	<table class="table">
		<tr>
			<th class="text-center">CODIGO</th>
			<th class="text-center">CANT.</th>
			<th>DESCRIPCION</th>
			<th></th>
		</tr>
	<?php
		$sql=mysqli_query($con, "select * from products, tmp where products.id_producto=tmp.id_producto and tmp.session_id='".$session_id."'");
		while ($row=mysqli_fetch_array($sql)){
		$id_tmp=$row["id_tmp"];
		$codigo_producto=$row['codigo_producto'];
		$cantidad=$row['cantidad_tmp'];
		$nombre_producto=$row['nombre_producto'];
	?>
		<tr>
			<td class='text-center'><?php echo $codigo_producto;?></td>
			<td class='text-center'><?php echo $cantidad;?></td>
			<td><?php echo $nombre_producto;?></td>
			<td class='text-center'><a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></td>
		</tr>		
	<?php
		}
	?>
	</table>
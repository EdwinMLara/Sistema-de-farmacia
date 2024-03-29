<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['codigo_producto'])) {
           $errors[] = "Código vacío";
        }  else if (empty($_POST['cantidad'])){
			$errors[] = "Cantidad del producto vacío";
		}else if (empty($_POST['precio'])){
			$errors[] = "Precio vacío";
		} else if (
			!empty($_POST['codigo_producto']) &&
			!empty($_POST['cantidad']) &&
			!empty($_POST['precio'])
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$codigo=mysqli_real_escape_string($con,(strip_tags($_POST["codigo_producto"],ENT_QUOTES)));
		$precio=floatval($_POST['precio']);
		$cantidad=intval($_POST['cantidad']);
		$date_added=date("Y-m-d H:i:s");
		//$sql="INSERT INTO products (codigo_producto, nombre_producto, status_producto, date_added, inventary_min) VALUES ('$codigo','$nombre','$estado','$date_added','5')";
		$sqld="INSERT INTO detalle_productos (codigo_producto, status, cantidad, detalle_date_added, precio) VALUES ('$codigo','1','$cantidad','$date_added','$precio')";
		//$query_new_insert = mysqli_query($con,$sql);
		$query_new_insert_d = mysqli_query($con,$sqld);
			if ($query_new_insert_d){
				$messages[] = "El producto ha sido reavastecido satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
			}
		} else {
			$errors []= "Error desconocido.";
		}
		
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>
<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
// checking for minimum PHP version		
	if (empty($_POST['nombre2'])){
		$errors[] = "Nombre vacíos";
	} elseif (!empty($_POST['nombre2'])){
        require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre2"],ENT_QUOTES)));
		$depto_id=intval($_POST['mod_id']);
		// write new user's data into database
        $sql = "UPDATE departamentos SET nombre='".$nombre."' WHERE depto_id='".$depto_id."';";
		$query_update = mysqli_query($con,$sql);
		// if user has been added successfully
        if ($query_update) {
			$messages[] = "El departamento ha sido modificado con éxito.";
		} else {
			$errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
		}
	} else {
		$errors[] = "Un error desconocido ocurrió.";
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
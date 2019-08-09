<?php
include('is_logged.php');//Archivo verifica que el usario que intenta acceder a la URL esta logueado
// checking for minimum PHP version
	
if (empty($_POST['nombre'])){
	$errors[] = "Nombre vacío";
}  elseif (!empty($_POST['nombre'])) {
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	// escaping, additionally removing everything that could be (html/javascript-) code
	$nombre = mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
	// write new user's data into database
    $sql = "INSERT INTO departamentos (nombre)	VALUES ('".$nombre."');";
    $query_new_depto_insert = mysqli_query($con,$sql);
	// if user has been added successfully
    if ($query_new_depto_insert) {
		$messages[] = "El Departamento ha sido creado con éxito.";
	} else {
		$errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
	}
} else {
	$errors[] = "Un error desconocido ocurrió.";
}
if (isset($errors)){	?>
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
if (isset($messages)){	?>
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
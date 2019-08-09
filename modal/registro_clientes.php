<?php	if (isset($con)){	?>
<!-- Modal -->
<div class="modal fade" id="nuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo cliente</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
					<div id="resultados_ajax"></div>
					
					<input type="hidden" class="form-control" id="telefono" name="telefono"  value="S/T">
					<input type="hidden" class="form-control" id="email" name="email" value="S/E">
					<input type="hidden" class="form-control" id="direccion" name="direccion" value="S/D">
					
					<div class="form-group">
						<label for="nombre" class="col-sm-3 control-label">Nombre</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="nombre" name="nombre" required>
						</div>
					</div>
					<div class="form-group">
						<label for="departamento" class="col-sm-3 control-label">Departamento</label>
						<div class="col-sm-8">
							<select class="form-control" id="departamento" name="departamento" required>
								<option value="">-- Selecciona el Departamento --</option>
								<?php
									$sqldep=mysqli_query($con,"SELECT * FROM departamentos");
									while($rowdep=mysqli_fetch_array($sqldep)){
								?>
								<option value="<?php echo $rowdep['depto_id']?> "><?php echo $rowdep['nombre'] ?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label for="estado" class="col-sm-3 control-label">Estado</label>
						<div class="col-sm-8">
							<select class="form-control" id="estado" name="estado" required>
								<option value="">-- Selecciona estado --</option>
								<option value="1" selected>Activo</option>
								<option value="0">Inactivo</option>
							</select>
						</div>
					</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
			</div>
				</form>
		</div>
	</div>
</div>
<?php	}	?>
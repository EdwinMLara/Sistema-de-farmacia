<div class="modal fade reporte-entradas" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-list-alt"></i> Generar Reportes de Entradas</h4>
									</div>
									<div class="modal-body">
										<form class="form-horizontal" role="form" id="datos_reporte">
											<div class="form-inline">
												<h3 class="text-primary">Generar Reporte </h3>
												<hr style="border-top:1px dotted #000;"/>
												<label>Desde:</label>
												<input type="date" class="form-control" placeholder="Inicio"  id="date1"/>
												<label>Hasta</label>
												<input type="date" class="form-control" placeholder="Final"  id="date2"/>
												<button type="submit" class="btn btn-default">
													<span class="glyphicon glyphicon-print"></span> Generar Reporte
												</button>
											</div>
										</form>
										<div id="resultados_reporte"></div><!-- Carga los datos ajax -->
										<div class="outer_div_reporte"></div><!-- Carga los datos ajax -->
			
									</div>
								</div>
							</div>
						</div>
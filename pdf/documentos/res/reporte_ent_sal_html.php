<style type="text/css">
<!--
table { vertical-align: top; }
tr    { vertical-align: top; }
td    { vertical-align: top; }
.midnight-blue{
	background:#2c3e50;
	padding: 4px 4px 4px;
	color:white;
	font-weight:bold;
	font-size: 12pt;
}
.silver{
	background:white;
	padding: 3px 4px 3px;
}
.clouds{
	background:#ecf0f1;
	padding: 3px 4px 3px;
}
.totales{
	background:#F7BE81;
	padding: 3px 4px 3px;
}
.border-top{
	border-top: solid 1px #bdc3c7;
	
}
.border-left{
	border-left: solid 1px #bdc3c7;
}
.border-right{
	border-right: solid 1px #bdc3c7;
}
.border-bottom{
	border-bottom: solid 1px #bdc3c7;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
}
-->
</style>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
	<page_footer>
		<table class="page_footer">
            <tr>
                <td style="width: 50%; text-align: left">
                    P&aacute;gina [[page_cu]]/[[page_nb]]
                </td>
                <td style="width: 50%; text-align: right">
                    &copy; <?php echo "Precidencia Municipal de Uriangato "; echo  $anio=date('Y'); ?>
                </td>
            </tr>
        </table>
    </page_footer>
	<?php include("encabezado_reporte.php"); ?>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
		<tr class="midnight-blue">
			<td style="width: 55%; text-align: left;"><strong>Producto</strong></td>
			<td style="width: 15%; text-align: center;"><strong>Entradas</strong></td>
			<td style="width: 15%; text-align: center;"><strong>Salidas</strong></td>
			<td style="width: 15%; text-align: center;"><strong>Inventario</strong></td>
		</tr>
		<?php
			$sumatoria_entrada=0;
			$sumatoria_salida=0;
			$nums=1;
			$queryp=mysqli_query($con, $sqlproducto);
			while($row=mysqli_fetch_array($queryp)){
				$nombre_producto=$row['nombre_producto'];
				$codigo_producto=$row['codigo_producto'];
				$queryen=mysqli_query($con, "SELECT p.id_producto, p.codigo_producto, dp.codigo_producto, dp.status, sum(dp.cantidad) AS total_entrada FROM detalle_productos dp, products p WHERE dp.codigo_producto=p.codigo_producto AND p.codigo_producto=$codigo_producto AND dp.status='1'");
				if ($nums%2==0){
					$clase="clouds";
				} else {
					$clase="silver";
				}
				while($rowen=mysqli_fetch_array($queryen)){
					$total_entrada=$rowen['total_entrada'];
					$querysal=mysqli_query($con, "SELECT p.id_producto, p.codigo_producto, dp.codigo_producto, dp.status, sum(dp.cantidad) AS total_salida FROM detalle_productos dp, products p WHERE dp.codigo_producto=p.codigo_producto AND p.codigo_producto=$codigo_producto AND dp.status='2'");
					while($rowsal=mysqli_fetch_array($querysal)){
						$total_salida=$rowsal['total_salida'];
						
		?>
		<tr>
			<td class='<?php echo $clase;?>' style="text-align: left;"><?php echo $nombre_producto; ?></td>
			<td class='<?php echo $clase;?>' style="text-align: center;"><?php echo $total_entrada; ?></td>
			<td class='<?php echo $clase;?>' style="text-align: center;"><?php echo $total_salida; ?></td>
			<td class='<?php echo $clase;?>' style="text-align: center;"><?php echo $total_entrada-$total_salida; ?></td>
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
			<td style="text-align: left;"><strong>Totales:</strong></td>
			<td style="text-align: center;"><strong><?php echo $total_entrada ?></strong></td>
			<td style="text-align: center;"><strong><?php echo $total_salida ?></strong></td>
			<td style="text-align: center;"><strong><?php echo $diferencia=$total_entrada-$total_salida ?></strong></td>
		</tr>
	</table>
	<br><br>
	<div style="font-size:11pt;text-align:center;font-weight:bold">Reporte de la fecha <?php echo date("d-m-Y", strtotime($date1)); ?> a <?php echo date("d-m-Y", strtotime($date2)); ?></div>
</page>
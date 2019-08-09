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
	font-size:10pt;
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
        <tr>
            <!--<th style="width:10%; text-align:center" class='midnight-blue'>Codigo.</th>-->
            <th style="width:25%;" class="midnight-blue">Producto</th>
            <th style="width:8%;" class="midnight-blue">Estado</th>
            <th style="width:7%;" class="midnight-blue">Cantidad</th>
            <th style="width:8%;" class="midnight-blue">Salida</th>
            <th style="width:7%;" class="midnight-blue">Factura</th>
            <th style="width:25%;" class="midnight-blue">Cliente</th>
            <th style="width:10%;"class="midnight-blue">Departamento</th>
        </tr>
		<?php
			$date1=$_GET['date1'];
			$date2=$_GET['date2'];
			$nums=1;
			//$query=mysqli_query($con, "SELECT facturas.id_factura, facturas.numero_factura, facturas.fecha_factura, facturas.id_cliente, clientes.id_cliente, clientes.nombre_cliente, clientes.depto_id, departamentos.depto_id, departamentos.nombre, detalle_factura.numero_factura, detalle_factura.id_producto, detalle_factura.cantidad, products.id_producto, products.nombre_producto FROM facturas, clientes, departamentos, detalle_factura, products WHERE (facturas.id_cliente=clientes.id_cliente) AND (clientes.depto_id=departamentos.depto_id) AND (facturas.numero_factura=detalle_factura.numero_factura) AND (detalle_factura.id_producto=products.id_producto) AND facturas.fecha_factura BETWEEN '$date1' AND '$date2' ORDER BY facturas.fecha_factura DESC");
			$query=mysqli_query($con, "SELECT 
										facturas.id_factura, facturas.numero_factura, facturas.fecha_factura, facturas.id_cliente,
										clientes.id_cliente, clientes.nombre_cliente, clientes.depto_id,
										departamentos.depto_id, departamentos.nombre,
										detalle_factura.numero_factura, detalle_factura.id_producto, detalle_factura.cantidad,
										products.id_producto, products.nombre_producto
									   FROM
										facturas, clientes, departamentos, detalle_factura, products
									   WHERE
										(facturas.id_cliente=clientes.id_cliente) AND 
										(clientes.depto_id=departamentos.depto_id) AND
										(facturas.numero_factura=detalle_factura.numero_factura) AND
										(detalle_factura.id_producto=products.id_producto) AND 
										 (facturas.fecha_factura BETWEEN '$date1' AND '$date2')
										ORDER BY facturas.fecha_factura DESC");
			while($row=mysqli_fetch_array($query)){
				//$codigo_producto=$row['codigo_producto'];
				$nombre_producto=$row['nombre_producto'];
				$status_producto=2;
				if ($status_producto==1){$estado="Entrada";}
				else {$estado="Salida";}
				$cantidad=$row['cantidad'];
				$fecha_factura= date('d-m-Y', strtotime($row['fecha_factura']));
				$numero_factura=$row['numero_factura'];
				$nombre_cliente=$row['nombre_cliente'];
				$nombre_depto=$row['nombre'];
				if ($nums%2==0){
					$clase="clouds";
				} else {
					$clase="silver";
				}
		?>
        <tr>
			<!-- <td class='< ?php echo $clase;?>' style="text-align: center">< ?php echo $codigo_producto; ?></td> -->
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $nombre_producto; ?></td>
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $estado;?></td>
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $cantidad;?></td>
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $fecha_factura;?></td>
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $numero_factura ;?></td>
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $nombre_cliente ;?></td>
			<td class='<?php echo $clase;?>' style="text-align: center"><?php echo $nombre_depto ;?></td>
        </tr>
		<?php 
			$nums++;
			}
		?>
    </table>
	<br>
	<div style="font-size:11pt;text-align:center;font-weight:bold">Reporte de la fecha <?php echo date("d-m-Y", strtotime($date1)); ?> a <?php echo date("d-m-Y", strtotime($date2)); ?></div>
</page>
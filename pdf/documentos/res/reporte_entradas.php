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
	font-size:12px;
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
            <th style="width: 10%; text-align:center" class='midnight-blue'>Codigo.</th>
            <th style="width: 55%" class='midnight-blue'>Producto</th>
            <th style="width: 11%" class='midnight-blue'>Cantidad</th>
            <th style="width: 11%" class='midnight-blue'>Precio Entrada</th>
            <th style="width: 13%" class='midnight-blue'>Fecha Entrada</th>
        </tr>
		<?php
			$date1=$_GET['date1'];
			$date2=$_GET['date2'];
			$suma=0;
			$cantotal=0;
			$nums=1;
			$query=mysqli_query($con,"SELECT * FROM detalle_productos, products WHERE detalle_productos.codigo_producto = products.codigo_producto AND detalle_productos.detalle_date_added BETWEEN '$date1' AND '$date2' AND detalle_productos.status='1' ORDER BY detalle_productos.id_detalle_producto ASC");
			while($row=mysqli_fetch_array($query)){
				$codigo=$row['codigo_producto'];
				$nombre=$row['nombre_producto'];
				$cantidad=$row['cantidad'];
				$precio=$row['precio'];
				$fecha=$row['detalle_date_added'];
				if ($nums%2==0){
					$clase="clouds";
				} else {
					$clase="silver";
				}
		?>
        <tr class="<?php echo $clase;?>">
            <td style="width: 10%; text-align: center"><?php echo $codigo; ?></td>
            <td style="width: 55%; text-align: center"><?php echo $nombre;?></td>
            <td style="width: 11%; text-align: center"><?php echo $cantidad;?></td>
            <td style="width: 11%; text-align: center"><?php echo $precio;?></td>
            <td style="width: 13%; text-align: center"><?php echo date("d-m-Y", strtotime($fecha));?></td>
        </tr>
		<?php 
			$suma+=$precio;
			$cantotal+=$cantidad;
			$nums++;
			}
			$total=number_format($suma,2,'.','');
		?>
		<tr class="totales">
			<td colspan="2" style="width: 65%; text-align: right">TOTALES:</td>
			<td style="width: 11%; text-align: center"><?php echo $cantotal;?></td>
			<td style="width: 11%; text-align: center"><?php echo $total;?></td>
			<td></td>
		</tr>
    </table>
	<br>
	<div style="font-size:11pt;text-align:center;font-weight:bold">Reporte de la fecha <?php echo date("d-m-Y", strtotime($date1)); ?> a <?php echo date("d-m-Y", strtotime($date2)); ?></div>
</page>
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
                    &copy; <?php echo "obedalvarado.pw "; echo  $anio=date('Y'); ?>
                </td>
            </tr>
        </table>
    </page_footer>
    
	<?php include("encabezado_factura.php");?>
    <br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
        <tr>
           <td style="width:50%;" class='midnight-blue'>FACTURAR A</td>
        </tr>
		<tr>
           <td style="width:50%;" >
			<?php 
				$sql_cliente=mysqli_query($con,"select * from clientes where id_cliente='$id_cliente'");
				$rw_cliente=mysqli_fetch_array($sql_cliente);
				echo $rw_cliente['nombre_cliente'];
				echo "<br>";
				echo $rw_cliente['direccion_cliente'];
				echo "<br> Teléfono: ";
				echo $rw_cliente['telefono_cliente'];
				echo "<br> Email: ";
				echo $rw_cliente['email_cliente'];
			?>
		   </td>
        </tr>
    </table>
	<br>
	<table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
		<tr>
			<td style="width:35%;" class='midnight-blue'>VENDEDOR</td>
			<td style="width:25%;" class='midnight-blue'>FECHA</td>
        </tr>
		<tr>
			<td style="width:35%;">
				<?php 
					$sql_user=mysqli_query($con,"select * from users where user_id='$id_vendedor'");
					$rw_user=mysqli_fetch_array($sql_user);
					echo $rw_user['firstname']." ".$rw_user['lastname'];
				?>
			</td>
			<td style="width:25%;"><?php echo date("d/m/Y");?></td>
        </tr>
    </table>
	<br>
    <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
        <tr>
            <th style="width: 10%;text-align:center" class='midnight-blue'>CANT.</th>
            <th style="width: 60%" class='midnight-blue'>DESCRIPCION</th>
        </tr>
		<?php
			$nums=1;
			$sumador_total=0;
			$sql=mysqli_query($con, "select * from products, tmp where products.id_producto=tmp.id_producto and tmp.session_id='".$session_id."'");
			
			while ($row=mysqli_fetch_array($sql)){
				$id_tmp=$row["id_tmp"];
				$id_producto=$row["id_producto"];
				$codigo_producto=$row['codigo_producto'];
				$cantidad=$row['cantidad_tmp'];
				$nombre_producto=$row['nombre_producto'];
				if ($nums%2==0){
					$clase="clouds";
				} else {
					$clase="silver";
				}
		?>
        <tr>
            <td class='<?php echo $clase;?>' style="width: 10%; text-align: center"><?php echo $cantidad; ?></td>
            <td class='<?php echo $clase;?>' style="width: 60%; text-align: left"><?php echo $nombre_producto;?></td>
        </tr>
		<?php 
			//Insert en la tabla detalle_cotizacion
			$insert_detail=mysqli_query($con, "INSERT INTO detalle_factura (numero_factura,id_producto,cantidad) VALUES ('$numero_factura','$id_producto','$cantidad')");
			$insertb=mysqli_query($con,"INSERT INTO detalle_productos (codigo_producto, status, cantidad, detalle_date_added, precio)VALUES('$codigo_producto','2','$cantidad','$date','0')");
			$nums++;
		}
		?>
    </table>
	<br>
	<div style="font-size:11pt;text-align:center;font-weight:bold">Gracias por su compra!</div>
</page>

<?php
	
	$insert=mysqli_query($con,"INSERT INTO facturas (numero_factura,fecha_factura,id_cliente,id_vendedor,estado_factura) VALUES ('$numero_factura','$date','$id_cliente','$id_vendedor','1')");
	$delete=mysqli_query($con,"DELETE FROM tmp WHERE session_id='".$session_id."'");
?>
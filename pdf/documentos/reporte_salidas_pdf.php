<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../login.php");
		exit;
    }
	/* Connect To Database */
	include("../../config/db.php");
	include("../../config/conexion.php");
	//Archivo de funciones PHP
	include("../../funciones.php");
	//Variables por GET
	
	
	
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
	ob_start();
	include(dirname('__FILE__').'/res/reporte_salidas.php');
	$content = ob_get_clean();
	
	try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('L', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
        $html2pdf->pdf->SetDisplayMode('fullpage');
        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        // send the PDF
        $html2pdf->Output('Reporte.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>
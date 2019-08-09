		$(document).ready(function(){
			load(1);
		});

		function load(page){
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_departamentos.php?action=ajax&page='+page+'&q='+q,
				beforeSend: function(objeto){
					$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
				},
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
				}
			})
		}

		function eliminar (id){
			var q= $("#q").val();
			if (confirm("Realmente deseas eliminar el departamento")){	
				$.ajax({
					type: "GET",
					url: "./ajax/buscar_departamentos.php",
					data: "id="+id,"q":q,
					beforeSend: function(objeto){
						$("#resultados").html('<img src="./img/ajax-loader.gif"> Cargando...');
					},
					success: function(datos){
						$("#resultados").html(datos);
						load(1);
					}
				});
			}
		}
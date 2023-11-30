<?php
    //Verificacion de errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Achivos a usar
	include_once('../class/class.viajes.reales.php');
	include_once('../class/class.auto.select.php');
	include_once('../class/class.manejo.fechas.php');

    //DATOS ENVIADOS PEL FORMULARIO
    $date_start 	=	$_POST['date_start'];
    $date_end   	=   $_POST['date_end'];
    $unidadDeNegacio=   $_POST['uen'];

    $diaEnCurso			= date('d');
    $mesEnCurso 		= date('m');
	$anioEnCurso 		= date('Y');
    $totaldiasDelMes 	= date('t');
    

    //Instancia y convercion de mes numero a mes letra
	$obtmes			= new ManejoDeFechas();
	$mestexto		= $obtmes->obtenerMes($mesEnCurso);//Se pasa el dato extraido del sistema en numero y se convierte a texto para consulta

	$datolAlDia 	= new ViajesReales($date_start, $date_end, $mestexto , $anioEnCurso, $unidadDeNegacio);
	$unidadNegocio	= new LlenadoAutDeSelect();

	//Meta al dia
	$metasMensuales	= $datolAlDia->totalMetasPorUen($mestexto,$anioEnCurso,$unidadDeNegacio);

	//Funciones para presentar los datos para llenar SELECT
	$tractoGral 	= $datolAlDia->obtenerDatosTracto();
	$tractoSinViajes= $datolAlDia->obtenerDatosTractoSinViajes();
	$tractosSiMeta	= $datolAlDia->vehiculosSinMeta();
	
	foreach ($metasMensuales AS $x) {
		$MetaGral 		= $x['MG'];
		$totalCamiones 	= $x['NC'];
		$totalDiasDelMes= $x['DM'];
		$diaVencido		= $x['DV'];
		$metaPorDia		= $x['MporD'];
		$metaPorCamion  = $x['MC'];
		$metaAdiaVencido= $x['MDV'];
		
		if ($unidadDeNegacio != 'Opciónes UEN') {
			$mva = number_format($metaAdiaVencido,2);
			//echo('hola');
		} else {
			// maneja el caso cuando $metaAdiaVencido es null
			$mva = number_format(402986.72,2);
			// $metaAdiaVencido = 1; 
			// $MetaGral = 1;// o cualquier valor predeterminado que desees
		}
	}
?>
<!-- Impresión de datos -->

<!-- GRAFICO -->
<div class="row mt-4">
	<div class="col-lg-12 mb-lg-0 mb-4">
		<div class="card">
			<div class="card-body p-3">
				<div class="row">
					<div class="col-lg-12">
						<div class="d-flex flex-column h-100">
							<p class="mb-1 pt-2 text-bold">Datos del <?php echo $date_start; ?> al <?php echo $date_end; ?><?php echo $unidadDeNegacio; ?></p>
							<div id="myChart" style="margin-bottom: 1em;" class="chart-display"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END GRAFICO -->
<!-- TABLA CON DATOS -->
<div class="row mt-4">
	<div class="col-lg-12 mb-lg-0 mb-4">
		<div class="card">
			<div class="card-body p-3">
				<div class="col-lg-12 ms-auto mt-5 mt-lg-0">
					<div class="card mb-4">
						<div class="card-body px-0 pt-0 pb-2">
							<div class="table-responsive p-0 ">
								<table class="table align-items-center justify-content-center mb-0 display table-striped table-bordered" id="myTable" style="width:100%">
									<thead class="text-center">
										<tr class="table-dark">
										<th scope="col" class="text-uppercase">Tracto</th>
											<th scope="col" class="text-uppercase">Importe</th>
											<th scope="col" class="text-uppercase">Meta</th>
											<th scope="col" class="text-uppercase">Unidad de Negocio (UEN)</th>
										</tr>
									</thead>
									<tbody class="text-center">
										<?php foreach ($tractoGral as $viaje) : ?>
											<tr>
												<td>
													<div class="px-2">
														<div class="my-auto">
															<h6 class="mb-0 text-sm">
																<?= $viaje['tracto'] ?>
															</h6>
														</div>
													</div>
												</td>
												<td>
													<p class="text-sm font-weight-bold mb-0">
														$ <?= number_format($viaje['total'], 2) ?>
													</p>
												</td>
												<td>
													<p class="text-sm font-weight-bold mb-0">
														$ <?= number_format($viaje['meta'], 2) ?>
													</p>
												</td>
												<td>
													<p class="text-sm font-weight-bold mb-0">
														<?= $viaje['UEN'] ?>
													</p>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
						<br>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TABLA CON DATOS TRACTOS SIN VIAJES y VENICULOS SIN META -->
<div class="row mt-4">
<!-- TRACTOS SIN VIAJES -->
<div class="col-lg-6 mb-lg-0 mb-4">
	<div class="card">
		<div class="card-body p-3">
			<div class="col-lg-12 ms-auto mt-5 mt-lg-0">
				<div class="card mb-4">
					<div class="card-body px-0 pt-0 pb-2">
						<div class="table-responsive p-0 ">
							<table class="table align-items-center justify-content-center mb-0 display table-striped table-bordered" id="tractosSinViajes" style="width:100%">
								<thead class="text-center">
									<tr class="table-dark">
										<th scope="col" class="text-uppercase text-center" colspan="2">UNIDADES SIN VIAJE</th>
									</tr>
									<tr class="table-dark">
										<th scope="col" class="text-uppercase">Tracto Sin Viaje</th>
										<th scope="col" class="text-uppercase">Unidad de Negocio (UEN)</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<?php foreach ($tractoSinViajes as $sinviaje) : ?>
										<tr>
											<td>
												<div class="px-2">
													<div class="my-auto">
														<h6 class="mb-0 text-sm">
															<?= $sinviaje['eco'] ?>
														</h6>
													</div>
												</div>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0">
													<?= $sinviaje['operacion'] ?>
												</p>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TRACTOS SIN META -->
<div class="col-lg-6 mb-lg-0 mb-4">
	<div class="card">
		<div class="card-body p-3">
			<div class="col-lg-12 ms-auto mt-5 mt-lg-0">
				<div class="card mb-4">
					<div class="card-body px-0 pt-0 pb-2">
						<div class="table-responsive p-0 ">
							<table class="table align-items-center justify-content-center mb-0 display table-striped table-bordered" id="tractosSinMeta" style="width:100%">
								<thead class="text-center">
									<tr class="table-dark">
										<th scope="col" class="text-uppercase text-center" colspan="2">UNIDADES SIN MENTA</th>
									</tr>
									<tr class="table-dark">
										<th scope="col" class="text-uppercase">Tractos</th>
										<th scope="col" class="text-uppercase">Unidad de Negocio (UEN)</th>
									</tr>
								</thead>
								<tbody class="text-center">
									<?php foreach ($tractosSiMeta as $sinMeta) : ?>
										<tr>
											<td>
												<div class="px-2">
													<div class="my-auto">
														<h6 class="mb-0 text-sm">
															<?= $sinMeta['eco'] ?>
														</h6>
													</div>
												</div>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0">
													<?= $sinMeta['operacion'] ?>
												</p>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<script>
	$(document).ready(function() {
	// Inicializamos la tabla y cambiamos por Idioma en Español
	var table = $('#myTable').DataTable({
			"order": [[ 1, "desc" ]],
			"language": {
				"processing":     "Procesando...",
				"lengthMenu":     "Mostrar _MENU_ registros",
				"zeroRecords":    "No se encontraron resultados",
				"emptyTable":     "Ningún dato disponible en esta tabla",
				"info":           " Registros del _START_ al _END_ de un total de _TOTAL_ registros",
				"infoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
				"infoFiltered":   "(filtrado de un total de _MAX_ registros)",
				"infoPostFix":    "",
				"search":         "Buscar:",
				"url":            "",
				"infoThousands":  ",",
				"loadingRecords": "Cargando...",
				"paginate": {
					"first":    "Primero",
					"last":     "Último",
					"next":     "Siguiente",
					"previous": "Anterior"
				},
				"aria": {
					"sortAscending":  ": Activar para ordenar la columna de manera ascendente",
					"sortDescending": ": Activar para ordenar la columna de manera descendente"
				}
			}
			
	});

	// Inicializamon el gráfico HIGHCHARTS
	var chartOptions = {
			chart: {
				type: 'column'
			},
			title: {
				text: ''
			},
			xAxis: {
				categories: []
			},
			yAxis: {
				title: {
					text: 'IMPORTE'
				},
				max: 200000000 // Máximo del eje y según tus datos
			},
			plotOptions: {
				column: {
					borderColor: 'rgba(54, 162, 235, 1)',
					borderWidth: 1
				}
			},
			series: [{
				name: 'IMPORTE',
				data: [], // Datos vacíos
				color: 'rgba(0, 154, 219, 1)'
			}],

			plotOptions: {
				column: {
					pointPadding: 0.2,
					borderWidth: 0
				}
			},
			yAxis: {
				plotLines: [
					{
						color: 'red',        		// Color
						dashStyle: 'solid',  		// Estilo
						width: 1.5,            		// Grosor
						value: <?php echo "$MetaGral"; ?>,           	// Valor 
						label: {
							text: 'Meta General',   // Etiqueta
							align: 'left',    		// Posición
							x: 10,            		// Desplazamiento
							zIndex: 5
							},
						zIndex: 3
					},
					{
						color: 'green',        
						dashStyle: 'solid',  
						width: 1.5,            
						value: <?php echo "$metaAdiaVencido"; ?>,           
						label: {
							text: 'Meta al dia $ <?php echo "$mva"; ?>',    
							align: 'left',    
							x: 10,           
							zIndex: 5
							},
						zIndex: 3
					}
				]
			},
			credits: {
        		enabled: false
		    }
	};

	var myChart = Highcharts.chart('myChart', chartOptions);

		// Función para obtener los datos de la tabla
		function getTableData(table) {
			return table.rows({page: 'current'}).data().toArray().map(function(row) {
				return {
					category: row[0],
					value: parseInt(row[1].replace(/[^\d.]/g, ''))
				};
			});
		}

		// Función para actualizar el gráfico
		function updateChart(chart, data) {
			if (chart.xAxis[0] && chart.series[0]) {
				chart.xAxis[0].setCategories(data.map(function(item) { return item.category; }));
				chart.series[0].setData(data.map(function(item) { return item.value; }));
				chart.redraw();
			}
		}

		// Actualizar el gráfico al cargar la página
		updateChart(myChart, getTableData(table));

		// Actualizar el gráfico cada vez que se cambia la página de la tabla
		table.on('draw', function() {
			updateChart(myChart, getTableData(table));
		});
			

		});
</script>
<!-- END TABLA CON DATOS -->
</script>
<!-- TRACTOS SIN VIAJES -->
<script>
	$(document).ready(function() {
		var table = $('#tractosSinViajes').DataTable({
				"order": [[ 1, "desc" ]],
				"language": {
					"processing":     "Procesando...",
					"lengthMenu":     "Mostrar MENU registros",
					"zeroRecords":    "No se encontraron resultados",
					"emptyTable":     "Ningún dato disponible en esta tabla",
					"info":           " Registros del START al END de un total de TOTAL registros",
					"infoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
					"infoFiltered":   "(filtrado de un total de MAX registros)",
					"infoPostFix":    "",
					"search":         "Buscar:",
					"url":            "",
					"infoThousands":  ",",
					"loadingRecords": "Cargando...",
					"paginate": {
						"first":    "Primero",
						"last":     "Último",
						"next":     "Siguiente",
						"previous": "Anterior"
					},
					"aria": {
						"sortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				}
				// dom: 'Bfrtip',
				// buttons: [
				// 	'copy', 'csv', 'excel', 'pdf', 'print',
				// 	// {
				//     // extend: 'excel',
				//     // text: '<button class="btn btn-success">Excel</button>'
				// 	// },
				// 	// {
				//     // extend: 'pdf',
				//     // text: '<button class="btn btn-success">PDF</button>'
				// 	// },
				// 	// {
				//     // extend: 'print',
				//     // text: '<button class="btn btn-success">Imprimir</button>'
				// 	// },

				// ]
		});
	});
</script>
<!-- TRACTOS SIN META -->
<script>
	$(document).ready(function() {
		var table = $('#tractosSinMeta').DataTable({
				"order": [[ 1, "desc" ]],
				"language": {
					"processing":     "Procesando...",
					"lengthMenu":     "Mostrar MENU registros",
					"zeroRecords":    "No se encontraron resultados",
					"emptyTable":     "Ningún dato disponible en esta tabla",
					"info":           " Registros del START al END de un total de TOTAL registros",
					"infoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
					"infoFiltered":   "(filtrado de un total de MAX registros)",
					"infoPostFix":    "",
					"search":         "Buscar:",
					"url":            "",
					"infoThousands":  ",",
					"loadingRecords": "Cargando...",
					"paginate": {
						"first":    "Primero",
						"last":     "Último",
						"next":     "Siguiente",
						"previous": "Anterior"
					},
					"aria": {
						"sortAscending":  ": Activar para ordenar la columna de manera ascendente",
						"sortDescending": ": Activar para ordenar la columna de manera descendente"
					}
				}
				// dom: 'Bfrtip',
				// buttons: [
				// 	'copy', 'csv', 'excel', 'pdf', 'print',
				// 	// {
				//     // extend: 'excel',
				//     // text: '<button class="btn btn-success">Excel</button>'
				// 	// },
				// 	// {
				//     // extend: 'pdf',
				//     // text: '<button class="btn btn-success">PDF</button>'
				// 	// },
				// 	// {
				//     // extend: 'print',
				//     // text: '<button class="btn btn-success">Imprimir</button>'
				// 	// },

				// ]
		});
	});
</script>
<!-- END TABLA CON DATOS -->

<footer class="footer pt-3  ">
		<div class="container-fluid">
			<div class="row align-items-center justify-content-lg-between">
				<div class="col-lg-6 mb-lg-0 mb-4">
					<div class="copyright text-center text-sm text-muted text-lg-start">
						<h6>©
							<script>
								document.write(new Date().getFullYear())
							</script>,Hecho por Área IT Pilot
						</h6>
					</div>
				</div>
				
			</div>
		</div>
	</footer>
	</div>
</main>


<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>

<script>
	var ctx = document.getElementById("chart-bars").getContext("2d");

	new Chart(ctx, {
		type: "bar",
		data: {
			labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			datasets: [{
				label: "Sales",
				tension: 0.4,
				borderWidth: 0,
				borderRadius: 4,
				borderSkipped: false,
				backgroundColor: "#fff",
				data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
				maxBarThickness: 6
			},],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false,
				}
			},
			interaction: {
				intersect: false,
				mode: 'index',
			},
			scales: {
				y: {
					grid: {
						drawBorder: false,
						display: false,
						drawOnChartArea: false,
						drawTicks: false,
					},
					ticks: {
						suggestedMin: 0,
						suggestedMax: 500,
						beginAtZero: true,
						padding: 15,
						font: {
							size: 14,
							family: "Open Sans",
							style: 'normal',
							lineHeight: 2
						},
						color: "#fff"
					},
				},
				x: {
					grid: {
						drawBorder: false,
						display: false,
						drawOnChartArea: false,
						drawTicks: false
					},
					ticks: {
						display: false
					},
				},
			},
		},
	});


	var ctx2 = document.getElementById("chart-line").getContext("2d");

	var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

	gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
	gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
	gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

	var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

	gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
	gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
	gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

	new Chart(ctx2, {
		type: "bar",
		data: {
			labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
			datasets: [{
				label: "Mobile apps",
				tension: 0.4,
				borderWidth: 0,
				pointRadius: 0,
				borderColor: "#cb0c9f",
				borderWidth: 3,
				backgroundColor: gradientStroke1,
				fill: true,
				data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
				maxBarThickness: 6

			},
			{
				label: "Websites",
				tension: 0.4,
				borderWidth: 0,
				pointRadius: 0,
				borderColor: "#3A416F",
				borderWidth: 3,
				backgroundColor: gradientStroke2,
				fill: true,
				data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
				maxBarThickness: 6
			},
			],
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					display: false,
				}
			},
			interaction: {
				intersect: false,
				mode: 'index',
			},
			scales: {
				y: {
					grid: {
						drawBorder: false,
						display: true,
						drawOnChartArea: true,
						drawTicks: false,
						borderDash: [5, 5]
					},
					ticks: {
						display: true,
						padding: 10,
						color: '#b2b9bf',
						font: {
							size: 11,
							family: "Open Sans",
							style: 'normal',
							lineHeight: 2
						},
					}
				},
				x: {
					grid: {
						drawBorder: false,
						display: false,
						drawOnChartArea: false,
						drawTicks: false,
						borderDash: [5, 5]
					},
					ticks: {
						display: true,
						color: '#b2b9bf',
						padding: 20,
						font: {
							size: 11,
							family: "Open Sans",
							style: 'normal',
							lineHeight: 2
						},
					}
				},
			},
		},
	});
</script>
<script>
	var win = navigator.platform.indexOf('Win') > -1;
	if (win && document.querySelector('#sidenav-scrollbar')) {
		var options = {
			damping: '0.5'
		}
		Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
	}
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
<!-- Datatables -->
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.7/datatables.min.js"></script>

<!-- SCRIPT BUSQUEDA POR DIAS ESPECIFICADO -->
<script>
	// $(document).ready(function(){
	// 	$("#btn_consumo_por_unidad").click(function(){
	// 		var fechaInicio = $("#date_start").val();
	// 		var fechaTermino = $("#date_end").val();
	// 		var unidadNegocio = $("#uen").val();

	// 		$.ajax({
	// 			url: '../viewsPorBusqueda/consumoPorUnidadYMetaMensual.php',
	// 			type: 'post',
	// 			data: {date_start: fechaInicio, date_end: fechaTermino, uen: unidadNegocio},
	// 			beforeSend: function() {
	// 				$("#datosProcesados").html("<div style='text-align:center;'><samp>Calculando registros...</samp><br><br><br><img src='../assets/img/gif/loading.gif' alt='Procesando Datos'></div>");
	// 			},
	// 			success: function(response){
	// 				$("#datosProcesados").html(response);
	// 			}
	// 		});
	// 	});
	// });

</script>
<script>
	$(document).ready(function(){
    $("#btn_consumo_por_unidad").click(function(){
        var fechaInicio = $("#date_start").val();
        var fechaTermino = $("#date_end").val();
        var unidadNegocio = $("#uen").val();

        // Convierte las fechas a objetos Date de JavaScript
        var inicio = new Date(fechaInicio);
        var final = new Date(fechaTermino);

        // Comprueba si la fecha de inicio es posterior a la fecha final
        if(inicio > final) {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'La fecha final no puede ser anterior a la fecha de inicio!'
			})
          return; // Detiene la ejecución de la función
        }

        $.ajax({
            url: '../viewsPorBusqueda/consumoPorUnidadYMetaMensual.php',
            type: 'post',
            data: {date_start: fechaInicio, date_end: fechaTermino, uen: unidadNegocio},
            beforeSend: function() {
                $("#datosProcesados").html("<div style='text-align:center;'><samp>Calculando registros...</samp><br><br><br><img src='../assets/img/gif/loading.gif' alt='Procesando Datos'></div>");
            },
            success: function(response){
                $("#datosProcesados").html(response);
            }
        });
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

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
<!-- Incluir los archivos JS para los botones de exportación -->
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
</body>

</html>
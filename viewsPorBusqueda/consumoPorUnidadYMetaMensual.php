<?php
    //archivos a usar
	include_once('../class/class.viajes.reales.por.unidad.php');
	include_once('../class/class.manejo.fechas.php');
    //Verificacion de errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //Recepcion de dato
    $dateStart    = $_POST['date_start'];
    $dateEnd       = $_POST['date_end'];
    $unidadDeNegocio= $_POST['uen'];
    $diaEnCurso			= date('d');
    $mesEnCurso 		= date('m');
	$anioEnCurso 		= date('Y');
    $totaldiasDelMes 	= date('t');
    //Paginacion
	$nPorPagina	= 20;

    //Instancia de clases
    $datolAlDia 	= new ViajesRealesPorUnidad($dateStart, $dateEnd, $nPorPagina, $unidadDeNegocio);
    $obtmes			= new ManejoDeFechas();
    $totalPaginas	= $datolAlDia->calcularPaginas();

    //Funciones para presentar los datos
	$tractoGral 	= $datolAlDia->obtenerDatosTracto();
	$totalPaginas	= $datolAlDia->calcularPaginas();

    //trabajando
	$mestexto		= $obtmes->obtenerMes($mesEnCurso);//Se pasa el dato extraido del sistema en numero y se convierte a texto para consulta
	$totalMetasAlDia= $datolAlDia->totalMetas($mestexto,$anioEnCurso);
	//Meta al dia
	foreach ($totalMetasAlDia AS $x) {
		$sumaMetaGral = $x['SumaMetaGral'];
		$cantidadDeMetas = $x['cantidadDeMeta'];
		$mediaMetaGral = $sumaMetaGral / $cantidadDeMetas;
		$metaPorDia = $mediaMetaGral / $totaldiasDelMes;
		$totalMetaAlDia = $metaPorDia * $diaEnCurso;
	}
	// Media de meta Mensual
	// foreach ($totalMetasAlDia AS $x) {
	// 	$sumaMetaGral = $x['SumaMetaGral'];
	// 	$cantidadDeMetas = $x['cantidadDeMeta'];
	// 	$mediaMetaGral = $sumaMetaGral / $cantidadDeMetas;
	// }





?>
<!-- GRAFICO CHARTjs -->
<div class="row mt-4">
	<div class="col-lg-12 mb-lg-0 mb-4">
		<div class="card"> 
			<div class="card-body p-3">
				<div class="row">
					<div class="col-lg-12">
						<div class="d-flex flex-column h-100">
                            <!-- Libreria para graficar chart.js -->
	                        <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/Chart.min.js"></script>
							<p class="mb-1 pt-2 text-bold">Datos del <?php echo $dateStart; ?> al <?php echo $dateEnd; ?></p>
							<canvas id="myChart" style="width:100%; "></canvas>
						</div>
					</div>
					<!-- DATOS A GRAFICAR -->
					<script>
									var ctx = document.getElementById('myChart').getContext('2d');
									var myChart = new Chart(ctx, {
										type: 'bar',
										data: {
											labels: [
												<?php
												$count = count($tractoGral);

												$i = 0;
												foreach ($tractoGral as $viaje) {
													$i++;
													echo "'" . $viaje['tracto'] . "'";
													if ($i != $count) {
														echo ",";
													}
												}
												?>
											],
											datasets: [{
												label: 'IMPORTE',
												data: [
													<?php
													$count = count($tractoGral);

													$i = 0;
													foreach ($tractoGral as $viaje) {
														$i++;
														echo "'" . $viaje['total'] . "'";
														if ($i != $count) {
															echo ",";
														}
													}
													?>
												],
												borderWidth: 1,
												borderColor: '#1a5873',
												backgroundColor: '#00adf7',
											}]
										},
										options: {
											scales: {
												y: {
													beginAtZero: true,
													max: 5000
												}
											},
											plugins: {
												datalabels: {
													color: 'white',
													font: {
														size: 18
													}
												}
											},
											animation: {
												onComplete: function() {
													var chartInstance = this.chart,
													ctx = chartInstance.ctx;
													ctx.textAlign = 'center';
													ctx.textBaseline = 'bottom';
													//Linea de meta mensual
													
													var yValue = 350000; //Aquí es donde se coloca el valor de la meta mensual
													var yScale = this.scales['y-axis-0'];
													var pixel = yScale.getPixelForValue(yValue);
													ctx.save();
													ctx.beginPath();
													ctx.moveTo(30, pixel);
													ctx.strokeStyle = '#ff0000'; // línea a roja
													ctx.lineTo(this.scales['x-axis-0'].right, pixel);
													ctx.stroke();
													ctx.restore();

													//Linea de meta al dia
													var yValue2 = <?php echo $totalMetaAlDia; ?>; //Aquí es donde se coloca el valor del UEN respecto a los dias trancurridos en el mes
													var yScale2 = this.scales['y-axis-0'];
													var pixel2 = yScale2.getPixelForValue(yValue2);
													ctx.save();
													ctx.beginPath();
													ctx.moveTo(30, pixel2);
													ctx.strokeStyle = '#00ff00'; // línea a verde
													ctx.lineTo(this.scales['x-axis-0'].right, pixel2);
													ctx.stroke();
													ctx.restore();
												}
											}
										}
									});
					</script>
					<!-- END DATOS A GRAFICAR -->
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
											<table class="table align-items-center justify-content-center mb-0 display table-striped table-bordered" id="example" style="width:100%">
												<thead class="text-center">
													<tr class="table-dark">
														<th scope="col" class="text-uppercase">Tracto</th>
														<th scope="col" class="text-uppercase">Importe</th>
														<th scope="col" class="text-uppercase">Unidad de Negocio</th>
														<!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Completion</th> -->
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
																	<?= $viaje['UEN'] ?>
																</p>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
									<div class="card-footer pb-0">
										<div class="pagination" style="display: flex; justify-content: center; align-items: center; height: 100%;">
											<br>
											<?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
												<a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
											<?php endfor; ?>
										</div>
									</div>
									<br>
								</div>
							</div>
						</div>
					</div>
				</div>
</div>
<!-- END TABLA CON DATOS -->
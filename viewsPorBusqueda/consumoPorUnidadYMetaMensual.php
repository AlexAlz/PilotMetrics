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
	$metasMensuales	= $datolAlDia->totalMetas($mestexto,$anioEnCurso);

	//Funciones para presentar los datos para llenar SELECT
	$tractoGral 	= $datolAlDia->obtenerDatosTracto();
	
	foreach ($metasMensuales AS $x) {
		$MetaGral 		= $x['MG'];
		$totalCamiones 	= $x['NC'];
		$totalDiasDelMes= $x['DM'];
		$diaVencido		= $x['DV'];
		$metaPorDia		= $x['MporD'];
		$metaPorCamion  = $x['MC'];
		$metaAdiaVencido= $x['MDV'];
		$mva = number_format($metaAdiaVencido,2);//Variable para mostrar en la grafica LINEA verde
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
							<p class="mb-1 pt-2 text-bold">Datos del <?php echo $date_start; ?> al <?php echo $date_end; ?></p>
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
<!-- END TABLA CON DATOS -->
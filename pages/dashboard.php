<?php
	//Verificacion de errores
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);	
	
	//Archivos a usar
	include_once("header-nav.php");
	include_once('../class/class.viajes.reales.php');
	include_once('../class/class.auto.select.php');
	include_once('../class/class.manejo.fechas.php');

	
	//Manejo de fechas
	date_default_timezone_set('America/Mexico_City');
	$date 				= new DateTime();
	$date->sub(new DateInterval('P1D'));
	$date 				= $date->format('Y-m-d');
	$Fechaprimerodemes	= date('Y-m-01');
	$diaEnCurso			= date('d');
	$mes 				= date('m');
	$anio 				= date('Y');
	$totaldiasDelMes 	= date('t');

	//Instancia y convercion de mes numero a mes letra
	$obtmes			= new ManejoDeFechas();
	$mestexto		= $obtmes->obtenerMes($mes);//Se pasa el dato extraido del sistema en numero y se convierte a texto para consulta

	$datolAlDia 	= new ViajesReales($Fechaprimerodemes, $date, $mestexto , $anio);
	$unidadNegocio	= new LlenadoAutDeSelect();
	

	//Funciones para presentar los datos para llenar SELECT
	$tractoGral 	= $datolAlDia->obtenerDatosTracto();
	$unidades 		= $unidadNegocio->selectUnidadNegocio();//Llena SELECT de formulario

	//Meta al dia
	$metasMensuales	= $datolAlDia->totalMetas($mestexto,$anio);
	
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
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
	<!-- Navbar  marcador de ruta y deslogeo-->
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
		<div class="container-fluid py-1 px-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
					<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Views</a>
					</li>
					<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
				</ol>
				<h6 class="font-weight-bolder mb-0">Dashboard</h6>
			</nav>
			<div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
				<div class="ms-md-auto pe-md-3 d-flex align-items-center">
				</div>
				<ul class="navbar-nav  justify-content-end">
					<li class="nav-item d-flex align-items-center">
						<a href="../index.html" class="nav-link text-body font-weight-bold px-0">
							<i class="fa fa-user me-sm-1"></i>
							<span class="d-sm-inline d-none">Sign In</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<!-- End Navbar -->
	<div class="container-fluid py-2">
		<!--FILA DE FORMULARIO -->
		<div class="row">
			<div class="col-lg-12">
				<div class="card">
					<form id="formConsumoPorTipoUnidad" method="POST">
						<table class="table table-sm text-center">
							<thead>
								<tr>
									<th scope="col" class="text-uppercase ">Fecha inicial</th>
									<th scope="col" class="text-uppercase ">Fecha final</th>
									<th scope="col" class="text-uppercase ">Unidad de Negocio</th>
									<th scope="col" class="text-uppercase ">Boton</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="align-middle"><!-- Fecha de inicio -->
										<div class="form-group">
											<br>
											<input class="form-control form-control-sm" type="date" id="date_start" name="date_start"><br>
										</div>
									</td>
									<td class="align-middle"><!-- Fecha de Termino -->
										<div class="form-group">
											<br>
											<input class="form-control form-control-sm" type="date" id="date_end" name="date_end"><br>
										</div>
									</td>
									<td class="align-middle"><!-- Unidad de Negocio -->
										<div class="form-group">
											<select class="form-control form-control-sm" id="uen" name="uen">										
												<option selected>Opciónes UEN</option>
												<?php $value = 1; foreach ($unidades as $unidad) : ?>
													<option value="<?= $unidad['uen']; ?>"><?= $unidad['uen']; ?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</td>
									<td class="align-middle"><!-- Boton -->
										<div class="form-group">
											<div class="nav-item d-flex justify-content-center">
												<input type="button" value="Consultar" class="btn btn-info btn-bg text-white" id="btn_consumo_por_unidad" name="btn_consumo_por_unidad">
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
				</div>
			</div>
		</div>
		<!-- END FILA DE FORMULARIO -->

		<div id="datosProcesados">

			<!-- GRAFICO CHARTjs -->
			<div class="row mt-4">
				<div class="col-lg-12 mb-lg-0 mb-4">
					<div class="card">
						<div class="card-body p-3">
							<div class="row">
								<div class="col-lg-12">
									<div class="d-flex flex-column h-100">
										<!-- <p class="mb-1 pt-2 text-bold">Datos del <?php echo $Fechaprimerodemes; ?> al <?php echo $date; ?></p> -->
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
			
		</div>
	</div>
	
	<?php
	include_once("footer.php");
	?>
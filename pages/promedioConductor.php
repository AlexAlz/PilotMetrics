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


// //Manejo de fechas
// date_default_timezone_set('America/Mexico_City');
// $date 				= new DateTime();
// $date->sub(new DateInterval('P1D'));
// $date 				= $date->format('Y-m-d');
// $Fechaprimerodemes	= date('Y-m-01');
// $diaEnCurso			= date('d');
// $mes 				= date('m');
$anio 				= date('Y');
//echo $aniomeno = $anio - 1;
// $totaldiasDelMes 	= date('t');

//Instancias
$meses = new ManejoDeFechas();
$mes   = $meses->obtMes();
$unidadNegocio	= new LlenadoAutDeSelect();
$unidades 		= $unidadNegocio->selectUnidadNegocio();//Llena SELECT de formulario

?>
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
	<!-- Navbar  marcador de ruta y deslogeo-->
	<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
		<div class="container-fluid py-1 px-3">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
					<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Views</a></li>
					<li class="breadcrumb-item text-sm text-dark active" aria-current="page">Promedio Conductor</li>
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
									<th scope="col" class="text-uppercase ">Año</th>
									<th scope="col" class="text-uppercase ">Mes</th>
									<th scope="col" class="text-uppercase ">Unidad de Negocio</th>
									<th scope="col" class="text-uppercase ">Boton</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="align-middle"><!-- Fecha de inicio -->
										<div class="form-group">
											<div class="mb-3">
												<select multiple class="form-select form-select-lg" name="sel_anios" id="sel_anios">
													<option selected>Seleccione el año o años</option>
													<option value="2023">2023</option>
													<option value="2022">2022</option>
													<option value="2021">2021</option>
												</select>
											</div>
										</div>
									</td>
									<td class="align-middle"><!-- Fecha de Termino -->
										<div class="form-group">
											<div class="mb-3">
												<select multiple class="form-select form-select-lg" name="sel_meses" id="sel_meses">
													<option selected>Select one</option>
													<?php foreach ($mes AS $ms) :?>
														<option value="<?= $ms['mes']; ?>"><?= $ms[	'mes']; ?></option>
													<?php endforeach ; ?>
												</select>
											</div>
										</div>
									</td>
									<td class="align-middle"><!-- Unidad de Negocio -->
										<div class="form-group">
											<div class="mb-3">
											  	<select multiple class="form-select form-select-lg" name="" id="">
													<option selected>Opciónes UEN</option>
													<?php
													foreach ($unidades as $unidad) : ?>
														<option value="<?= $unidad['uen']; ?>"><?= $unidad['uen']; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</td>
									<td class="align-middle"><!-- Boton -->
										<div class="form-group">
											<div class="nav-item d-flex justify-content-center">
												<input type="button" value="Consultar" class="btn btn-info btn-bg text-white" id="btn_promedio_conductor" name="btn_promedio_conductor">
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

		<div id="salarioConductor">
			<!-- Área para mostrar información -->
		</div>
	</div>

	<?php
	include_once("footer.php");
	?>
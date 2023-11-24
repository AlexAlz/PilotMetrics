<?php
include_once('class.conexion.php');

class Paginacion extends Conexion {
     
    public $conexion;
    public $Fechaprimerodemes;
    public $dateStart;
    public $dateEnd;
    public $consultaCount;
    public $numPorPaginas;

    //variables para la paginacion
    private $paginaActual;
    private $totalPaginas;
    private $nResultados;
    private $resultadosPorPagina;
    private $indice;//apunta en que posicion se encuentra
    private $error = false;

    function __construct($dateStart, $dateEnd, $consultaCount, $numPorPaginas)
    {   
        $this->conexion     =   $this->connect();
        $this->indice               = 0;
        $this->paginaActual         = 1;
        $this->resultadosPorPagina  = $numPorPaginas;
        $this->dateStart    =   $dateStart;
        $this->dateEnd      =   $dateEnd;
        $this->consultaCount=   $consultaCount;
        $this->Fechaprimerodemes = date('Y-m-01');
        $this->calcularPaginas();
    }
    

    //Paginacion
    function calcularPaginas(){
        
        $stmt = $this->conexion->prepare($this->consultaCount);
        $stmt->execute([$this->dateStart . ' 00:00:00', $this->dateEnd . ' 23:00:00']);
        $this->nResultados = $stmt->fetch(PDO::FETCH_OBJ)->total;
        $this->totalPaginas = ceil($this->nResultados / $this->resultadosPorPagina);
        
        //mapear para modificar la pagina actual
        if (isset($_GET['pagina'])) {
            //Validar que pagina sea mayor a un numero
            if (is_numeric($_GET['pagina'])) {
                //validar que pagina sea mayor o igual a 1 y menor o igual al total
                if ($_GET['pagina'] >= 1 && $_GET['pagina'] <= $this->totalPaginas) {
                    $this->paginaActual = $_GET['pagina'];
                    $this->indice = ($this->paginaActual - 1) * $this->resultadosPorPagina;
                } else {
                    echo "No existe esa página";
                    $this->error = true;
                }
            } else {
                echo "Error al mostrar la página";
                $this->error = true;
            }
        }
        return $this->totalPaginas;
    }
}
?>
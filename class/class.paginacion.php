<?php
include_once('class.conexion.php');

class Paginacion extends Conexion {
     
    public $con;
    public $dateStart;
    public $dateEnd;
    private $consultaCount;//Consulta

    //variables para la paginacion
    private $paginaActual;
    private $totalPaginas;
    private $nResultados;
    private $resultadosPorPagina;
    private $indice;//apunta en que posicion se encuentra
    private $error = false;

    function __construct($consultaCount, $dateStart, $dateEnd, $nPorPagina)
    {   
        $this->resultadosPorPagina = $nPorPagina;
        $this->indice = 0;
        $this->paginaActual = 1;
        $this->calcularPaginas();
        
        $this->con = $this->connect();
        $this->dateStart    =   $dateStart;
        $this->dateEnd      =   $dateEnd;
        $this->consultaCount=   $consultaCount;//Consulta
    }
    

    //Paginacion__
    function calcularPaginas(){
        $stmt = $this->connect()->prepare($this->consultaCount);
        // $consultaCount = "SELECT COUNT(DISTINCT(tracto)) AS total FROM viajesreales WHERE DATE(fecha) >= ? AND DATE(fecha) <= ? ";
        // $stmt = $this->connect()->prepare($this->consultaCount);
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
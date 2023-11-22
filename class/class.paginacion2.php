<?php
require_once("class.conexion.php");//INCLUIMOS LA CLASE -> CONEXION

class ViajesReales extends Conexion {
    public $con;
    public $dateStart;
    public $dateEnd;
    public $Fechaprimerodemes;
    //VARIABLE PARA LA PAGINACION
    private $paginaActual;
    private $totalPaginas;
    private $nResultados;
    private $resultadosPorPagina;
    private $indice;//apunta en que posicion se encuentra
    private $error = false;

    function __construct($dateStart, $dateEnd, $nPorPagina) {
        //VARIABLES PARA LA PAGINACION
        $this->resultadosPorPagina = $nPorPagina;
        $this->indice = 0;
        $this->paginaActual = 1;
        $this->calcularPaginas();

        $this->con = $this->connect();
        $this->dateStart   =   $dateStart;
        $this->dateEnd     =   $dateEnd;
        $this->Fechaprimerodemes = date('Y-m-01');
    }

    //Paginacion
    function calcularPaginas(){
        $consultaCount = "SELECT COUNT(DISTINCT(tracto)) AS total FROM viajesreales WHERE DATE(fecha) >= ? AND DATE(fecha) <= ? ";
        $stmt = $this->connect()->prepare($consultaCount);
        $stmt->execute([$this->Fechaprimerodemes . ' 00:00:00', $this->dateEnd . ' 23:00:00']);
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

    //FUNCION PARA TRAER IMPORTE GENERAL ACUMULADO POR UEN MENSUAL
    function obtenerDatosTracto() {
        //Llamamos la funcion paginación
        $this->calcularPaginas();

        if (!$this->error) {
            $consulta = "SELECT
                            a.tracto AS tracto,
                            SUM(a.importetotal + a.importetotal2) AS total,
                            a.origen AS UEN
                        FROM
                            viajesreales a
                        WHERE
                            a.fecha >= ? 
                            AND a.fecha <= ?
                        GROUP BY
                            a.tracto
                        ORDER BY
                            total DESC
                        LIMIT ?, ?";
            $stmt = $this->con->prepare($consulta);
            $stmt->bindValue(1, $this->Fechaprimerodemes . ' 00:00:00', PDO::PARAM_STR);
            $stmt->bindValue(2, $this->dateEnd . ' 23:00:00', PDO::PARAM_STR);
            $stmt->bindValue(3, $this->indice, PDO::PARAM_INT);
            $stmt->bindValue(4, $this->resultadosPorPagina, PDO::PARAM_INT);
            $stmt->execute();
            $datosTracto = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $datosTracto;
        } else {
            echo "Error al procesar los datos";
                $this->error = true;
        }
    }

    // Funcion para sacar la meta y media
    function totalMetas($mesEnCurso,$anioEnCurso) {
        $metaaldia = "SELECT
                        COUNT(DISTINCT meta) AS cantidadDeMeta,
                        SUM(DISTINCT meta) AS SumaMetaGral
                    FROM
                        metatractor a
                    WHERE
                        a.mestm = ?
                        AND a.anactualt = ?
                        AND a.meta != '0'
                    ";
        $stmt = $this->connect()->prepare($metaaldia);
        $stmt->execute([$mesEnCurso,$anioEnCurso]);
        $metaAlDiaTranscurrido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $metaAlDiaTranscurrido;
    }
}

?>
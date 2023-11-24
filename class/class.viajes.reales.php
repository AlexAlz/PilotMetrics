    <?php
    require_once("class.conexion.php");

    class ViajesReales extends Conexion {
        public $con;
        public $dateStart;
        public $dateEnd;
        public $Fechaprimerodemes;
        //variables para la paginacion
        //private $paginaActual;
        private $totalPaginas;
        private $nResultados;
        private $resultadosPorPagina;
        //private $indice;//apunta en que posicion se encuentra
        private $error = false;
        private $mes;
	    private $anio;

        function __construct($dateStart, $dateEnd, $mes , $anio ) {
            //Parte de la paginacion
            // $this->resultadosPorPagina = $nPorPagina;
            // $this->indice = 0;
            // $this->paginaActual = 1;

            $this->con = $this->connect();
            //borrar var dump
            $this->dateStart   =   $dateStart;
            $this->dateEnd     =   $dateEnd;
            $this->mes   =  $mes;
            $this->anio  =  $anio;
            $this->Fechaprimerodemes = date('Y-m-01');
        }

        // Funcion trafo importe acumulado mensual
        function obtenerDatosTracto() {

            if (!$this->error) {
                $consulta = "SELECT 
                                a.tracto AS tracto, 
                                SUM(a.importetotal + a.importetotal2) AS total,
                                a.origen AS UEN,
                                b.meta AS meta
                            FROM 
                                viajesreales a
                                INNER JOIN metatractor b ON a.tracto = b.eco
                            WHERE
                                a.fecha         >= ? 
                                AND a.fecha     <= ?
                                AND b.mestm     = ?
                                AND b.anactualt = ?
                            GROUP BY
                                a.tracto
                            ORDER BY
                                total DESC";
                $stmt = $this->con->prepare($consulta);
                $stmt->bindValue(1, $this->Fechaprimerodemes . ' 00:00:00', PDO::PARAM_STR);
                $stmt->bindValue(2, $this->dateEnd . ' 23:00:00', PDO::PARAM_STR);
                $stmt->bindValue(3, $this->mes, PDO::PARAM_STR);
                $stmt->bindValue(4, $this->anio, PDO::PARAM_INT);
                                
                if (!$stmt->execute()) {
                    var_dump($stmt->errorInfo());
                }
                $datosTracto = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //$stmt->debugDumpParams();//IMPRIMIR CONSULTA CON DATOS SETEADOS SE COLOCA DESPUES DES EXECUTE   
                return $datosTracto;
                
            } else {
                echo "Error al procesar los datos";
                    $this->error = true;
            }
        }

        // Funcion para sacar la meta y media
        function totalMetas($mes,$anio) {
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
            $stmt->execute([$mes,$anio]);
            $metaAlDiaTranscurrido = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $metaAlDiaTranscurrido;
        }
    }

    ?>
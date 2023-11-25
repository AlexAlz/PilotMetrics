    <?php
    require_once("class.conexion.php");

    class ViajesReales extends Conexion {
        public $con;
        public $dateStart;
        public $dateEnd;
        public $Fechaprimerodemes;
        //private $indice;//apunta en que posicion se encuentra
        private $error = false;
        private $mes;
	    private $anio;

        function __construct($dateStart, $dateEnd, $mes , $anio ) {

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
            $metaAdiaVencido = "SELECT
                            t1.metaGral AS MG,
                            t1.NumeroDeCamiones AS NC,
                            t1.diasDelMes AS DM,
                            t1.diaVencido AS DV,
                            /*Meta_por_diaria = meta_general / total_de_dias_al _mes*/
                            ( t1.metaGral/ t1.diasDelMes ) AS MporD,
                            /*Meta_por_camion = meta_por_dia / numero_de_camiones*/
                            ( ( t1.metaGral/ t1.diasDelMes ) / t1.NumeroDeCamiones ) AS MC,
                            /*Meta_a_dia_vencido */
                            ( ( t1.metaGral/ t1.diasDelMes ) / t1.NumeroDeCamiones ) * t1.diaVencido AS MDV
                        FROM
                            (
                                SELECT
                                    SUM(a.meta) AS MetaGral,
                                    COUNT(a.operacion) AS NumeroDeCamiones,
                                    DAY(LAST_DAY(NOW())) AS diasDelMes,
                                    DATEDIFF(CURDATE(), DATE_FORMAT(CURDATE() ,'%Y-%m-01')) AS diaVencido
                                FROM
                                    metatractor a
                                WHERE
                                    a.mestm = ?
                                    AND a.anactualt = ?
                            ) AS t1";
            $stmt = $this->connect()->prepare($metaAdiaVencido);
            $stmt->execute([$mes,$anio]);
            $metaAlDiaTranscurrido = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $metaAlDiaTranscurrido;
        }
    }

    ?>
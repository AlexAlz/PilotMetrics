<?php
    require_once("class.conexion.php");
    

    class ViajesReales extends Conexion {
        public $con;
        public $date_start;
        public $date_end;
        public $Fechaprimerodemes;
        public $unidadDeNegacio;
        
        private $error = false;
        private $mes;
	    private $anio;
        private $meses;

        function __construct($date_start, $date_end, $mes , $anio, $unidadDeNegacio) {
            $this->con = $this->connect();
            // var_dump ( $date_start);
            // var_dump ( $date_end);
            // var_dump ( $unidadDeNegacio);
            if ($date_start == null && $date_end == null) {
                $this->date_start   =  date('Y-m-01');
                $this->date_end     =   date('Y-m-t');
                
            }else{
                $this->date_start   =   $date_start;
                $this->date_end     =   $date_end;
                // var_dump ( $this->date_start);
                // var_dump ( $this->date_end);
            }
        
            $this->mes   =  $mes;
            $this->anio  =  $anio;
            $this->Fechaprimerodemes = date('Y-m-01');
            $this->unidadDeNegacio      =   $unidadDeNegacio;
        
            $fechaInicio = new DateTime($this->date_start);
            $fechaFin = new DateTime($this->date_end);
            $intervalo = DateInterval::createFromDateString('1 month');
        
            $meses = "";
        
            $nombresMeses = [
                1 => "Enero",
                2 => "Febrero",
                3 => "Marzo",
                4 => "Abril",
                5 => "Mayo",
                6 => "Junio",
                7 => "Julio",
                8 => "Agosto",
                9 => "Septiembre",
                10 => "Octubre",
                11 => "Noviembre",
                12 => "Diciembre"
            ];
        
            while ($fechaInicio <= $fechaFin) {
                $numeroMes = (int)$fechaInicio->format("n");
                $meses .= "'" . $nombresMeses[$numeroMes] . "',";
                $fechaInicio->add($intervalo);
            }
        
            $meses = rtrim($meses, ",");
            $this->meses = $meses;
        }
        
        function obtenerDatosTracto() {
            if (!$this->error) {
                $consulta = "SELECT 
                                a.tracto AS tracto, 
                                SUM(a.importetotal + a.importetotal2) AS total,
                                b.operacion AS UEN,
                                b.meta AS meta
                            FROM 
                                viajesreales a
                                INNER JOIN metatractor b ON a.tracto = b.eco
                            WHERE
                                b.mestm = ?
                                AND b.anactualt = ?";
                                
                
                $swhere = "";
                
                // Añade las condiciones de fecha si se proporcionaron
                if ($this->date_start != null && $this->date_end != null) {
                    $swhere .= " AND DATE(a.fecha) >= ? AND DATE(a.fecha) <= ?";
                }
                
                // Añade la condición específica si unidadDeNegacio no está vacía y es diferente de 'Opciónes UEN'
                if (!empty($this->unidadDeNegacio) && $this->unidadDeNegacio != 'Opciónes UEN') {
                    echo 'hola';
                    $swhere .= " AND b.operacion = ?";
                }
                //echo $this->unidadDeNegacio;
                
                $consulta .= $swhere . " GROUP BY a.tracto ORDER BY total DESC";
                $stmt = $this->con->prepare($consulta);
                $stmt->bindValue(1, $this->mes, PDO::PARAM_STR);
                $stmt->bindValue(2, $this->anio, PDO::PARAM_INT);
                $i = 3;
                if ($this->date_start != null && $this->date_end != null) {
                    $stmt->bindValue($i++, $this->date_start, PDO::PARAM_STR);
                    $stmt->bindValue($i++, $this->date_end, PDO::PARAM_STR);
                }
                if (!empty($this->unidadDeNegacio) && $this->unidadDeNegacio != 'Opciónes UEN') {
                    $stmt->bindValue($i++, $this->unidadDeNegacio, PDO::PARAM_STR);
                    echo 'hola';
                }
                //echo $this->unidadDeNegacio;
                if (!$stmt->execute()) {
                    var_dump($stmt->errorInfo());
                }
                $datosTracto = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //$stmt->debugDumpParams();
                return $datosTracto;
            } else {
                echo "Error al procesar los datos";
                $this->error = true;
            }
        }

        function obtenerDatosTractoSinViajes() {
            $consultaTractosSinsViajes = "SELECT
                                            mt.eco AS eco,
                                            mt.mestm,
                                            mt.anactualt,
                                            mt.operacion AS operacion
                                        FROM
                                            metatractor mt
                                        WHERE
                                            mt.eco NOT IN (
                                                SELECT
                                                    vr.tracto
                                                FROM
                                                    viajesreales vr
                                                WHERE
                                                    DATE(vr.fecha) IN ( ? )
                                                    AND DATE(vr.fecha) <= ?
                                            )
                                        AND mt.mestm = ?
                                        AND mt.anactualt = ?;";
            $stmt   =   $this->con->prepare($consultaTractosSinsViajes);
            $stmt->bindValue(1, $this->Fechaprimerodemes, PDO::PARAM_STR);
            $stmt->bindValue(2, $this->date_end, PDO::PARAM_STR);
            $stmt->bindValue(3, $this->mes, PDO::PARAM_STR);
            $stmt->bindValue(4, $this->anio, PDO::PARAM_STR);
            $stmt->execute();
            $datosTractosSinViaje = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$stmt->debugDumpParams();
            return $datosTractosSinViaje;
        }
        function vehiculosSinMeta() {
            $consultavehiculosSinMeta = "SELECT
                                            vh.eco AS eco,
                                            vh.operacion AS operacion
                                        FROM
                                            vehiculos vh
                                        WHERE
                                            vh.eco NOT IN (
                                                SELECT
                                                    mt.eco
                                                FROM
                                                    metatractor mt
                                                WHERE
                                                    /*MES EN CURSO */
                                                    mt.mestm = ?
                                                    /*AÑO EN CURSO */
                                                    AND mt.anactualt <= ? 
                                            )
                                            AND vh.estatus != 'Baja'
                                            AND vh.tipo = 'Tractor';";
            $stmt   =   $this->con->prepare($consultavehiculosSinMeta);
            $stmt->bindValue(1, $this->mes, PDO::PARAM_STR);
            $stmt->bindValue(2, $this->anio, PDO::PARAM_STR);
            $stmt->execute();
            $datosVehucilosSinMeta = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$stmt->debugDumpParams();
            return $datosVehucilosSinMeta;
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
                                    AND a.operacion != 'Local'
                            ) AS t1";
            $stmt = $this->connect()->prepare($metaAdiaVencido);
            $stmt->execute([$mes,$anio]);
            $metaAlDiaTranscurrido = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$stmt->debugDumpParams();//IMPRIMIR CONSULTA CON DATOS SETEADOS SE COLOCA DESPUES DES EXECUTE 
            return $metaAlDiaTranscurrido;
        }

    
        // function totalMetasPorUen ($mes,$anio,$unidadDeNegacio) {
        //     $metaAdiaVencido = "SELECT
        //                     t1.metaGral AS MG,
        //                     t1.NumeroDeCamiones AS NC,
        //                     t1.diasDelMes AS DM,
        //                     t1.diaVencido AS DV,
        //                     /*Meta_por_diaria = meta_general / total_de_dias_al _mes*/
        //                     ( t1.metaGral/ t1.diasDelMes ) AS MporD,
        //                     /*Meta_por_camion = meta_por_dia / numero_de_camiones*/
        //                     ( ( t1.metaGral/ t1.diasDelMes ) / t1.NumeroDeCamiones ) AS MC,
        //                     /*Meta_a_dia_vencido */
        //                     ( ( t1.metaGral/ t1.diasDelMes ) / t1.NumeroDeCamiones ) * t1.diaVencido AS MDV
        //                 FROM
        //                     (
        //                         SELECT
        //                             SUM(a.meta) AS MetaGral,
        //                             COUNT(a.operacion) AS NumeroDeCamiones,
        //                             DAY(LAST_DAY(NOW())) AS diasDelMes,
        //                             DATEDIFF(CURDATE(), DATE_FORMAT(CURDATE() ,'%Y-%m-01')) AS diaVencido
        //                         FROM
        //                             metatractor a
        //                         WHERE
        //                             a.mestm = ?
        //                             AND a.anactualt = ?
        //                             AND a.operacion != 'Local'
        //                             AND a.operacion = ?
        //                     ) AS t1";
        //     $stmt = $this->connect()->prepare($metaAdiaVencido);
        //     $stmt->execute([$this->mes,$anio,$this->unidadDeNegacio]);
        //     $metaAlDiaTranscurrido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //     $stmt->debugDumpParams();//IMPRIMIR CONSULTA CON DATOS SETEADOS SE COLOCA DESPUES DES EXECUTE 
        //     return $metaAlDiaTranscurrido;
        // }

        function totalMetasPorUen ($mes,$anio,$unidadDeNegacio) {
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
                                        AND a.operacion != 'Local'";
        
            $params = [$mes, $anio];
        
            if (!empty($unidadDeNegacio) && $unidadDeNegacio != 'Opciónes UEN') {
                $metaAdiaVencido .= " AND a.operacion = ?";
                $params[] = $unidadDeNegacio;
            }
        
            $metaAdiaVencido .= ") AS t1";
        
            $stmt = $this->connect()->prepare($metaAdiaVencido);
            $stmt->execute($params);
            $metaAlDiaTranscurrido = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //$stmt->debugDumpParams();//IMPRIMIR CONSULTA CON DATOS SETEADOS SE COLOCA DESPUES DES EXECUTE 
            return $metaAlDiaTranscurrido;
        }
        
        

    }

    ?>
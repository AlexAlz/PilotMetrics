<?php
include_once("class.conexion.php");

class LlenadoAutDeSelect extends Conexion
{

    private $con;

    function __construct()
    {

        $this->con = $this->connect();
    }

    function selectUnidadNegocio()
    {
        $consulta = "SELECT
                        a.unidadnegocio AS uen
                    FROM
                        unidad_negocio a
                    GROUP BY 
                        a.unidadnegocio
                    ORDER BY
                        a.unidadnegocio ASC;";
        $stmt = $this->connect()->prepare($consulta);
        $stmt->execute();
        $datosUnidadNegocio = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $datosUnidadNegocio;
    }
}

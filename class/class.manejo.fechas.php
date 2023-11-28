<?php
include_once("class.conexion.php");
class ManejoDeFechas extends Conexion{

    private $con;

    function __construct()
    {

        $this->con = $this->connect();
    }


    //Seleccion de mes
    function obtenerMes($numero) {
        switch ($numero) {
            case 1:
                return "Enero";
            case 2:
                return "Febrero";
            case 3:
                return "Marzo";
            case 4:
                return "Abril";
            case 5:
                return "Mayo";
            case 6:
                return "Junio";
            case 7:
                return "Julio";
            case 8:
                return "Agosto";
            case 9:
                return "Septiembre";
            case 10:
                return "Octubre";
            case 11:
                return "Noviembre";
            case 12:
                return "Diciembre";
            default:
                return "Número inválido. Por favor, ingresa un número del 1 al 12.";
        }
    }

    function obtMes() {
        $obtMeses = "SELECT mes FROM meses";
        $stmt = $this->con->prepare($obtMeses);
        $stmt->execute();
        $stmes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $stmes;
    }
}
?>
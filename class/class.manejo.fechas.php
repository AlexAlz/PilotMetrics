<?php
class ManejoDeFechas {

    function diasTranscurridos() {
        // Obtén el día actual
        echo $diaActual = date('j');
    
        // Obtén el número total de días en el mes actual
        echo $diasEnElMes = date('t');
    
        // Calcula los días transcurridos
        echo $diasTranscurridos = $diasEnElMes - $diaActual;
    
        return $diasTranscurridos;
    }

 }

?>
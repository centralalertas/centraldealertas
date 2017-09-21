<?php

class FuncoesHelper extends AppHelper {
    
    var $arrayThreaded   = array();
    
    function selectThreaded($registros, $dominio) {
        
        $this->arrayThreaded = array();
        foreach ($registros as $registro):
            $this->setArrayThreaded($registro, $dominio);
        endforeach;

        //echo "<pre>";print_r($this->arrayThreaded);echo "</pre>";exit();
        
        return $this->arrayThreaded;
        
    }
    
    private function setArrayThreaded($registro, $dominio, $nivel = 0) {
            
        $espacos    = $this->getEspacosPorNivel($nivel);
        $this->arrayThreaded[ $registro[$dominio]['id'] ] = $espacos . $registro[$dominio]['nome'];

        foreach ($registro['children'] as $filho):
            $novo_nivel = $nivel + 1;
            $this->setArrayThreaded($filho, $dominio, $novo_nivel);
        endforeach;
        
    }
    
    private function getEspacosPorNivel($nivel)
    {
        if ($nivel == 0) {
            return "";
        } else {
            $nivel = $nivel * 3;
            $espacos = '';
            for ($i = 0; $i < $nivel; $i ++) {
                $espacos .= "&nbsp; ";
            }
            return $espacos;
        }
    }
    
    /**
     * Converte a data para o formato americano
     *
     * @param unknown_type $data
     * @return unknown
     */
    function formateDate( &$data )
    {
        if ( strstr( $data, "/" ) ) {
            $d = explode( "/", $data );
            $rstData = "$d[2]-$d[1]-$d[0]";
            $data = $rstData;
        }
        return $data;
    }

    /**
     * Converte a data para o formato americano
     *
     * @param unknown_type $data
     * @return unknown
     */
    function formateDateTime( &$data )
    {
        if ( strstr( $data, "/" ) ) {
            $time = substr( $data, 11, 8 );
            $date = substr( $data, 0, 10 );

            $data = $this->formateDate( $date );

            if ( $time ) {
                $data = $data . ' ' . $time;
            }
        }

        return $data;
    }
    
}

?>
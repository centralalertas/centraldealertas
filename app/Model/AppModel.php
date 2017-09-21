<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    public function beforeValidate( $options = array() )
    {
        foreach ( $this->_schema as $field => $attr ) {
            switch ( $attr[ 'type' ] ) {
                case 'date':
                    $this->formateDate( $this->data[ $this->name ][ $field ] );
                    break;
                case 'datetime':
                    $this->formateDateTime( $this->data[ $this->name ][ $field ] );
                    break;
            }
        }
        return true;
    }
    
    public function afterFind( $results, $primary = false )
    {
        if(isset( $this->_schema )) {
            foreach ( $this->_schema as $field => $attr ) {
                switch ( $attr[ 'type' ] ) {
                        case 'date':
                                $function = "data";
                                break;
                        case 'datetime':
                                $function = "dataHora";
                                break;
                        default:
                                $function = "";
                                break;
                }
                if ( !empty( $function ) ) {
                        if ( isset( $results[ $this->name ][ $field ] ) ) {

                                self::$function( $results[ $this->name ][ $field ] );
                        } else {
                                foreach ( $results as $key => $out ) {
                                    if ( isset( $results[ $key ] ) && isset( $results[ $key ][ $this->name ] ) && isset( $results[ $key ][ $this->name ][ $field ] ) ) {
                                            self::$function( $results[ $key ][ $this->name ][ $field ] );
                                        }
                                }
                        }
                }
            }
        }

        return $results;
    }
    
    /**
     * Retorna a data no formato brasileiro
     */
    static function data( &$sInput )
    {
            $sInput = strtotime( $sInput );

            if ( empty( $sInput ) ) {
                    return null;
            }
            $sInput = date( "d/m/Y", $sInput );

            return $sInput;
    }

    /**
     * Retorna a data e hora no formato brasileiro
     */
    static function dataHora( &$sInput )
    {
            $sInput = strtotime( $sInput );

            if ( empty( $sInput ) ) {
                    return null;
            }
            $sInput = date( "d/m/Y H:i:s", $sInput );

            return $sInput;
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

<?php

    if (!class_exists('BaseConexion'))
        include 'BaseConexion.php';
    
    if (!class_exists('BaseGenerales'))
        include 'BaseGenerales.php';
    
    class ParametrosEstados
    {
        function arrConsultarEstados($intCodigo)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "select * from tb_par_estados where esta_codigo = ".$intCodigo." or ".$intCodigo." = -1;";
                
                $arrConsulta = $obClsCnx->arrEjecutarConsulta($strSentencia);
                
                return $arrConsulta;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function strListarEstados($intCodigo, $strDescripcion, $strName, $strTextAdd)
        {
            try
            {
                $obClsGral = new BaseGenerales();
                
                $arrEstados = self::arrConsultarEstados($intCodigo);
                
                $strLista = $obClsGral->strCargarLista($strName, $arrEstados, 'esta_codigo', 'esta_descripcion', $strTextAdd);
                
                return $strLista;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
    }

?>
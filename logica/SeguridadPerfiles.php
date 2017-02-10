<?php

    if (!class_exists('BaseConexion'))
        include 'BaseConexion.php';
    
    if (!class_exists('BaseGenerales'))
        include 'BaseGenerales.php';
    
    class SeguridadPerfiles
    {
        function insertarPerfil($strDescrpicion)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                session_start();
                
                $strSentencia = "insert into tb_seg_perfiles (perf_descripcion, perf_uc) 
                    values ('".$strDescrpicion."', ".$_SESSION['usua_cod'].");";
                
                $obClsCnx->ejecutarSentencia($strSentencia);
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function arrConsultarPerfiles($intCodigo, $strDescripcion, $intEstado)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "select perf.perf_codigo, perf.perf_descripcion, perf.fk_par_estados, esta.esta_descripcion
                    from tb_seg_perfiles perf 
                    join tb_par_estados esta on (esta.esta_codigo = perf.fk_par_estados)
                    where (perf.perf_codigo = ".$intCodigo." or ".$intCodigo." = -1)
                    and (upper(perf.perf_descripcion) like concat('%', upper('".$strDescripcion."'), '%') or '".$strDescripcion."' = '-1')
                    and (perf.fk_par_estados = ".$intEstado." or ".$intEstado." = -1);";
                
                $arrConsulta = $obClsCnx->arrEjecutarConsulta($strSentencia);
                
                return $arrConsulta;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function modificarPerfil($intCodigo, $strDescripcion, $intEstado)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                session_start();
                
                $strSentencia = "update tb_seg_perfiles set perf_descripcion = '".$strDescripcion."', 
                    fk_par_estados = ".$intEstado.", perf_um = ".$_SESSION['usua_cod'].", perf_fm = now() 
                    where perf_codigo = ".$intCodigo.";";
                
                $obClsCnx->ejecutarSentencia($strSentencia);
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function eliminarPerfil($intCodigo)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "delete from tb_seg_perfiles where perf_codigo = ".$intCodigo.";";
                
                $obClsCnx->ejecutarSentencia($strSentencia);
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function strListaPerfiles($intCodigo, $strDescripcion, $intEstado, $strTextAdd, $strName)
        {
            try
            {
                $arrPerfiles = self::arrConsultarPerfiles($intCodigo, $strDescripcion, $intEstado);
                
                $obClsGral = new BaseGenerales();
                
                $strLista = $obClsGral->strCargarLista($strName, $arrPerfiles, 'perf_codigo', 'perf_descripcion', $strTextAdd);
                
                return $strLista;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
    }

?>
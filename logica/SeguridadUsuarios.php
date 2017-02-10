<?php

    if (!class_exists('BaseConexion'))
        include 'BaseConexion.php';
    
    class SeguridadUsuarios
    {
        function validarUsuario($strLogin, $strPassword)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "select usua_codigo, usua_login, usua_password, usua_nombre, usua_mail, fk_seg_perfiles, fk_par_estados  
                    from tb_seg_usuarios where (usua_login = '".$strLogin."');";
					
				$arrUsuario = $obClsCnx->arrEjecutarConsulta($strSentencia);
				
				if (count($arrUsuario) != 1)
                    throw new Exception('Verifique su nombre de usuario');

                if ($arrUsuario[0]['usua_password'] != $strPassword)
                    throw new Exception('Verifique su contraseña');
                
                if ($arrUsuario[0]['fk_par_estados'] != 1)
                    throw new Exception('Su cuenta no está activa, comuniquese con un administrador del sistema');
                
                //if (!$_SESSION)
                    session_start();
                $_SESSION['usua_cod'] = $arrUsuario[0]['usua_codigo'];
                $_SESSION['usua_log'] = $arrUsuario[0]['usua_login'];
                $_SESSION['usua_nom'] = $arrUsuario[0]['usua_nombre'];
                $_SESSION['usua_mai'] = $arrUsuario[0]['usua_mail'];
                $_SESSION['usua_per'] = $arrUsuario[0]['fk_seg_perfiles'];
                $_SESSION['usua_est'] = $arrUsuario[0]['fk_par_estados'];
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function insertarUsuario($strLogin, $strPassword, $strNombre, $strMail, $intPerfil)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                session_start();
                
                $strSentencia = "insert into tb_seg_usuarios (usua_login, usua_password, usua_nombre, usua_mail, fk_seg_perfiles, usua_uc) 
                    values ('".$strLogin."', '".  md5($strPassword)."', '".$strNombre."', '".$strMail."', ".$intPerfil.", ".$_SESSION['usua_cod'].");";
					
				$obClsCnx->ejecutarSentencia($strSentencia);
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
                
        function arrConsultarUsuario($intCodigo, $strLogin, $strNombre, $intPerfil, $intEstado)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "select USUA.usua_codigo, USUA.usua_login, USUA.usua_nombre, USUA.usua_mail, 
                    USUA.fk_seg_perfiles, PERF.perf_descripcion, USUA.fk_par_estados, ESTA.esta_descripcion
                    from tb_seg_usuarios USUA 
                    join tb_seg_perfiles PERF on (PERF.perf_codigo = USUA.fk_seg_perfiles) 
                    join tb_par_estados ESTA on (ESTA.esta_codigo = USUA.fk_par_estados)
                    where (USUA.usua_codigo = ".$intCodigo." or ".$intCodigo." = -1)
                    and (upper(USUA.usua_login) like(concat('%', upper('".$strLogin."'), '%')) or '".$strLogin."' = '-1')
                    and (upper(USUA.usua_nombre) like(concat('%', upper('".$strNombre."'), '%')) or '".$strNombre."' = '-1')
                    and (USUA.fk_seg_perfiles = ".$intPerfil." or ".$intPerfil." = -1)
                    and (USUA.fk_par_estados = ".$intEstado." or ".$intEstado." = -1);";
                
                $arrConsulta = $obClsCnx->arrEjecutarConsulta($strSentencia);
                
                return $arrConsulta;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
		
		function actualizarUsuario($strLogin, $strPassword, $strNombre, $strMail, $intPerfil, $intEstado)
		{
			try
			{
                $obClsCnx = new BaseConexion();
                
                session_start();
				
				$strCambioClave = $strPassword != '' ? "'".md5($strPassword)."'" : 'usua_password';
				
				$strSentencia = "update tb_seg_usuarios 
					set usua_nombre = '".$strNombre."', ".
					"usua_password = ".$strCambioClave.", ".
					"usua_mail = '".$strMail."', 
					fk_seg_perfiles = ".$intPerfil.", 
					fk_par_estados = ".$intEstado.",
					usua_um = ".$_SESSION['usua_cod']." 
					where usua_login = '".$strLogin."';";
					
				$obClsCnx->ejecutarSentencia($strSentencia);
			}
			catch (Exception $ex)
			{
				throw new Exception($ex->getMessage());
			}
		}
		
		function eliminarUsuario($intCodigo)
		{
			try
			{
				$obClsCnx = new BaseConexion();
				
				$strSentencia = "delete from tb_seg_usuarios where usua_codigo = ".$intCodigo.";";
				
				$obClsCnx->ejecutarSentencia($strSentencia);
			}
			catch (Exception $ex)
			{
				throw new Exception($ex->getMessage());
			}
		}
		
		function cambiarPassword($strPassword)
		{
			try 
			{
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "update tb_seg_usuarios set usua_password = '".md5($strPassword)."' where usua_codigo = ".$_SESSION['usua_cod'].";";
				
				$obClsCnx->ejecutarSentencia($strSentencia);	
			}
			catch (Exception $ex)
			{
				throw new Exception($ex->getMessage());
			}
		}
    }

?>
<?php

    if (!class_exists('BaseConexion'))
        include 'BaseConexion.php';
    
    class SeguridadPermisos
    {
        function arrConsultarModulos($intPerfil)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "select OPCI.fk_seg_modulos, MODU.modu_descripcion 
                    from tb_seg_opciones OPCI 
                    JOIN tb_seg_permisos PERM on (PERM.fk_seg_opciones = OPCI.opci_codigo)
                    JOIN tb_seg_modulos MODU on (MODU.modu_codigo = OPCI.fk_seg_modulos)
                    where (PERM.fk_seg_perfiles = ".$intPerfil.")
                    group by OPCI.fk_seg_modulos 
                    order by OPCI.fk_seg_modulos desc";
                
                $arrConsulta = $obClsCnx->arrEjecutarConsulta($strSentencia);
                
                return $arrConsulta;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function arrConsultarPermisos($intPerfil)
        {
            try
            {
                $obClsCnx = new BaseConexion();
                
                $strSentencia = "select PERM.perm_codigo, PERM.fk_seg_perfiles, PERF.perf_descripcion, 
                    OPCI.fk_seg_modulos,MODU.modu_descripcion, MODU.modu_icono, PERM.fk_seg_opciones, OPCI.opci_nombre, 
                    OPCI.opci_enlace
                    from tb_seg_permisos PERM 
                    join tb_seg_perfiles PERF on (PERF.perf_codigo = PERM.fk_seg_perfiles) 
                    join tb_seg_opciones OPCI on (OPCI.opci_codigo = PERM.fk_seg_opciones)
                    join tb_seg_modulos MODU on (MODU.modu_codigo = OPCI.fk_seg_modulos)
                    where (PERM.fk_seg_perfiles = ".$intPerfil.") 
                    and (PERF.fk_par_estados = 1) 
                    and (OPCI.fk_par_estados = 1)
					order by fk_seg_perfiles, fk_seg_opciones desc;";
                
                //echo $strSentencia; exit();
                
                $arrConsulta = $obClsCnx->arrEjecutarConsulta($strSentencia);
                
                return $arrConsulta;
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
		
		function strConstruirMenu2($intPerfil)
		{
			try 
			{
				$arrPermisos = self::arrConsultarPermisos($intPerfil);

				$strModulo = "";

				$blIniciado = false;

                $strDropdwon = '';

				$strMenu = '<nav>
					<div class="nav-wrapper">
                    <a class="brand-logo" href="#">ISC Plus</a>
                    <ul class="right hide-on-med-and-down">';

				for ($i = 0; $i < count($arrPermisos); $i++) 
				{
					if ($strModulo != $arrPermisos[$i]['modu_descripcion'])
					{
						if ($blIniciado)
							$strMenu .= '</ul></li>';

						$strModulo = $arrPermisos[$i]['modu_descripcion'];
						
						$strMenu .= '<li>'.
							'<a class="dropdown-button" href="#!" data-activates="dropdown_'.$arrPermisos[$i]['fk_seg_modulos'].'">'
							.'<i class="fa fa-'.$arrPermisos[$i]['modu_icono'].'" aria-hidden="true"></i> '.$arrPermisos[$i]['modu_descripcion']
							.'</a>'
                            .'<ul id="dropdown_'.$arrPermisos[$i]['fk_seg_modulos'].'" class="dropdown-content">';
						$blIniciado = true;
					}

					$strMenu .= 
                        '<li><a href="'.$arrPermisos[$i]['opci_enlace'].'">'.$arrPermisos[$i]['opci_nombre'].'</a></li>';
				}

                if(!$_SESSION)
                    session_start();

				$strMenu .= '</ul></li>
                        <li>
                            <a class="dropdown-button" href="#!" data-activates="dropdown_user"><i class="fa fa-user" aria-hidden="true"></i> '.$_SESSION['usua_nom'].'</a>
                            <ul id="dropdown_user" class="dropdown-content">
                                <li><a id="salir" href="/base/vista/interfaces/Seguridad/login.html?des=true">Salir</a></li>
                            </ul>
                        </li>
                    </ul></div></nav>';

                return $strMenu;
			} 
			catch (Exception $e) 
			{
				throw new Exception($e->getMessage());
			}
		}

        function strConstruirMenu($intPerfil)
        {
            try 
            {
                $arrPermisos = self::arrConsultarPermisos($intPerfil);

                //print_r($arrPermisos); exit();

                $strModulo = "";

                $blIniciado = false;

                $strMenu = '<nav class="navbar navbar-default">
                    <div class="container-fluid">
                    <ul class="nav navbar-nav  navbar-right">';

                for ($i = 0; $i < count($arrPermisos); $i++) 
                {
                    if ($strModulo != $arrPermisos[$i]['modu_descripcion'])
                    {
                        if ($blIniciado)
                            $strMenu .= '</ul></li>';

                        $strModulo = $arrPermisos[$i]['modu_descripcion'];
                        
                        $strMenu .= '<li class="dropdown">'.
                            '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'
                            .'<i class="fa fa-'.$arrPermisos[$i]['modu_icono'].'" aria-hidden="true"></i> '.$arrPermisos[$i]['modu_descripcion'].
                            ' <span class="caret"></span></a><ul class="dropdown-menu">';
                        $blIniciado = true;
                    }

                    $strMenu .= 
                        '<li><a href="'.$arrPermisos[$i]['opci_enlace'].'">'.$arrPermisos[$i]['opci_nombre'].'</a></li>';
                }

                if(!$_SESSION)
                    session_start();

                $strMenu .= '</ul></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'
                            .'<i class="fa fa-user" aria-hidden="true"></i> '.$_SESSION['usua_nom'].' <span class="caret"></span></a>'
                            .'<ul class="dropdown-menu">'
                            .'<li><a id="salir" href="/base/vista/interfaces/Seguridad/login.html?des=true">Salir</a></li>'
                            .'</ul>
                        </li></li></ul>
                    </ul></div></nav>';

                return $strMenu;

                $strMenu .= '</ul></li><li><a id="salir" role="button"><i class="fa fa-sign-out"></i> Salir</a></li> </ul></div></nav>';

                return $strMenu;
            } 
            catch (Exception $e) 
            {
                throw new Exception($e->getMessage());
            }
        }

        public static function blValidarAccion($intPerfil, $intOpcion, $strAccion)
        {
            try 
            {
                $obClsCnx = new BaseConexion();

                $strSentencia = "select perm_lectura, perm_escritura 
                    from tb_seg_permisos 
                    where fk_seg_perfiles = ".$intPerfil." and fk_seg_opciones = ".$intOpcion.";";

                $arrConsulta = $obClsCnx->arrEjecutarConsulta($strSentencia);

                if (count($arrConsulta) != 1)
                    throw new Exception('No se pudo recuperar el permiso');

                $blPermiso = false;

                if ($strAccion == 'L')
                    $blPermiso = $arrConsulta[0]['perm_lectura'] == 1;
                else if ($strAccion == 'E')
                    $blPermiso = $arrConsulta[0]['perm_escritura'] == 1;

                return $blPermiso;
            } 
            catch (Exception $e) 
            {
                throw new Exception($e->getMessage());
            }
        }
    }
    
?>
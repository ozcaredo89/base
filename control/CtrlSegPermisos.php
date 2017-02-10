<?php

if (!class_exists('SeguridadPermisos'))
	include_once '../logica/SeguridadPermisos.php';

class CtrlSegPermisos
{
	public function jsonEjecutar($strOpcion, $jsonParametros)
	{
		$jsonRta = new StdClass();

		try 
		{
			$obClsPerm = new SeguridadPermisos();

			switch ($strOpcion) 
			{
				case 'menu':
					session_start();
					$strMenu = $obClsPerm->strConstruirMenu($_SESSION['usua_per']);

					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = $strMenu;
					$jsonRta->destino = 'div';
					$jsonRta->id = 'menu';

					break;
				default:
					throw new Exception('No se reocnoce la opcion: '.$strOpcion);
			}
		} 
		catch (Exception $e) 
		{
			$jsonRta->tipo = 'error';
			$jsonRta->mensaje = $e->getMessage();
			$jsonRta->destino = 'alert';
		}

		return $jsonRta;
	}
}

?>
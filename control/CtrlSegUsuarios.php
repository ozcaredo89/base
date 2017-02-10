<?php

if (!class_exists('SeguridadUsuarios'))
	include_once '../logica/SeguridadUsuarios.php';

class CtrlSegUsuarios
{
	function jsonEjecutar($strOpcion, $jsonParametros)
	{
		// Objeto JSON con la respuesta
		$jsonRta = new StdClass();
		
		try
		{
			// Instanciar la clase de estados
			$obClsUsua = new SeguridadUsuarios();
			
			// Identificar la opción que se va a ejecutar
			switch($strOpcion)
			{
				case 'validar': // Opción listar
					// Recorrer los parámetros enviados por POST y ponerlos en un arreglo asociativo
					$arrDatos = array();
					for ($i = 0; $i < count($jsonParametros); $i++)
						$arrDatos[$jsonParametros[$i]['name']] = $jsonParametros[$i]['value'];

					if (!isset($arrDatos['usua_login']))
						throw new Exception('No se recibio el nombre de usuario');

					if ($arrDatos['usua_login'] == '')
						throw new Exception('Digite el nombre de usuario');

					if (!isset($arrDatos['usua_password']))
						throw new Exception('No se recibio la contraseña');

					if ($arrDatos['usua_password'] == '')
						throw new Exception('Digite la contraseña');

					$obClsUsua->validarUsuario($arrDatos['usua_login'], md5($arrDatos['usua_password']));
					
					// Definir características del objeto de respuesta
					$jsonRta->mensaje = 'Principal.html';
					$jsonRta->destino = 'redirect';
					
					break;
				default: // Opción no identificada
					throw new Exception('No se reconoce la opción '.$_POST['opcion']);
					break;
			}
		}
		catch (Exception $ex)
		{
			throw new Exception($ex->getMessage());
		}
		
		// Devolver respuesta
		return $jsonRta;
	}
}

?>
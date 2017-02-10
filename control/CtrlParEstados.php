<?php

include_once '../logica/ParametrosEstados.php';

/**
 * Control de la entidad estados
 * @author Jorge Alexis Aguilar Ocampo - 2016/08/11
 */
class CtrlParEstados
{
	/**
	 * Método que ejecuta cualquier método de la clase ClsParametrosEstados
	 * @param String $strOpcion Opción que se va a ejecutar
	 * @param JSON Objeto en formato JSON con los parámetros que necesite la opción
	 */
	function jsonEjecutar($strOpcion, $jsonParametros)
	{
		// Objeto JSON con la respuesta
		$jsonRta = new StdClass();
		
		try
		{
			// Instanciar la clase de estados
			$obClsEsta = new ParametrosEstados();
			
			// Identificar la opción que se va a ejecutar
			switch($strOpcion)
			{
				case 'listar': // Opción listar
					// Crear lista desplegable de estados
					$strLista = $obClsEsta->strListarEstados($jsonParametros['esta_cod'], 
						$jsonParametros['esta_des'], 
						$jsonParametros['esta_div'], 
						'-- Estado --');
					
					// Definir características del objeto de respuesta
					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = $strLista;
					$jsonRta->destino = 'div';
					$jsonRta->id = $jsonParametros['esta_div'];
					
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
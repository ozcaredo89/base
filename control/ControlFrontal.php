<?php

	/**
	 * Control frontal de la aplicación
	 * Recibe todas las peticiones enviadas por ajax y las redirecciona al control preciso
	 * @author Jorge Alexis Aguilar Ocampo - 2016/08/11
	 */

	// Objeto tipo JSON que se devuelve como respuesta a las peticiones
	$jsonRta = new StdClass();

	try
	{
		// Validar que se haya recibido el objeto
		if (!isset($_POST['objeto']) || $_POST['objeto'] == '')
			throw new Exception('No se recibió el objeto');
		
		// Validar que se haya recibido la opción que se va a ejecutar
		if (!isset($_POST['opcion']) || $_POST['opcion'] == '')
			throw new Exception('No se recibió la opcion');
		
		// Listado de controles de los objetos que pueden ser usados en la aplicación
		$arrObjetos = array();
		$arrObjetos['permisos'] = 'CtrlSegPermisos';
		$arrObjetos['perfiles'] = 'CtrlSegPerfiles';
		$arrObjetos['usuarios'] = 'CtrlSegUsuarios';

		$arrObjetos['estados'] = 'CtrlParEstados';
		
		// Incluir el objeto necesario
		include_once $arrObjetos[$_POST['objeto']].'.php';

		// Instanciar el control que se va a utilizar
		$obCtrl = new $arrObjetos[$_POST['objeto']]();
		
		// Llamar al método del control
		$objRta = $obCtrl->jsonEjecutar($_POST['opcion'], $_POST['parametros']);
		
		// Establecer respuesta
		$jsonRta = $objRta;
	}
	catch (Exception $ex)
	{
		$jsonRta->destino = 'alert';
		$jsonRta->tipo = 'error';
		$jsonRta->mensaje = $ex->getMessage();
	}
	
	// Imprimir respuesta
	echo json_encode($jsonRta);

?>
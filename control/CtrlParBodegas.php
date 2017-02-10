<?php

// Incluye clase de parametros para bodegas
if (!class_exists('ParametrosBodegas'))
	include_once '../logica/ParametrosBodegas.php';

/**
 * Control de la entidad bodegas
 * @author Oscar Eduardo Hincapie Vargas - 2017/01/27
 */
class CtrlParBodegas
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
				case 'insertar':
					if ($jsonParametros['add_perf_descripcion'] == '')
						throw new Exception('Digite la descripcion');
					
					$obClsPerf->insertarPerfil($jsonParametros['add_perf_descripcion']);
					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = 'El proceso se realizo con exito';
					$jsonRta->destino = 'alert';

					break;
				case 'consultar':
					$arrConsulta = $obClsPerf->arrConsultarPerfiles($jsonParametros['perf_codigo'] == '' ? -1 : $jsonParametros['perf_codigo'], 
						$jsonParametros['perf_descripcion'] == '' ? '-1' : $jsonParametros['perf_descripcion'], 
						$jsonParametros['fk_par_estados']);

					$intNumRegistros = count($arrConsulta);

					$strTabla = '
						<div class="col-sm-12">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title">Resultados de la Consulta (Numero de Registro: '.$intNumRegistros.')</h3>
								</div>
								<div class="panel-body">
									<table class="table table-bordered table-condensed table-hover">
										<tr class="info text-center">
											<td>#</td>
											<td>Código</td>
											<td>Descripción</td>
											<td>Estado</td>
											<td>Editar</td>
											<td>Eliminar</td>
										</tr>';

					for ($i = 0; $i < $intNumRegistros; $i++)
						$strTabla .= '<tr id="row_'.$arrConsulta[$i]['perf_codigo'].'">
							<td>'.($i+1).'</td>
							<td>'.$arrConsulta[$i]['perf_codigo'].'</td>
							<td>'.$arrConsulta[$i]['perf_descripcion'].'</td>
							<td>'.$arrConsulta[$i]['esta_descripcion'].'</td>
							<td class="text-center">
								<button class="btn btn-warning btn-sm" onclick="cargar('.$arrConsulta[$i]['perf_codigo'].');"><i class="fa fa-pencil"></i></button>
							</td>
							<td class="text-center">
								<button class="btn btn-danger btn-sm" onclick="eliminar('.$arrConsulta[$i]['perf_codigo'].');"><i class="fa fa-trash-o"></i></button>
							</td>
							</tr>';

					$strTabla .='</table>
								</div>
							</div>
						</div>';
					
					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = $strTabla;
					$jsonRta->destino = 'div';
					$jsonRta->id = 'resultado';
					break;
				case 'actualizar':
					$strMsj = '';

					if ($jsonParametros['perf_codigo'] == '')
						$strMsj .= 'No se recibio el codigo\n';
					if ($jsonParametros['perf_descripcion'] == '')
						$strMsj .= 'Digite la descripcion\n';
					if ($jsonParametros['fk_par_estados'] == '')
						$strMsj .= 'Elija un estado\n';

					if ($strMsj != '')
						throw new Exception($strMsj);

					$obClsPerf->modificarPerfil($jsonParametros['perf_codigo'], 
						$jsonParametros['perf_descripcion'], 
						$jsonParametros['fk_par_estados']);

					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = 'El proceso se realizo con exito';
					$jsonRta->destino = 'alert';
						
					break;
				case 'eliminar':
					$obClsPerf->eliminarPerfil($jsonParametros['perf_codigo']);

					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = 'El proceso se realizo con exito';
					$jsonRta->destino = 'alert';

					break;
				default:
					throw new Exception('No se reconoce la opcion: '.$strOpcion);
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
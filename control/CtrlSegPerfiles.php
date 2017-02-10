<?php

if (!class_exists('SeguridadPerfiles'))
	include_once '../logica/SeguridadPerfiles.php';

class CtrlsegPerfiles
{
	public function jsonEjecutar($strOpcion, $jsonParametros)
	{
		$jsonRta = new StdClass();

		try 
		{
			$obClsPerf = new ClsSeguridadPerfiles();

			switch ($strOpcion) {
				case 'insertar':
					if ($jsonParametros['add_perf_descripcion'] == '')
						throw new Exception('Digite la descripcion');
					
					$obClsPerf->insertarPerfiles($jsonParametros['add_perf_descripcion']);
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
					$obClsPerf->eliminarPerfiles($jsonParametros['perf_codigo']);

					$jsonRta->tipo = 'exito';
					$jsonRta->mensaje = 'El proceso se realizo con exito';
					$jsonRta->destino = 'alert';

					break;
				default:
					throw new Exception('No se reconoce la opcion: '.$strOpcion);
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
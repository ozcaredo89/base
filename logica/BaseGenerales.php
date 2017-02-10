<?php

    /**
     * Clase con métodos generales que se usan en toda la aplicaión
     */
    class BaseGenerales
    {
        /**
         * Método que crea una lista deplegable html
         * @param String $strId Identificador de la lista desplegable
         * @param Array $arrDatos Arreglo con la información que se va a cargar en la lista desplegable
         * @param String $strValor Campo que determina el valor de las opciones de la lista desplegable
         * @param String $strTexto Campo que determina el texto de las opciones de la lista desplegable
         * @param String $strTextoAdd Texto del elemento adicional (si no se quiere incluir se envía vacío)
         * @return String Cadena que escribe una lista desplegble en código html
         */
        function strCargarLista($strId, $arrDatos, $strValor, $strTexto, $strTextoAdd)
        {
            try 
            {
                //	Iniciar lista desplegable
                $strLista = '<select class="form-control input-sm" id="'.$strId.'" name="'.$strId.'">';

                //	Validar si se debe incluir elemento adicional
                if ($strTexto != "")
                    $strLista .= '<option value="-1">'.$strTextoAdd.'</option>';

                //	Recorrer arreglo de resultados e incluirlos en la lista
                for($i = 0; $i < count($arrDatos); $i++)
                    $strLista .= '<option value="'.$arrDatos[$i][$strValor].'">'.$arrDatos[$i][$strTexto].'</option>';

                //	Cerrar lista desplegable
                $strLista .= '</select>';

                return $strLista;
            } 
            catch (Exception $e) 
            {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * Método que crea una tabla con los resultados de la consulta
         * @param Array $arrDatos Datos que se van a poner en la tabla
         * @return String 
         */
        function strCargarTabla($strModulo, $strClase, $arrDatos, $blEditar, $blEliminar)
        {
            try 
            {
                //	Iniciar tabla
                $strTabla = '<table class="bordered"><thead><tr>';

                //	Validar si el conjunto de resultados está vacío
                if (count($arrDatos) > 0)
                {
                    foreach ($arrDatos[0] as $key => $value) //	Recorrer columnas para construir la cabecera
                        $strTabla .= '<th data-field="'.str_replace(' ', '', $key).'">'.$key.'</th>';
                }

                //	Validar si tiene opción de edición
                if ($blEditar)
                    $strTabla .= '<th>Editar</th>';

                //	Validar si tiene opción de eliminación
                if ($blEliminar)
                    $strTabla .= '<th>Eliminar</th>';

                //	Cerrar cabecera
                $strTabla .= '</thead>';

                //	Iniciar filas de resultados
                $strTabla .= '<tbody>';

                //	Recorrer resultados para incluirlos en la tabla
                for ($i = 0; $i < count($arrDatos); $i++)
                {
                    $strTabla .= '<tr>';

                    foreach ($arrDatos[$i] as $key => $value)
                        $strTabla .= '<td>'.$value.'</td>';

                    //	Validar si tiene opción de edición
                    if ($blEditar)
                        $strTabla .= '<th><a><i class="mdi-editor-mode-edit"></i></a></th>';

                    //	Validar si tiene opción de eliminación
                    if ($blEliminar)
                        $strTabla .= '<th><a onclick="del(\''.$strModulo.'/'.$strClase.'/'.$arrDatos[$i]['Codigo'].'\');"><i class="mdi-content-clear"></i></a></th>';

                    $strTabla .= '</tr>';
                }

                $strTabla .= '</tbody></table>';

                //echo $strTabla; exit();

                return $strTabla;
            } 
            catch (Exception $e) 
            {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * Método que devuelve la fecha actual del sistema
         * @return Fecha en formato AAAAMMDD
         */
        function intFechaActual()
        {
            try
            {
                //	Fijar zona horaria
                date_default_timezone_set("America/Bogota");

                //	Devolver la fecha en formato AAAAYYMM
                return date('Ymd');
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }

        /**
         * Método que devuelve la hora actual del sistema
         * @return Hora en formato HHmmss
         */
        function intHoraActual()
        {
            try 
            {
                date_default_timezone_set("America/Bogota");
                return date('His');
            } 
            catch (Exception $e) 
            {
                throw new Exception($e->getMessage());
            }
        }
        
        function strAutoCompletar()
        {
            try
            {
                return '';
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
        
        function subirArchivo($strRuta, $strName)
        {
            try
            {
                $target_path = $strRuta.basename($strArchivo);
                
                $data = fopen($target_path, "r");
                $size = filesize($target_path);
                $contenido = fread($data, $size);

                $contenido2 = file_get_contents($target_path);
                $codificada = base64_encode($contenido2);

                //	Verificar si el archivo ha sido subido|
                if(move_uploaded_file($_FILES['arc_ruta']['tmp_name'], $target_path)) 
                {		
                        $obClsArch = new Archivos();

                        //	Insertar registro de archivo en la base de datos
                        $obClsArch->insertarArchivo(basename($_FILES['arc_ruta']['name']), $target_path);	

                        print_r("<p style='color:black; font-family:Calibri; font-size:14pt;'>El archivo ". basename( $_FILES['arc_ruta']['name']). " ha sido subido</p>");
                } 
                else
                        echo "<p style='color:red; font-family:Calibri; font-size:14pt;'>Se produjo un error al subir el archivo ". basename( $_FILES['arc_ruta']['name'])."</p>";
            } 
            catch (Exception $ex)
            {
                throw new Exception($ex->getMessage());
            }
        }
    }
?>
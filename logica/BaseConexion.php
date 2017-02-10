<?php

    /**
    *	Clase con métodos relacionados con las operaciones en base de datos
    */
    class BaseConexion
    {
        /**
        *	Método que establece conexión con la base de datos
        *	@return $cnxConexion Conexión con la base de datos
        */
        function cnxConectar()
        {
            try
            {
                //	Fijar variables que se utilizan en la conexión
                $strServidor = 'localhost';
                $strUsuario = 'jorgeaguilar';
                $strClave = 'admin';
                $strBaseDatos = 'bd_base';
                    
                //	Establecer conexión con la base de datos
                $cnxConexion = mysqli_connect($strServidor, $strUsuario, $strClave, $strBaseDatos);
				
				//	Validar errores en la conexión
                if (!$cnxConexion)
                    throw new Exception('No se pudo establecer conexion. '.mysqli_errno($cnxConexion));

                //	Fijar charset
                mysqli_set_charset($cnxConexion, 'utf8');

                //	Retornar conexión
                return $cnxConexion;
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }

        /**
        *	Método que finaliza la conexión con la base de datos
        *	@param $cnxConexion Objeto de conexión
        */
        function desconectar($cnxConexion)
        {
            try
            {
                //	Finalizar conexión indicada
                mysqli_close($cnxConexion);
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }

        /**
        *	Ejecutar sentencia de consulta
        *	@param $strSentencia Sentencia SQL de consulta que se va a ejecutar
        *	@return Arreglo con el conjunto de resultados
        */
        function arrEjecutarConsulta($strSentencia)
        {
            try
            {
                //	Establecer conexión con la base de datos
                $cnxConexion = self::cnxConectar();

                //	Ejecutar consulta
                $rslConsulta = mysqli_query($cnxConexion, $strSentencia);

                //	Validar consulta
                if (!$rslConsulta)
                    throw new Exception("Se produjo un error durante la consulta: ".mysqli_error($cnxConexion));

                //	Finalizar la conextión con la base de datos
                self::desconectar($cnxConexion);

                //	Arreglo de filas para almacenar el resultado
                $arrFilas = null;
				
				//	Recorrer conjunto de resultados
                //while ($drFila = mysqli_fetch_array($rslConsulta, 'MYSQL_ASSOC'))
                while ($drFila = $rslConsulta->fetch_array(MYSQLI_ASSOC))
                    $arrFilas[] = $drFila;	//	Agregar fila al arreglo de resultados

                //	Liberar conjunto de resultados
                mysqli_free_result($rslConsulta);

                //	Retornar resultado de la consulta
                return $arrFilas;
            }
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
        }

        /**
        *	Método que ejecuta sentencias de inserción, modificación y eliminación en la base de datos
        *	@param $strSentencia Sentencia SQL que se va a ejecutar
        */
        function ejecutarSentencia($strSentencia)
        {
            try
            {
                //	Establecer conexión con la base de datos
                $cnxConexion = self::cnxConectar();
                
                //  Ejecutar sentencia
                $cnxConexion->query($strSentencia);
                
                $intId = mysqli_insert_id($cnxConexion);

                //	Finalizar la conextión con la base de datos
                self::desconectar($cnxConexion);
                
                return $intId;
            }
            catch (Exception $e)
            {
                    throw new Exception($e->getMessage());
            }
        }
    }

?>
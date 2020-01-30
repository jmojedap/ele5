<?php
class Pcrn extends CI_Model{
    
    /* Pcrn, es una abreviatura de Pacarina
     * Colección de funciones creadas por Pacarina Media Lab para utilizarse complementariamente
     * con CodeIgniter. Versión 2012-11-20
     */
    
//---------------------------------------------------------------------------------------------------------
//FUNCIONES DE BASE DE DATOS
      
    /**
    * 
    * Devuelve el valor de un campo ($nombre_campo) del primer registro de una $tabla
    * que cumpla una $condicion con el formato where de sql.
    */
    function campo($tabla, $condicion, $nombre_campo)
    {
        $sql = "SELECT {$nombre_campo} FROM {$tabla} WHERE {$condicion} LIMIT 1";
        
        $query = $this->db->query($sql);
        if ( $query->num_rows() > 0 )
        {
            $row = $query->row();
            $campo = $row->$nombre_campo;
        } else {
            $campo = NULL;
        }
        
        return $campo;
    }
    
    /**
    * 
    * Devuelve el valor de un campo ($nombre_campo) del primer registro de una $tabla
    * que cumpla 
    */
    function campo_id($tabla, $id, $nombre_campo)
    {
        $campo = NULL;
        
        if ( ! is_null($id) ) {
            $sql = "SELECT {$nombre_campo} FROM {$tabla} WHERE id = {$id}";
        
            $query = $this->db->query($sql);
            if ( $query->num_rows() > 0 )
            {
                $row = $query->row();
                $campo = $row->$nombre_campo;
            }
        }
        
        return $campo;
    }
    
    /* Devuelve el primer registro de una $tabla
    * que cumpla una $condicion con el formato where de sql.
    */
    function registro($tabla, $condicion)
    {
        
        //Valor por defecto
        $row = NULL;

        $this->db->where($condicion);
        $query = $this->db->get($tabla, 1);

        if ( $query->num_rows() ) { $row = $query->row(); }
        
        return $row;
        
    }
    
    /* Devuelve el primer registro de una $tabla
    *  teniendo un valor de id determinado
    */
    function registro_id($tabla, $id)
    {
        $row = NULL;
        if ( ! is_null($id) )
        {
            $this->db->where('id', $id);
            $query = $this->db->get($tabla, 1);
            
            if ( $query->num_rows() ) { $row = $query->row(); }
        }
        
        return $row;
        
    }
    
    /* Devuelve el primer registro en formato array de una $tabla
    * que cumpla una $condicion con el formato where de sql.
    */
    function registro_array($tabla, $condicion)
    {
        
        //Valor por defecto
        $row = NULL;

        $sql = "SELECT * FROM {$tabla} WHERE {$condicion}";
        $query = $this->db->query($sql);
        if ( $query->num_rows() > 0 ){
            $row = $query->row_array();
        }
        
        return $row;
        
    }
    
    /* Devuelve el primer registro en formato de array de una $tabla
    *  teniendo un valor de id determinado
    */
    function registro_id_array($tabla, $id)
    {
        $row = NULL;
        if ( ! is_null($id) ){
            $condicion = "id = {$id}";
            $row = $this->registro_array($tabla, $condicion);
        }
        
        return $row;
        
    }
    
    /* Devuelve el número de registros de una tabla 
    * con una condición con el formato where de sql
    */
    function num_registros($tabla, $condicion)
    {
        $num_registros = 0;
        
        $sql = "SELECT COUNT(*) AS cant_registros FROM {$tabla} WHERE {$condicion}";
        $query = $this->db->query($sql);
        if ( $query->num_rows() > 0 ) 
        {
            $num_registros = $query->row()->cant_registros;
        }
        
        return $num_registros;
    }
    
    /**
     * Determina si existe un registro con una $condicion sql en una $tabla
     * Si no existe devuelve 0, si existe devuelve el id del registro
     * 
     * @param type $tabla
     * @param type $condicion string
     * @return type
     */
    function existe($tabla, $condicion)
    {
        $sql = "SELECT id FROM {$tabla} WHERE {$condicion} LIMIT 1";
        $existe = 0;
        
        $query = $this->db->query($sql);
        if ( $query->num_rows() > 0 ){
            $row = $query->row();
            $existe = $row->id;
        }
        
        return $existe;
    }
    
    /**
     * Si un registro con una $condicion sql existe en una $tabla, se edita
     * Si no existe se inserta nuevo. Devuelve el id del registro editado o insertado
     * 
     * @param type $tabla
     * @param type $condicion
     * @param type $registro
     * @return type
     */
    function guardar($tabla, $condicion, $registro)
    {
        $row_id = $this->existe($tabla, $condicion);
        
        if ( $row_id == 0 ) {
            //No existe, insertar
            $this->db->insert($tabla, $registro);
            $row_id = $this->db->insert_id();
        } else {
            //Ya existe, editar
            $this->db->where('id', $row_id);
            $this->db->update($tabla, $registro);
        }
        
        return $row_id;
                
    }
    
    /**
     * Si un registro con una $condicion sql no existe en una $tabla, se inserta
     * Devuelve el id del registro editado o insertado
     * 
     * @param type $tabla
     * @param type $condicion
     * @param type $registro
     * @return type
     */
    function insertar_si($tabla, $condicion, $registro)
    {
        $row_id = $this->existe($tabla, $condicion);
        
        if ( $row_id == 0 ) {
            //No existe, insertar
            $this->db->insert($tabla, $registro);
            $row_id = $this->db->insert_id();
        }
        
        return $row_id;
                
    }
    
    /**
     * Alterna una variable entre dos valores, intercambiando el valor actual
     * por el otro.
     * 
     * @param type $valor_actual
     * @param type $valor_1
     * @param type $valor_2
     * @return type
     */
    function alternar($valor_actual, $valor_1 = 1, $valor_2 = 0)
    {
        $valor_alt = $valor_2;
        if ( $valor_actual == $valor_2 ) { $valor_alt = $valor_1; }
        
        return $valor_alt;
    }
    
    function alternar_boleano($tabla, $id, $campo)
    {
        $row = $this->registro_id($tabla, $id);
        
        $nuevo_valor = 0;
        if ( $row->$campo == 0 ) { $nuevo_valor = 1; }
        
        $registro[$campo] = $nuevo_valor;
        
        $this->db->where('id', $id);
        $this->db->update($tabla, $registro);
        
        return $nuevo_valor;
    }
    
//---------------------------------------------------------------------------------------------------------
//FUNCIONES DE FECHA Y TIEMPO
    
    /**
     * Se recibe en el formato YYYY-MM-DD hh:mm:ss y se devuelve en formato de marca de tiempo
     * La entrada $fecha_texto debe estar formato de fecha/hora de MySql.
     * 
     * @param type $fecha_texto
     * @return type 
     */
    function texto_a_mktime($fecha_texto)
    {
        
        //Extrayendo partes de la fecha
            $anyo = substr($fecha_texto,0,4);
            $mes = substr($fecha_texto,5,2);
            $dia = substr($fecha_texto,8,2);
            $hora = substr($fecha_texto, 11,2);
            $min = substr($fecha_texto, 14,2);
            $seg = substr($fecha_texto, 17,2);
        
        $texto_a_mktime = mktime($hora,$min,$seg,$mes,$dia,$anyo);
        return $texto_a_mktime;
    }
   
    /**
     * Entrada en el formato YYYY-MM-DD hh:mm:ss
     * Devuelve una cadena con el formato especificado de fecha
     * 
     * @param type $fecha
     * @param type $formato
     * @return string
     */
    function fecha_formato($fecha, $formato = 'Y-M-d H:i')
    {
        
        if ( is_null($fecha) == FALSE ) {
            $fecha_formato = date($formato, $this->texto_a_mktime($fecha));
        } else {
            $fecha_formato = "";
        }
        return $fecha_formato;
    }
    
    /**
     * Devuelve un string con un formato de hh:mm:ss, teniendo como entrada un número de segundos
     * 
     * @param type $segundos
     * @return string 
     */
    function tiempo_formato($segundos)
    {
        $parte_horas = floor( $segundos / 3600 );
        $residuo_horas = $segundos % 3600;
        $parte_minutos = floor( $residuo_horas / 60 );
        $parte_segundos = $residuo_horas % 60;
        
        //Formato con dos dígitos
        $parte_horas = $this->ceros_izq($parte_horas, 2);
        $parte_minutos = $this->ceros_izq($parte_minutos, 2);
        $parte_segundos = $this->ceros_izq($parte_segundos, 2);
        
        
        $tiempo_formato = $parte_horas . ":" . $parte_minutos . ":" . $parte_segundos;
        
        return $tiempo_formato ;
    }
    
    /**
     * $fecha_inicial, en formato YYYY-MM-DD hh:mm:ss
     * Devuelve el valor en años desde una fecha determinada
     * 
     * @param type $fecha_inicial
     * @return type 
     */
    function edad_actual($fecha_inicial)
    {
        
        if( $fecha_inicial == NULL ){
            $edad_actual = NULL;
        } else {
            $mkt_fecha_inicial = $this->texto_a_mktime($fecha_inicial);
            //31557600, segundos que tiene un año
            $edad_actual = round((time()-$mkt_fecha_inicial)/31557600,1);
        }
        return $edad_actual;
    }
    
    //Devuelve un array con el número de segundos que tienen diferentes lapsos
    function segundos_periodo_array()
    {
        $segundos = array(
            'anio' => 31557600,
            'mes' => 2629800,
            'semana' => 604800,
            'dia' => 86400,
            'hora' => 3600,
            'minuto' => 60,
        );
        
        return $segundos;
        
    }
    
    //Devuelve un array con el número de segundos que tienen diferentes lapsos
    function segundos_periodo($periodo = 'dia')
    {
        $segundos = array(
            'anio' => 31557600,
            'mes' => 2629800,
            'semana' => 604800,
            'dia' => 86400,
            'hora' => 3600,
            'minuto' => 60,
        );
        
        return $segundos[$periodo];
        
    }
    
    /**
     * Devuelve un string con el valor que ha pasado desde hace una fecha determinada
     * la variable $fecha debe tener el formato YYYY-MM-DD hh:mm:ss, utilizado en MySQL 
     * para los campos de fecha
     * 
     * @param type $fecha
     * @return type 
     */
    function tiempo_hace($fecha, $poner_prefijo = FALSE)
    { 
        $segundos_periodo = $this->segundos_periodo_array();
        
        $prefijo = 'Hace ';
        if ( $fecha > date('Y-m-d H:i:s') ) { $prefijo = 'Dentro de '; }

        $tiempo_hace = 'ND';
        if ( ! is_null($fecha) ) {
            
            //Marcas de tiempo, se calcula diferencia ($segundos)
            $mkt1 = $this->texto_a_mktime($fecha);
            $mkt2 = time();
            $segundos = abs($mkt2 - $mkt1);

            if ($segundos < $segundos_periodo['minuto']){
                $valor_tiempo = 1;
                $sufijo_periodo = 'm';
            } elseif ($segundos < $segundos_periodo['hora']){
                $valor_tiempo = $segundos / $segundos_periodo['minuto'];
                $sufijo_periodo = 'm';
            } elseif ($segundos < $segundos_periodo['dia']){
                $valor_tiempo = $segundos / $segundos_periodo['hora'];
                $sufijo_periodo = ' h';
            } elseif ($segundos < $segundos_periodo['semana']){
                $valor_tiempo = $segundos / $segundos_periodo['dia'];
                $sufijo_periodo = ' d';
            } elseif ($segundos < $segundos_periodo['mes']){
                $valor_tiempo = $segundos / $segundos_periodo['semana'];
                $sufijo_periodo =' sem';
            } elseif ($segundos < $segundos_periodo['anio']){
                $valor_tiempo = $segundos / $segundos_periodo['mes'];
                $sufijo_periodo = ' meses';
            } else {
                $valor_tiempo = $segundos / $segundos_periodo['anio'];
                $sufijo_periodo = ' años';
            }
            
            //Se agrega la unidad de medida
            $tiempo_hace = $this->validar_medida($valor_tiempo, 1, $sufijo_periodo);
            
            if ( $poner_prefijo ) { $tiempo_hace = $prefijo . $tiempo_hace; }
        }

        return $tiempo_hace;
    }
    
    /**
     * String con valor de lapso correspondiente a una cantidad de segundos
     * 2017-07-15
     * 
     * @param type $segundos
     * @return type
     */
    function lapso($segundos)
    {
        $segundos_periodo = $this->segundos_periodo_array();

        $lapso = '-';
        $decimales = 1;
        if ( $segundos > 0 )
        {
            if ( $segundos < 60 )
            {
                $valor_tiempo = $segundos;
                $sufijo_periodo = ' s';
                $decimales = 0;
            } elseif ($segundos < $segundos_periodo['hora']){
                $valor_tiempo = $segundos / $segundos_periodo['minuto'];
                $sufijo_periodo = ' m';
                $decimales = 0;
            } elseif ($segundos < $segundos_periodo['dia']){
                $valor_tiempo = $segundos / $segundos_periodo['hora'];
                $sufijo_periodo = ' h';
            } elseif ($segundos < $segundos_periodo['semana']){
                $valor_tiempo = $segundos / $segundos_periodo['dia'];
                $sufijo_periodo = ' d';
            } elseif ($segundos < $segundos_periodo['mes']){
                $valor_tiempo = $segundos / $segundos_periodo['semana'];
                $sufijo_periodo =' sem';
            } elseif ($segundos < $segundos_periodo['anio']){
                $valor_tiempo = $segundos / $segundos_periodo['mes'];
                $sufijo_periodo = ' mes';
            } else {
                $valor_tiempo = $segundos / $segundos_periodo['anio'];
                $sufijo_periodo = ' años';
            }
            
            //Se agrega la unidad de medida
            $lapso = $this->validar_medida($valor_tiempo, $decimales, $sufijo_periodo);
        }

        return $lapso;
    }
    
    function segundos_lapso($fecha_inicial, $fecha_final)
    {
        $mkt1 = $this->texto_a_mktime($fecha_inicial);
        $mkt2 = $this->texto_a_mktime($fecha_final);
        
        $segundos_lapso = abs($mkt2 - $mkt1);
        
        return $segundos_lapso;
    }
    
    /**
     * Devuelve una fecga al sumar una cantidad de tiempo a una fecha determinada
     * 
     * @param type $fecha_inicial
     * @return type
     */
    function suma_fecha($fecha_inicial, $suma = '+1 day')
    {
        
        $mkt = strtotime ( $suma , strtotime ( $fecha_inicial ) ) ;
        $nueva_fecha = date ( 'Y-m-d H:i:s' , $mkt );

        return $nueva_fecha;
    }
    
    /**
     * Convierte un string de fecha del formato 'Y-m-d H:i:s' al formato 'YmdHis'
     * Utilizada para filtros en url
     * 
     * @param type $fecha
     */
    function fecha_numeros($fecha)
    {
        $fecha_numeros = $fecha;
        $fecha_numeros = str_replace(' ', '', $fecha_numeros);
        $fecha_numeros = str_replace('-', '', $fecha_numeros);
        $fecha_numeros = str_replace(':', '', $fecha_numeros);
        
        return $fecha_numeros;
    }
    
    /**
     * Cantidad de días que pasan entre dos fechas
     * 2015-10-08
     * 
     * @param type $fecha_inicial
     * @param type $fecha_final
     * @return type
     */
    function dias_lapso($fecha_inicial, $fecha_final)
    {
        $dias = NULL;
        
        if ( strlen($fecha_inicial) > 0 ) {
            $segundos = $this->segundos_lapso($fecha_inicial, $fecha_final);
            $dias = round($segundos / 86400, 2);
        }
        
        return $dias;
    }
    
    /**
     * Convierte una fecha de excel en mktime de Unix
     * @param type $fecha_excel
     * @return type
     */
    function fexcel_unix($fecha_excel)
    {
        $horas_diferencia = 19; //Diferencia GMT
        return (( $fecha_excel - 25568 ) * 86400) - ($horas_diferencia * 60 * 60);
    }
//---------------------------------------------------------------------------------------------------------
//FUNCIONES CONTROL
    
    /* Comprueba si el valor de una variable ($variable) es nulo
    * Si es nulo devuelve $valor_si_null
    * Si no es nulo devuelve $valor_no_null
    * Función utilizada para evitar errores provocados al utilizar una función con valor nulo
    */
    function si_nulo($variable, $valor_si_null, $valor_no_null = NULL)
    {
        //Si el valor no null no se establece, se toma el mismo valor de la variable
        if ( is_null($valor_no_null) ) { $valor_no_null = $variable; }
        
        if( is_null($variable) ){
            $valor_variable = $valor_si_null;
        } else {
            $valor_variable = $valor_no_null;
        }
        
        return $valor_variable;
    }
    
    /* Comprueba si el valor de una variable ($variable) está vacío
     * Si es vacío devuelve $valor_si_vacia
     * Si no es vacío devuelve $valor_no_vacia
     * Función utilizada para evitar errores provocados al utilizar una función con valor vacío
     * principalmente para comprobar si un campo de una tabla en la base de datos tiene un valor
     */
    function si_vacia($variable, $valor_si_vacia, $valor_no_vacia = NULL)
    {
        
        
        //Si el valor no null no se establece, se toma el mismo valor de la variable
        if ( is_null($valor_no_vacia) ) { $valor_no_vacia = $variable; }
        
        $valor_variable = $valor_no_vacia;
        if( empty($variable) ){
            $valor_variable = $valor_si_vacia;
        }
        return $valor_variable;
    }
    
    /**
     * Devuelve uno u otro valor dependiendo de si una variable es igual a cero o no
     * Si es cero devuelve $valor_si_cero
     * Si no es vacío devuelve $valor_no_cero
     * Función utilizada para evitar errores provocados al utilizar una función con valor vacío
     * principalmente para comprobar si un campo de una tabla en la base de datos tiene un valor
     * 
     * @param type $variable
     * @param type $valor_si_cero
     * @param type $valor_no_cero
     * @return type 
     */
    function si_cero($variable, $valor_si_cero, $valor_no_cero = NULL)
    {   
        if ( is_null($valor_no_cero) ) { $valor_no_cero = $variable; }
        if ( $variable == 0 ) {
            $si_cero = $valor_si_cero;
        } else {
            $si_cero = $valor_no_cero;
        }
        
        return $si_cero;
    }
    
    /**
     * Si la longitud de una cadena es cero, devuelve un valor_si
     * Si la longitud no es cero, devuelve un $valor_no
     * Si el $valor_no es null, devuelve el valor de la variable
     * 
     * @param type $variable
     * @param type $valor_si
     * @param type $valor_no
     * @return type
     */
    function si_strlen($variable, $valor_si, $valor_no = NULL)
    {
        if ( is_null($valor_no) ) { $valor_no = $variable; }
        
        if ( strlen($variable) == 0 ) {
            $si_strlen = $valor_si;
        } else {
            $si_strlen = $valor_no;
        }
        return $si_strlen;
        
    }
    
    /**
     * Evita que la variable tome el valor de cero, Útil para controlar divisiones
     * Si la variable es cero devuelve 1.
     * 
     * @param type $valor
     * @return type 
     */
    function no_cero($valor)
    {
        //$no_cero = $this->si_cero($valor, 1, $valor);
        $no_cero = $valor;
        if ( $valor == 0 ) { $no_cero = 1; }
        return $no_cero;
    }
    
    function control_plural ($valor, $singular, $plural)
    {
        
        /* Devuelve un el valor en singular o plural dependiendo de un número ($valor)
         * El valor $singular es devuelto cuando $valor es exactamente igual a 1
         */
        
        if ( $valor == 1 ){
            $control_plural = $singular;
        } else {
            $control_plural = $plural;
        }
        return $control_plural;
    }
    
    function validar_medida($valor_medida, $decimales, $unidad_medida)
    {
        
        //Devuelve un valor con una unidad de medida y un número específico de decimales
        
        $medida_validada = "ND";
        if ( is_numeric($valor_medida) )
        {
            $medida_validada = number_format($valor_medida,$decimales, "." ,",") . $unidad_medida;
        }
        return $medida_validada;
    }
    
    /**
     * Ajusta el valor de una variable en un rango determinado
     */
    function limitar_entre($valor, $min, $max)
    {
        $valor_limitado = $valor;
        if ($valor > $max){$valor_limitado = $max;}
        if ($valor < $min){$valor_limitado = $min;}
        return $valor_limitado;
    }
    
    /**
     * Ajusta el valor de una variable en un rango determinado. Si es mayor al 
     * máximo devuelve el mínimo, sie es menor al mínimo devuelve al máximo.
     */
    function rotar_entre($valor, $min, $max)
    {
        $valor_limitado = $valor;
        if ($valor > $max){$valor_limitado = $min;}
        if ($valor < $min){$valor_limitado = $max;}
        return $valor_limitado;
    }
    
    /**
     * Devuelve un valor entero de porcentaje (ya multiplicado por 100)
     * 
     * @param type $dividendo
     * @param type $divisor
     * @return int
     */
    function int_percent($dividendo, $divisor = 1)
    {
        $int_percent = 0;
        
        if ( $divisor != 0 ) {
            $int_percent = number_format(100 * $dividendo / $divisor, 0);
        }
        
        return $int_percent;
    }
    
    /**
     * Hace una división segura, evitando que el divisor = 0, caso en el que devuelve
     * un valor alternativo ($alt)
     * 
     * @param type $dividendo
     * @param type $divisor
     * @param type $alt
     * @return type
     */
    function dividir($dividendo, $divisor, $alt = 'NA')
    {
        $cociente = $alt;
        if ( $divisor != 0 ) { $cociente = $dividendo / $divisor; }
        
        return $cociente;
    }
//---------------------------------------------------------------------------------------------------------
//FUNCIONES DE TEXTO
    
    function sin_acentos($s)
    {

        //Caracteres de ISO
            $s = str_replace('Ã¡','a',$s);
            $s = str_replace('Ã©','e',$s);
            $s = str_replace('Ã','i',$s);
            $s = str_replace('Ã³','o',$s);
            $s = str_replace('Ãº','u',$s);
            $s = str_replace('Ã±','n',$s);
            $s = str_replace('Ã','A',$s);
            $s = str_replace('Ã‰','E',$s);
            $s = str_replace('Ã','I',$s);
            $s = str_replace('Ã“','O',$s);
            $s = str_replace('Ãš','U',$s);
            $s = str_replace('Ã‘','N',$s);
            $s = str_replace("ñ","n",$s);
            $s = str_replace("Ñ","N",$s);
            
        //Quitar tildes
            $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
            $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
            $s_nuevo = str_replace($no_permitidas, $permitidas ,$s);
        
	return $s_nuevo;
    }
    
    /**
     * Devuelve un texto sin signos de puntuacion
     * @param type $texto
     * @return type
     */
    function sin_puntuacion($texto)
    {
        $no_permitidas = array (',', '.', '-', '!', '¡', '?', '¿', ':', '(', ')', '"', '+', '$', '/', ';');
        $permitidas = array ('');
        $sin_puntuacion = str_replace($no_permitidas, $permitidas ,$texto);
        
        return $sin_puntuacion;
    }
    
    /**
     * Recorta un núero de caracteres al final del texto
     *
     * @param type $texto
     * @param type $caracteres
     * @return type 
     */
    function cortar_der($texto, $caracteres)
    {
        $nuevo_texto = substr($texto, 0, strlen($texto)-$caracteres);
        return $nuevo_texto;
    }

    /**
     * Recorta un número de caracteres al principio del texto
     *
     * @param type $texto
     * @param type $caracteres
     * @return type 
     */
    function cortar_izq($texto, $caracteres)
    {
        $nuevo_texto = substr($texto, 0 + $caracteres, strlen($texto));
        return $nuevo_texto;
    }
    
    /**
     * Número con un determinado número de ceros a la izquierda
     */
    function ceros_izq($numero, $ceros)
    {
        $var_texto = str_repeat("0", $ceros) . $numero;
        $largo = strlen($var_texto);
        $ceros_izq = $this->cortar_izq($var_texto, $largo - $ceros);
        return $ceros_izq;
    }
    
    /**
     * Generar una cadena aleatoria numérica o alfanumérica con una determinada cantidad de caracteres
     * 
     * @param type $length
     * @param type $uc
     * @param type $n
     * @param type $sc
     * @return type
     */
    function alfanumerico_random($length=10,$uc=TRUE,$n=TRUE,$sc=FALSE)
    { 
 
        $source = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if( $uc == 1 ) { $source .= 'abcdefghijklmnopqrstuvwxyz'; }
        if( $n == 1 ) { $source .= '1234567890'; }
        if( $sc == 1 ) { $source .= '|@#~$%()=^*+[]{}-_'; }
        if( $length > 0 ){
            $rstr = "";
            $source = str_split($source,1);
            for($i=1; $i<=$length; $i++){
                //mt_srand((double)microtime() * 1000000);
                $num = mt_rand(1,count($source));
                $rstr .= $source[$num-1];
            }

        }
        return $rstr;
    }
    
    /**
     * Prepara el texto de una url para mostrar en un link
     * Recorta la longitud del texto y le quita segmentos de texto innecesarios
     * 
     * @param type $url
     * @return type
     */
    function texto_url($url)
    {
        $texto_url = str_replace('https://www.', '', $url);
        $texto_url = str_replace('http://www.', '', $texto_url);
        $texto_url = str_replace('http://', '', $texto_url);
        $texto_url = str_replace('https://', '', $texto_url);
        $texto_url = str_replace('http //', '', $texto_url);
        
        if ( strlen($texto_url) > 40 ) {
            $texto_url = substr($texto_url, 0, 40) . '...';
        }
        
        return $texto_url;
    }
    
    /**
     * Agrega el prefijo 'http://' a una url si no la tiene
     * @param type $url
     * @return string
     */
    function preparar_url($url)
    {
        $agregar = TRUE;
        $url_preparada = str_replace('http ', 'http:', $url);
        
        $inicio_1 = substr($url_preparada,0,7);   //Verificar 7 caractéres: http://
        $inicio_2 = substr($url_preparada,0,8);   //Verificar 8 caractéres: https://
        
        //Verificar si se agrega prefijo de url
        if ( $inicio_1 == 'http://' ) { $agregar = FALSE; }
        if ( $inicio_2 == 'https://' ) { $agregar = FALSE; }
        
        //Cambiar espacios
        if ( $inicio_2 == 'http://' ) { $agregar = FALSE; }
        
        if ( $agregar ) { $url_preparada = 'http://' . $url_preparada; }
        
        return $url_preparada;
    }
    
//FUNCIONES VARIAS
//---------------------------------------------------------------------------------------------------------

    function rango_valor($rangos, $valor_comparacion)
    {
        
        //Valor inicial
        $limite_inferior = 0;
        $rango = 0;
        
        foreach ($rangos as $key => $limite_superior) {
            if( $valor_comparacion >= $limite_inferior && $valor_comparacion < $limite_superior ){
                $rango = $key + 1;
            }
            
            $limite_inferior = $limite_superior;    //Para comparación en el siguiente ciclo
        }
        
        return $rango;
    }
    
    function valor_rango($rangos, $valor_comparacion)
    {
        //Valor inicial
        $limite_inferior = 0;
        $valor_rango = '';
        
        foreach ($rangos as $key => $valor )
        {
            $limite_superior = $key;
            if( $valor_comparacion >= $limite_inferior && $valor_comparacion < $limite_superior ){
                $valor_rango = $valor;
                break;
            }
            
            $limite_inferior = $limite_superior;    //Para comparación en el siguiente ciclo
        }
        
        return $valor_rango;
    }
    
    /**
     * 2015-08-12
     * Convierte el resultado de un query de codeigniter en un array unidimensional
     * 
     * $campo_indice: nombre del campo cuyo valor será índice en el array (key)
     * $campo_valor: nombre del campo cuyo valor será el valor del elemento del array ($value)
     */
    function query_to_array($query, $campo_valor, $campo_indice = NULL)
    {
        
        $array = array();
        
        foreach ($query->result() as $row)
        {
            if ( is_null($campo_indice) ) {
                //Sin índice
                $array[] = $row->$campo_valor;
            } else {
                $indice = $row->$campo_indice;
                $array[$indice] = $row->$campo_valor;
            }
            
        }
        
        return $array;
        
    }
    
    /**
     * Convierte el conjunto de valores de un $campo de un $query en un string
     * separado ($separador) por un caracter
     * 
     * @param type $query
     * @param type $campo
     * @param type $separador
     * @return type
     */
    function query_to_str($query, $campo, $separador = '-')
    {
        $str = '';
        
        foreach ($query->result() as $row)
        {
            $str .= $row->$campo . $separador;
        }
        
        //Se quita el separador final con substr
        return substr($str, 0, -strlen($separador));
    }
    
    function opciones_dropdown($query, $campo_indice, $campo, $texto_vacio = '')
    {
        $array = array();
        
        if ( strlen($texto_vacio) > 0 ) {
            $array[''] = "[{$texto_vacio}]";
        }
        
        foreach ( $query->result() as $row_query ){
            $indice = '0' . $row_query->$campo_indice;
            $array[$indice] = $row_query->$campo;
        }
        
        return $array;
    }
    
    /**
     * Devuelve array con los números de un rango
     * Editores 2
     * 2013-11-15
     * 
     * @param type $min
     * @param type $max
     * @return int
     */
    function array_rango($min, $max)
    {
        $array_rango = array();
        
        if ( $min <= $max ){
            $numero = $min;
            do {
                $array_rango[] = $numero;
                $numero += 1;
            } while ($numero <= $max);
        }
        
        return $array_rango;
    }
    
    //Dependiendo de la utilidad se quitan o se ponen espacios en una cadena
    function texto_uri($texto, $quitar_espacios = TRUE)
    {
        if ( $quitar_espacios ) {
            $texto_uri = str_replace(" ","_", $texto);
        } else {
            $texto_uri = str_replace("_"," ", $texto);
        }
        
        return $texto_uri;
        
    }
    
    function slug($texto, $separador = '-')
    {
        $this->load->helper('text');
        $slug = convert_accented_characters($texto);
        $slug = $this->sin_puntuacion($slug);
        $slug = trim($slug);
        $slug = str_replace(' ', $separador, $slug);
        $slug = strtolower($slug);
        
        return $slug;
    }
    
    function slug_unico($texto, $tabla, $campo = 'slug')
    {
        $slug_base = $this->slug($texto);
        
        //Contar coincidencias
            $condicion = "{$campo} = '{$slug_base}'";
            $cant_registros = $this->num_registros($tabla, $condicion);
        
        $sufijo = '';
        if ( $cant_registros > 0 ) { $sufijo = '-2'; }
        
        $slug = $slug_base . $sufijo;
        
        return $slug;
    }
    
    /**
     * Devuelve un string de la parte GET de la URL actual.
     * Si se agrega el argumento '$quitar' esa variable ($var) no se incluirá en la cadena
     * Sirve para creación de filtros combinados en links
     * 
     * @param type $quitar
     * @return string
     */
    function get_str($quitar = '')
    {
        $get = $this->input->get();
        $get_str = '';
        foreach ( $get as $var => $valor ) 
        {
            if ( $var != $quitar ) { $get_str .= $var . '=' . $valor . '&'; }
        }
        
        return $get_str;
    }
    
//Arrays
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Array con el resultado por defecto de un proceso, valor inicial
     * 
     * @return string
     */
    function res_inicial($mensaje = 'El proceso no fue ejecutado')
    {
        $resultado['ejecutado'] = 0;
        $resultado['mensaje'] = $mensaje;
        $resultado['clase'] = 'alert-danger';
        $resultado['icono'] = 'fa-times';
        
        return $resultado;
    }
    
    /**
     * Array con los valores para poner en un formulario, para crear registro
     * son todos los valores NULL.
     * 
     * @param type $row
     * @return type
     */
    function valores_form($row, $tabla)
    {
        $campos = $this->db->list_fields($tabla);
        
        foreach ( $campos as $campo ) 
        {
            $valores_form[$campo] = NULL;
        }
        
        if ( ! is_null($row) )
        {
            foreach ( $campos as $campo ) 
            {
                $valores_form[$campo] = $row->$campo;
            }
        }
        
        return $valores_form;
    }
    
//---------------------------------------------------------------------------------------------------------
//FUNCIONES AYUDAS DE HTML
    
    function html_select($campo, $opciones, $valor, $att_select, $att_option)
    {
       $html_select = '<select name="' . $campo. '"' . $att_select . '>';
       
       foreach ($opciones as $key => $texto_opcion) {

            $select_add = "";
            if ( $row[$id] == $default ){
                $select_add = " selected='selected'";
            }
            $html_select .= "<option value='{$key}' {$select_add} {$att_option}>{$texto_opcion}</option>";
        }
       $html_select .= '</select>';
       
       return $html_select;
       
    }
    
//---------------------------------------------------------------------------------------------------------
//CARPETAS Y ARCHIVOS
    
    /**
     * Devuelve un array con los nombres de los archivos o carpetas de una carpeta determinada
     * 
     * @param type $carpeta
     * @param string $tipo
     * @return type
     */
    function objetos_carpeta($carpeta, $tipo = NULL)
    {
        $archivos = array();
        $carpetas = array();
        $objetos_total = array();
        $directorio = opendir($carpeta);
        
        //Recorrer directorio
        while ( $objeto = readdir($directorio) ) {
            $objetos_total[] = $objeto;
            if ( is_dir($carpeta . $objeto) ) {
                $carpetas[] = $objeto;
            } else {
                $archivos[] = $objeto;
            }
        }
        
        //Según criterio seleccionado
        if ( is_null($tipo) ) {
            unset($objetos_total[0]);   //el valor '.'
            unset($objetos_total[1]);   //el valor '..'
            $objetos_carpeta = $objetos_total;
        } elseif ( $tipo == 'carpeta' ) {
            unset($objetos_total[0]);   //el valor '.'
            unset($objetos_total[1]);   //el valor '..'
            $objetos_carpeta = $carpetas;
        } elseif ($tipo == 'archivo') {
            $objetos_carpeta = $archivos;
        }
        
        return $objetos_carpeta;
    }
    
    function archivos($carpeta)
    {
        $archivos = array();
        $directorio = opendir($carpeta);
        
        //Recorrer directorio
        while ( $objeto = readdir($directorio) ) {
            $objetos_total[] = $objeto;
            if ( ! is_dir($carpeta . $objeto) ) {
                $archivos[] = $objeto;
            }
        }
        
        return $archivos;
    }
    
    
// HTML
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve el valor de una clase html dependiendo de si un valor es igual
     * a un elemento actual con el que se compara. Si son iguales se devuelve 
     * la clase $activa (Para resaltarlo como actual). Si son diferentes se
     * devuelve la clase $inactiva
     * 
     * @param type $elemento_actual
     * @param type $elemento_comparar
     * @param type $activa
     * @param type $inactiva
     * @return type
     */
    function clase_activa($elemento_actual, $elemento_comparar, $activa, $inactiva = '')
    {
        $clase_activa = $inactiva;
        if ( $elemento_actual == $elemento_comparar ) { $clase_activa = $activa; }
        
        return $clase_activa;
    }
    
    /* Equivalente a la función anchor de codeinginter, pero agrega un mensaje de confirmación antes
    * de abrir el link, si el texto de confirmación es nulo se muestra un texto por defecto
    * que es "¿Confirma la eliminación este registro?" ya que esta función se usa principalmente para eliminación
    * de registros. 
    */
    function anchor_confirm($enlace, $contenido, $atributos = NULL, $texto_confirmacion = NULL)
    {
        if ( is_null($texto_confirmacion) ) { $texto_confirmacion = "¿Confirma la eliminación este registro?"; }
        
        $atributos_total = $atributos . ' onclick="return confirm (' . "'{$texto_confirmacion}'". ');"';
        return anchor($enlace, $contenido, $atributos_total);
    }
    
}
/**
 * FUNCIONES GENERALES PARA EL USO EN EL LENGUAJE JAVASCRIPT
 * DESARROLLADAS POR Mauricio Ojeda-Pepinosa
 * 2014-08-14
 * 
 */

var Pcrn = new function()
{
    /**
     * Controlar el valor de una variable numérica para que su valor permanezca
     * en un rango determinado
     * 
     * @param {type} $valor
     * @param {type} $min
     * @param {type} $max
     * @returns {unresolved}
     */
    this.limitar_entre = function($valor, $min, $max)
    {
        $valor_limitado = $valor;

        if ( $valor_limitado < $min ) $valor_limitado = $min;
        if ( $valor_limitado > $max ) $valor_limitado = $max;

        return $valor_limitado;
    };
    
    /**
     * Controlar el valor de una variable numérica para que su valor permanezca
     * en un rango determinado,
     * Si supera el máximo devuelve el mínimo
     * Si supera el mínimo devuelve el máximo
     * 
     * @param {type} $valor
     * @param {type} $min
     * @param {type} $max
     * @returns {unresolved}
     */
    this.ciclo_entre = function($valor, $min, $max)
    {
        $valor_limitado = $valor;

        if ( $valor_limitado < $min ) $valor_limitado = $max;
        if ( $valor_limitado > $max ) $valor_limitado = $min;

        return $valor_limitado;
    };
    
    this.redondear = function($numero, $decimales)
    {
        var $factor = Math.pow(10, $decimales);
        var $original = parseFloat($numero);
        var $result = Math.round($original * $factor) / $factor;
        return $result;
    };
    
//---------------------------------------------------------------------------------------------------
// HTML elementos

    this.elemento_html = function($elemento, $contenido, $atributos)
    {
        $elemento = '<' + $elemento + ' ' + $atributos + '>' + $contenido + '</' + $elemento + '>';
        return $elemento;
    };
    
    this.anchor = function($destino, $contenido, $atributos)
    {
        $anchor = '<a href="' + $destino + '" ' + $atributos + '>' + $contenido + '</a>';
        return $anchor;
    };

//---------------------------------------------------------------------------------------------------
            
    this.convertir_coordenada = function($pix, $borde, $maximo, $total_pix) {
        //Valores de factores
            $factor_pix = $total_pix / $maximo;
        
        //Calcular
            return ($pix - $borde) / $factor_pix; 
    };
    
};
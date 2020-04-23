<?php
class Search_model extends CI_Model{
    
    function words($search_text)
    {
        
        $words = array();
        
        if ( strlen($search_text) > 2 ){
            
            $no_buscar = array('la', 'el', 'los', 'las', 'del', 'de','y');

            $words = explode(' ', $search_text);

            foreach ($words as $key => $palabra)
            {
                if ( in_array($palabra, $no_buscar ) )
                {
                    unset($words[$key]);
                }
            }
        }
        
        return $words;
    }
    
    /**
     * Array de índices
     * 
     * @return string
     */
    function search_indexes()
    {
        $search_indexes = array(
            'q',        //Texto búsqueda
            'cat',      //Categoría
            'u',        //Usuario
            'list',     //Lista
            'tp',       //Tipo
            'o',        //Order by
            'ot',       //Order by tipo
            'rol',      //Rol de usuario
            'e',        //Editado, fecha de edición
            'est',      //Estado
            'a',        //Área 
            'n',        //Nivel escolar, grado escolar
            'i',        //Institución
            'g',        //Grupo
            'y',        //Year, año generación
            'fi',       //Fecha inicial
            'ff',       //Fecha final
            'fa',       //Fecha hacia atrás
            'ctn',      //Cuestionario
            'cptc',     //Competencia
            'cpnt',     //Componente
            'f1',       //Filtro 1
            'f2',       //Filtro 2
            'f3',       //Filtro 3
            'condicion' //Condición SQL Where adicional
        );
        
        return $search_indexes;
    }
    
    /**
     * Array de búsqueda con valor NULL para todos los índices
     * Valor inicial antes de evaluar contenido de POST y GET
     * @return null
     */
    function default_filters()
    {
        $search_indexes = $this->search_indexes();
        
        foreach ($search_indexes as $index) { $search[$index] = NULL; }
        
        return $search;
    }
    
    /**
     * Array con los parámetros de una búsqueda, respuesta para los dos métodos
     * de solicitud POST y GET.
     * 
     * @return type
     */
    function filters()
    {
        $search = $this->default_filters();
        $search_indexes = $this->search_indexes();
        
        if ( $this->input->post() )
        {
            //POST form search
            foreach ($search_indexes as $index) 
            {
                $search[$index] = $this->input->post($index);
            }
        } else {            
            //Search by GET in URL
            foreach ($search_indexes as $index) 
            {
                $search[$index] = $this->input->get($index);
            }
        }
            
        return $search;
    }
    
    /**
     * String con la cadena para URL tipo GET, con los valores de la búsqueda
     * @return type
     */
    function str_filters()
    {
        $filters = $this->filters();
        $search_indexes = $this->search_indexes();
        $str_filters = '';
        
        foreach ( $search_indexes as $index ) 
        {
            if ( $filters[$index] != '' ) { $str_filters .= "{$index}={$filters[$index]}&"; }
        }
        
        return $str_filters;
    } 
    
    
    /**
     * Devuelve string con segmento sql de fields con el condicional para concatenar
     * 
     * @param type $fields
     * @return type
     */
    function concat_fields($fields)
    {
        $concat_fields = '';
        
        foreach ( $fields as $field ) 
        {
            $concat_fields .= "IFNULL({$field}, ''), ";
        }
        
        return substr($concat_fields, 0, -2);
    }
    
    function words_condition($text_search, $fields)
    {
        $condition = NULL;
        
        if ( strlen($text_search) > 2 )
        {
            $concat_fields = $this->concat_fields($fields);
            $words = $this->words($text_search);

            foreach ($words as $word) 
            {
                $condition .= "CONCAT({$concat_fields}) LIKE '%{$word}%' AND ";
            }
            
            $condition = substr($condition, 0, -5);
            
        }
        
        return $condition;
    }
}
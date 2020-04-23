<?php
class Db_model extends CI_Model{
    
    /* Db, is abbreviation for Database
     * Functions that complement database operations with CodeIgniter
     * Actualizada 2019-06-17
     */
      
    /**
    * 
    * Devuelve el valor de un field ($nombre_field) del primer row de una $table
    * que cumpla una $condition con el formato where de sql.
    */
    function field($table, $condition, $nombre_field)
    {
        $field = NULL;  //Valor por defecto
        $query = $this->db->query("SELECT {$nombre_field} FROM {$table} WHERE {$condition} LIMIT 1");
        
        if ( $query->num_rows() > 0 ){ $field = $query->row()->$nombre_field; }
        
        return $field;
    }
    
    /**
    * 
    * Devuelve el valor de un field ($nombre_field) del primer row de una $table
    * que cumpla 
    */
    function field_id($table, $id, $nombre_field)
    {
        $field = NULL;
        
        if ( strlen($id) > 0 ) 
        {
            $query = $this->db->query("SELECT {$nombre_field} FROM {$table} WHERE id = {$id} LIMIT 1");
            if ( $query->num_rows() > 0 ){ $field = $query->row()->$nombre_field; }
        }
        
        return $field;
    }
    
    /* Devuelve el primer row de una $table
    * que cumpla una $condition con el formato where de sql.
    */
    function row($table, $condition)
    {
        //Valor por defecto
        $row = NULL;

        $query = $this->db->query("SELECT * FROM {$table} WHERE {$condition} LIMIT 1");
        if ( $query->num_rows() > 0 ){ $row = $query->row(); }
        
        return $row;
    }
    
    /* Devuelve el primer row de una $table
    *  teniendo un valor de table.id determinado
    */
    function row_id($table, $id)
    {
        $row = NULL;
        $row_id = 0;
        if ( strlen($id) > 0 ) { $row_id = $id; }
        
        $query = $this->db->query("SELECT * FROM {$table} WHERE id = {$row_id} LIMIT 1");
        if ( $query->num_rows() > 0 ){ $row = $query->row(); }
        
        return $row;   
    }
    
    /* Devuelve el número de rows de una table 
    * con una condición con el formato where de sql
    */
    function num_rows($table, $condition)
    {    
        $this->db->where($condition);
        $query = $this->db->get($table);
        return $query->num_rows();
    }
    
    /**
     * Determina si exists un row con una $condition sql en una $table
     * Si no exists devuelve 0, si exists devuelve el id del row
     * 
     * @param type $table
     * @param type $condition string
     * @return type
     */
    function exists($table, $condition)
    {
        $exists = 0;
        
        $query = $this->db->query("SELECT id FROM {$table} WHERE {$condition} LIMIT 1");
        if ( $query->num_rows() > 0 ){ $exists = $query->row()->id; }
        
        return $exists;
    }
    
    /**
     * Determina si un valor para un field es único en la table. Si se agrega
     * el ID de un row específico, lo descarta en la búsqueda, valor ya
     * existente.
     * 2019-11-05
     */
    function is_unique($table, $field, $value, $row_id = NULL)
    {
        $is_unique = TRUE;
        
        $this->db->select('id');
        $this->db->where("{$field} = '{$value}'");
        $this->db->where("LENGTH({$field}) > 0");   //Que no esté vacío
        if ( ! is_null($row_id) ) { $this->db->where("id <> {$row_id}"); }
        $query = $this->db->get($table);
        
        if ( $query->num_rows() > 0 ) { $is_unique = FALSE; }
        
        return $is_unique;
    }
    
    /**
     * Si un row con una $condition sql existe en una $table, se edita
     * Si no existe se inserta nuevo registro. Devuelve el id del row editado o insertado
     * 2019-12-10
     */
    function save($table, $condition, $arr_row)
    {
        $row_id = $this->exists($table, $condition);
        
        if ( $row_id == 0 ) 
        {
            //Do not exists, insert
            $this->db->insert($table, $arr_row);
            $row_id = $this->db->insert_id();
        } else {
            unset($arr_row['creator_id']);
            //Already exists, update
            $this->db->where('id', $row_id);
            $this->db->update($table, $arr_row);
        }
        
        return $row_id;
    }
    
    /**
     * Si un row con una $condition sql no exists en una $table, se inserta
     * Diferente a Db_model->save(), si exists, NO se edita.
     * Devuelve el id del row editado o insertado
     * 
     * @param type $table
     * @param type $condition
     * @param type $row
     * @return type
     */
    function insert_if($table, $condition, $row)
    {
        $row_id = $this->exists($table, $condition);
        
        if ( $row_id == 0 ) 
        {
            //No exists, insert
            $this->db->insert($table, $row);
            $row_id = $this->db->insert_id();
        }
        
        return $row_id;           
    }

// HELPERS
//-----------------------------------------------------------------------------

    /**
     * Array from Post, adding edition data
     * 2019-11-29
     */
    function arr_row($row_id)
    {
        $arr_row = $this->input->post();
        
        $arr_row['editor_id'] = $this->session->userdata('user_id');
        $arr_row['creator_id'] = $this->session->userdata('user_id');
        
        if ( $row_id == 0 ) { unset($arr_row['creator_id']); }

        return $arr_row;
    }
    
// TEXT AND STRING FUNCTIONS
//-----------------------------------------------------------------------------
    
    function slug($text)
    {
        $this->load->helper('text');
        $slug = convert_accented_characters($text);     //Without accents
        $slug = url_title($slug, '-', TRUE);            //Without spaces Sin espaciosy sin caracteres
        $slug = substr($slug, 0, 140);
        
        return $slug;
    }
    
    function unique_slug($text, $table, $field = 'slug')
    {
        $base_slug = $this->slug($text);
        
        //Count equal slug
            $condition = "{$field} = '{$base_slug}'";
            $num_rows = $this->num_rows($table, $condition);
        
        $sufix = '';
        if ( $num_rows > 0 )
        {
            $this->load->helper('string');
            $sufix = '-' . random_string('numeric', 2);
        }
        
        $slug = $base_slug . $sufix;
        
        return $slug;
    }
}
<?php
class Pcrn_excel extends CI_Model{
    
    /* Pcrn_excel, es una abreviatura de Pacarina y Excel.
     * Colección de funciones creadas por Pacarina Media Lab para utilizarse complementariamente
     * con CodeIgniter, haciendo uso de la Clase PHPExcel. Versión 2017-05-09
     */
    
//---------------------------------------------------------------------------------------------------------
//FUNCIONES LIBERÍA PHPExcel
    
    function array_hoja_default($letra_columna)
    {
        $archivo = $_FILES['file']['tmp_name'];             //Se crea un archivo temporal, no se sube al servidor, se toma el nombre temporal
        $nombre_hoja = $this->input->post('nombre_hoja');   //Nombre de hoja digitada por el usuario en el formulario
        
        $resultado = $this->array_hoja($archivo, $nombre_hoja, $letra_columna);
        
        return $resultado;
    }
    
    /**
     * Convierte un listado de una hoja de cálculo en un array
     * Desde la columna A y la fila 2
     * 
     * @param type $archivo
     * @param type $nombre_hoja
     * @return type
     */
    function array_hoja($archivo, $nombre_hoja, $columna_fin = 'A')
    {
        
        $resultado['valido'] = 0;
        $resultado['mensaje'] = 'Verifique el tipo de archivo o el nombre de la hoja de cálculo';
        $array_hoja = array();
        
        $this->load->library('excel');
        
        $objPHPExcel = PHPExcel_IOFactory::load($archivo);  //Objeto archivo
        $hoja = $objPHPExcel->getSheetByName($nombre_hoja); //Objeto hoja

        if ( ! is_null($hoja) )
        {
            $fila_fin = $hoja->getHighestRow(); //Última fila con datos
            $rango = "A2:{$columna_fin}{$fila_fin}";
            $array_hoja = $hoja->rangeToArray($rango, NULL, TRUE, FALSE);
            
            $resultado['valido'] = 1;
            $resultado['mensaje'] = 'Cargado';
        }
        
        $resultado['array_hoja'] = $array_hoja;
        
        return $resultado;        
    }
    
    /**
     * Genera un objeto PHPExcel, para generar un archivo MS Excel para descargar
     * En el array $datos se requiere:
     *  query: El objeto query que se pone en la tabla de excel
     *  nombre_hoja: Nombre que se le pondrá al a hoja de cálculo
     * 
     * @param type $datos
     * @return type
     */
    function archivo_query($datos)
    {
        //Cargar librería PHPExcel
        $this->load->library('excel');
        
        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        $cacheSettings = array( 'memoryCacheSize' => '30MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings);
        
        //Activar la hoja de cálculo 0
        $this->excel->setActiveSheetIndex(0);
        //Establecer nombre a la hoja de cálculo
        $this->excel->getActiveSheet()->setTitle($datos['nombre_hoja']);
        
        //Encabezados
            $campos = $datos['query']->list_fields();
            foreach ( $campos as $key => $campo ) 
            {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($key, 1, $campo);
            }
        
        //Valores
            $arr_datos = $datos['query']->result_array();
            $this->excel->getActiveSheet()->fromArray($arr_datos, null, 'A2');

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        
        return $objWriter;
    }
    
    /**
     * Genera un objeto PHPExcel, para generar un archivo MS Excel para descargar
     * En el array $datos se requiere:
     *  array: El array que se pone en la tabla de excel
     *  nombre_hoja: Nombre que se le pondrá al a hoja de cálculo
     * 
     * @param type $datos
     * @return type
     */
    function archivo_array($datos)
    {
        
        $this->load->library('excel');          //Cargar librería PHPExcel
        $this->excel->setActiveSheetIndex(0);   //Activar hoja de cálculo con índice 0
        $this->excel->getActiveSheet()->setTitle($datos['nombre_hoja']);    //Asignar nombre a la hoja de cálculo
        
        //Encabezados
            $campos = $datos['campos'];
            foreach ( $campos as $key => $campo ) 
            {
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow($key, 1, $campo);
            }
        
        //Valores
            $fila = 2;
            foreach ( $datos['arr_datos'] as $row )
            {
                foreach ( $campos as $key => $campo )
                {
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow($key,  $fila, $row[$campo]);
                }
                $fila++;
            }

        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');  
        
        return $objWriter;
    }
    
}
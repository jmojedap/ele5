<?php
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

$object_file->save('php://output');
//$objWriter->save(RUTA_CONTENT . 'temp/' . $nombre_archivo . '.xlsx');
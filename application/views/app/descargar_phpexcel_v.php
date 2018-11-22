<?php
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="'. $nombre_archivo .'.xlsx"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

$objWriter->save('php://output');
<?php
require_once(ROOT. DS . 'vendor' . DS . 'PHPExcel.php');

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("creator name");

//HEADER
$i=1;
$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, 'User ID');
$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, 'User Name');
$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'Manager');
//DATA
foreach($data as $user){
    $i++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $user->user_id);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $user->user->name);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $user->user->email);
    if ($user->isManager != null) {
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$i, 'X');
    }
}

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('List Staff');

$filename = $department->department_name . '.xlsx';
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Content-Type: application/vnd.ms-excel');
header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit();

<?php
require 'vendor/autoload.php'; // Adjust the path as needed for PHPExcel or PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileTmpPath = $_FILES['excelFile']['tmp_name'];

    $spreadsheet = IOFactory::load($fileTmpPath);
    $sheet = $spreadsheet->getActiveSheet();

    $customerName = $sheet->getCell('A2')->getValue();
    $address = $sheet->getCell('B2')->getValue();
    $phone = $sheet->getCell('C2')->getValue();
    $email = $sheet->getCell('D2')->getValue();

    $articles = [];
    $row = 3; // Assuming data starts from row 3
    while (!empty($articleName = $sheet->getCell('E'.$row)->getValue())) {
        $size = $sheet->getCell('F'.$row)->getValue();
        $quantity = $sheet->getCell('G'.$row)->getValue();

        $articles[] = [
            'article' => $articleName,
            'size' => $size,
            'quantity' => $quantity
        ];

        $row++;
    }

    $response = [
        'customerName' => $customerName,
        'address' => $address,
        'phone' => $phone,
        'email' => $email,
        'articles' => $articles
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
    
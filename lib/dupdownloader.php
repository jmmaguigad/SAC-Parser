<?php
    error_reporting(0);
    session_start();
    header("Content-type: text/csv");
    header("Content-disposition: attachment; filename = Duplicate_Data.csv");
    $fpdup = fopen('php://output', 'w');        
    $duplist = array();
    $searchvalue = array();
    $file = fopen("../tmp/".$_SESSION['file_scanned'],"r");

    if (count($_SESSION['tag']) > 0) {
        foreach ($_SESSION['tag'] as $key => $value) {
            if ($value > 1){
                $searchvalue[] = $key;
            }
        }
    }

    while (($line = fgetcsv($file)) !== FALSE) {
        $cols = array($line[1]);
        foreach($searchvalue as $val){
            if (in_array($val, $cols)) {
                $duplist[] = $line;   
            }
        }
    }

    $header = array('COL 1' => 'Row Indicator','COL 2' => 'Barcode','COL 3' => 'Last Name','COL 4' => 'First Name','COL 5' => 'Middle Name','COL 6' => 'Ext','COL 7' => 'Rel HH','COL 8' => 'Kapanganakan (mm/dd/yy)','COL 9' => 'Kasarian','COL 10' => 'Trabaho','COL 11' => 'Sektor','COL 12' => 'Kondisyon ng Kalusugan','COL 13' => 'PSGC Barangay Code','COL 14' => 'Tirahan','COL 15' => 'Kalye','COL 16' => 'Uri Ng ID','COL 17' => 'Numero ng ID','COL 18' => 'Buwanang Kita','COL 19' => 'Cellphone Number (+09XXXXXXXX)','COL 20' => 'Pinagtratrabahuhang Lugar','COL 21' => 'Bene_UCT','COL 22' => 'Bene_4ps','COL 23' => 'Katutubo','COL 24' => 'Katutubo  Name','COL 25' => 'Bene_others','COL 26' => 'Others Name','COL 27' => 'Petsa ng Pagrehistro','COL 28' => 'Pangalan ng Punong Barangay','COL 29' => 'Pangalan ng LSWDO');
    fputcsv($fpdup,$header);
    
    // duplicate list session
    $_SESSION['duplist'] = $duplist;

    foreach($duplist as $list) {  
        if (!empty(trim($list[1]))) {
            fputcsv($fpdup, $list);  
        }
    }

    fclose($file);
?>
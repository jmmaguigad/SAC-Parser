<?php
session_start();
error_reporting(0);
include_once 'functions.php';
try{
    if (isset($_FILES) && !empty($_FILES)){
        $mimes = array("application/vnd.ms-excel","text/plain","text/csv");
        // echo in_array($_FILES['forsanitizefile']['type'],$mimes);
        if(in_array($_FILES['forsanitizefile']['type'],$mimes)){
            $start = microtime(TRUE);    
            $handle = fopen($_FILES["forsanitizefile"]["tmp_name"], "r");
            // $_SESSION['file_scanned'] = $_FILES["forsanitizefile"]["tmp_name"];
            $row = 1;
            $tag = [];
            $dateofreg = "";
            $nameofbrgycapt = []; 
            $nameofmswdo = "";
            if (($handle) !== FALSE) {
              while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($data[0] == "H"){
                    if (isset($tag[$data[1]])){
                        $tag[$data[1]]++;
                    } else {
                        $tag[$data[1]] = 1;
                    }
                    if (!empty($data[26])){
                        $dateofreg = $data[26];
                    }
                    if (!empty($data[27]) && !in_array($data[27],$nameofbrgycapt)) {
                        $nameofbrgycapt[$data[12]] = $data[27];
                    }
                    if (!empty($data[28])){
                        $nameofmswdo = $data[28];
                    }
                }
              }
              fclose($handle);
            }
            // move file
            $filename = rand(1000,10000).date('Y.m.d').time;
            $_SESSION['file_scanned'] = $filename.".csv";            
            move_uploaded_file($_FILES["forsanitizefile"]["tmp_name"], "../tmp/{$filename}.csv");
            $end = microtime(TRUE);
            $duration = $end-$start;
            $seconds = round($duration,5); 
            echo "Code executed for $seconds seconds<br/>";
            echo "List of Duplicate values detected: ";
            echo "<p class='duplicatevalues' style='overflow-y:scroll;height:30vh;'>";
            if (count($tag) > 0) {
                $_SESSION['tag'] = $tag;
                foreach ($tag as $key => $value) {
                    if ($value > 1){
                        // output in the result area
                        echo "&#8594; <b>$key</b>". " = ".$value.' occurence'.'<br/>'; 
                    }
                }      
                echo "<a href='lib/dupdownloader.php' target='_blank'><b style='color:green'>Download Duplicate/s</b></a>";
            }
            $_SESSION['brgycapt'] = $nameofbrgycapt;
            $_SESSION['mswdo'] = $nameofmswdo;
            if (!empty($dateofreg)) {
                $_SESSION['datereg'] = registrationDateFormat($dateofreg);
            } else {
                $_SESSION['datereg'] = generateRegistrationDate();
            }
            echo "</p>";  
        }       
    } else {
        echo "<p class='duplicatevalues'>";
        echo "<b style='color:red;'>Error Detected:</b> <br/>";
        echo "&#8594; <b>Incorrect mime/type detected, please select csv file.</b>";
        echo "</p>";
    }   
}catch(\PDOException $e){
    echo $e->getMessage();
}
?>
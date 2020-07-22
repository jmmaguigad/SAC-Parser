<?php
session_start();
error_reporting(0);
include_once 'functions.php';
try{
    if (isset($_FILES) && !empty($_FILES)){
        $mimes = array("application/vnd.ms-excel","text/plain","text/csv");
        if(in_array($_FILES['forsanitizefile']['type'],$mimes)){
            $start = microtime(TRUE);    
            // move file
            $filename = rand(1000,10000).date('Y.m.d').time;
            $_SESSION['file_scanned'] = $filename.".csv";   
            $tempname = $_FILES["forsanitizefile"]["tmp_name"];         
            move_uploaded_file($tempname, "../tmp/{$filename}.csv", "r");
            
            $origfile = fopen("../tmp/{$filename}.csv", 'w');
            $handle = fopen($tempname, "r");
            $row = 1;
            $tag = [];
            $nameofbrgycapt = []; 
            $nameofmswdo = "";
            $brgypsgc = "";
            $regdate = "";
            $hhbarcode = "";
            if (($handle) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($data[0] == "H"){
                        if (isset($tag[trim($data[1])])){
                            $tag[trim($data[1])]++;
                        } else {
                            $tag[$data[1]] = 1;
                        }
                    }
                    if (!empty($data[12])){
                        $brgypsgc = $data[12];
                    }
                    if (isset($data[26])){
                        $date = createDate($data[26]);
                        $regdate = $date;
                        // if (validateDate($date) == 1 && date('m',strtotime($date)) == 4){
                        //     $regdate = $date;
                        // }
                    }
                    if (!empty($data[27]) && !in_array($data[27],$nameofbrgycapt)) {
                        $nameofbrgycapt[$data[12]] = $data[27];
                    }
                    if (!empty($data[28])){
                        $nameofmswdo = $data[28];
                    }                    
                    if (!empty($data[1])){
                        $data[1] = $data[1];
                        $hhbarcode = $data[1];
                    } else {
                        $data[1] = $hhbarcode;
                    }
                    fputcsv($origfile, $data);
                    // $console = $tag[$data[1]];
                    // echo "<script>console.log($console)</script>";
                }
                fclose($handle);
            }
            $end = microtime(TRUE);
            $duration = $end-$start;
            $seconds = round($duration,5); 
            echo "Code executed for $seconds seconds<br/>";
            echo "List of Duplicate values detected: ";
            echo "<p class='duplicatevalues' style='overflow-y:scroll;height:30vh;'>";
            if (count($tag) > 0) {
                $_SESSION['tag'] = $tag;
                foreach ($tag as $key => $value) {
                    if ($value > 1 && $key != ""){
                        // output in the result area
                        echo "&#8594; <b>$key</b>". " = ".$value.' occurence'.'<br/>'; 
                    }
                }      
                echo "<a href='lib/dupdownloader.php' target='_blank'><b style='color:green'>Download Duplicate/s</b></a>";
            }
            $_SESSION['brgycapt'] = $nameofbrgycapt;
            $_SESSION['mswdo'] = $nameofmswdo;
            if (!empty($regdate) && $regdate != ""){
                $_SESSION['datereg'] = $regdate;
            } else {
                $_SESSION['datereg'] = generateRegistrationDate();
            }
            $_SESSION['brgypsgc']  = $brgypsgc;        
            echo "</p>";  
        }       
    } else {
        echo "<p class='duplicatevalues'>";
        echo "<b style='color:red;'>Error Detected:</b> <br/>";
        echo "&#8594; <b>Incorrect mime/type detected, please select csv file.</b>";
        echo "</p>";
    }   
}catch(Exception $e){
    echo "Caught Exception: ". $e->getMessage() . "\n";
}
?>
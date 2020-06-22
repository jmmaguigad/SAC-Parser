<?php
session_start();
try{
    if (isset($_FILES) && !empty($_FILES)){
        $start = microtime(TRUE);    
        $handle = fopen($_FILES["forsanitizefile"]["tmp_name"], "r");
        $row = 1;
        $tag = [];
        $dateofreg = [];
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
                // if (!in_array($data[27],$nameofbrgycapt)){
                //     $nameofbrgycapt[$data[12]] = $data[27];
                // }
                if (!empty($data[27]) && !in_array($data[27],$nameofbrgycapt)) {
                    $nameofbrgycapt[$data[12]] = $data[27];
                }
                if (!empty($data[28])){
                    $nameofmswdo = $data[28];
                }
            }
            // if (!empty($data[27])){
            //     $_SESSION['brgy_captain']
            // }
          }
          fclose($handle);
        }
        $end = microtime(TRUE);
        $duration = $end-$start;
        $seconds = round($duration,5); 
        echo "Code executed for $seconds seconds<br/>";
        echo "Duplicate values detected: ";
        echo "<p class='duplicatevalues' style='overflow-y:scroll;height:50vh;'>";
        if (count($tag) > 0) {
            foreach ($tag as $key => $value) {
                if ($value > 1){
                    echo "&#8594; <b>$key</b>". " = ".$value.' occurence'.'<br/>'; 
                }
            }
        }
        $_SESSION['brgycapt'] = $nameofbrgycapt;
        $_SESSION['mswdo'] = $nameofmswdo;
        // var_dump($_SESSION['brgycapt']);
        // if (count($nameofbrgycapt) > 0){
        //     foreach ($nameofbrgycapt as $key => $value) {
        //         echo "&#8594; <b>$key</b>". " = ".$value.'<br/>'; 
        //     }            
        // }
        echo "</p>";
    } else {
        echo "Please check needed fields and make sure you've uploaded a csv file<br/>";
    }   
}catch(\PDOException $e){
    echo $e->getMessage();
}
// print_r($_POST);
// echo $_POST["fileforsanitize"];
// if ($_POST && $_SESSION['dup_check'] == 1){
//     $handle = fopen($_FILES["file"]["tmp_name"], "r");
//     $row = 1;
//     if (($handle) !== FALSE) {
//       while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//         $num = count($data);
//           for ($c=0; $c < $num; $c++) {

//           }
//         fputcsv($fp, $data);//arrayUnique  
//         $row++;
//       }
//       fclose($handle);
//       ob_flush();
//       exit();
//     }
//     $end = microtime(TRUE);
//   }  
  ?>
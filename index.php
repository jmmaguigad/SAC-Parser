<?php
session_start();
$start = microtime(TRUE);

include_once 'lib/functions.php';

if ($_POST){
  header("Content-type: text/csv");
  header("Content-disposition: attachment; filename = Sanitized_Data.csv");
  $fp = fopen('php://output', 'w');
  $handle = fopen($_FILES["file"]["tmp_name"], "r");
  // arrays used in storing data
  $arrayUnique = array();
  $sortArray = array();
  $listahan = array(); 
  foreach ($_SESSION['duplist'] as $list){
    $listahan[] =  $list[1];
  }
  $lista = array_unique($listahan);
  
  // storage of barcode for household head
  $hhbarcode = "";
  $row = 1;
  if (($handle) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      if (count(array_filter($data)) !== 0){
          if (!in_array(trim($data[1]),$lista)){
            $num = count($data);
            for ($c=0; $c < $num; $c++) {
              if ($c == 0){ //row indicator
                if (empty($data[0])) {
                  if(stristr($haystack,"puno") !== false || firstCharacter($haystack) == "1"){
                    $data[$c] = "H";
                  } else {
                    $data[$c] = "M";
                  }
                }
                if ($data[$c] == "H"){
                  $hhbarcode = $data[1];
                }
              }else if ($c == 1){ //barcode number
                if ($row > 1){
                  if (!empty($data[1])){
                    $data[$c] = formatBarcodeNumber(trim($_POST['psgc']),$data[$c]);
                  } else {
                    $data[$c] = formatBarcodeNumber(trim($_POST['psgc']),$hhbarcode);
                  }
                }
              } else if ($c == 2 || $c == 3 || $c == 4 || $c == 5){
                if ($row > 1){
                  if (!empty(trim($data[$c]))){
                    $data[$c] = cleanName($data[$c]);
                  }
                }
              } else if ($c == 6){
                if ($row > 1){
                  $data[$c] = findRelHH($data[$c]);
                }
              } else if ($c == 7){ //date
                if ($row > 1){
                  $data[$c] = createDate($data[$c]);
                  if (empty(trim($data[$c])) && ($data[22] == "Y" || !empty($data[23]))){
                    $data[$c] = "07/01/1980";
                  }
                }
              } else if ($c == 8){ //Kasarian
                if ($row > 1){
                  $data[$c] = checkKasarian($data[$c]);
                }
              } else if ($c == 9){ //Trabaho and other dependencies
                if ($row > 1){
                  $data[$c] = checkTrabaho($data[$c]);                
                  if ($data[0] == "H"){
                    if ($data[$c] == "" || $data[$c] == "-") {
                      $data[$c] = "-";
                      $data[17] = 0;
                      $data[19] = "-";
                    } else {
                      $data[17] = getBuwanangKita($data[17]);
                      if (!empty(trim($data[19]))){
                        if (trim($data[19]) != "-"){
                          $data[19] = $data[19];
                        } else {
                          $data[19] = "NA";
                        }
                      } else {
                        $data[19] = "NA";
                      }
                    }
                  } else {
                    if ($data[$c] == "" || $data[$c] == "-") {
                      $data[$c] = "-";
                      $data[17] = "";
                      $data[19] = "";  
                    }
                  }  
                }
              } else if ($c == 10){ //sektor
                if ($row > 1){
                  $data[$c] = findSector($data[7],$data[8],$data[$c]);
                }
              } else if ($c == 11){ //kondisyon ng kalusugan
                if ($row > 1){
                  $data[$c] = findKondisyonNgKalusugan($data[$c]);
                }
              } else if ($c == 12){ //psgc brgy code
                if (firstCharacter($data[$c]) != 0) {
                  if ($data[0] == "H"){
                    $data[$c] = str_pad($data[$c], 9, '0', STR_PAD_LEFT);                
                  }
                } 
              } else if ($c == 13 || $c == 14){ //tirahan at kalye
                if ($data[$c] == "" && $data[0] == "H") {
                  $data[$c] = "-";
                } 
                if ($data[0] == "M"){
                  $data[$c] = "";
                } 
              } else if ($c == 15){ //uri ng ID
                if ($row > 1){
                  $data[$c] = findID($data[$c]);
                }
              } else if ($c == 16){ //numero ng ID
                if ($data[$c] == ""){
                  $data[$c] = "-";
                }
              } else if ($c == 18){ //cellphone number
                $cplength = strlen($data[$c]);
                if ($data[0] == "H"){
                  if (($cplength == 10 && firstCharacter($data[$c]) == 9) || ($cplength == 11 && firstCharacter($data[$c]) == 0)){
                  } else {
                    $data[$c] = "-";
                  }
                } if ($data[0] == "M"){
                  $data[$c] = "-";
                }
              } else if ($c == 20 || $c == 21 || $c == 22){ //bene uct and 4ps
                if ($data[0] == "H"){
                  if ($data[$c] == "" || firstCharacter($data[$c]) == "N"){
                    $data[$c] = "N";
                  } else {
                    $data[$c] = "Y";
                  }
                } if ($data[0] == "M"){
                  $data[$c] = "-";
                }
              } else if ($c == 22){ //katutubo => katutubo name is dependent while bene others is dependent on others name...
                if ($data[0] == "H"){
                  if ($c == 22) {
                    if ($data[$c] == "" || firstCharacter($data[$c]) == "N"){
                      $data[$c] = "N";
                      $data[23] = "-";
                    } else {
                      if ($data[23] == "" || $data[23] == "-"){
                        $data[$c] = "N";
                        $data[23] = "-";
                      } else {
                        $data[$c] = "Y";
                      }
                    }
                  }
                } else if ($data[0] == "M"){
                  $data[22] = "";
                  $data[23] = "";             
                }
              } else if ($c == 23){
                if (($data[0] == "H" && $data[22] == "N") || ($data[0] == "M")){
                  $data[$c] = "-"; 
                }
              } else if ($c == 24){
                if ($data[0] == "H"){
                  if ($c == 24){
                    if ($data[$c] == "" || firstCharacter($data[$c]) == "N"){
                      $data[$c] = "N";
                      $data[25] = "-";
                    } else {
                      if ($data[25] == "" || $data[25] == "-"){
                        $data[$c] = "N";
                        $data[25] = "-";
                      } else {
                        $data[$c] = "Y";
                      }
                    }
                  }
                } else if ($data[0] == "M"){
                  $data[24] = "";
                  $data[25] = "";              
                }
              } else if ($c == 26){ //petsa ng pagrehistro
                if ($row > 1){
                  if ($data[0] == "H") { 
                      $data[$c] = $_SESSION['datereg'];
                  } else {
                    $data[$c] = "-";
                  }
                }
              } else if ($c == 27){ //pangalan ng punong brgy
                if ($data[0] == "H" && empty($data[27])){
                  if (count($_SESSION['brgycapt']) > 1){
                    foreach ($_SESSION['brgycapt'] as $key => $value) {
                      if ($data[12] == $key){
                        $data[$c] = $value;
                      }
                    }
                  } else {
                    $data[$c] = array_values($_SESSION['brgycapt'])[0];
                  }
                }
              } else if ($c == 28){ //pangalan ng lswdo
                if ($data[0] == "H" && empty($data[28])){
                  $data[$c] = $_SESSION['mswdo'];
                }
              }
            }          
            $sortArray[] = $data;
          fputcsv($fp, $data);//arrayUnique  
          $row++;
          }
      }
    }
    fclose($handle);
    ob_flush();
    exit();
  }
  $end = microtime(TRUE);
}  
?>
<?php include_once 'view/form.html'; ?>
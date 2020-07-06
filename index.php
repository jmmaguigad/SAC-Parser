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
  $sortArray = array();
  $listahan = array(); 
  foreach ($_SESSION['duplist'] as $list){
    $listahan[] =  $list[1];
  }
  $lista = array_unique($listahan);
  $psgcbrgy = $_SESSION['brgypsgc'];
  
  // storage of barcode for household head
  $hhbarcode = "";
  $row = 1;
  if (($handle) !== FALSE) {
    $header = array('COL 1' => 'Row Indicator','COL 2' => 'Barcode','COL 3' => 'Last Name','COL 4' => 'First Name','COL 5' => 'Middle Name','COL 6' => 'Ext','COL 7' => 'Rel HH','COL 8' => 'Kapanganakan (mm/dd/yy)','COL 9' => 'Kasarian','COL 10' => 'Trabaho','COL 11' => 'Sektor','COL 12' => 'Kondisyon ng Kalusugan','COL 13' => 'PSGC Barangay Code','COL 14' => 'Tirahan','COL 15' => 'Kalye','COL 16' => 'Uri Ng ID','COL 17' => 'Numero ng ID','COL 18' => 'Buwanang Kita','COL 19' => 'Cellphone Number (+09XXXXXXXX)','COL 20' => 'Pinagtratrabahuhang Lugar','COL 21' => 'Bene_UCT','COL 22' => 'Bene_4ps','COL 23' => 'Katutubo','COL 24' => 'Katutubo  Name','COL 25' => 'Bene_others','COL 26' => 'Others Name','COL 27' => 'Petsa ng Pagrehistro','COL 28' => 'Pangalan ng Punong Barangay','COL 29' => 'Pangalan ng LSWDO');
    fputcsv($fp,$header);            
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      if (count(array_filter($data)) !== 0){
        if (!in_array(trim($data[1]),$lista)){
          if($row == 1){ $row++; continue; }
          $num = count($data);
          for ($c=0; $c < $num; $c++) {
            if ($c == 0){ //row indicator
              if (empty($data[0])) {
                if((stristr($haystack,"puno") !== false || firstCharacter($haystack) == "1")){ //|| empty($data[0])
                  $data[$c] = "H";
                } else {
                  $data[$c] = "M";
                }
              } else {
                $data[$c] = trim($data[$c]);
              }                
            }else if ($c == 1){ //barcode number
              $psgc = $_POST['psgc'];
              if (firstCharacter($psgc) != 0){
                $psgc = "0".$psgc;
              }
              if (!empty($data[1])){
                $data[$c] = formatBarcodeNumber(trim($psgc),$data[$c]);
                $hhbarcode = $data[1];
              } else {
                $data[$c] = formatBarcodeNumber(trim($psgc),$hhbarcode);
              }
            } else if ($c == 2 || $c == 3 || $c == 4 || $c == 5){
              if (!empty(trim($data[$c]))){
                $data[$c] = cleanName($data[$c]);
              }
            } else if ($c == 6){ //relation to household head
              $data[$c] = findRelHH($data[0],$data[$c]);
            } else if ($c == 7){ //date
              $data[$c] = createDate($data[$c]);
              if (empty(trim($data[$c])) && (!empty($data[23]) && $data[23]!= "-")){
                $data[$c] = "07/01/1980";
              }
            } else if ($c == 8){ //Kasarian
              $data[$c] = checkKasarian($data[$c]);
            } else if ($c == 9){ //Trabaho and other dependencies
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
                } else {
                  // solution for bug: proper formatting of kita of member
                  $data[17] = 0;
                }
              }  
            } else if ($c == 10){ //sektor
              $data[$c] = findSector($data[7],$data[8],$data[$c]);
            } else if ($c == 11){ //kondisyon ng kalusugan
              $data[$c] = findKondisyonNgKalusugan($data[$c]);
            } else if ($c == 12){ //psgc brgy code
              if ($data[0] == "H"){
                $data[$c] = cleanPSGC($data[0],$data[12]);
              }
            } else if ($c == 13 || $c == 14){ //tirahan at kalye
              if ($data[$c] == "" && $data[0] == "H") {
                $data[$c] = "-";
              } 
              if ($data[0] == "M"){
                $data[$c] = "";
              } 
            } else if ($c == 15){ //uri ng ID
              $data[$c] = findID($data[$c]);
            } else if ($c == 16){ //numero ng ID
              if ($data[$c] != ""){
                $data[$c] = $data[16];
              } else {
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
            } else if ($c == 22){ //katutubo
              if ($data[0] == "H"){
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
              } else if ($data[0] == "M"){
                $data[22] = "";
                $data[23] = "";             
              }
            } else if ($c == 23){ //katutubo name
              if ($data[0] == "H"){
                if ($data[22] == "N" || strlen($data[$c]) <= 1) {
                  $data[$c] = "-"; 
                  $data[22] = "N";
                }
              } else {
                $data[$c] = "-"; 
                $data[22] = "-";
              }
            } else if ($c == 24){ //others
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
            } else if ($c == 25){ //others name
              if ($data[0] == "H"){
                if ($data[24] == "N" || strlen($data[$c]) <= 1) {
                  $data[$c] = "-"; 
                  $data[24] = "N";
                }
              } else {
                $data[$c] = "-"; 
                $data[24] = "-";
              }
            } else if ($c == 26){ //petsa ng pagrehistro
              if ($data[0] == "H") { 
                  $data[$c] = $_SESSION['datereg'];
              } else {
                $data[$c] = "-";
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
        fputcsv($fp, $data);  
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
<?php
$start = microtime(TRUE);

function firstCharacter($word){
  return substr(strtoupper($word), 0, 1);
}

// Barcode
function formatBarcodeNumber($psgc,$barcode){
  // check if there's any dash present
  if (stristr($barcode,"-") !== false){
    $barcode = substr($barcode, strrpos($barcode, '-') + 1);
  }
  $length = strlen($barcode);
  $zero = "";
  for($l=0;$l < (8 - $length);$l++){
    $zero .= "0"; 
  }
  $returnval = $zero . $barcode;
  return 'PH-COVID-'.$psgc.'-'.$returnval;
}

// Date
function createDate($date){
  $arrMonths = ["January","February","March","April","May","June","July","August","September","October","November","December"];
  $flag = 0;
  foreach($arrMonths as $month){
    if (stristr($date,$month) !== false){
      $flag = 1;
    break;
    }
  }
  if ($flag == 1){
    $date = date("m/d/Y",strtotime($date));
  }
  $date = preg_replace('#/+#','/',str_replace("-","/",$date));
  if (!empty($date)){
    if (strlen($date) < 9){
      $twodigits = substr($date,strlen($date)-(strlen($date)+2),2);
      $remainingdigits = trim(str_replace($twodigits,"",$date));
      if ($twodigits > 20 && $twodigits < 100){
        $appenddigits = 19;
      } else {
        $appenddigits = 20;
      }
      $returnedDate = formatDate($remainingdigits,$appenddigits,$twodigits);
    } else {
      $returnedDate = $date;
    }    
  } else {
    $returnedDate = "";
  }
  return $returnedDate;
}

function formatDate($remainingdigit,$appenddigit,$twodigit){
  return $remainingdigit.$appenddigit.$twodigit;
}

//calculating buwanang kita
function getBuwanangKita($strKita){
  $kita = round(preg_replace('/[^0-9.]/','', $strKita));
  if (!empty($kita)){
      if (stristr($strKita,"day") !== false || stristr($strKita,"araw") !== false){
          $returnKita = $kita * 22;
      } else if (stristr($strKita,"week") !== false){
          $returnKita = $kita * 4;
      } else if (stristr($strKita,"harvest") !== false){
          $returnKita = $kita / 3;
      } else {
          $returnKita = $kita;
      }
  } else {
      $returnKita = 0;
  }
  return round($returnKita);
}

// URI ng ID
function findID($haystack){
  if (!empty($haystack)){
    if(stristr($haystack,"driv") !== false) {
      $id = "DRIVER'S LICENSE";
    } else if (stristr($haystack,"barang") !== false || stristr($haystack,"brgy") !== false){
      $id = "BARANGAY CERTIFICATION";
    } else if (stristr($haystack,"company") !== false || stristr($haystack,"employee") !== false){
      $id = "EMPLOYMENT ID";
    } else if (stristr($haystack,"gsis") !== false) {
      $id = "GSIS UMID";
    } else if (stristr($haystack,"ncip") !== false || stristr($haystack,"i.p") !== false){
      $id = "NCIP CERTIFICATION";
    } else if (stristr($haystack,"ofw") !== false || stristr($haystack,"owwa") !== false){
      $id = "OFW";
    } else if (stristr($haystack,"passport") !== false){
      $id = "PASSPORT";
    } else if (stristr($haystack,"health") !== false || stristr($haystack,"phil") !== false){
      $id = "PHILHEALTH";
    } else if (stristr($haystack,"postal") !== false){
      $id = "POSTAL";
    } else if (stristr($haystack,"prc") !== false){
      $id = "PRC";
    } else if (stristr($haystack,"pwd") !== false){
      $id = "PWD";
    } else if (stristr($haystack,"osca") !== false || stristr($haystack,"senior") !== false || stristr($haystack,"citizen") !== false || 
      stristr($haystack,"s.c") !== false || stristr($haystack,"sc") !== false){
      $id = "SENIOR CITIZEN";
    } else if (stristr($haystack,"sss") !== false){
      $id = "SSS UMID";
    } else if (stristr($haystack,"solo") !== false || stristr($haystack,"single") !== false){
      $id = "SOLO PARENT";
    } else if (stristr($haystack,"tin") !== false || stristr($haystack,"bir") !== false){
      $id = "TIN";
    } else if (stristr($haystack,"vote") !== false){
      $id = "VOTER'S ID";
    } else {
      $id = "OTHERS";
    }
  } else {
    $id = "NONE";
  }
  return $id;
}

function replaceSpecialCharacter($replacement,$word){
  $word = preg_replace('/[^A-Za-z0-9-]/', '', $word);
  return str_replace("-",$replacement,$word);
}

function findSector($birthday,$kasarian,$haystack){
  if (!empty($haystack)){
    if(stristr($haystack,"buntis") !== false || firstCharacter($haystack) == "B") {
      $sector = "B - Buntis";
    } else if(stristr($haystack,"tanda") !== false || firstCharacter($haystack) == "A") {
      $birthDate = explode("/", $birthday);
      $arrCount = count($birthDate);
      if ($arrCount >= 3){
        $birthDate = array_values(array_filter($birthDate));
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));          
      } else if ($arrCount < 3){
        $age = date("Y") - $birthDate[1];
      }
      if ($age > 59){
        $sector = "$age - A - Nakakatanda";
      } else {
        $sector = "$age - W";
      }           
    } else if(stristr($haystack,"suso") !== false || firstCharacter($haystack) == "C") {
      if ($kasarian == "M"){
        $sector = "W";
      } else {
        $sector = "C - Nagpapasusong Ina";
      }     
    } else if(stristr($haystack,"pwd") !== false || firstCharacter($haystack) == "D") {
      $sector = "D - PWD";
    } else if(stristr($haystack,"solo") !== false || firstCharacter($haystack) == "E") {
      $sector = "E - Solo Parent";
    } else if(stristr($haystack,"homeless") !== false || stristr($haystack,"tirahan") !== false || firstCharacter($haystack) == "F") {
      $sector = "F - Walang Tirahan";
    } else {
      $sector = "W";
    }
  } else {
    $sector = "W";
  }
  return $sector;
}

function findKondisyonNgKalusugan($haystack){
  if (!empty($haystack)){
    if (strlen($haystack) > 1){
      $haystack = substr($haystack,1,1);
      if(stristr($haystack,"puso") !== false || firstCharacter($haystack) == "1") {
        $kalusugan = "1 - Sakit sa Puso";
      } else if(stristr($haystack,"presyo") !== false || stristr($haystack,"blood") !== false || firstCharacter($haystack) == "2") {
        $kalusugan = "2 - Altapresyon";              
      } else if(stristr($haystack,"baga") !== false || firstCharacter($haystack) == "3") {
        $kalusugan = "3 - Sakit sa baga";
      } else if(stristr($haystack,"betes") !== false || stristr($haystack,"diyabetis") !== false || firstCharacter($haystack) == "4") {
        $kalusugan = "4 - Diyabetis";
      } else if(stristr($haystack,"cancer") !== false || stristr($haystack,"kanser") !== false || firstCharacter($haystack) == "5") {
        $kalusugan = "5 - Kanser";
      } else {
        $kalusugan = "0";
      }      
    }
  } else {
    $kalusugan = "0";
  }
  return $kalusugan;
}

function findRelHH($haystack){
  if (!empty($haystack)){
    if(stristr($haystack,"puno") !== false || stristr($haystack,"head") !== false || stristr($haystack,"pamilya") !== false || firstCharacter($haystack) == "1") {
      $relHH = "1 - Puno ng Pamilya";
    } else if(stristr($haystack,"asawa") !== false || stristr($haystack,"mister") !== false || stristr($haystack,"misis") !== false ||  stristr($haystack,"live") !== false || stristr($haystack,"wife") !== false || stristr($haystack,"husband") !== false || firstCharacter($haystack) == "2") {
      $relHH = "2 - Asawa";
    } else if(stristr($haystack,"anak") !== false || stristr($haystack,"son") !== false || stristr($haystack,"daugh") !== false || firstCharacter($haystack) == "3") {
      $relHH = "3 - Anak";
    } else if (stristr($haystack,"kapatid") !== false || firstCharacter($haystack) == "4" || stristr($haystack,"brother") !== false || stristr($haystack,"sister") !== false) {
      if (stristr($haystack,"law") !== false) {
        $relHH = "8 - Other Relative";
      }else{
        $relHH = "4 - Kapatid";
      }
    } else if (stristr($haystack,"bayaw") !== false || stristr($haystack,"hipag") !== false || firstCharacter($haystack) == "5") {
      $relHH = "5 - Bayaw o Hipag";
    } else if (stristr($haystack,"apo") !== false || (stristr($haystack,"grand") !== false)) {
      if (stristr($haystack,"son") !== false || stristr($haystack,"daugh") !== false) {
        $relHH = "6 - Apo";
      }
    } else if (stristr($haystack,"tatay") !== false || stristr($haystack,"nanay") !== false || (stristr($haystack,"law") !== false && (stristr($haystack,"mother") !== false || stristr($haystack,"father") !== false))) {
      $relHH = "7 - Tatay/Nanay";
    } else {
      $relHH = "8 - Other Relative";
    }
  } else {
    $relHH = "8 - Other Relative";
  }
  return $relHH;
}

if ($_POST){
  header("Content-type: text/csv");
  header("Content-disposition: attachment; filename = Sanitized_Data.csv");
  $fp = fopen('php://output', 'w');
  $handle = fopen($_FILES["file"]["tmp_name"], "r");
  // arrays used in storing data
  $arrayUnique = array();
  $arrayDups = array();
  // storage of barcode for household head
  $hhbarcode = "";
  $row = 1;
  if (($handle) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
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
            if (!empty($data[1])){
              $data[$c] = formatBarcodeNumber(trim($_POST['psgc']),$data[$c]);
            } else {
              $data[$c] = formatBarcodeNumber(trim($_POST['psgc']),$hhbarcode);
            }
            if ($data[0] == "H"){
              $arrayUnique[] = array_push($arrayUnique,$data[$c]);
            }
          } else if ($c == 6){
            $data[$c] = findRelHH($data[$c]);
          } else if ($c == 7){ //date
            $data[$c] = createDate($data[$c]);
          } else if ($c == 9){ //Trabaho and other dependencies
            if ($data[0] == "H"){
              if ($data[$c] == "" || $data[$c] == "-") {
                $data[$c] = "-";
                $data[17] = 0;
                $data[19] = "-";
              } else {
                $data[17] = getBuwanangKita($data[17]);
              }
            } else {
              if ($data[$c] == "" || $data[$c] == "-") {
                $data[$c] = "-";
                $data[17] = "";
                $data[19] = "";  
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
              $data[$c] = "0".$data[$c];
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
              if ($data[$c] == "" || $data[$c] == "N"){
                $data[$c] = "N";
              } else {
                $data[$c] = "Y";
              }
            } if ($data[0] == "M"){
              $data[$c] = "-";
            }
          } else if ($c == 22 || $c == 24){ //katutubo => katutubo name is dependent while bene others is dependent on others name...
            if ($data[0] == "H"){
              if ($data[$c] == "" || $data[$c] == "N"){
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
            } if ($data[0] == "M"){
              $data[$c] = "-";
              $data[23] = "-";
            }
          }
        }
      fputcsv($fp, $data);//arrayUnique  
      $row++;
    }
    fclose($handle);
    ob_flush();
    exit();
  }
  $end = microtime(TRUE);
}  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<form action="" method="POST" enctype="multipart/form-data">
  <fieldset>
    <label for="file_sanitize">Province PSG Code</label>
    <input type="text" name="psgc">  
    <label for="file_sanitize">File to be Sanitized</label>
    <input type="file" name="file">
    <input type="submit" name="submit"/> 
  </fieldset>
</form>
</body>
</html>
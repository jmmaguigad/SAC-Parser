<?php
  // remove error reporting
  error_reporting(0);
  
  // helpers
  function firstCharacter($word){
    return substr(strtoupper($word), 0, 1);
  }

  function replaceSpecialCharacter($replacement,$word){
    $word = preg_replace('/[^A-Za-z0-9-]/', '', $word);
    return str_replace("-",$replacement,$word);
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
  
  // Date for Birthday
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
    $specialchar = [" ","-","`","'"];
    $date = preg_replace('#/+#','/',str_replace($specialchar,"/",trim($date)));
    $sanitizeddate = explode("/",$date);
    if (strlen($sanitizeddate[2]) == 2){
        $year = '20'.$sanitizeddate[2];
        if ($sanitizeddate[0] > 12) {
            $month = $sanitizeddate[1];
            $day = $sanitizeddate[0];
        } else {
            $month = $sanitizeddate[0];
            $day = $sanitizeddate[1];
        }
        if ($year > date('Y')){
            $year = $year - 100;
        }
        $returndate = $month."/".$day."/".$year;
    } else if (strlen($sanitizeddate[2]) == 4){
        if ($sanitizeddate[0] > 12){
            $returndate = $sanitizeddate[1]."/".$sanitizeddate[0]."/".$sanitizeddate[2];
        } else {
            $returndate = $date;
        }
    } else{
        $returndate = "";
    }    
    return $returndate;
  }
  
  function registrationDateFormat($date){
    $sanitizeddate = explode("/",$date);
    $substr1 = intval($sanitizeddate[0]);
    if ($substr1 > 12){
        $newdate = explode("/",$sanitizeddate[1]."/".$sanitizeddate[0]."/2020");
        if ($substr1 != 4){
            $returndate = "04/".$newdate[1]."/2020";
        } else {
            $returndate = $newdate;
        }        
    } else {
        if ($substr1 != 4){
            $returndate = "04/".$sanitizeddate[1]."/2020";
        } else {
            $returndate = $date;
        }
    }
    return $returndate;
  }

  function generateRegistrationDate(){
    $rangeDate = rand(strtotime("Apr 01 2020"), strtotime("Apr 30 2020"));
    return date("m/d/Y", $rangeDate);
  }

  // find relationship to household head
  function findRelHH($indicator,$haystack){
    if ($indicator == "H") {
      $relHH = "1 - Puno ng Pamilya";
    } else if ($indicator == "M") {
      if (!empty($haystack)){
        if(stristr($haystack,"asawa") !== false || stristr($haystack,"mister") !== false || stristr($haystack,"misis") !== false ||  stristr($haystack,"live") !== false || stristr($haystack,"wife") !== false || stristr($haystack,"husband") !== false || firstCharacter($haystack) == "2") {
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
        } else if (stristr($haystack,"apo") !== false || (stristr($haystack,"grand") !== false && (stristr($haystack,"son") !== false || stristr($haystack,"daugh") !== false))) {
            $relHH = "6 - Apo";
        } else if (stristr($haystack,"tatay") !== false || stristr($haystack,"nanay") !== false || (stristr($haystack,"law") !== false && (stristr($haystack,"mother") !== false || stristr($haystack,"father") !== false))) {
          $relHH = "7 - Tatay/Nanay";
        } else {
          $relHH = "8 - Other Relative";
        }
      } else {
        $relHH = "8 - Other Relative";
      }
    }
    return $relHH;
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

  // find sector
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
            $birthDate = substr($birthDate,-4);
            $age = date("Y") - $birthDate;
        }
        if ($age > 59){
            $sector = "A - Nakakatanda";
        } else {
            $sector = "W";
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
  
  // find kondisyon ng kalusugan
  function findKondisyonNgKalusugan($haystack){
    if (!empty($haystack)){
      if (strlen($haystack) >= 1){
        // $haystack = substr($haystack,1,1);
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

  // trabaho checker
  function checkTrabaho($haystack){
    if (!empty($haystack)){
      if(stristr($haystack,"student") !== false || (stristr($haystack,"house") !== false && stristr($haystack,"wife") !== false) || (stristr($haystack,"kasa") !== false && stristr($haystack,"bahay") !== false) || stristr($haystack,"tambay") !== false) {
        $trabaho = "-";
      } else {
        $trabaho = strtoupper($haystack);
      }
    } else {
      $trabaho = "-";
    }
    return $trabaho;
  }

  function checkKasarian($haystack){
    if (!empty($haystack)){
      if(stristr($haystack,"male") !== false || stristr($haystack,"lala") !== false || firstCharacter($haystack) == "m" || firstCharacter($haystack) == "M") {
        $kasarian = "M";
      } else if(stristr($haystack,"female") !== false || stristr($haystack,"baba") !== false || firstCharacter($haystack) == "f" || firstCharacter($haystack) == "F") { 
        $kasarian = "F";
      }
    } else {
      $kasarian = "M";
    }
    return $kasarian;
  }  

  function cleanName($name){
    $specialchar = ["\\",",",".","/","`","'"];
    $name = str_replace($specialchar,"",trim($name));
    return $name;
  }

  // psgc
  function cleanPSGC($indicator,$psgc){
    if (firstCharacter($psgc) != 0 && strlen($psgc) == 8) {
      if ($indicator == "H"){
        $returnpsgc = str_pad($psgc, 9, '0', STR_PAD_LEFT);                
      }
    } else if (strlen($psgc) >= 9) {
      if ($indicator == "H"){
        $returnpsgc = preg_replace('/[^0-9]/','',$psgc);
      }
    } else {
      if ($indicator == "M"){
        $returnpsgc = "";
      }
    } 
    return $returnpsgc;
  }

  // simple checking of date (for registration date)
  function validateDate($date, $format = 'm/d/Y'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
  }  
?>
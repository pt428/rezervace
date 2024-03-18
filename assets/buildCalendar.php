<?php 
function build_calendar($month,$year) {//vytvor kalendar
    
    $admin=0;
    $admin=$_SESSION["admin"];
    $monthNow=  getdate()["mon"];//aktualni mesic
    //pripoj se k DB
    $mysqli=connectDB();
    $stmt=$mysqli->prepare("select * from bookings where MONTH(date)=? and YEAR(date)=?");
    $stmt->bind_param('ss',$month,$year);
    $bookingsDB=array();//cela tabulka z DB
    $waitingDB=array();//cekajici rezervace
    $idDB=array();//ID z DB
    $nameDB=array();//jmeno a prijmeni z DB
    $emailDB=array();//email z DB
    $dateDB=array();//datum rezervace z DB
    if($stmt->execute()){
        $result=$stmt->get_result();
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $idDB[]=$row["id"];
                $bookingsDB[]=$row["date"];
                $waitingDB[]=$row["waiting"];
                $nameDB[]=$row["name"];
                $emailDB[]=$row["email"];
                $dateDB[]=$row["date"];
                $phoneDB[]=$row["phone"];
                $addressDB[]=$row["address"];
                $cityDB[]=$row["city"];
            }            
            $stmt->close();
        }
    }
    
    //pole se dny v tydnu
    $daysOfWeek = array("Pondělí","Úterý","Středa","Čtvrtek","Pátek","Sobota","Neděle");    
    
    //prvni den mesice bude argument teto funce
    $firstDayOfMonth=mktime(0,0,0,$month,1,$year);
    
    //pocet dni v mesici
    $numberDays = date("t",$firstDayOfMonth);
    
    //info o prvnim dni mesice
    
    $dateComponents=getdate($firstDayOfMonth);
    
    //jmeno tohoto mesice
    $monthName=$dateComponents['month'];
    $monthName= prevedNaCeskeNazvyMesicu($monthName);
    //zjistit index 0-6 prvniho dne v mesici
    $dayOfWeek = $dateComponents['wday'];
    if ($dayOfWeek==0 )$dayOfWeek= 7;
    //zjistit aktualini den   
    $dateToday = date('Y-m-d');
    
    //vytvorime HTML tabulku    
    $prev_month=date("m",mktime(0,0,0,$month-1,1,$year));
    $prev_year=date("Y",mktime(0,0,0,$month-1,1,$year));
    $next_month=date("m",mktime(0,0,0,$month+1,1,$year));
    $next_year=date("Y",mktime(0,0,0,$month+1,1,$year));
    //tvotime kalendar
    
   
     $calendar=  "<center><h2>$monthName $year</h2></center>"; 
     $calendar=$calendar . "        
     <div class='row'> 
         <div class='col-md-12 '>";

   if($monthNow<$month || $admin){
        $calendar=$calendar . 
        "<a class='btn btn-danger' href='?month=" . $prev_month . "&year=" . $prev_year . "'>Předchozí měsíc</a>";
    }else{
        $calendar=$calendar . 
        "<a class='btn btn-danger disabled'>Předchozí měsíc</a>";      
    }
    
    $calendar=$calendar . 
    "<a class='btn btn-success' href='?month=".date('m')."&year=".date('Y')."'>Aktuální měsíc</a>";
    $calendar=$calendar . 
    "<a class='btn btn-danger' href='?month=" . $next_month . "&year=" . $next_year . "'>Další měsíc</a>";
    $calendar=$calendar . 
    "<a class='btn btn-outline-info' href='cenik.php'>Cenik</a>";
    $calendar=$calendar . 
    "<a class='btn btn-outline-info' href='foto.php'>Fotogalerie</a>";
   
    
    $calendar=$calendar ."<table class='table table-bordered'>";
    $calendar= $calendar . "<tr>";
    
    //hlavicka kalendare    
    foreach($daysOfWeek as $day){
        $calendar= $calendar . "<th class='header'>$day</th>";
    }
    
    $calendar= $calendar ."</tr><tr>";
    
    // promenne $dayOfWeek musi byt jenom 7 sloupcu  v tabulce
    if ($daysOfWeek>2){
        for($k=1;$k<$dayOfWeek;$k++){
            $calendar= $calendar . "<td   class='empty'></td>";
        }
        
    }
    //pocitadlo dne
    $currentDay=1;
    
    //cislo mesice
    $month = str_pad($month, 2,"0",STR_PAD_LEFT);    
    
    while($currentDay<=$numberDays){
        //sedm sloupcu sobota zacina novy radek
        if($dayOfWeek==8){
            $dayOfWeek=1;
            $calendar= $calendar ."</tr><tr>";
        }
        $currentDayRel = str_pad($currentDay,2 ,"0",STR_PAD_LEFT);
        $date="$year-$month-$currentDayRel";
        $dayName=strtolower(date("l",strtotime($date)));
        $today=$date==date("Y-m-d")?"today":"day";
        //hledam datum rezervace v DB
        if(in_array($date, $bookingsDB)){
            $id=array_search($date, $bookingsDB);//hleda datum v DB
        }else{
            $id=-1;
        }
        //zobrati se jmeno a prijmeni pouze ADMIN*************************************
        if($id>=0 && $admin){
            if($waitingDB[$id]===1){//ADMIN tlacitka zarezevovano a ceka na schvaleni
                $calendar= $calendar . 
                "<td class='$today data-bs-toggle=modal data-bs-target=#modal-edit'><h4>$currentDay<h4>
                <a href=
                'admin.php?month=".$month.
                "&year=" .$year.
                "&id=" . $id .
                "&idDB=". $idDB[$id].
                "&dateDB=". $dateDB[$id].
                "&nameDB=". $nameDB[$id].
                "&emailDB=". $emailDB[$id].
                "&phoneDB=". $phoneDB[$id].
                "&addressDB=". $addressDB[$id].
                "&cityDB=". $cityDB[$id].
                "'class='btn btn-danger btn-xs  '
                >Rezervováno<br>$nameDB[$id]</a></td>";
            }else{
                $calendar= $calendar . 
                "<td class='$today'><h4>$currentDay<h4> 
                <a href=
                'admin.php?month=".$month.
                "&year=" .$year.
                "&id=" . $id .
                "&idDB=".  $idDB[$id].
                "&dateDB=". $dateDB[$id].
                "&nameDB=".$nameDB[$id].
                "&emailDB=". $emailDB[$id].
                "&phoneDB=". $phoneDB[$id].
                "&addressDB=". $addressDB[$id].
                "&cityDB=".  $cityDB[$id].
                "'class='btn btn-warning btn-xs data-bs-toggle=modal data-bs-target=#modal-edit'>Čeká na schválení<br>$nameDB[$id]</a></td>";
            }// standartni zobrazeni kalendare pouze user ******************************
        }elseif($id>=0 && !$admin){
                if($waitingDB[$id]===1){//user tlacitka zarezevovano a ceka na schvaleni
                    $calendar= $calendar . 
                    "<td class='$today'><h4>$currentDay<h4>
                    <button  class='jq--scroll-form btn btn-danger btn-xs'>Rezervováno<br></button></td>";
                }else{
                    $calendar= $calendar . 
                    "<td class='$today'><h4>$currentDay<h4>
                    <button class='btn btn-warning  btn-xs'>Čeká na schválení<br></button></td>";
                    
                }
           
        }else{     //tlacitka rezervovat pouze user   
            $calendar= $calendar . 
            "<td class='$today'><h4>$currentDay<h4>
            <a href=
            'book.php?fromdate=" . $date . 
            "&todate=".$date . 
            "'class='btn btn-success btn-xs'>Rezervovat</a></td>";
      
          
            
        }
        //******************************************************

        //zvysit pocitadla
        $currentDay++;
        $dayOfWeek++;
    }
    //zkompletujeme radek posledniho tydne v mesici pokud to je nutne
    if($dayOfWeek!=8){
        $remainingDays=9-$dayOfWeek;
        for($i=1;$i<$remainingDays;$i++){
            $calendar= $calendar . "<td></td>";
        }
    }
   
    $calendar= $calendar . "</table> ";
      
    //po kliknuti na datum v kalendari se zobrazi data z DB
    if(isset($_GET["id"]) and $admin){
      
         
        //ADMIN editovaci formular
         
       
       
        
        $calendar= $calendar . "<footer>";
          $calendar= $calendar .  "<div class=form-edit>";
        
   
        $calendar= $calendar . "<form class='jq--form' action='' method='post'>";
     
        $calendar= $calendar . "<h1 class='text-centre'>Editovací fomulář</h1>";
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>ID</span></div>"; 
        $calendar= $calendar . "<input type='text' class='form-control' name='idedit' value='". $_GET['idDB'] . "'></div>" ;
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>Datum</span></div>";
        $calendar= $calendar . "<input type='date' class='form-control' name='dateEdit' value='". $_GET['dateDB'] . "'></div>" ;
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>Jméno a příjmení</span></div>";
        $calendar= $calendar . "<input type='text'  class='form-control' name='name' value='". $_GET['nameDB'] . "'></div>  ";
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>Adresa</span></div>";
        $calendar= $calendar . "<input type='text' class='form-control' name='email' value='". $_GET['addressDB'] . "'></div>";
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>Město</span></div>";
        $calendar= $calendar . "<input type='text' class='form-control' name='email' value='". $_GET['cityDB'] . "'></div>";
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>Email</span></div>";
        $calendar= $calendar . "<input type='email' class='form-control' name='email' value='". $_GET['emailDB'] . "'></div>";
        $calendar= $calendar . "<div class='input-group mb-3'>";
        $calendar= $calendar . "<div class='input-group-prepend'>";
        $calendar= $calendar . "<span class='input-group-text'>Telefon</span></div>";
        $calendar= $calendar . "<input type='rext' class='form-control' name='phone' value='". $_GET['phoneDB'] . "'></div>";
     
        if($_GET["id"]!=""){
            $calendar= $calendar . 
            "<a 
            class='btn  btn-warning'  
            href='admin.php?month=".$month.
            "&year=".$year.
            "&idEdit=" . $_GET['idDB'] . 
            "'
            >Schválit rezervaci</a> ";
            $calendar= $calendar .
            "<a 
            class='btn btn-danger' 
            href='admin.php?month=".$month.
            "&year=".$year.
            "&deleteID=" . $_GET['idDB'] . 
            "'
            >Smazat rezervaci</a>
            "; 
            $calendar= $calendar .
            "<a 
            class='btn btn-secondary ' 
            href='admin.php?month=".$month.
            "&year=".$year."'
            >Zavřít</a></form>"; 

        } 
        $calendar= $calendar . "</form></div></footer> ";
 
    }
    return  $calendar;
    
}


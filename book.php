<?php 
    require "assets/db.php";
    require "assets/email.php";
    session_start();
     $admin=0;
    
     $name=NULL;
     $email=NULL;
     $note= NULL;
     $fromDate=NULL;
     $toDate=NULL;      
     $phone=NULL;
     $address=NULL;
     $city=NULL;

     if($_SESSION["admin"]){
        $wait=1;
     }else{
        $wait=0;
     }

    if(isset($_GET["fromdate"])  ){
        $fromDate=$_GET["fromdate"]; 
        $toDate=$_GET["todate"]; 
        //ADMIN rezervaci automaticky schvali hned
         
        
    } 
    global $interval;
    //po kliknuti na odeslat
    if(isset($_POST["submit"])){
        $name=htmlspecialchars($_POST["name"]);
        $email=htmlspecialchars($_POST["email"]);
        $note= htmlspecialchars($_POST["note"]);
        $fromDate=htmlspecialchars($_POST["fromDate"]);
        $toDate=htmlspecialchars($_POST["toDate"]);        
        $phone=htmlspecialchars($_POST["phone"]);
        $address=htmlspecialchars($_POST["address"]);
        $city=htmlspecialchars($_POST["city"]);
         

         
            $nelzeRezervovat=FALSE;
            //zjistim pocet dni pro rezervaci
            $datetime1 = new DateTime($fromDate);
            $datetime2 = new DateTime($toDate);
           
            $interval = $datetime1->diff($datetime2);
            
            
            $interval= $interval->format('%R%a');//R znamenko
            //jestlize jsou datumy od do v minusu
            if($interval>=0){
                $dateArray=array();
                //prvni den rezervace
                $dateArray[0]=$fromDate;
                $msg="<div class='alert alert-danger'>Rezervaci nelze v daném termínu provést</div>";

                //overim datum pro rezervaci zda jsou volne
                $mysqli=connectDB();                
                
                $stmt=$mysqli->prepare("select * from bookings");
                $bookings=array();
                if($stmt->execute()){
                    $result=$stmt->get_result();
                    if($result->num_rows>0){
                        while($row=$result->fetch_assoc()){
                            $bookings[]=$row["date"];
                            
                        }
                        
                        $stmt->close();
                    }
                }
                
                
                //nactu vsechny datumy k rezervaci
                for($i=0;$i<$interval;$i++){
                    $dateArray[$i+1]=  $datetime1->modify('+1 day')->format('Y-m-d');
                }
                
                //kontrola zda neni datum pro rezervaci v DB
                foreach($dateArray as $singleDate){
                    if(in_array($singleDate, $bookings)){
                        $nelzeRezervovat=TRUE;
                    }
                }
                
                if($nelzeRezervovat){
                    $msg=  "<div class='alert alert-danger'>Nelze rezervovat v termínu od $fromDate do $toDate</div>";
                }else{
                    //ulozim do DB
                    $mysqli=connectDB();
                    $stmt=$mysqli->prepare("INSERT INTO bookings(name,email,date,note,waiting,address,city,phone) values(?,?,?,?,?,?,?,?)");
                    foreach($dateArray as $singleDate){
                        $stmt->bind_param("ssssssss", $name, $email,$singleDate,$note,$wait,$address,$city,$phone);
                        $stmt->execute();
                    }
                    
                    $msg=$interval;//"<div class='alert alert-success'>Rezervace byla úspěšně odeslána</div>";
                    
                    $stmt->close();
                    $mysqli->close();
                    //odeslani emailu a potvrzeni
                    sendEmail($name,$email,$note,$fromDate,$toDate,$phone,$address,$city);
                }   
            }else{
                $msg=  "<div class='alert alert-danger'>Špatně vložené datum od-do</div>";
            }
            
       
        
     
    } 
      
?>
<!doctype html>
<html lang="cs">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rezervace</title>
	<link rel="icon" type="image/x-icon" href="/img/favicon.ico">
 
    <link rel="stylesheet" href="css/style.css">
  </head>
 
    <body>
         <div class="container">    	 
            
         <div class="col-md-6 offset-md-3">
      
   
                    <form action="" method="post" >
        				<h1 class="text-centre">Rezervační fomulář</h1>
    			      <?php                             			    
                           //potvrzeni o odeslani emailu
                            if(isset($_GET['success'])){
                                if($_GET['success']==1){
                                    echo  "<div class='alert alert-success'>Rezervace byla úspěšně odeslána</div>"; 
                                    
                                }else{
                                    echo  "<div class='alert alert-success'>Chyba při odeslání. Zkuste to znovu.</div>";
                                }
                            } 
                           echo (isset($msg)?$msg:"") ;      			    
        			    ?>	
        			          				    
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Od</span>
                                <input 
                                type="date" 
                                class="form-control" 
                                name="fromDate" 
                                aria-label="Sizing example input" 
                                aria-describedby="inputGroup-sizing-lg"
                                required <?php echo "value='$fromDate'"?>>
        				</div>  
        				<div class='input-group input-group-lg'>
                                <span class='input-group-text'>Do</span>
        					<input type="date" 
                            class="form-control" 
                            name="toDate" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required <?php echo "value='$toDate'"?>>
        				</div>  
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Jméno a příjmení</span>
        					<input type="text"  
                            class="form-control" 
                            name="name" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required <?php echo "value='$name'"?>>
        				</div>    
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Ulice a č.p.</span>
        					<input type="text" 
                            class="form-control" 
                            name="address"  
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required <?php echo "value='$address'"?>>
        				</div>
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Město</span>
        					<input type="text" 
                            class="form-control" 
                            name="city" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required <?php echo "value='$city'"?>>
        				</div> 			
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Email</span>
        					<input type="email" 
                            class="form-control" 
                            name="email" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required <?php echo "value='$email'"?>>
        				</div>
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Telefon</span>
        					<input type="text" 
                            class="form-control" 
                            name="phone" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required <?php echo "value='$phone'"?>>
        				</div>        				   				
                        <div class='input-group input-group-lg'>
                                <span class='input-group-text'>Účel pronájmu</span>
        					<textarea  class="form-control"  
                            name="note" rows="4" cols="50" 
                            aria-label="Sizing example input" 
                            aria-describedby="inputGroup-sizing-lg"
                            required></textarea <?php echo "value='$note'"?>>
        				</div>   
                        <div class="d-grid gap-2"> 	
                        <p>Odesláním formuláře souhlasíte, aby provozovatel těchto stránek Obec&nbsp;Malenovice zpracovávala Vaše vypsané osobní údaje dle <a href="https://www.malenovice.eu/urad/povinne-informace/?ftresult=GDPR">GDPR</a> </p>
        				<button class="btn btn-primary btn-lg btn-block" 
                            type="submit" 
                            name="submit">---- Odeslat ----
                        </button>
        				<a class="btn btn-info btn-lg btn-block" href="index.php">Zpět do kalendáře</a>
                        </div>
                    </form>    			
                    </div>    
              
            
        </div> 
    </body>
</html>
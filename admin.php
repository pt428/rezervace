<?php
session_start();

if($_SESSION["admin"]){
    require "assets/db.php";
    require "assets/ceskeMesice.php";     
    require "assets/buildCalendar.php";
       //po kliknuti na tlacitko schvalit
    if(isset($_GET["idEdit"])){ 
        $id=$_GET["idEdit"];
        $query="SELECT * FROM bookings";
        $query2="UPDATE bookings SET waiting=1 WHERE id= $id";
        connectDBSend2Queries($query,$query2);    
       
    }

    //po kliknuti na tlacitko smazat rezervaci
    if(isset($_GET["deleteID"])){   
            $id=$_GET["deleteID"];      
            $query="DELETE FROM bookings WHERE id=$id";
            connectDBSendQuery($query);
        
    } 
}else{
    echo "<div style='text-align: center; margin-top:100px'><h1 style='font-size:100px'>Neutorizovaný přístup!!!</h1></div>";
    die("");
}
  
    ?>
     
<html lang="cs" >
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Rezervační systém</title>
        <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
        <script src="script.js"></script>
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/x-icon" href="img/favicon.ico">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head> 
    <body>
        <div class="container">
         
            <header>
               
               <div class="row">
                   <div class="col">
                        <img class="icon" src='img/favicon.ico' >
                    </div>
                    <div class="col-10 text-center">
                        <h1>ADMINISTRÁTOR</h1>
                    </div>
                    <div class="col">
                        
                    </div>
               </div>
            </header>
            
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
        			<?php 
        			 $dateComponents=getdate();//altualni datum    		    
        		    if(isset($_GET['month']) && isset($_GET['year'])){
        		        $month=$_GET['month'];//kliknuto na dalsi/predchozi mesic
        		        $year=$_GET['year'];    		        
        		    }else{    		        
        		        $month=$dateComponents["mon"];//prvni nacteni stranky
        		        $year=$dateComponents["year"];
        		    }    		    
        		    echo build_calendar($month, $year,1);//vytvor kalendar   
        			?>
              
        		</div>	
        	</div>
        </div> 
        <div>
         
        </div>
    
    </body>
</html>
<?php 
    require "assets/db.php";
    require "assets/ceskeMesice.php";     
    require "assets/buildCalendar.php";
    session_start();
    $_SESSION["admin"]=false;
?>
<html lang="cs" >
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Rezervační systém</title>
 
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/x-icon" href="img/favicon.ico">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    </head>
 
    <body>

    <div class="container ">
        <header>
               
            <div class="row">
                <div class="col">
                        <a href="https://www.malenovice.eu/"><img class="icon"  src='img/favicon.ico' ></a> 
                </div>
                <div class="col-md-auto text-center">
                        <h1>Rezervační systém Obecního domu v Malenovicích</h1>
                </div>
                <div class="col">
                        
                </div>
            </div>
       </header>
              <button class="btn" data-bs-toggle="modal" data-bs-target="#modal-edit">yxvyxvy</button>

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
                                $dateComponents=getdate();        		    
                                if(isset($_GET['month']) && isset($_GET['year'])){
                                    $month=$_GET['month'];
                                    $year=$_GET['year'];        		        
                                }else{        		        
                                    $month=$dateComponents["mon"];
                                    $year=$dateComponents["year"];
                                }        		    
                                echo build_calendar($month, $year);
                        ?>        		
                    </div>	
                </div>
                

    </div>   


         
 
 
    </body>
</html>
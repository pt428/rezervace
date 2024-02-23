<?php
function sendEmail($name,$email,$note,$fromDate,$toDate,$phone,$address,$city){
    session_start();
    
    // Kontroluje data popř. přesměruje na chybovou adresu
    if (empty($name) OR empty($note) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location:https://obecnidum.unas.cz/book.php?success=-1&date=0");
        exit;
    }

    // Nastavte si email, nakterý chcete, aby se vyplněný formulář odeslal - jakýkoli váš email
    $recipient = "pt75@seznam.cz";
    // $recipient = "podatelna@malenovice.eu";

    // Nastavte předmět odeslaného emailu
    $subject = "Rezervace Obecního domu od: $name";
    $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    // Obsah emailu, který se vám odešle
     
    $email_content = "Jméno: $name\n";
    $email_content .= "Adresa: $address\n";
    $email_content .= "Město: $city\n";
    $email_content .= "Telefon: $phone\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Od:\n$fromDate\n";
    $email_content .= "Do:\n$toDate\n";
    $email_content .= "Zpráva:\n$note\n";
    // Emailová hlavička
    
    $email_headers = "From: $name <$email>";
    $email_headers = utf8_decode($email_headers);
  //mail($recipient,utf8_decode($subject),utf8_decode($email_content),utf8_decode($email_headers));
    // Odeslání emailu - dáme vše dohromady
    mail($recipient, $subject, $email_content, $email_headers);
    if($_SESSION["admin"]){
// Přesměrování na stránku, pokud vše proběhlo v pořádku
header("Location:https://obecnidum.unas.cz/admin.php?success=1&date=0");
    }else{

    // Přesměrování na stránku, pokud vše proběhlo v pořádku
    header("Location:https://obecnidum.unas.cz/index.php?success=1&date=0");
    }
}


?>
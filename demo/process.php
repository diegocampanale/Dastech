<?php
    if (isset($_POST['scarica'])) {
        if(!isset($_POST['selected'])){
            $_POST['selected'] = array();
            echo "Nessun elemento selezionato";
        }
        $selectedItems = $_POST['selected'];

        // $selectedItems conterrà un array dei valori delle checkbox selezionate
        // Puoi eseguire le azioni desiderate con i valori selezionati
        foreach ($selectedItems as $item) {
            echo "Elemento selezionato: " . $item . "<br>";
        }
    }

    if(isset($_POST['btnEdit'.$id])){
        echo 'ciao btnedit2';

        // Connessione al database
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "Magazzino_Azienda";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }


        $conn->close();
        
    }

?>
<?php
session_start();



if (isset($_POST['submitNewFornitore'])) {
    
    $conn = new mysqli('localhost', 'root', 'root', 'Magazzino_Azienda');
        if ( mysqli_connect_errno() ) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

    function sanitizeString($value, $mysqli = null) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    
        if ($mysqli !== null) {
            $value = $mysqli->real_escape_string($value);
        }
    
        return $value;
    }

    $errors = array();

    $id_fornitore = $_POST['id_Fornitore'];
    if(empty($_POST['id_Fornitore'])){
        $id_fornitore = 'ciao';
    }
    $nome = sanitizeString($_POST['nome'], $conn);
    $indirizzo = sanitizeString($_POST['indirizzo'], $conn);
    $telefono = sanitizeString($_POST['telefono'], $conn);
    $email = $_POST['email'];

    function push_db($nome, $indirizzo, $telefono, $email, $conn){

        $conn = new mysqli('localhost', 'root', 'root', 'Magazzino_Azienda');
        if ( mysqli_connect_errno() ) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }

        $values = array($nome); 
        if (!empty($indirizzo)) {
            $values[] = $indirizzo;
        }
        if (!empty($telefono)) {
            $values[] = $telefono;
        }
        if (!empty($email)) {
            $values[] = $email;
        }

        $sql = "INSERT INTO fornitori (nome";
        if (!empty($indirizzo)) {
            $sql .= ", indirizzo";
        }
        if (!empty($telefono)) {
            $sql .= ", telefono";
        }
        if (!empty($email)) {
            $sql .= ", email";
        }
        $sql .= ") VALUES ";

        $sql .= "(" . implode(", ", array_fill(0, count($values), "?")) . ")";

        // Preparazione dello statement
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            echo "Errore nella preparazione dello statement: " . $conn->error;
            exit();
        }

        // Bind dei parametri dinamicamente
        $paramTypes = str_repeat("s", count($values)); // Tipo di dato "s" per stringa
        $stmt->bind_param($paramTypes, ...$values);

        // Esecuzione dello statement
        if ($stmt->execute()) {
            echo "Dati inseriti correttamente nella tabella fornitori";
        } else {
            echo "Errore durante l'inserimento dei dati nella tabella fornitori: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }



    if (strlen($nome) > 255) {
        $errors['nome_lungo'] = 'La lunghezza del nome supera il limite consentito';
    }
    if (strlen($nome) < 1) {
        $errors['nome_corto'] = 'È obbligatorio inserire il nome';
    }

    if (isset($indirizzo) && strlen($indirizzo) > 255) {
        $errors['indirizzo'] = 'La lunghezza dell\'indirizzo supera il limite consentito';
    }

    if (isset($telefono) && strlen($telefono) > 10) {
        $errors['telefono'] = 'La lunghezza del telefono supera il limite consentito';
    }
    
    if (empty($email)) {
        if(empty($errors)){
            push_db($nome, $indirizzo, $telefono, $email, $conn);
            $_SESSION['fornitoreOK'] = array("nome" => $nome, "id" => $id_fornitore);

            header('Location: index.php?openModalArticle=true');

            echo '<br><br>OK';
            exit();
        }else{
            print_r($errors);
            $_SESSION['datiFornitore'] = array("nome" => $nome, "indirizzo" => $indirizzo, "telefono" => $telefono, "email" => $email);
            $_SESSION['fornitoreErrors'] = $errors;

            header('Location: index.php?openModalArticle=true&error=true');
            exit();
        }
        
    } else {
        $email = sanitizeString($email);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email non valida';
            $_SESSION['fornitoreErrors'] = $errors;
            $_SESSION['datiFornitore'] = array("nome" => $nome, "indirizzo" => $indirizzo, "telefono" => $telefono, "email" => $email);
            header('Location: index.php?openModalArticle=true&error=true');
            exit();
        }else{
            push_db($nome, $indirizzo, $telefono, $email, $conn);
            $_SESSION['fornitoreOK'] = array("nome" => $nome, "id" => $id_fornitore);
            header('Location: index.php?openModalArticle=true');

            echo '<br><br>OK';
            exit();
        }
    }
}

?>

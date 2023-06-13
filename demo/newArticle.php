<style>
    table {
        border-collapse: collapse; /* Combinare i bordi delle celle adiacenti */
        width: 100%;
    }
    
    th, td {
        padding: 8px; /* Aggiungere spazio interno alle celle */
        border: 1px solid black; /* Applicare un bordo alle celle */
    }
    
    th {
        background-color: #f2f2f2; /* Aggiungere un colore di sfondo alle celle dell'intestazione */
    }
</style>

<?php
    session_start();

    $nFile = $_SESSION['nFile'];
    $nScaffali = $_SESSION['nScaffali'];
    $nPiani = $_SESSION['nPiani'];

    $conn = new mysqli('localhost', 'root', 'root', 'Magazzino_Azienda');
        if ( mysqli_connect_errno() ) {
            exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        }
    
    function sanitizeString($string) {
        $input = trim($string);
        $input = stripslashes($input);
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
    function sanitizeInt($value) {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }
    function sanitizeFloat($value) {
        if (is_int($value)) {
            return floatval(sanitizeInt($value));
        } else {
            $sanitizedValue = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            return floatval($sanitizedValue);
        }
    }
    function sanitizeTextarea($input) {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = sanitizeTextarea($value);
            }
        } else {
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        }
        return $input;
    }
    
    function sanitizeImage($imageFile) {
        // Verifica se è stato caricato un file
        if (!isset($imageFile['tmp_name']) || !is_uploaded_file($imageFile['tmp_name'])) {
            return false;
        }
    
        // Verifica il tipo di file (solo immagini consentite)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($imageFile['type'], $allowedTypes)) {
            return false;
        }
    
        // Verifica la dimensione del file (max 2MB)
        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($imageFile['size'] > $maxFileSize) {
            return false;
        }
    
        // Leggi il contenuto dell'immagine come stringa
        $imageData = file_get_contents($imageFile['tmp_name']);
    
        // Restituisci l'immagine come stringa in formato LONGBLOB
        return $imageData;
    }
    
    if (isset($_POST['submitNewArticle'])) {

        $nome = sanitizeString($_POST['nome']);
        $descrizione = sanitizeTextarea($_POST['descrizione']);
        $quantita = sanitizeInt($_POST['quantita']);
        $fila = sanitizeInt($_POST['fila']);
        $scaffale = sanitizeString($_POST['scaffale']);
        $piano = strtoupper(sanitizeInt($_POST['piano']));
        $prezzo = (float)sanitizeFloat($_POST['prezzo']);
        $fornitore = sanitizeInt($_POST['fornitore']);
        $codiceEAN = sanitizeString($_POST['codice_EAN']);
        $categoria = sanitizeString($_POST['categoria']);
        $nuovaCategoria = '';
        if(empty($categoria)){
            $nuovaCategoria = sanitizeString($_POST['nuovaCategoria']);
        }
        $note = sanitizeTextarea($_POST['note']);
        $immagine = sanitizeImage($_FILES['immagine']);

        $datiArticoli = array(
            "nome" => $nome,
            "descrizione" => $descrizione,
            "quantita" => $quantita,
            "fila" => $fila,
            "scaffale" => $scaffale,
            "piano" => $piano,
            "prezzo" => $prezzo,
            "fornitore" => $fornitore,
            "codiceEAN" => $codiceEAN,
            "categoria" => $categoria,
            "nuovaCategoria" => $nuovaCategoria,
            "immagine" => $immagine,
            "note" => $note
        );

        $errors = array();

        // validazioni input

        if(empty($nome)){
            $errors['not_nome'] = 'È obbligatorio inserire il nome dell\'articolo';
        }
        if (strlen($nome) > 255) {
            $errors['nome_lungo'] = 'La lunghezza del nome supera il limite consentito';
        }
        if(strlen($quantita)>11){
            $errors['quantita_lunga'] = 'La lunghezza della quantita supera il limite consentito';
        }
        if($quantita<0){
            $errors['quantita_corta'] = 'La quantità non può essere minore di 0';
        }
        if(($fila<1 || $fila > $nFile) && !empty($fila)){
            $errors['fila'] = 'La fila deve essere compresa tra 1 e '.$nFile;
        }
        if(empty($fila)){
            $fila = null;
        }
        if((!ctype_alpha($scaffale) && !ord($scaffale) >= ord('A') && !ord($scaffale) <= ord('A') + $nScaffali - 1) && !empty($scaffale)){
            $errors['scaffale'] = 'Lo scaffale deve essere compreso tra A e '.chr(ord('A') + $nScaffali - 1);
        }
        if(($piano<1 || $piano > $nPiani) && !empty($piano)){
            $errors['piano'] = 'Il piano deve essere compreso tra 1 e '.$nPiani;
        }
        if(empty($piano)){
            $piano = null;
        }
        if ((!empty($fila) || !empty($scaffale) || !empty($piano)) && (empty($fila) || empty($scaffale) || empty($piano))) {
            $errors['settore'] = 'Per favore inserisci tutti i campi';
        }
        
        if(!empty($prezzo)){
            $cifreInteriLimite = 10;
            $cifreDecimaliLimite = 2;
            $numeroStringa = (string)$prezzo;
            $cifreInteri = floor($prezzo);

            // Verifica se è presente una virgola nel valore del prezzo
            if (strpos($numeroStringa, '.') !== false) {
                $cifreDecimali = substr($numeroStringa, strrpos($numeroStringa, '.') + 1);
                if (strlen($cifreDecimali) > $cifreDecimaliLimite) {
                    $errors['prezzo_dec'] = "Il numero ha superato il limite massimo di cifre decimali consentite ->" . $prezzo;
                }
            }

            if (strlen((string)$cifreInteri) > $cifreInteriLimite) {
                $errors['prezzo_int'] = 'Il prezzo ha superato il limite massimo di cifre intere consentite ->' . $prezzo;
            }
        }
        $sql = "SELECT MAX(id) as max_id FROM fornitori";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $maxIdFornitori = $row['max_id'];
        if(!empty($fornitore) && ($fornitore<1 || $fornitore>$maxIdFornitori)){
            $errors['fornitore'] = 'Il fornitore inserito non esiste';
        }
        if(empty($fornitore)){
            $errors['not_fornitore'] = 'Per favore inserisci il fornitore';
        }
        if(!empty($codiceEAN) && strlen($codiceEAN) > 13){
            $errors['codiceEAN'] = 'Il codice EAN ha superato il limite massimo di cifre intere consentite.';
        }
        if(!empty($_FILE['immagine']) && $immagine === false){
            $errors['immagine'] = 'Errore nel caricamento dell\'immagine.';
        }
        if (empty($categoria) && empty($nuovaCategoria)) {
            $errors['categoria'] = 'Seleziona una categoria o inserisci una nuova categoria.';
        } elseif (!empty($categoria) && !empty($nuovaCategoria)) {
            $errors['categoria'] = 'Puoi selezionare solo una categoria o inserire una nuova categoria, non entrambe.';
        }
        if(!empty($errors)){
            $_SESSION['errorsArticolo'] = $errors;
            $_SESSION['datiArticoli'] = $datiArticoli;
            $_SESSION['categoriaOK'] = $categoria;
            header('Location: index.php?openModalArticle=true');
            print_r($errors);
            //exit();
        }else{
            
            if (empty($categoria)) {
                $categoria = $nuovaCategoria;
            }
            push_db($nome, $fornitore, $quantita, $prezzo, $categoria, $conn, $descrizione, $fila, $scaffale, $piano, $codiceEAN, $note, $immagine);
            // Visualizza i dati in una tabella
            echo '<h2>Dati inseriti:</h2>';
            echo '<table>';
            echo '<tr><th>ID</th><th>Nome</th><th>Descrizione</th><th>Quantità</th><th>Fila</th><th>Scaffale</th><th>Piano</th><th>Prezzo</th><th>Fornitore</th><th>Codice EAN</th><th>Categoria</th><th>Note</th><th>Immagine</th></tr>';
            echo '<tr>';
            echo '<td>51</td>';
            echo '<td>'.$nome.'</td>';
            echo '<td>'.$descrizione.'</td>';
            echo '<td>'.$quantita.'</td>';
            echo '<td>'.$fila.'</td>';
            echo '<td>'.$scaffale.'</td>';
            echo '<td>'.$piano.'</td>';
            echo '<td>'.$prezzo.'</td>';
            echo '<td>'.$fornitore.'</td>';
            echo '<td>'.$codiceEAN.'</td>';
            echo '<td>'.$categoria.'</td>';
            echo '<td>'.$note.'</td>';
            echo '<td>'.$immagine.'</td>';
            echo '</tr>';
            echo '</table>';

            echo '<br><br>OK';

            header('Location: index.php');

            $conn->close();
            
        }
    }

    if (isset($_POST['annulla'])){
        $datiArticoli = array(
            "nome" => "",
            "descrizione" => "",
            "quantita" => 0,
            "fila" => "",
            "scaffale" => "",
            "piano" => "",
            "prezzo" => 0.00,
            "fornitore" => "",
            "codiceEAN" => "",
            "categoria" => "",
            "nuovaCategoria" => "",
            "immagine" => "",
            "note" => ""
        );

        $_SESSION['errorsArticolo'] = array();
        $_SESSION['datiArticoli'] = $datiArticoli;
        $_SESSION['fornitoreOK'] = array();
        $_SESSION['categoriaOK'] = "";

        print_r($_SESSION['errorsArticolo']);
        print_r($_SESSION['datiArticoli']);

        header('Location: index.php');
    }

    function push_db($nome, $fornitore, $quantita, $prezzo, $categoria, $conn, $descrizione = '', $fila = '', $scaffale = '', $piano = '', $codiceEAN = '', $note = '', $immagine = '') {
        // Dichiarazione preparata (prepared statement) per l'inserimento
        $sql = "INSERT INTO articoli (nome, descrizione, quantita, fila, sezione, piano, prezzo, fornitore_id, codice_EAN, categoria, note, immagine)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = $conn->prepare($sql);
        $_SESSION['DBerror'] = "";
        if (!$stmt) {
            $_SESSION['DBerror'] = "Errore nella preparazione dello statement: " . $conn->error;
            echo "Errore nella preparazione dello statement: " . $conn->error;
            exit;
        }
        $stmt->bind_param("ssisssdissss", $nome, $descrizione, $quantita, $fila, $scaffale, $piano, $prezzo, $fornitore, $codiceEAN, $categoria, $note, $immagine);
    
        $_SESSION['DBsuccess'] = "";
        $_SESSION['DBerror'] = "";

        if ($stmt->execute()) {
            $_SESSION['DBsuccess'] = "Dati inseriti correttamente";
            echo "Dati inseriti correttamente";
        } else {
            $_SESSION['DBerror'] = "Errore durante l'inserimento dei dati: " . $stmt->error;
            echo "Errore durante l'inserimento dei dati: " . $stmt->error;
        }
    
        // Chiusura dello statement e della connessione
        $stmt->close();
        $conn->close();
    }
    


?>




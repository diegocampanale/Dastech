<?php
// Connessione al database
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'Magazzino_Azienda';

$mysqli = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
  exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if (isset($_POST['articleId'])) {
  $articleId = $_POST['articleId'];
  
  // Esegui una query per recuperare i dati dell'articolo dal database
  $query = "SELECT * FROM articoli WHERE ID = $articleId";
  $result = $conn->query($query);
  
  if ($result && $result->num_rows > 0) {
    $article = $result->fetch_assoc();
    echo json_encode($article);
  } else {
    echo json_encode(array('error' => 'Articolo non trovato'));
  }
} else {
  echo json_encode(array('error' => 'ID articolo non fornito'));
}

// Chiudi la connessione al database e altre operazioni finali
$mysqli->close();
?>

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

// Esegui la query per ottenere i dati dei clienti
$query = "SELECT * FROM articoli";
$result = $mysqli->query($query);

// Costruisci l'array dei dati dei clienti
$data = array();
while ($row = $result->fetch_assoc()) {
    //$row['check'] = "<input type='checkbox' name='selected[]' value=".$row['ID'].">";
    $row['settore'] = $row['fila'].$row['sezione'].$row['piano'];
    $row['edit'] = "<button class='btn btn-secondary btn-sm edit-btn' type='submit' name='btnEdit".$row['ID']."' id='btnEdit".$row['ID']."' data-toggle='tooltip' title='Modifica articolo' data-bs-toggle='modal' data-bs-target='#editModal".$row["ID"]."'><i class='fas fa-pencil-alt'></i></button>";
    $row['view'] = "<button class='btn btn-primary btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Info articolo'>&nbsp<i class='fa-solid fa-info'></i>&nbsp</button>";
    $row['delete'] = "<button class='btn btn-danger btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Elimina articolo' ><i class='fa-solid fa-trash'></i></button>";
    $row['check'] = "<input type='checkbox' name='selected[]' class='form-check-input ' id='check".$row['ID']."' value='".$row['ID']."'>";
    
    
    $data[] = $row;
    // <button class='btn btn-secondary btn-sm' type='button' id='btnEdit' data-bs-toggle='tooltip' data-bs-placement='top' title='Modifica'><i class='fas fa-pencil-alt'></i></button>
}

print_r($row);
// Restituisci i dati dei clienti come JSON
header('Content-Type: application/json');
echo json_encode($data);

$mysqli->close();
?>
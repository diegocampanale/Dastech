<?php
$id = $_SESSION['editRow']['id'];
$nome = $_SESSION['editRow']['nome'];
$descrizione = $_SESSION['editRow']['descrizione'];
$quantita = $_SESSION['editRow']['quantita'];
$fila = $_SESSION['editRow']['fila'];
$scaffale = $_SESSION['editRow']['scaffale'];
$piano = $_SESSION['editRow']['piano'];
$prezzo = $_SESSION['editRow']['prezzo'];
$fornitoreId = $_SESSION['editRow']['fornitoreId'];
$codiceEAN = $_SESSION['editRow']['codiceEAN'];
$categoria = $_SESSION['editRow']['categoria'];
$note = $_SESSION['editRow']['note'];


echo '
<div class="modal fade" id="editModal'.$id.'"  tabindex="-1" aria-labelledby="editModal'.$id.'label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModal'.$id.'label">Modifica articolo #'.$id.'</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <div class="modal-body">
            <!-- MODAL SENZA IMMAGINE -->
            <!-- ID -->
            <div class="mb-3">
                <div class="form-floating">
                    <input type="number" class="form-control" id="editId" name="id" value="'.$id.'" aria-label="id" disabled>
                    <label for="id">ID</label>
                </div>
            </div>

            <!-- Nome -->
            <div class="mb-3">
                <div class="form-floating is-invalid">
                    <input class="form-control" type="text" placeholder="Nome" aria-label="Nome" id="editNome" name="nome" aria-describedby="nomeArticoloFeedback" value="'.$nome.'" > 
                    <label for="nome">Nome</label>
                </div>
            </div>

            <!-- Descrizione -->
            <div class="form-floating mb-3">
                <textarea class="form-control" rows="5" placeholder="Descrizione" aria-label="Descrizione" id="editDescrizione" name="descrizione">'.$descrizione.'</textarea>
                <label for="descrizione">Descrizione</label>
            </div>

            <!-- Quantita -->
            <div class="mb-3">
                <div class="form-floating is-invalid">
                    <input class="form-control" type="number" placeholder="Quantità" aria-label="Quantita" id="editQuantita" name="quantita" value="'.$quantita.'">
                    <label for="quantita">Quantità</label>
                </div>
            </div>

            <!-- Settore -->
            <div class="input-group mb-3">
                    <div class="form-floating is-invalid">
                        <select class="form-select " aria-label="Fila" id="editFila" name="fila" >
                            <option value="" selected>Seleziona fila</option>';
                            
                                for ($i = 1; $i <= $_SESSION['nFile']; $i++) {
                                    $selected = '';
                                    if ($i == $fila) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                }
                                echo '
                        </select>
                        <label for="fila" class="form-label">Fila</label>
                    </div>

                    <div class="form-floating is-invalid">
                        <select class="form-select" aria-label="Scaffale" id="editScaffale" name="scaffale" >
                            <option value="" selected>Seleziona scaffale</option>';
                            
                                $startChar = ord('A');
                                $endChar = $startChar + $_SESSION['nScaffali'] - 1;

                                for ($i = $startChar; $i <= $endChar; $i++) {
                                    $letter = chr($i);
                                    $selected = '';
                                    if ($i == $scaffale) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . $letter . '" ' . $selected . '>' . $letter . '</option>';
                                }
                           
                        echo '
                        </select>
                        <label for="categoria" class="form-label">Scaffale</label>
                    </div>

                    <div class="form-floating is-invalid">
                        <select class="form-select " aria-label="Piano" id="editPiano" name="piano">
                            <option value="" selected>Seleziona piano</option>';
                            
                                for ($i = 1; $i <= $_SESSION['nPiani']; $i++) {
                                    $selected = '';
                                    if ($i == $piano) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                }
                         echo '   
                        </select>
                        <label for="piano" class="form-label">Piano</label>
                    </div>
                </div>

            <!-- Prezzo -->
            <div class="input-group mb-3">
                <span class="input-group-text"> &euro; </span>
                <div class="form-floating is-invalid">
                    <input type="number" class="form-control  " step="0.01" min="0" id="editPrezzo" name="prezzo" placeholder="0.00" value="'.$prezzo.'">
                    <label for="price">Prezzo:</label>
                </div>
            </div>

            <!-- Fornitore -->
            <div class="form-floating mb-3">
                <select class="form-select" aria-label="Fornitore" name="fornitore" id="editFornitore">
                    <option value="">Seleziona fornitore</option>';
                    
                        $sql = 'SELECT id, nome FROM fornitori ORDER BY id DESC';
                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            $selected = '';
                            if($row['id'] == $fornitoreId){
                                $selected = 'selected';
                            }
                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['nome'] . '</option>';
                        }
                        echo '
                </select>
                <label for="fornitore" class="form-label">Fornitore</label>
            </div>

            <!-- Codice EAN -->
            <div class="mb-3">
                <div class="form-floating is-invalid">
                    <input class="form-control " type="text" placeholder="Codice EAN" aria-label="Codice EAN" id="editCodice_EAN" name="codice_EAN" maxlength="13" value="'.$codiceEAN.'" >
                    <label for="codice_EAN">Codice EAN</label>
                </div>
            </div>

            <!-- Immagine -->
            <div class="input-group mb-3">
                <label class="input-group-text" for="immagine">Cambia immagine</label>
                <input type="file" name="immagine" id="editImmagine" class="form-control">
            </div>

            <!-- Categoria -->
            <div class="form-floating mb-3">
                <select class="form-select" aria-label="Categoria" id="editCategoria" name="categoria">
                    <option value="" selected>Seleziona Categoria</option>';

                        $sql = 'SELECT categoria FROM articoli GROUP BY categoria ORDER BY categoria';
                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            $selected = '';
                            if($row['categoria'] == $categoria){
                                $selected = 'selected';
                            }
                            echo '<option value="' . $row['categoria'] . '" ' . $selected . '>' . $row['categoria'] . '</option>';
                        }
                        echo '
                </select>
                <label for="categoria" class="form-label">Categoria</label>
            </div>

            <!-- Note -->
            <div class="form-floating mb-3">
                <textarea class="form-control form-floating" id="editNote" rows="5" placeholder="Note" name="note" aria-label="Note">'.$note.'</textarea>
                <label for="note" class="form-label">Note</label>
            </div>
        </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <button type="submit" id="submitBtnEdit" name="submitEditArticle"class="btn btn-primary">Salva modifiche</button>
            </div>
        </div>
    </div>
</div>
';

?>


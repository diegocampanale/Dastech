

<div class="modal fade" id="editModal1" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModal1label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModal1label">Modifica articolo #1</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        <div class="modal-body">
            <!-- MODAL CON IMMAGINE -->
            <div class="row g-3 mb-3">
                <div class="col-md ">
                    <!-- immagine -->
                    <div style="width: 100%; height: 280px; overflow: hidden;" >
                        <img src="uploads/foto.png" class="rounded" alt="..." style="object-fit: cover; width: 100%; height: 100%;">  
                    </div>
                </div>
                <div class="col">
                    <!-- ID -->
                    <div class="mb-3">
                        <div class="form-floating">
                            <input type="number" class="form-control" id="id" name="id" value="1" aria-label="id" disabled>
                            <label for="id">ID</label>
                        </div>
                    </div>

                    <!-- Nome -->
                    <div class="mb-3">
                        <div class="form-floating is-invalid">
                            <input class="form-control" type="text" placeholder="Nome" aria-label="Nome" id="nome" name="nome" aria-describedby="nomeArticoloFeedback" value="Lampada LED" > 
                            <label for="nome">Nome</label>
                        </div>
                    </div>

                    <!-- Descrizione -->
                    <div class="form-floating mb-3">
                        <textarea class="form-control" id="Descrizione" rows="3" placeholder="Descrizione" aria-label="Descrizione" id="descrizione" name="descrizione">Lampada a LED ad alta efficienza energeticaLampada a LED ad alta efficienza energeticaLampada a LED ad alta efficienza energeticaLampada a LED ad alta efficienza energeticaLampada a LED ad alta efficienza energeticaLampada a LED ad alta efficienza energeticaLampada a LED ad alta efficienza energetica</textarea>
                        <label for="descrizione">Descrizione</label>
                    </div>

                    <!-- Quantita -->
                    <div class="">
                        <div class="form-floating is-invalid">
                            <input class="form-control" type="number" placeholder="Quantità" aria-label="Quantita" id="quantita" name="quantita" value="10">
                            <label for="quantita">Quantità</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settore -->
            <div class="input-group mb-3">
                <div class="form-floating is-invalid">
                    <select class="form-select " aria-label="Fila" id="fila" name="fila" >
                        <option value="" selected>Seleziona fila</option>
                        <?php
                            for ($i = 1; $i <= $_SESSION['nFile']; $i++) {
                                $selected = '';
                                if ($i == 1) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                            }
                        ?>
                    </select>
                    <label for="fila" class="form-label">Fila</label>
                </div>

                <div class="form-floating is-invalid">
                    <select class="form-select" aria-label="Scaffale" id="scaffale" name="scaffale" >
                        <option value="" selected>Seleziona scaffale</option>
                        <?php
                            $startChar = ord('A');
                            $endChar = $startChar + $_SESSION['nScaffali'] - 1;

                            for ($i = $startChar; $i <= $endChar; $i++) {
                                $letter = chr($i);
                                $selected = '';
                                if ($i == ord('A')) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $letter . '" ' . $selected . '>' . $letter . '</option>';
                            }
                        ?>
                    </select>
                    <label for="categoria" class="form-label">Scaffale</label>
                </div>

                <div class="form-floating is-invalid">
                    <select class="form-select " aria-label="Piano" id="piano" name="piano">
                        <option value="" selected>Seleziona piano</option>
                        <?php
                            for ($i = 1; $i <= $_SESSION['nPiani']; $i++) {
                                $selected = '';
                                if ($i == 1) {
                                    $selected = 'selected';
                                }
                                echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                            }
                        ?>
                    </select>
                    <label for="piano" class="form-label">Piano</label><?php if (isset($errorsArticolo['piano'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['fila'].'</div>'; } ?>
                </div>
            </div>

            <!-- Prezzo -->
            <div class="input-group mb-3">
            <span class="input-group-text"> &euro; </span>
            <div class="form-floating is-invalid">
                <input type="number" class="form-control  " step="0.01" min="0" id="prezzo" name="prezzo" placeholder="0.00" value="19.99">
                <label for="price">Prezzo:</label>
            </div>
            </div>

            <!-- Fornitore -->
            <div class="form-floating mb-3">
            <select class="form-select" aria-label="Fornitore" name="fornitore" id="fornitore">
                <option value="">Seleziona fornitore</option>
                <?php
                    $sql = 'SELECT id, nome FROM fornitori ORDER BY id DESC';
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        $selected = '';
                        if($row['id'] == 1){
                            $selected = 'selected';
                        }else{
                            if (isset($_POST['nuovoFornitore']) && $row['id'] == $_SESSION['fornitoreOK']['id']) {
                                $selected = 'selected';
                            }
                        }
                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['nome'] . '</option>';
                    }
                ?>
            </select>
            <label for="fornitore" class="form-label">Fornitore</label>
            </div>

            <!-- Codice EAN -->
            <div class="mb-3">
            <div class="form-floating is-invalid">
                <input class="form-control " type="text" placeholder="Codice EAN" aria-label="Codice EAN" id="codice_EAN" name="codice_EAN" maxlength="13" value="1234567890123" >
                <label for="codice_EAN">Codice EAN</label>
            </div>
            </div>

            <!-- Immagine -->
            <div class="input-group mb-3">
            <label class="input-group-text" for="immagine">Cambia immagine</label>
            <input type="file" name="immagine" id="immagine" class="form-control">
            </div>

            <!-- Categoria -->
            <div class="form-floating mb-3">
            <select class="form-select" aria-label="Categoria" id="categoria" name="categoria">
                <option value="" selected>Seleziona Categoria</option>

                <?php
                    $sql = 'SELECT categoria FROM articoli GROUP BY categoria ORDER BY categoria';
                    $result = $conn->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        $selected = '';
                        if($row['categoria'] == 'Illuminazione'){
                            $selected = 'selected';
                        }
                        echo '<option value="' . $row['categoria'] . '" ' . $selected . '>' . $row['categoria'] . '</option>';
                    }
                ?>
            </select>
            <label for="categoria" class="form-label">Categoria</label>
            </div>

            <!-- Note -->
            <div class="form-floating mb-3">
            <textarea class="form-control form-floating" id="Note" rows="5" placeholder="Note" name="note" aria-label="Note"></textarea>
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





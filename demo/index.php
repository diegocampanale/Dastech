<?php
    session_start();
    
    $conn = new mysqli('localhost', 'root', 'root', 'Magazzino_Azienda');
    if ( mysqli_connect_errno() ) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    $sql = "SELECT MAX(id) as max_id FROM articoli";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $lastId = $row['max_id'];
    $nextIdArticoli = $lastId + 1;

    $sql = "SELECT MAX(id) as max_id FROM fornitori";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $lastId = $row['max_id'];
    $nextIDFornitori = $lastId + 1;

    $_SESSION['nFile'] = '8';
    $_SESSION['nScaffali'] = 6;
    $_SESSION['nPiani'] = '5';
    
    if(!isset($_SESSION['fornitoreErrors'])){
        $_SESSION['fornitoreErrors'] = array();
    }


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css" rel="stylesheet"/>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/d90aa46435.js" crossorigin="anonymous"></script>


</head>
<body>

    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>


  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg bg-dark sticky-top navbar-dark p-3 shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="#"><i class="fa-duotone fa-shield-halved" style="margin-right: 5px;"></i> <strong>Dastech Impianti</strong></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class=" collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto ">
          <li class="nav-item">
            <a class="nav-link mx-2 text-uppercase active" aria-current="page" href="#">Articoli</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mx-2 text-uppercase" href="categorie.php">Categorie</a>
          </li>
        </ul>

        <div class="navbar-nav ms-auto">
            <a class="nav-link text-uppercase mx-2" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 20 20">
                    <image xlink:href="profile.svg" width="20" height="20" />
                </svg>
                <span class="name-desktop ">Diego</span>
            </a>
        </div>
      </div>
    </div>
  </nav>
  <!-- END NAVBAR -->

  
  
  <div class="container mt-3">
    <form method="POST" action="process.php">

        <!-- SUB NAVBAR -->
        <div class="row mt-3">
            <div class="col-sm-6">
                <h1 class="text-left">Articoli</h1>
            </div>
            <div class="col-sm-6 text-right">
                <!-- Nav pills -->
                    <ul class="nav justify-content-end">
                    <li class="nav-item">
                        <button class="btn btn-primary" type="submit">Carica</button>
                    </li>
                    <li class="nav-item">
                        <button class="btn btn-danger mx-2" name="scarica" type="submit">Scarica</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newArticleModal" name="btnNuovoArticolo" >Nuovo articolo</button>
                    </li>
                    </ul>
            </div>
        </div>
        <!-- SUB NAVBAR -->

        <!-- DATABASE TABLE -->
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="article_table" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Quantità</th>
                                    <th>Settore</th>
                                    <th>Codice EAN</th>
                                    <th>Categoria</th>
                                    <th>#</th>
                                    <th>#</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- END DATABASE TABLE -->

        <!-- Tooltip for button in the table -->
        <script>
            $(document).ready(function() {
                $("body").tooltip({ selector: '[data-toggle=tooltip]' });
            });
        </script>
        <!-- END Tooltip for button in the table -->
    </form>




        <!-- edit modal -->
        <form action="editArticle.php" method="post" enctype="multipart/form-data" id="formEditArticle">
            <?php

            include 'modal/editConImg.php';
            ?>
        </form>

        <script>
            $(document).on('click', '.edit-btn', function() {
            var button = $(this);
            var articleId = button.attr('id').replace('btnEdit', ''); // Ottieni l'ID dell'articolo
            
            // Esegui una chiamata AJAX al tuo script PHP per recuperare i dati dell'articolo
            $.ajax({
                url: 'fetch_article.php',
                method: 'POST',
                data: { articleId: articleId },
                dataType: 'json',
                success: function(response) {
                // Popola i campi del modal con i dati ricevuti
                $('#editModal' + articleId).find('#id').val(response.id);
                $('#editModal' + articleId).find('#nome').val(response.nome);
                $('#editModal' + articleId).find('#descrizione').val(response.descrizione);
                // ...
                // Popola gli altri campi del modal con i dati ricevuti
                
                // Apri il modal
                $('#editModal' + articleId).modal('show');
                },
                error: function(xhr, status, error) {
                console.log(error);
                }
            });
            });
        </script>
        <!-- END edit modal-->





    <!-- Alert -->
    <?php
        $showFeedbackDBsuccess = isset($_SESSION['DBsuccess']);
        $showFeedbackDBerror = isset($_SESSION['DBerror']);

        if (!empty($_SESSION['DBsuccess'])) {
            echo '<div class="DBfeedback row justify-content-end d-flex" id="DBfeedbackSuccess">';
            echo '<div class="alert alert-success justify-content-end col-5" role="alert">';
            echo $_SESSION['DBsuccess'];
            echo '</div></div>';
            $_SESSION['DBsuccess'] = "";

        }

        if (!empty($_SESSION['DBerror'])) {
            echo '<div class="DBfeedback row justify-content-end d-flex" id="DBfeedbackError">';
            echo '<div class="alert alert-danger justify-content-end col-5" role="alert">';
            echo $_SESSION['DBerror'];
            echo '</div></div>';
            $_SESSION['DBerror']="";
        }
    ?>
    <script>
        // Funzione per nascondere il div dopo 5 secondi
        setTimeout(function() {
            <?php if ($showFeedbackDBsuccess) { 
            echo 'var successDiv = document.getElementById("DBfeedbackSuccess");
            successDiv.classList.add("d-none");';
             } ?>

            <?php if ($showFeedbackDBerror) { 
            echo 'var errorDiv = document.getElementById("DBfeedbackError");
            errorDiv.classList.add("d-none");';
         } ?>
        }, 5000);
    </script>
    <!-- END Alert -->


        


    <!-- DataTables -->
    <script>
        $(document).ready(function() {
            $('#article_table').DataTable({
            ajax: {
                url: 'api/article.php', 
                dataSrc: '' 
            },
            columns: [
                { data: 'check' },
                { data: 'ID' },
                { data: 'nome' },
                { data: 'quantita' },
                { data: 'settore' },
                { data: 'codice_ean' },
                { data: 'categoria' },
                { data: 'view' },
                { data: 'edit' },
                { data: 'delete' },
            ],
            language: {
                processing: "Elaborazione in corso...",
                search: "Cerca:",
                lengthMenu: "Mostra _MENU_ elementi",
                info: "Visualizzazione da _START_ al _END_ su un totale di _TOTAL_ elementi",
                infoEmpty: "Nessun elemento da visualizzare",
                infoFiltered: "",
                infoPostFix: "",
                loadingRecords: "Caricamento in corso...",
                // zeroRecords: "Nessun elemento corrispondente trovato",
                emptyTable: "Nessun dato disponibile nella tabella",
                paginate: {
                first: "Primo",
                previous: "<<",
                next: ">>",
                last: "Ultimo"
                },
                aria: {
                sortAscending: ": attiva per ordinare la colonna in ordine crescente",
                sortDescending: ": attiva per ordinare la colonna in ordine decrescente"
                }
            }
            }); 
    });
    </script>
    <!--END DataTables -->

    <!-- New article modal -->
    <form action="newArticle.php" method="post" enctype="multipart/form-data" id="formNuovoArticolo">
            <div class="modal fade modal-fullscreen-sm-down" id="newArticleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Nuovo Articolo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php

                            if(!isset($_SESSION['errorsArticolo'])){
                                $_SESSION['errorsArticolo'] = array();
                            }
                            $errorsArticolo = $_SESSION['errorsArticolo'];
                            // print_r($errorsArticolo);
                            // print_r($_SESSION['fornitoreOK']);
                        ?>
                        <!-- ID -->
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="id" name="id" value="<?=$nextIdArticoli?>" aria-label="id" disabled>
                                <label for="id">ID</label>
                            </div>
                        </div>

                        <!-- Nome -->
                        <div class="mb-3">
                            <div class="form-floating is-invalid">
                                <input class="form-control <?php if (isset($errorsArticolo['not_nome'])) { echo 'is-invalid'; }else{if (isset($errorsArticolo['nome_lungo'])) { echo 'is-invalid'; }} ?>" type="text" placeholder="Nome" aria-label="Nome" id="nome" name="nome" aria-describedby="nomeArticoloFeedback" <?php if(isset($_SESSION['datiArticoli'])){echo 'value="'.$_SESSION['datiArticoli']['nome'].'"';} ?> > 
                                <label for="nome">Nome</label>
                            </div>
                            <?php if (isset($errorsArticolo['not_nome'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['not_nome'].'</div>'; }else{if (isset($errorsArticolo['nome_lungo'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['nome_lungo'].'</div>'; }} ?>
                        </div>

                        <!-- Descrizione -->
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="Descrizione" rows="3" placeholder="Descrizione" aria-label="Descrizione" id="descrizione" name="descrizione" ><?php if(isset($_SESSION['datiArticoli'])){echo $_SESSION['datiArticoli']['descrizione'];}?></textarea>
                            <label for="descrizione">Descrizione</label>
                        </div>

                        <!-- Quantita -->
                        <div class="mb-3">
                            <div class="form-floating is-invalid">
                                <input class="form-control <?php if (isset($errorsArticolo['quantita_corta'])) { echo 'is-invalid'; }else{if (isset($errorsArticolo['quantita_lunga'])) { echo 'is-invalid'; }} ?>" type="number" placeholder="Quantità" aria-label="Quantita" id="quantita" name="quantita" value="0" <?php if(isset($_SESSION['datiArticoli'])){echo 'value="'.$_SESSION['datiArticoli']['quantita'].'"';} ?> required>
                                <label for="quantita">Quantità</label>
                            </div>
                            <?php if (isset($errorsArticolo['quantita_corta'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['quantita_corta'].'</div>'; }else{if (isset($errorsArticolo['quantita_lunga'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['quantita_lunga'].'</div>'; }} ?>
                        </div>

                        <!-- Settore -->
                        <div class="input-group mb-3 is-invalid">
                            <div class="form-floating is-invalid">
                                <select class="form-select <?php if (isset($errorsArticolo['settore'])) { echo 'is-invalid'; }else{if (isset($errorsArticolo['fila'])) { echo 'is-invalid'; }} ?>" aria-label="Fila" id="fila" name="fila" <?php if(isset($_SESSION['datiArticoli'])){echo 'value="'.$_SESSION['datiArticoli']['fila'].'"';} ?> >
                                    <option value="" selected>Seleziona fila</option>
                                    <?php
                                        for ($i = 1; $i <= $_SESSION['nFile']; $i++) {
                                            $selected = '';
                                            if (isset($_SESSION['datiArticoli']['fila']) && $_SESSION['datiArticoli']['fila'] == $i) {
                                                $selected = 'selected';
                                            }
                                            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                        }
                                    ?>
                                </select>
                                <label for="fila" class="form-label">Fila</label>
                                <?php if (isset($errorsArticolo['fila'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['fila'].'</div>'; } ?>
                            </div>

                            <div class="form-floating is-invalid">
                                <select class="form-select <?php if (isset($errorsArticolo['settore'])) { echo 'is-invalid'; }else{if (isset($errorsArticolo['scaffale'])) { echo 'is-invalid'; }} ?>" aria-label="Scaffale" id="scaffale" name="scaffale" >
                                    <option value="" selected>Seleziona scaffale</option>
                                    <?php
                                        $startChar = ord('A');
                                        $endChar = $startChar + $_SESSION['nScaffali'] - 1;

                                        for ($i = $startChar; $i <= $endChar; $i++) {
                                            $letter = chr($i);
                                            $selected = '';
                                            if (isset($_SESSION['datiArticoli']['scaffale']) && $_SESSION['datiArticoli']['scaffale'] == $letter) {
                                                $selected = 'selected';
                                            }
                                            echo '<option value="' . $letter . '" ' . $selected . '>' . $letter . '</option>';
                                        }
                                    ?>
                                </select>
                                <label for="categoria" class="form-label">Scaffale</label>
                                <?php if (isset($errorsArticolo['scaffale'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['fila'].'</div>'; } ?>
                            </div>

                            <div class="form-floating is-invalid">
                                <select class="form-select <?php if (isset($errorsArticolo['settore'])) { echo 'is-invalid'; }else{if (isset($errorsArticolo['piano'])) { echo 'is-invalid'; }} ?>" aria-label="Piano" id="piano" name="piano">
                                    <option value="" selected>Seleziona piano</option>
                                    <?php
                                        for ($i = 1; $i <= $_SESSION['nPiani']; $i++) {
                                            $selected = '';
                                            if (isset($_SESSION['datiArticoli']['piano']) && $_SESSION['datiArticoli']['piano'] == $i) {
                                                $selected = 'selected';
                                            }
                                            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                                        }
                                    ?>
                                </select>
                                <label for="piano" class="form-label">Piano</label>
                                <?php if (isset($errorsArticolo['piano'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['fila'].'</div>'; } ?>
                            </div>
                            <?php if (isset($errorsArticolo['settore'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['settore'].'</div>'; } ?>
                        </div>

                        <!-- Prezzo -->
                        <div class="input-group mb-3">
                            <span class="input-group-text"> &euro; </span>
                            <div class="form-floating is-invalid">
                                <input type="number" class="form-control  <?php if (isset($errorsArticolo['prezzo_int']) || isset($errorsArticolo['prezzo_dec'])) { echo 'is-invalid'; } ?>" step="0.01" min="0" id="prezzo" name="prezzo" placeholder="0.00" <?php if(isset($_SESSION['datiArticoli'])){echo 'value="'.$_SESSION['datiArticoli']['prezzo'].'"';}else{ echo 'value="0.00"';} ?> required>
                                <label for="price">Prezzo:</label>
                            </div>
                            <?php if (isset($errorsArticolo['prezzo_int'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['prezzo_int'].'</div>'; }else{if (isset($errorsArticolo['prezzo_dec'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['prezzo_dec'].'</div>'; }} ?>
                        </div>

                        <!-- Fornitore -->
                        <div class="row mb-3 g-3 justify-content-between">
                            <div class="col-sm-9">
                                <select class="form-select <?php if (isset($errorsArticolo['fornitore'])) { echo 'is-invalid'; }else{if (isset($errorsArticolo['not_fornitore'])) { echo 'is-invalid'; }} ?>" aria-label="Fornitore" name="fornitore" id="fornitore">
                                    <option value="">Seleziona fornitore</option>
                                    <?php
                                        $sql = 'SELECT id, nome FROM fornitori ORDER BY id DESC';
                                        $result = $conn->query($sql);

                                        while ($row = $result->fetch_assoc()) {
                                            $selected = '';
                                            if ($row['id'] == $_SESSION['fornitoreOK']['id']) {
                                                $selected = 'selected';
                                            }
                                            echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['nome'] . '</option>';
                                        }
                                        
                                    ?>
                                    </select>
                                
                                <?php if (isset($errorsArticolo['fornitore'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['fornitore'].'</div>'; }else{if (isset($errorsArticolo['not_fornitore'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['not_fornitore'].'</div>'; }} ?>
                            </div>

                            <div class="col">
                                <button type="button" name="nuovoFornitore" id="nuovoFornitore" class="btn btn-primary" data-bs-target="#newFornitore" data-bs-toggle="modal">Aggiungi Fornitore</button>
                            </div>
                            
                            <script>
                                var selectFornitore = document.getElementById('fornitore');
                                var btnAggiungiFornitore = document.getElementById('nuovoFornitore');

                                selectFornitore.addEventListener('change', function() {
                                    if (selectFornitore.value !== '') {
                                        btnAggiungiFornitore.disabled = true;
                                    } else {
                                        btnAggiungiFornitore.disabled = false;
                                    }
                                });
                            </script>
                        </div>
                        
                        <!-- Codice EAN -->
                        <div class="mb-3">
                            <div class="form-floating is-invalid">
                                <input class="form-control <?php if (isset($errorsArticolo['codiceEAN'])) { echo 'is-invalid'; } ?>" type="text" placeholder="Codice EAN" aria-label="Codice EAN" id="codice_EAN" name="codice_EAN" maxlength="13" <?php if(isset($_SESSION['datiArticoli'])){echo 'value="'.$_SESSION['datiArticoli']['codiceEAN'].'"';} ?> >
                                <label for="codice_EAN">Codice EAN</label>
                            </div>
                            <?php if (isset($errorsArticolo['codiceEAN'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['codiceEAN'].'</div>'; } ?>
                        </div>

                        <!-- Immagine -->
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="immagine">Foto articolo</label>
                            <input type="file" name="immagine" id="immagine" class="form-control <?php if (isset($errorsArticolo['immagine'])) { echo 'is-invalid'; }?>">
                        </div>
                        <?php if (isset($errorsArticolo['immagine'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$errorsArticolo['immagine'].'</div>'; } ?>

                        <!-- Categoria -->

                        <div class="row g-2 mb-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select <?php if (isset($errorsArticolo['categoria'])) { echo 'is-invalid'; } ?>" aria-label="Categoria" id="categoria" name="categoria">
                                        <option value="" selected>Seleziona Categoria</option>

                                        <?php
                                            $sql = 'SELECT categoria FROM articoli GROUP BY categoria ORDER BY categoria';
                                            $result = $conn->query($sql);

                                            while ($row = $result->fetch_assoc()) {
                                                $selected = '';
                                                if ($row['categoria'] == $_SESSION['categoriaOK']) {
                                                    $selected = 'selected';
                                                }
                                                echo '<option value="' . $row['categoria'] . '" ' . $selected . '>' . $row['categoria'] . '</option>';
                                            }
                                            
                                        ?>
                                    </select>
                                    <label for="categoria" class="form-label">Categoria</label>
                                    <?php if (isset($errorsArticolo['categoria'])) {echo '<div class="invalid-feedback">' . $errorsArticolo['categoria'] . '</div>';}?>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating is-invalid" id="nuovaCategoriaContainer">
                                    <input class="form-control <?php if (isset($errorsArticolo['categoria'])) { echo 'is-invalid'; } ?>" list="categoriaOpzioni" id="nuovaCategoria" placeholder="Nuova Categoria" name="nuovaCategoria" >
                                    <label for="nuovaCategoria" class="form-label">Inserisci la nuova categoria</label>
                                </div>
                            </div>
                        </div>

                          <script>

                            var selectCategoria = document.getElementById('categoria');
                            var nuovaCategoria = document.getElementById('nuovaCategoria');

                            selectCategoria.addEventListener('change', function() {
                                if (selectCategoria.value !== '') {
                                    nuovaCategoria.disabled = true;
                                } else {
                                    nuovaCategoria.disabled = false;
                                }
                            });

                            nuovaCategoria.addEventListener('change', function() {
                                if (nuovaCategoria.value !== '') {
                                    selectCategoria.disabled = true;
                                } else {
                                    selectCategoria.disabled = false;
                                }
                            });
                          </script>
                          

                        <!-- Note -->
                        <div class="form-floating mb-3">
                            <textarea class="form-control form-floating" id="Note" rows="5" placeholder="Note" name="note" aria-label="Note"></textarea>
                            <label for="note" class="form-label">Note</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-secondary" name="annulla" data-bs-dismiss="modal">Annulla</button>
                        <input type="submit" id="submitBtn" class="btn btn-primary" name="submitNewArticle" value="Salva">
                    </div>
                    </div>
                </div>
            </div>
            </form>
    <!-- New article modal end -->

    <!-- New Fornitore modal -->
            <?php
                $fornitoreErrors = $_SESSION['fornitoreErrors'];
            ?>
            <form action="newFornitore.php" method="post">
                <div class="modal fade" id="newFornitore" aria-hidden="true" aria-labelledby="newFornitoreLabel2" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="newFornitoreLabel2">Nuovo Fornitore</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <!-- ID -->
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" id="id_Fornitore" name="id_Fornitore" placeholder="<?=$nextIDFornitori?>" value="<?=$nextIDFornitori?>" aria-label="id" readonly>
                                <label for="id">ID</label>
                            </div>

                            <!-- Nome -->
                            <div class="form-floating mb-3 is-invalid">
                                <input class="form-control <?php if (isset($fornitoreErrors['nome_lungo'])) { echo 'is-invalid'; }else{if (isset($fornitoreErrors['nome_corto'])) { echo 'is-invalid'; }} ?>" type="text" placeholder="Nome" aria-label="Nome" id="nome_fornitore" name="nome" maxlength="255" <?php if(isset($_SESSION['datiFornitore'])){echo 'value="'.$_SESSION['datiFornitore']['nome'].'"';} ?> >
                                <label for="nome">Nome</label>
                                <?php if (isset($fornitoreErrors['nome_lungo'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$fornitoreErrors['nome_lungo'].'</div>'; }else{if (isset($fornitoreErrors['nome_corto'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$fornitoreErrors['nome_corto'].'</div>'; }} ?>
                            </div>

                            <!-- Indirizzo -->
                            <div class="form-floating mb-3 is-invalid">
                                <input class="form-control <?php if (isset($fornitoreErrors['indirizzo'])) { echo 'is-invalid'; } ?>" type="text" placeholder="Indirizzo" aria-label="Indirizzo" id="indirizzo" maxlength="255" name="indirizzo" <?php if(isset($_SESSION['datiFornitore'])){echo 'value="'.$_SESSION['datiFornitore']['indirizzo'].'"';} ?>>
                                <label for="indirizzo">Indirizzo</label>
                                <?php if (isset($fornitoreErrors['indirizzo'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$fornitoreErrors['indirizzo'].'</div>'; } ?>
                            </div>

                            <!-- Telefono -->
                            <div class="form-floating mb-3 is-invalid">
                                <input class="form-control <?php if (isset($fornitoreErrors['telefono'])) { echo 'is-invalid'; } ?>" type="text" placeholder="Telefono" aria-label="telefono" id="telefono" maxlength="20" name="telefono" <?php if(isset($_SESSION['datiFornitore'])){echo 'value="'.$_SESSION['datiFornitore']['telefono'].'"';} ?> >
                                <label for="telefono">Telefono</label>
                                <?php if (isset($fornitoreErrors['telefono'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$fornitoreErrors['telefono'].'</div>'; } ?>
                            </div>

                            <!-- Email -->
                            <div class="form-floating mb-3">
                                <input class="form-control <?php if (isset($fornitoreErrors['email'])) { echo 'is-invalid'; } ?>" type="email" placeholder="email" aria-label="Email" id="email" maxlength="255" name="email" <?php if(isset($_SESSION['datiFornitore'])){echo 'value="'.$_SESSION['datiFornitore']['email'].'"';} ?> >
                                <label for="email">Email</label>
                                <?php if (isset($fornitoreErrors['email'])) { echo '<div id="nomeArticoloFeedback" class="invalid-feedback">'.$fornitoreErrors['email'].'</div>'; } ?>
                            </div>


                            <?php if (!empty($FornitoreErrors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($FornitoreErrors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-secondary" onclick="backArticle()" data-bs-target="#newArticleModal" data-bs-toggle="modal" value="Annulla">
                            <input type="submit" name="submitNewFornitore" class="btn btn-primary" value="Salva e torna indietro" >
                        </div>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                function backArticle(){
                    $('#newFornitore').modal('hide');
                }

                $(document).ready(function() {
                // Controlla se il parametro "openModalArticle" è presente nell'URL
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('openModalArticle') === 'true' && urlParams.get('error') != 'true') {
                    // Apri il modal
                    $('#newArticleModal').modal('show');

                }else{
                    if(urlParams.get('openModalArticle') === 'true' && urlParams.get('error') === 'true')
                    $('#newFornitore').modal('show');
                }
                });
            </script>
    <!-- New Fornitore modal end-->

    <!-- Edit Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
    </div>
    <!-- END Edit Modal-->


    <?php $conn->close(); ?>
</body>
</html>

<form method="POST" action="process.php">

        <!-- DATABASE TABLE -->
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="article_table" class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>#</th>
                                    <th>#</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <button class='btn btn-secondary btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Modifica articolo' data-bs-toggle='modal' data-bs-target='#exampleModal' ><i class='fas fa-pencil-alt'></i></button>
                                    <button class='btn btn-primary btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Info articolo'>&nbsp<i class='fa-solid fa-info'></i>&nbsp</button>
                                    <button class='btn btn-danger btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Elimina articolo' ><i class='fa-solid fa-trash'></i></button>  
                                    <input type='checkbox' name='selected[]' class='form-check-input ' id='check1' value='1'>
                                </tr>
                                <tr>
                                    <button class='btn btn-secondary btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Modifica articolo' data-bs-toggle='modal' data-bs-target='#exampleModal' ><i class='fas fa-pencil-alt'></i></button>
                                    <button class='btn btn-primary btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Info articolo'>&nbsp<i class='fa-solid fa-info'></i>&nbsp</button>
                                    <button class='btn btn-danger btn-sm' type='button' id='btnEdit' data-toggle='tooltip' title='Elimina articolo' ><i class='fa-solid fa-trash'></i></button>  
                                    <input type='checkbox' name='selected[]' class='form-check-input ' id='check2a' value='2'>
                                </tr>
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

    </form>
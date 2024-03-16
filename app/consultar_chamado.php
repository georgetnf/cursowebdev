<?php

  require_once "validador_acesso.php";  

?>
<?php

  //array para os chamados
  $chamados = array();

  //abrir o arquivo .hd, abrir para leitura
  $arquivo = fopen("../../app_help/arquivo.hd", "r");

  //vai percorrer o arquivo .h  enquanto tiver linhas nele, eof = "end of file"
  //! = inverter a condião
  while (!feof($arquivo)) {
    # code...
    //linhas
    $registro = fgets($arquivo);

    //explode dos detalhes do registro para verificar o id do usuário responsável pelo cadastro
    $registro_detalhes = explode("#", $registro);

    //(perfil id = 2) só vamos exibir o chamado, se ele foi criado pelo usuário
    if ($_SESSION["perfil_id"] == 2) {
      # code...
      //se usuário autenticado não for o usuário de abertura do chamado então não faz nada
      if ($_SESSION["id"] != $registro_detalhes[0]) {
        # code...
        continue;//não faz nada
      }else {
        //insere os registros recuperados do arquivo no array chamado
        $chamados[] = $registro;
      }
    }else {
      //insere os registros recuperados do arquivo no array chamado
      $chamados[] = $registro;  
    }

  }

  //fechar o arquivo
  fclose($arquivo);


?>
<html>
  <head>
    <meta charset="utf-8" />
    <title>App Help Desk</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
      .card-consultar-chamado {
        padding: 30px 0 0 0;
        width: 100%;
        margin: 0 auto;
      }
    </style>
  </head>

  <body>

  <nav class="navbar navbar-dark bg-dark">
      <a class="navbar-brand" href="#">
        <img src="logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
        App Help Desk
      </a>
      <ul class="nav navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="logoff.php">SAIR</a>
        </li>
      </ul>
  </nav>

    <div class="container">    
      <div class="row">

        <div class="card-consultar-chamado">
          <div class="card">
            <div class="card-header">
              Consulta de chamado
            </div>
            
            <div class="card-body">
              
            <?php foreach ($chamados as $chamado){ ?>

              <?php 
                
                $chamado_dados = explode("#", $chamado);

                //verifica se o perfil de usuario NÂO é de administrador
                /*if($_SESSION["perfil_id"] == 2){
                  //só vamos exibir o chamado se foi criado pelo mesmo usuario
                  if ($chamado_dados[0] != $_SESSION["id"]) {
                    # code...
                    continue;
                  }
                }*/

                if (count($chamado_dados)< 3) {
                  # code...
                  continue;
                }
              
              ?>
              <div class="card mb-3 bg-light">
                <div class="card-body">
                  <h5 class="card-title"><?=$chamado_dados[1]?></h5>
                  <h6 class="card-subtitle mb-2 text-muted"><?=$chamado_dados[2]?></h6>
                  <p class="card-text"><?=$chamado_dados[3]?></p>

                </div>
              </div>
              <?php } ?>


              <div class="row mt-5">
                <div class="col-6">
                <a class="btn btn-lg btn-warning btn-block" href="home.php">Voltar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
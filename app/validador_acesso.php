<?php

  //como o session_start está aqui nas outras paginas apenas requisitamos o validador_acesso
  session_start();
  if(!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] != "Sim"){
    header("Location: index.php?login=erro2");
  }
  
  //echo $_SESSION["autenticado"];

?>
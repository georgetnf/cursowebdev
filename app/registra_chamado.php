<?php

    echo "<pre>";
    //variavel post
    print_r($_POST); 
    echo "</pre>"; 

    session_start();

    //para evitar bugs forçamos a substituição do "#" por um "-"
    $titulo = str_replace("#","-",$_POST["titulo"]);
    $categoria = str_replace("#","-",$_POST["categoria"]);
    $descricao = str_replace("#","-",$_POST["descricao"]);

    //"#" usado para separar textos
    //.PHP_EOL = end of line, quebra de linha
    //$texto = $titulo. "#" .$categoria. "#" . $descricao.PHP_EOL;
    $texto = $_SESSION["id"]."#".$titulo."#".$categoria."#".$descricao.PHP_EOL;

    //abrindo o arquivo 
    $arquivo = fopen("../../app_help/arquivo.hd", "a");
    
    //escrever o texto no arquivo
    fwrite($arquivo, $texto);
    //fecha o arquivo que foi aberto
    fclose($arquivo);

    //echo $texto;

    header("Location: abrir_chamado.php");
?>
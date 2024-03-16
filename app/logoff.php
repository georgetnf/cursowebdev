<?php

    //acesso a super global
    session_start();

    session_destroy();
    header("Location: index.php");

?>

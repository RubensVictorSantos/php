<?php
    
    require_once('../modulo.php');
    require_once('../bd/conexao.php');
    session_start();
    
?>


<!DOCTYPE html>
<html lang="pt-br">
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8" />
    <head>
        <title>
            CMS Adm. Usuário
        </title>
        
        <link rel="icon" href="../img/ico/i405_TDM_icon_bike93.gif">
        <link rel="stylesheet" type="text/css" href="css/style.css">

    </head>
    <body>
        <div id="box-main" class="center">
            
            <!--********************* MENU ********************-->
            <?php
                require_once('cms-menu.php');
    
            ?>
            
            <!--********************* CONTEÚDO ****************-->
            <div class="titulos-cms">
                <h3>Administrar Usuarios</h3>
            </div>
            
            <div id="conteudo">
                
                <div>
                    
                    <a href="cms-usuario.php">Cadastrar Usuário</a>
                    
                </div>
                <div>
                    
                    <a href="cms-nivel-usuario.php">Cadastrar Nível Usuário</a>
                    
                </div>
                
            </div>
            
            <!--********************* FOOTER ********************-->
            <div id="footer">
                <h3>
                    Desenvolvido por: Rubens Victor
                </h3>
            
            </div>
        </div>
    </body>
</html>
<?php

    require_once('../modulo.php');
    require_once('../db/conexao.php');
    session_start();
    
    $conexao = conexaoMysql();

    $nomeProduto = null;
    $imagem = null;
    $descricao = null;
    $preco = null;
    $valorDesconto = null;
    $status = null;
    $sql = null;
    $rs = null;
    $id = null;
    $rdoativado = null;
    $rdodesativado = null;
    $botao = 'salvar';
    
    if(isset($_GET['modo'])){
        
        $modo = $_GET['modo'];
        $id = $_GET['id'];
        
        /***************************** EXCLUIR ************************/
        if($modo == 'excluir'){
            $nomefoto = $_GET['nomefoto'];
            
            $sql = "DELETE FROM tbl_produto WHERE codigo =".$id;
            mysqli_query($conexao, $sql);
            
            unlink($nomefoto);
            
            header('location:cms-promocoes.php');
            
        /************************ CONSULTAR **********************/
        }elseif($modo == 'consultar'){
            
            $sql = "SELECT * FROM tbl_produto WHERE codigo =".$id;
            $select = mysqli_query($conexao, $sql);
            
            if($rs = mysqli_fetch_array($select)){
                
                $nomeProduto = $rs['produto'];
                $nomefoto = $rs['imagem'];
                $descricao = $rs['descricao'];
                $preco = $rs['preco'];
                $valorDesconto = $rs['valor_desconto'];
                
                if($rs['status'] == 'A'){
                    $rdoativado = 'checked';
                
                }else{
                    $rdodesativado = 'checked';
                
                }
                $botao = 'editar';
                
                $_SESSION['id'] = $id;
                $_SESSION['nomefoto'] = $nomefoto;
                $_SESSION['path_foto'] = null;
                
            }
        }
    }

    if(isset($_POST['btnsalvar'])){
        
        $nomeProduto = filter_var($_POST['textnomep'], FILTER_SANITIZE_STRING);
        $descricao = filter_var($_POST['textdescricao'], FILTER_SANITIZE_STRING);
        $preco = filter_var($_POST['textpreco'], FILTER_SANITIZE_STRING);
        $valorDesconto = filter_var($_POST['textvdesconto'], FILTER_SANITIZE_STRING);
        $status = $_POST['radio'];
        $foto = "";
        
        if(isset($_SESSION['path_foto'])){
            $foto = $_SESSION['path_foto'];

        }
        
        /***************************** SALVAR ************************/
        if($_POST['btnsalvar']=='salvar'){
            
            if(isset($_SESSION['path_foto']) && $_SESSION['path_foto']!=null){
            
                if(is_numeric($preco) && is_numeric($valor_desconto)){
                    
                    $sql = "INSERT INTO tbl_produto(produto, imagem,descricao, preco, valor_desconto, status) VALUES ('".$nomeProduto."','".$foto."','".$descricao."',".$preco.",".$valorDesconto.",'".$status."')";

                    if(mysqli_query($conexao, $sql)){
                        $_SESSION['path_foto'] = null;
                        $_SESSION['nomefoto'] = null;

                    }
                    
                    header("location:cms-promocoes.php");
                }else{
                    echo("<script>alert('Digite um valor numerico nos campos preço e desconto')</script>");
                    
                }
            }else{
                echo("<script>alert('Erro ao salvar')</script>");
                
            }
            
        /***************************** EDITAR ************************/
        }elseif($_POST['btnsalvar']=='editar'){
            
            if(is_numeric($preco) && is_numeric($valorDesconto)){
                if(isset($_SESSION['path_foto']) && $_SESSION['path_foto']!=null){



                    $sql = "UPDATE tbl_produto SET produto = '".$nomeProduto."',
                                                imagem ='".$foto."',
                                                descricao = '".$descricao."',
                                                preco = ".$preco.",
                                                valor_desconto = ".$valorDesconto.",
                                                status = '".$status."'
                                                WHERE codigo =".$_SESSION['id'];

                    if(mysqli_query($conexao, $sql)){
                        unlink($_SESSION['nomefoto']);
                        $_SESSION['path_foto'] = null;
                        $_SESSION['nomefoto'] = null;

                    }
                    
                    header("location:cms-promocoes.php");
                }else{

                    $sql = "UPDATE tbl_produto SET produto = '".$nomeProduto."',
                                                descricao = '".$descricao."',
                                                preco = ".$preco.",
                                                valor_desconto = ".$valorDesconto.",
                                                status = '".$status."'
                                                WHERE codigo =".$_SESSION['id'];

                    mysqli_query($conexao, $sql);
                    
                    header("location:cms-promocoes.php");
                }
                
            }else{
                echo("<script>alert('Digite um valor numerico nos campos preço e desconto')</script>");
                
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>
            cms-promoções
        </title>
        <link rel="icon" href="../img/ico/i405_TDM_icon_bike93.gif">
        <script src="../js/mascara.js" type="text/javascript"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.form.js"></script>
        <script>
            $(document).ready(function(){
                
                $('#filefoto').live('change', function(){
                    
                    $('#fotos').ajaxForm({
                        
                        target: '#visualizar_foto'
                        
                    }).submit();
                    
                });
            });
        </script>
    </head>
    <body>
        <div id="box-main" class="center">   
            <?php
                include('cms-menu.php');
    
            ?>
            <div class="titulos-cms">
                <h3>Página Promoções</h3>
                
            </div>
            <div id="conteudo">
                <div class="conteudo-cms-promo">
                    <form id="fotos" name="frmFotos" method="POST" action="upload.php" enctype="multipart/form-data">
                        <div class="input-text-cms">
                            <div id="box-file">
                                <input type="file"
                                       id="filefoto"
                                       name="flefoto"
                                       required>
                                
                            </div>
                        </div>
                    </form>
                    
                    <form name="frmcms-promocoes" method="POST" action="cms-promocoes.php" enctype="multipart/form-data">
                        <div id="visualizar_foto">  
                            <?php
                                if(isset($nomefoto)){

                            ?>

                            <img src="<?php echo($nomefoto);?>" alt="" id="img-card" style="border:1px solid #003311;border-radius:2px 2px;">


                            <?php
                                }else{
                            ?>
                            
                            <img src="../img/ico/imgnotfound.png" alt="" id="img-card">
                            
                            <?php 
                                }

                            ?>
                        </div>
                        <div class="input-text-cms">
                            <div class="box-input-promo">
                                <input onkeypress="return validar(event,'number','nome')"
                                       type="text"
                                       name="textnomep"
                                       id="nome"
                                       class="input-cms-promo"
                                       value="<?php echo($nomeProduto)?>"
                                       maxlength="90"
                                       placeholder="Digite o nome do produto"
                                       required>

                            </div>
                        </div>
                        <div class="input-text-cms">
                            <div class="box-input-promo">
                                <input type="text"
                                       name="textdescricao"
                                       class="input-cms-promo"
                                       value="<?php echo($descricao)?>"
                                       maxlength="65"
                                       placeholder="Digite a descrição do produto"
                                       required>

                            </div>
                        </div>
                        <div class="input-text-cms">
                            <div class="box-input-promo">
                                <input onkeypress="return validar(event,'decimal','preco')"
                                       type="text"
                                       name="textpreco"
                                       id="preco"
                                       class="input-cms-promo"
                                       value="<?php echo($preco)?>"
                                       maxlength="6"
                                       placeholder=" Digite o preço atual do produto"
                                       required>

                            </div>
                        </div>
                        <div class="input-text-cms">
                            <div class="box-input-promo">
                                <input type="text"
                                       name="textvdesconto"
                                       class="input-cms-promo"
                                       value="<?php echo($valorDesconto)?>"
                                       maxlength="6"
                                       placeholder=" Digite o preço promocional"
                                       required>

                            </div>
                        </div>
                        <div class="input-text-cms">
                            <div class="box-rdo" style="width:85px;">
                                <input type="radio"
                                       name="radio"
                                       value="A"
                                       id="rdo-ativado" <?php echo($rdoativado)?>
                                       checked
                                       required>

                                <label for="rdo-ativado"> Ativar</label>
                            </div>
                            <div class="box-rdo">
                                <input type="radio"
                                       name="radio"
                                       value="D"
                                       id="rdo-desativado" <?php echo($rdodesativado)?>
                                       required>

                                <label for="rdo-desativado"> Desativar</label>
                            </div>
                            <div class="box-rdo">
                                <input type="submit"
                                       class="btn-salvar"
                                       name="btnsalvar"
                                       id="btnsalvar"
                                       value="<?php echo($botao)?>">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="conteudo-cms-tbl">
                    <div id="tbl-promocoes">
                        <div class="cabecalho">
                            <div class="titulos-promo" style="width:300px;">
                                <p>
                                    Produto
                                </p>
                                
                            </div>
                            <div class="titulos-promo" style="width:200px;">
                                <p>
                                    Imagem
                                </p>
                                
                            </div>
                            <div class="titulos-promo" style="width:120px;">
                                <p>
                                    Preço
                                </p>
                                
                            </div>
                            <div class="titulos-promo" style="width:120px;">
                                <p>
                                    Desconto
                                </p>
                                
                            </div>
                            <div class="titulos-promo" style="width:120px;">
                                <p>
                                    Status
                                </p>
                                
                            </div>
                            <div class="titulo-campo-opcoes">
                                <p>
                                    Opções
                                </p>
                                
                            </div>
                        </div>
                        <?php
                            
                            /********************* VISUALIZAR DADOS DO BANCO ************************/
                            $sql = "SELECT * FROM tbl_produto ORDER BY codigo DESC";
                            $select = mysqli_query($conexao, $sql);

                            while($rscontatos=mysqli_fetch_array($select)){
                                
                        ?>
                        <div class="tbl-dados-db">
                            <div class="campos-tbl-promo" style="width:300px;">
                                <?php echo($rscontatos['produto'])?>
                                
                            </div>
                            <div class="campos-tbl-promo" style="width:200px;">
                                <?php echo($rscontatos['imagem'])?>
                                
                            </div>
                            <div class="campos-tbl-promo" style="width:120px;">
                                <?php echo($rscontatos['preco'])?>
                                
                            </div>
                            <div class="campos-tbl-promo" style="width:120px;">
                                <?php echo($rscontatos['valor_desconto'])?>
                                
                            </div>
                            <div class="campos-tbl-promo" style="width:120px;">
                                <?php
                                    if($rscontatos['status'] == 'A'){
                                        echo('Ativado');
                                        
                                    }else{
                                        echo('Desativado');
                                        
                                    }
                                ?>	
                            </div>
                            
                            <div class="campo-opcoes">
                                <div class="opcoes-promo">
                                    <a href= "cms-promocoes.php?modo=excluir&id=<?php echo($rscontatos['codigo']);?>&nomefoto=<?php echo($rscontatos['imagem']);?>" onclick="return confirm('Deseja realmente excluir?');">

                                        <input type="image"
                                               src="../img/excluir.png"
                                               width="24px"
                                               height="24px"
                                               class="img center"
                                               style="margin-top:2px;">
                                        
                                    </a>
                                </div>
                                <div class="opcoes-promo">
                                    <a href="cms-promocoes.php?modo=consultar&id=<?php echo($rscontatos['codigo']);?>">
                                        <img src="../img/editar24.png" width="20px" height="23px" class="img center" style="margin-top:2px;">
                                        
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
            <div id="footer">
                <h3>
                    Desenvolvido por: Rubens Victor
                </h3>
            
            </div>
        </div>
    </body>
</html>
<?php
include './library/configServer.php';
include './library/consulSQL.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Destino</title>
    <?php include './plantilla/link.php'; ?>
</head>
<body id="container-page-product">
    <?php include './plantilla/navbar.php'; ?>
    <section id="store">
       <br>
        <div class="container">
            <div class="page-header">
              <h1>Destinos <small class="tittles-pages-logo">ViajiTico</small></h1>
            </div>
            <?php
            session_start();
            include_once './library/configServer.php';
            include_once './library/consulSQL.php';

            $categoriac = ejecutarSQL::consultar('SELECT * FROM DESTINO');
            oci_execute($categoriac);
              // $checkAllCat=ejecutarSQL::consultar("SELECT * FROM categoria");
              // if(oci_num_rows($checkAllCat)>=1):
            ?>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-xs-12 col-md-4">
                    <div class="dropdown">
                      <button class="btn btn-primary btn-raised dropdown-toggle" type="button" id="drpdowncategory" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Seleccione un Destino &nbsp;
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="drpdowncategory">
                        <?php 
                          while($dest=oci_fetch_array($categoriac, OCI_ASSOC + OCI_RETURN_NULLS)){
                              echo '
                                <li><a href="destino.php?categ='.$dest['id_destino'].'">'.$dest['des_actividad'].'</a></li>
                                <li role="separator" class="divider"></li>
                              ';
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <!-- <div class="col-xs-12 col-md-4 col-md-offset-4">
                    <form action="./search.php" method="GET">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>
                          <input type="text" id="addon1" class="form-control" name="term" required="" title="Escriba nombre o marca del producto">
                          <span class="input-group-btn">
                              <button class="btn btn-info btn-raised" type="submit">Buscar</button>
                          </span>
                        </div>
                      </div>
                    </form>
                  </div> -->
                </div>
              </div>
            <?php
                $categoria=consultasSQL::clean_string($_GET['categ']);
                if(isset($categoria) && $categoria!=""){
            ?>
            <?php
                }else{
                  echo '<h2 class="text-center">Por favor seleccione una categoría para empezar</h2>';
                }
              // else:
                // echo '<h2 class="text-center">Lo sentimos, no hay productos ni categorías registradas en la tienda</h2>';
              // endif;
            ?>
        </div>
    </section>
    <?php include './plantilla/footer.php'; ?>
</body>
</html>
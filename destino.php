<?php
include './library/configServer.php';
include './library/consulSQL.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Productos</title>
    <?php include './plantilla/link.php'; ?>
</head>
<body id="container-page-product">
    <?php include './plantilla/navbar.php'; ?>
    <section id="store">
       <br>
        <div class="container">
            <div class="page-header">
              <h1>PRODUCTOS <small class="tittles-pages-logo">ViajiTico</small></h1>
            </div>
            <?php
              $checkAllCat=ejecutarSQL::consultar("SELECT * FROM PROVINCIA");
              if(oci_num_rows($checkAllCat)>=1):
            ?>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-xs-12 col-md-4">
                    <div class="dropdown">
                      <button class="btn btn-primary btn-raised dropdown-toggle" type="button" id="drpdowncategory" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Seleccione una categoría &nbsp;
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="drpdowncategory">
                        <?php 
                          while($cate=oci_fetch_array($checkAllCat, OCI_ASSOC + OCI_RETURN_NULLS)){
                              echo '
                                <li><a href="product.php?categ='.$cate['CodigoCat'].'">'.$cate['Nombre'].'</a></li>
                                <li role="separator" class="divider"></li>
                              ';
                          }
                        ?>
                      </ul>
                    </div>
                  </div>
                  <div class="col-xs-12 col-md-4 col-md-offset-4">
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
                  </div>
                </div>
              </div>
            <?php
                $categoria=consultasSQL::clean_string($_GET['categ']);
                if(isset($categoria) && $categoria!=""){
            ?>
              <div class="row">
                <?php
                  $stid = oci_connect(SERVER, USER, PASS);
                  //oci_set_charset($stid, "utf8");

                  $pagina = isset($_GET['pag']) ? (int)$_GET['pag'] : 1;
                  $regpagina = 20;
                  $inicio = ($pagina > 1) ? (($pagina * $regpagina) - $regpagina) : 0;

                  //$consultar_productos=oci_parse($stid,"SELECT SQL_CALC_FOUND_ROWS * FROM destino WHERE CodigoCat='$categoria' AND Stock > 0 AND Estado='Activo' LIMIT $inicio, $regpagina");
                  $consultar_productos=oci_parse($stid,"SELECT * FROM destino where id_provincia = :pidProvincia");
                  $pidProvincia = $provincia;
                  oci_bind_by_name($consultar_productos, ':pidProvincia', $pidProvincia);

                  $selCat=ejecutarSQL::consultar("SELECT * FROM categoria WHERE CodigoCat='$categoria'");
                  $datCat=oci_fetch_array($selCat, OCI_ASSOC + OCI_RETURN_NULLS);

                  $totalregistros = oci_parse($stid,"SELECT FOUND_ROWS()");
                  $totalregistros = oci_fetch_array($totalregistros, OCI_ASSOC + OCI_RETURN_NULLS);
        
                  $numeropaginas = ceil($totalregistros["FOUND_ROWS()"]/$regpagina);

                  if(oci_num_rows($consultar_productos)>=1){
                    echo '<h3 class="text-center">Se muestran los productos de la categoría <strong>"'.$datCat['Nombre'].'"</strong></h3><br>';
                    while($prod=oci_fetch_array($consultar_productos, OCI_ASSOC + OCI_RETURN_NULLS)){
                ?>
                    <div class="col-xs-12 col-sm-6 col-md-4">
                         <div class="thumbnail">
                           <img class="img-product" src="./assets/img-products/<?php if($prod['Imagen']!="" && is_file("./assets/img-products/".$prod['Imagen'])){ echo $prod['Imagen']; }else{ echo "default.png"; } ?>
                           ">
                           <div class="caption">
                             <h3><?php echo $prod['Marca']; ?></h3>
                             <p><?php echo $prod['NombreProd']; ?></p>
                             <?php if($prod['Descuento']>0): ?>
                             <p>
                             <?php
                             $pref=number_format($prod['Precio']-($prod['Precio']*($prod['Descuento']/100)), 2, '.', '');
                             echo $prod['Descuento']."% descuento: $".$pref; 
                             ?>
                             </p>
                             <?php else: ?>
                              <p>$<?php echo $prod['Precio']; ?></p>
                             <?php endif; ?>
                             <p class="text-center">
                                 <a href="infoProd.php?CodigoProd=<?php echo $prod['CodigoProd']; ?>" class="btn btn-primary btn-raised btn-sm btn-block"><i class="fa fa-plus"></i>&nbsp; Detalles</a>
                             </p>

                           </div>
                         </div>
                     </div>     
                <?php    
                  }
                  if($numeropaginas>0):
                ?>
                <div class="clearfix"></div>
                <div class="text-center">
                  <ul class="pagination">
                    <?php if($pagina == 1): ?>
                        <li class="disabled">
                            <a>
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="product.php?categ=<?php echo $categoria; ?>&pag=<?php echo $pagina-1; ?>">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>


                    <?php
                        for($i=1; $i <= $numeropaginas; $i++ ){
                            if($pagina == $i){
                                echo '<li class="active"><a href="product.php?categ='.$categoria.'&pag='.$i.'">'.$i.'</a></li>';
                            }else{
                                echo '<li><a href="product.php?categ='.$categoria.'&pag='.$i.'">'.$i.'</a></li>';
                            }
                        }
                    ?>
                    

                    <?php if($pagina == $numeropaginas): ?>
                        <li class="disabled">
                            <a>
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="product.php?categ=<?php echo $categoria; ?>&pag=<?php echo $pagina+1; ?>">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                  </ul>
                </div>
                <?php
                  endif;
                  }else{
                    echo '<h2 class="text-center">Lo sentimos, no hay productos registrados en la categoría <strong>"'.$datCat['Nombre'].'"</strong></h2>';
                  }
                ?>
              </div>
            <?php
                }else{
                  echo '<h2 class="text-center">Por favor seleccione una categoría para empezar</h2>';
                }
              else:
                echo '<h2 class="text-center">Lo sentimos, no hay productos ni categorías registradas en la tienda</h2>';
              endif;
            ?>
        </div>
    </section>
    <?php include './plantilla/footer.php'; ?>
</body>
</html>
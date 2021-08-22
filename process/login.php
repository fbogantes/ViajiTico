<?php
    session_start();
    include '../library/configServer.php';
    include '../library/consulSQL.php';

    $nombre=consultasSQL::clean_string($_POST['nombre-login']);
    $clave=consultasSQL::clean_string($_POST['clave-login']);
    $radio=consultasSQL::clean_string($_POST['optionsRadios']);
    if($nombre!="" && $clave!=""){
        if($radio=="option2"){

          $verAdmin=ejecutarSQL::consultar('SELECT * FROM ADMINISTRADOR WHERE NOMBRE = :pNombre AND CLAVE = :pClave');
          $pNombre = $nombre;
          $pClave = $clave;
          oci_bind_by_name($verAdmin, ':pNombre', $pNombre);
          oci_bind_by_name($verAdmin, ':pClave', $pClave);
          oci_execute($verAdmin);

        while (($row = oci_fetch_array($verAdmin, OCI_ASSOC + OCI_RETURN_NULLS)) != false) {
            foreach ($row as $item) {
                ($item!==null?htmlentities($item, ENT_QUOTES|ENT_SUBSTITUTE):"");
            }
            $admin_array[] = array("ID"=>oci_result($stid, 'ID'),"NOMBRE"=>oci_result($stid, 'NOMBRE'),"CLAVE"=>oci_result($stid, 'CLAVE'));
        }
          //oci_free_statement($verAdmin); //cerrar sesion
        foreach($admin_array as $index => $value){
            $id  = $value['ID'];
            $nombre  = $value['NOMBRE'];
            $clave = $value['CLAVE'];
        }
          $AdminC=oci_num_rows($verAdmin);
            if(oci_num_rows($verAdmin)>0){
                $filaU=oci_fetch_array($verAdmin, OCI_ASSOC + OCI_RETURN_NULLS);
                $_SESSION['nombreAdmin']=$nombre;
                $_SESSION['claveAdmin']=$clave;
                $_SESSION['UserType']="Admin";
                $_SESSION['adminID']=$filaU['id'];
                echo '<script> location.href="index.php"; </script>';
            }else{
              echo 'Error nombre o contraseña invalido';
            }

            oci_close(ejecutarSQL::conectar());
        }
        if($radio=="option1"){
            $verUser=ejecutarSQL::consultar($conexion,"SELECT * FROM cliente WHERE Nombre=:pNombre AND Clave=:pClave");
            oci_bind_by_name($verUser, ':pNombre', $nombre);
            oci_bind_by_name($verUser, ':pClave', $clave);
            oci_execute($verUser);
            $filaU=oci_fetch_array($verUser, OCI_ASSOC + OCI_RETURN_NULLS);
            $UserC=oci_num_rows($verUser);
            if($UserC>0){
                $_SESSION['nombreUser']=$nombre;
                $_SESSION['claveUser']=$clave;
                $_SESSION['UserType']="User";
                $_SESSION['UserNIT']=$filaU['NIT'];
                echo '<script> location.href="index.php"; </script>';
            }else{
                echo 'Error nombre o contraseña invalido';
            }
        }

    }else{
        echo 'Error campo vacío<br>Intente nuevamente';
    }
    

<?php

include '../library/configServer.php';
include '../library/consulSQL.php';

$pCedula=consultasSQL::clean_string($_POST['clien-nit']);
$pnombre=consultasSQL::clean_string($_POST['clien-name']);
$fullnameCliente=consultasSQL::clean_string($_POST['clien-fullname']);
$papellido1=consultasSQL::clean_string($_POST['clien-lastname']);
$papellido2=consultasSQL::clean_string($_POST['clien-lastname2']);
$dirCliente=consultasSQL::clean_string($_POST['clien-dir']);
$pTelefono=consultasSQL::clean_string($_POST['clien-phone']);
$pEmail=consultasSQL::clean_string($_POST['clien-email']);
$pdestino=consultasSQL::clean_string($_POST['clien-destino']);
$pIdioma=consultasSQL::clean_string($_POST['idioma']);
$pfecha=consultasSQL::clean_string($_POST['fecha']);
$pCantidad=consultasSQL::clean_string($_POST['cantidad']);
$pComentario=consultasSQL::clean_string($_POST['comentario']);

if(!$pCedula=="" && !$pnombre=="" && !$papellido1=="" && !$papellido2=="" && !$dirCliente=="" && !$pTelefono=="" && !$pEmail=="" && !$fullnameCliente=="" && !$pdestino=="" && !$pIdioma=="" && !$pfecha=="" && !$pCantidad="" && !$pComentario=""){
    /*if($passCliente==$passCliente2){*/
        $INSERTAR = ejecutarSQL::consultar('EXECUTE pack_insert.p_insert_reserva (to_date(:pFecha), :pDestino, :pNombre, :pApellido1, :pApellido2, :pCedula, :pIdioma, :pTelefono, :pEmail, :pCantidad, :pComentario)');
        
        $fecha = $pfecha;
        $destino = $pdestino;
        $nombre = $pnombre;
        $apellido1 = $papellido1;
        $apellido2 = $papellido2;
        $cedula = $pCedula;
        $idioma = $pIdioma;
        $telefono = $pTelefono;
        $email = $pEmail;
        $cantidad = $pCantidad;
        $comentario = $pComentario;

        oci_bind_by_name($INSERTAR, ':pFecha', $fecha);
        oci_bind_by_name($INSERTAR, ':pDestino', $destino);
        oci_bind_by_name($INSERTAR, ':pNombre', $nombre);
        oci_bind_by_name($INSERTAR, ':pApellido1', $apellido1);
        oci_bind_by_name($INSERTAR, ':pApellido2', $apellido2);
        oci_bind_by_name($INSERTAR, ':pCedula', $cedula);
        oci_bind_by_name($INSERTAR, ':pIdioma', $idioma);
        oci_bind_by_name($INSERTAR, ':pTelefono', $telefono);
        oci_bind_by_name($INSERTAR, ':pEmail', $email);
        oci_bind_by_name($INSERTAR, ':pCantidad', $cantidad);
        oci_bind_by_name($INSERTAR, ':pComentario', $comentario);

        
        $resultado = oci_execute($INSERTAR); //commit


        if ($resultado) {
          echo '<script>
                    swal.fire({
                      title: "Registro completado",
                      text: "El registro se completó con éxito, ya puedes iniciar sesión en el sistema",
                      type: "success",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "Aceptar",
                      cancelButtonText: "Cancelar",
                      closeOnConfirm: false,
                      closeOnCancel: false
                      },
                      function(isConfirm) {
                      if (isConfirm) {
                        location.reload();
                      } else {
                        location.reload();
                      }
                    });
                </script>';
        }

        oci_free_statement($INSERTAR); //cerrar sesion

        oci_close($conexion); //cerrar conexion

        //fin insersion preparada

        /*
        $verificaltotal = oci_num_rows($verificar);
        if($verificaltotal<=0){
            if(consultasSQL::InsertSQL("cliente", "NIT, Nombre, NombreCompleto, Apellido, Direccion, Clave, Telefono, Email", "'$nitCliente','$nameCliente','$fullnameCliente','$apeCliente','$dirCliente', '$passCliente','$phoneCliente','$emailCliente'")){
                echo '<script>
                    swal({
                      title: "Registro completado",
                      text: "El registro se completó con éxito, ya puedes iniciar sesión en el sistema",
                      type: "success",
                      showCancelButton: true,
                      confirmButtonClass: "btn-danger",
                      confirmButtonText: "Aceptar",
                      cancelButtonText: "Cancelar",
                      closeOnConfirm: false,
                      closeOnCancel: false
                      },
                      function(isConfirm) {
                      if (isConfirm) {
                        location.reload();
                      } else {
                        location.reload();
                      }
                    });
                  </script>';
            }else{
               echo '<script>swal("ERROR", "Ocurrió un error inesperado, por favor intente nuevamente", "error");</script>';
            }
        }else{
            echo '<script>swal("ERROR", "El DNI que ha ingresado ya está registrado en el sistema, por favor ingrese otro número de DNI", "error");</script>';
        }
        oci_free_statement($verificar);
    }else{
        echo '<script>swal("ERROR", "Las contraseñas no coinciden, por favor verifique e intente nuevamente", "error");</script>';
    }*/
}else {
    echo '<script>swal("ERROR", "Los campos no pueden estar vacíos", "error");</script>';
}
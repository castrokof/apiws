// Scripts se ejecutan cuando el dom este cargado completamente
$(document).ready(function () {

    //Función para convertir minuscula en mayuscula en el form
    $(".UpperCase").on("keypress", function () {
        $input = $(this);
        setTimeout(function () {
            $input.val($input.val().toUpperCase());
        }, 50);
    });


    //------------------------------------------------------Funciones de Listas-----------------------------------------//

    //Función para abrir modal y prevenir el cierre de creación de listas
    $(document).on('click', '.create_listas', function () {

        $('#modal-listas').modal({ backdrop: 'static', keyboard: false });
        $('#modal-listas').modal('show');


    });

    //Recuperar el valor del radio button de listas

    function customSwitch1() {

        if ($('#customSwitch1').prop('checked')) {
            $('#activo').val('SI');
            console.log($('#activo').val());

        } else {

            $('#activo').val('NO');
            console.log($('#activo').val());

        }
    }

    $("#customSwitch1").change(customSwitch1);

    //------------------------------------------------------Funciones de items-----------------------------------------//







    //Función para abrir modal y prevenir el cierre de creación de items
    $(document).on('click', '.create_items', function () {

        $('#modal-items').modal({ backdrop: 'static', keyboard: false });
        $('#modal-items').modal('show');


    });

    //Recuperar el valor del radio button de detalle de items
    function customSwitch2() {

        if ($('#customSwitch2').prop('checked')) {
            $('#activo2').val('SI');
            console.log($('#activo2').val());

        } else {

            $('#activo2').val('NO');
            console.log($('#activo2').val());

        }
    }

    $("#customSwitch2").change(customSwitch2);


    // Función que envía los datos de listas al controlador ademas controla los input con sweat alert2

    $('#form-general2').on('submit', function (event) {
        event.preventDefault();
        var url = '';
        var method = '';
        var text = '';


        if ($('#action').val() == 'Add') {
            text = "Estás por crear un item"
            url = "detallecrear-listas";
            method = 'post';
        }

        if ($('#nombre1').val() == '' || $('#descripcion1').val() == '' || $('#slug1').val() == '' ||
            $('#activo1').val() == '') {
            Swal.fire({
                title: "Debes de rellenar todos los campos del formulario",
                text: "Respuesta Paliativos",
                icon: "warning",
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            Swal.fire({
                target: document.getElementById('modal-items'),
                title: "¿Estás seguro?",
                text: text,
                icon: "success",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {
                    Swal.fire({
                        target: document.getElementById('modal-items'),
                        icon: "success",
                        title: 'Espere por favor !',
                        html: 'Creando item..',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        willOpen: () => {
                            Swal.showLoading()
                        },
                    }),
                        $.ajax({
                            url: url,
                            method: method,
                            data: $('#form-general2').serialize(),
                            dataType: "json",
                            success: function (data) {
                                if (data.success == 'ok') {
                                    $('#form-general2')[0].reset();
                                    $('#modal-items').modal('hide');
                                    $('#listasGeneralDetalle').DataTable().ajax.reload();
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Item creado correctamente',
                                        showConfirmButton: false,
                                        timer: 2000
                                    })
                                } else if (data.errors != null) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: data.errors,
                                        showConfirmButton: false,
                                        timer: 3000
                                    })
                                }
                            }

                        });
                }
            });

        }

    });




    // Función para cambio de check y obtener el cambio del dom

    $(document).on('click', '.check_99', function () {

        if ($(this).is(':checked')) {
            var data = {
                id: $(this).val(),
                _token: $('input[name=_token]').val()
            };

        }
        //Si se ha desmarcado se ejecuta el siguiente mensaje.
        else {
            var data = {
                id: $(this).val(),
                _token: $('input[name=_token]').val()
            }
        }

        ajaxRequest('detalle-estado', data);
    });

    // Función envío de datos para activar o desactivar

    function ajaxRequest(url, data) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (respuesta) {

                $('#listasGeneralDetalle').DataTable().ajax.reload();
                Manteliviano.notificaciones(respuesta.respuesta, respuesta.titulo, respuesta.icon);
            }
        });
    }




});



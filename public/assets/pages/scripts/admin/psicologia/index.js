// Scripts se ejecutan cuando el dom este cargado completamente
$(document).ready(function(){

    //Función para convertir minuscula en mayuscula en el form

    $(".UpperCase").on("keypress", function () {
        $input=$(this);
        setTimeout(function () {
         $input.val($input.val().toUpperCase());
        },50);
       });



    //Función para abrir modal y prevenir el cierre
     $(document).on('click', '.create_evolution', function(){

                $('#modal-evolution').modal({backdrop: 'static', keyboard: false});
                $('#modal-evolution').modal('show');


    });




    function ocultar(){

        if($('#municipality').val() != "Otro"){


           $("#municipio_otras").css("display", "none")
           $("#municipioo").prop("required", false);



        }else{

            $("#municipio_otras").css("display", "block");
            $("#municipioo").prop("required", true);


             }

    }

    function ocultar_radio(){

         if($('#consultation').val() != 1){
            $("#radio_button").css("display", "none")
            $('#radioPrimary1').prop('checked',false);
            $('#radioPrimary2').prop('checked',false);
            $('#diagnos').val('');

            }else{

                $("#radio_button").css("display", "block");

                }

    }

    function radio_button(){

        if($('#radioPrimary1').prop('checked')){
            $('#diagnosis').val('SI');
            console.log( $('#diagnosis').val());

        }else{

            $('#diagnosis').val('NO');
            console.log( $('#diagnosis').val());


            }

    }

    $("#municipality").change(ocultar);
    $("#consultation").change(ocultar_radio);
    $("#radioPrimary1").change(radio_button);
    $("#radioPrimary2").change(radio_button);




    function edad(){

            let hoy = new Date();


            if($('#date_birth').val() != null){

            var nacimiento = new Date($('#date_birth').val());
            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            let meses = hoy.getMonth() - nacimiento.getMonth();

            if (meses < 0 || (meses === 0 && hoy.getDate() < nacimiento.getDate())) {
                            edad--;
                        }
            console.log(edad);

            $('#edad').val(edad);

            }else{

                $('#edad').val();
            }
    }

    $("#date_birth").change(edad);







    });

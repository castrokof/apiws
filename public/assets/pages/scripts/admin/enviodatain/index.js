// Scripts se ejecutan cuando el dom este cargado completamente
$(() => {

    $(function() {


        $('#guardar_entrada').click(function() {


            var url = "entradasstore";

            var entrada = [];

            var entradainput = {};

            entradainput.documento = $('#documento').val();
            entradainput.consecutivo = $('#consecutivo').val();
            entradainput.proveedor_id = $('#proveedor_id').val();
            entradainput.codigop = $('#codigop').val();
            entradainput.nombrep = $('#nombrep').val();
            entradainput.facturae = $('#facturae').val();
            entradainput.codigof = $('#codigof').val();
            entradainput.fecha_facturae = $('#fecha_facturae').val();
            entradainput.precio_compra_subtotal = $('#precio_compra_subtotalf').val();
            entradainput.totaliva = $('#totaliva').val();
            entradainput.precio_compra_total = $('#precio_compra_totalf').val();
            entradainput.usuario_id = $('#user_ids').val();

            entrada.push(entradainput);

            console.log(entrada);

            var entradadetalle = [];
          
            $("#tcups > tbody > tr").each(function() {
                var itemdetalle = {};
                
                // Encuentra todas las celdas en la fila actual
                var tds = $(this).find("td");


                
                
                // Obtén los valores de las celdas y guárdalos en el objeto itemdetalle
                itemdetalle.productocosdis_id = parseFloat(tds.eq(0).text());
                itemdetalle.codigo = parseFloat(tds.eq(1).text());
                itemdetalle.codigoean = tds.eq(2).text();
                itemdetalle.nombrep = tds.eq(3).text();
               
                let subtotalunitext = tds.eq(4).text();
                let subtotalunisin = subtotalunitext.replace(/[$.]/g, "");
                subtotalunisin.replace(',', '.');
                itemdetalle.precio_compra_subtotal_unitario = parseInt(subtotalunisin);
               
                let valorIvaUnitext =  tds.eq(5).text();
                let valorIvaUnisin = valorIvaUnitext.replace(/[$.]/g, "");
                valorIvaUnisin.replace(',', '.');
                itemdetalle.cantidad_iva_unitario = parseInt(valorIvaUnisin);
                
                itemdetalle.cantidad = parseInt(tds.eq(6).text());

                let subtotaltext = tds.eq(7).text();
                let subtotalsin = subtotaltext.replace(/[$.]/g, "");
                subtotalsin.replace(',', '.');
                itemdetalle.precio_compra_subtotal = parseInt(subtotalsin);


                let ivaTotaltext = tds.eq(8).text();
                let ivaTotalsin = ivaTotaltext.replace(/[$.]/g, "");
                ivaTotalsin.replace(',', '.');
                itemdetalle.cantidad_iva_total = parseInt(ivaTotalsin);
                


                let totaltext = tds.eq(9).text();
                let totalsin = totaltext.replace(/[$.]/g, "");
                totalsin.replace(',', '.');
                itemdetalle.precio_compra_total = parseInt(totalsin);

                itemdetalle.proveedor_id = $('#proveedor_id').val();
                itemdetalle.documento = $('#documento').val();
                itemdetalle.consecutivo = $('#consecutivo').val();
              


                // Agrega el objeto itemdetalle al array entradadetalle
                entradadetalle.push(itemdetalle);
            
                console.log(entradadetalle);

                
            });
            Swal.fire({
                title: "¿Estás seguro?",
                text: "Vas a realizar una entrada",
                type: "success",
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Aceptar',
            }).then((result) => {
                if (result.value) {

            Swal.fire({
                type: "info",
                    title: 'Espere por favor !',
                    html: 'Realizando la entrada..', // add html attribute if you want or remove
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    willOpen: () => {
                        Swal.showLoading()
                    },
                }),
                $.ajax({
                    beforeSend: function() {
                        $('.loader').css("visibility", "visible");
                    },
                    url:url,
                    dataType: 'json',
                    method: 'post',
                    data: {
                        data: entrada, entradadetalle,
                        "_token": $("meta[name='csrf-token']").attr("content")
                    },
                    //dataType:"json",
                    success: function(data) {
                        if (data.success == 'ya') {

                            $.each(JSON.parse(data.result), function(i, items) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: items,
                                    showConfirmButton: true,
                                    //timer: 1500
                                })

                            });
                            //$('#mipres').DataTable().destroy(); 
                        } else if (data.success == 'ok') {

                          //  $.each(JSON.parse(data.result), function(i, item) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "La entrada numero: " + 1, //item.Id,
                                    text: "Se realizo la entrada correctamente" +
                                        item.IdProgramacion,
                                    showConfirmButton: true,
                                    //timer: 1500
                                })
                          //  });
                            //$('#mipres').DataTable().destroy();

                        }

                    },
                    complete: function() {
                        $('.loader').css("visibility", "hidden");
                    }


                });

            }
        });


        })

        //    }
        //    });
    });

   



});



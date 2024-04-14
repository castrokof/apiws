$(document).ready(function(){
    $("#li").draggable();
    $('#menu').sortable({
        stop: function() {

            $.map($(this).find('li'), function(el){
                var itemId = el.id;
                var itemConsecutivo = $(el).index();

                
                $.ajax({
                    beforeSend: function(){ 
                    $('.loader').css("visibility", "visible"); }, 
                    url: "ruta",
                    type: 'post',
                    dataType: 'json',
                    data: {itemId:itemId, itemConsecutivo:itemConsecutivo,
                        "_token": $("meta[name='csrf-token']").attr("content")},
                        // success:function()
                        // { 
                        //     Manteliviano.notificaciones('Ruta actualizada exitosamente', 'Sistema Coll', 'success');
                        //     $('#menu').reload();     
                        // },
                    
                    complete: function(){ 
                    $('.loader').css("visibility", "hidden");
                    }
                     
                   })

            })
        }   

    });


});
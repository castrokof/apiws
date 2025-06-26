$(document).ready(function(){
    $('.alert[data-auto-dismiss]').each(function(index, element){
        const $element = $(element),
            timeout = $element.data('auto-dismiss') || 4000;
        setTimeout(function(){
            $element.alert('close');
            
        }, timeout);    
    });
    //TOOLTIPS
    $('body').tooltip({
        trigger: 'hover',
        selector: '.tooltipsC',
        placement: 'top',
        html: true,
        container: 'body'

    });
    
 
            document.addEventListener('DOMContentLoaded', function () {
                // Seleccionar todos los elementos con la clase dropdown-submenu
                var dropdowns = document.querySelectorAll('.dropdown-submenu .dropdown-toggle');
            
                dropdowns.forEach(function(dropdown) {
                    dropdown.addEventListener('click', function (e) {
                        e.stopPropagation(); // Prevenir la propagación del evento
            
                        // Cerrar otros submenús abiertos
                        dropdowns.forEach(function(item) {
                            if (item !== dropdown) {
                                item.nextElementSibling.classList.remove('show');
                            }
                        });
            
                        // Alternar el submenú actual
                        var subMenu = this.nextElementSibling;
                        if (subMenu) {
                            subMenu.classList.toggle('show');
                        }
                    });
                });
            
                // Cerrar el submenú si se hace clic en cualquier lugar fuera del submenú
                document.addEventListener('click', function (e) {
                    dropdowns.forEach(function(dropdown) {
                        var subMenu = dropdown.nextElementSibling;
                        if (subMenu && !dropdown.contains(e.target)) {
                            subMenu.classList.remove('show');
                        }
                    });
                });
            });


    //$('ul.#sidebar-menu').find('li.active').parents('li').addClass('active');

});
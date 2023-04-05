 <!-- Modal del resumen -->

 <div class="modal fade" tabindex="-1" id ="modal-addseguimiento"  role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">

          <div class="modal-content bg-lite">
             <div class="modal-header">
                <h5 class="modal-title-addseguimiento" id="myLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>

            </div>
        <div class="modal-body">

        <form id="form-generaladd" name="form-general" class="form-horizontal">
                @csrf
                @include('lineaPsicologica.form.formAnalistaAddSeguimiento')
        </form>
        </div>


        </div>
    </div>
</div>

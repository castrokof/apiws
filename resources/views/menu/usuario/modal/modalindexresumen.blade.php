 <!-- Modal del resumen -->

 <div class="modal fade" tabindex="-1" id ="modal-resumen"  role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-xl" role="document">

          <div class="modal-content bg-lite">
             <div class="modal-header">
                <h5 class="modal-title-resumen" id="myLargeModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>

            </div>
        <div class="modal-body">

        <form id="form-general" name="form-general" class="form-horizontal" method="post">
                @csrf


                @include('lineaPsicologica.form.formAnalista')


        </form>
        </div>


        <div class="modal-footer">
            <button type="button" id="reportare" class="btn btn-success">Reportar</button>

        </div>

        </div>
    </div>
</div>

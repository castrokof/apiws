    <div class="modal fade" tabindex="-1" id="modal-u" role="dialog" aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-lg-12">
                        @include('includes.form-error')
                        @include('includes.form-mensaje')
                        <span id="form_result"></span>
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title"></h3>
                                <div class="card-tools pull-right">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                            <form id="form-general" class="form-horizontal" method="PUT">
                                @csrf
                                <div class="card-body">
                                    @include('auth.form.form-edit')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                         <div class="row">
                                            <input type="hidden" name="action" id="action" value="Add"/>
                                            <input type="hidden" name="hidden_id" id="hidden_id" value="Add"/>
                                            <div class="col-xs-3">
                                            <input type ="submit" name="action_button" id="action_button" class="addempleado btn btn-success" value="Add"/>
                                            </div>
                                            </div>
                                    </div>
                                        
                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
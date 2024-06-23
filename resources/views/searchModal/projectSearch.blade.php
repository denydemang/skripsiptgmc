<div class="modal fade" id="modalProjectRealisationSearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleview">Project Search</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 mr-1">
                    <div class="col-6">
                        <div class="d-flex">
                            <h4>Customer Code : <span class="titlecustcode"></span></h4>
                        </div>
                    </div>
                </div>
                <table class="align-items-center table-flush projectsearchtable w-100 table">
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('/') }}js/search/projectsearch.js" type="module"></script>

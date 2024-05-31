<div class="modal fade" id="modalProjectRealisationSearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title titleview">Project Realisation Search</h5>
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

						<h4>Transaction Date</h4>
						<div class="d-flex">
							<div class="input-group date mr-2" id="dtpstarttrans" data-target-input="nearest">
								<input type="text" class="form-control form-control-sm datetimepicker-input inputstartdatetrans"
									data-target="#dtpstarttrans" style="cursor: pointer" readonly />
								<div class="input-group-append" data-target="#dtpstarttrans" data-toggle="dtpstarttrans">
									<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
							<span class="mr-1">to </span>
							<div class="input-group date" id="dtplasttrans" data-target-input="nearest">
								<input type="text" style="cursor: pointer"
									class="form-control form-control-sm datetimepicker-input inputlastdatetrans" data-target="#dtplasttrans"
									readonly />
								<div class="input-group-append" data-target="#dtplasttrans" data-toggle="dtplasttrans">
									<div class="input-group-text" style="height: 32px"><i class="fa fa-calendar"></i></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<table class="align-items-center table-flush projectrealisationsearchtable w-100 table">
				</table>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('/') }}js/search/projectRealisationSearch.js" type="module"></script>

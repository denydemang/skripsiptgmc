<div class="modal fade" id="modalItemSearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title titleview">Item Search</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="align-items-center table-flush itemsearchtable w-100 table">
					{{-- <thead class="thead-light">
						<tr>
							<th scope="col">Actions</th>
							<th scope="col">Code</th>
							<th scope="col">Name</th>
							<th scope="col">Address</th>
							<th scope="col">Zip Code</th>
							<th scope="col">Email</th>
						</tr>
					</thead>
					<tbody class="list">
					</tbody> --}}
				</table>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('/') }}js/search/searchItems.js" type="module"></script>

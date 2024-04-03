<div class="modal fade" id="modalCOASearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title titleview">COA Search</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="align-items-center table-flush COASearchtable w-100 table">
					<thead class="thead-light">
						<tr>
							<th scope="col">Actions</th>
							<th scope="col">Code</th>
							<th scope="col">Name</th>
							<th scope="col">Type</th>
							<th scope="col">Description</th>
						</tr>
					</thead>
					<tbody class="list">
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script src="{{ asset('/') }}js/search/searchCOA.js" type="module"></script>
{{-- <script src="{{ asset('/') }}js/search/itemSearch.js" type="module"></script> --}}

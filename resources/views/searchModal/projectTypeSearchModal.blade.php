<div class="modal fade" id="modalProjectTypeSearch" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title titleview">Project Type Search</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="align-items-center table-flush projecttypesearchtable w-100 table">
					<thead class="thead-light">
						<tr>
							<th scope="col">Actions</th>
							<th scope="col">Code</th>
							<th scope="col">Name</th>
							<th scope="col">Description</th>
							<th scope="col">Updated By</th>
							<th scope="col">Created By</th>
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
<script src="{{ asset('/') }}js/search/projectTypeSearch.js" type="module"></script>

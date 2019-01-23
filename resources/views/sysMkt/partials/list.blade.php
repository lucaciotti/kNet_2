
@if (count($sysMkt))
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title" data-widget="collapse">System List</h3>
		<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body">

		<table class="table table-hover table-condensed dtTbls_full">
			<thead>
				<th>Codice</th>
				<th>Descrizione</th>
				<th>Url Sito Mkt</th>
				<th>&nbsp;</th>
			</thead>
			<tbody>
				@foreach ($sysMkt as $sys)
					<tr>
						<td><big>{{ $sys->codice }}</big></td>
						<td>{{ $sys->descrizione }}</td>
						<td>{{ $sys->url }}</td>
						<td>
							<form action="{{route('sysMkt::sysMkt.destroy', $sys->codice)}}" method="POST">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}

								<button type="submit" id="delete-user-{{ $sys->codice }}" class="btn btn-danger">
									<i class="fa fa-btn fa-trash"></i>
								</button>
							</form>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@endif
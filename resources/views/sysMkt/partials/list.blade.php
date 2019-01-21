
@if (count($sysMkt))
<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title" data-widget="collapse">System List</h3>
		<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body">

		<ul>
			@foreach ($sysMkt as $sys)
				<li>
					<a href="#">{{ $sys->codice }}</a> - {{ $sys->descrizione}}
				</li>
			@endforeach
		</ul>
		<hr>

	</div>
</div>

@endif
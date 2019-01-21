<div class="box box-default">
	<div class="box-header with-border">
		<h3 class="box-title" data-widget="collapse">System Create</h3>
		<div class="box-tools pull-right">
		<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
		</div>
	</div>
	<div class="box-body">

        <form action="{{ route('sysMkt::sysMkt.store') }}" method="POST" @submit.prevent='onSubmit' @keydown="form.errors.clear($event.target.name)">
              {{ csrf_field() }}

            <div class="form-group">
              <label>Codice System</label>
              <input type="text" class="form-control" name="codice" value="" placeholder="Codice System" v-model='form.codice'>
              <span class="help is-danger" v-if="form.errors.has('codice')" v-text="form.errors.get('codice')"></span>
            </div>

            <div class="form-group">
              <label>Descrizione System</label>
              <input type="text" class="form-control" name="descrizione" value="" placeholder="Descrizione System" v-model='form.descrizione'>
              <span class="help is-danger" v-if="form.errors.has('descrizione')" v-text="form.errors.get('descrizione')"></span>
            </div>

            <div>
              <button type="submit" class="btn btn-primary" :disabled="form.errors.any()">{{ trans('_message.submit') }}</button>
            </div>
        </form>
    
    </div>
</div>
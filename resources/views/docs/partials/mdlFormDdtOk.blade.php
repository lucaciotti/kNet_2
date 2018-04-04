<button class="btn btn-success btn-block" data-toggle="modal" data-target=".bs-modal">{{ trans('doc.confirmReceived') }}</button>

<div class="modal fade bs-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('doc.confirmReceived') }}</h4>
      </div>
      <form action="{{ route('ddtConfirm', $head->id) }}" method="POST">
          {{ csrf_field() }}
          {{-- {{ method_field('PUT') }} --}}
          <div class="modal-body">
            <div class="form-group">
              <label>{{ trans('doc.document') }}</label>
              <input type="text" class="form-control" name="doc" disabled value="{{$head->tipodoc}} {{$head->numerodoc}}">
            </div>
            <div class="form-group">
              <label>{{ trans('doc.sign') }}</label>
              <input type="text" class="form-control" name="firma" value="" placeholder="Firma">
            </div>
            <div class="form-group">
              <label>{{ trans('doc.noteReceived') }}</label>
              <textarea class="form-control" rows="3" name="note" placeholder="Scrivi eventuali note &hellip;"></textarea>
            </div>
            <input type="hidden" name="id_testa" value="{{$head->id}}">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ trans('doc.closePage') }}</button>
            <button type="submit" class="btn btn-primary">{{ trans('doc.savePage') }}</button>
          </div>
      </form>
    </div>
  </div>
</div>

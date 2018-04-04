<table class="table table-hover table-condensed dtTbls_full" id="listDocs">
  <thead>
    <th>{{ trans('prod.codeArt') }}</th>
    <th>{{ trans('prod.descArt') }}</th>
    <th>{{ trans('prod.groupProd') }}</th>
    <th>{{ trans('prod.listPrice') }}</th>
  </thead>
  <tbody>
    @foreach ($products as $prod)
      <tr>
        <td><a href="#"> {{ $prod->codice }} </a>
          @if($prod->u_perscli==1)
            &nbsp&nbsp<small class="label bg-yellow">Personal.</small>
          @endif
        </td>
        <td>
          {{ $prod->descrizion }}
        </td>
        <td>[{{ $prod->gruppo }}]
          @if($prod->grpProd)
            {{ $prod->grpProd->descrizion }}
          @endif
          &nbsp&nbsp<small class="label bg-green">{{ $prod->tipo_prod }}</small>
        </td>
        <td>{{ $prod->listino }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
{{--
@push('scripts')
    <script>
    $(document).ready(function() {
      $('#listDocs').DataTable( {
          "order": [[ 3, "desc" ]]
      } );
    } );
    </script>
@endpush --}}

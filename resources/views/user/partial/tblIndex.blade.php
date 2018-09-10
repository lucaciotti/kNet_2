<table class="table table-hover table-condensed dtTbls_full">
  <thead>
    <th>&nbsp;</th>
    <th>{{ trans('user.name') }}</th>
    <th>{{ trans('user.eMail') }}</th>
    <th>{{ trans('user.role') }}</th>
    <th>{{ trans('user.codAg') }}</th>
    <th>{{ trans('user.codCli') }}</th>
    <th>isActive?</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </thead>
  <tbody>
      @foreach ($users as $user)
        <tr>
          <td>
            <a href="{{ route('user::actLike', $user->id ) }}">
              <button type="submit" id="act-user-{{ $user->id }}" class="btn btn-warning">
                  <i class="fa fa-btn fa-user-secret">
                  </i>
              </button>
            </a>
          </td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>@foreach ($user->roles as $role)
            {{ $role->display_name }}
          @endforeach</td>
          <td>@if (!empty($user->codag))
            {{ $user->codag }} - {{ $user->agent->descrizion or 'NONE' }}
          @endif</td>
          <td>@if (!empty($user->codcli))
            {{ $user->codcli }}
            {{-- {{ $user->codcli }} - {{ $user->client->descrizion }} --}}
          @endif</td>
          <td>
            @if ($user->isActive)
              Si
            @else
              No
            @endif
          </td>
          <td>
            <a href="{{ route('user::users.edit', $user->id ) }}">
              <button type="submit" id="edit-user-{{ $user->id }}" class="btn">
                  <i class="fa fa-btn fa-pencil">
                  </i>
              </button>
            </a>
          </td>
          <td>
            <form action="{{ route('user::users.destroy', $user->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" id="delete-user-{{ $user->id }}" class="btn btn-danger">
                    <i class="fa fa-btn fa-trash"></i>
                </button>
            </form>
          </td>
        </tr>
      @endforeach

  </tbody>
</table>

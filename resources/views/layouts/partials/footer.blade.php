<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        {{-- <a href="{{ url("/todo") }}">#ToDo List...</a> --}}
    </div>
    <!-- Default to the left -->
    <strong>
      Copyright &copy; {{ Carbon\Carbon::now()->year }}
      <a href="http://www.k-group.com">K-Group</a>.
    </strong>
    {{ trans('_message.createdby') }}
    {{-- <a href="https://github.com/lucaciotti">Luca Ciotti</a>. --}}
    <a href="#">Luca Ciotti</a>.
</footer>

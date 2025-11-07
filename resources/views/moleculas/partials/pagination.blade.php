@if ($moleculas->hasPages())
  <div class="d-flex justify-content-center">
    {!! $moleculas->onEachSide(1)->links() !!}
  </div>
@endif

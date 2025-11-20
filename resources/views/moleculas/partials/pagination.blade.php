@if ($moleculas->hasPages())
  <div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">
      Mostrando {{ $moleculas->firstItem() ?? 0 }} a {{ $moleculas->lastItem() ?? 0 }} de {{ $moleculas->total() }} registros
    </div>
    <div>
      {!! $moleculas->onEachSide(1)->links() !!}
    </div>
  </div>
@endif

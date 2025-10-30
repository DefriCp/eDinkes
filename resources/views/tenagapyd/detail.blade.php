@props(['label','value'=>null])

<div class="p-3 rounded border bg-white">
  <div class="text-xs text-slate-500">{{ $label }}</div>
  <div class="font-medium">
    {{ ($value !== null && $value !== '') ? $value : '—' }}
  </div>
</div>

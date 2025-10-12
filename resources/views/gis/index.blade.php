@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="mb-4 flex items-center gap-2">
  <form method="get">
    <select name="month" class="border rounded px-3 py-2 bg-white">
      @for($m=1; $m<=12; $m++)
        <option value="{{ $m }}" {{ $m==$month?'selected':'' }}>
          {{ ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][$m] }}
        </option>
      @endfor
    </select>
    <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2 w-24" />
    <button class="px-4 py-2 rounded bg-blue-700 text-white">Tampilkan</button>
    <span class="ml-4 text-sm text-gray-600">Layer:
      <select id="metric" class="border rounded px-2 py-1">
        <option value="idl_pct">IDL %</option>
        <option value="k1_pct">K1 %</option>
        <option value="k4_pct">K4 %</option>
        <option value="dbd_cases">Kasus DBD</option>
        <option value="visits">Kunjungan</option>
      </select>
    </span>
  </form>
</div>

<div id="map" style="height: 640px;" class="rounded-lg shadow border"></div>

<script>
const month = {{ $month }};
const year  = {{ $year }};
let choroplethLayer, facilityLayer, legend;
const map = L.map('map').setView([-7.35,108.22], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18,attribution:'&copy; OpenStreetMap'}).addTo(map);

function getColor(val, field){
  if (val == null) return '#cccccc';
  if (field.endsWith('_pct')) {
    return val >= 90 ? '#14532d' : val >= 80 ? '#15803d' : val >= 70 ? '#22c55e' : val >= 60 ? '#86efac' : '#dcfce7';
  } else {
    return val >= 2000 ? '#7f1d1d' : val >= 1000 ? '#b91c1c' : val >= 500 ? '#ef4444' : val >= 100 ? '#fecaca' : '#fee2e2';
  }
}
function styleBy(field){ return f=>({weight:1,color:'#666',fillColor:getColor(f.properties[field],field),fillOpacity:0.8}); }
function onEach(f, layer){
  const p=f.properties, nm=p.nama||p.NAMA||'Kecamatan';
  layer.bindPopup(`<b>${nm}</b><br>ID L%: ${p.idl_pct??'-'}<br>K1%: ${p.k1_pct??'-'}<br>K4%: ${p.k4_pct??'-'}<br>DBD: ${p.dbd_cases??0}<br>Kunjungan: ${p.visits??0}`);
}

async function loadChoropleth(field='idl_pct'){
  const res = await fetch(`{{ url('/gis/geojson') }}?month=${month}&year=${year}`);
  const gj = await res.json();
  if (choroplethLayer) choroplethLayer.remove();
  choroplethLayer = L.geoJSON(gj, { style: styleBy(field), onEachFeature: onEach }).addTo(map);
  map.fitBounds(choroplethLayer.getBounds());

  if (legend) legend.remove();
  legend = L.control({position:'bottomright'});
  legend.onAdd = function(){
    const div = L.DomUtil.create('div','info legend');
    div.style.background='white'; div.style.padding='8px 10px'; div.style.border='1px solid #ddd'; div.style.borderRadius='8px';
    div.innerHTML = `<b>${field.toUpperCase()}</b><br><span class="text-xs">Bulan ${month}/${year}</span>`;
    return div;
  };
  legend.addTo(map);
}

async function loadFacilities(){
  const res = await fetch(`{{ url('/gis/facilities') }}`);
  const rows = await res.json();
  if (facilityLayer) facilityLayer.remove();
  facilityLayer = L.layerGroup(rows.filter(r=>r.lat && r.lng).map(r =>
    L.marker([r.lat, r.lng]).bindPopup(`<b>${r.name}</b><br>${r.type}<br><small>${r.address??''}</small>`)
  )).addTo(map);
}

loadChoropleth('idl_pct');
loadFacilities();
document.getElementById('metric').addEventListener('change', e => loadChoropleth(e.target.value));
</script>
@endsection

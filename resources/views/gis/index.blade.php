@extends('layouts.app-dashboard')
@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="mb-4 flex items-center gap-3 flex-wrap">
  <form method="get" class="flex items-center gap-2">
    <select name="month" class="border rounded px-3 py-2 bg-white">
      @for($m=1; $m<=12; $m++)
        <option value="{{ $m }}" {{ $m==$month?'selected':'' }}>
          {{ ['','Okt','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][$m] }}
        </option>
      @endfor
    </select>
    <input type="number" name="year" value="{{ $year }}" class="border rounded px-3 py-2 w-24" />
    <button class="px-4 py-2 rounded bg-blue-700 text-white">Tampilkan</button>
  </form>

  <div class="flex items-center gap-2">
    <span class="text-sm text-gray-600">Layer:</span>
    <select id="metric" class="border rounded px-2 py-1">
      <option value="idl_pct">IDL %</option>
      <option value="k1_pct">K1 %</option>
      <option value="k4_pct">K4 %</option>
      <option value="dbd_cases">Kasus DBD</option>
      <option value="visits">Kunjungan</option>
    </select>
  </div>

  <div class="flex items-center gap-2">
    <span class="text-sm text-gray-600">Kecamatan:</span>
    <select id="kecSelect" class="border rounded px-2 py-1 min-w-56">
      <option value="">— Semua kecamatan —</option>
    </select>
    <button id="clearHi" type="button" class="px-3 py-1.5 border rounded bg-white">Bersihkan highlight</button>
  </div>
</div>

<div id="map" style="height: 640px;" class="rounded-lg shadow border"></div>

<script>
const month = {{ $month }};
const year  = {{ $year }};
let polyLayer, legend, lastHighlight = null;

const map = L.map('map').setView([-7.35,108.22], 10);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:18,attribution:'&copy; OpenStreetMap'}).addTo(map);

// Palet 3 tingkat (hijau = baik, kuning = sedang, merah = rendah/buruk)
const COLORS = { good:'#1f7a3d', mid:'#f2b01c', bad:'#c92a2a', nulls:'#e5e7eb' };
const HIGH_IS_GOOD = new Set(['idl_pct','k1_pct','k4_pct']);
const THRESH = { pct:{hi:80, mid:60}, cnt:{hi:100, mid:40} };

function colorTri(v, field){
  if (v == null) return COLORS.nulls;
  const isPct = field.endsWith('_pct');
  const t = isPct ? THRESH.pct : THRESH.cnt;
  const highIsGood = HIGH_IS_GOOD.has(field);
  if (highIsGood){
    if (v >= t.hi) return COLORS.good;
    if (v >= t.mid) return COLORS.mid;
    return COLORS.bad;
  } else {
    if (v >= t.hi) return COLORS.bad;
    if (v >= t.mid) return COLORS.mid;
    return COLORS.good;
  }
}
function styleBy(field){ return f=>({weight:1,color:'#ffffff',fillColor:colorTri(f.properties?.[field],field),fillOpacity:0.88}); }

function makeLegend(title, field){
  if (legend) legend.remove();
  const isPct = field.endsWith('_pct');
  const t = isPct ? THRESH.pct : THRESH.cnt;
  const highIsGood = HIGH_IS_GOOD.has(field);
  const rows = highIsGood
    ? [[COLORS.good,`≥ ${t.hi}${isPct?'%':''}`],[COLORS.mid,`${t.mid}${isPct?'%':''} – ${t.hi-1}${isPct?'%':''}`],[COLORS.bad,`< ${t.mid}${isPct?'%':''}`]]
    : [[COLORS.bad,`≥ ${t.hi}`],[COLORS.mid,`${t.mid} – ${t.hi-1}`],[COLORS.good,`< ${t.mid}`]];
  legend = L.control({position:'bottomright'});
  legend.onAdd = () => {
    const div = L.DomUtil.create('div','legend');
    div.style.cssText='background:#fff;padding:8px 10px;border:1px solid #ddd;border-radius:8px;line-height:1.3;box-shadow:0 2px 10px rgba(0,0,0,.08)';
    div.innerHTML = `<b>${title}</b><br><span style="font-size:12px">Bulan ${month}/${year}</span>` +
      rows.map(([c,l])=>`<div style="display:flex;align-items:center;gap:6px;margin-top:4px">
        <span style="width:14px;height:14px;border:1px solid #999;background:${c};display:inline-block"></span><span>${l}</span>
      </div>`).join('');
    return div;
  };
  legend.addTo(map);
}

function attachHover(layer){
  layer.on({
    mouseover: (e)=>{ const l=e.target; if (l===lastHighlight) return; l.setStyle({weight:2,color:'#fff',fillOpacity:1}); l.bringToFront?.(); },
    mouseout:  (e)=>{ if (polyLayer && polyLayer.resetStyle && e.target!==lastHighlight) polyLayer.resetStyle(e.target); }
  });
}

async function getJSON(url){
  const r = await fetch(url, {credentials:'same-origin'});
  const txt = await r.text();
  let j = null; try{ j = JSON.parse(txt); }catch(e){}
  return {ok:r.ok, status:r.status, json:j, raw:txt};
}
function ensureFC(res, ep){
  if (!res.ok || !res.json || res.json.type!=='FeatureCollection' || !Array.isArray(res.json.features)) {
    console.error('GIS Fetch failed', {ep, status:res.status, raw:res.raw});
    alert(`Gagal memuat data peta (${ep}). Cek console (F12).`);
    return false;
  }
  return true;
}

function populateKecSelect(){
  const sel = document.getElementById('kecSelect');
  sel.innerHTML = `<option value="">— Semua kecamatan —</option>`;
  const names = [];
  polyLayer.eachLayer(l => {
    const n = (l.feature?.properties?._nama_disp || '').toString();
    if (n) names.push(n);
  });
  names.sort((a,b)=>a.localeCompare(b,'id'));
  names.forEach(n=>{
    const opt=document.createElement('option'); opt.value=n; opt.textContent=n; sel.appendChild(opt);
  });
}

function highlightKecamatan(name){
  lastHighlight = null;
  if (!name){ polyLayer.setStyle(styleBy(document.getElementById('metric').value)); return; }

  let targetBounds = null;
  polyLayer.eachLayer(l => {
    const nm = (l.feature?.properties?._nama_disp || '').toString().toLowerCase();
    if (nm === name.toLowerCase()){
      lastHighlight = l;
      l.setStyle({weight:3,color:'#111827',fillOpacity:0.95});
      targetBounds = l.getBounds();
      l.bringToFront?.();
    } else {
      l.setStyle({weight:1,color:'#ffffff',fillOpacity:0.35});
    }
  });
  if (targetBounds) map.fitBounds(targetBounds, {padding:[20,20]});
}

// ——— load poligon dari satu endpoint: gis.geojson ———
async function loadMetricPolygon(field){
  const res = await getJSON(`{{ route('gis.geojson') }}?month=${month}&year=${year}`);
  if (!ensureFC(res, 'gis.geojson')) return;
  if (polyLayer) polyLayer.remove();
  polyLayer = L.geoJSON(res.json, { style:styleBy(field), onEachFeature:(f,layer)=>{
    const p=f.properties, nm=p._nama_disp||p.nama||'Kecamatan';
    layer.bindPopup(`<b>${nm}</b><br>
      IDL: ${p.idl_pct??'-'}% | K1: ${p.k1_pct??'-'}% | K4: ${p.k4_pct??'-'}%<br>
      DBD: ${p.dbd_cases??0} | Kunjungan: ${p.visits??0}`);
    attachHover(layer);
  }}).addTo(map);
  try{ map.fitBounds(polyLayer.getBounds()); }catch(e){}
  makeLegend(field.toUpperCase(), field);
  populateKecSelect();
  const urlKec = new URLSearchParams(location.search).get('kec');
  if (urlKec) highlightKecamatan(urlKec);
}

// driver
const metricSelect = document.getElementById('metric');
async function loadByMetric(m){ return loadMetricPolygon(m); }
loadByMetric(metricSelect.value);
metricSelect.addEventListener('change', e=>loadByMetric(e.target.value));

// interaksi dropdown kecamatan
document.getElementById('kecSelect').addEventListener('change', e=>{
  const name = e.target.value || '';
  if (!polyLayer) return;
  if (name===''){
    polyLayer.setStyle(styleBy(document.getElementById('metric').value));
    lastHighlight = null;
    try{ map.fitBounds(polyLayer.getBounds()); }catch(e){}
  } else {
    highlightKecamatan(name);
  }
});
document.getElementById('clearHi').addEventListener('click', ()=>{
  document.getElementById('kecSelect').value='';
  polyLayer.setStyle(styleBy(document.getElementById('metric').value));
  lastHighlight = null;
  try{ map.fitBounds(polyLayer.getBounds()); }catch(e){}
});
</script>
@endsection

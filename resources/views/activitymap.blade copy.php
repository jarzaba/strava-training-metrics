@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="width: 1024px;">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if ($tokenExists)

                    <div id="mapid"></div>


                    @else
                    <p><a href="{{ url('/authenticate') }}">Fetch your activities from Strava</a></p>
                    <p>To use this app, you need to log into your Strava account and give permissions to fetch them</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Tässä hyödynnetään
    // Alustetaan kartan leveys- ja korkeuspiirit sopiviksi
    var InitialCoord = {
        lat: 61,
        lng: 25
    };

    // Haetaan oikea karttaprojektio, muunnetaan web-mercator -> ERTS-TM35FIN,
    // Tuloksena saadaan MML-maastokartta näkymään oikein
    // Tätä varten tarvitaan Proj4 -kirjasto
    function getCRStm35() {
        var bounds, crsName, crsOpts, originNw, projDef, zoomLevels;
        zoomLevels = [8192, 4096, 2048, 1024, 512, 256, 128, 64, 32, 16, 8, 4, 2, 1, 0.5, 0.25];
        crsName = 'EPSG:3067';
        projDef = '+proj=utm +zone=35 +ellps=GRS80 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs';
        bounds = L.bounds(L.point(-548576, 6291456), L.point(1548576, 8388608));
        originNw = L.point(bounds.min.x, bounds.max.y);
        crsOpts = {
            resolutions: zoomLevels,
            bounds: bounds,
            transformation: new L.Transformation(1, -originNw.x, -1, originNw.y)
        };
        return new L.Proj.CRS(crsName, projDef, crsOpts);
    }

    function createTileLayer() {
        return L.tileLayer("https://avoin-karttakuva.maanmittauslaitos.fi/avoin/wmts/1.0.0/maastokartta/default/ETRS-TM35FIN/{z}/{y}/{x}.png", {
            attribution: '&copy; Karttamateriaali <a href="http://www.maanmittauslaitos.fi/avoindata">Maanmittauslaitos</a>',
            continuousWorld: true,
            maxZoom: 8192,
            minZoom: 1,
            subdomains: ['tile1', 'tile2']
        });
    }

    function makeMap(elem, defaultBasemap) {
        var map;

        var defaultOpts = {
            center: [InitialCoord.lat, InitialCoord.lng],
        }

        opts = defaultOpts;
        opts.crs = getCRStm35();
        opts.zoom = 6;
        opts.layers = defaultBasemap

        map = L.map(elem, opts);

        return map;
    }

    var taustakartta = createTileLayer("taustakartta_3067");
    var map = makeMap("mapid", taustakartta);
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="width: 1024px; margin: auto;">
            <div class="card-header">
                <a href="{{ url('/home') }}"> &lt;&lt; back </a>
                | Your {{ $activity->distance/1000 }} km {{ strtolower($activity->type) }} on {{ $activity->start_date_local }}</div>
                <div class="card-body">
                    <div id="mapid" style="width: 900px; height: 700px; margin: auto;"></div>


                    <?php
                    $polyline = $activity->map->polyline;
                    //$polyline_escaped = preg_replace('*\\\*', '\\\\', $polyline);
                    ?>
                    <script>



                        // Tässä hyödynnetään
                        // Alustetaan kartan leveys- ja korkeuspiirit sopiviksi

                        //console.log(encoded);
                        var InitialCoord = {lat: 61, lng: 25};

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
                                subdomains: ['tile1','tile2']
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


                        var taustakartta = createTileLayer();
                        var map = makeMap("mapid", taustakartta);

                        // Liitetään aktiviteetin reitti kartalle
                        // php-muuttuja merkkijonona javascriptiin
                        var imported_polyline = "<?php echo $polyline ?>";
                        // ainakin yritys saada kenoviivat pysymään tavallisina merkkeinä
                         var activity_polyline = imported_polyline.replace(/\\/ig, "\\\\");
                        // taikojen avulla muutetaan polyline latitudeksi ja longitudeksi
                        console.log(activity_polyline);
                        console.log(imported_polyline);
                        var coordinates = L.Polyline.fromEncoded(activity_polyline).getLatLngs();
                        // piirretään lat&lng koordinaattien avulla reitti kartalle
                        var polyline = L.polyline(coordinates,
                                                        {
                                                            color: 'blue',
                                                            weight: 10,
                                                            opacity: 1,
                                                            //dashArray: '20,15',
                                                            lineJoin: 'round'
                                                        }
                                                        ).addTo(map);
                        // Kohdennetaan kartta reitin mukaan
                        map.fitBounds(polyline.getBounds());

                        // Tässä on vielä jotain ongelmaa. Reitit eivät tulostu täysin oikein.
                        // Joko reitin projektio ei täsmää kartan kanssa tai polylinen merkkijono ei ole ihan kohdillaan.



                </script>

</div>
</div>
</div>
</div>
</div>
@endsection


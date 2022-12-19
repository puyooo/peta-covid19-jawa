<style>
    #mapid {
        width: 900px;
        height: 500px;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    .leaflet-popup-content {
        font-size: larger;
    }
    #sidenav { 
        margin: 7rem 2.5rem 1.5rem 1.5rem;
        vertical-align: top;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    }
    #sidenav a:hover {
        background-color: #ddd;
    }
</style>
<div id="mainContainer" class="container-fluid p-4">
    <!-- SIDE NAVIGASI -->
    <nav id="sidenav" class="nav flex-column d-inline-block border rounded p-2 font-weight-bold bg-white">
        <div class="dropdown-divider"></div>
        <?php foreach ($provinsi as $provinsi_item): ?>

            <a id="<?= strtolower(str_replace(' ', '_', esc($provinsi_item->nama_provinsi))) ?>" class="nav-link text-dark" href="javascript:;"><?= $provinsi_item->nama_provinsi; ?></a>
            <div class="dropdown-divider"></div>

        <?php endforeach; ?>
    </nav>

    <!-- PETA PENYEBARAN COVID -->
    <div id="content" class="d-inline-block text-center">
        <h1>Penyebaran COVID-19 di Provinsi Pulau Jawa</h1>
        <div id="mapid" class="mt-4"></div>
    </div>
</div>

<script>
    var mymap = L.map('mapid').setView([-7.140183, 109.894131], 7); //lokasi awal dan tingkat zoom peta saat website dibuka

<?php $data_provinsi = array_column($data_covid['list_data'], 'key'); ?> //membuat array berisi data provinsi dalam array '$data_covid' dengan key 'list_data'
<?php foreach ($provinsi as $provinsi_item): ?>
    <?php
    $nama_provinsi = strtolower(str_replace(' ', '_', esc($provinsi_item->nama_provinsi))); //nama provinsi diformat untuk dijadikan variable js
    //mencari data provinsi didalam array api covid-19 berdasarkan nama provinsi
    $key_provinsi = array_search(strtoupper(esc($provinsi_item->nama_provinsi)), $data_provinsi); //nama diformat agar bentuk sama dengan array api covid-19
    //ambil data provinsi dari array api covid-19
    $update = $data_covid['last_date'];
    $positif = $data_covid['list_data'][$key_provinsi]['jumlah_kasus'];
    $sembuh = $data_covid['list_data'][$key_provinsi]['jumlah_sembuh'];
    $meninggal = $data_covid['list_data'][$key_provinsi]['jumlah_meninggal'];

    //menyetting warna polygon dalam peta berdasarkan jumlah positif
    if ($positif <= 100000) {
        $color = 'green';
    } elseif ($positif > 100000 && $positif <= 200000) {
        $color = 'yellow';
    } elseif ($positif > 200000 && $positif <= 300000) {
        $color = 'orange';
    } elseif ($positif > 300000 && $positif <= 500000) {
        $color = 'red';
    } elseif ($positif > 500000) {
        $color = 'purple';
    } else {
        $color = 'blue';
    }
    ?>

        //membentuk polygon leafletjs per provinsi
        var <?= $nama_provinsi ?> = L.polygon([
    <?= esc($provinsi_item->polygon); ?> //posisi dan bentuk polygon per provinsi
        ], {color: '<?= $color ?>'}).addTo(mymap);

        //popup data untuk per polygon provinsi leafletjs
    <?= $nama_provinsi ?>.bindPopup(data_provinsi("<?= $update ?>", "<?= esc($provinsi_item->nama_provinsi) ?>", "<?= number_format($positif, 0, ',', '.') ?>", "<?= number_format($sembuh, 0, ',', '.') ?>", "<?= number_format($meninggal, 0, ',', '.') ?>"));

        //function klik pada side navigasi
        $(document).ready(function () {
            $("#<?= $nama_provinsi ?>").click(function () {
                //popup standalone leafletjs untuk side navigasi
                L.popup()
                        .setLatLng(<?= esc($provinsi_item->posisi_popup) ?>) //posisi popup pada peta
                        .setContent(data_provinsi("<?= $update ?>", "<?= esc($provinsi_item->nama_provinsi) ?>", "<?= number_format($positif, 0, ',', '.') ?>", "<?= number_format($sembuh, 0, ',', '.') ?>", "<?= number_format($meninggal, 0, ',', '.') ?>"))
                        .openOn(mymap);
            });
        });

<?php endforeach; ?>

//function untuk membuat data popup covid per provinsi
    function data_provinsi(update, provinsi, positif, sembuh, meninggal) {
        return "\
                Update : <b>" + update + "</b><br>\
                Provinsi : <b>" + provinsi + "</b><br>\
                Positif : <span style='color:red'><b>" + positif + "</span></b><br>\
                Sembuh : <span style='color:green'><b>" + sembuh + "</span></b><br>\
                Meninggal : <span style='color:purple'><b>" + meninggal + "</span></b><br>\
                            ";
    }

    //api openstreetmap untuk membuat peta
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1Ijoic2VhbGxpb256eiIsImEiOiJja3I0dGI2cmowazAyMndsdHU1cGJhMHB2In0.WfJ4LrFTHRC6gFOKPhuadQ' //token api
    }).addTo(mymap);
</script>

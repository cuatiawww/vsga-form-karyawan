<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form karyawan</title>
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/style.css">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,100;1,700&display=swap"
        rel="stylesheet">
</head>

<?php
//Library random generate untuk token
require_once "vendor/autoload.php";
$token = new RandomLib\Factory;
$generator = $token->getGenerator(new SecurityLib\Strength(SecurityLib\Strength::MEDIUM));

$listAgama = ["Kristen", "Hindu", "Katolik", "Islam", "Budha", "Konghuchu"];
rsort($listAgama); //mengurutkan array dari yang terbesar
$listGolongan = ["I", "II", "III"];

$fileJson = './data/data_karyawan.json'; //menampung/menyimpan data karyawan 
$dataKaryawan = []; //data sementara sebelum masuk ke file json

//membaca file json
$dataJson = file_get_contents($fileJson); //baca file json
$dataKaryawan = json_decode($dataJson, true);

if (isset($_GET['btnSave'])) {
    //mengambil/memproses data
    $nik = $_GET['nik'];
    $nama = $_GET['nama'];
    $jekel = $_GET['jekel'];
    $agama = $_GET['agama'];
    $gol = $_GET['gol'];
    $gapok = $_GET['gapok'];

    //membuat array associative baru
    $dataBaru = [
        "nik" => $nik,
        "nama" => $nama,
        "jekel" => $jekel,
        "agama" => $agama,
        "gol" => $gol,
        "gapok" => $gapok,
    ];
    //memasukkan object dataBaru ke dataKaryawan
    array_push($dataKaryawan, $dataBaru);
    //mengubah array ke json
    $dataToJson = json_encode($dataKaryawan, JSON_PRETTY_PRINT);
    //menulis ke file Json
    file_put_contents($fileJson, $dataToJson);
}
?>

<body>
    <!-- User Interface Form Karyawan -->
    <div class="container mt-5 bg-white">
        <div class="row">
            <div class="col-sm-8 bg-dark text-white p-4 rounded-5">
                <h1 class="text-center">Form Karyawan</h1>
                <div class="d-grid col-12 mx-auto bg-black text-white p-4 rounded-5">
                    <form action="" method="get">
                        <div>
                            <div class="form-outline col">
                                <label class="form-label mb-1" for="nik">NIK</label>
                                <input type="text" name="nik" id="nik" class="form-control bg-dark text-white">
                            </div>
                            <div class=" mt-2">
                                <label class="form-label mb-1">Nama</label>
                                <input type="text" name="nama" id="nama" class="form-control bg-dark text-white">
                            </div>
                            <div class="row justify-content-start mt-2">
                                <div class="col ">
                                    <label class="form-label mb-1">Jenis Kelamin</label>
                                    <select class="form-select bg-dark text-white" name="jekel" id="jekel">
                                        <option value="1">Laki-Laki</option>
                                        <option value="0">Perempuan</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1">Agama</label>
                                    <select class="form-select bg-dark text-white" name="agama" id="agama">
                                        <?php
                                        //melakukan looping pada array agama dan menampilkannya.
                                        foreach ($listAgama as $agama) {
                                            echo "<option value='$agama'>$agama</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-start mt-2">
                                <div class="col">
                                    <label class="form-label mb-1">Golongan</label>
                                    <select class="form-select box-gol bg-dark text-white" name="gol" id="gol">
                                        <?php
                                        //melakukan looping pada array golongan dan menampilkannya.
                                        foreach ($listGolongan as $gol) {
                                            echo "<option value='$gol'>$gol</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label class="form-label mb-1">Gaji Pokok</label>
                                    <input type="text" name="gapok" id="gapok" class="form-control bg-dark text-white">
                                </div>
                            </div>
                        </div>
                        <div class="d-grid col mx-auto mt-4">
                            <button class="btn btn-primary" type="submit" name="btnSave" id="btnSave">Save</button>
                        </div>
                    </form>
                </div>

                <hr>
                <!-- Tabel data karyawan -->
                <table class=" table table-dark table-hover mx-auto">
                    <tr class="text-center">
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Agama</th>
                        <th>Golongan</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Pajak</th>
                        <th>Total Gaji</th>
                        <th>Token</th>
                    </tr>
                    <?php
                    include("fitur.php"); //memanggil file fitur.php yang berisi function hitung tunjangan pajak, dan total gaji
                    //looping array dataKaryawan, lalu variable karyawan akan mewakili data/nilai pada array
                    foreach ($dataKaryawan as $karyawan) {
                        $tunjangan = null; //inisialisasi tunjangan 
                        //memanggil function tunjangan, hitungPajak, dan hitungTotGaji, lalu menampungnya di dalam variabel yang sudah dibuat.
                        $tunjangan = tunjangan($karyawan['gol'], $tunjangan);
                        $pajak = hitungPajak($karyawan['gapok'], $tunjangan);
                        $totGaji = hitungTotalGaji($karyawan['gapok'], $tunjangan, $pajak);

                        //mencetak baris tabel dengan berisi data karyawan + token generate
                        echo "<tr style='text-align:center;'>
                            <td>{$karyawan['nik']}</td>
                            <td>{$karyawan['nama']}</td>
                            <td>" . (($karyawan['jekel'] == '1') ? 'Laki-Laki' : 'Perempuan') . "</td> 
                            <td>{$karyawan['agama']}</td>
                            <td>{$karyawan['gol']}</td>
                            <td>{$karyawan['gapok']}</td>
                            <td>$tunjangan</td>
                            <td>$pajak</td>
                            <td>$totGaji</td>
                            <td>{$generator->generateString(8, 'abcdefg12345')}</td>
                        </tr>";
                    }
                    ?>
                </table>
            </div>
            <!-- Logo Perusahaan -->
            <div class="col-sm-4 p-2">
                <img src="./images/gambar1.png" width="400" class=" img-fluid">
                <h3 class="text-black text-center">Selamat Datang di Perusahaan CUA.Corp</h3>
            </div>
        </div>
    </div>
    <script src="./js/bootstrap.js"></script>
</body>

</html>
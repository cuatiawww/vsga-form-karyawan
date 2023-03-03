<?php
//membuat function untuk menghitung pajak, total gaji, dan menentukan tunjangan sesuai golongan
function hitungPajak($gapok, $tunjangan)
{
    $pajak = ($gapok + $tunjangan) * 0.05;
    return $pajak;
}
function hitungTotalGaji($gapok, $tunjangan, $pajak)
{
    $totGaji = $gapok + $tunjangan - $pajak;
    return $totGaji;
}
function tunjangan($gol, $tunjangan)
{
    if ($gol == 'I') {
        $tunjangan = 1000000;
    } elseif ($gol == 'II') {
        $tunjangan = 2000000;
    } else {
        $tunjangan = 3000000;
    }
    return $tunjangan;
}
?>
<?php

session_start();
include 'setting.php';
require_once ('../class/database_class.php');
require_once ('../class/printer_class.php');
require_once ('../class/function_class.php');
require_once ('../class/enkripsi_class.php');

$db = new db($dbserver, $dbuser, $dbpass, $dbname);
$objFunction = new myfunction();
$objEnkrip = new Encryption();
$request = $objEnkrip->decode($_REQUEST['request']);

//Style
$report = '<html><style>
         table,th,td,tr
         {
            border : 1px solid;
         }
         th,td
         {
            padding : 5px;
            white-space : nowrap;
         }
         th {background-color : #eee;}
         .text-center { text-align : center;}
         .text-right { text-align : right;}

         </style><header><title>Cetak Laporan</title></header><body>';



switch ($request) {
    case 'transaksi':
        $date1 = $objEnkrip->decode($_REQUEST['s']);
        $date2 = $objEnkrip->decode($_REQUEST['e']);
        $jenis = $objEnkrip->decode($_REQUEST['j']);
        $dataKolom = array('Waktu', 'ID Transaksi', 'Pembeli', 'Total item', 'Total harga', 'Total bayar', 'Kasir'); //Judul Kolom
        $dataField = array('datetime', 'id_transaksi', 'pembeli', 'total_item', 'total_harga', 'total_bayar', 'id_user');
        $user = $db->get_data($db, 'user', '*', '', '', '');
        $grand_total = 0;
        $judul = 'Laporan Transaksi';
        //echo $start . '-' . $end . ' Jenis : ' . $jenis;

        if ($date1 != '' && $date2 != '') {
            $subtitel = "Laporan Transaksi Tanggal $date1 s.d " . $date2;
            $crt = " datetime BETWEEN '" . $date1 . "' AND '" . $date2 . "'  + INTERVAL 1 DAY ";
        } else {
            $subtitel = "Laporan Transaksi " . $objFunction->get_date();
            $crt = " datetime BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY ";
        }
        $ArrayDt = $db->get_data($db, 'transaksi', '*', $crt, 'datetime DESC', '');
        $report .= '<div style="width:100%;text-align:center;"><h3>' . $subtitel . '</h3></div>';
        $report .= '<table cellspacing="0" style="font-size: 12px;color: #000; width:100%;"><thead><tr><th class="text-center">No</th>';
        for ($i = 0; $i < count($dataKolom); $i++) {
            $report.='<th class="text-center">' . $dataKolom[$i] . '</th>';
        }
        $report.='</tr></thead><tbody>';

        for ($i = 0; $i < count($ArrayDt); $i++) {
            $no = $i + 1;
            //edit module
            $report.= '<tr><td width="40px" class="text-center">' . $no . '</td>';
            for ($x = 0; $x < count($dataField); $x++) {
                $arData = $ArrayDt[$i][$dataField[$x]];

                switch ($dataField[$x]) {
                    case 'total_harga' :
                        if ($jenis == 'pedeef')
                            $report.= '<td class="text-right">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                        if ($jenis == 'exeles')
                            $report.= '<td class="text-right">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                        $grand_total = $grand_total + $ArrayDt[$i][$dataField[$x]];
                        break;

                    case 'total_bayar' :
                        if ($jenis == 'pedeef')
                            $report.= '<td class="text-right">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                        if ($jenis == 'exeles')
                            $report.= '<td class="text-right">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                        break;

                    case 'pembeli' :
                        if ($ArrayDt[$i][$dataField[$x]] == '1')
                            $pembeli = "Umum";
                        else
                            $pembeli = "Toko";
                        $report.= '<td class="text-center">' . $pembeli . '</td>';
                        break;

                    case 'id_user' :
                        $report.= '<td class="text-center">' . $objFunction->search_by($user, 'id_user', $ArrayDt[$i][$dataField[$x]], 'nama') . '</td>';
                        break;

                    default:
                        $report.= '<td class="text-center">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                        break;
                }
            }

            $report.= '</tr>';
        }

        //$report.='<tr  class="text-right"><td colspan="5">Total :</td><td colspan="3" style="text-align:left;">' . $objFunction->set_rupiah($grand_total) . '</td></tr>';
        $report.= '</tbody></table>';
        $report .= '<div style="text-align:left;"><h4>Total Transaksi: ' . $objFunction->set_rupiah($grand_total) . '</h4></div>';
        //echo $report;
        break;
    case 'obat':
        $data_obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
        $satuan = $db->get_data($db, 'satuan', '*', '', 'nama ASC', '');
        $date1 = $objEnkrip->decode($_REQUEST['s']);
        $date2 = $objEnkrip->decode($_REQUEST['e']);
        $jenis = $objEnkrip->decode($_REQUEST['j']);
        $dataKolom = array('Nama Obat', 'Jumlah Terjual', 'Stock Saat Ini', 'Tanggal Terakhir Masuk'); //Judul Kolom
        $dataField = array('id_data_obat', 'jumlah', 'stock', 'tgl_masuk');

        $judul = 'Laporan Penjualan Obat';
        //echo $start . '-' . $end . ' Jenis : ' . $jenis;

        if ($date1 != '' && $date2 != '') {
            $subtitel = "Laporan Penjualan Obat<br>Tanggal $date1 s.d " . $date2;
            $crt = " datetime BETWEEN '" . $date1 . "' AND '" . $date2 . "'  + INTERVAL 1 DAY ";
        } else {
            $subtitel = "Laporan Penjualan Obat<br> " . $objFunction->get_date();
            $crt = " datetime BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY ";
        }
        // $ArrayDt = $db->get_data($db, $tbl, $fldSelect, $crt, 'id_data_obat DESC', '');
        $report .= '<div style="width:100%;text-align:center;"><h3>' . $subtitel . '</h3></div>';
        $report .= '<table cellspacing="0" style="font-size: 12px;color: #000; width:100%;"><thead><tr><th class="text-center">No</th>';
        for ($i = 0; $i < count($dataKolom); $i++) {
            $report.='<th class="text-center">' . $dataKolom[$i] . '</th>';
        }
        $report.='</tr></thead><tbody>';

        $ArrayDt = $db->get_data($db, 'transaksi_detail', '*', $crt, 'id_data_obat DESC', '');
        $ar_data = array();
        for ($i = 0; $i < count($ArrayDt); $i++) {

            if (count($ar_data) == 0) {
                $ar_data [0]['id_data_obat'] = $ArrayDt[$i]['id_data_obat'];
                $ar_data [0]['jumlah'] = $ArrayDt[$i]['jumlah'];
                $ar_data [0]['satuan'] = $ArrayDt[$i]['satuan'];
            }

            //echo "Jumlah : " . count($ar_data) . "<br>";
            $index = -1;
            for ($x = 0; $x < count($ar_data); $x++) {
                if ($ArrayDt[$i]['id_data_obat'] == $ar_data[$x]['id_data_obat']) {
                    $index = $x;
                }
            }

            $z = count($ar_data);
            if ($index != -1) {
                if ($ar_data [$index]['satuan'] == $ArrayDt[$i]['satuan']) {
                    $ar_data [$index]['jumlah'] += $ArrayDt[$i]['jumlah'];
                } else {
                    $index = -1;
                    for ($x = 0; $x < count($ar_data); $x++) {
                        if ($ArrayDt[$i]['satuan'] == $ar_data[$x]['satuan']) {
                            $index = $x;
                        }
                    }
                    if ($index == -1) {
                        $ar_data [$z]['id_data_obat'] = $ArrayDt[$i]['id_data_obat'];
                        $ar_data [$z]['jumlah'] = $ArrayDt[$i]['jumlah'];
                        $ar_data [$z]['satuan'] = $ArrayDt[$i]['satuan'];
                    } else {
                        $ar_data [$index]['jumlah'] += $ArrayDt[$i]['jumlah'];
                    }
                }
            } else {
                $ar_data [$z]['id_data_obat'] = $ArrayDt[$i]['id_data_obat'];
                $ar_data [$z]['jumlah'] = $ArrayDt[$i]['jumlah'];
                $ar_data [$z]['satuan'] = $ArrayDt[$i]['satuan'];
            }

            //edit module
        }

        for ($i = 0; $i < count($ar_data); $i++) {
            $id_obat = $ar_data[$i]['id_data_obat'];
            $jumlah = $ar_data[$i]['jumlah'];
            $satuans = $ar_data[$i]['satuan'];
            $satuan_kecil = $objFunction->search_by($data_obat, 'id_data_obat', $id_obat, 'satuan_kecil');
            $jumlah_satuan_kecil = $objFunction->search_by($data_obat, 'id_data_obat', $id_obat, 'jumlah_satuan_kecil');
            //echo $satuan_kecil;

            if ($satuans != $satuan_kecil) {
                $ar_data[$i]['satuan'] = $satuan_kecil;
                $ar_data[$i]['jumlah'] = $jumlah * $jumlah_satuan_kecil;
            }
        }
        $ar_data_x = array();
        $indexAr = 0;
        for ($i = 0; $i < count($ar_data); $i++) {
            @$ar_data_x[$ar_data[$i]['id_data_obat']] += $ar_data[$i]['jumlah'];
        }
        $ar_data = array();
        foreach ($ar_data_x as $key => $value) {
            $ar_data[$indexAr]['id_data_obat'] = $key;
            $ar_data[$indexAr]['jumlah'] = $value;
            $indexAr++;
        }

        for ($i = 0; $i < count($ar_data); $i++) {
            $no = $i + 1;
            $report .= '<tr>';
            $report .= '<td width="40px" class="text-center">' . $no . '</td>';
            for ($x = 0; $x < count($dataField); $x++) {
                switch ($dataField[$x]) {
                    case 'id_data_obat' :
                        $id_data_obat = $ar_data[$i][$dataField[$x]];
                        $report .= '<td>' . $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'nama') . '</td>';
                        break;
                    case 'jumlah' :
                        $satuan_kecils = $objFunction->search_by($satuan, 'id_satuan', $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'satuan_kecil'), 'nama');
                        $report .= '<td class="text-right">' . $ar_data[$i][$dataField[$x]] . ' ' . $satuan_kecils . '</td>';
                        break;
                    case 'stock' :
                        $stock_masuk = $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'stock_masuk');
                        $stock_keluar = $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'stock_keluar');
                        $satuan_kecils = $objFunction->search_by($satuan, 'id_satuan', $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'satuan_kecil'), 'nama');
                        $stock = $stock_masuk - $stock_keluar;
                        $report .= '<td class="text-right">' . $stock . ' ' . $satuan_kecils . '</td>';
                        break;
                    case 'tgl_masuk' :
                        $tgl_masuk = $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'tanggal_terakhir_masuk');
                        $stock = $stock_masuk - $stock_keluar;
                        $report .= '<td class="text-center">' . $tgl_masuk . '</td>';
                        break;
                    default:
                        $report .= '<td class="text-center">' . $ar_data[$i][$dataField[$x]] . '</td>';
                        break;
                }
            }

            $report .= '</tr>';
        }

        //$report.='<tr  class="text-right"><td colspan="5">Total :</td><td colspan="3" style="text-align:left;">' . $objFunction->set_rupiah($grand_total) . '</td></tr>';
        $report.= '</tbody></table>';
        //echo $report;
        break;
    default : break;
}
if ($jenis == 'pedeef') {
    require_once("../lib/dompdf/dompdf_config.inc.php");
    ob_start();
    echo '<div style="width:100%;">' . $report . '</div></body></html>';

    file_put_contents('tempdataPrint.html', ob_get_contents());
    $filename = 'tempdataPrint.html';
    $dompdf = new DOMPDF();
    $dompdf->set_paper(array(0, 0, 8.27 * 72, 13 * 72), "potrait");
    $dompdf->load_html(file_get_contents($filename));
    $dompdf->render();

    $canvas = $dompdf->get_canvas();
    $font = Font_Metrics::get_font("arial", "normal");

    $output = $dompdf->output();
    $filename = $judul . '-' . date('Ymd') . ".pdf";
    $dompdf->stream($filename, array("compress" => 1, "Attachment" => 0));
} elseif ($jenis == 'exeles') {
    $objFunction->headerDownload('Laporan_Transaksi' . '-' . date('Ymd'), '.xls');
    echo $report;
}
?>
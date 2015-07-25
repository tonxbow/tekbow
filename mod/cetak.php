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
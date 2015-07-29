<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
    }
</style>

<?php
$data_obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
$satuan = $db->get_data($db, 'satuan', '*', '', 'nama ASC', '');

$filename = 'dtreport.php';
$judul = 'Report Obat ';
$stsCetak = true;
$actionForm = 'mod/act.php?ask=' . $objEnkrip->encode('actreport.php');
$jenisPesan = '';

$tbl = 'transaksi_detail';
$fldSelect = "id_data_obat,jumlah,satuan";
$criteriaField = 'id_transaksi_detail';

//Header Tabel
$dataKolom = array('Nama Obat', 'Jumlah Terjual', 'Stock Saat Ini', 'Tanggal Terakhir Masuk'); //Judul Kolom
$dataField = array('id_data_obat', 'jumlah', 'stock', 'tgl_masuk');
//field primary key

$date1 = '';
$date2 = '';
if (isset($_REQUEST['date1']) && $_REQUEST['date1'] != '')
    $date1 = mysql_escape_string($_REQUEST['date1']);
if (isset($_REQUEST['date2']) && $_REQUEST['date2'] != '')
    $date2 = mysql_escape_string($_REQUEST['date2']);

if ($date1 != '' && $date2 != '')
    $crt = " datetime BETWEEN '" . $date1 . "' AND '" . $date2 . "'  + INTERVAL 1 DAY ";
else
    $crt = " datetime BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY ";
?>

<!-- page start-->
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-primary">
            <header class="panel-heading" style="padding-top: 10px; padding-bottom: 0;">
                <div style="font-size: 18px;">
                    <form method="post" action="#">
                        <?php echo $judul; ?>
                        Tanggal :
                        <input style="width: 150px; color: #000; text-align: center;"  name="date1" id="date1" value="<?php //echo ($date1 != '' ? $date1 : '');             ?>">
                        s.d
                        <input style="width: 150px; color: #000; text-align: center;"  name="date2" id="date2" value="<?php //echo ($date2 != '' ? $date2 : '');             ?>">
                        <button class="btn btn-danger " id="btn_show" type="submit">Show</button>
                    </form>
                </div>
            </header>
            <div class="panel-body" style="height: 70%;">
                <div class="adv-table editable-table ">
                    <div class="clearfix" >

                        <?php if ($stsCetak) { ?>
                            <div class="btn-group pull-right" style="position: absolute;left: 92%;">
                                <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">Export <i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li><a target="_blank" href="mod/cetak.php?request=<?php echo $objEnkrip->encode('obat') . '&s=' . $objEnkrip->encode($date1) . '&e=' . $objEnkrip->encode($date2) . '&j=' . $objEnkrip->encode('pedeef'); ?>">PDF</a></li>
                                    <li><a target="_blank" href="mod/cetak.php?request=<?php echo $objEnkrip->encode('obat') . '&s=' . $objEnkrip->encode($date1) . '&e=' . $objEnkrip->encode($date2) . '&j=' . $objEnkrip->encode('exeles'); ?>">Excel</a></li>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>

                    <?php
                    if ($date1 == '' && $date2 == '')
                        $subtitel = "Laporan Penjualan Obat Hari Ini";
                    else
                        $subtitel = "Laporan Penjualan Obat Tanggal $date1 s.d " . $date2;
                    ?>
                    <div style="text-align:center;color: #000;" ><h4><?php echo $subtitel; ?></h4></div>
                    <table class="table table-striped table-hover table-bordered display" id="table-data"  cellspacing="0" style="font-size: 12px;color: #000;">
                        <thead>
                            <tr>
                                <?php
                                echo '<th class="text-center">No</th>';
                                for ($i = 0; $i < count($dataKolom); $i++) {
                                    echo '<th class="text-center">' . $dataKolom[$i] . '</th>';
                                }
                                ?>
                            </tr>
                        </thead>

                        <tbody id="tbl_body">
                            <?php
                            $ArrayDt = $db->get_data($db, $tbl, $fldSelect, $crt, 'id_data_obat DESC', '');
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
                                echo '<tr>';
                                echo '<td width="40px" class="text-center">' . $no . '</td>';
                                for ($x = 0; $x < count($dataField); $x++) {


                                    switch ($dataField[$x]) {
                                        case 'id_data_obat' :
                                            $id_data_obat = $ar_data[$i][$dataField[$x]];
                                            echo '<td>' . $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'nama') . '</td>';
                                            break;
                                        case 'jumlah' :
                                            $satuan_kecils = $objFunction->search_by($satuan, 'id_satuan', $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'satuan_kecil'), 'nama');
                                            echo '<td class="text-right">' . $ar_data[$i][$dataField[$x]] . ' ' . $satuan_kecils . '</td>';
                                            break;
                                        case 'stock' :
                                            $stock_masuk = $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'stock_masuk');
                                            $stock_keluar = $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'stock_keluar');
                                            $satuan_kecils = $objFunction->search_by($satuan, 'id_satuan', $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'satuan_kecil'), 'nama');
                                            $stock = $stock_masuk - $stock_keluar;
                                            echo '<td class="text-right">' . $stock . ' ' . $satuan_kecils . '</td>';
                                            break;
                                        case 'tgl_masuk' :
                                            $tgl_masuk = $objFunction->search_by($data_obat, 'id_data_obat', $id_data_obat, 'tanggal_terakhir_masuk');
                                            $stock = $stock_masuk - $stock_keluar;
                                            echo '<td class="text-center">' . $tgl_masuk . '</td>';
                                            break;
                                        default:
                                            echo '<td class="text-center">' . $ar_data[$i][$dataField[$x]] . '</td>';
                                            break;
                                    }
                                }

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>


<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css" />
<script src="js/jquery.dataTables.min.js"></script>
<script>
    jQuery(function () {
        jQuery('#date1').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    maxDate: jQuery('#date2').val() ? jQuery('#date2').val() : false
                })
            },
            timepicker: false
        });
        jQuery('#date2').datetimepicker({
            format: 'Y-m-d',
            onShow: function (ct) {
                this.setOptions({
                    //minDate: jQuery('#date1').val() ? jQuery('#date1').val() : false
                })
            },
            timepicker: false
        });
    });




    jQuery(document).ready(function () {
        $('#date2').hide();
        $('#btn_show').hide();

        $('#date1').on("change", function () {
            if ($('#date1').val() != '')
                $('#date2').fadeIn();
            else
            {
                $('#date2').val('');
                $('#date2').fadeOut();
            }
        });

        $('#date2').on("change", function () {
            if ($('#date2').val() != '')
                $('#btn_show').fadeIn();
            else
                $('#btn_show').fadeOut();
        });


        var tables = $('#table-data').DataTable(
                {
                    "iDisplayLength": 50,
                    "aLengthMenu": [
                        [50, 100, 200, -1],
                        [50, 100, 200, "All"] // change per page values here
                    ],
                    "scrollCollapse": true,
                    "paging": true,
                    "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                }
        );

    });
</script>

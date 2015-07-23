<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
    }
</style>

<?php
/* * ************************************************************
 * Untuk membuat Form baru :
 * 1. Seuaikan variable dengan kebutuhan
 * 2. Buat Form
 * 3. Buat Validasi
 *
 *
 *  VARIABLE
 * *********************************************************** */
$filename = 'dtdata_obat.php';
$judul = 'Data Obat';
$stsCetak = false;
$actionForm = 'mod/act.php?ask=' . $objEnkrip->encode('actdata_obat.php');
$jenisPesan = '';

$tbl = 'data_obat';
$fldSelect = "*";
$criteriaField = 'id_data_obat';

//Header Tabel
$dataKolom = array('Nama', 'Kode', 'Sat. Besar', 'Sat. Kecil', 'Isi Sat.', 'Harga Beli', 'Harga Dasar', 'Obat In', 'Obat Out', 'Stock', 'Tgl Masuk', 'Action'); //Judul Kolom
$dataField = array('nama', 'barcode', 'satuan_besar', 'satuan_kecil', 'jumlah_satuan_kecil', 'harga_dasar', 'harga_jual', 'stock_masuk', 'stock_keluar', 'stock', 'tanggal_terakhir_masuk');
//field primary key

$classLabel = 'col-xs-4 control-label';
$classInput = 'col-xs-8';

$satuan = $db->get_data($db, 'satuan', '*', '', '', '');

/* ----------------------------------
 * action for action-data
 * ---------------------------------- */
if (isset($_GET['p']) && trim($_GET['p']) != '' && !isset($_POST['btnSubmit'])) {
    $reqP = $objEnkrip->decode(trim($_GET['p']));
    if (substr($reqP, 0, 1) == 'y') {
        $jenisPesan = 'alert-success';
        $statusPesan = 'Berhasil';
        $isiPesan = 'Data berhasil di';
    } else {
        $jenisPesan = 'alert-danger';
        $statusPesan = 'Gagal';
        $isiPesan = 'Data Gagal di';
    }

    switch (substr($reqP, 1)) {
        case 'add': $isiPesan .= 'tambah';
            break;
        case 'edit': $isiPesan .='ubah';
            break;
        case 'del': $isiPesan .='hapus';
            break;
        default:
    }//nsw
}

$crt = '';
?>

<!-- page start-->
<div class="row">
    <div class="col-sm-12">
        <section class="panel panel-primary">
            <header class="panel-heading" style="padding-top: 10px; padding-bottom: 0;">

                <h4>Data Obat</h4>
            </header>
            <div class="panel-body" style="height: 75%;">
                <div class="adv-table editable-table ">
                    <div class="clearfix">

                        <?php if ($stsCetak) { ?>
                            <div class="btn-group pull-right">
                                <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown">Tools <i class="fa fa-angle-down"></i></button>
                                <ul class="dropdown-menu pull-right">
                                    <li><a target="_blank" href="cetak/ctkpxdata_sales_eksekutif.php?ask=<?php echo $objEnkrip->encode('pdf') ?>">Save as PDF</a></li>
                                    <li><a target="_blank" href="cetak/ctkpxdata_sales_eksekutif.php?ask=<?php echo $objEnkrip->encode('xls') ?>">Export To Excel</a></li>

                                </ul>
                            </div>
                        <?php } ?>
                    </div>

                    <!--  RESPONSE MESSAGE-->
                    <?php
                    if ($jenisPesan != '') {
                        echo '<div class = "alert alert-block ' . $jenisPesan . ' fade in" style = "margin-top: 15px;">
                            <button data-dismiss = "alert" class = "close close-sm" type = "button"><i class = "fa fa-times"></i></button>
                            <strong>' . $statusPesan . ' </strong>' . $isiPesan .
                        '</div>';
                    }
                    ?>

                    <!--TABLE DATA-->
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
                            //

                            $user = $db->get_data($db, 'user', '*', '', '', '');
                            $ArrayDt = $db->get_data($db, $tbl, $fldSelect, $crt, 'nama ASC', '');
                            $grand_total = 0;

                            for ($i = 0; $i < count($ArrayDt); $i++) {
                                $no = $i + 1;
                                //edit module
                                echo '<tr>';
                                echo '<td width="40px" class="text-center">' . $no . '</td>';
                                for ($x = 0; $x < count($dataField); $x++) {


                                    switch ($dataField[$x]) {
                                        case 'harga_dasar' :
                                            echo '<td class="text-right">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                            break;
                                        case 'harga_jual' : echo '<td class="text-right">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                            break;
                                        case 'nama' : echo '<td class="text-left">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                            break;
                                        case 'satuan_kecil' :
                                            $satuan_kecil = $objFunction->search_by($satuan, 'id_satuan', $ArrayDt[$i][$dataField[$x]], 'nama');
                                            echo '<td class="text-center">' . $satuan_kecil . '</td>';
                                            break;
                                        case 'satuan_besar' : echo '<td class="text-center">' . $objFunction->search_by($satuan, 'id_satuan', $ArrayDt[$i][$dataField[$x]], 'nama') . '</td>';
                                            break;
                                        case 'stock' :
                                            $stock = ($ArrayDt[$i]['stock_masuk'] - $ArrayDt[$i]['stock_keluar']); //. ' ' . $satuan_kecil;
                                            echo '<td class="text-center">' . $stock . '</td>';
                                            break;
                                        default:
                                            echo '<td class="text-center">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                            break;
                                    }
                                }

                                echo '<td style="whitespace:nowrap;" class="text-center"><a id="editBtn' . $i . '" class="edit" data-toggle="modal" href="#modalEdit">Detail</a>
                                    </td>
                                    </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </section>
    </div>
</div>
<?php
//$grand_total = $objFunction->set_rupiah($grand_total);
?>
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css" />
<script src="js/jquery.dataTables.min.js"></script>
<script>







    jQuery(document).ready(function () {


        var tables = $('#table-data').DataTable(
                {
                    "iDisplayLength": 10,
                    "aLengthMenu": [
                        [10, 50, 100, -1],
                        [10, 50, 100, "All"] // change per page values here
                    ],
                    "scrollCollapse": true,
                    "paging": true,
                    "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                }
        );
    });
</script>

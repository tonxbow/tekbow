<style>
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        width: 100%;
    }
</style>

<?php
$filename = 'dtreport.php';
$judul = 'Report Penjualan ';
$stsCetak = true;
$actionForm = 'mod/act.php?ask=' . $objEnkrip->encode('actreport.php');
$jenisPesan = '';

$tbl = 'transaksi';
$fldSelect = "*";
$criteriaField = 'id_transaksi';

//Header Tabel
$dataKolom = array('Waktu', 'ID Transaksi', 'Pembeli', 'Total item', 'Total harga', 'Total bayar', 'Kasir', 'Action'); //Judul Kolom
$dataField = array('datetime', 'id_transaksi', 'pembeli', 'total_item', 'total_harga', 'total_bayar', 'id_user');
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
                        <input style="width: 150px; color: #000; text-align: center;"  name="date1" id="date1" value="<?php echo ($date1 != '' ? $date1 : ''); ?>">
                        s.d
                        <input style="width: 150px; color: #000; text-align: center;"  name="date2" id="date2" value="<?php echo ($date2 != '' ? $date2 : ''); ?>">
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
                                    <li><a target="_blank" href="mod/cetak.php?request=<?php echo $objEnkrip->encode('transaksi') . '&s=' . $objEnkrip->encode($date1) . '&e=' . $objEnkrip->encode($date2) . '&j=' . $objEnkrip->encode('pedeef'); ?>">PDF</a></li>
                                    <li><a target="_blank" href="mod/cetak.php?request=<?php echo $objEnkrip->encode('transaksi') . '&s=' . $objEnkrip->encode($date1) . '&e=' . $objEnkrip->encode($date2) . '&j=' . $objEnkrip->encode('exeles'); ?>">Excel</a></li>

                                </ul>
                            </div>
                        <?php } ?>
                    </div>

                    <?php
                    if ($date1 == '' && $date2 == '')
                        $subtitel = "Laporan Transaksi Hari Ini";
                    else
                        $subtitel = "Laporan Transaksi Tanggal $date1 s.d " . $date2;
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
                            //

                            $user = $db->get_data($db, 'user', '*', '', '', '');
                            $ArrayDt = $db->get_data($db, $tbl, $fldSelect, $crt, 'datetime DESC', '');
                            $grand_total = 0;

                            for ($i = 0; $i < count($ArrayDt); $i++) {
                                $no = $i + 1;
                                //edit module
                                echo '<tr>';
                                echo '<td width="40px" class="text-center">' . $no . '</td>';
                                for ($x = 0; $x < count($dataField); $x++) {
                                    $arData = $ArrayDt[$i][$dataField[$x]];

                                    switch ($dataField[$x]) {
                                        case 'total_harga' :
                                            echo '<td class="text-right">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                            $grand_total = $grand_total + $ArrayDt[$i][$dataField[$x]];
                                            break;

                                        case 'total_bayar' : echo '<td class="text-right">' . $objFunction->set_rupiah($ArrayDt[$i][$dataField[$x]]) . '</td>';
                                            break;

                                        case 'pembeli' :
                                            if ($ArrayDt[$i][$dataField[$x]] == '1')
                                                $pembeli = "Umum";
                                            else
                                                $pembeli = "Toko";
                                            echo '<td class="text-center">' . $pembeli . '</td>';
                                            break;

                                        case 'id_user' : echo '<td class="text-center">' . $objFunction->search_by($user, 'id_user', $ArrayDt[$i][$dataField[$x]], 'nama') . '</td>';
                                            break;

                                        default:
                                            echo '<td class="text-center">' . $ArrayDt[$i][$dataField[$x]] . '</td>';
                                            break;
                                    }
                                }

                                echo '<td style="whitespace:nowrap;" class="text-center"><button onclick="show_detail(\'' . $objEnkrip->encode($ArrayDt[$i][$criteriaField]) . '\')">Detail</button>
                                    </td>
                                    </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-6" style="margin-top:10px;"></div>
                <div class="col-sm-6 text-right" style="margin-top:10px;">
                    <table style="width: 100%; background-color: #ddd; color: #000;">
                        <tr>
                            <td>
                                <div class="col-sm-2 text-center" style="padding-top: 5px; font-size: 20px;color: #000;">
                                    <button id="btn_detail_total">+</button>
                                </div>
                                <div class="col-sm-3 text-right" style="padding-top: 5px; font-size: 20px;">
                                    Total Transaksi :
                                </div>
                                <div class="col-sm-7 text-right" style="padding-top: 5px; font-size: 20px;">
                                    <p id="lbl_grand_total"></p>
                                </div>
                            </td>

                        </tr>
                        <tbody id="tbl_detail" style="font-size: 18px;">
                            <tr>
                                <td>
                                    <div class="col-sm-offset-2 col-sm-4 text-right" style="padding-top: 5px;">
                                        PPN 10% :
                                    </div>
                                    <div class="col-sm-6 text-right" style="padding-top: 5px;">
                                        <p id="lbl_ppn"></p>
                                    </div>
                                </td>

                            </tr>
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

<div class="modal fade" id="mod_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Detail</h4>
            </div>
            <div class="modal-body">
                <textarea id="txt_struk" style="width: 100%; resize: none; color: #000;" readonly rows="10"></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-block" id='btn_close'>Close</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables_themeroller.css" />
<script src="js/jquery.dataTables.min.js"></script>
<script>

    function toRp(a, b, c, d, e) {
        e = function (f) {
            return f.split('').reverse().join('')
        };
        b = e(parseInt(a, 10).toString());
        for (c = 0, d = ''; c < b.length; c++) {
            d += b[c];
            if ((c + 1) % 3 === 0 && c !== (b.length - 1)) {
                d += '.';
            }
        }
        return'Rp. ' + e(d);
    }

    function show_detail(id_trx)
    {
        //console.log(id_trx);
        $.post("mod/action.php", {request: "<?php echo $objEnkrip->encode('report_detail'); ?>", id: id_trx})
                .done(function (result) {
                    //console.log("Status : " + result);
                    $('#txt_struk').val(result);
                });
        $('#mod_detail').modal("show");
    }

    $('#btn_close').on("click", function () {
        $('#mod_detail').modal("hide");
    });

    jQuery(function () {
        $("#lbl_grand_total").text(toRp(<?php echo $grand_total; ?>));
        $("#lbl_ppn").text(toRp(<?php echo $grand_total * 10 / 100; ?>));
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
            timepicker: false
        });
    });


    jQuery(document).ready(function () {
        $('#tbl_detail').hide();

        var tables = $('#table-data').DataTable(
                {
                    "iDisplayLength": 5,
                    "aLengthMenu": [
                        [5, 20, 100, -1],
                        [5, 20, 100, "All"] // change per page values here
                    ],
                    "scrollCollapse": true,
                    "paging": true,
                    "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
                }
        );


        $('#btn_detail_total').on("click", function () {
            $('#tbl_detail').slideToggle();

        });

    });
</script>

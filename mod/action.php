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
$printer = new printer();

$request = $objEnkrip->decode($_REQUEST['request']);
$satuan = $db->get_data($db, 'satuan', '*', '', 'nama ASC', '');
switch ($request) {
    case 'id_transaksi' :
        switch ($objEnkrip->decode($_REQUEST['jenis'])) {
            case 'transaksi':
                $idtransaksi = array("id_transaksi" => $objFunction->createID('transaksi', 'id_transaksi', 'trx.' . $objFunction->get_date(), 5, 99999));
                break;
            case 'obat_masuk':
                $idtransaksi = array("id_transaksi" => $objFunction->createID('barang_masuk', 'id_barang_masuk', 'bm.' . $objFunction->get_date(), 5, 99999));
                break;
            default : $idtransaksi = array("id_transaksi" => '-');
        }
        echo json_encode($idtransaksi);
        break;
    case 'get_data_obat':
        $data_obat = $db->get_data($db, 'data_obat', '*', '', 'nama ASC', '');
        $data = json_encode($data_obat, JSON_PRETTY_PRINT);
        echo $data;
        break;
    case 'transaksi':
        $ret = 'success';
        //DATA HEAD <TONX> DATA BODY <TONX> DATA FOOTER
        $satuan = $db->get_data($db, 'satuan', '*', '', '', '');
        $data = $_REQUEST['data'];
        $ar_data = explode("<TONX>", $data);

        $header = $ar_data[0];
        $body = $ar_data[1];
        $footer = $ar_data[2];

        $struk = "";
        $ar_header = explode(";", $header);
        $ar_body = explode(";", $body);
        $ar_footer = explode(";", $footer);

        $id_transaksi = $ar_header[0];

//SAVE table transaksi
        $data_transaksi['id_transaksi'] = $id_transaksi;
        $data_transaksi['pembeli'] = $ar_header[1];
        $data_transaksi['total_item'] = $ar_header[2];
        $data_transaksi['total_harga'] = str_replace(' ', '', str_replace('Rp', '', str_replace('.', '', trim($ar_footer[0]))));
        $data_transaksi['total_bayar'] = str_replace(' ', '', str_replace('Rp', '', str_replace('.', '', trim($ar_footer[1]))));
        $data_transaksi['id_user'] = $_SESSION['id_user'];

        if (!$db->add_data($db, 'transaksi', $data_transaksi))
            $ret = 'failed';
        //HEADER
        $struk .= $printer->PrintHeader();
        $struk .= "ID Transaksi : " . $id_transaksi;
        $struk .= $printer->PrintEnter();
        $struk .= "Tanggal      : " . $objFunction->get_daydate();
        $struk .= $printer->PrintEnter();
        $struk .= "Kasir        : " . $db->get_curr_data($db, 'user', 'nama', 'id_user = "' . $data_transaksi['id_user'] . '"');
        $struk .= $printer->PrintEnter();
        $struk .= "=========================================\r\n";

        //BODY
        $data_detail_transaksi['id_transaksi'] = $id_transaksi;
        for ($i = 0; $i < count($ar_body) - 1; $i++) {
            $data_body = explode(",", $ar_body[$i]);

            $jumlah_terjual = str_replace(' ', '', $data_body[1]);
            $satuan_terjual = str_replace(' ', '', $data_body[2]);
            $id_data_obat = str_replace(' ', '', $data_body[5]);

            $struk .= trim(substr($data_body[0], 0, 42)) . "\r\n";
            $jml_struk = "  " . $jumlah_terjual . " " . $satuan_terjual . " x " . str_replace(' ', '', trim($data_body[3]));
            $jml_struk = str_pad($jml_struk, 27, " ", STR_PAD_RIGHT);
            $harga_struk = str_replace(' ', '', trim($data_body[4])) . " ";
            $harga_struk = str_pad($harga_struk, 15, " ", STR_PAD_LEFT);
            $struk .= $jml_struk . $harga_struk . "\r\n";
//42 = 17 + 25
            //Save Table Transaksi_detail
            $data_detail_transaksi['id_transaksi_detail'] = $id_transaksi . '.' . $i;
            $data_detail_transaksi['id_data_obat'] = $id_data_obat;
            $data_detail_transaksi['jumlah'] = $data_body[1];
            $data_detail_transaksi['satuan'] = $objFunction->search_by($satuan, 'nama', trim($data_body[2]), 'id_satuan');
            $data_detail_transaksi['harga_jual'] = str_replace(' ', '', str_replace('Rp', '', str_replace('.', '', trim($data_body[3]))));
            if (!$db->add_data($db, 'transaksi_detail', $data_detail_transaksi))
                $ret = 'failed';;
            //Kurangi Stok

            $obat = $db->get_data($db, 'data_obat', '*', 'id_data_obat = "' . $id_data_obat . '"', '', '');

            //print_r($obat);
            $obat_terjual = $obat[0]['stock_keluar'];

            //get id satuan
            $id_satuan_obat = $db->get_curr_data($db, 'satuan', 'id_satuan', 'nama="' . $satuan_terjual . '"');
            //Check Satuan
            if ($id_satuan_obat == $obat[0]['satuan_kecil']) {
                $data_obat['stock_keluar'] = $obat_terjual + $jumlah_terjual;
            } else {
                $data_obat['stock_keluar'] = $obat_terjual + ($jumlah_terjual * $obat[0]['jumlah_satuan_kecil']);
            }
            //Update Stock Terjual
            if (!$db->update_data($db, 'data_obat', $data_obat, 'id_data_obat="' . $id_data_obat . '"'))
                $ret = 'failed';;
        }
        $struk .= "=========================================+" . "\r\n";
        $struk .= $printer->BigText();
        $temp = str_pad($ar_footer[0] . " ", 17, " ", STR_PAD_LEFT);
        $struk .= "                   Total " . $temp;
        $struk .= $printer->TextNormal();
        $struk .= $printer->PrintEnter();
        $temp = str_pad($ar_footer[1] . " ", 17, " ", STR_PAD_LEFT);
        $struk .= "                   Bayar " . $temp;
        $struk .= $printer->PrintEnter();
        $temp = str_pad($ar_footer[2] . " ", 17, " ", STR_PAD_LEFT);
        $struk .= "                 Kembali " . $temp;
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        //$struk .= $printer->PrintBar();
        $struk .= $printer->AlignCenter();
        $struk .= $objFunction->get_datetime_sql() . "\r\n";
        $struk .= "Terima Kasih";
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->CutPaper();
        //echo $struk;

        $folder = '../struk/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        if (!file_exists($folder)) {
            mkdir($folder, '0777', true);
        }
        //$folder = '';
        $file = $folder . $ar_header[0] . '.txt';
        $handle = fopen($file, 'w') or $ret = 'failed';
        fwrite($handle, $struk);

        $setting = $db->get_data($db, 'setting', '*', '', '', '');
        $printer->print_data($setting[0]['port'], $struk);

        echo $ret;
        break;
    case 'report_detail':
        $id = $objEnkrip->decode($_REQUEST['id']);
        $ar_file = explode('.', $id);
        $file = $ar_file[1];
        $file = '../struk/' . substr($file, 0, 4) . '/' . substr($file, 4, 2) . '/' . substr($file, 6, 2) . '/' . $id . '.txt';
        //$file = '../struk/2015/07/16/trx.20150716.03164.txt';
        $data = file_get_contents($file);
        echo $data;
        break;
    case 'update_password' :
        $data = $_REQUEST['data'];
        $ar_data = explode(';', $data);
        $data_user['password'] = md5($ar_data[1]);
        $id_user = $_SESSION['id_user'];
        $password_lama = md5($ar_data[0]);
        $status = $db->get_curr_data($db, 'user', 'id_user', 'password = "' . $password_lama . '" AND id_user = "' . $id_user . '"');
        if ($status != "") {
            if ($db->update_data($db, 'user', $data_user, 'id_user = "' . $id_user . '"')) {
                echo '1';
                $objFunction->log('2', "table : user, [ubah password] data : " . $data);
            } else
                echo '2';
        } else
            echo '3';
        break;
    case 'update_database' :
        $data_request = $_REQUEST['data'];
        $ar_data = explode(';', $data_request);
        $data['server'] = $ar_data[0];
        $data['user'] = $ar_data[1];
        $data['password'] = $ar_data[2];
        $data['database'] = $ar_data[3];
        $file = "";
        $file .= "<?php \r\n";
        $file .= '$dbserver = "' . $data['server'] . '";' . "\r\n";
        $file .= '$dbuser = "' . $data['user'] . '";' . "\r\n";
        $file .= '$dbpass = "' . $data['password'] . '";' . "\r\n";
        $file .= '$dbname = "' . $data['database'] . '";' . "\r\n";
        $file .= "?>";
        //print_r($data);
        if (file_put_contents('setting.php', $file)) {
            echo '1';
        } else
            echo '2';
        break;
    case 'update_setting' :
        $data_request = $_REQUEST['data'];
        $tipe = $_REQUEST['tipe'];


        $ar_data = explode(';', $data_request);
        if ($tipe == '1') {
            $data['margin_umum'] = $ar_data[0];
            $data['margin_toko'] = $ar_data[1];
            $data['pajak'] = $ar_data[2];
        }
        if ($tipe == '2') {
            $data['nama_toko'] = $ar_data[0];
            $data['alamat1'] = $ar_data[1];
            $data['alamat2'] = $ar_data[2];
            $data['telp'] = $ar_data[3];
        }
        if ($tipe == '3') {
            $data['port'] = $ar_data[0];
            $data['baudrate'] = $ar_data[1];
        }
        if ($db->update_data($db, 'setting', $data, 'id_setting = "' . 1 . '"')) {
            echo '1';
            $objFunction->log('2', "table : setting, data : " . $data_request . ", tipe : " . $tipe);
        } else
            echo '2';

        break;
    //CRUD Data OBAT
    case 'update_data_obat' :
        $data = $_REQUEST['data'];


        $ar_data = explode(';', $data);
        $data_obat['barcode'] = $ar_data[0];
        $data_obat['nama'] = strtoupper($ar_data[1]);
        $data_obat['satuan_besar'] = $ar_data[2]; //$objEnkrip->decode($ar_data[2]);
        $data_obat['jumlah_satuan_kecil'] = $ar_data[3];
        $data_obat['satuan_kecil'] = $ar_data[4]; //$objEnkrip->decode($ar_data[4]);
        $data_obat['harga_dasar'] = $ar_data[5];
        $data_obat['harga_jual'] = $ar_data[6];
        $data_obat['id_data_obat'] = $ar_data[7];
        $data_obat['update_at'] = $objFunction->get_datetime_sql();

        if ($db->update_data($db, 'data_obat', $data_obat, 'id_data_obat = "' . $data_obat ['id_data_obat'] . '"')) {
            $objFunction->log('2', "table : data_obat, data : " . $data);
            echo 'success';
        } else {
            echo 'fail';
        }
        break;
    case 'delete_data_obat' :
        $data['id_data_obat'] = $_REQUEST['data'];
        if ($db->delete_data($db, 'data_obat', 'id_data_obat = "' . $data ['id_data_obat'] . '"')) {
            echo 'success';
            $objFunction->log('3', "table : data_obat, id : " . $data['id_data_obat']);
        } else {
            echo 'fail';
        }
        break;
    case 'add_data_obat' :
        $data = $_REQUEST['data'];
        $ar_data = explode(';', $data);
        $data_obat['id_data_obat'] = $objFunction->createID('data_obat', 'id_data_obat', 'obt', 5, 99999);
        if ($ar_data[0] == '')
            $data_obat['barcode'] = $objFunction->createID('data_obat', 'barcode', $objFunction->get_date(), 5, 99999);
        else
            $data_obat['barcode'] = $ar_data[0];
        $data_obat['nama'] = strtoupper($ar_data[1]);
        $data_obat['satuan_besar'] = $ar_data[2]; //$objEnkrip->decode($ar_data[2]);
        $data_obat['jumlah_satuan_kecil'] = $ar_data[3];
        $data_obat['satuan_kecil'] = $ar_data[4]; //$objEnkrip->decode($ar_data[4]);
        $data_obat['harga_dasar'] = $ar_data[5];
        $data_obat['harga_jual'] = $ar_data[6];
        $data_obat['id_group_obat'] = '0'; //$objEnkrip->decode($ar_data[7]);
        $data_obat['id_jenis_obat'] = '0'; //$objEnkrip->decode($ar_data[8]);
        $data_obat['id_type_obat'] = '0'; //$objEnkrip->decode($ar_data[9]);
        //print_r($data_obat);
        if ($db->add_data($db, 'data_obat', $data_obat)) {
            echo 'success';
            $objFunction->log('1', "table : data_obat, data : " . $data);
        } else {
            echo 'fail';
        }
        break;
    case 'update_harga_data_obat' :
        $id_obat = trim($_REQUEST['id_obat']);
        $ar_harga = explode(';', trim($_REQUEST['harga']));
        $data_obat['harga_dasar'] = $ar_harga[0];
        if (isset($ar_harga[1]) && $ar_harga [1] != '')
            $data_obat['harga_jual'] = $ar_harga[1];
        if ($db->update_data($db, 'data_obat', $data_obat, " id_data_obat = '$id_obat'"))
            echo 'success';
        else
            echo 'fail';
        break;
    //CRUD Data User
    case 'update_data_user' :
        $data_request = $_REQUEST['data'];
        $ar_data = explode(';', $data_request);
        $data['username'] = $ar_data[0];
        $data['nama'] = $ar_data[1];
        if ($ar_data[2] != '')
            $data['password'] = md5($ar_data[2]);
        $data['role'] = $ar_data[3];
        $id = $ar_data[4];
        $data['update_at'] = $objFunction->get_datetime_sql();
        //print_r($data);
        if ($db->update_data($db, 'user', $data, 'id_user = "' . $id . '"')) {
            echo 'success';
            $objFunction->log('2', "table : user, data : " . $data_request);
        } else
            echo 'fail';
        break;
    case 'delete_data_user' :
        $data['id_user'] = $_REQUEST['data'];
        if ($db->delete_data($db, 'user', 'id_user = "' . $data ['id_user'] . '"')) {
            echo 'success';
            $objFunction->log('3', "table : user, id : " . $data['id_user']);
        } else
            echo 'fail';
        break;
    case 'add_data_user' :
        $data_request = $_REQUEST['data'];
        //echo ($data);
        $ar_data = explode(';', $data_request);
        $data['id_user'] = $objFunction->createID('user', 'id_user', 'usr', 5, 99999);
        $data['username'] = $ar_data[0];
        $data['nama'] = $ar_data[1];
        $data['password'] = $ar_data[2];
        $data['role'] = $ar_data[3];
        //print_r($data_obat);
        if ($db->add_data($db, 'user', $data)) {
            echo 'success';
            $objFunction->log('1', "table : user, data : " . $data_request);
        } else
            echo 'fail';
        break;
    case 'barang_masuk':
        $ret = 'success';
        //DATA HEAD <TONX> DATA BODY <TONX> DATA FOOTER

        $data = $_REQUEST['data'];
        $ar_data = explode("<TONX>", $data);
        $header = $ar_data[0];
        $body = $ar_data[1];
        $footer = $ar_data[2];

        $struk = "";
        $ar_header = explode(";", $header); //id;tgl;vendor;total_item
        $ar_body = explode(";", $body); //nama,jumlah,satuan,batch,expire,harga,id_obat
        $ar_footer = explode(";", $footer); //grand_total

        $id_transaksi = $ar_header[0];

//SAVE table barang_masuk
        $data_barang_masuk['id_barang_masuk'] = $id_transaksi;
        $data_barang_masuk['tanggal_pembelian'] = $ar_header[1];
        $data_barang_masuk['vendor'] = $ar_header[2];
        $data_barang_masuk['total_barang'] = $ar_header[3];
        $data_barang_masuk['total_harga'] = str_replace(' ', '', str_replace('Rp', '', str_replace('.', '', trim($ar_footer[0]))));
        $data_barang_masuk['id_user'] = $_SESSION['id_user'];
        $data_barang_masuk['penerima'] = trim($ar_footer[1]);

        if (!$db->add_data($db, 'barang_masuk', $data_barang_masuk))
            $ret = 'fail add transaksi';

        //HEADER
        $struk .= $printer->PrintHeader();
        $struk .= "ID Trx  : " . $id_transaksi;
        $struk .= $printer->PrintEnter();
        $struk .= "Vendor  : " . $ar_header [2];
        $struk .= $printer->PrintEnter();
        $struk .= "Tanggal : " . $ar_header [1];
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= "------------------------------------------" . "\r\n";

        //BODY
        $data_detail_transaksi['id_barang_masuk'] = $id_transaksi;
        for ($i = 0; $i < count($ar_body) - 1; $i ++) {

            $data_body = explode(",", $ar_body [$i]); //nama,jumlah,satuan,batch,expire,harga,id_obat

            $jumlah_masuk = str_replace(' ', '', $data_body[1]);
            $satuan = str_replace(' ', '', $data_body[2]);
            $id_data_obat = str_replace(' ', '', $data_body[6]);

            $struk .= trim(substr($data_body[0], 0, 40)) . "\r\n";
            $struk .= " x" . $jumlah_masuk . "\t" . $satuan . "\t" . str_replace(' ', '', trim($data_body[5])) . "\r\n";


            //Save Table Transaksi_detail
            $data_detail_transaksi['id_barang_masuk_detail'] = $id_transaksi . '.' . $i;
            $data_detail_transaksi['id_data_obat'] = $id_data_obat;
            $data_detail_transaksi['jumlah_satuan_kecil'] = $jumlah_masuk;
            $data_detail_transaksi['batch_number'] = trim($data_body[3]);
            $data_detail_transaksi['expire_date'] = trim($data_body[4]);
            $data_detail_transaksi['harga_beli'] = str_replace(' ', '', str_replace('Rp', '', str_replace('.', '', trim($data_body[5]))));

            if (!$db->add_data($db, 'barang_masuk_detail', $data_detail_transaksi))
                $ret = 'fail add detail transaksi';

            //Tambah Stok
            $obat = $db->get_data($db, 'data_obat', '*', 'id_data_obat = "' . $id_data_obat . '"', '', '');
            $obat_masuk = $obat[0]['stock_masuk'];
            $data_obat['stock_masuk'] = $obat_masuk + $jumlah_masuk;
            $data_obat['tanggal_terakhir_masuk'] = trim($ar_header[1]);
            $data_obat['update_at'] = $objFunction->get_datetime_sql();
            //Update Stock Terjual
            if (!$db->update_data($db, 'data_obat', $data_obat, 'id_data_obat="' . $id_data_obat . '"'))
                $ret = 'fail update stock';
            ;
        }

        $struk .= "-----------------------------------------+" . "\r\n";
        $struk .= "Total    :" . $ar_footer [0];
        $struk .= $printer->PrintEnter();
        $struk .= "Penerima :" . $ar_footer [1];
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintBar();
        $struk .= $printer->AlignCenter();
        $struk .= $objFunction->get_datetime_sql() . "\r\n";
        $struk .= "Terima Kasih";
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->PrintEnter();
        $struk .= $printer->CutPaper();
        //echo $struk;

        $folder = '../laporan/barang masuk/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';
        if (!file_exists($folder)) {
            mkdir($folder, '0777', true);
        }
//$folder = '';
        $file = $folder . $id_transaksi . '.txt';
        $handle = fopen($file, 'w') or $ret = 'fail struk';
        fwrite($handle, $struk);

        $setting = $db->get_data($db, 'setting', '*', '', '', '');
        if (isset($_REQUEST['print']) && trim($_REQUEST ['print']) == 'ok') {
            $printer->print_data($setting[0]['port'], $struk);
        }

        echo $ret;
        break;
    case 'get_grand_total':
        $grand_total = $db->get_data($db, 'transaksi', 'SUM(total_harga) as grand_total', ' datetime BETWEEN CURDATE() AND CURDATE() + INTERVAL 1 DAY AND id_user="' . $_SESSION['id_user'] . '"', '', '');
        if (count($grand_total) > 0)
            echo json_encode(array("total" => $objFunction->set_rupiah($grand_total[0]['grand_total'])));
        else
            echo '0';
        break;
    case 'retur_barang':
        $id = $objEnkrip->decode($_GET['id']);
        //Update Stock
        $obat = $db->get_data($db, 'data_obat', '*', '', '', '');
        $data_barang = $db->get_data($db, 'transaksi_detail', '*', 'id_transaksi = "' . $id . '"', '', '');
        //print_r($data_barang);
        $status = true;
        for ($i = 0; $i < count($data_barang); $i++) {
            $id_barang = $data_barang[$i]['id_data_obat'];
            $jumlah = $data_barang[$i]['jumlah'];
            $satuan = $data_barang[$i]['satuan'];
            $satuan_kecil = $objFunction->search_by($obat, 'id_data_obat', $id_barang, 'satuan_kecil');
            $jumlah_satuan_kecil = $objFunction->search_by($obat, 'id_data_obat', $id_barang, 'jumlah_satuan_kecil');
            $stock_keluar = $objFunction->search_by($obat, 'id_data_obat', $id_barang, 'stock_keluar');
            if ($satuan != $satuan_kecil)
                $jumlah = $jumlah * $jumlah_satuan_kecil;

            $data_trx['stock_keluar'] = $stock_keluar - $jumlah;

            if (!$db->update_data($db, 'data_obat', $data_trx, 'id_data_obat = "' . $id_barang . '"'))
                $status = false;
        }
        if ($status) {
            if ($db->delete_data($db, 'transaksi_detail', 'id_transaksi = "' . $id . '"')) {
                if ($db->delete_data($db, 'transaksi', 'id_transaksi = "' . $id . '"'))
                    echo "berhasil";
                else
                    echo "gagal";
            } else
                echo "gagal";
        } else
            echo "gagal";

        break;
}
?>
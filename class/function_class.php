<?php

class myfunction {

    public function login($u, $p) {
        global $db;
        $getUser = $db->get_data($db, 'user', '*', 'username=\'' . $u . '\' AND password=md5(\'' . $p . '\')', '', '');
        $status = count($getUser);

        if ($status >= 1) {
            $_SESSION["logged"] = "apotek";
            $_SESSION['id_user'] = $getUser[0]['id_user'];
            $_SESSION['username'] = $getUser[0]['username'];
            $_SESSION['role'] = $getUser[0]['role'];
            $data['last_login'] = self::get_datetime_sql();
            $db->update_data($db, 'user', $data, 'id_user="' . $_SESSION['id_user'] . '"');
            self::log('4', "Login");
            return true;
        } else {
            return false;
        }
    }

    public function logout() {
        self::log('4', "Logout");
        session_unset();
        session_destroy();
        return true;
    }

    public function checkLogin() {
        if (!(isset($_SESSION['logged']) && $_SESSION['logged'] != '' && $_SESSION['logged'] == 'apotek')) {
            header("Location: login.php");
        }
    }

    public function log($action, $data_action) {//
        //1:Add,2:Update,3:Delete,4:Akses
        global $db;
        $data ['id_user'] = $_SESSION['id_user'];
        $data ['action'] = $action;
        $data ['data'] = $data_action;
        if ($db->add_data($db, 'audit_trail', $data))
            return true;
        else
            return false;
    }

    public function createID($table, $field, $prefix, $jumlahrandom, $maxnumber) {//
        global $db;
        $isExist = false;
        do {
            $randId = str_pad(rand(0, $maxnumber), $jumlahrandom, "0", STR_PAD_LEFT);
            $id = $prefix . '.' . $randId;

            $aData = $db->get_data($db, $table, $field, $field . '="' . $id . '"', '', '');
            if (!empty($aData))
                $isExist = (!empty($aData)) ? true : false;
        } while ($isExist);

        return $id;
    }

    public function get_datetime_sql() {
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d H:i:s');
    }

    public function get_date() {
        date_default_timezone_set('Asia/Jakarta');
        return date('Ymd');
    }

    public function get_datetime() {
        date_default_timezone_set('Asia/Jakarta');
        return date('YmdHis');
    }

    public function get_daydate() {
        date_default_timezone_set('Asia/Jakarta');
        return date('Y-m-d');
    }

    public function set_rupiah($angka) {

        $result = "Rp." . number_format($angka, 0, ',', '.');
        return $result;
    }

//header('Content-type: application/pdf');
    public function headerDownload($name = 'data', $type = 'xls', $contenttype = null) {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: private");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $name . "." . $type);
        header("Content-Transfer-Encoding: binary ");
        if ($contenttype != null) {
            if (is_array($contenttype)) {
                foreach ($contenttype as $value) {
                    header("Content-type: " . $value . "");
                }
            } else {
                header("Content-type: " . $contenttype . "");
            }
        }
    }

    public function bytes_converter($bytes) {
        $satuan = array('Bytes', 'KB', 'MB', 'TB');
        $kali = floor(log($bytes, 1024));
        $bagi = round($bytes / pow(1024, $kali), 2);
        return $bagi . ' ' . $satuan[$kali];
    }

    public function encodestrhtml($str) {
        return htmlentities(trim($str), ENT_QUOTES);
    }

    public function decodestrhtml($str) {
        return addslashes(html_entity_decode($str, ENT_QUOTES));
    }

//aan
    public function debugArray($arr) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    public function sendMail($to, $subject, $body) {
        require 'email/PHPMailerAutoload.php';
        $mail = new PHPMailer;

        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for GMail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587; // or 587
        $mail->IsHTML(true);
        $mail->Username = "blog.pekanbarupark@gmail.com";
        $mail->Password = "pekanbarupark123";
        $mail->SetFrom("blog.pekanbarupark@gmail.com");
        $mail->Subject = $subject;
        $mail->Body = $body;

        foreach ($to as $value) {
            $mail->addAddress($value);               // Name is optional
        }

//    $mail->AddAddress("andangcharisma@gmail.com");
        if (!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }

    public function getDateNext($next) {
        $next_day = time() + 86400 * $next;
        return $days = date("Y-m-d", $next_day);
    }

    public function getDateNextFrom($start, $next) {
        $today = date($start);
        return $next_month = date("d-m-Y", strtotime("$today +$next day"));
    }

    public function getDateNextInst($date, $month) {
        $date_ex = explode("-", $date);
//    debugArray($date_ex);
        $m = ($date_ex[1] * 1) + $month;
        if ($m > 12) {
            $d = $date_ex[0];
            $m = $m - 12;
            $y = $date_ex[2] + 1;
        } else {
            $d = $date_ex[0];
            $m = $m;
            $y = $date_ex[2];
        }
        $m = (strlen($m) > 1) ? $m : "0" . $m;
        return $d . "-" . $m . "-" . $y;
    }

    public function get_date_format($date) {
        $originalDate = $date;
        $newDate = date("d/m/Y", strtotime($originalDate));
        return $newDate;
    }

    function search_by($array, $field_by, $field_content, $field_search) {

        $result = '';
        for ($i = 0; $i < count($array); $i++) {
            if ($field_content == $array[$i][$field_by]) {
                $result = $array[$i][$field_search];
            }
        }
        return $result;
    }

}

<?php

/* ======================================================
  Connect to Database
  ====================================================== */

//$conn = new db($dbserver, $dbuser, $dbpass, $dbname);

class db extends mysqli {

    public function __construct($host, $user, $pass, $db) {
        parent::__construct($host, $user, $pass, $db);
        if (mysqli_connect_error()) {
            die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
        }
    }

    public function get_data($conn, $tbl, $fldSelect, $crt, $ord, $grp) {// data tertentu
        $qs = "SELECT " . $fldSelect . " FROM " . $tbl;
        $qs .= ($crt != '') ? " WHERE " . $crt . " " : "";
        $qs .= ($grp != '') ? " GROUP BY " . $grp : "";
        $qs .= ($ord != '') ? " ORDER BY " . $ord : "";
        //echo $qs . ' | ';
        $rData = array();
        if ($result = $conn->query($qs)) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $rData[] = $row;
            }
        } else {
            die("Query [getCertainData] Error!" . mysqli_error($conn));
        }//nelse

        return $rData;
    }

    public function add_data($conn, $tbl, $val) {
        //global $conn;
        $bOk = false;

        if (count($val) > 0) {

            $field = array_keys($val);
            $value = array_values($val);

            $value_query = '';
            for ($i = 0; $i < count($field); $i++) {
                $value_query .= $field[$i] . ' = \'' . $value[$i] . '\'';
                if ($i != count($field) - 1)
                    $value_query.=',';
            }

            $qs = "INSERT INTO " . $tbl . " SET " . $value_query;

            if ($result = $conn->query($qs)) {

                if ($conn->affected_rows > 0) {
                    $bOk = true;
                }
            } else {
                die("Query [insertData] Error!" . mysqli_error($conn));
            }//nelse
        }
        return $bOk;
    }

    public function update_data($conn, $tbl, $val, $crt) {
        //global $conn;

        $bOk = false;

        if (count($val) > 0) {
            $field = array_keys($val);
            $value = array_values($val);
            $value_query = '';

            for ($i = 0; $i < count($field); $i++) {

                $value_query .= $field[$i] . ' = \'' . $value[$i] . '\'';
                if ($i != count($field) - 1)
                    $value_query.=',';
            }

            $qs = "UPDATE " . $tbl . " SET " . $value_query;
            $qs.= ($crt != "") ? " WHERE " . $crt : "";
            // echo $qs;
//exit();
            if ($result = $conn->query($qs)) {
                $bOk = true;
            } else {
                die("Query [updateData] Error!" . mysqli_error($conn));
            }//nelse
        }
        return $bOk;
    }

    public function delete_data($conn, $tbl, $crt) {
        //global $conn;
        $bOk = false;

        if ($crt != '') {
            $qs = "DELETE FROM " . $tbl;
            $qs .= " WHERE " . $crt;

            if ($result = $conn->query($qs)) {
                if ($conn->affected_rows > 0) {
                    $bOk = true;
                }
            } else {
                die("Query [deleteData] Error!" . mysqli_error($conn));
            }//nelse
        }
        return $bOk;
    }

    public function get_data_join($conn, $tbl1, $tbl2, $id, $fldSelect, $crt, $ord, $grp) {// data tertentu
        //global $conn;
        $qs = "SELECT " . $fldSelect . " FROM " . $tbl1 . " INNER JOIN " . $tbl2 . " USING (" . $id . ")";
        $qs .= ($crt != '') ? " WHERE " . $crt . " " : "";
        $qs .= ($ord != '') ? " ORDER BY " . $ord : "";
        $qs .= ($grp != '') ? " GROUP BY " . $grp : "";
        //echo $qs.' | ';
        $rData = array();
        if ($result = $conn->query($qs)) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $rData[] = $row;
            }
        } else {
            die("Query [getCertainDataJoin] Error!" . mysqli_error($conn));
        }//nelse


        return $rData;
    }

    public function get_curr_data($conn, $tbl, $fldSelect, $crt) {// data tertentu
        //global $conn;
        $qs = "SELECT " . $fldSelect . " FROM " . $tbl;
        $qs .= ($crt != '') ? " WHERE " . $crt . " " : "";
        //echo $qs.' | ';
        $data = '';
        if ($result = $conn->query($qs)) {
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $data = $row[$fldSelect];
            }
        } else {
            die("Query [getCurrentData] Error!" . mysqli_error($conn));
        }//nelse

        return $data;
    }

//aan
    public function custom_query($conn, $qs) {// data tertentu
        //global $conn;
        $rData = array();
        if ($result = $conn->query($qs)) {
            /* fetch associative array */
            //while ($row = $result->fetch_assoc()) {
            //    $rData[] = $row;
            //}
        } else {
            die("Query [getCertainData] Error!" . mysqli_error($conn));
        }//nelse


        return $rData;
    }

}

?>

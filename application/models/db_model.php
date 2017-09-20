<?php
class db_model extends CI_Controller{
 
   function __construct(){
        parent::__construct();
    }
public function get_data(){	
 $conn = oci_connect("hcp","hcp","cthcp");
 $sql=" SELECT SEGMENT_NO,SEGMENT_NAME   FROM gl_segment";
 
$stid = oci_parse($conn, $sql);
oci_execute($stid);

$row = oci_fetch_all($stid, $result);
 
//oci_free_statement($stid);
//oci_close($this->db->conn_id);
 
 /*
echo "<html><head>";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
echo "<meta http-equiv=\"Content-Language\" content=\"zh-cn\" /></head><body>";
echo "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
        echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    echo "</tr>\n";
}
echo "</table></body></html>\n";
 */
 return $result;
}
public function get_dataR(){	
 $conn = oci_connect("hcp","hcp","cthcp");
 $sql=" SELECT  SEGMENT_NAME  FROM gl_segment";
 
$stid = oci_parse($conn, $sql);
oci_execute($stid);

 //oci_fetch_all($stid, $result);

$row = oci_fetch_array($stid, OCI_BOTH);
 var_dump($row); 
return $row;
}

}
?>
<?php

$conn =  oci_connect('DAVIDV','2367', "(DESCRIPTION = (LOAD_BALANCE = yes)
                            (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP) (HOST = 10.6.53.21) (PORT = 1521) ) )
                            (CONNECT_DATA = (FAILOVER_MODE = (TYPE = select) (METHOD = basic) (RETRIES = 180) (DELAY = 5) )
                            (SERVICE_NAME = ORACLE) ) )",'AL32UTF8');

if (!$conn) {
  echo "Error";
}else{
   echo "OK";

   $stid = oci_parse($conn, 'SELECT * FROM GG_TCORREL');
   oci_execute($stid);
   
   echo "<table border='1'>\n";
   while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
       echo "<tr>\n";
       foreach ($row as $item) {
           echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "") . "</td>\n";
       }
       echo "</tr>\n";
   }
   echo "</table>\n";






}

?>
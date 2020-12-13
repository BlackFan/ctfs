<?php
//case when(ascii(substr((select login||chr(58)||password from administrators limit 1 offset 1),1,1))>0)then 1 else 0 end
include('header.inc');
include('webdevelopconfig.php');
if(isset($_GET[id])){
$id=$_GET[id];
$result = pg_query("select descr from content where id=$id 
order by id");
while ( $row = pg_fetch_assoc ( $result ) ) {
print $row ['descr'];
}
} else {
$result = pg_query("select descr from content where id=1");
while ( $row = pg_fetch_assoc ( $result ) ) {
print $row ['descr'];
}
}
include('footer.inc');

?>
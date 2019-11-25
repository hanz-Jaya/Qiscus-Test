<?php
$json_url="https://private-0e6b9-ganjarwidiatmansyah.apiary-mock.com/people?results=10&nat=en";
$ch = curl_init( $json_url );
$options = array(
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => array('Content-type: application/json') 
);
curl_setopt_array( $ch,$options); 
$result =  curl_exec($ch);
$decode = json_decode($result, true);
$jumlah_data = $decode["info"]["results"];

function format_tanggal($tanggal){
    $ex_tanggal_awal=explode(" ",$tanggal);
    $ex_tanggal=explode("-",$ex_tanggal_awal[0]);
    $bulan_arr=array("","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
    $tanggal_view=$ex_tanggal[2];
    $bulan_view=$bulan_arr[(int)$ex_tanggal[1]];
    $tahun_view=$ex_tanggal[0];
    return $format_tanggal_view = $tanggal_view." ".$bulan_view." ".$tahun_view;
}

?>
<table width="900px" id="table_target">
    <thead>
        <tr>
            <th>No</th>
            <th>Images</th>
            <th>Name</th>
            <th>Date of Birth</th>
            <th>Time</th>
            <th>Gander</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            for($a=0;$a<$jumlah_data;$a++):
                $dob        = substr($decode["results"][$a]["dob"]["date"],0,10);
                $time       = $decode["results"][$a]["location"]["timezone"]["offset"];

                $offset = $time;
                list($hours, $minutes) = explode(':', $offset);
                $seconds = $hours * 60 * 60 + $minutes * 60;
                $tz = timezone_name_from_abbr('', $seconds, 1);
                if($tz === false) $tz = timezone_name_from_abbr('', $seconds, 0);
                $timeview   = $tz . ': ' . date('r');             
        ?>
        <tr>
            <td><?php echo $a+1; ?></td>
            <td>
                <a href="<?php echo $decode["results"][$a]["picture"]["large"]; ?>" target="_blank">
                    <img src="<?php echo $decode["results"][$a]["picture"]["thumbnail"]; ?>" >
                </a>
            </td>
            <td><?php echo ucwords($decode["results"][$a]["name"]["first"]." ".$decode["results"][$a]["name"]["last"]); ?></td>
            <td><?php echo format_tanggal($dob); ?></td>
            <td><?php echo $timeview; ?></td>
            <td><?php echo ucwords($decode["results"][$a]["gender"]); ?></td>
        </tr>
        <?php endfor; ?> 
    </tbody>
</table>
<style>
#table_target   { border-collapse: collapse; border: 1px solid grey; font-family: Arial, Helvetica, sans-serif; }
#table_target th { background-color: aliceblue;}
#table_target th,td { border: 1px solid grey; padding: 2px; text-align: center; }
</style>
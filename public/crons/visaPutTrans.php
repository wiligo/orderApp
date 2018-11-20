<?php

date_default_timezone_set ( 'Africa/Accra' );
$auth = array ();
$auth['ARB']['user'] = "brahab3b)m32345";
$auth['ARB']['pwd'] = "faw0s3begy3bLowQ";


$auth ['url'] = "http://platform.tfpcore.com";


echo $query_rsTrans = "SELECT * FROM trans where dn_status = '0' AND accountmsisdn <> '0241763214' LIMIT 25";//Determine which transaction is live or test so that you can pick correct transactions.
$rsTrans = runQuery($query_rsTrans, "RAW");
echo "Sadat";
echo $totalRows_rsTrans = mysql_num_rows($rsTrans);
//echo "<br />";
// end Recordset

if ($totalRows_rsTrans > 0) {
	// *******************************************************************************************
	// *******************************************************************************************
	// *******************************************************************************************
	// create a new curl resource
	$ch = curl_init ();

	while ( ($rsTrans_row = mysql_fetch_assoc ( $rsTrans )) != false ) {
	$bank = $rsTrans_row['source_id'];
	$conn_id = md5 ( strftime ( "%Y%b%W%a" ) . $rsTrans_row['source_id'] );
    $scriptAuth = "?id=$bank&cx=$conn_id&login=" . md5 ( $auth[$rsTrans_row['source_id']] ['user'] . $auth[$rsTrans_row['source_id']] ['pwd'] );
    $murl = $auth ['url'] . "/banks/getTrans.php$scriptAuth";
		$rec = "";
		$rec .= "trans_id='" . $rsTrans_row ['trans_id'] . "'";
		$rec .= ",bank_id='" . $bank . "'";
		$rec .= ",inst_id='" . $rsTrans_row ['merchant_id'] . "'";
		$rec .= ",branch='" . $rsTrans_row ['source_branch_code'] . "'";
		$rec .= ",account_no=''";
		$rec .= ",payer_id='" . $rsTrans_row ['sub_merchant_id'] . "'";
		$rec .= ",date_created='" . substr($rsTrans_row ['created'],0,10) . "'";
		$rec .= ",date_processed='" . substr($rsTrans_row ['created'],0,10) . "'";
		$rec .= ",processing_time='" . substr($rsTrans_row ['created'],-8) . "'";
		$rec .= ",desc_id='" . $rsTrans_row ['product_id'] . "'";
		$rec .= ",currency='" . $rsTrans_row ['currency'] . "'";
		$rec .= ",amount='" . $rsTrans_row ['amount'] . "'";
		$rec .= ",subscription='" . $rsTrans_row ['fees'] . "'";
		$rec .= ",payer_name='" . mysql_escape_string ( $rsTrans_row ['accountname'] ) . "'";
		$rec .= ",bank_trans_id='" . $rsTrans_row ['source_trans_id'] . "'";
		$rec .= ",alt1='" . $rsTrans_row ['accountref'] . "'";
		$rec .= ",alt2='" . $rsTrans_row ['alt2'] . "'";
		$rec .= ",alt3='" . $rsTrans_row ['alt3'] . "'";
		$rec .= ",alt4='" . $rsTrans_row ['trans_type'] . "'";
		$rec .= ",alt5='" . $rsTrans_row ['payment_desc'] . "'";
		$rec .= ",revtrans_id='" . $rsTrans_row ['rev_trans_id'] . "'";
		$rec .= ",up_status='1'";
		//print_r ( $rec );
		$rec = RC4 ( $rec,$rsTrans_row['source_id'] );
		$rec = base64_encode ( urlencode ( $rec ) );
		echo $url = "$murl&rec=$rec";
		// set URL and other appropriate options
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// grab URL and pass it to the browser
		echo $response = curl_exec ( $ch );
		if ($response == '1') {
			echo $query = "UPDATE trans SET dn_status='1' WHERE trans_id='" . $rsTrans_row ['trans_id'] . "'";
			runQuery ($query,"RAW");
		}
		// print_r($rsTrans_row);
	}

	// close curl resource, and free up system resources
	// *******************************************************************************************
	// *******************************************************************************************
	// *******************************************************************************************
	curl_close ( $ch );
}
// echo $url;


function RC4($data,$bank) { // ecncrypt $data with the key in $keyfile with an rc4 algorithm

		$keyFile['ARB'] = "ece7ecf8256a1dc12cd094912464396124b1f30e91b03a9bf9191d12b8b701ecssl3l3lk2okskdh4h3jfusureurRReudusk4Dd24ksjfdjsdSdjfj4Sdfju39sdjk434sdj@sjkdsdjssjsdjdsdjsdjddjddjdjdjdjdjd232o3324949aakzkzxmxmvjki2944.dmdjkgkdkfkf<fki3949294485829193i4kereifejksiwej2DADiweiwsWW#393sksdjdjdd,d3kdk2@k1kfkskf";

		$pwd = $keyFile[$bank];

		$pwd_length = strlen ( $pwd );
		for($i = 0; $i < 255; $i ++) {
			$key [$i] = ord ( substr ( $pwd, ($i % $pwd_length) + 1, 1 ) );
			$counter [$i] = $i;
		}
		for($i = 0; $i < 255; $i ++) {
			$x = ($x + $counter [$i] + $key [$i]) % 256;
			$temp_swap = $counter [$i];
			$counter [$i] = $counter [$x];
			$counter [$x] = $temp_swap;
		}
		for($i = 0; $i < strlen ( $data ); $i ++) {
			$a = ($a + 1) % 256;
			$j = ($j + $counter [$a]) % 256;
			$temp = $counter [$a];
			$counter [$a] = $counter [$j];
			$counter [$j] = $temp;
			$k = $counter [(($counter [$a] + $counter [$j]) % 256)];
			$Zcipher = ord ( substr ( $data, $i, 1 ) ) ^ $k;
			$Zcrypt .= chr ( $Zcipher );
		}
		return $Zcrypt;
	}


function runQuery($query,$type) {
	$dbhost='127.0.0.1';
    $dbusername='root';
    $dbpassword='';
    $dbname='tfBank';

    $connect = mysqli_connect($dbhost, $dbusername, $dbpassword) or die("Cannot connect to Database: ");
    mysqli_select_db($dbname, $connect) or die("Database ".$dbname." not found: ");

	echo "connected";

		$var = mysqli_query ( $query, $connect ) or die();
		switch ($type) {

			case "ARR" :

				while ( ($row = mysql_fetch_assoc ( $var )) != false ) {
					$result [] = $row;
				}
				break;

			case "AFF" :

				$result = mysql_affected_rows ( $connect );
				break;

			case "NUM" :
				$result = mysql_num_rows ($var);
				break;

			case "RAW" :
				$result = $var;
				break;

			default :
				$result = mysql_fetch_assoc ( $var );
				break;
		}

		return $result;
    }
    
    

?>

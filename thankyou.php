<?php
/**
 * Template Name: Thankyou
 */
get_header();
//$user = wp_get_current_user();
?>
<?php
$email = '';
if(isset($_REQUEST) && $_REQUEST['trans_num']){
	global $wpdb;    
	$result = $wpdb->get_results( "SELECT amount,user_id,trans_num FROM wp_mepr_transactions WHERE trans_num = '".$_REQUEST['trans_num']."'");
	
	if($result && $result[0]){
		$user = get_user_by( 'ID', $result[0]->user_id );
		$email = $user->data->user_email;
			?>
			<script type="text/javascript">
				var email = '<?php echo $email;?>';
					if (window.$FPROM){
					$FPROM.trackSignup({email: email});
					} else {
					_fprom=window._fprom||[];window._fprom=_fprom;
					_fprom.push(["event","signup"]);
					_fprom.push(["email",email]);
					console.log('_fprom>>',_fprom);
				}
			</script>
		<?php
		
		if($result[0]->amount > 0){
			$post = [
				'email' => $email,
				'amount' => ($result[0]->amount * 100),
				'event_id'   => $result[0]->trans_num,
			];
			/* echo '<pre>post';
			print_r(json_encode($post)); */
			$ch = curl_init();   
			curl_setopt($ch, CURLOPT_URL,"https://firstpromoter.com/api/v1/track/sale");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-api-key: 5c4ebdf7622bea1aa2c2f8be467d0c01',));
			curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$server_output = curl_exec($ch);
			$server_output = json_decode($server_output,true);
			/* echo '<pre>server_output';
			print_r($server_output); */
			curl_close ($ch);	
		}
	}
}

//Start the session.
session_start();

//If the session variable does not exist,
//presume that the page has not been refreshed yet.
if(!isset($_SESSION['already_refreshed'])){

    //Number of seconds to refresh the page after.
    $refreshAfter = 1;

    //Send a Refresh header.
    header('Refresh: ' . $refreshAfter);

    //Set the session variable so that we don't
    //refresh again.
    $_SESSION['already_refreshed'] = true;

}

?>
<h2>Thank You.</h2><br>
<h5>
	Your subscription has been set up successfully.
</h5>
<?php
 get_footer();
?>

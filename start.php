<?php
//	if( ! isset( $_SERVER['REMOTE_ADDR'] ) || $_SERVER['REMOTE_ADDR'] != '31.220.16.184' ) {
//		if( ! isset( $_SERVER['USER'] ) || $_SERVER['USER'] !== "root" ) {
//			header("HTTP/1.0 404 Not Found");
//			exit;
//		}
//	}

	error_reporting( E_ALL );
	ini_set( 'display_errors', 'on' );
	date_default_timezone_set( "UTC" );

	require_once( "config_safe.php" );

	try{

		/*****
			ADAPTERS: facades around wrapper library for native restful API's
			BOTS: functions are an app.
			GUI: it is a web app.
			API: can make rest calls to them through HTTP requests like with AJAX.

			TODO: make a Bot class
			NOTE: do not print_r the $Adapter class because it may leak keys and secrets on the web.

			One way to run a bot would be to execute a php script in a cron job.
			Another way would be to make a while(1) loop that continuously runs.
			It would be a good idea to monitor the process with e-mail alerts, etc...
			Don't put the while(1) on in a cron or else a new instance will be launched each time that will stay executing... sure to get banhammer on API!!!

			------

			Run a PHP process for each exchange from the terminal like this:
			php start.php Bitfinex > bitfinex_out.txt & php start.php Bitstamp > bitstamp_out.txt & php start.php Bittrex > bittrex_out.txt & php start.php Btce > btce_out.txt & php start.php Coinbase > coinbase_out.txt & php start.php Poloniex > poloniex_out.txt & 

			Kill the processes from the terminal like this:
			pkill -9 php

			Here are some sample bots being used in such a simple way:

		 *****/

			echo "\n\n***************************";
		//	sleep(1);
			echo "\n***************************\n\n";

			
		//	cancel_buy_order( $Adapters );
		//build_cache( $Adapters );
		//run_tests( $Adapters, $Tester );
		//make_max_orders( $Adapters );
		make_min_orders( $Adapters );
		//make_deposit_addresses( $Adapters, $Tester ); //todo: get the Tester object out of here and put in run_tests above...
		//human_readable_summary( $exchanges, $currencies, $markets, $worths, $volumes );//need to get these from Adapter & Utilities first like in run_tests bot...
		//disperse_funds( array( $Adapters['Btce'] ), array( $Adapters['Bitfinex'], $Adapters['Bitstamp'], $Adapters['Bittrex'], $Adapters['Bter'], $Adapters['Poloniex'] ), 'BTC', '0.02222222' ); //$from_arr, $to_arr, $curr_arr

	} catch( Exception $e ){
		echo $e->getMessage() . "\n";
	}

?> 

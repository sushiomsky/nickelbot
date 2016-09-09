<?PHP

	/*
		@Author Adam Cox

		This is a simple example of a bot that will make minimum buy and sell orders for every currency across every exchange.

		A 1% gap is very large for BTC while very small for some ALTs

		TODO
		 - a lot
	*/

	function cancel_buy_order( $Adapters ) {
		$planned_timeout = time();
		foreach( $Adapters as $Adapter ) {
			echo "*** " . get_class( $Adapter ) . " ***\n";

			//_____get the markets to loop over:
			$market_summaries = $Adapter->get_market_summaries();
			$num_markets = sizeof( $market_summaries );

			$bal = [];
			shuffle( $market_summaries );
			foreach( $market_summaries as $market_summary ) {
				//_____get currencies/balances:
				$market = $market_summary['market'];
				$curs_bq = explode( "-", $market );
				$base_cur = $curs_bq[0];
				$quote_cur = $curs_bq[1];
				$base_bal_arr = $Adapter->get_balance( $base_cur, array( 'type' => 'exchange' ) );
				$base_bal = isset( $bal[ $base_cur ] ) ? $bal[ $base_cur ] : $base_bal_arr['available'];
				$quote_bal_arr = $Adapter->get_balance( $quote_cur, array( 'type' => 'exchange' ) );
				$quote_bal = isset( $bal[ $quote_cur ] ) ? $bal[ $quote_cur ] : $quote_bal_arr['available'];
				//_____calculate some variables that are rather trivial:
				$precision = $market_summary['price_precision'] + 2;	//_____significant digits - example 1: "1.12" has 2 as PP. example 2: "1.23532" has 5 as PP.
				$epsilon = 1 / pow( 10, $precision );					//_____smallest unit of base currency that exchange recognizes: if PP is 3, then it is 0.001.
				$buy_price = $market_summary["low"];					//_____buy at same price as highest bid.
				$sell_price = $market_summary['high'];					//_____sell at same price as lowest ask.
				$spread = $market_summary["ask"] - $market_summary["bid"];						//_____difference between highest bid and lowest ask.
				$spread_pct = ($spread/$market_summary["bid"]*100);
				$order_size = Utilities::get_min_order_size( $market_summary['minimum_order_size_base'], 0.0001, $epsilon, $sell_price, $precision);
				$buy_price = number_format( $market_summary['low'], $precision, '.', '' );
				$sell_price = number_format( $market_summary['high'], $precision, '.', '' );
				ob_flush();
				
					
			//_____get open orders, sort them by creation date and remove the oldest orders:
			$open_orders = $Adapter->get_open_orders($market_summary['market']);
			
							
			//_____remove oldest orders for each valid market...
			if (sizeof($open_orders)>0) {
				foreach ($open_orders as $order){
					if ($order['type'] == 'buy' ) {
						$output = $Adapter->cancel( $order['id'], array( "market" => $market_summary['market'] ) );
						//print_r($output)." ->  Cancel order  \n";
					}
					//if ($order['type'] == 'sell' && $order['timestamp_created'] < (time()-(3600*48))) {
					//	$output = $Adapter->cancel( $order['id'], array( "market" => $market_summary['market'] ) );
						//print_r($output)." ->  Cancel order  \n";
			//		}
				}
			}		
			
			
				
			
				echo " -> " . get_class( $Adapter ) . " \n";
				echo " -> base currency ($base_cur) \n";
				echo " -> base currency balance ($base_bal) \n";
				echo " -> quote currency ($quote_cur) \n";
				echo " -> quote currency balance ($quote_bal) \n";

			
				echo " -> precision $precision \n";
				echo " -> epsilon $epsilon \n";
				echo " -> buy price: $buy_price \n";
				echo " -> sell price: $sell_price \n";
				echo " -> spread: $spread \n";
				echo " -> spread %: $spread_pct \n";
				echo " ->24h high: ".$market_summary["high"]." \n";
				echo " ->24h low: ".$market_summary["low"]." \n";

				echo " -> final formatted buy price: $buy_price \n";
				echo " -> final formatted sell price: $sell_price \n";
				
			
				echo "\n";
			}
		}
	}

?>

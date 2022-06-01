<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  11px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 11px;
	vertical-align:top;
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
}
.text-right{
    text-align:right;
}
</style>
<table cellspacing="0" cellpadding="0">
    	<thead>
            <tr>
                <th width="50%">Assets</th>
                <th>Liabilities</th>
            </tr>
    	</thead>
    	<tbody>
        	<tr>
				<td>
                    <table cellspacing="0" cellpadding="0">
						<thead>
                            <tr>
                                <th colspan="2">Current Assets</th>
                            </tr>
                        </thead>
						<?php 
						$total = 0;
						$account_payable = array();
						$sql="select * from account where status=1";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = get_account_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["title"] ); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$account_payable[] = array(
											"name" => unslash($r["title"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						
						?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right"><?php echo curr_format( $total )?></th>
                        </tr>
                  	</table>
              	</td>
                <td>
                    <table cellspacing="0" cellpadding="0">
						<?php 
						if( count($account_payable) > 0){
							?>
							<thead>
                                <tr>
                                    <th colspan="2">Accounts</th>
                                </tr>
                            </thead>
							<?php
							$sn=1;
							$total = 0;
							foreach( $account_payable as $account ){
								$total += $account[ "balance" ];
								?>
								<tr>
									<td><?php echo $account["name"]; ?></td>
									<td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
								</tr>
								<?php 
								$sn++;
							}
							?>
                            <tr>
                            	<th>Total</th>
                                <th class="text-right"><?php echo curr_format( $total )?></th>
                            </tr>
							<?php	
						}
						?>
                  	</table>
              	</td>
           	</tr>
    	</tbody>
  	</table>
<?php
die;

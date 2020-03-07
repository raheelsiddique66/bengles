<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title"><?php echo $employee["name"]?></h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Report</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
            <a href="employee_manage.php?tab=list" class="btn btn-light editproject">Back to List</a> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
            <a class="btn print-btn" href="employee_manage.php?tab=employee_report_print&id=<?php echo $employee["id"]; ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
            	<input type="hidden" name="tab" value="employee_report" />
                <span class="col-sm-1">Employee</span>
                <div class="col-sm-2">
                    <select name="id">
                    	<?php
                        $rs=doquery( "select * from employees where status=1 order by name", $dblink );
                        if( numrows( $rs ) > 0 ) {
                            while( $r = dofetch( $rs ) ) {
                                ?>
                                <option value="<?php echo $r[ "id" ]?>"<?php echo $r[ "id" ]==$employee["id"]?' selected':''?>><?php echo unslash( $r[ "name" ] )?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
               <span class="col-sm-1 text-to">From</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date From" name="date_from" id="date_from" placeholder="" class="form-control date-picker"  value="<?php echo $date_from?>" >
                </div>
                <span class="col-sm-1 text-to">To</span>
                <div class="col-sm-2">
                    <input type="text" title="Enter Date To" name="date_to" id="date_to" placeholder="" class="form-control date-picker"  value="<?php echo $date_to?>" >
                </div>               
                <div class="col-sm-3 text-left">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
          	</form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="5%" class="text-center">S.no</th>
                <th width="15%">
                    <a href="employee_manage.php?tab=employee_report&id=<?php echo $employee["id"]; ?>&order_by=date&order=<?php echo $order=="asc"?"desc":"asc"?>" class="sorting">
                    Date
                    <?php
                    if( $order_by == "date" ) {
                        ?>
                        <span class="sort-icon">
								<i class="fa fa-angle-<?php echo $order=="asc"?"up":"down"?>" data-hover_in="<?php echo $order=="asc"?"down":"up"?>" data-hover_out="<?php echo $order=="desc"?"down":"up"?>" aria-hidden="true"></i>
							</span>
                        <?php
                    }
                    ?>
                    </a>
                </th>
                <th width="15%">Details</th>
                <th width="10%" class="text-right">Debit</th>
                <th width="10%" class="text-right">Credit</th>
                <th width="10%" class="text-right">Balance</th>
            </tr>
    	</thead>
    	<tbody>
            <?php
            $sql="select concat( 'Salary #', id) as details, date, 0 as debit, calculated_salary as credit from employee_salary where employee_id = '".$employee[ "id" ]."' $extra union select 'Payment', date, amount as debit, 0 as credit from employee_payment where employee_id = '".$employee[ "id" ]."' $extra order by $order_by $order";
            $rs=doquery( $sql, $dblink );
            if(numrows($rs)>0){
                $sn=1;
                $balance = get_employee_balance( $employee["id"], date_dbconvert($date_from));
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-left"><strong><?php echo $order == 'desc'?'Closing':'Opening'?> Balance</strong></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo $balance ?></td>
                </tr>
                <?php
                while($r=dofetch($rs)){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td><?php echo date_convert($r['date']); ?></td>
                        <td><?php echo $r['details']; ?></td>
                        <td class="text-right"><?php echo curr_format($r['debit']); ?></td>
                        <td class="text-right"><?php echo curr_format($r['credit']); ?></td>
                        <td class="text-right"><?php if($order == 'asc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} echo curr_format( $balance ); if($order == 'desc'){$balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);} ?></td>
                    </tr>
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                	<td colspan="2"></td>
                    <td><?php echo $order != 'desc'?'Closing':'Opening'?> Balance</td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo curr_format( $balance )?></td>
                </tr>
                <?php 
            } 
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
    	</tbody>
  	</table>
</div>

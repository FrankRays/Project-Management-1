<table class="zebra-striped" id="orderSortTable">
	<thead>
		<tr>
		<th class="header headerSortUp">Ticket Num</th>
		<th class="red header headerSortUp">Product Id</th>
		<th class="yellow header headerSortUp">Quantity</th>
		<th class="blue header headerSortUp">Unit Price</th>
		<th class="blue header headerSortUp">Total Price</th>
		<th class="purple header headerSortUp">Date Processed</th>
		<th class="orange header headerSortUp">Status</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach($stock_tickets as $ticket) { ?>
	    <tr id="<?php echo $ticket['ticketNum'] ?>">
		<td class="ticketNum"><a href="/stock/admin_show/<?php echo $ticket['ticketNum'] ?>"><?php echo $ticket['ticketNum'] ?></a></td>
		<td class="pid"><a href="/products/admin_show/<?php echo $ticket['pid'] ?>"><?php echo $ticket['pid'] ?></td>
		<td class="quantity"><?php echo $ticket['Quantity'] ?></td>
		<td class="priceUSD">$<?php echo $ticket['PriceUSD'] ?></td>
		<td class="totalPriceUSD">$<?php echo $ticket['PriceUSD'] * $ticket['Quantity'] ?></td>
		<td class="date"><?php echo $ticket['DateSubmitted'] ?></td>
		<td class="status"><?php echo $ticket['Status'] ?></td>
	<?php } ?>
	</tbody>
</table>
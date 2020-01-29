<!--
 * @author   Twistpay
 * @version  1.0.1
-->

<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1>
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Transactions</h3>
        <!-- <div class="trans-filter pull-right">
          <?php if (!empty($customers)) {?>
          <select class="trans-customers">
            <option value="0" <?php if ($selected=='0') { ?> selected="selected" <?php } ?>>All Customers</option>
            <?php foreach ($customers as $customer) { ?>
            <option value="<?php echo $customer['customer_id']; ?>" <?php if ($selected==$customer['customer_id']) {?> selected="selected" <?php } ?> title="<?php echo $customer['email']; ?>"><?php echo $customer['name']; ?></option>
            <?php } ?>
          </select>
        <?php } ?>
        </div> -->
      </div>
      <div class="panel-body">
        <?php if (empty($trans)) { ?>
        <div class="nodata">No transactions</div>
        <?php } else { ?>
        <table class="twispay-logs" cellpading="10px" cellspacing="0" width="100%" border="1">
          <thead>
            <tr>
              <th colspan="3" class="big-border">Website</th>
              <th colspan="10">Twispay</th>
            </tr>
            <tr>
              <th>User Id</th>
              <th>Order Id</th>
              <th class="big-border">Invoice Id</th>
              <th>Customer Id</th>
              <th>Order Id</th>
              <th>Card Id</th>
              <th>Transaction Id</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Currency</th>
              <th>Date</th>
              <th>Refund Date</th>
              <th>Refund</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($trans as $tran) { ?>
            <tr>
              <td><?php echo $tran['identifier']; ?></td>
              <td><?php echo $tran['order_id']; ?></td>
              <td class="big-border"><?php echo $tran['invoice']; ?></td>
              <td><?php echo $tran['customerId']; ?></td>
              <td><?php echo $tran['orderId']; ?></td>
              <td><?php echo $tran['cardId']; ?></td>
              <td><?php echo $tran['transactionId']; ?></td>
              <td><?php echo $tran['status']; ?></td>
              <td><?php echo $tran['amount']; ?></td>
              <td><?php echo $tran['currency']; ?></td>
              <td><?php echo $tran['date']; ?></td>
              <td><?php if ($tran['status']=='refunded') {?><?php echo $tran['refund_date']; ?> <?php } ?></td>
              <td><?php if ($tran['status']=='complete-ok') {?><i class="refund fa fa-times red" aria-hidden="true" data-transid="<?php echo $tran['transactionId']; ?>" data-orderid="<?php echo $tran['order_id']; ?>"
                  data-store="<?php echo $tran['store_id']; ?>"></i><?php } ?></td>
            </tr>
            <?php
          }
          ?>
          </tbody>
        </table>
        <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var redir = "<?php echo $redir; ?>";
  var token = "<?php echo $token; ?>";
  $(document).on('change', 'select.trans-customers', function() {
    var encoded = redir + $(this).val() + '&user_token=' + token;
    var decoded = encoded.replace(/&amp;/g, '&');
    window.location.href = decoded;
  });

  $(document).on('click', 'i.refund', function() {
    var transid = $(this).attr('data-transid');
    var orderid = $(this).attr('data-orderid');
    var storeid = $(this).attr('data-store');
    var parent = $(this).parents('tr');
    var token = "<?php echo $token; ?>";
    $(parent).css('opacity', '0.5');
    var refund = "<?php echo $refund; ?>" + '&token=' + token;;
    setTimeout(function() {
      if (window.confirm("Are you sure you want to refund transaction #" + transid + " ?\nProcess is not reversible !!!")) {
        $(parent).css('opacity', '1');
        $.ajax({
          url: refund,
          dataType: 'json',
          type: 'post',
          data: {
            'transid': transid,
            'orderid': orderid
          },
          success: function(data) {
            if (data.refunded != 1) {
              alert(data.status);
            } else {
              console.log('<?php echo $catalog; ?>index.php?route=api/order/history&api_token=<?php echo $token; ?>&store_id=' + storeid + '&order_id=' + orderid);
              $.ajax({
                url: '<?php echo $catalog; ?>index.php?route=api/order/history&api_token=<?php echo $token; ?>&store_id=' + storeid + '&order_id=' + orderid,
                type: 'post',
                dataType: 'json',
                data: 'order_status_id=11&notify=1&override=1&append=1&comment=' + encodeURIComponent('Refunded Twispay transaction #' + transid),
                success: function(data) {
                  console.log(data);
                  if (typeof data.success != 'undefined') {
                    alert('Successfuly refunded !');
                  } else {
                    alert(data.error);
                  }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                },
                complete: function(xhr, ajaxOptions, thrownError) {
                  setTimeout(function() {
                    window.location.reload(), 1000
                  })
                }
              });
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      } else {
        $(parent).css('opacity', '1');
      }
    }, 50);
  });
</script>
<style>
  .big-border {
    border-right: 4px solid #a7a7a7;
  }

  i.refund {
    font-size: 20px;
  }

  table.twispay-logs tr:nth-child(even) td {
    background-color: #ffffff;
  }

  table.twispay-logs tr:nth-child(odd) td {
    background-color: #f5f5f5;
  }

  table.twispay-logs td {
    text-align: center;
    padding: 4px;
  }

  table.twispay-logs th {
    text-align: center;
    padding: 4px;
  }

  .red {
    color: #dd0000;
    cursor: pointer;
  }
</style>

<?php
  echo $footer;
?>

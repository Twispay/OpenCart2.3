<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <!--<a href="<?php echo $logs; ?>" data-toggle="tooltip" title="<?php echo $text_logs; ?>" class="btn btn-default"><i class="fa fa-file-archive-o green"></i></a></div>-->
                <button type="submit" form="form-payment-twispay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a> <a href="<?php echo $logs; ?>" data-toggle="tooltip" title="<?php echo $text_logs; ?>" class="btn btn-info"><i class="fa fa-search"></i></a>
            </div>
            <h1><!--<img src="view/image/payment/twispay.png" alt="" />--><?php echo $heading_title; ?></h1>
			  <ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			  </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) {?>
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payment-twispay" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="twispay_status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-5">
                            <select name="twispay_status" id="twispay_status" class="form-control">
                                    <option value="1" <?php if ($twispay_status) {?> selected="selected"<?php } ?>><?php echo $text_enabled; ?></option>
                                    <option value="0" <?php if (!$twispay_status) {?> selected="selected"<?php } ?>><?php echo $text_disabled; ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="twispay_testMode"><?php echo $text_testMode; ?></label>
                        <div class="col-sm-5">
                            <select name="twispay_testMode" id="twispay_testMode" class="form-control">
                                <option value="1" <?php if ($twispay_testMode) {?> selected="selected" <?php } ?>><?php echo $text_yes; ?></option>
                                <option value="0" <?php if (!$twispay_testMode) {?> selected="selected" <?php } ?>><?php echo $text_no; ?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <?php echo $desc_testMode; ?>
                        </div>
                    </div>
                    <div class="form-group tw-live" <?php if ($twispay_testMode) {?> style="display:none;" <?php } ?>>
                        <label class="col-sm-2 control-label" for="twispay_live_site_id"><?php echo $text_live_site_id; ?></label>
                        <div class="col-sm-5">
                            <input type="text" name="twispay_live_site_id" id="twispay_live_site_id" class="form-control" value="<?php echo $twispay_live_site_id; ?>"/>

                        </div>
                        <div class="col-sm-5 tw-pd">
                            <?php echo $desc_live_site_id; ?>
                        </div>
                    </div>
                    <div class="form-group tw-live" <?php if ($twispay_testMode) {?> style="display:none;" <?php } ?>>
                        <label class="col-sm-2 control-label" for="twispay_live_site_key"><?php echo $text_live_site_key; ?></label>
                        <div class="col-sm-5">
                            <input type="password" name="twispay_live_site_key" id="twispay_live_site_key" class="form-control" value="<?php echo $twispay_live_site_key; ?>"/>

                        </div>
                        <div class="col-sm-5 tw-pd">
                            <?php echo $desc_live_site_key; ?>
                        </div>
                    </div>
                    <div class="form-group tw-stage" <?php if (!$twispay_testMode) {?> style="display:none;" <?php } ?>>
                        <label class="col-sm-2 control-label" for="twispay_staging_site_id"><?php echo $text_staging_site_id; ?></label>
                        <div class="col-sm-5">
                            <input type="text" name="twispay_staging_site_id" id="twispay_staging_site_id" class="form-control" value="<?php echo $twispay_staging_site_id; ?>"/>

                        </div>
                        <div class="col-sm-5 tw-pd">
                            <?php echo $desc_staging_site_id; ?>
                        </div>
                    </div>
                    <div class="form-group tw-stage" <?php if (!$twispay_testMode) {?> style="display:none;" <?php } ?>>
                        <label class="col-sm-2 control-label" for="twispay_staging_site_key"><?php echo $text_staging_site_key; ?></label>
                        <div class="col-sm-5">
                            <input type="password" name="twispay_staging_site_key" id="twispay_staging_site_key" class="form-control" value="<?php echo $twispay_staging_site_key; ?>"/>

                        </div>
                        <div class="col-sm-5 tw-pd">
                            <?php echo $desc_staging_site_key; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="twispay_s_t_s_notification"><?php echo $text_s_t_s_notification; ?></label>
                        <div class="col-sm-5">
                            <input type="text" readonly="readonly" name="twispay_s_t_s_notification" id="twispay_s_t_s_notification" class="form-control" value="<?php echo $twispay_s_t_s_notification; ?>" />

                        </div>
                        <div class="col-sm-5 tw-pd">
                            <?php echo $desc_s_t_s_notification; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="twispay_redirect_page"><?php echo $text_redirect_page; ?></label>
                        <div class="col-sm-5">
                            <input type="text" name="twispay_redirect_page" id="twispay_redirect_page" class="form-control" value="<?php echo $twispay_redirect_page; ?>" />

                        </div>
                        <div class="col-sm-5 tw-pd">
                            <?php echo $desc_redirect_page; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                        <div class="col-sm-10">
                            <input type="text" name="twispay_sort_order" value="<?php echo $twispay_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="twispay_order_status_id"><?php echo $entry_order_status; ?></label>
                        <div class="col-sm-5">
                            <select name="twispay_order_status_id" id="twispay_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" <?php if ($order_status['order_status_id'] == $twispay_order_status_id) {?> selected="selected"  <?php } ?> > <?php echo $order_status['name']; ?></option>
                                <?php } ?>

                            </select>
                        </div>
                        <div class="col-sm-5">

                        </div>
                    </div>
                    <input type="hidden" name="twispay_logs" id="twispay_logs" class="form-control" value="<?php echo $twispay_logs; ?>" />
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('change','#twispay_testMode', function(){
        mode = $(this).val();
        if(mode == '1'){
            $('.tw-stage').css('display','block');
            $('.tw-live').css('display','none');
        } else {
            $('.tw-stage').css('display','none');
            $('.tw-live').css('display','block');
        }

    });
</script>
<style>
    .green{
        color: #3ad900;
    }
</style>

<?php echo $footer; ?>
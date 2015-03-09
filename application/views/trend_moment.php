<?php
	$book_id = (isset($this->uri->segments[3])) ? $this->uri->segments[3] : 0;
	$book = $this->book_model->get_by_id(array( 'id' => $book_id ));
	
	// page
	$page['book'] = $book;
?>

<?php $this->load->view( 'common/meta', array( 'title' => 'Trend Moment' ) ); ?>

<body>
<?php $this->load->view( 'common/header'); ?>

<div class="content enlarged">
	<?php $this->load->view( 'common/sidebar'); ?>
	
  	<div class="mainbar">
	    <div class="page-head">
			<h2 class="pull-left button-back">Trend Moment</h2>
			<div class="clearfix"></div>
		</div>
		<div class="hide">
			<div class="cnt-page"><?php echo json_encode($page); ?></div>
		</div>
		
	    <div class="matter"><div class="container">
            <div class="row"><div class="col-md-12">
				<div class="grid-main">
					<div class="widget">
						<div class="widget-head">
							<div class="pull-left">
								<button class="btn btn-info btn-xs btn-add">Tambah</button>
							</div>
							<div class="widget-icons pull-right">
								<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
								<a href="#" class="wclose"><i class="fa fa-times"></i></a>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget-content">
							<table id="datatable" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Tanggal</th>
										<th>Penjualan (x)</th>
										<th>Waktu (y)</th>
										<th>x . y</th>
										<th>x<sup>2</sup></th>
										<th class="center">Control</th></tr>
								</thead>
								<tbody></tbody>
							</table>
							<div class="widget-foot">
								<br /><br />
								<div class="clearfix"></div> 
							</div>
						</div>
					</div>
					
					<div class="center">
						<button class="btn btn-success btn-generate">Generate</button>
					</div>
					
					<div class="cnt-result">
						<div class="widget cnt-variable hide" style="padding: 15px;">&nbsp;</div>
						<div class="widget cnt-table hide"></div>
					</div>
				</div>
				
				<div class="widget hide" id="form-trend">
					<div class="widget-head">
						<div class="pull-left">Form Trend Moment</div>
						<div class="widget-icons pull-right">
							<a href="#" class="wminimize"><i class="fa fa-chevron-up"></i></a>
							<a href="#" class="wclose"><i class="fa fa-times"></i></a>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="widget-content">
						<div class="padd"><form class="form-horizontal">
							<input type="hidden" name="action" value="update" />
							<input type="hidden" name="id" value="0" />
							<input type="hidden" name="book_id" value="0" />
							
							<div class="form-group">
								<label class="col-lg-2 control-label">Tanggal</label>
								<div class="col-lg-10">
									<div id="datetimepicker1" class="input-append datepicker">
										<input name="tanggal" data-format="dd-MM-yyyy" type="text" class="form-control dtpicker" placeholder="Tanggal">
										<span class="add-on">
											<i data-time-icon="fa fa-time" data-date-icon="fa fa-calendar" class="btn btn-info"></i>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Penjualan</label>
								<div class="col-lg-10">
									<input type="text" name="penjualan" class="form-control" placeholder="Penjualan" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Waktu</label>
								<div class="col-lg-10">
									<input type="text" name="waktu" class="form-control" placeholder="Waktu" />
								</div>
							</div>
							
							<hr />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-9">
									<button type="submit" class="btn btn-info">Save</button>
									<button type="button" class="btn btn-info btn-show-grid">Cancel</button>
								</div>
							</div>
						</form></div>
					</div>
				</div>  
				
			</div></div>
        </div></div>
    </div>
	<div class="clearfix"></div>
</div>

<?php $this->load->view( 'common/footer' ); ?>
<?php $this->load->view( 'common/library_js'); ?>

<script>
$(document).ready(function() {
	var dt = null;
	var page = {
		init: function() {
			var raw_data = $('.cnt-page').html();
			eval('var data = ' + raw_data);
			page.data = data;
		},
		show_grid: function() {
			$('.grid-main').show();
			$('#form-trend').hide();
		},
		show_form: function() {
			$('.grid-main').hide();
			$('#form-trend').show();
		}
	}
	page.init();
	
	// global
	$('.btn-show-grid').click(function() {
		page.show_grid();
	});
	
	// grid
	var param = {
		id: 'datatable',
		source: web.host + 'trend_moment/grid', iDisplayLength: 50,
		column: [ { sClass: "center" }, { sClass: "center" }, { sClass: "center" }, { bSortable: false, sClass: "center" }, { bSortable: false, sClass: "center" }, { bSortable: false, sClass: "center" } ],
		fnServerParams: function(aoData) {
			aoData.push( { name: 'book_id', value: page.data.book.id } );
		},
		callback: function() {
			$('#datatable .btn-edit').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.ajax({ url: web.host + 'trend_moment/action', param: { action: 'get_by_id', id: record.id }, callback: function(result) {
					Func.populate({ cnt: '#form-trend', record: result });
					page.show_form();
				} });
			});
			
			$('#datatable .btn-delete').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.form.del({
					data: { action: 'delete', id: record.id },
					url: web.host + 'trend_moment/action', callback: function() { dt.reload(); }
				});
			});
		}
	}
	dt = Func.datatable(param);
	
	// form agama
	$('.btn-add').click(function() {
		page.show_form();
		$('#form-trend form')[0].reset();
		$('#form-trend [name="id"]').val(0);
		$('#form-trend [name="book_id"]').val(page.data.book.id);
	});
	$('#form-trend form').validate({
		rules: {
			tanggal: { required: true, minlength: 2 },
			penjualan: { required: true },
			waktu: { required: true }
		}
	});
	$('#form-trend form').submit(function(e) {
		e.preventDefault();
		if (! $('#form-trend form').valid()) {
			return false;
		}
		
		Func.form.submit({
			url: web.host + 'trend_moment/action',
			param: Func.form.get_value('form-trend'),
			callback: function(result) {
				dt.reload();
				page.show_grid();
				$('#form-trend form')[0].reset();
			}
		});
	});
	
	$('.btn-generate').click(function() {
		Func.ajax({ url: web.host + 'trend_moment/action', param: { action: 'generate', book_id: page.data.book.id }, callback: function(result) {
			if (result.status == 1) {
				
				// get variable
				Func.ajax({ url: web.host + 'trend_moment/get_view', param: { action: 'variable', book_id: page.data.book.id }, is_json: 0, callback: function(result) {
					$('.cnt-variable').html(result).show();
				} });
				
				// get table
				Func.ajax({ url: web.host + 'trend_moment/get_view', param: { action: 'table', book_id: page.data.book.id }, is_json: 0, callback: function(result) {
					$('.cnt-table').html(result).show();
				} });
			} else {
				noty({ text: result.message, layout: 'topRight', type: 'error', timeout: 1500 });
			}
		} });
	});
});
</script>
</body>
</html>
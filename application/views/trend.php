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
										<th>Name</th>
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
						<button class="btn btn-success">Button</button>
					</div>
					
					<div class="widget">
						<div class="widget-head">
							<div class="pull-left">Tables</div>
							<div class="clearfix"></div>
						</div>
						<div class="widget-content">
						

                    <table class="table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Location</th>
                          <th>Age</th>
                          <th>Education</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Ashok</td>
                          <td>Norway</td>
                          <td>23</td>
                          <td>B.Tech</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Kumarasamy</td>
                          <td>USA</td>
                          <td>22</td>
                          <td>BE</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Babura</td>
                          <td>UK</td>
                          <td>43</td>
                          <td>PhD</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>John Doe</td>
                          <td>China</td>
                          <td>73</td>
                          <td>PUC</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>Santhosh</td>
                          <td>Japan</td>
                          <td>43</td>
                          <td>M.Tech</td>
                        </tr>                                                                        
                      </tbody>
                    </table>
						</div>
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
							
							<div class="form-group">
								<label class="col-lg-2 control-label">Name</label>
								<div class="col-lg-10">
									<input type="text" name="title" class="form-control" placeholder="Name" />
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
		show_grid: function() {
			$('.grid-main').show();
			$('#form-trend').hide();
		},
		show_form: function() {
			$('.grid-main').hide();
			$('#form-trend').show();
		}
	}
	
	// global
	$('.btn-show-grid').click(function() {
		page.show_grid();
	});
	
	// grid
	var param = {
		id: 'datatable',
		source: web.host + 'trend/grid',
		column: [ { }, { bSortable: false, sClass: "center" } ],
		callback: function() {
			$('#datatable .btn-edit').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.ajax({ url: web.host + 'trend/action', param: { action: 'get_by_id', id: record.id }, callback: function(result) {
					Func.populate({ cnt: '#form-trend', record: result });
					page.show_form();
				} });
			});
			
			$('#datatable .btn-delete').click(function() {
				var raw_record = $(this).siblings('.hide').text();
				eval('var record = ' + raw_record);
				
				Func.form.del({
					data: { action: 'delete', id: record.id },
					url: web.host + 'trend/action', callback: function() { dt.reload(); }
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
	});
	$('#form-trend form').validate({
		rules: {
			title: { required: true, minlength: 2 }
		}
	});
	$('#form-trend form').submit(function(e) {
		e.preventDefault();
		if (! $('#form-trend form').valid()) {
			return false;
		}
		
		Func.form.submit({
			url: web.host + 'trend/action',
			param: Func.form.get_value('form-trend'),
			callback: function(result) {
				dt.reload();
				page.show_grid();
				$('#form-trend form')[0].reset();
			}
		});
	});
});
</script>
</body>
</html>
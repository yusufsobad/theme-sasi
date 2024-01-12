<?php
(!defined('THEMEPATH')) ? exit : '';

class create_table
{

	protected static $check = false;

	protected static $thead = true;

	protected static $tbody = true;

	public static function _table($args = array())
	{

		$class = "table table-hover sasi-table ";
		if (isset($args['class'])) {
			$class .= $args['class'];
		}

		$id = '';
		$_idx = '';
		if (isset($args['id'])) {
			$id = $args['id'];
			$_idx = '#' . $id . ' ';
		}

		if (isset($args['search'])) {
			self::_search($args['search'], $args['data']);
		}

		// table-scrollable
?>
		<div class="table-responsive">
			<table id="<?php print($id); ?>" class="<?php print($class); ?>">
				<?php
				$check = array_filter($args['table']);
				if (empty($check)) {
					$args['table'][0]['tr'] = array('');
					$args['table'][0]['td'] = array(
						'Keterangan'	=> array(
							'center',
							'auto',
							'Tidak ada data yang ditemukan',
							false
						)
					);
				}

				$thead = isset($args['thead']) ? $args['thead'] : true;
				$tbody = isset($args['tbody']) ? $args['tbody'] : true;

				self::$thead = $thead;
				self::$tbody = $tbody;

				if ($thead) {
					self::thead($args['table']);
				}
				if ($tbody) {
					self::tbody($args['table']);
				}
				?>
			</table>
			<?php if (self::$check) : ?>
				<script>
					function sobad_check_all() {
						$('<?php print($_idx); ?>.check-sobad').each(function() {
							if ($(this).is(":checked")) {
								$(this).prop('checked', false);
							} else {
								$(this).prop('checked', true);
							}
						});
						return false;
					}
				</script>
			<?php endif; ?>
		</div>
		<?php

		if (isset($args['page'])) {
			if (is_callable(array(new self(), $args['page']['func']))) {
				$func = $args['page']['func'];
				self::{$func}($args['page']['data']);
			}
		}
	}

	public static function _search($args = array(), $data = array())
	{
		$search = '';
		$check = array_filter($args);

		$value = isset($data['data']) ? $data['data'] : ''; //data
		$type = isset($data['type']) ? $data['type'] : ''; //type
		$obj = isset($data['object']) ? $data['object'] : ''; //Object
		$func = isset($data['func']) ? empty($data['func']) ? '_search' : $data['func'] : '_search'; //function
		$load = isset($data['load']) ? empty($data['load']) ? 'sobad_portlet' : $data['load'] : 'sobad_portlet'; //load hasil request
		$search = isset($data['name']) ? $data['name'] : ''; //name search
		$val_src = isset($data['value']) ? $data['value'] : ''; //value search
		$filter = isset($data['filter']) ? $data['filter'] : true; //button filter

		?>
		<div class="row search-form-default">
			<div class="col-lg-4 p-0">
				<form id="sobad-search" class="sobad_form" action="javascript:;">
					<div class="row p-sm">
						<!-- <div class="input-group">
						<div class="input-cont">
							<input type="text" name="words<?php print($search); ?>" placeholder="Search..." class="form-control" data-sobad="<?php print($func); ?>" data-load="<?php print($load); ?>" data-type="<?php print($type); ?>" data-object="<?php print($obj); ?>" value="<?php print($value); ?>">
						</div>
						<span class="input-group-btn">
							<?php
							if (!empty($check)) {
								self::_dropdown($args, $search, $val_src);
							}
							?>
						</span>
					</div> -->
						<div class="sasi-search-content">
							<div class="col-md-7 pl-0 ml-0 pr-0 text-right">
								<div class="sasi-search">
									<div class="row m-0">
										<div class="input-group form-search">
											<div class="col-sm-9 p-0">
												<input type="text" name="words<?php print($search); ?>" class="form-control sasi-form" data-sobad="<?php print($func); ?>" data-load="<?php print($load); ?>" data-type="<?php print($type); ?>" data-object="<?php print($obj); ?>" value="<?php print($value); ?>" placeholder="Penelusuran">
											</div>
											<div class="col-sm-3 text-right p-0">
												<button type="button" data-sobad="<?php print($func); ?>" class="btn sasi-btn-search" aria-expanded="false" data-load="<?php print($load); ?>" data-object="<?php print($obj); ?>" onclick="sobad_search(this)" data-type="<?php print($type); ?>" data-load="<?php print($load); ?>" value="<?php print($value); ?>">
													<i class="fa fa-search" aria-hidden="true"></i>
												</button>
											</div>
										</div>
									</div>
								</div><!-- /input-group -->
							</div>
						</div>
						<div class="sasi-search-filter">
							<div class="col-md-4 p-0">
								<?php
								if (!empty($check)) {
									self::_dropdown($args, $search, $val_src, $filter);
								}
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<script>
			$('#sobad-search input').keypress(function(event) {
				var keycode = (event.keyCode ? event.keyCode : event.which);

				if (keycode == '13') {
					sobad_search(this);
				}
			});
		</script>
	<?php
	}

	private static function _dropdown($args = array(), $search = '', $value = 0, $filter = true)
	{
		$filter = $filter ? 1 : 0;
	?>
		<div class="btn-group">
			<button id="filter-sasi" type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="opacity:<?= $filter ;?>"><i class="fa fa-cogs mr-xs" aria-hidden="true"></i><span class="filter-title">Filter</span><span class="caret ml-xs"></span></button>
			<div class="dropdown-menu hold-on-click dropdown-radiobuttons" role="menu">
				<?php
				foreach ($args as $key => $val) {
					if (empty($val)) {
						continue;
					}

					$check = $value == $key ? 'checked' : '';

					$idx = 'inp_src' . $key;
				?>
					<label for="<?php print($idx); ?>" class="mouse_click">
						<div class="radio">
							<span class="<?php print($check); ?>">
								<input id="<?php print($idx); ?>" type="radio" name="search<?php print($search); ?>" value="<?php print($key); ?>" <?php print($check); ?>>
							</span>
						</div>
						<?php print($val); ?>
					</label>
				<?php
					$check = '';
				}
				?>
			</div>
		</div>
		<script>
			$(".mouse_click").click(function() {
				$(".mouse_click .radio span").removeClass("checked");
				$(this).children('div.radio').children('span').addClass("checked");
			});
		</script>
	<?php
	}

	private static function thead($args = array())
	{
	?>
		<thead>
			<tr role="row">
				<?php
				$thead = isset($args[0]['td']) ? $args[0]['td'] : $args[0];

				foreach ($thead as $key => $val) {

					$att = '';
					if ($val[3] == true && isset($val[3])) {
						$att = 'class="sorting"';
					}

					$colspan = '';
					if (isset($val[4])) {
						$colspan = 'colspan="' . $val[4] . '"';
					}

					$rowspan = '';
					if (isset($val[5])) {
						$rowspan = 'rowspan="' . $val[5] . '"';
					}

					if (strtolower($key) == 'check') {
						$key = '<input onchange="sobad_check_all()" type="checkbox" id="sobad-check-all" >';
						self::$check = true;
					}


					if ($key == 'accordion') {
						print('<th ' . $colspan . ' ' . $rowspan . ' ' . $att . ' style="text-align:left;width:' . $val[1] . ';"></th>');
					} else {
						print('<th ' . $colspan . ' ' . $rowspan . ' ' . $att . ' style="text-align:left;width:' . $val[1] . ';">' . $key  . '</th>');
					}
				}
				?>
			</tr>
		</thead>
	<?php
	}

	private static function tbody($args = array())
	{
		$len = count($args);


	?>
		<style>
			.loader {
				border: 5px solid #f3f3f3;
				border-radius: 30px !important;
				border-top: 5px solid #3498db;
				width: 30px;
				height: 30px;
				-webkit-animation: spin 2s linear infinite;
				animation: spin 2s linear infinite;
				margin-left: auto;
				margin-right: auto;
			}

			/* Safari */
			@-webkit-keyframes spin {
				0% {
					-webkit-transform: rotate(0deg);
				}

				100% {
					-webkit-transform: rotate(360deg);
				}
			}

			@keyframes spin {
				0% {
					transform: rotate(0deg);
				}

				100% {
					transform: rotate(360deg);
				}
			}
		</style>
		<tbody>
			<?php
			for ($i = 0; $i < $len; $i++) {
				$hsl = $i % 2;
				$cls = ''; //'odd';
				//if($hsl == 1){
				//	$cls = 'even';
				//}

				$acordion = false;
				$cls1 = isset($args[$i]['tr']) ? $args[$i]['tr'] : '';
				$cls1 = isset($cls1[0]) ? $cls1[0] : '';

				echo '<tr role="row" class="' . $cls . ' ' . $cls1 . '">';

				$tbody = isset($args[0]['td']) ? $args[0]['td'] : $args[0];
				$config_accordion = array();
				$cols_accordion = count($args[$i]['td']);
				foreach ($args[$i]['td'] as $key => $val) {

					// echo '<pre>' . print_r($val[2], true) . '</pre>';
					// die();


					$colspan = '';
					if (isset($val[4])) {
						$colspan = 'colspan="' . $val[4] . '"';
					}

					$rowspan = '';
					if (isset($val[5])) {
						$rowspan = 'rowspan="' . $val[5] . '"';
					}

					if (strtolower($key) == 'check') {
						$val[2] = '<input name="checked_ids" type="checkbox" class="check-sobad" value="' . $val[2] . '">';
					}


					if (strtolower($key) == 'accordion') {
						$check = array_filter($val[2]);
						if (!empty($check)) {
							$acordion = true;
							$config_accordion = $val[2];
							$val[2] = '<a onclick="sobad_button(this,false)" class="accordion-toggle" data-toggle="collapse" data-load="' . $config_accordion['ID'] . '"  data-target="#' . $config_accordion['ID'] . '"  data-sobad="' . $config_accordion['func'] . '"  data-type="' . $config_accordion['type'] . '" href="javascript:"><i class="fa fa-angle-down" aria-hidden="true"></i></a>';
						}else{
							$val[2] = '';
						}
					}

					$width = self::$thead == false ? "width:" . $val[1] : '';
					echo '<td ' . $colspan . ' ' . $rowspan . ' style="text-align:' . $val[0] . ';' . $width . '">' . $val[2] . '</td>';
				}
				echo '</tr>';

				if ($acordion) {

					echo '<tr>';
					echo '<td colspan="' . $cols_accordion . '" style="padding:0px;">';
					echo '<div  class="accordian-body collapse"  id="' . $config_accordion['ID'] . '">';
					echo '<div class="loader"></div>';
					echo '</div>';
					echo '</td>';
					echo '</tr>';
				}
			}

			?>
			<script>
				$(function() {
					$(".fold-table tr.view").on("click", function() {
						if ($(this).hasClass("open")) {
							$(this).removeClass("open").next(".fold").removeClass("open");
						} else {
							$(".fold-table tr.view").removeClass("open").next(".fold").removeClass("open");
							$(this).addClass("open").next(".fold").addClass("open");
						}
					});
				});
			</script>
		</tbody>
	<?php
	}

	public static function _pagination($args = array())
	{
		$prev = '';
		$next = '';
		$page = 0;
		$number = intval($args['start']);
		$load = isset($args['load']) ? $args['load'] : 'sobad_portlet';
		$object = isset($args['object']) ? $args['object'] : '';

		$type = '';
		if (isset($args['type'])) {
			$type = $args['type'];
		}

		if ($args['qty'] != 0) {
			$page = ceil($args['qty'] / $args['limit']);
		}

		$args['func'] = isset($args['func']) ? $args['func'] : '_pagination';

		if ($page > 5) {
			$start = (ceil($number / 5) - 1)  * 5 + 1;
			$finish = $start + 4;
			if ($finish > $page) {
				$finish = $page;
			}

			if ($start - 1 >= 1) {
				$prev = '<li>
							<a class="page_malika" data-sobad="' . $args['func'] . '" data-qty="' . intval($start - 1) . '" data-load="' . $load . '" data-object="' . $object . '" data-type="' . $type . '" href="javascript:;" aria-label="Previous">
								<span aria-hidden="true">«</span>
							</a>
						</li>';
			}
		} else {
			$start = 1;
			$finish = $page;
		}

		if ($finish < $page) {
			$next = '<li>
						<a class="page_malika" data-sobad="' . $args['func'] . '" data-qty="' . intval($finish + 1) . '" data-load="' . $load . '" data-object="' . $object . '" data-type="' . $type . '" href="javascript:;" aria-label="Next">
							<span aria-hidden="true">»</span>
						</a>
					</li>';
		}
	?>
		<div class="panel-footer sasi-card-footer light radius-bottom-lg p-lg">
			<div class="sasi-pagination pl-lg pr-lg pb-md">
				<div class="row">
					<div class="col-lg-6">
						<div class="table-length width-fit">
							<select name="show_page" class="form-control input-sm">
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="100">100</option>
							</select>
						</div>
					</div>
					<div class="col-lg-6 text-right">
						<ul id="dash_pagination" class="pagination sasi-pagination m-0">
							<?php
							print($prev);

							for ($i = $start; $i <= $finish; $i++) {
								$opt = '';
								$pg_malika = 'page_malika';
								if ($i == $number) {
									$opt = 'disabled';
									$pg_malika = '';
								}

								echo '<li class="' . $opt . '"><a class="' . $pg_malika . '" data-sobad="' . $args['func'] . '" data-object="' . $object . '" data-qty="' . $i . '" data-load="' . $load . '" data-type="' . $type . '" href="javascript:;"> ' . $i . ' <span class="sr-only">(current)</span></a></li>';
							}

							print($next);
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<script>
			$("a.page_malika").click(function() {
				sobad_pagination(this);
			});
		</script>
	<?php
	}

	private static function _scrolldown()
	{
	?>

<?php
	}
}

<?php

(!defined('THEMEPATH')) ? exit : '';

require dirname(__FILE__) . '/js-script.php';

require dirname(__FILE__) . '/template/coming_soon.php';
require dirname(__FILE__) . '/template/login.php';

require dirname(__FILE__) . '/template/form.php';
require dirname(__FILE__) . '/template/table.php';

abstract class sasi_template extends custom_script
{

	// ---------------------------------------------
	// Create Panel --------------------------------
	// ---------------------------------------------
	public static function _panel($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		foreach ($args as $key => $val) {
			$func = $val['func'];

			$object = 'sasi_template';
			if (isset($val['object'])) {
				if (class_exists($val['object'])) {
					$object = $val['object'];
				}
			}

			if (method_exists($object, $func)) {
				// add style
				$css = array_filter($val['style']);
				if (!empty($css)) {
					if (is_callable(array(new $val['style'][0](), $val['style'][1]))) {
						$obj = $val['style'][0];
						$style = $val['style'][1];
						$obj::{$style}();
					}
				}

				echo '<div class="container-fluid card pt-md pr-xl pl-xl pb-xl  light-grey">';
				echo '<div class="row">';
				$object::{$func}($val['data']);
				echo '</div>';
				echo '</div>';
				self::_clearfix();

				// add script
				$js = array_filter($val['script']);
				if (!empty($js)) {
					if (is_callable(array(new $val['script'][0](), $val['script'][1]))) {
						$obj = $val['script'][0];
						$style = $val['script'][1];
						$obj::{$style}();
					}
				}
			}
		}
	}

	private static function _clearfix()
	{
?>
		<div class="clearfix"></div>
	<?php
	}

	// ---------------------------------------------
	// Header Content ------------------------------
	// ---------------------------------------------

	public static function _modal_form($qty = 0)
	{
	?>
		<div class="modal fade bs-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div id="here_modal" class="modal-dialog modal-full-custom">
				<!-- /.modal-dialog -->
				<?php self::_modal_loading(); ?>
				<!-- /.modal-content -->
			</div>
		</div>
		<?php
		if ($qty > 1) :
			for ($i = 2; $i <= $qty; $i++) {
		?>
				<div class="modal fade bs-modal-lg" id="myModal<?php print($i); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div id="here_modal<?php print($i); ?>" class="modal-dialog modal-lg">
						<!-- /.modal-dialog -->
					</div>
				</div>
		<?php
			}
		endif;
	}

	private static function _modal_loading()
	{
		?>
		<div class="modal-content">
			<div class="modal-body">
				<img src="asset/img/loading-spinner-grey.gif" alt="" class="loading">
				<span> &nbsp;&nbsp;Loading... </span>
			</div>
		</div>
	<?php
	}

	public static function _modal_content($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		$form = isset($args['form']) ? $args['form'] : true;

		$id = isset($args['id']) ? 'id="' . $args['id'] . '"' : '';
		$obj = _object;

		$idx = date('d-m-Y H:i:s');
		$idx = strtotime($idx);

		$idx = strval($idx);
	?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php print($args['title']); ?></h4>
			</div>
			<div class="mr-xl ml-xl mb-lg mt-sm border-top-xs"></div>
			<?php if ($form) : ?>
				<form id="frm_<?php print($idx); ?>" role="form" method="post" class="form-horizontal" enctype="multipart/form-data" novalidate="novalidate">
				<?php endif; ?>

				<button type="button" class="btn metronic-submit" style="display: none;"></button>
				<?php foreach ($args['func'] as $key => $func) : ?>
					<div class="modal-body">
						<div <?php print($id); ?> class="row">
							<?php
							if (method_exists('sasi_template', $func)) {
								self::{$func}($args['data'][$key]);
							} else if (method_exists($obj, $func)) {
								$obj::{$func}($args['data'][$key]);
							}
							?>
						</div>
					</div>
				<?php endforeach; ?>

				<?php if ($form) : ?>
				</form>
			<?php endif; ?>

			<div class="modal-footer">
				<?php
				$button = $args['button'];
				if (method_exists('sasi_template', $button)) {
					self::{$button}($args['status'], $idx);
				} else if (method_exists($obj, $button)) {
					$obj::{$button}($args['status'], $idx);
				}
				?>
			</div>
		</div>
	<?php
	}

	private static function _btn_modal_save($args = array(), $idx = '')
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		//$idx = sobad_asset::ascii_to_hexa($idx);

		$status = '';
		if (isset($args['status'])) {
			$status = $args['status'];
		}

		$type = '';
		if (isset($args['type'])) {
			$type = $args['type'];
		}

		$index = '';
		if (isset($args['index'])) {
			$index = $args['index'];
		}

		$modal = true;
		if (isset($args['modal'])) {
			$modal = $args['modal'];
		}

	?>
		<button id="btn_<?php print($idx); ?>" data-sobad="<?php print($args['link']); ?>" data-load="<?php print($args['load']); ?>" data-type="<?php print($type); ?>" type="button" class="btn blue" data-index="#frm_<?php print($idx); ?>" data-modal="<?php print($modal); ?>" onclick="metronicSubmit_<?php print($idx); ?>()" <?php print($status); ?>>Save</button>
		<button type="button" class="btn red-border" data-dismiss="modal">Cancel</button>

		<script type="text/javascript">
			function metronicSubmit_<?php print($idx); ?>() {
				$("form#frm_<?php print($idx); ?>").validate({
					errorElement: 'span', //default input error message container
					errorClass: 'help-block help-block-error', // default input error message class
					focusInvalid: false, // do not focus the last invalid input
					highlight: function(element) { // hightlight error inputs
						$(element)
							.closest('.form-group').addClass('has-error'); // set error class to the control group   
					},
					success: function(label, element) {
						$(element)
							.closest('.form-group').removeClass('has-error'); // set success class 
					},
					submitHandler: function(form) {
						sobad_submitLoad('#btn_<?php print($idx); ?>');
					}
				});

				$("form#frm_<?php print($idx); ?>>button.metronic-submit").trigger("click");

				setTimeout(function() {
					$("form#frm_<?php print($idx); ?>>button.metronic-submit").removeAttr("type").attr("type", "submit");
					$("form#frm_<?php print($idx); ?>>button.metronic-submit").trigger("click");
				}, 200);
			}
		</script>
	<?php
	}

	private static function _btn_modal_import($args = array(), $idx = '')
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		$status = '';
		if (isset($args['status'])) {
			$status = $args['status'];
		}

		$load = 'sobad_portlet';
		if (isset($args['status'])) {
			$load = $args['load'];
		}

	?>
		<button id="importFile" data-sobad="<?php print($args['link']); ?>" data-load="<?php print($load); ?>" type="button" class="btn blue" data-dismiss="modal" <?php print($status); ?> style="display:none;"></button>

		<script>
			$(document).ready(function(e) {
				$("form#frm_<?php print($idx); ?>").submit(function() {
					sobad_load('frm_<?php print($idx); ?>');
					sobad_import(this, '<?php print($load); ?>');
					//Metronic.unblockUI('<?php //print($args['id']); 
											?>');

					var target = '#<?php print($args['id']); ?>';
					$(target).unblock({
						onUnblock: function() {
							$(target).css('position', '');
							$(target).css('zoom', '');
						}
					});
					return false;
				});
			});
		</script>
	<?php
	}

	private static function _btn_modal_yes($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

	?>
		<button data-sobad="<?php print($args['link']); ?>" data-object="<?php print($args['func']); ?>" type="button" class="btn green" onclick="sobad_quest(this)">Ya</button>
		<button type="button" class="btn red" data-dismiss="modal">Tidak</button>
	<?php
	}

	protected static function _head_pagebar($link = array(), $date = false)
	{
		$check = array_filter($link);
	?>
		<div class="sasi-navigation secondary-grey pt-md pl-lg pr-lg">
			<div class="container-fluid pl-xl pr-xl">
				<ol class="breadcrumb secondary-grey">
					<a class="ml-sm mr-sm" href="javascript:" onclick="sasi_backmenu()"><i class="fa fa-arrow-circle-left color-dark-grey"></i></a>
					<li>
						<a class="color-dark-grey" href="<?php echo '/' . URL; ?>">Home</a>
					</li>
					<?php
					if (!empty($check)) {
						foreach ($link as $key => $val) {
							echo '
										<li>
											<a id="sobad_' . $val['func'] . '" class="color-dark-grey" href="javascript:" onclick="sasi_sidemenu(this)" data-uri="' . $key . '">
												' . $val['label'] . '
											</a>
										</li>';
						}
					}
					?>
				</ol>
			</div>
		</div>
	<?php
	}

	protected static function _menu_head_pagebar($link = array(), $date = false)
	{
		$check = array_filter($link);
	?>
		<div class="sasi-navigation secondary-grey pt-md pl-lg pr-lg">
			<div class="container-fluid pl-xl pr-xl">
				<ol class="breadcrumb secondary-grey">
					<a class="ml-sm mr-sm" href="javascript:" onclick="sasi_backmenu()"><img src="theme/sasi/asset/img/menu-arrow-back.png"></a>
					<li>
						<a class="color-dark-grey" href="<?php echo '/' . URL; ?>">Home</a>
					</li>
					<?php
					if (!empty($check)) {
						foreach ($link as $key => $val) {
							echo '
										<li>
											<a id="sobad_' . $val['func'] . '" class="color-dark-grey" href="javascript:" onclick="sasi_sidemenu(this)" data-uri="' . $key . '">
												' . $val['label'] . '
											</a>
										</li>';
						}
					}
					?>
				</ol>
			</div>
		</div>
	<?php
	}

	// ---------------------------------------------
	// Create Portlet Box --------------------------
	// ---------------------------------------------
	public static function _portlet($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		$_id = "sobad_portlet";
		if (isset($args['ID'])) {
			$_id = $args['ID'];
		}

		if (isset($args['object'])) {
			if (class_exists($args['object'])) {
				$object = $args['object'];
			} else {
				return '';
			}
		} else {
			$object = 'sasi_template';
		}

	?>
		<div class="col-md-12">
			<div class="portlet box blue-madison">
				<div class="portlet-title">
					<div class="caption">
						<?php print($args['label']); ?>
					</div>
					<div class="tools">
						<?php print($args['tool']); ?>
					</div>
					<div class="actions">
						<?php
						print($args['action']);
						?>
					</div>
				</div>
				<div class="portlet-body">
					<div id="<?php print($_id); ?>" class="dataTables_wrapper no-footer">
						<?php
						$func = $args['func'];
						if (method_exists($object, $func)) {
							$object::{$func}($args['data']);
						}
						?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	// ---------------------------------------------
	// Create Tabs ---------------------------------
	// ---------------------------------------------
	public static function _tabs($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

		if (isset($args['object'])) {
			if (class_exists($args['object'])) {
				$object = $args['object'];
			} else {
				return '';
			}
		} else {
			$object = 'sasi_template';
		}

	?>
		<div class="col-md-12">
			<div id="sobad_tabs" class="tabbable">
				<ul class="nav nav-tabs nav-tabs-lg">
					<?php

					$script = 'sobad_tabs(this)';
					if (isset($args['script'])) {
						$script = $args['script'];
					}

					$active = isset($args['active']) ? $args['active'] : '';
					$li_cls = 'active';
					foreach ($args['tab'] as $key => $val) {
						$li_cls = empty($active) ? $li_cls : $active == $val['key'] ? 'active' : '';
						$func = isset($val['func']) ? $val['func'] : "_tabs";
						$info = isset($val['info']) ? $val['info'] : "badge-success";

						echo '
								<li class="' . $li_cls . '">
									<a onclick="' . $script . '" id="' . $val['key'] . '" data-sobad="' . $func . '" data-load="tab_malika" data-toggle="tab" href="#tab_malika' . $key . '" aria-expanded="true">
									' . $val['label'] . ' 
									<span class="badge ' . $info . '">' . $val['qty'] . '</span>
									</a>
								</li>
							';

						$li_cls = '';
					}
					?>
				</ul>
				<div class="tab-content">
					<?php
					$no_tab = 0;
					foreach ($args['tab'] as $key => $val) {
					?>
						<div class="tab-pane active" id="tab_malika">
							<div class="row">

								<?php
								if ($no_tab < 1) {
									$func = $args['func'];
									if (method_exists($object, $func)) {
										$object::{$func}($args['data']);
									}
								}
								?>

							</div>
						</div>
					<?php
						$no_tab += 1;
					}
					?>
				</div>
			</div>
		</div>
	<?php
	}

	// ---------------------------------------------
	// Create Inline Menu --------------------------
	// ---------------------------------------------

	public static function _inline_menu($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return '';
		}

	?>
		<div class="col-md-3">
			<ul class="ver-inline-menu tabbable margin-bottom-10">
				<?php
				$li_cls = 'active';
				foreach ($args['menu'] as $key => $val) {
					if (isset($args['active'])) {
						$li_cls = '';
						if ($args['active'] == $key) {
							$li_cls = 'active';
						}
					}

					echo '
								<li class="' . $li_cls . '">
									<a id="' . $val['key'] . '" data-toggle="tab" href="#inline_malika' . $key . '" aria-expanded="true">
										<i class="' . $val['icon'] . '"></i>
										' . $val['label'] . ' 
										<span class="after"></span>
									</a>
								</li>
							';
					$li_cls = '';
				}
				?>
			</ul>
		</div>
		<div class="col-md-9">
			<div class="tab-content">
				<?php
				$active = 'active';
				foreach ($args['content'] as $key => $val) {
					if (isset($args['active'])) {
						$active = '';
						if ($args['active'] == $key) {
							$active = 'active';
						}
					}

					$object = isset($val['object']) ? $val['object'] : 'sasi_template';
					$func = $val['func'];
					if (method_exists($object, $func)) {
						echo '<div id="inline_malika' . $key . '" class="tab-pane ' . $active . '">';
						$object::{$func}($val['data']);
						echo '</div>';

						$active = '';
					}
				}
				?>
			</div>
		</div>
<?php
	}

	// ---------------------------------------------
	// Create option dashboard ---------------------
	// ---------------------------------------------
	public static function sobad_dashboard($args = array())
	{
		if (!class_exists('admin_dashboard')) {
			require dirname(__FILE__) . '/template/dashboard.php';
		}

		$dash = admin_dashboard::_dashboard($args);
	}

	// ---------------------------------------------
	// Create Table --------------------------------
	// ---------------------------------------------
	public static function sobad_table($args = array())
	{
		$table = create_table::_table($args);
	}

	// ---------------------------------------------
	// Create option File manager ------------------
	// ---------------------------------------------
	public static function sobad_file_manager($args = array())
	{
		if (!class_exists('create_file_manager')) {
			require dirname(__FILE__) . '/template/file_manager.php';
		}

		$manager = create_file_manager::_layout($args);
	}

	// ---------------------------------------------
	// Create Form ---------------------------------
	// ---------------------------------------------
	public static function report_form($args = array())
	{
		$form = create_form::get_form($args, true);
	}

	public static function sobad_form($args = array())
	{
		$form = create_form::get_form($args, false);
	}

	// ---------------------------------------------
	// Create Chart ---------------------------------
	// ---------------------------------------------
	public static function sobad_chart($args = array())
	{
		if (!class_exists('create_chart')) {
			require dirname(__FILE__) . '/template/chart.php';
		}

		$chart = create_chart::_layout($args);
	}
}

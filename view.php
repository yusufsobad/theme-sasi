<?php

(!defined('THEMEPATH')) ? exit : '';

define('_theme_name', 'sasi_layout');
define('_theme_folder', basename(__DIR__));

require dirname(__FILE__) . '/scripts.php';
require dirname(__FILE__) . '/view_header.php';

class sasi_layout extends sasi_template
{
	private static $page = array();

	private static $menu = array();

	private static $sidemenu = array();

	public static function load_here($data = '')
	{
		$menu = $data;
		$footer = '';
		if (gettype($data) == 'array') {
			$menu = '';
			extract($data);
		}

?>
		<div class="wrapper">
			<?php
			self::_topbar();
			//					self::_clearfix();
			self::_container();
			self::_footer($footer);
			?>
		</div>
	<?php
	}

	private static function _topbar($menu = '')
	{
	?>
		<!-- BEGIN HEADER -->
		<div class="topbar">
			<nav class="navbar mb-0 navbar-default border-none purple  bg-header">
				<?php
				$menu = sasi_header::_create();
				?>
			</nav>
		</div>

		<!-- END HEADER -->
	<?php
	}

	private static function _container()
	{
		global $reg_sidebar;

		$page = get_page_url();

		$no = 0;
		$status = false;
		if (empty($page)) {
			foreach ($reg_sidebar as $key => $val) {
				if ($no == 0) {
					$uri = $key;
					$menu = $val['child'];
					$func = $val['func'];
					$data = $val['label'];

					$loc = isset($val['loc']) ? $val['loc'] : '';
				}

				// Check active Sidemenu
				if ($val['status'] == 'active') {
					$status = true;
					$menu = $val['child'];
					$func = $val['func'];
					$data = $val['label'];

					$loc = isset($val['loc']) ? $val['loc'] : '';
				}

				$no += 1;
			}
		} else {
			$status = true;
			$child = get_side_active($reg_sidebar, $page);

			$menu = null;
			$uri = $page;
			$loc = isset($child['loc']) ? $child['loc'] : '';

			$func = $child['func'];
			$data = $child['label'];
		}

		define('_object', $func);
	?>
		<div id="here_content" class="containt">
			<?php
			if ($status) {
				if ($menu == null) {
					if (!empty($loc)) {
						$loc = empty($loc) ? $func : $loc . '.' . $func;
						sobad_asset::_loadFile($loc);
					}

					if (class_exists($func)) {
						if (is_callable(array($func, '_sidemenu'))) {
							$uri = defined('uri') ? uri : array();
							unset($uri[0]);

							$uri = implode('/', $uri);
							echo $func::_sidemenu($uri);
						}
					}
				} else {
					self::_sidebar($menu);
				}
			}
			?>
		</div>
		<?php
		if ($status) {
			self::_script_sidemenu($uri);
		}
	}

	private static function _footer($footer = '')
	{
		?>
		<div class="footer page-footer">
			<?php if (empty($footer)) : ?>
				<div class="container-fluid secondary-grey p-xl">
					<div class="row pl-xl pr-xl">
						<div class="footer-company col-lg-6 text-left pt-md">
							<a class="color-dark-black" href="">
								<h4>© SOLO ABADI SYSTEM INFORMATION, 2021.</h4>
							</a>
						</div>
						<div class="footer-logo col-lg-6 text-right ">
							<h4> Your company: </h4>
							<img src="asset/img/logo-company.png">
						</div>
					</div>
				</div>
			<?php
			else :
				echo $footer;
			endif;
			?>
		</div>
	<?php
	}

	// ------------------------------------------------------------------------------
	// Sidemenu ---------------------------------------------------------------------
	// ------------------------------------------------------------------------------

	public static function _load_sidemenu($args = array())
	{
		$args = json_decode($args, true);

		ob_start();
		self::_sidebar($args);
		return ob_get_clean();
	}

	private static function _contain_menu($args = array())
	{
		$label = $args['label'];
		$icon = isset($args['icon-menu']) ? $args['icon-menu'] : 'sasi-icon-other';
		$qty = isset($args['qty']) ? $args['qty'] : '';

		$status = empty($args['func']) ? 'menu-disabled' : '';
	?>
		<div class="d-flex <?php echo $status; ?>">
			<div class="w-100">
				<div class="pr-lg">
					<i class="<?php echo $icon; ?> color-purple icon-menu"></i>
					<h4 class="font-weight-600"><?php echo $label; ?></h4>
				</div>
			</div>
			<div class="flex-shrink-1">
				<div class="pr-sm pl-sm pt-0">
					<span class="label label-danger"><?php echo $qty; ?></span>
				</div>
			</div>
		</div>
	<?php
	}

	private static function _contain_hover($key = '', $args = array())
	{
		$func = $args['func'];
		$label = $args['label'];
		$help = isset($args['help']) ? $args['help'] : '';

		$click = 'onclick="sasi_sidemenu(this)"';
		$status = '';
		if (empty($func)) {
			$click = '';
			$status = 'disabled';
		}
		$element_link = '<a id="sobad_' . $func . '" href="javascript:" class="sasi_childmenu ' . $status . '" data-uri="' .  $key . '" ' . $click . '>';

		if (isset($args['modal'])) {
			$status = $args['status'] ?? '';
			$type = '';
			if (isset($args['modal']['type'])) {
				$type = $args['modal']['type'];
			}

			$onclick = 'sobad_button(this)';
			if (isset($args['modal']['script'])) {
				$onclick = $args['modal']['script'];
			}

			$no = $args['modal']['no'] ?? '';
			$load = 'here_modal' . $no;
			$href = '#myModal' . $no;
			$element_link =  '<a data-toggle="modal" object="' . $func . '" data-sobad="' . $args['modal']['func'] . '" data-load="' . $load . '" data-type="' . $type . '" href="' . $href . '" class="sasi_childmenu"  onclick="' . $onclick . '" ' . $status . '>';
		}
	?>
		<div class="overlay">
			<div class="p-0 text-right">
				<img class="circle-icon" src="theme/<?php echo _theme_folder; ?>/asset/img/sasi-logo-circle.png">
			</div>
			<div class="content-card-menu"></div>
			<?= $element_link ?>
			<div class="box-sidemenu">
				<div class="content-menu absolute color-magenta pl-lg pr-lg top-0">
					<div class="col text-left">
						<h4 class="font-weight-600"><?php echo $label; ?></h4>
						<p><?php echo $help; ?></p>
					</div>
				</div>
			</div>
			</a>
			<a href="javascript:">
				<div class="col color-magenta text-left absolute bottom-6 pl-md pb-md-pr-md">
					<p><img src="theme/<?php echo _theme_folder; ?>/asset/img/sasi-logo-help.png">Bantuan</p>
				</div>
			</a>
		</div>
	<?php
	}

	public static function _sidebar($menu = array())
	{
		self::_menu_head_pagebar();
	?>
		<div class="container-fluid sasi-menu">
			<?= parent::_modal_form(2); ?>
			<div class="mt-lg col-lg">
				<div class="sasi-row justify-content-md-start">
					<?php
					foreach ($menu as $key => $val) {
						self::_sidebar_menu($key, $val);
					}
					?>
				</div>
			</div>
		</div>
	<?php
	}

	private static function _sidebar_menu($key = '', $child = array())
	{
		$idx = $child['id'] ?? 'mn_' . $key;
		$badge = $child['notify'] ?? '';
		$hide = empty($badge) ? 'hide' : '';
	?>
		<div class="col-19 sasi-col pt-lg">
			<div class="panel panel-default border-light radius-sm shadow-md sasi-card-body">
				<div class="panel-body card-menu p-0">
					<div class="col-lg-12 p-lg">
						<span id="<?= $idx ?>" class="badge badge-success red <?= $hide ;?>" src="" style="position: absolute;top: 12px;right: 6px;font-size: 12px !important;border-radius: 6px !important;"><?= $badge ?></span>
						<?php
						self::_contain_menu($child);
						self::_contain_hover($key, $child);
						?>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	private static function _script_sidemenu($key = '')
	{
		global $reg_sidebar;

	?>
		<script type="text/javascript">
			var active_menu = '<?php echo $key; ?>';
			var history_menu = ['<?php echo $key; ?>'];
			var sasi_dataMenu = <?php echo json_encode($reg_sidebar); ?>;

			// click button sidemenu
			$(".sasi_sidemenu").click(function() {
				var ajx = $(this).attr("id");

				history_menu = [];

				$("ul.nav.navbar-nav.navbar-item li>a").removeClass('active');
				$(this).addClass('active');
				sasi_sidemenu(this);
			});

			function sasi_sidemenu(val) {
				var ajx = $(val).attr("id");
				var uri = $(val).attr("data-uri");
				sobad_load('here_content');

				if (ajx != 'sobad_#' && ajx != 'sobad_') {
					var lenSasi = history_menu.length;
					history_menu[lenSasi] = uri;

					//setcookie("sidemenu",ajx);
					window.history.pushState(sasi_history(uri), ajx, '/' + system + '/' + uri);
				} else {
					if (ajx != 'sobad_') {
						var lenSasi = history_menu.length;
						var sasi_menu = sasi_dataMenu;

						active_menu = uri;
						history_menu[lenSasi] = uri;

						sasi_ajaxmenu(sasi_menu, lenSasi + 1);
					}
				}
			}

			function sasi_backmenu() {
				history_menu.pop();

				var lenSasi = history_menu.length;
				var sasi_menu = sasi_dataMenu;

				active_menu = history_menu[lenSasi];

				sasi_ajaxmenu(sasi_menu, lenSasi);
			}

			// function history sidemenu
			function sasi_history(obj) {
				obj = obj.replace("sobad_", '');
				object = obj;

				data = "ajax=_sidemenu&object=" + object + "&data=";
				sobad_ajax('#here_content', data, 'html', false);
			}

			function sasi_ajaxmenu(sasi_menu, lenSasi) {
				var system_ajax = url_ajax;
				url_ajax = "theme/<?php echo _theme_folder; ?>/ajax.php";

				for (var i = 0; i < lenSasi; i++) {
					sasi_menu = sasi_menu[history_menu[i]]['child'];
				}

				var data = JSON.stringify(sasi_menu);

				data = "ajax=_load_sidemenu&object=sasi_layout&data=" + data;
				sobad_ajax('#here_content', data, 'html', false);

				url_ajax = system_ajax;
			}
		</script>
	<?php
	}

	// ------------------------------------------------------------------------------
	// Notification -----------------------------------------------------------------
	// ------------------------------------------------------------------------------

	public static function _notification($data=[]){
		$_date = '0000-00-00';
		foreach ($data as $key => $val) {
			$status_date = false;

			$icon = $val['icon'] ?? '';
			$icon = empty($icon) ? 'sasi-icon-other' : $icon;

			$time = strtotime($val['date']);
			$time = date('H:i',$time);

			if($val['date'] != $_date){
				$status_date = true;

				$_date = $val['date'];
				$date = $val['date'] == date('Y-m-d') ? 'Today' : format_date_id($val['date']);
			}

			$notify = '';
			if(!empty($val['link'])){
				$base = $val['ID'] . '#' . $val['post_id'];
				$notify = base64_encode($base);
			}

		?>
			<?php if($status_date): ?>
				<li><small class="color-dark-grey mt-md"><?= $date ;?></small></li>
			<?php endif; ?>

			<li class="content-notify mt-sm ">
				<i class="mr-sm <?= $icon ;?> color-purple icon-menu" style="font-size: 20px;padding: 10px;background-color: #F6E7FF;width: 40px;border-radius: 50%;"></i>

				<?= self::_content_notification($val['content'],$val['link'],$notify) ?>

				<span class="color-dark-grey ml-lg align-right">
					<small><?= $time ;?></small>
				</span>
				<!--
					<span class="color-dark-black font-weight-600"><small>Gading
						Rengga</small>
					</span>
					<span class="color-dark-grey"><small> send you a new
							task</small>
					</span>
					<span class="color-dark-black font-weight-600"><small>
							Project Team</small>
					</span>
					<span class="color-dark-grey ml-lg align-right"><small>6
							min</small>
					</span>
				-->
			</li>
		<?php

		}
	}

	public static function _content_notification($content='',$link='',$notify=''){
		/*
			[bold={text}]	=> <span class="color-dark-black font-weight-600">
									<small>{text}</small>
								</span>

			[purple={text}]	=> <span class="color-purple font-weight-600">
									<small>{text}</small>
								</span>
			
			[normal={text}]	=> <span class="color-dark-grey">
										<small>{text}</small>
									</span>
		*/

		$bold = '<span class="color-dark-black font-weight-600"><small>';
		$purple = '<span class="color-purple font-weight-600"><small>';
		$normal = '<span class="color-dark-grey"><small>';
		$close = '</small></span>';

		$content = str_replace('[bold=', $bold, $content);
		$content = str_replace('[purple=', $purple, $content);
		$content = str_replace('[normal=', $normal, $content);
		$content = str_replace(']', $close, $content);

		if(!empty($link)){
			$link .= '/?notify=' . $notify;
			$content = '<a href="'.$link.'" style="display:contents;"> '.$content.' </a>';
		}

		return $content;
	}

	public static function _conv_content_notification($content=''){
		echo self::_content_notification($content);
	}

	// ------------------------------------------------------------------------------
	// Head Title -------------------------------------------------------------------
	// ------------------------------------------------------------------------------	

	public static function _head_content($args = array())
	{
		$check = array_filter($args);
		if (empty($check)) {
			return 'Not Available';
		}

		$qty = isset($args['modal']) ? $args['modal'] : 2;

		parent::_modal_form($qty);
		parent::_head_pagebar($args['link'], $args['date']);
	?>
		<div class="sasi-tittle pl-xl light-grey">
			<div class="container-fluid pl-xl">
				<h1 class="font-weight-600"><?php echo $args['title']; ?></h1>
			</div>
		</div>
		<?php
	}

	public static function _content($func, $args = array())
	{
		if (method_exists('sasi_template', $func)) {
			// get content
			parent::{$func}($args);
		} else {
		?>
			<div style="text-align:center;"> Tidak ada data yang di Load </div>
<?php
		}
	}
}

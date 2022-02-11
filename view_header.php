<?php

class sasi_header
{
	protected static $menu = array();

	public static function _create()
	{
?>

		<!-- BEGIN HEADER INNER -->
		<?php
		self::_contain_menu();
		self::menu_user();
		?>
		<!-- END HEADER INNER -->
	<?php

		return self::$menu;
	}

	public static function _logo()
	{
	?>
		<!-- BEGIN LOGO -->
		<div class="navbar-header">
			<a class="navbar-brand" href="#">
				<img src="theme/<?php echo _theme_folder; ?>/asset/img/sasi-logo.png" width="50" height="50">
			</a>
		</div>
		<!-- END LOGO -->
	<?php
	}

	public static function _menu_toggle()
	{
	?>
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<button type="button" class="navbar-toggle topbar-content collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<!-- END RESPONSIVE MENU TOGGLER -->
	<?php
	}

	public static function _sasi_menu()
	{
		global $reg_sidebar;

	?>
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="navbar-collapse topnav collapse" id="bs-example-navbar-collapse-1" aria-expanded="false" style="height: 1px;">
			<ul class="nav navbar-nav navbar-item pl-sm">
				<?php
				self::_sidemenu_horizontal($reg_sidebar);
				?>
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	<?php
	}

	public static function _sidemenu_horizontal($args = array())
	{
		$check = array_filter($args);
		if (!empty($check)) {
			foreach ($args as $key => $val) {
				// Check active Sidemenu
				if ($val['status'] == 'active') {
					$status = 'active';
				} else {
					$status = '';
				}

				$disable = '';
				if (empty($val['func'])) {
					$disable = 'disable-target';
				}

				echo '<li>';

				echo '<a id="sobad_' . $val['func'] . '" class="sasi_sidemenu ' . $status . ' ' . $disable . '" href="javascript:" data-uri="' . $key . '">
					<h4 class="font-weight-200 pb-sm">' . $val['label'] . '</h4></a>';

				echo '</li>';
			}
		}
	}

	public static function _contain_menu()
	{
	?>
		<div class="col-lg-10">
			<div class="container-fluid">
				<div class="navbar-header">
					<?php
					self::_logo();
					self::_menu_toggle();
					?>
				</div>
				<?php
				self::_sasi_menu();
				?>
			</div>
		</div>
	<?php
	}


	public static function menu_user()
	{
		$name = get_name_user();
		$id = get_id_user();

		$user = get_picture_user();
		$dept = get_divisi_user();
		$image = empty($user) ? 'asset/img/user/no-profile.jpg' : $user;

	?>
		<div class="col-lg-2 p-md">
			<div class="icon-user">
				<div class="col-md-12">
					<div class="row">
						<div class="col-xs-11 w-95 pr-md pt-sm m-0 pl-0 text-right">
							<div class="icon-user-item">
								<h4 class="font-weight-700 color-light"><?php print($name); ?></h4>
								<h6 class="color-light"><?php print($dept); ?></h6>
							</div>
						</div>
						<div class="col-xs-1 w-5  m-0 pt-md pl-0 pr-0">
							<div class="dropdown">
								<button class="btn-circle btn dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
									<img src="<?php print($image); ?>">
								</button>
								<ul class="dropdown-menu icon-user-menu" aria-labelledby="dropdownMenu1">
									<li><a href="javascript:">My Profile</a></li>
									<li><a href="javascript:">My Calender</a></li>
									<li role="separator" class="divider"></li>
									<li><a href="javascript:">Lock Screen</a></li>
									<li role="separator" class="divider"></li>
									<a href="#myModal" data-toggle="modal" class="btn btn-sm magenta color-light radius-sm mb-md sobad_logout" tabindex="-1" role="button" aria-disabled="true">Logout</a>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}

	private static function _scroll_bar()
	{
	?>
		<div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 147.994px;"></div>
		<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div>
<?php
	}
}

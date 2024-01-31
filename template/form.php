<?php
(!defined('THEMEPATH')) ? exit : '';

class create_form
{

	private static $_types = array();

	private static $_require = array();

	public static $col_label = 12;

	public static $col_input = 12;

	public static $date_picker = false;

	public static $clock_picker = false;

	public static $auto_upload = false;

	private static function option_form($args = array())
	{
		$inp = '';
		foreach ($args as $key => $val) {
			if ($key === 'cols') {
				//self::$col_label = $val[0];
				//self::$col_input = $val[1];
			} else if ($key === 'column') {
				foreach ($args['column'] as $ky => $vl) {
?>
					<div class="col-lg-<?= self::_conv_column(count($args['column'])); ?>">
						<?php self::option_form($vl); ?>
					</div>
		<?php
				}
			} else {
				if (is_callable(array(new self(), $val['func']))) {
					$func = $val['func'];
					$inp = self::{$func}($val);
				}

				if (isset($val['type'])) {
					if ($val['type'] != 'hidden') {
						$inp = '<div class="form-group">' . $inp . '</div>';
					}
				} else {
					$inp = '<div class="form-group">' . $inp . '</div>';
				}

				echo $inp;
			}
		}
	}

	public static function get_option($opt, $args = array(), $label = 4, $input = 7)
	{
		$func = 'opt_' . $opt;

		if (!is_callable(array(new self(), $func))) {
			return false;
		}

		self::$col_label = $label;
		self::$col_input = $input;
		return self::$func($args);
	}

	private static function _conv_column($value = 1)
	{
		$args = array(0, 12, 6, 4, 3, 2, 2, 1, 1, 1, 1, 1, 1);
		return isset($args[$value]) ? $args[$value] : 12;
	}

	public static function get_form($args, $status = false)
	{
		$check = array_filter($args);
		if (empty($check)) {
			$args = array(
				0 => array(
					'key'			=> '',
					'label'			=> 'Info',
					'class'			=> '',
					'placeholder'	=> '',
					'value'			=> 'Tidak ada data yang ditemukan',
					'status'		=> 'readonly'
				),
				'cols'	=> array(4, 7),
				'id'	=> ''
			);
		}

		$id = '';
		if (isset($args['id'])) {
			$id = $args['id'];
			unset($args['id']);
		}

		?>
		<div class="col-lg-12">
			<?php if ($status) : ?>
				<form id="<?php print($id); ?>" role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
				<?php
			endif;

			self::option_form($args);
			if (!isset($_SESSION[_prefix . 'input_form'])) {
				$_SESSION[_prefix . 'input_form'] = array();
			}

			// if (!isset($_SESSION[_prefix . 'require_form'])) {
			// 	$_SESSION[_prefix . 'require_form'] = array();
			// }

			$_SESSION[_prefix . 'input_form'] = array_merge($_SESSION[_prefix . 'input_form'], self::$_types);
			// $_SESSION[_prefix . 'require_form'] = array_merge($_SESSION[_prefix . 'require_form'], self::$_require); 
				?>
				<!--<button id="metronic-submit" type="submit" class="btn" style="display: none;"></button>-->

				<?php if ($status) : ?>
				</form>
			<?php endif; ?>

		</div>
		<script>
			$('.money').on('keydown', function() {
				mask_money('.money');
			});

			$('.decimal').on('keydown', function() {
				mask_decimal('.decimal');
			});

			$('.number').on('keydown', function() {
				mask_quantity('.number');
			});

			$('.decimal3').on('keydown', function() {
				mask_decimal3('.decimal3');
			});

			<?php
			if (self::$date_picker) {
				echo 'sobad_picker();';
			}
			?>

			<?php
			if (self::$clock_picker) {
				echo 'sobad_clockpicker();';
			}
			?>

			<?php
			if (self::$auto_upload) {
				echo '
					function sasi_auto_upload(){
						$("input[name=import]").trigger("click");
					}
				';
			}
			?>

			ComponentsDropdowns.init();
			ComponentsEditors.init();
		</script>

		<script type="text/javascript">
			$(".option_box").on('click', function() {
				//Check box
				if ($(this).children('div.checker').children('span').hasClass("checked")) {
					$(this).children('div.checker').children('span').removeClass("checked");
					$(this).children('div.checker').children('span').children('input').prop("checked", false);
				} else {
					$(this).children('div.checker').children('span').addClass("checked");
					$(this).children('div.checker').children('span').children('input').prop("checked", true);
				}
			});
		</script>
	<?php
	}

	private static function opt_label($val = '', $req = false)
	{
		// label
		$req = $req ? '<span class="required" aria-required="true"> * </span>' : '';
		return '<label class="col-md-' . self::$col_label . ' control-label pt-md">' . $val . $req . '</label>';
	}

	private static function opt_hidden($val = array())
	{
	?>
		<style>
			.form-group {
				margin-bottom: 0px !important;
			}
		</style>
		<?php
		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$inp = '<input ' . $id . ' type="hidden" name="' . $val['key'] . '" value="' . $val['value'] . '">';
		return $inp;
	}

	private static function opt_input($val = array())
	{
		// id, type , class , key , value , *data
		// *data = placeholder , status , max , min , dll
		// label (optional)
		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$btn = '';
		$cols = self::$col_input;
		if (isset($val['button'])) {
			$cols -= 2;
			$btn = '<div class="col-md-1">' . $val['button'] . '</div>';
		}

		// Insert type data --->
		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		self::$_types[$val['key']] = $val['type'];

		switch ($val['type']) {
			case 'price':
				$val['type'] = 'text';
				$val['class'] .= ' money';
				break;

			case 'decimal':
				$val['type'] = 'text';
				$val['class'] .= ' decimal';
				break;

			case 'number':
				$val['type'] = 'text';
				$val['class'] .= ' number';
				break;

			case 'decimal3':
				$val['type'] = 'text';
				$val['class'] .= ' decimal3';
				break;

			case 'clock':
				$val['type'] = 'text';
				$val['class'] .= ' clockpicker';

				self::$clock_picker = true;
				break;

			default:
				# code...
				break;
		}

		$inp .= '<div class="col-md-' . $cols . '">';
		// prefix atau suffix
		if (isset($val['prefix']) || isset($val['suffix'])) {
			$inp .= '<div class="input-group mb-3">';
		}

		// prefix
		if (isset($val['prefix'])) {
			if (gettype($val['prefix']) == 'array') {
				$key_select = isset($val['setting_prefix']) ? $val['setting_prefix'] : ['key' => 'prefix_' . rand(100), 0];

				$opt_group = '';
				foreach ($val['prefix'] as $ky => $vl) {
					$select_group = $ky == $key_select['value'] ? 'selected' : '';
					$opt_group .= '<option value="' . $ky . '" ' . $select_group . '>' . $vl . '</option>';
				}

				$inp_group = '<div class="input-group-addon radius-left-sm bold"> 
				<select class="custom-select" name="' . $key_select['key'] . '" style="background: #E5E5E5;border: 0;" ' . ($key_select['attr'] ?? '') . '>
					' . $opt_group . '
				</select>
			</div>';
			} else {
				$inp_group = '<span class="input-group-addon radius-left-sm bold">' . $val['prefix'] . '</span>';
			}

			$inp .= $inp_group;
		}
		// ------

		$inp .= '<input ' . $id . ' type="' . $val['type'] . '" class="form-control ' . $val['class'] . '" name="' . $val['key'] . '" value="' . $val['value'] . '" ' . $val['data'] . ' ' . $required . '>';

		// suffix
		if (isset($val['suffix'])) {
			if (gettype($val['suffix']) == 'array') {
				$key_select = isset($val['setting_suffix']) ? $val['setting_suffix'] : ['key' => 'suffix_' . rand(100), 0];

				$opt_group = '';
				foreach ($val['suffix'] as $ky => $vl) {
					$select_group = $ky == $key_select['value'] ? 'selected' : '';
					$opt_group .= '<option value="' . $ky . '" ' . $select_group . '>' . $vl . '</option>';
				}

				$inp_group = '<div class="input-group-addon radius-right-sm bold"> 
								<select class="custom-select" name="' . $key_select['key'] . '" style="background: #E5E5E5;border: 0;" ' . ($key_select['attr'] ?? '') . '>
									' . $opt_group . '
								</select>
							</div>';
			} else {
				$inp_group = '<span class="input-group-addon radius-right-sm bold">' . $val['suffix'] . '</span>';
			}

			$inp .=  $inp_group;
		}
		// ------

		if (isset($val['prefix']) || isset($val['suffix'])) {
			$inp .= '</div>';
		}
		// ------

		$inp .= '</div>';
		return $inp . $btn;
	}

	private static function opt_box($val = array())
	{
		// id, type , class , key , value , *data
		// *data = placeholder , status , max , min , dll
		// label (optional)
		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$onclick = '';
		if (isset($val['onclick'])) {
			$onclick = 'onclick="' . $val['onclick'] . '"';
		}

		$btn = '';
		$cols = self::$col_input;
		if (isset($val['button'])) {
			$cols -= 2;
			$btn = '<div class="col-md-1">' . $val['button'] . '</div>';
		}

		$class = $val['type'] == 'radio' ? 'radio-list' : 'checkbox-list';

		$inp .= '<div class="col-md-' . $cols . '">';
		$inp .= '<div ' . $id . ' class="' . $class . '">';

		foreach ($val['data'] as $ky => $vl) {
			$check = '';

			if (is_array($val['value'])) {
				if (in_array($vl['value'], $val['value'])) {
					$check = 'checked';
				}
			} else {
				if ($val['value'] == $vl['value']) {
					$check = 'checked';
				}
			}

			$class = '';
			if (isset($val['inline'])) {
				if ($val['inline']) {
					$class = $val['type'] == 'radio' ? 'radio-inline' : 'checkbox-inline';
				}
			}

			if ($val['type'] == 'radio') {
				$type = 'radio';
			} else {
				$type = 'checker';
			}

			$inp .= '
				<label class="option_box ' . $class . '" for="box_opt' . $val['key'] . $ky . '">
					<div class="control-box">
						<input type="' . $val['type'] . '" id="box_opt' . $val['key'] . $ky . '" name="' . $val['key'] . '" value="' . $vl['value'] . '" ' . $check . ' ' . $onclick . ' ' . $required . '>
						' . $vl['title'] . ' 
					</div>
				</label>
			';
		}

		$inp .= '</div></div>';
		return $inp . $btn;
	}

	private static function opt_file($val = array())
	{
		// id , class , key , value , *data
		// *data = placeholder , status , max , min , dll
		// label (optional)
		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$txt = 'Import';
		if (isset($val['text'])) {
			$txt = $val['text'];
		}

		$auto = false;
		if (isset($val['auto_upload'])) {
			$auto = $val['auto_upload'];
		}

		self::$auto_upload = $auto;
		$inp_submit = $auto ? 'style="display:none;"' : '';
		$on_change = $auto ? 'onchange="sasi_auto_upload()"' : '';

		$cols = self::$col_input;

		$inp .= '<div class="col-md-' . ($cols - 2) . '">';
		$inp .= '<input ' . $id . ' type="file" class="form-control" name="' . $val['key'] . '" accept="' . $val['accept'] . '" ' . $on_change . ' ' . $val['data'] . ' ' . $required . '>';
		$inp .= '</div>';

		$inp .= '<div class="col-md-2">';
		$inp .= '<input type="submit" name="import" value="' . $txt . '" class="btn green" ' . $inp_submit . '>';
		$inp .= '</div>';
		return $inp;
	}

	private static function opt_textarea($val = array())
	{
		// id, key , class , rows , value
		// label (optional)
		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$data = '';
		if (isset($val['data'])) {
			$data = $val['data'];
		}

		// Insert type data --->
		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		self::$_types[$val['key']] = 'textarea';

		$inp .= '<div class="col-md-' . self::$col_input . '">';
		$inp .= '<textarea ' . $id . ' name="' . $val['key'] . '" class="form-control ' . $val['class'] . '" rows="' . $val['rows'] . '" ' . $data . ' ' . $required . '>' . $val['value'] . '</textarea>';
		$inp .= '</div>';
		return $inp;
	}

	private static function opt_wysihtml5($val = array())
	{

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label']);
			$col_input = self::$col_input;
		} else {
			$col_input = self::$col_label + self::$col_input;
		}

		// Insert type data --->
		self::$_types[$val['key']] = 'html';

		$inp .= '<div class="col-md-' . $col_input . '">';
		$inp .= '<div name="' . $val['key'] . '" id="summernote_1" class="' . $val['class'] . '" ' . $val['data'] . '>' . $val['value'] . '</div>';
		$inp .= '</div>';
		return $inp;
	}

	private static function opt_button($val = array())
	{
		// id, key , class , label, text, click, data
		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label']);
		} else {
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$inp .= '<div class="col-md-' . self::$col_input . '">';
		$inp .= '<button ' . $id . ' onclick="' . $val['click'] . '" type="' . $val['key'] . '" class="btn btn-default ' . $val['class'] . '" ' . $val['data'] . '>' . $val['text'] . '</button>';
		$inp .= '</div>';
		return $inp;
	}

	private static function opt_switch_toggle($val = array())
	{
		// id, key , class , label, value, type, data
		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label']);
		} else {
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$data = isset($val['data']) ? $val['data'] : '';

		$inp .= '<div class="col-md-' . self::$col_input . '">';
		$inp .= '<div class="form-check ' . $val['class'] . '">
            		<label>
        	    	  <input ' . $id . ' type="checkbox" name="' . $val['key'] . '" value="' . $val['value'] . '">
        	    	  <span>' . $data . '</span>
    	        	</label>
	        	</div>';
		$inp .= '</div>';
		return $inp;
	}

	private static function opt_select_tags($val = array())
	{
		// id , class , key , data, select
		// label (optional)
		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = 'tag-blood';
		if (isset($val['id'])) {
			$id = $val['id'];
		}

		$_id = str_replace('-', '', $id);

		$btn = '';
		$cols = self::$col_input;
		if (isset($val['button'])) {
			$cols -= 2;
			$btn = '<div class="col-md-1">' . $val['button'] . '</div>';
		}

		// Insert type data --->
		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		self::$_types[$val['key']] = "select";

		$inp .= '<div class="col-md-' . $cols . '">';
		$inp .= '<input id="' . $id . '" type="text" class="form-control ' . $val['class'] . '" name="' . $val['key'] . '" ' . $required . '>';

		$inp .= '</div>';

		$_data = array();
		foreach ($val['data'] as $ky => $vl) {
			$_data[] = array(
				'value'		=> $ky,
				'text'		=> $vl
			);
		}

		$_data = json_encode($_data);

		ob_start();
		?>
		<script type="text/javascript">
			var <?php print($_id); ?> = new Bloodhound({
				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('text'),
				queryTokenizer: Bloodhound.tokenizers.whitespace,
				local: <?php print($_data); ?>
			});

			<?php print($_id); ?>.initialize();

			var elt = $('#<?php print($id); ?>');

			elt.tagsinput({
				itemValue: 'value',
				itemText: 'text',
				typeaheadjs: {
					name: 'selected',
					displayKey: 'text',
					source: <?php print($_id); ?>.ttAdapter()
				}
			});

			<?php
			$text = '';
			foreach ($val['select'] as $ky => $vl) {
				$text = isset($val['data'][$vl]) ? $val['data'][$vl] : '';
				echo 'elt.tagsinput("add", { "value": ' . $vl . ' , "text": "' . $text . '"});';
			}
			?>
		</script>
	<?php
		$script = ob_get_clean();

		return $inp . $btn . $script;
	}

	private static function opt_select_search($args = [])
	{

		ob_start();
	?>

		<style>
			.search-box {
				background-color: #fff;
				padding: 20px;
				border-radius: 8px;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
				width: 300px;
				margin-bottom: 20px;
			}

			.search-input {
				width: 100%;
				padding: 10px;
				border: 1px solid #ccc;
				border-radius: 4px;
				margin-bottom: 10px;
			}

			.search-btn {
				background-color: #4285f4;
				color: #fff;
				padding: 10px;
				border: none;
				border-radius: 4px;
				cursor: pointer;
			}

			.search-results {
				width: 100%;
				list-style-type: none;
				padding: 0;
			}

			.result-item {
				background-color: #fff;
				padding: 10px;
				border: 1px solid #ccc;
				border-radius: 10px !important;
				margin-bottom: 10px;
				cursor: pointer;
				color: #969696;
			}

			.result-item:hover {
				background: #F8EBFF;
			}

			.card-list-search {
				background-color: #FBFBFB;
				border-radius: 10px !important;
				padding: 20px;
				display: none;
			}

			.btn-inner-searchlist {
				width: 100%;
				background-color: #EBF7FF;
				padding: 10px;
				display: inline-block;
				text-align: center;
				color: #009EF7;
				border-radius: 10px !important;
			}

			.btn-inner-searchlist:hover {
				background-color: #009EF7;
				color: #EBF7FF;
			}
		</style>


		<div class="search-container">
			<?= self::opt_label($args['label']); ?>
			<input value="<?= $args['value'] ?? '' ?>" <?= $args['status'] ?> name="<?= $args['key'] ?>" type="text" class="search-input form-control input-circle <?= $args['class'] ?? '' ?>" id="<?= $args['id'] ?? '' ?>" name="query" onkeyup="handleSearch()">
			<div class="card-list-search" id="searchResultsCard">
				<ul class="search-results" id="searchResults"></ul>
				<input type="hidden" id="selectedIdInput" name="selectedId" value="<?= $args['selected'] ?>">
				<?php if (isset($args['action'])) {
					$type = '';
					if (isset($args['action']['type'])) {
						$type = $args['action']['type'];
					}
				?>
					<a id="<?= $args['action']['id'] ?? '' ?>" class="btn-inner-searchlist" data-toggle="modal" data-sobad="<?= $args['action']['func'] ?>" data-load="here_modal<?= $args['action']['modal'] ?? 2 ?>" data-type="<?= $type ?>" data-alert="" data-notify="" href="#myModal<?= $args['action']['modal'] ?? 2 ?>" data-uri="" onclick="sobad_button(this,0)">
						<i class="fa fa-plus"></i> Add New
					</a>
				<?php } ?>
			</div>
		</div>

		<script>
			document.addEventListener('click', function(event) {
				var isInsideSearchResults = document.getElementById('searchResultsCard').contains(event.target);
				var isInput = $(event.target).hasClass('search-input');
				if (!isInsideSearchResults && !isInput) {
					// Menyembunyikan daftar pencarian saat mengklik di luar daftar pencarian
					$('#searchResultsCard').hide();
				}
			});

			function handleSearch() {
				const myInput = $('.search-input');
				var searchValue = $('.search-input').val();
				var searchQuery = $('.search-input').val().toLowerCase();
				var resultsContainer = $('#searchResults');
				var card = $('#searchResultsCard');

				var attributes = myInput.prop('attributes');
				var attr = [];
				$.each(attributes, function() {
					if (this.specified) {
						attr.push("&" + this.name + "=" + this.value);
					}
				});
				var attribute_tostring = attr.join('');

				var ajx = <?= json_encode($args['ajax']['on_func']) ?>;
				data = "ajax=" + ajx + "&object=" + object + "&data=" + searchValue + attribute_tostring;
				sobad_ajax('', data, domSearch, false);
			}

			function domSearch(args) {
				if (typeof args === 'string') {
					args = [];
				}
				var card = document.getElementById("searchResultsCard");
				var resultsContainer = document.getElementById("searchResults");
				var searchQuery = $('.search-input').val().toLowerCase();

				// Filter data dummy berdasarkan query
				var searchResults = args.filter(function(result) {
					return result.name.toLowerCase().includes(searchQuery);
				});

				// Menampilkan atau menyembunyikan kartu berdasarkan hasil pencarian
				if (searchResults.length > 0) {
					card.style.display = "block"; // Tampilkan kartu
				} else {
					card.style.display = "none"; // Sembunyikan kartu
				}

				// Menampilkan hasil pencarian
				resultsContainer.innerHTML = '';
				if (searchResults.length > 0 && searchQuery) {
					searchResults.forEach(function(result) {
						// script tambahan on_search
						<?= isset($args['ajax']['on_search']) ? $args['ajax']['on_search'] : ''; ?>(result);
						var liElement = document.createElement("li");
						liElement.classList.add("result-item");
						liElement.textContent = result.name;
						liElement.setAttribute('data-id', result.ID);
						liElement.addEventListener('click', function() {
							handleLiClick(liElement, result);
						});
						resultsContainer.appendChild(liElement);
					});
				} else {
					// Jika Tidak ada item yg dicari
					$(".card-list-search").show();
					$(".search-results").append("<h4 class='color-dark-grey'>Not Found</h4>");
				}
			}

			function handleLiClick(liElement, args) {
				// script tambahan on_select
				<?= isset($args['ajax']['on_select']) ? $args['ajax']['on_select'] : ''; ?>(args);
				var selectedText = liElement.textContent;
				var selectedId = liElement.getAttribute('data-id');

				// Mengatur nilai formulir sesuai dengan yang dipilih
				$('.search-input').val(selectedText);
				document.getElementById('selectedIdInput').value = selectedId;

				// Menghilangkan daftar hasil pencarian setelah dipilih
				document.getElementById('searchResults').innerHTML = '';

				// Menyembunyikan kartu setelah memilih
				document.getElementById('searchResultsCard').style.display = 'none';
			}
		</script>


	<?php
		return ob_get_clean();
	}

	private static function opt_search_tag()
	{
		ob_start();
	?>
		<style>
			body {
				font-family: Arial, sans-serif;
				display: flex;
				align-items: center;
				justify-content: center;
				height: 100vh;
				margin: 0;
			}

			.tag-info {
				margin-bottom: 10px;
				font-size: 14px;
				color: #555;
			}

			.tag-container {
				position: relative;
				width: 300px;
				border: 1px solid #ccc;
				padding: 10px;
				border-radius: 5px;
				overflow: visible;
				/* Menampilkan elemen autocompleteList di luar border */
			}

			.tag-input {
				width: calc(100% - 22px);
				/* Lebar input dikurangkan dengan margin dari tombol close */
				border: none;
				outline: none;
				font-size: 16px;
			}

			.tag-list {
				display: flex;
				flex-wrap: wrap;
				margin: 0;
				padding: 0;
				list-style: none;
			}

			.tag {
				position: relative;
				background-color: #3498db;
				color: #fff;
				border-radius: 3px;
				padding: 5px 10px;
				margin: 5px;
				display: flex;
				align-items: center;
				cursor: pointer;
			}

			.tag-close {
				margin-left: 5px;
				cursor: pointer;
			}

			.autocomplete-list {
				position: absolute;
				z-index: 1;
				width: calc(100% - 22px);
				/* Lebar autocompleteList sama dengan lebar input */
				border: 1px solid #ccc;
				max-height: 150px;
				overflow-y: auto;
				top: 100%;
				/* Menempatkan autocompleteList di bawah tag-input */
				left: 0;
				border-radius: 0 0 5px 5px;
				/* Memberikan radius pada bagian bawah */
				display: none;
				/* Mulai dengan menyembunyikan autocompleteList */
			}

			.autocomplete-item {
				padding: 10px;
				cursor: pointer;
				background-color: #f9f9f9;
			}

			.autocomplete-item:hover {
				background-color: #ddd;
			}
		</style>

		<div class="tag-info tagInfo"></div>

		<div class="tag-container">
			<ul class="tag-list tagList"></ul>
			<input type="text" class="tag-input tagInput" placeholder="Add a tag">
			<div class="autocomplete-list autocompleteList"></div>
			<input type="hidden" class="selectedTagsInput" name="selectedTags">
		</div>


		<script>
			function search_tag() {
				console.log('test-test')
				const tagInput = $(".tagInput");
				const tagList = $(".tagList");
				const autocompleteList = $(".autocompleteList");
				const tagInfo = $(".tagInfo");
				const selectedTagsInput = $(".selectedTagsInput");

				tagInput.on("input", function() {
					const inputText = tagInput.val().trim().toLowerCase();
					const suggestions = getAutocompleteSuggestions(inputText);

					renderAutocompleteList(suggestions);
				});

				tagInput.on("keydown", function(event) {
					if (event.key === "Enter" || event.key === ",") {
						event.preventDefault();
						const selectedAutocompleteItem = $(".autocomplete-item.selected");
						const tag = selectedAutocompleteItem.length ? selectedAutocompleteItem.data("tag") : tagInput.val().trim();
						addTag(tag);
						tagInput.val("");
						clearAutocompleteList();
					} else if (event.key === "ArrowUp" || event.key === "ArrowDown") {
						event.preventDefault();
						navigateAutocompleteList(event.key);
					}
				});

				tagList.on("click", ".tag-close", function() {
					removeTag($(this).parent());
				});

				$(document).on("click", function(event) {
					if (!$(event.target).hasClass("tagInput") && !$(event.target).hasClass("autocomplete-item")) {
						clearAutocompleteList();
					}
				});

				function getAutocompleteSuggestions(inputText) {
					// Data dummy untuk daftar pilihan dengan ID
					const dummyData = [{
							id: 1,
							name: "JavaScript"
						},
						{
							id: 2,
							name: "HTML"
						},
						{
							id: 3,
							name: "CSS"
						},
						{
							id: 4,
							name: "React"
						},
						{
							id: 5,
							name: "Vue"
						},
						{
							id: 6,
							name: "Node.js"
						},
						{
							id: 7,
							name: "Express"
						},
						{
							id: 8,
							name: "MongoDB"
						}
					];

					// Menghilangkan tag yang sudah dipilih dari daftar saran
					const selectedTags = tagList.find(".tag").map(function() {
						return $(this).data("tag").toLowerCase();
					}).get();
					const suggestions = dummyData.filter(tag => tag.name.toLowerCase().includes(inputText) && !selectedTags.includes(tag.name.toLowerCase()));

					return suggestions;
				}

				function renderAutocompleteList(suggestions) {
					autocompleteList.html("");
					if (suggestions.length > 0) {
						autocompleteList.css("display", "block"); // Menampilkan autocompleteList jika ada saran
					} else {
						autocompleteList.css("display", "none"); // Menyembunyikan autocompleteList jika tidak ada saran
					}

					$.each(suggestions, function(index, suggestion) {
						const item = $("<div></div>")
							.addClass("autocomplete-item")
							.text(suggestion.name)
							.data("tag", suggestion.name)
							.data("tagId", suggestion.id)
							.on("click", function() {
								addTag(suggestion.name, suggestion.id);
								tagInput.val("");
								clearAutocompleteList();
							});

						autocompleteList.append(item);
					});
				}

				function navigateAutocompleteList(direction) {
					const items = $(".autocomplete-item");
					let selectedIndex = items.filter(".selected").index();

					if (direction === "ArrowUp" && selectedIndex > 0) {
						selectedIndex--;
					} else if (direction === "ArrowDown" && selectedIndex < items.length - 1) {
						selectedIndex++;
					}

					items.removeClass("selected");
					if (selectedIndex >= 0) {
						items.eq(selectedIndex).addClass("selected");
					}
				}

				function addTag(tagName, tagId) {
					if (tagName !== "") {
						const tag = $("<li></li>")
							.addClass("tag")
							.text(tagName)
							.data("tag", tagName)
							.data("tagId", tagId)
							.append("<span class='tag-close'>&#10006;</span>");

						tagList.append(tag);
						updateTagInfo();
						updateSelectedTagsInput();
					}
				}

				function removeTag(tagElement) {
					tagElement.remove();
					updateTagInfo();
					updateSelectedTagsInput();
				}

				function clearAutocompleteList() {
					autocompleteList.html("");
					autocompleteList.css("display", "none");
				}

				function updateTagInfo() {
					const tagCount = tagList.children().length;
					tagInfo.text(`Total Tags: ${tagCount}`);
				}

				function updateSelectedTagsInput() {
					const selectedTags = tagList.find(".tag").map(function() {
						return $(this).data("tagId");
					}).get().join(",");
					selectedTagsInput.val(selectedTags);
				}

				// Update tag info saat halaman dimuat pertama kali
				updateTagInfo();
			}

			search_tag();
		</script>

	<?php
		return ob_get_clean();
	}

	private static function opt_select($val = array())
	{
		// id, key , class , data , select
		// label (optional)

		$arr_select = $val['select'];

		$func = '';
		if (is_array($val['data'])) {
			foreach ($val['data'] as $key => $opt) {
				$select = '';

				if (!is_array($arr_select)) {
					if ($key == $arr_select) {
						$select = 'selected';
					}
				} else {
					if (in_array($key, $arr_select)) {
						$select = 'selected';
					}
				}

				// Grouped
				if (isset($val['group']) && $val['group'] == true) {
					if (is_array($opt)) {
						// start grouping
						$func .= '<optgroup label="' . $key . '">';
						foreach ($opt as $ky => $grp) {
							$select = '';
							if (is_array($arr_select)) {
								if (in_array($ky, $arr_select)) {
									$select = 'selected';
								}
							} else {
								if ($ky == $arr_select) {
									$select = 'selected';
								}
							}

							$func .= self::opt_value_select($ky, $select, $grp);
						}
						$func .= '</optgroup>';
					} else {
						$func .= self::opt_value_select($key, $select, $opt);
					}
				} else {
					$func .= self::opt_value_select($key, $select, $opt);
				}
			}
		} else {
			$func = '<option value="0"> Tidak ada </option>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$status = '';
		if (isset($val['status'])) {
			$status = $val['status'];
		}

		$btn = '';
		$cols = self::$col_input;
		if (isset($val['button'])) {
			$cols -= 2;
			$btn = '<div class="col-md-1">' . $val['button'] . '</div>';
		}

		if (isset($val['searching'])) {
			if ($val['searching']) {
				$placeholder = isset($data['placeholder']) ? $data['placeholder'] : 'Search here ...';
				$val['class'] = 'bs-select';
				$status .= ' data-live-search="true" data-size="6" data-style="blue" data-live-search-placeholder="' . $placeholder . '"';
			}
		}

		// Insert type data --->
		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		self::$_types[$val['key']] = 'select';

		// Load ajax select
		$load_select = '';
		$script = '';
		if (isset($val['ajax'])) {
			$ajax = $val['ajax'];
			$idx = isset($val['id']) ? $val['id'] : '';

			$sobad = isset($ajax['on_func']) ? 'data-sobad="' . $ajax['on_func'] . '"' : '';
			$load = isset($ajax['on_load']) ? 'data-load="' . $ajax['on_load'] . '"' : '';
			$attr = isset($ajax['on_attribute']) ? 'data-attribute="' . $ajax['on_attribute'] . '"' : '';
			$change = isset($ajax['src_func']) ? 'data-change="' . $ajax['src_func'] . '"' : '';

			$load_select = $sobad . ' ' . $load . ' ' . $attr . ' ' . $change;
		}

		$onchange = isset($val['onchange']) ? $val['onchange'] : 'sobad_options(this)';
		$inp .= '<div class="col-md-' . $cols . '">';
		$inp .= '<select ' . $id . ' name="' . $val['key'] . '" class="form-control ' . $val['class'] . '" ' . $status . ' onchange="' . $onchange . '" ' . $required . $load_select . '>' . $func . '</select>';
		$inp .= '</div>';

		$inp .= $script;

		return $inp . $btn;
	}

	private static function opt_value_select($key, $select, $opt)
	{
		$func = '<option value="' . $key . '" ' . $select . '>' . $opt . ' </option>';
		return $func;
	}

	private static function opt_datepicker($val = array())
	{
		// key , class , value , date
		// id, to, data, label (optional)

		self::$date_picker = true;

		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$date = date('d-m-Y');
		if (isset($val['date'])) {
			$date = $val['date'];
		}

		$status = '';
		if (isset($val['status'])) {
			$status = $val['status'];
		}

		$status2 = '';
		if (isset($val['status2'])) {
			$status2 = $val['status2'];
		}

		$class = '';
		if (isset($val['to'])) {
			$class = 'input-group input-large';
		}

		// Insert type data --->
		self::$_require[$val['key']] = array(
			'name'		=> $val['label'],
			'status'	=> $req
		);

		self::$_types[$val['key']] = 'date';

		$val['value'] = date($val['value']);
		$val['value'] = strtotime($val['value']);
		$val['value'] = date('d-m-Y', $val['value']);

		$inp .= '<div class="col-md-' . self::$col_input . '">';
		$inp .= '<div ' . $id . ' class="' . $class . ' input-daterange date-picker ' . $val['class'] . '" data-date="' . $date . '" data-date-format="dd-mm-yyyy">';
		$inp .= '<input type="text" class="form-control" value="' . $val['value'] . '" name="' . $val['key'] . '" ' . $status . ' ' . $required . '>';

		if (isset($val['to'])) {
			$val['data'] = date($val['data']);
			$val['data'] = strtotime($val['data']);
			$val['data'] = date('d-m-Y', $val['data']);

			$inp .= '<span class="input-group-addon"> to </span>';
			$inp .= '<input type="text" class="form-control" value="' . $val['data'] . '" name="' . $val['to'] . '" ' . $status2 . ' ' . $required . '>';

			// Insert type data --->
			self::$_require[$val['to']] = array(
				'name'		=> $val['label'],
				'status'	=> $req
			);

			self::$_types[$val['to']] = 'date';
		}

		$inp .= '</div>';
		$inp .= '</div>';

		return $inp;
	}

	private static function opt_dropzone($val = array())
	{
		// id, func, callback, object , accept, load, data, value
		// index

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label']);
		} else {
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}

		// Get layout dropzone
		$val['func'] = $val['callback'];
		$val['tag'] = 'div';

		ob_start();
		sasi_layout::sobad_file_manager(array(
			'func'	=> '_upload_file',
			'data'	=> $val
		));
		$upload = ob_get_clean();

		$inp .= '<div class="col-md-' . self::$col_input . '">';
		$inp .= $upload;
		$inp .= '</div>';
		return $inp;
	}

	private static function opt_range_slider($val = array())
	{
		$req = false;
		$required = '';
		if (isset($val['required'])) {
			$req = $val['required'];
			$required = $req ? 'required' : '';
		}

		$id = '';
		if (isset($val['id'])) {
			$id = 'id="' . $val['id'] . '"';
		}

		$cols = self::$col_input;
		if (isset($val['button'])) {
			$cols -= 2;
			$btn = '<div class="col-md-1">' . $val['button'] . '</div>';
		}

		$inp = '';
		if (isset($val['label'])) {
			$inp .= self::opt_label($val['label'], $req);
		} else {
			$val['label'] = '';
			//			$inp .= '<div class="col-md-' . self::$col_label . '"></div>';
		}
		$range = '1';
		if (isset($val['range'])) {
			$range = $val['range'];
		}

		$note = isset($val['note']) ? '<small class="form-text text-muted">' . $val['note'] . '</small>' : '';

		$inp .= '<div class="col-md-' . $cols . ' mb-xs">';
		$inp .= '<div class="range-slider">
					<div class="row m-0">
						<div class="col-md-10 p-0"><input ' . $id . ' class="range-slider__range" type="range" value="' . $val['value'] . '" min="0" max="100" step="' . $range . '" name="' . $val['key'] . '" style="height: 20px;"></div>
						<div class="col-md-1 m-xs"><span class="range-slider__value">' . $val['value'] . '</span>%</div>
					</div>
				</div>
				' . $note . '';
		$inp .= '</div>';

	?>
		<script>
			const settings = {
				fill: '#1abc9c',
				background: '#d7dcdf'
			}
			const sliders = document.querySelectorAll('.range-slider');
			Array.prototype.forEach.call(sliders, (slider) => {
				slider.querySelector('input').addEventListener('input', (event) => {
					// 1. apply our value to the span
					slider.querySelector('span').innerHTML = event.target.value;
					// 2. apply our fill to the input
					applyFill(event.target);
				});
				// Don't wait for the listener, apply it now!
				applyFill(slider.querySelector('input'));
			});
			// This function applies the fill to our sliders by using a linear gradient background
			function applyFill(slider) {
				// Let's turn our value into a percentage to figure out how far it is in between the min and max of our input
				const percentage = 100 * (slider.value - slider.min) / (slider.max - slider.min);
				// now we'll create a linear gradient that separates at the above point
				// Our background color will change here
				const bg = `linear-gradient(90deg, ${settings.fill} ${percentage}%, ${settings.background} ${percentage+0.1}%)`;
				slider.style.background = bg;
			}
		</script>
<?php
		return $inp;
	}
}

<?php

	function render_view($view, $data){
		global $user, $module, $method, $lex, $lang;
		// Turn $data array to variables
		if (isset($data))
			foreach ($data as $key => $val)
				$$key = $val;
		// Render view to the output_buffer
		ob_start();
			if (file_exists($view))
				include $view;
			else
				require_once 'templates/error_404.php';
			$yield = ob_get_contents();
		ob_end_clean();
		return $yield;
	}

	function render_template($template, $yield, $html_head = false){
		global $user, $module, $method, $lex, $lang; //, $html_head;
		// Render the view inside the template to the output_buffer
		if ($html_head == false){
			$html_head = array('title' => ucfirst($module).' '.ucfirst(str_replace('_', ' ', $method)));
		}
		ob_start();
			if (file_exists('templates/'.$template)){
				include 'templates/'.$template;
				$yield = ob_get_contents();
			}
			else{
				require_once 'templates/error_404.php';
			}
		ob_end_clean();
		//
		return $yield;
	}

	// ========================================================

	function render_data_view($schema, $data, $edit_action){
		echo '<br/>';
		foreach ($schema as $key => $field){
			if (isset($field['key']) && $field['key'] || (isset($field['display']) && $field['display'] == 'hidden')){
			}
			else if (!isset($field['form']) || $field['form']){ ?>
		<label for="<?= $key; ?>"><?= $field[0]; ?></label>
		<div class="row">
			<div class="col-xs-6 col-md-4">
<?php 			if (isset($field['enum'])){
					echo isset($field['enum'][$data[$key]]) ? (is_array($field['enum'][$data[$key]]) ? $field['enum'][$data[$key]][0] : $field['enum'][$data[$key]]) : '-';
				}
				else if (isset($field['display'])){
					if ($field['display'] == 'calendar'){
						echo beautify_datetime($data[$key]);
					}
					if ($field['display'] == 'calendar,clock'){
						echo beautify_datetime($data[$key]);
					}
					else if ($field['display'] == 'password'){
						echo '[ ** Encrypted ** ]';
					}
					else if ($field['display'] == 'folder'){
						$path = explode('}', $field['path']);
						foreach ($path as &$segment)
							if ($segment != ''){
								$segment = explode('{', $segment);
								$segment = $segment[0].$data[$segment[1]];
							}
						$path = implode($path); ?>
				<iframe src="<?= BASE_URL; ?>dashboard/folder/view/<?= $path; ?>" class="form-control"></iframe>
<?php 				}
					else if ($field['display'] == 'textarea' || $field['display'] == 'richtext'){
						echo $data[$key];
					}
				}
				else{
					echo $data[$key];
				} ?>
				<hr/>
			</div>
		</div>
<?php 		}
		} ?>
		<input class="btn" type="button" value="Edit" onclick="window.location='<?= BASE_URL.$edit_action; ?>';" />
		<input class="btn" type="button" value="Back" onclick="window.history.back();" />
<?php }

	// ========================================================

	function render_form($schema, $data, $action){
		$file_upload = false;
		$calendar = false;
		$richtext = false;
		$autofill = false;
		foreach ($schema as $col => $meta)
			if (isset($meta['display'])){
				if ($meta['display'] == 'calendar' || $meta['display'] == 'calendar,clock')
					$calendar = true;
				else if ($meta['display'] == 'file')
					$file_upload = true;
				else if ($meta['display'] == 'richtext')
					$richtext = true;
			}
			else if (isset($meta['autofill']))
				$autofill = true;
		if ($richtext){ ?>
	<script src="<?= BASE_URL_STATIC; ?>tinymce/tinymce.min.js"></script>
	<style>.mce-tinymce.mce-container.mce-panel{width:609px; border:0 solid #DDDDDD;} .mce-tinymce.mce-container.mce-panel{width:99.5%;}</style>
<?php 	}
		if ($autofill){ ?>
	<script src="<?= BASE_URL_STATIC; ?>js/select2filter.js"></script>
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL_STATIC; ?>css/select2filter.css" />
<?php 	} ?>
	<form method="post" class="data-form" action="<?= BASE_URL.$action; ?>"<?= $file_upload ? ' enctype="multipart/form-data"' : ''; ?> onsubmit="return validate(this);" autocomplete="off">
<?php 	foreach ($schema as $key => $field){
			if (isset($field['key']) && $field['key'] || (isset($field['display']) && $field['display'] == 'hidden')){ ?>
		<input type="hidden" name="<?= $key; ?>" value="<?= $data[$key]; ?>" />
<?php 		}
			else if (!isset($field['form']) || $field['form']){ ?>
		<div class="form-group row<?= isset($field['form-width']) ? ' width-'.$field['form-width'] : ''; ?>">
			<div class="col-xs-6 col-md-4<?= isset($field['required']) && $field['required'] ? ' required' : ''; ?><?= isset($field['display']) && $field['display'] == 'currency' ? ' currency' : ''; ?>">
				<label for="<?= $key; ?>"><?= $field[0]; ?></label>
<?php 			if (isset($field['enum']) /*&& is_array($field['enum'])*/){
					render_dropdown($key, $field['enum'], $data[$key]);
				}
				else if (isset($field['autofill'])){
					render_dropdown($key, $field['autofill'], $data[$key]);
					echo '<script>new select2filter(document.forms[0].'.$key.');</script>';
				}
				else if (isset($field['display'])){
					if ($field['display'] == 'calendar'){ ?>
				<input type="text" class="form-control" name="<?= $key; ?>" value="<?= substr($data[$key], 0, 10); ?>" data-validate="date" onfocus="ShowCalendar(this);" readonly="true" />
<?php 				}
					if ($field['display'] == 'calendar+clock'){ ?>
				<input type="text" class="form-control" name="<?= $key; ?>" value="<?= substr($data[$key], 0, 10); ?>" data-validate="date" onfocus="ShowCalendar(this, 'clock');" readonly="true" />
<?php 				}
					else if ($field['display'] == 'password'){ ?>
				<input type="password" class="form-control" name="<?= $key; ?>" value="<?= $data[$key]; ?>" />
<?php 				}
					else if ($field['display'] == 'textarea' || $field['display'] == 'richtext'){ ?>
				<textarea name="<?= $key; ?>" rows="3" class="form-control<?= $field['display'] == 'richtext' ? ' richtext' : ''; ?>"><?= $data[$key]; ?></textarea>
<?php 				}
					else if ($field['display'] == 'email'){ ?>
				<input type="email" class="form-control" name="<?= $key; ?>" value="<?= $data[$key]; ?>" />
<?php 				}
					else if ($field['display'] == 'currency'){ ?>
				<span>Rs.</span>
				<input type="text" class="form-control" name="<?= $key; ?>" value="<?= $data[$key]; ?>" data-validate="currency" />
<?php 				}
					else if ($field['display'] == 'numeric'){ ?>
				<input type="text" class="form-control" name="<?= $key; ?>" value="<?= $data[$key]; ?>" data-validate="numeric" />
<?php 				}
					else if ($field['display'] == 'check' || $field['display'] == 'checkbox'){ ?>
				<input type="checkbox" class="form-control" name="<?= $key; ?>" value="<?= $data[$key]; ?>" />
<?php 				}
					else if ($field['display'] == 'file'){ ?>
				<input type="file" class="form-control" name="<?= $key; ?>" />
				<br/><br/><img src="<?= BASE_URL_STATIC.$data[$key].'-thumb.jpg'; ?>" width="240" />
<?php 				}
					else if ($field['display'] == 'folder'){
						$path = explode('}', $field['path']);
						foreach ($path as &$segment)
							if ($segment != ''){
								$segment = explode('{', $segment);
								$segment = $segment[0].$data[$segment[1]];
							}
						$path = implode($path); ?>
				<iframe src="<?= BASE_URL; ?><?= $path; ?>" class="form-control"></iframe>
<?php 				}
				}
				else{ ?>
				<input type="text" class="form-control" name="<?= $key; ?>" value="<?= $data[$key]; ?>" />
<?php 			} ?>
			</div>
		</div>
<?php 		}
		} ?>
		<div class="row">
			<input class="btn" type="submit" value="Save" />
			<input class="btn" type="button" value="Cancel" onclick="window.history.back();" />
		</div>
	</form>
<?php 	if ($richtext){ ?>
<script>
tinymce.init({
	selector: "textarea.richtext", plugins: ["link image code fullscreen textcolor"], convert_urls: false,
	toolbar: "bold italic | forecolor backcolor | fontsizeselect | alignleft aligncenter alignright | bullist numlist"
});
</script>
<?php 	}
		if ($calendar)
			render_calendar();
	}

	// ========================================================

	$cal_rendered = false;
	function render_calendar(){
		global $cal_rendered;
		if ($cal_rendered)
			return false;
		$cal_rendered = true; ?>
<script src="<?= BASE_URL_STATIC; ?>js/calendar.js"></script>
<script src="<?= BASE_URL_STATIC; ?>js/js_vlib.js"></script>
<link rel="stylesheet" type="text/css" href="<?= BASE_URL_STATIC; ?>css/calendar.css" /><?php
		include 'interfaces/calendar.php';
	}

	// ========================================================

	function render_table($schema, $data, $classname = false){
		$found = false; ?>
	<table width="100%" class="table table-striped<?= $classname != false ? ' '.$classname : ''; ?>"><thead><tr>
<?php 	$cmd_opened = false;
		foreach ($schema as $col => $meta){
			if (!isset($meta['table']) || $meta['table']){
				if (isset($meta['cmd']) || isset($meta['onclick'])){
					if (!$cmd_opened){
						echo '<th width="120" class="action_btns">Actions';
						$cmd_opened = true;
					}
				}
				else{
				?><th><?= $meta[0]; ?></th><?php
				}
			}
		}
		if ($cmd_opened)
			echo '</th>';
		?></tr></thead><tbody><?php
		$key = false;
		while ($row = mysql_fetch_assoc($data)){
			foreach ($schema as $col => $meta){
				if (isset($meta['key']) && $meta['key'])
					$key = $row[$col];
			} ?><tr onclick="return row_click(this);"<?= isset($key) ? 'data-key="'.$key.'"' : ''; ?>><?php
			$cmd_opened = false;
			foreach ($schema as $col => $meta){
				if (isset($meta['cmd']) || isset($meta['onclick'])){
					if (!$cmd_opened){
						echo '<td class="action_btns">';
						$cmd_opened = true;
					}
					?> &nbsp;<a class="<?= $col; ?><?= isset($meta['default']) ? ' default' : ''; ?>" href=<?php
					if (isset($meta['cmd']))
						echo '"' . BASE_URL.str_replace('{key}', $key, $meta['cmd']) . '"' . (isset($meta['confirm']) ? ' onclick="if (confirm(\'Are you sure.?\')){return true;}else{event.stopPropagation(); return false;}"' : '');	//	' onclick="if (confirm(\'Are you sure.?\')){return true;}else{event.stopPropagation(); return false;}"'
					else if (isset($meta['onclick']))
						echo '"javascript:void(0);" onclick="'.str_replace('{key}', $key, $meta['onclick']) . '"';
					?>><?= $meta[0]; ?></a><?php
				}
				else if (!isset($meta['table']) || $meta['table']){
					echo '<td>';
					if (isset($meta['table-display'])){
						if ($meta['table-display'] == 'enum')	//echo '<small>'.$row[$col].'</small>';
							render_dropdown($col.'['.$key.']', $meta['enum'], $row[$col], $col, (isset($meta['onchange']) ? $meta['onchange'] : false), true);
						else if ($meta['table-display'] == 'calendar')
							echo '<input type="text" class="form-control '.$col.'" name="'.$col.'['.$key.']" value="'.substr($row[$col], 0, 10).'" '.(isset($meta['onchange']) ? 'onchange="'.$meta['onchange'].'"' : '').'onfocus="return ShowCalendar(this);" readonly="true" />';
						else if ($meta['table-display'] == 'calendar,clock')
							echo '<input type="text" class="form-control '.$col.'" name="'.$col.'['.$key.']" value="'.$row[$col].'" onfocus="return ShowCalendar(this, \'clock\');" readonly="true" />';
						else if ($meta['table-display'] == 'small')
							echo '<small>'.$row[$col].'</small>';
						else
							echo $row[$col];
					}
					else if (isset($meta['enum']))
						echo isset($meta['enum'][$row[$col]]) ? (is_array($meta['enum'][$row[$col]]) ? $meta['enum'][$row[$col]][0] : $meta['enum'][$row[$col]]) : '-';
					else if (isset($meta['autofill']))
						echo isset($meta['autofill'][$row[$col]]) ? (is_array($meta['autofill'][$row[$col]]) ? $meta['autofill'][$row[$col]][0] : $meta['autofill'][$row[$col]]) : '-';
					else if (isset($meta['display'])){
						if ($meta['display'] == 'calendar' || $meta['display'] == 'calendar,clock')
							echo beautify_datetime($row[$col]);
						else
							echo $row[$col];
					}
					else
						echo isset($row[$col]) ? $row[$col] : '-';
					echo '</td>';
				}
			}
			if ($cmd_opened)
				echo '</td>';
			?></tr><?php
			$found = true;
		}
		if (!$found){
			?><tr class="no-records"><td colspan="99"><i>No records to display</i></td></tr><?php
		} ?></tbody></table><?php
	}

	// ========================================================

	function render_dropdown($name, $data, $selected = false, $classname = false, $onchange = false, $ontblrow = false){ ?>
				<select name="<?= $name; ?>" class="form-control<?= $classname != false ? ' '.$classname : ''; ?>"<?= $onchange != false ? ' onchange="'.$onchange.'"' : ''; ?><?= $ontblrow != false ? ' onmouseover="row_click_latch=true;" onmouseout="row_click_latch=false;"' : ''; ?>>
					<option value="" class="first-child">Please Select</option>
<?php 				foreach ($data as $key => $val){ ?>
					<option value="<?= $key; ?>" <?= $selected == $key ? 'selected' : ''; ?>><?= is_array($val) ? $val[0] : $val; ?></option>
<?php 				} ?>
				</select>
<?php }

	// ========================================================

	function flash_message($message, $level, $fadeout = false){
		if (!isset($_SESSION['flash_messages']))
			$_SESSION['flash_messages'] = array();
		$_SESSION['flash_messages'][] = array($level, $message, $fadeout);
	}

	function flash_message_dump(){
		if (!isset($_SESSION['flash_messages']) || count($_SESSION['flash_messages']) == 0)
			return true;
		//
		echo '<div id="flash_messages">';
		foreach ($_SESSION['flash_messages'] as $flash_message){
			$div_id = rand(20, 34956344).'_'.time();
			echo '<div class="'.$flash_message[0].'" id="flash_message_'.$div_id.'"><div class="icon"></div>'.$flash_message[1].
				'<a class="dismiss" href="javascript:popup_bring_down(\'flash_message_'.$div_id.'\', 100);"></a></div>';
			if ($flash_message[2])
				echo '<script>setTimeout("popup_bring_down(\'flash_message_'.$div_id.'\', 100);", 1600);</script>';
		}
		echo '</div>';
		//
		$_SESSION['flash_messages'] = array();
	}

	function shorten_string($string, $len, $content_id = false, $skip = 0){
		$string = str_replace("\t", '', $string);
		$string = str_replace(array("\n", "\r", '&nbsp;', '  '), ' ', $string);
		$string = preg_replace('#<script(.*?)</script>#s', '', $string);
		$string = strip_tags($string);
		//$string = str_replace(array('&', '<', '>', '"'), array('&amp;', '&lt;', '&gt;', '&quot;'), $string);
		//
		$len += $skip;
		if (strlen($string) < $len)
			return substr($string,  $skip);
		$tmp = strpos($string, ' ', $len);
		if ($tmp === false)
			return substr($string,  $skip);
		$shorten_count = uniqid();
		if ($content_id == false)
			return substr($string, $skip, $tmp - $skip);
		else
			return substr($string, $skip, $tmp - $skip).
				' <a href="'.BASE_URL.'content/view/'.$content_id.'" '.
					'onclick="show_more_text(\''.$shorten_count.'\', \''.$content_id.':x\', '.$tmp.'); return false;" '.
					'id="shorten_more_link_'.$shorten_count.'">...more</a>'.
				'<span style="display:none;" id="shorten_more_'.$shorten_count.'">'.'</span>';
	}

	function beautify_datetime($datetime){
		global $lang;
		$time = is_numeric($datetime) ? $datetime : strtotime($datetime);
		if ($time == 0)
			return '-';
		return date('Y-M-d', $time);
		$now = time();
		$diff = $now - $time;
		if ($time == 0)
			return '-';
		if ($diff < 86400){
			if ($diff < 0)
				return _beautify_datetime_future($datetime);
			else if ($diff < 5)
				return 'Just now';
			else if ($diff < 60)
				return $diff.'Seconds ago';
			else{
				$diff = floor($diff / 60);
				if ($diff < 60)
					return $diff.'Minutes ago';
				else if (date('j', $now) == date('j', $time))
					return date('g:i', $time).date('a', $time);
				else
					return 'Yesterday'.' '.date('g', $time).' '.date('a', $time);
			}
		}
		else
			if (date('Y', $now) == date('Y', $time))
				if (date('n', $now) == date('n', $time))
					if (date('W', $now) == date('W', $time))
						return date('l', $time).' '.date('g', $time).' '.date('a', $time);
					else
						return date('j', $time).date('S', $time).' '.date('M', $time).' '.date('g', $time).' '.date('a', $time);
				else
					return date('j', $time).date('S', $time).' '.date('M', $time);
			else
				return date('M', $time).' '.date('Y', $time);
	}
	/*function _beautify_datetime_future($datetime){
		$time = strtotime($datetime);
		$now = time();
		$diff = $time - $now;
		if ($diff < 86400){
			if ($diff < 5)
				return 'Just now';
			else if ($diff < 60)
				return 'in '.$diff.' seconds';
			else{
				$diff = floor($diff / 60);
				if ($diff < 60)
					return 'in '.$diff.' minutes';
				else if (date("j", $now) == date("j", $time))
					return date("g:i a", $time);
				else
					return "Tomorrow ".date("g a", $time);
			}
		}
		else
			if (date("Y", $now) == date("Y", $time))
				if (date("n", $now) == date("n", $time))
					if (date("W", $now) == date("W", $time))
						return date("l g a", $time);
					else
						return date("jS M g a", $time);
				else
					return date("jS M", $time);
			else
				return date("M Y", $time);
	}*/

	function slugify($text){
		$text = preg_replace('/[\/_|+ -.]+/', '-', $text);
		$text = trim($text, '-');
		$text = strtolower($text);
		return $text;
	}

?>

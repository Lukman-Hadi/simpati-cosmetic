<?php

function buildTree(array $rows, $parent = 0)
{
	$branch = array();
	foreach ($rows as $row) {
		if ($row['parent_id'] == $parent) {
			$children = buildTree($rows, $row['id']);
			if ($children) {
				$row['children'] = $children;
			}
			$branch[] = $row;
		}
	}
	return $branch;
}

function buildTableTree($menus)
{
	$result = '';
	foreach ($menus as $menu) {
		if (isset($menu['children'])) {
			$result .= buildSubTableTree($menu);
		} else {
			$result .= "<tr>
							<td>" . $menu['nama'] . "</td>
							<td>
								<label class='custom-toggle'>
									<input type='checkbox' " . checked_akses($menu['access']) . " name='menus[]' value='" . $menu['id'] . "'>
									<span class='custom-toggle-slider rounded-circle' data-label-off='No' data-label-on='Yes'></span>
								</label>
							</td>
						</tr>";
		}
	}
	return $result;
}
function buildSubTableTree($parent)
{
	$result = '';
	$result .= "<tr>
					<td>" . $parent['nama'] . "</td>
					<td>
						<label class='custom-toggle'>
							<input type='checkbox' class='parent-" . $parent['link'] . "' onClick='" . 'kasiAkses("' . $parent['link'] . '")' . "' " . checked_akses($parent['access']) . " name='menus[]' value='" . $parent['id'] . "'>
							<span class='custom-toggle-slider rounded-circle' data-label-off='No' data-label-on='Yes'></span>
						</label>
					</td>
				</tr>";
	$children = $parent['children'];
	foreach ($children as $child) {
		if (isset($child['children'])) {
			$result .= buildSubTableTree($child, $parent);
		} else {
			$result .= "<tr>
							<td>" . $child['nama'] . "</td>
							<td>
								<label class='custom-toggle'>
									<input type='checkbox' class='" . $parent['link'] . "' " . checked_akses($child['access']) . " name='menus[]' value='" . $child['id'] . "' onClick='" . 'kasiAkses("' . $parent['link'] . '")' . "'>
									<span class='custom-toggle-slider rounded-circle' data-label-off='No' data-label-on='Yes'></span>
								</label>
							</td>
						</tr>";
		}
	}
	return $result;
}
function checked_akses($checked)
{
	return $checked ? "checked='checked'" : "";
	// return "checked='checked'";
}
function buildMenu($menus = [], $active = [])
{
	// $ci = get_instance();
	// // $menus = buildTree($ci->menu->getMenus());
	if (!isset($menus)) return '';
	$menus = buildTree($menus);
	$result = '<ul class="navbar-nav">';
	foreach ($menus as $menu) {
		$result .= "<li class='nav-item'>";
		if (isset($menu['children'])) {
			$result .= buildSubMenu($menu, $active);
		} else {
			if (count($active) > 1) {
				$isActive = $active[0] == $menu['link'] ? 'active' : ' ';
			} else {
				$isActive = $menu['link'] == '/' ? 'active' : '';
			}
			$result .= "<a class='nav-link " . $isActive . "' href='" . base_url($menu['link']) . "'>
							<i class='" . $menu['icon'] . "'></i>
							<span class='nav-link-text'>" . $menu['title'] . "</span>
						</a>";
		}
		$result .= "</li>";
	}
	$result .= "</ul>";
	return $result;
}
function buildSubMenu($parent, $active)
{
	if (count($active) > 0) {
		$GrandParent = array_search($active[0], array_column($parent["children"], 'link'));
		$isParentActive = ($active[0] == $parent['link'] || $GrandParent) ? 'active' : ' ';
		$isParentCollapsed = ($active[0] == $parent['link'] || $GrandParent) ? 'show' : ' ';
		$isParentExpanded = ($active[0] == $parent['link'] || $GrandParent) ? 'true' : 'false';
	} else {
		$isParentActive = '';
		$isParentCollapsed = '';
		$isParentExpanded = '';
	}
	$result = "<a class='nav-link " . $isParentActive . "' href='#" . $parent['link'] . "' data-toggle='collapse' role='button' aria-expanded='" . $isParentExpanded . "' aria-controls='" . $parent['link'] . "'>
					<i class='" . $parent['icon'] . "'></i>
					<span class='nav-link-text'>" . $parent['title'] . "</span>
				</a>
				<div class='collapse " . $isParentCollapsed . "' id='" . $parent['link'] . "'>
					<ul class='nav nav-sm flex-column'>";
	$children = $parent['children'];
	foreach ($children as $child) {
		if (isset($child['children'])) {
			$result .= buildSubMenu($child, $active);
		} else {
			if (count($active) > 1) {
				$isActive = $active[1] == $child['link'] ? 'active' : ' ';
			} else {
				$isActive = '';
			}
			$result .= "<li class='nav-item " . $isActive . "'>
							<a href='" . base_url($parent['link'] . "/" . $child['link']) . "' class='nav-link'><i class='" . $child['icon'] . "'></i>" . $child['title'] . "</a>
						</li>";
		}
	}
	$result .= "</ul></div>";
	return $result;
}

function buildMenuExxxxx($rows, $parent = 0)
{
	$result = '<ul class="navbar-nav">';
	foreach ($rows as $row) {
		echo $row->parent_id . '<br>';
		if ($row->parent_id == $parent) {
			$result .= "<li class='nav-item'>";
			echo $row->title . '<br>';
			if (menuHasChild($rows, $row->id)) {
				print_r($row);
				$result .= "<a class='nav-link' href='#$row->link'>
								<i class='$row->icon'></i>
								<span class='nav-link-text'>$row->title</span>
							</a>
							<div class='collapse' id='$row->link'>
								<ul class='nav nav-sm flex-column'>";
				$result .= buildMenu($rows, $row->id);
			} else {
				$result .= "<a class='nav-link' href='$row->link'>
								<i class='$row->icon'></i>
								<span class='nav-link-text'>$row->title</span>
							</a>";
			}
			$result .= "</li>";
		}
	}
	$result .= "</ul>";
	return $result;
}
function menuHasChild($rows, $id)
{
	foreach ($rows as $row) {
		// echo $row->parent_id.'<br>';
		if ($row->parent_id == $id) {
			return true;
		} else {
			return false;
		}
	}
}

function generateRefNo($type)
{
	$ci = get_instance();
	$ci->load->model("Stock_model", "model");
	if ($type == "add") {
		$satu = "TB";
	} else {
		$satu = "PY";
	}
	$month = date('m');
	$year = date('Y');
	$dua = $ci->model->getRef($month, $year);
	return $satu . '/' . $dua . '/' . $month . '/' . $year;
}

function generateInvoiceNo()
{
	$ci = get_instance();
	$ci->load->model("Penjualan_model", "model");
	$satu = "INV";
	$month = date('m');
	$year = date('Y');
	$dua = $ci->model->getRef($month, $year);
	return $satu . '/' . $dua . '/' . $month . '/' . $year;
}
function is_login()
{
	$ci = get_instance();
    if (!$ci->session->userdata('id')) {
        return false;
    } else {
        return true;
    }
}

function terbilang($angka)
{
	$angka = (float)$angka;

	$bilangan = array(
		'',
		'Satu',
		'Dua',
		'Tiga',
		'Empat',
		'Lima',
		'Enam',
		'Tujuh',
		'Delapan',
		'Sembilan',
		'Sepuluh',
		'Sebelas'
	);

	if ($angka < 12) {
		return $bilangan[$angka];
	} else if ($angka < 20) {
		return $bilangan[$angka - 10] . ' Belas';
	} else if ($angka < 100) {
		$hasil_bagi = (int)($angka / 10);
		$hasil_mod = $angka % 10;
		return trim(sprintf('%s Puluh %s', $bilangan[$hasil_bagi], $bilangan[$hasil_mod]));
	} else if ($angka < 200) {
		return sprintf('seratus %s', terbilang($angka - 100));
	} else if ($angka < 1000) {
		$hasil_bagi = (int)($angka / 100);
		$hasil_mod = $angka % 100;
		return trim(sprintf('%s Ratus %s', $bilangan[$hasil_bagi], terbilang($hasil_mod)));
	} else if ($angka < 2000) {
		return trim(sprintf('seribu %s', terbilang($angka - 1000)));
	} else if ($angka < 1000000) {
		$hasil_bagi = (int)($angka / 1000); // karena hasilnya bisa ratusan jadi langsung digunakan rekursif
		$hasil_mod = $angka % 1000;
		return sprintf('%s Ribu %s', terbilang($hasil_bagi), terbilang($hasil_mod));
	} else if ($angka < 1000000000) {
		$hasil_bagi = (int)($angka / 1000000);
		$hasil_mod = $angka % 1000000;
		return trim(sprintf('%s Juta %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
	} else if ($angka < 1000000000000) {
		$hasil_bagi = (int)($angka / 1000000000);
		$hasil_mod = fmod($angka, 1000000000);
		return trim(sprintf('%s Milyar %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
	} else if ($angka < 1000000000000000) {
		$hasil_bagi = $angka / 1000000000000;
		$hasil_mod = fmod($angka, 1000000000000);
		return trim(sprintf('%s Triliun %s', terbilang($hasil_bagi), terbilang($hasil_mod)));
	} else {
		return 'Wow...';
	}
}

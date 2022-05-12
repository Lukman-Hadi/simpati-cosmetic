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
							<input type='checkbox' class='parent-".$parent['link']."' onClick='".'kasiAkses("'.$parent['link'].'")'."' " . checked_akses($parent['access']) . " name='menus[]' value='" . $parent['id'] . "'>
							<span class='custom-toggle-slider rounded-circle' data-label-off='No' data-label-on='Yes'></span>
						</label>
					</td>
				</tr>";
	$children = $parent['children'];
	foreach ($children as $child) {
		if (isset($child['children'])) {
			$result .= buildSubTableTree($child,$parent);
		} else {
			$result .= "<tr>
							<td>" . $child['nama'] . "</td>
							<td>
								<label class='custom-toggle'>
									<input type='checkbox' class='".$parent['link']."' " . checked_akses($child['access']) . " name='menus[]' value='" . $child['id'] . "' onClick='".'kasiAkses("'.$parent['link'].'")'."'>
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
function buildMenu($menus=[],$active = [])
{
	// $ci = get_instance();
	// // $menus = buildTree($ci->menu->getMenus());
	if(!isset($menus))return '';
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

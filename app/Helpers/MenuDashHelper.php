<?php

/**
 * Menu Helper
 *
 * Updated 31 Agustus 2021, 09:40
 *
 * @author Muhammad Ikhwan Fathulloh
 *
 */

namespace App\Helpers;

use Request;
use Illuminate\Support\Facades\Auth;
use Modules\SysMenu\Repositories\SysMenuRepository;

class MenuDashHelper
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public static function render()
    {
        $_sysmenuRepository = new SysMenuRepository;

        $getMenus   = $_sysmenuRepository->getAllOrderByParams(['menu_is_sub' => 0]);
        $menus      = "";

        if (sizeof($getMenus) > 0) {

            foreach ($getMenus as $menu) {

                $getSubs    = $_sysmenuRepository->getAllOrderByParams(['menu_parent_id' => $menu->menu_id]);
                $subs       = "";
                $subLinks   = array();

                if (sizeof($getSubs) > 0) {
                    
                    $areSubs = false;

                    foreach ($getSubs as $sub) {

                        // Check Role
                        $getRole = $_sysmenuRepository->getRole($sub->module_id, Auth::user()->group_id);

                        if (!$getRole) {
                            continue;
                        }

                        $active = '';

                        if (Request::segment(1) == $sub->menu_url) {
                            $active = 'active';
                        }

                        $subLinks[] = $sub->menu_url;

                        $subs .= "<li class='nav-item sidebar-item" . $active . "'><a class='nav-link sidebar-link pl-3' href='" . url($sub->menu_url) . "'><span class='ml-1 item-text'>" . $sub->menu_name . "</span></a></li>";

                        $areSubs = true;
                    }

                    if (!$areSubs) continue;

                    $active   = '';
                    $show     = '';

                    if (in_array(Request::segment(1), $subLinks)) {
                        $active   = 'active';
                        $show     = 'show';
                    }

                    $id_class_replace = str_replace(" ", "", $menu->menu_name);
                    $id_class = substr($id_class_replace, 0, 5);
                    $menus     .= "<li class='nav-item sidebar-item dropdown" . $active . "'>
                                    <a href='#" . $id_class . " 'data-toggle='collapse' aria-expanded='false' class='dropdown-toggle nav-link sidebar-link'>
                                        <i data-feather='" . $menu->menu_icon . "'></i> <span class='ml-3 item-text'>" . $menu->menu_name . "</span>
                                    </a>
                                    <ul id='" . $id_class . "' class='collapse list-unstyled pl-4 w-100" . $show . "' >
                                        " . $subs . "
                                    </ul>
                                </li>";
                } else {
                    // Check Role
                    $getRole = $_sysmenuRepository->getRole($menu->module_id, Auth::user()->group_id);

                    if (!$getRole) {
                        continue;
                    }

                    $active = '';

                    if (Request::segment(1) == $menu->menu_url) {
                        $active = 'active';
                    }

                    $menus     .= "
                        <li class='nav-item w-100 sidebar-item " . $active . "'>
                            <a class='nav-link sidebar-link' href='" . url($menu->menu_url) . "' aria-expanded='false'>
                            <i data-feather='" . $menu->menu_icon . "' ></i><span class='ml-3 item-text'>" . $menu->menu_name . "</span>
                            </a>
                        </li>
                    ";
                }
            }
        }

        return $menus;
    }
}

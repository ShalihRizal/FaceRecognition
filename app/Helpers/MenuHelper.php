<?php

/** 
 * Menu Helper
 * 
 * Updated 31 Agustus 2021, 09:40
 *
 * @author Robby Al Jufri
 *
 */

namespace App\Helpers;

use Request;
use Illuminate\Support\Facades\Auth;
use Modules\SysMenu\Repositories\SysMenuRepository;

class MenuHelper
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

                        $subs .= "<li class='sidebar-item " . $active . "'><a class='sidebar-link' href='" . url($sub->menu_url) . "'>" . $sub->menu_name . "</a></li>";

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
                    $menus     .= "<li class='sidebar-item " . $active . "'>
                                    <a href='#" . $id_class . "' data-toggle='collapse' class='sidebar-link collapsed'>
                                        <i class='align-middle' data-feather='" . $menu->menu_icon . "'></i> <span class='align-middle'>" . $menu->menu_name . "</span>
                                    </a>
                                    <ul id='" . $id_class . "' class='sidebar-dropdown list-unstyled collapse " . $show . "' data-parent='#sidebar'>
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
                        <li class='sidebar-item " . $active . "'>
                            <a class='sidebar-link' href='" . url($menu->menu_url) . "'>
                            <i class='align-middle' data-feather='" . $menu->menu_icon . "'></i> <span class='align-middle'>" . $menu->menu_name . "</span>
                            </a>
                        </li>
                    ";
                }
            }
        }

        return $menus;
    }
}

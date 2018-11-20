<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use App\Http\Requests;


use DB;
use App\MenuItems;
class MenuItemController extends Controller
{
    //Add new menu item
    public function createMenuItem(Request $req) {
    	$reqData = $req->toArray();
    	
    	$respArr = array();
    	
    	$menuItem  = new MenuItems();
    	$menuItem->menu_item = $reqData['menu_item'];
    	$created = $menuItem->save();
    	
    	if($created == true){
    		$respArr['responseCode'] = "200";
    		$respArr['responseMessage'] = "New item created!";
    	} else {
    		$respArr['respCode'] = "400";
    		$respArr['respMSG'] = "Failed to create new item!";
    	}
    	
    	return json_encode($respArr);
    }
    

    
}

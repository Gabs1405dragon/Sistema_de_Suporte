<?php 
namespace Views;

class MainView{
	public static function render($arr = [],$fileName,$var = false,$header = 'header.php',$footer = 'footer.php'){
		include('pages/includes/'.$header);
		include('pages/'.$fileName.'.php');
		include('pages/includes/'.$footer);
	}
}
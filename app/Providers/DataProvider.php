<?php 


namespace App\Providers;
use App\selectPopulate;

use Illuminate\Support\ServiceProvider;

class DataProvider extends ServiceProvider{
	public function boot(){
		view()->composer('*',function($view){
			$view->with('arrayname',selectPopulate::all());
		});
	}
}
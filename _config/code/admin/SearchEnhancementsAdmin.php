<?php


class SearchEnhancementsAdmin extends ModelAdmin
{
	private static $menu_title = 'Search';
	private static $url_segment = 'search';
	private static $managed_models = [
		'PopularQuery'
	];
}

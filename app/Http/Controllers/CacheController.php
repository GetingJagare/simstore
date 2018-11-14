<?php
/**
 * Created by PhpStorm.
 * User: wanderer
 * Date: 14.11.18
 * Time: 22:47
 */

namespace App\Http\Controllers;

use App\Region;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Request;

class CacheController extends Controller {

	private $cacheItems = [
		'tariffs' => [
			'name' => 'Тарифы',
			'items' => ['tariffs_on_sale']
		],
		'numbers' => [
			'name' => 'Номера',
			'items' => ['numbers_on_sale_{region_id}', 'numbers_max_price', 'region_{region_id}_c']
		]
	];

	public function index(Request $request)
	{
		$items = $request->items;
		$regions = Region::all();

		try {
			/** @var Region $region */
			foreach ($regions as $region) {
				foreach ($items as $item) {
					foreach ($this->cacheItems[$item]['items'] as $cacheItem) {
						$modifiedCacheItem = str_replace("{region_id}", $region->id, $cacheItem);
						if (Cache::has($modifiedCacheItem)) {
							Cache::forget($modifiedCacheItem);
						}
					}
				}
			}

			return json_encode(['success' => 1, 'message' => 'Кэш очищен']);
		} catch (\Exception $e) {
			return json_encode(['success' => 0, 'message' => $e->getMessage()]);
		}
	}

	public function getItems()
	{
		$items = [];
		foreach ($this->cacheItems as $itemType => $cacheItem) {
			$items[] = [
				'text' => $cacheItem['name'],
				'value' => $itemType
			];
		}
		return json_encode($items);
	}
}
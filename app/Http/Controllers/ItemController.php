<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\ChecklistAttributes;
use App\ItemAttributes;
use App\Http\Resources\ItemAttributesResource;
use App\Http\Resources\ChecklistRelatedItemResource;
use App\Http\Resources\CompletedItemResource;
use App\Http\Resources\ChecklistRelatedItemBulkOpResource;
use App\Http\Resources\ChecklistRelatedItemSummaryResource;
use App\Http\Resources\RawItemResource;

class ItemController extends Controller
{
	public function createChecklistItem(Request $request, $checklistId)
	{
		$arr = $request->json()->all();
		$attr = $arr['data']['attribute'];

		if (isset($attr['due'])) {
			$attr['due'] = (new \DateTime($attr['due']))
				->format('Y-m-d h:i:s');
		}

		$checklist = ChecklistAttributes::find($checklistId);
		$item = new ItemAttributes($attr);

		$checklist->items()->save($item);

		$item = $checklist
			->items()
			->latest()
			->first();

		$obj = new ItemAttributesResource($item);

		return response($obj, 200);
	}

	public function getDataById(Request $request, $checklistId, $itemId)
	{
		$checklist = ChecklistAttributes::find($checklistId);
		$item = $checklist->items()->find($itemId);

		$obj = new ItemAttributesResource($item);

		return response($obj, 200);
	}

	public function getAllRelatedItems(Request $request, $checklistId)
	{
		$checklist = ChecklistAttributes::with('items')
			->find($checklistId);
		$obj = new ChecklistRelatedItemResource(
			$checklist
		);

		return $obj;
	}

	public function createCompleteItems(Request $request)
	{
		$arr = $request->json()->all();
		$data = $arr['data'];
		$res = [];

		foreach ($data as $value) {
			try {
				$item = ItemAttributes::with('checklist')
					->findOrFail($value['item_id']);
				$item->is_completed = true;
				$item->save();

				// assuming action above is done,
				// append that modified item
				// into an array
				$res[] = $item;
			} catch (ModelNotFoundException $e) { /* put the damn exception logic here.. */ }
		}

		$obj = CompletedItemResource::collection(
			collect($res)
		);

		return $obj;
	}

	public function createIncompleteItems(Request $request)
	{
		$arr = $request->json()->all();
		$data = $arr['data'];
		$res = [];

		foreach ($data as $value) {
			try {
				$item = ItemAttributes::with('checklist')
					->findOrFail($value['item_id']);
				$item->is_completed = false;
				$item->save();

				// assuming action above is done,
				// append that modified item
				// into an array
				$res[] = $item;
			} catch (ModelNotFoundException $e) { /* put the damn exception logic here.. */ }
		}

		$obj = CompletedItemResource::collection(
			collect($res)
		);

		return $obj;
	}

	public function updatePartialData(Request $request, $checklistId, $itemId)
	{
		$arr = $request->json()->all();
		$data = $arr['data']['attribute'];

		$checklist = ChecklistAttributes::find($checklistId);
		$item = $checklist->items()->find($itemId);

		foreach ($data as $key => $value) {
			$item->{$key} = $value;
		}

		$item->save();

		$obj = new ItemAttributesResource(
			$item
		);

		return $obj;
	}

	public function removeDataById(Request $request, $checklistId, $itemId)
	{
		$checklist = ChecklistAttributes::find($checklistId);
		$item = $checklist->items()->find($itemId);

		$item->delete();

		return response('', 200);
	}

	public function updateBulkChecklistRelatedItem(Request $request, $checklistId)
	{
		$arr = $request->json()->all();
		$data = $arr['data'];
		$res = [];

		foreach ($data as $key => $value) {
			$id = $value['id'];
			$payload = $value['attributes'];

			try {
				$item = ItemAttributes::with('checklist')
					->findOrFail($id);

				foreach ($payload as $x => $y) {
					$item->{$x} = $y;
				}

				$item->save();

				// per item bulk operation
				// object
				$obj = new \stdClass;
				$obj->id = $id;
				$obj->action = $value['action'];
				$obj->status = 200;

				// append our bulk operation
				// object
				$res[] = $obj;
			} catch (ModelNotFoundException $e) {
				// per item bulk operation
				// object
				$obj = new \stdClass;
				$obj->id = $id;
				$obj->action = $value['action'];
				$obj->status = 404;

				// append our bulk operation
				// object
				$res[] = $obj;
			}
		}

		$obj = ChecklistRelatedItemBulkOpResource::collection(
			collect($res)
		);

		return $obj;
	}

	public function getSummaryOfChecklistRelatedItem(Request $request)
	{
		$this->validate(
			$request,
			[
				'date' => 'required|iso_date:utc'
			]
		);

		$items = ItemAttributes::with('checklist')
			->get();

		$summary = [
			'today' => 0,
			'past_due' => 0,
			'this_week' => 0,
			'past_week' => 0,
			'this_month' => 0,
			'past_month' => 0,
			'total' => \count($items)
		];

		foreach ($items as $value) {
			$pDate = new \DateTime($request->input('date'));
			$pCurr = new \DateTime($value->created_at);
			$pDue = new \DateTime($value->due);
			$pNow = new \DateTime('now');

			// is it today?
			$summary['today'] += \date_format($pDate, 'Y-m-d') === \date_format($pCurr, 'Y-m-d')
				? 1
				: 0;

			// is it in past due
			$summary['past_due'] += $pDate->getTimestamp() > $pDue->getTimestamp()
				? 1
				: 0;

			// is it in this week?
			$summary['this_week'] += \date_format($pCurr, 'j') === \date_format($pNow, 'j')
				? 1
				: 0;

			// is it in past week?
			$summary['past_week'] += intval(\date_format($pCurr, 'j')) === (intval(\date_format($pNow, 'j')) - 7)
				? 1
				: 0;

			// is it in this month?
			$summary['this_month'] += \date_format($pCurr, 'n') === \date_format($pNow, 'n')
				? 1
				: 0;

			// is it in past month?
			$summary['past_month'] += intval(\date_format($pCurr, 'n')) - intval(\date_format($pNow, 'n')) === 1
				? 1
				: 0;
		}

		$obj = new ChecklistRelatedItemSummaryResource($summary);

		return $obj;
	}

	public function getAllChecklistRelatedItems(Request $request)
	{
		$items = ItemAttributes::with('checklist')
			->get();

		return RawItemResource::collection(
			ItemAttributes::with('checklist')->paginate()
		);
	}
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\ChecklistAttributes;
use App\Http\Resources\ChecklistAttributesResource;

class ChecklistController extends Controller
{
	public function createData(Request $request)
	{
		$arr = $request->json()->all();

		$attr = $arr['data']['attributes'];

		if (isset($attr['due'])) {
			$attr['due'] = (new \DateTime($attr['due']))
				->format("Y-m-d h:i:s");
		}

		ChecklistAttributes::create($attr);

		$checklist = ChecklistAttributes::latest()
			->first();

		$obj = new ChecklistAttributesResource(
			ChecklistAttributes::find(
				$checklist->checklist_attr_id
			)
		);

		return response($obj, 201);
	}

	public function getDataById(Request $request, $checklistId)
	{
		try {
			$checklist = ChecklistAttributes::findOrFail($checklistId);
			return response(
				new ChecklistAttributesResource($checklist),
				200
			);
		} catch (ModelNotFoundException $e) {
			return response()->json([
				'status' => 404,
				'error' => $e->getMessage()
			], 404);
		}
	}

	public function getAll(Request $request)
	{
		$obj = ChecklistAttributesResource::collection(
			ChecklistAttributes::paginate()
		);

		return $obj;
	}

	public function updatePartialById(Request $request, $checklistId)
	{
		try {
			$checklist = ChecklistAttributes::findOrFail($checklistId);

			$arr = $request->json()->all();
			$attr = $arr['data']['attributes'];

			if (isset($attr['due'])) {
				$attr['due'] = (new \DateTime($attr['due']))
					->format("Y-m-d h:i:s");
			}

			$checklist->update($attr);

			return response(
				new ChecklistAttributesResource($checklist),
				200
			);
		} catch (ModelNotFoundException $e) {
			return response()
				->json([
					'status' => 404,
					'error' => $e->getMessage()
				], 404);
		}
	}

	public function removeById(Request $request, $checklistId)
	{
		try {
			$checklist = ChecklistAttributes::findOrFail($checklistId);
			$checklist->delete();
			return response('', 204);
		} catch (ModelNotFoundException $e) {
			return response()
				->json([
					'status' => 404,
					'error' => $e->getMessage()
				], 404);
		}
	}
}

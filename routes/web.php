<?php

$router->group(['prefix' => 'api/v1', 'middleware' => 'auth'], function() use ($router) {
	// checklist related endpoint..
	$router->post('/checklist', [
		'as' => 'create_checklist_data',
		'uses' => 'ChecklistController@createData'
	]);

	$router->get('/checklist', [
		'as' => 'get_all_checklist_data',
		'uses' => 'ChecklistController@getAll'
	]);

	$router->get('/checklist/{checklistId}', [
		'as' => 'get_checklist_data_by_id',
		'uses' => 'ChecklistController@getDataById'
	]);

	$router->patch('/checklist/{checklistId}', [
		'as' => 'patch_checklist_data_by_id',
		'uses' => 'ChecklistController@updatePartialById'
	]);

	$router->delete('/checklist/{checklistId}', [
		'as' => 'remove_checklist_data_by_id',
		'uses' => 'ChecklistController@removeById'
	]);

	// item related endpoint..
	$router->post('/checklist/{checklistId}/items', [
		'as' => 'create_checklist_item',
		'uses' => 'ItemController@createChecklistItem'
	]);

	$router->get('/checklist/{checklistId}/items/{itemId}', [
		'as' => 'get_checklisted_item_by_id',
		'uses' => 'ItemController@getDataById'
	]);

	$router->get('/checklist/{checklistId}/items', [
		'as' => 'get_checklisted_items',
		'uses' => 'ItemController@getAllRelatedItems'
	]);

	$router->post('/checklists/complete', [
		'as' => 'create_complete_items_related_to_checklist',
		'uses' => 'ItemController@createCompleteItems'
	]);

	$router->post('/checklists/incomplete', [
		'as' => 'create_incomplete_items_related_to_checklist',
		'uses' => 'ItemController@createIncompleteItems'
	]);

	$router->patch('/checklists/{checklistId}/items/{itemId}', [
		'as' => 'update_data_checklist_related_item_partially',
		'uses' => 'ItemController@updatePartialData'
	]);

	$router->delete('/checklists/{checklistId}/items/{itemId}', [
		'as' => 'remove_data_checklist_related_item',
		'uses' => 'ItemController@removeDataById'
	]);

	$router->post('/checklists/{checklistId}/items/_bulk', [
		'as' => 'bulk_update_checklist_related_item',
		'uses' => 'ItemController@updateBulkChecklistRelatedItem'
	]);

	$router->get('/checklists/items/summaries', [
		'as' => 'get_summaries_of_checklist_related_items',
		'uses' => 'ItemController@getSummaryOfChecklistRelatedItem'
	]);

	$router->get('/checklists/items', [
		'as' => 'get_all_checklist_related_items',
		'uses' => 'ItemController@getAllChecklistRelatedItems'
	]);
});

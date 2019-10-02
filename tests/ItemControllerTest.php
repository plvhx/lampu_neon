<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ItemControllerTest extends TestCase
{
	use DatabaseMigrations;

	public function testTryToCreateDataWithNoToken()
	{
		$this->json('POST', '/api/v1/checklist/1/items', ['foo' => 1, 'bar' => 2]);
		$this->assertEquals(401, $this->response->status());
	}

	public function testTryToCreateDataWithInvalidToken()
	{
		$this->json('POST', '/api/v1/checklist/1/items', ['foo' => 'bar'], ['apiKey' => 'invalid_token']);
		$this->assertEquals(401, $this->response->status());
	}

	public function testCanCreateDataByExampleSchema()
	{
		// create checklist data
		$this->json('POST', '/api/v1/checklist', [
			'data' => [
				'attributes' => [
					'object_domain' => 'contact',
					'object_id' => '1',
					'due' => '2019-01-25T07:50:14+00:00',
					'urgency' => 1,
					'description' => 'Need to verify this guy house.',
					'items' => [
						'Visit his house.',
						'Capture a photo',
						'Meet him on the house'
					],
					'task_id' => '123'
				]
			]
		],
		[
			'apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
		]);

		$this->assertEquals(201, $this->response->status());

		// create item data related to checklist..
		$this->json(
			'POST',
			'/api/v1/checklist/1/items',
			[
				'data' => [
					'attribute' => [
						"description" => "Need to verify this guy house.",
						"due" => "2019-01-19 18:34:51",
						"urgency" => "2",
						"assignee_id" => 123
					]
				]
			],
			[
				'apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $this->response->status());
	}

	public function testTryToGetDataByIDWithNoToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/1/items/1'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetDataByIDWithInvalidToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/1/items/1',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testCanGetDataByID()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'GET',
			'/api/v1/checklist/1/items/1',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $response->status());
	}

	public function testTryToGetRelatedItemsWithNoToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/1/items'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetRelatedItemsWithInvalidToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/1/items',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testCanGetRelatedItems()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'GET',
			'/api/v1/checklist/1/items',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $response->status());
	}

	public function testTryToCreateCompletedRelatedItemsWithNoToken()
	{
		$this->json(
			'POST',
			'/api/v1/checklists/complete',
			[
				'foo' => 'bar'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testTryToCreateCompletedRelatedItemsWithInvalidToken()
	{
		$this->json(
			'POST',
			'/api/v1/checklists/complete',
			[
				'foo' => 'bar'
			],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testCanCreateCompletedRelatedItems()
	{
		$this->testCanCreateDataByExampleSchema();

		$this->json(
			'POST',
			'/api/v1/checklists/complete',
			[
				'data' => [
					['item_id' => 1],
					['item_id' => 2],
					['item_id' => 3],
					['item_id' => 4]
				]
			],
			[
				'apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $this->response->status());
	}

	public function testTryToCreateIncompleteRelatedItemsWithNoToken()
	{
		$this->json(
			'POST',
			'/api/v1/checklists/incomplete',
			[
				'foo' => 'bar'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testTryToCreateIncompleteRelatedItemsWithInvalidToken()
	{
		$this->json(
			'POST',
			'/api/v1/checklists/incomplete',
			[
				'foo' => 'bar'
			],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testCanCreateIncompleteRelatedItems()
	{
		$this->testCanCreateDataByExampleSchema();

		$this->json(
			'POST',
			'/api/v1/checklists/incomplete',
			[
				'data' => [
					['item_id' => 1],
					['item_id' => 2],
					['item_id' => 3],
					['item_id' => 4]
				]
			],
			[
				'apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $this->response->status());
	}

	public function testTryToPartiallyUpdateRelatedItemsWithNoToken()
	{
		$this->json(
			'PATCH',
			'/api/v1/checklists/1/items/1',
			[
				'foo' => 'bar'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testTryToPartiallyUpdateRelatedItemsWithInvalidToken()
	{
		$this->json(
			'PATCH',
			'/api/v1/checklists/1/items/1',
			[
				'foo' => 'bar'
			],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testCanPartiallyUpdateRelatedItems()
	{
		$this->testCanCreateDataByExampleSchema();

		$this->json(
			'PATCH',
			'/api/v1/checklists/1/items/1',
			[
				'data' => [
					'attribute' => [
						'description' => 'patchwork time.. :))',
						'due' => "2019-01-19 18:34:51",
						"urgency" => "2",
						"assignee_id" => 123
					]
				]
			],
			[
				'apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $this->response->status());
	}

	public function testTryToRemoveRelatedItemWithNoToken()
	{
		$response = $this->call(
			'DELETE',
			'/api/v1/checklists/1/items/1'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToRemoveRelatedItemWithInvalidToken()
	{
		$response = $this->call(
			'DELETE',
			'/api/v1/checklists/1/items/1',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testCanRemoveRelatedItem()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'DELETE',
			'/api/v1/checklists/1/items/1',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4'
			]
		);

		$this->assertEquals(200, $response->status());
	}

	public function testTryToBulkUpdateRelatedItemsWithNoToken()
	{
		$this->json(
			'POST',
			'/api/v1/checklists/1/items/_bulk',
			[
				'foo' => 'bar'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testTryToBulkUpdateRelatedItemsWithInvalidToken()
	{
		$this->json(
			'POST',
			'/api/v1/checklists/1/items/_bulk',
			[
				'foo' => 'bar'
			],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $this->response->status());
	}

	public function testCanBulkUpdateRelatedItems()
	{
		$this->testCanCreateDataByExampleSchema();

		$this->json(
			'POST',
			'/api/v1/checklists/1/items/_bulk',
			[
				'data' => [
					[
						'id' => 1,
						'action' => 'update',
						'attributes' => [
							'description' => '',
							'due' => "2019-01-19 18:34:51",
							'urgency' => '2'
						]
					],
					[
						'id' => 2,
						'action' => 'update',
						'attributes' => [
							'description' => '{{data.attributes.description}}',
							'due' => '2019-01-19 18:34:51',
							'urgency' => '2'
						]
					]
				]
			],
			[
				'apiKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4'
			]
		);

		$this->assertEquals(200, $this->response->status());
	}

	public function testTryToGetRelatedItemSummaryWithNoToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklists/items/summaries'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetRelatedItemSummaryWithInvalidToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklists/items/summaries',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testCanGetRelatedItemSummary()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'GET',
			'/api/v1/checklists/items/summaries',
			[
				'date' => '2008-09-15T15:53:00+05:00'
			],
			[],
			[],
			[
				'HTTP_apiKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4'
			]
		);

		$this->assertEquals(200, $response->status());
	}

	public function testTryToGetAllRelatedItemsWithNoToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklists/items'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetAllRelatedItemsWithInvalidToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklists/items',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testCanGetAllRelatedItems()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'GET',
			'/api/v1/checklists/items',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4'
			]
		);

		$this->assertEquals(200, $response->status());
	}
}

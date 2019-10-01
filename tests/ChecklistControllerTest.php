<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ChecklistControllerTest extends TestCase
{
	use DatabaseMigrations;

	public function testTryToCreateDataWithNoToken()
	{
		$this->json('POST', '/api/v1/checklist', ['foo' => 'bar']);
		$this->assertEquals(401, $this->response->status());
	}

	public function testTryToCreateDataWithInvalidToken()
	{
		$this->json('POST', '/api/v1/checklist', ['foo' => 'bar'], ['apiKey' => 'invalid_token']);
		$this->assertEquals(401, $this->response->status());
	}

	public function testCanCreateDataByExampleSchema()
	{
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
		], ['apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"])
		->seeJson([
			'type' => 'checklists',
			'id' => 1
		]);
	}

	public function testTryToGetDataByIDWithNoToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/100'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetDataByIDWithInvalidToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/100',
			[],
			[],
			[],
			['apiKey' => 'invalid_token']
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetDataByIDWithNonexistingID()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist/100',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(404, $response->status());
	}

	public function testCanGetDataByID()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'GET',
			'/api/v1/checklist/1',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $response->status());
	}

	public function testTryToGetAllDataWithNoToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToGetAllDataWithInvalidToken()
	{
		$response = $this->call(
			'GET',
			'/api/v1/checklist',
			[],
			[],
			[],
			['apiKey' => 'invalid_token']
		);

		$this->assertEquals(401, $response->status());
	}

	public function testCanGetAllData()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'GET',
			'/api/v1/checklist',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $response->status());
	}

	public function testTryToPartiallyUpdateDataWithNoToken()
	{
		$response = $this->call(
			'PATCH',
			'/api/v1/checklist/100'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToPartiallyUpdateDataWithInvalidToken()
	{
		$response = $this->call(
			'PATCH',
			'/api/v1/checklist/100',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToPartiallyUpdateDataWithInvalidID()
	{
		$response = $this->call(
			'PATCH',
			'/api/v1/checklist/100',
			['foo' => 1, 'bar' => 2],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(404, $response->status());
	}

	public function testCanPartiallyUpdateDataByID()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->json(
			'PATCH',
			'/api/v1/checklist/1',
			[
				'data' => [
					'type' => 'checklists',
					'id' => 1,
					'attributes' => [
						'object_domain' => 'contact',
						'object_id' => '1',
						'description' => "this is absurd, we can't do it within two nights.",
						'is_completed' => false
					],
					'links' => [
						'self' => 'http://localhost:8000/api/v1/checklist/1'
					]
				]
			],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(200, $this->response->status());
	}

	public function testTryToRemoveDataByIdWithNoToken()
	{
		$response = $this->call(
			'DELETE',
			'/api/v1/checklist/100'
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToRemoveDataByIdWithInvalidToken()
	{
		$response = $this->call(
			'DELETE',
			'/api/v1/checklist/100',
			[],
			[],
			[],
			[
				'apiKey' => 'invalid_token'
			]
		);

		$this->assertEquals(401, $response->status());
	}

	public function testTryToRemoveDataByIDWithInvalidID()
	{
		$response = $this->call(
			'DELETE',
			'/api/v1/checklist/100',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(404, $response->status());
	}

	public function testCanRemoveDataByID()
	{
		$this->testCanCreateDataByExampleSchema();

		$response = $this->call(
			'DELETE',
			'/api/v1/checklist/1',
			[],
			[],
			[],
			[
				'HTTP_apiKey' => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCIsImN0eSI6ImFwcGxpY2F0aW9uL2pzb24ifQ.eyJpc3MiOiJtZSIsImV4cCI6MTU2OTk2MDA4MCwiY3JlZGVudGlhbHMiOnsidXNlcm5hbWUiOiJwbHZoeCIsInBhc3N3b3JkIjoidGhpc19pc19tZV93aG9fd2FudF90b19nZXRfaW4ifX0.1ry1xbnDuq_Lssj-LzmEl8KXTvPqazEC2HOtvwb1us4"
			]
		);

		$this->assertEquals(204, $response->status());
	}
}

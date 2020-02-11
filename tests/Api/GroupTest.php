<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Group;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class GroupControllerTest extends TestCase
{
    use WithFaker;

    public function testGroupCreated()
    {
        $expected = [
            'group_name' => 'Sally Group',
        ];
        $response = $this->json('POST', '/api/groups', $expected);
        $response
            ->assertStatus(201)
            ->assertJsonFragment($expected);

    }

    public function testGroupRetrieved()
    {
        $group = factory('App\Models\Group')->create();

        $response = $this->json('GET', '/api/groups/' . $group->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'group_name',
                ]
            ]);
    }

    public function testAllGroupsRetrieved()
    {
        $person = factory('App\Models\Group', 25)->create();

        $response = $this->json('GET', '/api/groups');
        $response
            ->assertStatus(200)
            ->assertJsonCount(25, 'data');
    }

    public function testNoGroupRetrieved()
    {
        $group = factory('App\Models\Group')->create();
        Group::destroy($group->id);

        $response = $this->json('GET', '/api/groups/' . $group->id);
        $response->assertStatus(404);
    }

    public function testGroupUpdated()
    {
        $group = factory('App\Models\Group')->create();

        $updatedGroupName = $this->faker->firstName() . ' Group';

        $response = $this->json('PUT', '/api/groups/' . $group->id, [
            'group_name' => $updatedGroupName
        ]);
        $response->assertStatus(204);

        $updatedPerson = Group::find($group->id);
        $this->assertDatabaseHas('groups', [
            'id'    => $group->id,
            'group_name' => $updatedGroupName
        ]);
    }

    public function testGroupDeleted()
    {
        $group = factory('App\Models\Group')->create();

        $deleteResponse = $this->json('DELETE', '/api/groups/' . $group->id);
        $deleteResponse->assertStatus(204);

        $response = $this->json('GET', '/api/groups/' . $group->id);
        $response->assertStatus(404);

    }

    public function testBulkUpload() {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/group.csv', $testData->generateCSV($testData::groupCSVData()));

        $file = storage_path('app/testcsv/group.csv');

        $testFile = new UploadedFile ($file, 'group.csv', 'text/csv', 0, true);


        $response = $this->json('POST', '/api/groups/bulk-upload', [
            'file' => $testFile,
        ]);

        $csvElement = collect($testData->parseCSV($file))->random();

        $this->assertDatabaseHas('groups', [
            'id'            => $csvElement['id'],
            'group_name'    => $csvElement['group_name'],
        ]);

        $response->assertStatus(201);
    }

    public function testBulkUploadDiffColumnOrder() {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/group.csv', $testData->generateCSV($testData::groupCSVData2()));

        $file = storage_path('app/testcsv/group.csv');

        $testFile = new UploadedFile ($file, 'group.csv', 'text/csv', 0, true);


        $response = $this->json('POST', '/api/groups/bulk-upload', [
            'file' => $testFile,
        ]);

        $csvElement = collect($testData->parseCSV($file))->random();

        $this->assertDatabaseHas('groups', [
            'id'            => $csvElement['id'],
            'group_name'    => $csvElement['group_name'],
        ]);

        $response->assertStatus(201);
    }

    public function testBulkUploadValidationFailed() {
        $response = $this->json('POST', '/api/groups/bulk-upload', [
            'file' => UploadedFile::fake()->image('test.jpg'),
        ]);
        $response->assertStatus(422);
    }

    public function testBulkUploadRequiredColumnsNotInCSV() {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/group.csv', $testData->generateCSV($testData::peopleCSVData()));

        $file = storage_path('app/testcsv/group.csv');

        $testFile = new UploadedFile ($file, 'group.csv', 'text/csv', 0, true);


        $response = $this->json('POST', '/api/groups/bulk-upload', [
            'file' => $testFile,
        ]);

        $response->assertStatus(400);
    }


}

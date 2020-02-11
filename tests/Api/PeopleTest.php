<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\Person;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class PeopleControllerTest extends TestCase
{
    use WithFaker;

    public function testPersonCreated()
    {
        $expected = [
            'first_name' => 'Sally',
            'last_name' => 'Ride',
            'email_address' => 'sallyride@nasa.gov',
            'status' => 'archived'
        ];
        $response = $this->json('POST', '/api/people', $expected);
        $response
            ->assertStatus(201)
            ->assertJsonFragment($expected);

    }

    public function testPersonRetrieved()
    {
        $person = factory('App\Models\Person')->create();

        $response = $this->json('GET', '/api/people/' . $person->id);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'first_name',
                    'last_name',
                    'email_address',
                    'status',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testAllPeopleRetrieved()
    {
        $person = factory('App\Models\Person', 25)->create();

        $response = $this->json('GET', '/api/people');
        $response
            ->assertStatus(200)
            ->assertJsonCount(25, 'data');
    }

    public function testNoPersonRetrieved()
    {
        $person = factory('App\Models\Person')->create();
        Person::destroy($person->id);

        $response = $this->json('GET', '/api/people/' . $person->id);
        $response->assertStatus(404);
    }

    public function testPersonUpdated()
    {
        $person = factory('App\Models\Person')->create();

        $updatedFirstName = $this->faker->firstName();
        $response = $this->json('PUT', '/api/people/' . $person->id, [
            'first_name' => $updatedFirstName
        ]);
        $response->assertStatus(204);

        $updatedPerson = Person::find($person->id);
        $this->assertEquals($updatedFirstName, $updatedPerson->first_name);
    }

    public function testPersonDeleted()
    {
        $person = factory('App\Models\Person')->create();

        $deleteResponse = $this->json('DELETE', '/api/people/' . $person->id);
        $deleteResponse->assertStatus(204);

        $response = $this->json('GET', '/api/people/' . $person->id);
        $response->assertStatus(404);

    }

    public function testBulkUpload() {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/people.csv', $testData->generateCSV($testData::peopleCSVData()));

        $file = storage_path('app/testcsv/people.csv');

        $testFile = new UploadedFile ($file, 'people.csv', 'text/csv', 0, true);


        $response = $this->json('POST', '/api/people/bulk-upload', [
            'file' => $testFile,
        ]);

        $csvElement = collect($testData->parseCSV($file))->random();

        $this->assertDatabaseHas('people', [
            'email_address' => $csvElement['email_address'],
            'first_name' => $csvElement['first_name'],
            'last_name' => $csvElement['last_name'],
        ]);

        $response->assertStatus(201);
    }

    public function testBulkUploadDiffColumnOrder() {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/people.csv', $testData->generateCSV($testData::peopleCSVData2()));

        $file = storage_path('app/testcsv/people.csv');

        $testFile = new UploadedFile ($file, 'people.csv', 'text/csv', 0, true);


        $response = $this->json('POST', '/api/people/bulk-upload', [
            'file' => $testFile,
        ]);

        $csvElement = collect($testData->parseCSV($file))->random();

        $this->assertDatabaseHas('people', [
            'email_address' => $csvElement['email_address'],
            'first_name' => $csvElement['first_name'],
            'last_name' => $csvElement['last_name'],
        ]);

        $response->assertStatus(201);
    }

    public function testBulkUploadValidationFailed() {
        $response = $this->json('POST', '/api/people/bulk-upload', [
            'file' => UploadedFile::fake()->image('test.jpg'),
        ]);
        $response->assertStatus(422);
    }

    public function testBulkUploadRequiredColumnsNotInCSV() {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/people.csv', $testData->generateCSV($testData::groupCSVData()));

        $file = storage_path('app/testcsv/people.csv');

        $testFile = new UploadedFile ($file, 'people.csv', 'text/csv', 0, true);


        $response = $this->json('POST', '/api/people/bulk-upload', [
            'file' => $testFile,
        ]);

        $response->assertStatus(400);
    }


}

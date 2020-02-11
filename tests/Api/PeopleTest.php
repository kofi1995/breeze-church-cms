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
        $this->bulkUploadResponse($this->generateCSVRequestFile(TestData::peopleCSVData()))->assertStatus(201);
        $testData = new TestData;

        $file = storage_path('app/testcsv/people.csv');


        $csvElement = collect($testData->parseCSV($file))->random();

        $this->assertDatabaseHas('people', [
            'email_address' => $csvElement['email_address'],
            'first_name' => $csvElement['first_name'],
            'last_name' => $csvElement['last_name'],
        ]);
    }

    public function testBulkUploadDiffColumnOrder() {
        $this->bulkUploadResponse($this->generateCSVRequestFile(TestData::peopleCSVData2()))->assertStatus(201);
        $testData = new TestData;

        $file = storage_path('app/testcsv/people.csv');


        $csvElement = collect($testData->parseCSV($file))->random();

        $this->assertDatabaseHas('people', [
            'email_address' => $csvElement['email_address'],
            'first_name' => $csvElement['first_name'],
            'last_name' => $csvElement['last_name'],
        ]);
    }

    public function testBulkUploadValidationFailed() {
        $this->bulkUploadResponse(UploadedFile::fake()->image('test.jpg'))->assertStatus(422);
    }

    public function testBulkUploadRequiredColumnsNotInCSV() {
        $this->bulkUploadResponse($this->generateCSVRequestFile(TestData::groupCSVData()))->assertStatus(400);
    }

    public function testBulkUploadWontUploadBadRows() {
        $this->bulkUploadResponse($this->generateCSVRequestFile(TestData::peopleCSVData3()))->assertStatus(201);
        $this->assertDatabaseHas('people', [
            'email_address' => 'mbourne@example.com',
            'first_name' => 'Marie',
            'last_name' => 'Bourne',
        ]);
        $this->assertDatabaseMissing('people', [
            'email_address' => 'alex@breezechms.com',
            'first_name' => 'Alex',
            'last_name' => 'Ortiz-Rosado',
        ]);
    }

    private function generateCSVRequestFile(array $array) {
        Storage::fake('testcsv');

        $testData = new TestData;

        Storage::put('testcsv/people.csv', $testData->generateCSV($array));

        $file = storage_path('app/testcsv/people.csv');

        return new UploadedFile ($file, 'people.csv', 'text/csv', 0, true);
    }

    private function bulkUploadResponse($file) {
        return $this->json('POST', '/api/people/bulk-upload', [
            'file' => $file,
        ]);
    }


}

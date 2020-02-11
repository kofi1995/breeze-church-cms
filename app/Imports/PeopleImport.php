<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Person;
use App\Exceptions\UploadValidationFailedException;

class PeopleImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     * @throws UploadValidationFailedException
     */
    public function model(array $row)
    {
        $keys = ['id', 'first_name', 'last_name', 'email_address', 'status'];

        if(!$this->validateHeaderExists($keys, $row)) {
            throw new UploadValidationFailedException(implode(",", $keys) . ' keys do not exist in the uploaded CSV file');
        }

        return Person::updateOrCreate(
            ['id' => $row['id']],
            [
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'email_address' => $row['email_address'],
                'status' => $row['status'],
            ]
        );
    }

    private function validateHeaderExists(array $keys, array $array) {
        return count(array_intersect(array_keys($array), $keys)) == count($keys);
    }
}

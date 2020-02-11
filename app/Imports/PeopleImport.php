<?php

namespace App\Imports;

use App\Exceptions\UploadColumnsNotFoundException;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Person;
use App\Exceptions\UploadValidationFailedException;
use Validator;

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
            throw new UploadColumnsNotFoundException(implode(",", $keys) . ' keys do not exist in the uploaded CSV file');
        }

        $validator = Validator::make($row, [
            $keys[0]    => 'required|integer',
            $keys[1]    => 'required|string|max:255',
            $keys[2]    => 'required|string|max:255',
            $keys[3]    => 'required|email',
            $keys[4]    => ['required', Rule::in(['active', 'archived'])],
        ]);

        if (!$validator->fails()) {
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
    }

    private function validateHeaderExists(array $keys, array $array) {
        return count(array_intersect(array_keys($array), $keys)) == count($keys);
    }
}

<?php

namespace App\Imports;

use App\Exceptions\UploadValidationFailedException;
use App\Models\Group;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null|bool
     */
    public function model(array $row)
    {
        $keys = ['id', 'group_name'];

        if(!$this->validateHeaderExists($keys, $row)) {
            throw new UploadValidationFailedException(implode(",", $keys) . ' keys do not exist in the uploaded CSV file');
        }
        return Group::updateOrCreate(
            ['id' => $row['id']],
            [
                'group_name' => $row['group_name'],
            ]
        );
    }

    private function validateHeaderExists(array $keys, array $array) {
            return count(array_intersect(array_keys($array), $keys)) == count($keys);
    }
}

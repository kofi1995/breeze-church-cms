<?php


namespace Tests;


class TestData
{

    public function generateCSV(array $data, string $delimiter = ',', string $enclosure = '"') {
        $handle = fopen('php://temp', 'r+');
        foreach ($data as $line) {
            fputcsv($handle, $line, $delimiter, $enclosure);
        }
        rewind($handle);
        $contents = "";
        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        fclose($handle);
        return $contents;
    }

    public static function peopleCSVData () {
        return  [
            [ 'id', 'first_name', 'last_name', 'email_address', 'status'],
            [ 1, "Alex", "Ortiz-Rosado", "alex@breezechms.com", 'active'],
            [ 2, "Jon", "VerLee", "jon@breezechms.com", "archived"],
            [ 3, "Fred", "Flintstone", "fredflintstone@example.com", "active"],
            [ 4, "Marie", "Bourne", "mbourne@example.com", "active"],
            [ 5, "Wilma", "Flintstone", "wilmaflinstone@example.com", "active"],
        ];
    }

    public static function peopleCSVData2 () {
        return  [
            [ 'status', 'id', 'first_name', 'last_name', 'email_address', ],
            [ 'active', 1, "Alex", "Ortiz-Rosado", "alex@breezechms.com", ],
            [ "archived", 2, "Jon", "VerLee", "jon@breezechms.com"],
            [ "active", 3, "Fred", "Flintstone", "fredflintstone@example.com" ],
            [ "active", 4, "Marie", "Bourne", "mbourne@example.com"],
            [ "active", 5, "Wilma", "Flintstone", "wilmaflinstone@example.com"],
        ];
    }

    public static function groupCSVData () {
        $data =  [
            ['id', 'group_name'],
            [1, "Volunteers"],
            [2, "Elders"],
            [3, "Bible Study"],
        ];
        return $data;
    }

    public static function groupCSVData2 () {
        $data =  [
            ['group_name', 'id'],
            ["Volunteers", 1],
            ["Elders", 2],
            ["Bible Study", 3],
        ];
        return $data;
    }

    public function parseCSV(string $filename = '', string $delimiter=',')
    {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $header = NULL;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
            {
                if(!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

}

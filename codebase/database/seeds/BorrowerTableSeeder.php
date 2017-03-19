<?php

use Illuminate\Database\Seeder;
use App\Borrower;

class BorrowerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = $this->getData();
        foreach($records as $record)
        {
            $borrower = new Borrower;
            $borrower->card_id = $record[0];
            $borrower->ssn = preg_replace("/[^0-9]/", "", $record[1]);
            $borrower->bname = $record[2] . " " . $record[3];
            $borrower->email = $record[4];
            $borrower->address = $record[5];
            $borrower->city = $record[6];
            $borrower->state = $record[7];
            $borrower->phone = preg_replace("/[^0-9]/", "", $record[8]);
            $borrower->save();
        }
    }

    private function getData()
    {
        $records = array();
        $recordNo = 1;
        $filePath = storage_path('seed/borrowers.csv');
        $fileHandle = fopen($filePath, "r");
        if($fileHandle) {
            while(($row = fgetcsv($fileHandle)) !== false) {
                if($recordNo > 1) {
                    array_push($records, $row);
                }
                else {
                    $recordNo += 1;
                }
            }
        }
        fclose($fileHandle);
        return $records;
    }
}

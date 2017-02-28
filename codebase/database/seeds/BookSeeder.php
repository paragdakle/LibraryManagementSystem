<?php

use Illuminate\Support\Facades\DB as DB;
use Illuminate\Database\Seeder;
use App\Book;
use App\Author;

class BookSeeder extends Seeder
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
            $book = new Book;
            $book->isbn = $record[0];
            $book->isbn13 = $record[1];
            $book->title = $record[2];
            $book->cover = $record[4];
            $book->publisher = $record[5];
            $book->pages = intval($record[6]);
            $book->save();

            if(strlen($record[3]) > 0) {
                $authorNames = explode(",", $record[3]);
                foreach($authorNames as $authorName) {
                    $author = Author::where('name', $authorName)->first();
                    if($author == NULL) {
                        $author = new Author;
                        $author->name = $authorName;
                        $author->save();
                        $id = $author->id;
                    }
                    else {
                        $id = $author->author_id;
                    }
                    try {
                        DB::table('book_authors')->insert(["author_id" => $id, "isbn" => $book->isbn]);
                    }
                    catch(Exception $e) {}
                }
            }
        }
    }

    private function getData()
    {
        $records = array();
        $recordNo = 1;
        $filePath = storage_path('seed/books.csv');
        $fileHandle = fopen($filePath, "r");
        if($fileHandle) {
            while(($row = fgetcsv($fileHandle, 0, "\t")) !== false) {
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

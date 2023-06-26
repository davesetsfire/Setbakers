<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class CreateProductCsvFileExport implements FromCollection {

    private $storedCsvFile;

    public function __construct($storedCsvFile) {
        $this->storedCsvFile = $storedCsvFile;
    }

    public function collection() {
        $fileContent = file_get_contents($this->storedCsvFile);
        $rows = array_map('str_getcsv', explode(PHP_EOL, $fileContent));
        return collect($rows);
    }

//    public function headings(): array {
//        $categories = __('csv_category_headers');
//        return array_keys($categories[$this->categoryName]);
//    }

}

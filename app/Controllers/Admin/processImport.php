use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;

public function processImport()
{
    $rules = [
        'excel_file' => 'uploaded[excel_file]|max_size[excel_file,5120]|ext_in[excel_file,xlsx,xls]'
    ];

    if (!$this->validate($rules)) {
        return redirect()->to(route_to('admin.master-data.index'))->with('errors', $this->validator->getErrors());
    }
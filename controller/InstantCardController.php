<?php

namespace App\Http\Controllers\Crm\Product\Deposit;

use App\Acme\CoreRequest;
use App\Acme\Dbf\ClientDbfExporter;
use App\Acme\Dbf\DbfImporter;
use App\Acme\Dbf\ImportFileSource;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;

class InstantCardController extends Controller
{

    private $clientDbfExporterController;

    public function __construct()
    {
        $this->clientDbfExporterController = new ClientDbfExportController();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPan(Request $request)
    {
        $file = $request->file('file');

        if ($file->getClientOriginalExtension() !== 'dbf')
            return response()->json([
                'code' => 400,
                'message' => 'Некорректный формат файла',
                'payload' => (object)[]
            ], 400);

        $source = new ImportFileSource($file);
        $fields = [
            'PAN'
        ];

        $dbfImporter = new  DbfImporter('instant_card', $source->getFilePath());

        try {
            $dbfData = $dbfImporter->getAssocData($fields);
        } catch (\Exception $exception) {
            $source->removeFile();
            return response()->json([
                'code' => 400,
                'message' => $exception->getTraceAsString(),
                'payload' => (object)[]
            ], 400);
        }

        if (count($allowInstantCards) !== count($dbfData)) {
            $source->removeFile();
            return response()->json([
                'Количество панов не совпадают с количеством доступных продуктов!'
            ], 400);
        }

       return response()->json([
            'code' => 200,
            'message' => "success",
            'payload' => (object)[]
        ], 200);

    }
   
}

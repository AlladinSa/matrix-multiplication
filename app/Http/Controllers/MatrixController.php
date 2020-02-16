<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MatrixController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function multiply(Request $request)
    {
        try {
            $data = $request->all();
            $this->validateRequest($request);
            $matrixA = (is_array($request->matrixA[0])) ? $request->matrixA : [$request->matrixA];
            $matrixB = $request->matrixB;
            $commonSize = sizeof($matrixA[0]); //call sizeOf once better than keep calling it inside the loop
            $bInnerSize = sizeof($matrixB[0]); //call sizeOf once better than keep calling it inside the loop
            $aSize = sizeof($matrixA);
            for ($i = 0; $i < $aSize; $i++) {
                for ($j = 0; $j < $bInnerSize; $j++) {
                    $total = 0;
                    for ($z = 0; $z < $commonSize; $z++) {
                        $total = $total + ($matrixA[$i][$z] * $matrixB[$z][$j]);
                    }
                    $result[$i][$j] = $this->convertIntegerToExcel($total);
                }
            }
            return response()->json([
                'message' => 'Success!',
                'detail' => $result
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error!',
                'detail' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // dd($e);
            return response()->json([
                'status' => $e->getStatusCode(),
                'message' => 'Error!',
                'detail' => $e->getMessage()
            ]);
        }
    }

    private function validateRequest(Request $request)
    {
        $isA2Dimenssional = ($this->checkMatrixDepth($request, 'A')) ? '.*' : '';
        $this->validate($request, [
            'matrixA' => 'required|array',
            'matrixB' => 'required|array',
            'matrixB.*' => 'required|array|size:'.count($request->matrixB[0]),
            'matrixB.*.*' => 'integer|min:0',
            'matrixA'.$isA2Dimenssional => 'required|array|size:'.count($request->matrixB),
            'matrixA.*'.$isA2Dimenssional => 'integer|min:0',
        ]);

        // if (isset($request->matrixA[0]) && is_array($request->matrixA[0])) {
        //     $this->validate($request, [
        //         'matrixA.*' => 'required|array|size:'.count($request->matrixB),
        //         'matrixB.*' => 'required|array|size:'.count($request->matrixB[0]),
        //         'matrixA.*.*' => 'integer|min:0',
        //         'matrixB.*.*' => 'integer|min:0',
        //     ]);
        // } else {
        //     $this->validate($request, [
        //         'matrixA' => 'required|array|size:'.count($request->matrixB),
        //         'matrixB.*' => 'required|array|size:'.count($request->matrixB[0]),
        //         'matrixA.*' => 'integer|min:0',
        //         'matrixB.*.*' => 'integer|min:0',
        //     ]);
        // }
    }

    private function checkMatrixDepth(Request $request, string $str)
    {
        $matrixName = 'matrix'.$str;
        return (isset($request->$matrixName[0]) && is_array($request->$matrixName[0]));
    }

    private function convertIntegerToExcel(int $integer)
    {
        $numeric = ($integer - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($integer- 1) / 26);
        if ($num2 > 0) {
            return $this->convertIntegerToExcel($num2) . $letter;
        }
        return $letter;
    }
}

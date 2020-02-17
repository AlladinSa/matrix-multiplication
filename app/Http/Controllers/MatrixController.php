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
            $bInnerSize = sizeof($matrixB[0]);
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
                'message' => 'success',
                'detail' => $result
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'error',
                'detail' => $e->errors()
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'message' => 'error',
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
            'matrixB.*' => 'required|array',
            'matrixB.*.*' => 'integer|min:0',
            'matrixA'.$isA2Dimenssional => 'required|array',
            'matrixA.*'.$isA2Dimenssional => 'integer|min:0',
        ]);
        $this->validate($request, [
            'matrixB.*' => 'array|size:'.count($request->matrixB[0]),
            'matrixA'.$isA2Dimenssional => 'array|size:'.count($request->matrixB),
        ]);
    }

    private function checkMatrixDepth(Request $request, string $str)
    {
        $matrixName = 'matrix'.$str;
        return (isset($request->$matrixName[0]) && is_array($request->$matrixName[0]));
    }

    private function convertIntegerToExcel(int $inputNum)
    {
        $numeric = ($inputNum - 1) % 26;
        $letter = chr(65 + $numeric);
        $num2 = intval(($inputNum - 1) / 26);
        if ($num2 > 0) {
            return $this->convertIntegerToExcel($num2) . $letter;
        }
        return $letter;
    }
}

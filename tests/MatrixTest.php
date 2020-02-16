<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class MatrixTest extends TestCase
{
    public function guard()
    {
        return Auth::guard('api');
    }
    /**
     * @dataProvider sampleTestsDataProvider
     */
    public function testMultiply($request, $expectedResponse)
    {
        $headers = [];
        $token = Auth::guard('api')
                ->login(User::whereEmail('abc@gmail.com')->first());
        $headers['Authorization'] = 'Bearer ' . $token;

        $this->json('POST', '/api/multiply', $request, $headers)
                ->seeJson($expectedResponse);
    }

    public function sampleTestsDataProvider()
    {
        return [
            //valid tests with correct data in the request
            'valid payload test 1' => [
                ["matrixA"=>[[2,12,4,5],[6,7,8,9]],"matrixB"=>[[10,11,12],[13,14,15],[16,17,18],[19,20,21]]],
                ["message"=>"Success!","detail"=>[["LW","MT","NQ"],["QH","RL","SP"]]]
            ],
            'valid payload test 2' => [
                ["matrixA"=>[2,3],"matrixB"=>[[3],[2]]],
                ["message"=>"Success!","detail"=>[["L"]]]
            ],
            'valid payload test 3' => [
                ["matrixA"=>[[22,33]],"matrixB"=>[[13],[12]]],
                ["message"=>"Success!","detail"=>[["ZF"]]]
            ],
            
            //tests with missing data in the request
            'matrix key missing test 1' => [
                ["matrixB"=>[[2,3]]],
                ["The matrix a field is required."]
            ],
            'matrix key missing test 2' => [
                ["matrixA"=>[],"matrixB"=>[[3],[2]]],
                ["The matrix a field is required."]
            ],
            
            // tests with matrices sizes mismatch
            'matrix size mismatch test 1' => [
                ["matrixA"=>[[12,4,5],[6,7,8,9]],"matrixB"=>[[10,11,12],[13,14,15],[16,17,18],[19,20,21]]],
                ["The matrixA.0 must contain 4 items."]
            ],
            'matrix size mismatch test 2' => [
                ["matrixA"=>[2,3],"matrixB"=>[[3],[2,3]]],
                ["The matrixB.1 must contain 1 items."]
            ],
            'matrix size mismatch test 3' => [
                ["matrixA"=>[22,33],"matrixB"=>[[13]]],
                ["The matrix a must contain 1 items."]
            ],
        
            //test with characters in the request
            'non numeric values in payload test 1' => [
                ["matrixA"=>["A",3],"matrixB"=>[[3],[2]]],
                ["The matrixA.0 must be an integer."]
            ],
            'non numeric values in payload test 2' => [
                ["matrixA"=>[1,3],"matrixB"=>[[3],["A"]]],
                ["The matrixB.1.0 must be an integer."]
            ],

            //tests with negative values in the request
            'negative values in payload test 1' => [
                ["matrixA"=>[-1,3],"matrixB"=>[[3],[2]]],
                ["The matrixA.0 must be at least 0."]
            ],
            'negative values in payload test 2' => [
                ["matrixA"=>[1,3],"matrixB"=>[[3],[-1]]],
                ["The matrixB.1.0 must be at least 0."]
            ],
        ];
    }
}

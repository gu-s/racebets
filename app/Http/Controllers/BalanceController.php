<?php

namespace App\Http\Controllers;

use App\Repositories\BalanceRepository;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    private BalanceRepository $balanceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(BalanceRepository $balanceRepository){
        $this->balanceRepository = $balanceRepository;

    }

    //
    public function deposit(Request $request){
        $data = $request->json()->all();
        $this->balanceRepository->deposit($data);
        return response()->json([ "success" => true] );

    }

    public function withdraw(Request $request){
        $data = $request->json()->all();
        $responseJson = [ "success" => true ];

        try{
            $this->balanceRepository->withdraw($data);
        }
        catch(\Exception $e){
            $responseJson = [
                'success' => false,
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ],
            ];
        }


        return response()->json($responseJson);
    }

    public function report(Request $request){
        $dataJson = $request->json();
        
        $timeframePeriod = $dataJson->get("time_frame");

        $result = $this->balanceRepository->report($timeframePeriod);
        
        return response()->json($result);
    }




}

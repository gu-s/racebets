<?php

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository){
        $this->customerRepository = $customerRepository;

    }

    //
    public function list(){
        return $this->customerRepository->listCustomer();
    }

    public function show($id){
        $customer =  $this->customerRepository->showCustomer($id);
        return response()->json($customer);
    }

    public function create(Request $request){
        $data = $request->json()->all();
        $responseJson = [ "success" => true ];
        
        try{
            $this->customerRepository->createCustomer($data);
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

    public function edit(Request $request, $id){
        $data = $request->json()->all();
        $rowsAffected = $this->customerRepository->updateCustomer($id, $data);
        return response()->json([ "success" =>  true, "updated" => $rowsAffected ? true : false] );
    }





    
}

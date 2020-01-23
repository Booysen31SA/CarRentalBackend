<?php 

class RentalController extends Controller {
    // function beforeroute() {
    //     //Check to make sure token passed is valid
    //     try {
    //         $userToken = new UserToken($this->db);
    //         $token = $this->f3->get('HEADERS.Token');
    
    //         $result = $userToken->verifyToken($token);
    
    //         if(empty($result) || $result['expiryDate'] < date('Y-m-d H:i:s')) {
    //             $this->f3->error(403);
    //         }
    //     }
    //     catch(Exception $e) {
    //         header('Content-type:application/json');
    //         echo json_encode(array(
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ));
    //         exit;
    //     }
    // }
    //============================================================
                    //Create or Update
    //============================================================
    function create($f3, $params){

        header('Content-type:application/json');

        try{

            $data = json_decode($f3->get('BODY'), true);
            $custNumber = $params['custNumber'];
//custNumber would be phoneNumber in create but custNumber is CustNumber in update
            if(empty($data['custNumber']) || empty($data['vehNumber'])){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing fields'
                ));
    
                return;
            }
            $customer = new Customer($this->db);
            $vehicle = new Vehicle($this->db);
            $rental = new Rental($this->db);

            if(empty($custNumber)){
                //create
                $customerResults = $customer->searchViaPhone_Number($data['custNumber']);
                $vehicleResults = $vehicle->searchByvehNumber($data['vehNumber']);
                $rentalResults = $rental->searchViaCustNumber($data['custNumber']);

                if(empty($customerResults)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Customer details does not exists'
                    ));
                    return;
                }

                if(empty($vehicleResults)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle details does not exists'
                    ));
                    return;
                }

                if($customerResults[0]['canRent'] == 1){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Customer has already has an OUTSTANDING rental'
                    ));
                    return;
                }

                if($vehicleResults[0]['availableForRent'] == 1){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle has already been rented'
                    ));
                    return;
                }

                $data['dateRental'] = date('Y-m-d H:i:s');
                $data['pricePerDay'] = $vehicleResults[0]['rentalPrice'];
                $data['custNumber'] = $customerResults[0]['custNumber'];
                //$data['pricePerDay'] = $vehicleResults['rentalPrice'];

                //update customer canRent 
                $customerData['custNumber'] = $customerResults[0]['custNumber'];
                $customerData['canRent'] = 1;
                $customerData['LastRented'] = date('Y-m-d H:i:s');
                $customerData['firstName'] = $customerResults[0]['firstName'];
                $customerData['surName'] = $customerResults[0]['surName'];

                //update vehicle available rent
                $vehicleData['vehNumber'] = $vehicleResults[0]['vehNumber'];
                $vehicleData['availableForRent'] = 1;

                $customer->create($customerData, 'update');
                $rental->create($data);
                $vehicle->create($vehicleData);
    
            echo json_encode(array(
                'success' => true,
                'message' => 'Rental successfully created'
            ));

            return;
            } else {
                //update
                $customerResults = $customer->searchViaCustNumber($custNumber);
                $vehicleResults = $vehicle->searchByvehNumber($data['vehNumber']);
                $rentalResults = $rental->searchViaRentalNumber($data['rentalNumber']);

                if(empty($customerResults)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Customer details does not exists'
                    ));
                    return;
                }

                if(empty($vehicleResults)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle details does not exists'
                    ));
                    return;
                }

                if(empty($rentalResults)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'No Rental for the Customer'
                    ));
                    return;
                }

                if($customerResults[0]['canRent'] == 0){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Customer has No rental'
                    ));
                    return;
                }

                if($vehicleResults[0]['availableForRent'] == 0){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle has not been rented'
                    ));
                    return;
                }

                //calc total rental
                // $dateRental =DateTime::createFromFormat('m-d-Y', $rentalResults[0]['dateRental'])->format('Y-m-d');
                // $dateRenturned = date_format($data['dateReturned'],"Y/m/d");

                $data['dateReturned'] = date('Y-m-d H:i:s');
                $d1 = strtotime($rentalResults[0]['dateRental']);
                $d2 = strtotime($data['dateReturned']);

                $dateDiff = round(($d2-$d1) / 86400);
//current day return is one day
                $PriceMustPay = ($dateDiff + 1) * $rentalResults[0]['pricePerDay'];

                
                $data['totalrental'] = $PriceMustPay;
                //update customer canRent 
                $customerData['custNumber'] = $data['custNumber'];
                $customerData['canRent'] = 0;
            
                //update vehicle available rent
                $vehicleData['vehNumber'] = $vehicleResults[0]['vehNumber'];
                $vehicleData['availableForRent'] = 0;
            
                $customer->create($customerData, 'update');
                $rental->create($data);
                $vehicle->create($vehicleData);

                $rental->create($data);
    
            echo json_encode(array(
                'success' => true,
                'message' => 'Rental successfully Returned',
                'Price' => $PriceMustPay
            ));

            return;
            }

        }catch(Exception $e){
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
        }
    }

    function getAllrentalsOutsanding(){

        header('Content-type:application/json');

        try{

            $rental = new Rental($this->db);
            $result = $rental->getAllrentalsOutsanding();

            if(empty($result)) {
                
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Rental List is empty'
                ));
    
                return;
            }

            echo json_encode(array(
                'success' => true,
                'count' => count($result),
                'results' => $result
            ));

        }catch(Exception $e){
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
        }
    }
    function getAllReturnedRentals(){

        header('Content-type:application/json');

        try{

            $rental = new Rental($this->db);
            $result = $rental->getAllReturnedRentals();

            if(empty($result)) {
                
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Rental List is empty'
                ));
    
                return;
            }

            echo json_encode(array(
                'success' => true,
                'count' => count($result),
                'results' => $result
            ));

        }catch(Exception $e){
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
        } 
    }
    function getAllRentals(){
        
        header('Content-type:application/json');

        try{

            $rental = new Rental($this->db);
            $result = $rental->getAllRentals();

            if(empty($result)) {
                
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Rental List is empty'
                ));
    
                return;
            }

            echo json_encode(array(
                'success' => true,
                'count' => count($result),
                'results' => $result
            ));

        }catch(Exception $e){
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
        } 
    }
}
?>
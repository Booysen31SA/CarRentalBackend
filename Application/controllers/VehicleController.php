<?php 

class VehicleController extends Controller {
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
                    //Create
    //============================================================ 
    function create($f3, $params){

        header('Content-type:application/json');

        try{

            $data = json_decode($f3->get('BODY'), true);
            $vehNumber = $params['vehNumber'];
    
            if(empty($data['make']) || empty($data['category'])){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing one or more required fields'
                ));
                return;
            }
            $vehicle = new Vehicle($this->db);
            $vehicleCategory = new vehicleCategories($this->db);
    
            if(empty($vehNumber)){
                //create
                $result = $vehicle->searchByvehNumber($data['vehNumber']);
    
                if(!empty($result)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehhicle Exist Already'
                    ));
        
                    return; 
                }
                $resultCategory = $vehicleCategory->readByVehicleType($data['category']);
                if(empty($resultCategory)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle type does not exist'
                    ));
        
                    return; 
                }
    
                $data['availableForRent'] = 0;
                $data['created'] = date('Y-m-d H:i:s');
                $data['disabled'] = 0;
                $data['rentalPrice'] = $resultCategory[0]['price'];
    
                $vehicle->create($data);
        
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Vehicle successfully created'
                ));
    
                return;
            }else{
                //update
                $result = $vehicle->searchByvehNumber($vehNumber);
    
                if(empty($result)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle does not Exist'
                    ));
        
                    return; 
                }
                $resultCategory = $vehicleCategory->readByVehicleType($data['category']);

                if(empty($resultCategory)){
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Vehicle type does not exist'
                    ));
        
                    return; 
                }

                $data['availableForRent'] = $result[0]['result'];
                $data['vehNumber'] = $vehNumber;
                $data['modified'] = date('Y-m-d H:i:s');
                $data['rentalPrice'] = $resultCategory[0]['price'];
    
                $vehicle->create($data);
        
                echo json_encode(array(
                    'success' => true,
                    'message' => 'Vehicle successfully updated'
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
    //============================================================
                    //searchByCategory
    //============================================================ 

    function searchByCategory($f3, $params){

        header('Content-type:application/json');

        try{

            $category = $params['category'];

            if(empty($category)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing one or more required fields'
                ));

                return;
            }

            $vehicle = new Vehicle($this->db);
            $result = $vehicle->searchByCategory($category);

            if(empty($result)) {
                
                echo json_encode(array(
                    'success' => false,
                    'message' => 'No Vehicle found by that category'
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

    //============================================================
                    //Delete
    //============================================================ 
    function delete($f3, $params){
    
        header('Content-type:application/json');
    
        try{
    
            $vehNumber = $params['vehNumber'];
    
            if(empty($vehNumber)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing one or more required fields'
                ));
                
                return;
            }
    
            $vehicle = new Vehicle($this->db);
    
            $result = $vehicle->searchByvehNumber($vehNumber);
    
    
            if(empty($result)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Customer does not exist'
                ));
    
                return;
            }
    
            if($result['availableForRent']){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Vehicle still needs to returned'
                ));
    
                return;
            }
    
            $data['vehNumber'] = $vehNumber;
            $data['make'] = $result[0]['make'];
            $data['category'] = $result[0]['category'];
            $data['rentalPrice'] = $result[0]['rentalPrice'];
            $data['availableForRent'] = $result[0]['availableForRent'];
            $data['created'] = $result[0]['created'];
            $data['modified'] = $result[0]['modified'];
            $data['disabled'] = 1;
    
            $vehicle->delete($data);
    
            echo json_encode(array(
                'success' => true,
                'message' => 'Vehicle successfully deactivated'
            ));
        }catch(Exception $e){
    
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
        }
    }

        //============================================================
                    //get All List
    //============================================================
    function getAllVehicles($f3, $params){

        header('Content-type:application/json');

        try{

            $disabled = $params['disabled'];

            if($disabled < 0 ){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing one or more required fields'
                ));

                return;
            }

            $vehicle = new Vehicle($this->db);
            $result = $vehicle->getAllVehicles($disabled);

            if(empty($result)) {
                
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Customer List is empty'
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
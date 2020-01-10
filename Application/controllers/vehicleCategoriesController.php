<?php 

class vehicleCategoriesController extends Controller {
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
        $vehicleType = $params['vehicleType'];

        if(empty($data['vehicleType']) || empty($data['price'])){
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing one or more required fields'
            ));
            return;
        }

        $vehicleCategories = new vehicleCategories($this->db);

        if(empty($vehicleType)){
            //created
            $result = $vehicleCategories->readByVehicleType($data['vehicleType']);

            if(!empty($result)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Vehicle type Already in System'
                ));
    
                return;
            }

            $data['created'] = date('Y-m-d H:i:s');
            $data['disabled'] = 0;

            $vehicleCategories->create($data);

            echo json_encode(array(
                'success' => true,
                'message' => 'Vehicle type successfully created'
            ));

            return;

        } else {
            //updated
            $result = $vehicleCategories->readByVehicleType($data['vehicleType']);

            if(empty($result[0])){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Vehicle type NOT in the System'
                ));
    
                return;
            }

            $data['ID'] =$result[0]['ID'];

            $vehicleCategories->create($data);

            echo json_encode(array(
                'success' => true,
                'message' => 'Vehicle type successfully updated'
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
                //searchViaSurname
//============================================================
 function readByVehicleType($f3, $params){

    header('Content-type:application/json');

    try{

        $vehicleType = $params['vehicleType'];

        if(empty($vehicleType)){
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing one or more required fields'
            ));

            return;
        }

        $vehicleCategories = new vehicleCategories($this->db);
        $result = $vehicleCategories->readByVehicleType($vehicleType);

        if(empty($result)) {
                
            echo json_encode(array(
                'success' => false,
                'message' => 'No Vehicle type Found by that Surname'
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

        $vehicleType = $params['vehicleType'];

        if(empty($vehicleType)){
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing one or more required fields'
            ));
            
            return;
        }

        $vehicleCategories = new vehicleCategories($this->db);
        $result = $vehicleCategories->readByVehicleType($vehicleType);


        if(empty($result)){
            echo json_encode(array(
                'success' => false,
                'message' => 'Vehicle type does not exist'
            ));

            return;
        }

        $data['ID'] = $result[0]['ID'];
        $data['vehicleType'] = $result[0]['vehicleType'];
        $data['disabled'] = 1;

        $vehicleCategories->delete($data);

        echo json_encode(array(
            'success' => true,
            'message' => 'Customer successfully deactivated'
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
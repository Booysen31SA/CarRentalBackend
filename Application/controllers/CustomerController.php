<?php 

class CustomerController extends Controller {
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
                    //get All List
    //============================================================
    function getAllCustomers($f3, $params){

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

            $vehicleCategories = new vehicleCategories($this->db);
            $result = $vehicleCategories->readByVehicleType($vehicleType);

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

//============================================================
                //searchViaSurname
//============================================================
    function searchViaSurname($f3, $params){

        header('Content-type:application/json');

        try{

            $surname = $params['surname'];

            if(empty($surname)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing one or more required fields'
                ));

                return;
            }

            $customers = new Customer($this->db);
            $result = $customers->searchViaSurname($surname);

            if(empty($result)) {
                
                echo json_encode(array(
                    'success' => false,
                    'message' => 'No User Found by that Surname'
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
                //Create or Update
//============================================================
function create($f3, $params) {

    header('Content-type:application/json');

    try{
        $data = json_decode($f3->get('BODY'), true);
        $custNumber = $params['custNumber'];

        if(empty($data['firstName']) || empty($data['surName']) || empty($data['Phone_Number']) || empty($data['Address'])){
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing one or more required fields'
            ));
            return;
        }
        $customer = new Customer($this->db);

        if(empty($custNumber)){
            //create
            $result = $customer->searchViaPhone_Number($data['Phone_Number']);

            if(!empty($result)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Customer Exist Already'
                ));
    
                return; 
            }

            $data['canRent'] = 0;
            $data['created'] = date('Y-m-d H:i:s');
            $data['disabled'] = 0;

            $customer->create($data, 'create');
    
            echo json_encode(array(
                'success' => true,
                'message' => 'Customer successfully created'
            ));

            return;
        }else{
            //update
            $result = $customer->searchViaCustNumber($custNumber);

            if(empty($result)){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Customer does not exist'
                ));
    
                return; 
            }

            unset($data['created']);
            unset($data['disabled']);

            $data['custNumber'] = $custNumber;
            $data['canRent'] = 0;

            $customer->create($data, 'update');
    
            echo json_encode(array(
                'success' => true,
                'message' => 'Customer successfully Updated'
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

function delete($f3, $params){
    
    header('Content-type:application/json');

    try{

        $custNumber = $params['custNumber'];

        if(empty($custNumber)){
            echo json_encode(array(
                'success' => false,
                'message' => 'Missing one or more required fields'
            ));
            
            return;
        }

        $customer = new Customer($this->db);

        $result = $customer->searchViaCustNumber($custNumber);


        if(empty($result)){
            echo json_encode(array(
                'success' => false,
                'message' => 'Customer does not exist'
            ));

            return;
        }

        if($result['canRent']){
            echo json_encode(array(
                'success' => false,
                'message' => 'Customer still needs to return rental Car'
            ));

            return;
        }

        $data['custNumber'] = $custNumber;
        $data['firstName'] = $result[0]['firstName'];
        $data['surName'] = $result[0]['surName'];
        $data['Phone_Number'] = $result[0]['Phone_Number'];
        $data['Address'] = $result[0]['Address'];
        $data['canRent'] = $result[0]['canRent'];
        $data['created'] = $result[0]['created'];
        $data['LastRented'] = $result[0]['LastRented'];
        $data['disabled'] = 1;

        $customer->delete($data);

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
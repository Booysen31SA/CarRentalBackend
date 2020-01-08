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

            $customers = new Customer($this->db);
            $result = $customers->getAllCustomers($disabled);

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

    }
?>
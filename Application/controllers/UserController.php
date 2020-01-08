<?php 

class UserController extends Controller {
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
    function LoginUser($f3, $params){

        header('Content-type:application/json');

        try {

            $user = new User($this->db);

            $data = json_decode($f3->get('BODY'), true);

            if(empty($data['Username']) || empty($data['Password'])){
                echo json_encode(array(
                    'success' => false,
                    'message' => 'Missing fields'
                ));
    
                return;
            }

            $result = $user->getByUsername($data['Username']);

            if(empty($result[0]['Username'])){

                echo json_encode(array(
                    'success' => false,
                    'message' => 'User does not exist'
                ));
    
                return;
            }

            $issuedPassword = md5($data['Password']);
            if($data['Username'] != $result[0]['Username'] || $result[0]['Password'] != $issuedPassword){

                echo json_encode(array(
                    'success' => false,
                    'message' => 'Username or Password does not match'
                ));
    
                return;
            }

            unset($data['Password']);
            $data['Last_Login'] = date('Y-m-d H:i:s');

            $user->create($data);

            echo json_encode(array(
                'success' => true,
                'message' => 'Successfully Logged in!'
            ));

        }catch(Exception $e) {
            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));
            exit;
        }
    }

    function create($f3, $params){

        header('Content-type:application/json');

        try {
            $user = new User($this->db);

            $data = json_decode($f3->get('BODY'), true);

            if(empty($params['Username'])){
                //create

                $result = $user->getByUsername($data['Username']);

                if(!empty($result[0]['Username'])){

                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Username already exist'
                    ));
        
                    return;
                }

                $resultCell = $user->getByCell($data['Cell_Number']);

                if(!empty($resultCell[0]['Cell_Number'])){

                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Cell number already exist'
                    ));
        
                    return;
                }

                if(empty($data['Username']) || empty($data['Password']) || empty($data['First_Name']) || empty($data['Last_Name']) || empty($data['Cell_Number']) || empty($data['Home_Address'])){

                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Missing one or more required fields'
                    ));

                    return;
                }

                $data['Password'] = md5($data['Password']);
                $data['disabled'] = 0;
                $data['Created'] = date('Y-m-d H:i:s');

                $user->create($data);

                echo json_encode(array(
                    'success' => true,
                    'message' => 'Successfully Created!'
                ));

            }else {
                //update
                $data['Username'] = $params['Username'];

                $result = $user->getByUsername($data['Username']);

                if(empty($result)) {
                
                    echo json_encode(array(
                        'success' => false,
                        'message' => 'User does not exist'
                    ));
        
                    return;
        
                }

                if(!empty($result['Username'])){

                    echo json_encode(array(
                        'success' => false,
                        'message' => 'Username already exist'
                    ));
        
                    return;
                }

                unset($data['Password']);
                $data['Modified'] = date('Y-m-d H:i:s');

                $user->create($data);

                echo json_encode(array(
                    'success' => true,
                    'message' => 'User successfully updated!'
                ));
            }

        } catch(Exception $e) {

            echo json_encode(array(
                'success' => false,
                'message' => $e->getMessage()
            ));

        }
    }
}
?>
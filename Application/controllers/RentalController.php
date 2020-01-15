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

}
?>
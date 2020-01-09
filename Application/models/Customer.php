<?php

 class Customer extends DB\SQL\Mapper{

     public function __construct(DB\SQL $db)
     {
         parent::__construct($db, 'customer');
     }

     public function getAllCustomers($disabled){

         try{

            $query = "SELECT * FROM customer WHERE disabled = '$disabled' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function searchViaSurname($surname) {
        try{

            $query = "SELECT * FROM customer WHERE surName = '$surname' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function searchViaCustNumber($CustNumber) {
        try{

            $query = "SELECT * FROM customer WHERE custNumber = '$CustNumber' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function searchViaPhone_Number($Phone_Number) {
        try{

            $query = "SELECT * FROM customer WHERE Phone_Number = '$Phone_Number' ORDER BY created DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function create($data, $type){
        try{
           if($type == 'create'){
            $this->load(array('Phone_Number = ?', $data['Phone_Number']));

            $this->copyFrom($data);

            $this->save();
           } else {
            $this->load(array('custNumber = ?', $data['custNumber']));

            $this->copyFrom($data);

            $this->save();  
           }

         }catch(Exception $e){
             throw new Exception($e);
         }
     }
    }
?>
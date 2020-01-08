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
    }
?>
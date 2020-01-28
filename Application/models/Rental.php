<?php

 class Rental extends DB\SQL\Mapper{

     public function __construct(DB\SQL $db)
     {
         parent::__construct($db, 'rental');
     }

     public function create($data){
        try{
            $this->load(array('rentalNumber = ?', $data['rentalNumber']));

            $this->copyFrom($data);
            $this->save();
        }catch(Exception $e){
            throw new Exception($e);
        }
     }

     public function delete($data){
        try{
            $this->load(array('custNumber = ?', $data['custNumber']));

            $this->copyFrom($data);
            $this->save();
        }catch(Exception $e){
            throw new Exception($e);
        }
     }

     public function searchViaCustNumber($custNumber){
        try{

            $query = "SELECT * FROM rental WHERE custNumber = '$custNumber' ORDER BY dateRental DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function searchViaRentalNumber($rentalNumber){
        try{

            $query = "SELECT * FROM rental WHERE rentalNumber = '$rentalNumber' ORDER BY dateRental DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function getAllrentalsOutsanding(){
        try{

            $query = "SELECT * FROM rental WHERE dateReturned IS NULL ORDER BY dateRental DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function getAllReturnedRentals(){
        try{

            $query = "SELECT * FROM rental WHERE dateReturned IS NOT NULL ORDER BY dateRental DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function getAllRentals(){
        try{

            $query = "SELECT * FROM rental ORDER BY dateRental DESC";

            $result = $this->db->exec($query);

            return $result;

         }catch(Exception $e){
               throw new Exception($e);
         }
     }

     public function SalesPerMonth(){
        try{
              $query = "CALL SalesPerMonth()";
        
              $result = $this->db->exec($query);
        
              return $result;
              
                 }catch(Exception $e){
                    throw new Exception($e);
                 }
    }
    }
?>
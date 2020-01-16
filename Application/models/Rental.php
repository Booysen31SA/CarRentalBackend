<?php

 class Rental extends DB\SQL\Mapper{

     public function __construct(DB\SQL $db)
     {
         parent::__construct($db, 'rental');
     }

     public function create($data){
        try{
            $this->load(array('custNumber = ?', $data['custNumber']));

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
    }
?>
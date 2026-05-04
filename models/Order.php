<?php
// File : models/Order.php

class Order{
    private $db;
    private $table = 'orders';

    public $id;
    public $num_cmd;
    public $nom_client;
    public $phone;
    public $delivery;
    public $montant;
    public $statut = 'pending';

    public function __construct($db){
        $this->db = $db;
    }

    public function getAll(){
        $query = 'SELECT * FROM ' . $this->table;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne(){
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->id]);
        return $stmt->fetch();
    }

    public function create(){
        $query = 'INSERT INTO ' . $this->table . '(num_cmd, nom_client, phone, delivery, montant, statut) VALUES(?,?,?,?,?,?)';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->num_cmd,
            $this->nom_client,
            $this->phone,
            $this->delivery,
            $this->montant,
            $this->statut
        ]);
    }

    public function update(){
        $query = 'UPDATE ' . $this->table . ' SET nom_client = ?, phone = ?, delivery = ?, montant = ?, statut = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->nom_client,
            $this->phone,
            $this->delivery,
            $this->montant,
            $this->statut,
            $this->id
        ]);
    }

    public function updateNumCmd(){
		$query = 'UPDATE ' . $this->table . ' SET num_cmd = ? WHERE id = ?';
		$stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->num_cmd,
			$this->id
        ]);
    }

    public function delete(){
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->id]);
    }

    public function changeOrderStatus(){
        $query = 'UPDATE ' . $this->table . ' SET statut = ? WHERE id = ?';
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $this->statut,
            $this->id
        ]);
    }
}
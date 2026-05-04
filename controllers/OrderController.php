<?php
// File : controllers/OrderController.php

class OrderController{
	private $db;
	private $orderObj;

	public function __construct($db){
		$this->db = $db;
		$this->orderObj = new Order($db);
	}

	public function getAll(){
		$orders = $this->orderObj->getAll();

		http_response_code(200);
		echo json_encode([
			'status' => 'success',
			'data' => $orders
		]);
	}

	public function getOne($id){
		$this->orderObj->id = $id;
		$order = $this->orderObj->getOne();

		if($order){
			http_response_code(200);
			echo json_encode([
				'status' => 'success',
				'data' => $order
			]);
		}else{
			http_response_code(404);
			echo json_encode([
				'status' => 'error',
				'message' => 'Commande non trouvée'
			]);
		}
	}

	public function create(){
		$data = json_decode(file_get_contents('php://input'), true);
		$errors = $this->validate($data);

		if(empty($errors)){
			$this->orderObj->nom_client = $data['nom_client'];
			$this->orderObj->phone = $data['phone'];
			$this->orderObj->delivery = $data['delivery'];
			$this->orderObj->montant = $data['montant'];

			if($this->orderObj->create()){
				$this->orderObj->id = $this->db->lastInsertId();
				$this->orderObj->num_cmd = $this->generateNumCmd($this->orderObj->id);

				if($this->orderObj->update()){
					http_response_code(201);
					echo json_encode([
						'status' => 'success',
						'message' => 'Commande enregistrée avec succès'
					]);
				}else{
					http_response_code(400);
					echo json_encode([
						'status' => 'error',
						'message' => 'Erreur numéro de commande'
					]);
				}
			}
		}else{
			foreach($errors as $error){
				http_response_code(400);
				echo json_encode([
					'status' => 'error',
					'message' => $error
				]);
			}
			return;
		}
	}

	public function update($id){
		$data = json_decode(file_get_contents('php://input'), true);
		$errors = $this->validate($data);

		if(empty($errors)){
			$this->assignData($data);
			$this->orderObj->id = $id;

			if($this->orderObj->update()){
				http_response_code(200);
				echo json_encode([
					'status' => 'success',
					'message' => 'Categorie modifiée avec succès'
				]);
			}
		}else{
			foreach($errors as $error){
				http_response_code(400);
				echo json_encode([
					'status' => 'error',
					'message' => $error
				]);
			}
			return;
		}
	}

	public function changeOrderStatus($status, $id){
		$this->orderObj->id = $id;
		$this->orderObj->statut = $status;

		if($this->orderObj->changeOrderStatus()){
			http_response_code(200);
			echo json_encode([
				'status' => 'success',
				'message' => 'Status modifié avec succcès'
			]);
		}
	}

	public function delete($id){
		$this->orderObj->id = $id;

		if($this->orderObj->delete()){
			http_response_code(200);
			echo json_encode([
				'status' => 'success',
				'message' => 'Commande supprimée avec succès'
			]);
		}
	}

	public function validate($data){
		$errors = [];
		if(empty($data['nom_client'])){
			$errors[] = 'Erreur : Le nom est obligatoire';
		}
		if(empty($data['phone'])){
			$errors[] = 'Erreur : Le numéro de téléphone est obligatoire';
		}
		if(empty($data['montant']) || !is_numeric($data['montant'])){
			$errors[] = 'Erreur : Le montant de la commande est incorrecte';
		}

		return $errors;
	}

	private function generateNumCmd($lastInsertId){
		$prefix = 'CMD';
		$year = date('Y');
		$number = str_pad($lastInsertId, 4, '0', STR_PAD_LEFT);

		$num_cmd = $prefix . '-' . $year . '-' . $number;
		return $num_cmd;
	}

	public function assignData($data){
		$this->orderObj->nom_client = $data['nom_client'];
		$this->orderObj->phone = $data['phone'];
		$this->orderObj->delivery = $data['delivery'];
		$this->orderObj->montant = $data['montant'];
	}
}
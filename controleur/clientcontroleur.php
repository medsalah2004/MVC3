<?php
include(__DIR__ . '/../config.php');
include(__DIR__ . '/../Modele/clients.php');

class ClientControleur 
{
    private $db;
    
    public function __construct() {
        try {
            $this->db = config::getConnexion();
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    public function listclient()
    {
        $sql = "SELECT * FROM client";
        try {
            $liste = $this->db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error:' . $e->getMessage());
            return [];
        }
    }
    
    public function addclient($client)
    {
        $sql = "INSERT INTO client (nom, prenom, email, cin, date_naissance, password, confirm_password, role , date_inscription)
                VALUES (:nom, :prenom, :email, :cin, :date_naissance, :password, :confirm_password, :role , :date_inscription)";
        try {
            $query = $this->db->prepare($sql);
            $query->execute([
                'nom' => $client->getnom(),
                'prenom' => $client->getprenom(),
                'email' => $client->getemail(),
                'cin' => $client->getcin(),
                'date_naissance' => $client->getdate_naissance()->format('Y-m-d'),
                'password' => $client->getpassword(),
                'confirm_password' => $client->getconfirm_password(),
                'role' => $client->getrole(), 
                'date_inscription' => $client->getdate_inscription()->format('Y-m-d H:i:s')

            ]);
            return true;
        } catch (PDOException $e) {
            error_log('Error adding client: ' . $e->getMessage());
            return false;
        }
    }

    public function updateclient($client, $id)
    {
        try {
            $query = $this->db->prepare(
                'UPDATE client SET 
                    nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    password = :password,
                    date_naissance = :date_naissance,
                    cin = :cin,
                    confirm_password = :confirm_password,
                    role = :role
                    date_inscription = :date_inscription,
                WHERE id = :id'
            );

            $result = $query->execute([
                'id' => $id,
                'cin' => $client->getcin(),
                'nom' => $client->getnom(),
                'prenom' => $client->getprenom(),
                'email' => $client->getemail(),
                'password' => $client->getpassword(), 
                'date_naissance' => $client->getdate_naissance()->format('Y-m-d'),
                'confirm_password' => $client->getconfirm_password(),
                'role'=> $client->getrole(),
                'date_inscription' => $client->getdate_inscription()->format('Y-m-d H:i:s'),
            ]);

            return $result;
        } catch (PDOException $e) {
            error_log("Error updating client: " . $e->getMessage());
            return false;
        }
    }

    public function deleteclient($id)
    {
        try {
            $sql = "DELETE FROM client WHERE id = :id";
            $req = $this->db->prepare($sql);
            $req->bindValue(':id', $id, PDO::PARAM_INT);
            return $req->execute();
        } catch (PDOException $e) {
            error_log('Error deleting client: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($id, $Password) {
        try {
            $db = config::getConnexion();
            $query = "UPDATE client SET password = :password WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":password", $Password);
            $stmt->bindParam(":id", $id);
        
          if ($stmt->execute()) {
             return true; // Password updated successfully
         } else {
             error_log("Failed to update password for user ID: $id");
             return false; // Failed to update password
         }
     } catch (PDOException $e) {
         error_log("Database error: " . $e->getMessage());
         return false; // Database error occurred
     }
 }
	public function updateconfirmPassword($id, $confirm_Password) {
    	try {
             $db = config::getConnexion();
             $query = "UPDATE client SET confirm_password = :confirm_password WHERE id = :id";
             $stmt = $db->prepare($query);
        $stmt->bindParam(":confirm_password", $confirm_Password);
        $stmt->bindParam(":id", $id);
        
        if ($stmt->execute()) {
            return true; // Password updated successfully
        } else {
            error_log("Failed to update password for user ID: $id");
            return false; // Failed to update password
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false; // Database error occurred
    }
}

    public function showclient($id)
    {
        $sql = "SELECT * FROM client WHERE id = :id";
        try {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();

            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error: ' . $e->getMessage());
            return null;
        }
    }

    public function getUserRegistrationStats() {
        try {
            $query = "SELECT DATE(date_inscription) as date, COUNT(*) as count 
                      FROM client 
                      GROUP BY DATE(date_inscription) 
                      ORDER BY date_inscription";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching user registration stats: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalUsers() {
        try {
            $query = "SELECT COUNT(*) as total FROM client";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error counting users: " . $e->getMessage());
            return 0;
        }
    }
    public function getTotalAdmins() {
        try {
            // Remplacez "client" par le nom de votre table dans la base de données
            $query = "SELECT COUNT(*) as total FROM client WHERE role = 'Admin'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error counting admins: " . $e->getMessage());
            return 0;
        }
    }
    public function getTotalClients() {
        try {
            // Remplacez "client" par le nom de votre table dans la base de données
            $query = "SELECT COUNT(*) as total FROM client WHERE role = 'Client'";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error counting admins: " . $e->getMessage());
            return 0;
        }
    }
}


?>
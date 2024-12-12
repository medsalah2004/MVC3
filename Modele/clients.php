<?php
class client {
    private ?int $id;
    private ?string $nom;
    private ?string $prenom;
    private ?string $email;  // Correction ici
    private ?string $cin; // Correction ici
    private ?DateTime $date_naissance;
    private ?string $password;
    private ?string $confirm_password;
    private ?string $role;
    private ?DateTime $date_inscription;
   
   
    // Constructor
    public function __construct(?int $id, ?string $nom, ?string $prenom, ?string $email, ?string $cin, ?DateTime $date_naissance, ?string $password, ?string $confirm_password, ?string $role, ?DateTime $date_inscription = null ) {
        $this->id = $id;    
        $this->nom = $nom; 
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;  // Correction ici
        $this->date_naissance = $date_naissance;
        $this->cin = $cin;
        $this->role = $role;
        $this->confirm_password= $confirm_password;
        $this->date_inscription =  $date_inscription ?: new DateTime();
    }

    // Getters and Setters
    public function getid(): ?int {
        return $this->id;
    }

    public function setid(?int $id): void {  // Correction ici
        $this->id = $id;
    }

    public function getnom(): ?string {
        return $this->nom;
    }

    public function setnom(?string $nom): void {
        $this->nom = $nom;
    }

    public function getprenom(): ?string {
        return $this->prenom;
    }

    public function setprenom(?string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getemail(): ?string {  // Correction ici
        return $this->email;
    }

    public function setemail(?string $email): void {  // Correction ici
        $this->email = $email;
    }

    public function getcin(): ?string {
        return $this->cin;
    }

    public function setcin(?string $cin): void {
        $this->cin = $cin;
    }

    public function getpassword(): ?string {  // Correction ici
        return $this->password;
    }

    public function setpassword(?string $password): void {  // Correction ici
        $this->password = $password;
    }

    public function getdate_naissance(): ?DateTime {
        return $this->date_naissance;
    }

    public function setdate_naissance(DateTime $date_naissance): void {
        $this->date_naissance = $date_naissance;
    }

    public function getrole(): ?string {
    return $this->role;
}

public function setrole(?string $role): void {  // Correction ici
    $this->role = $role;
}
public function getconfirm_password(): ?string {
    return $this->confirm_password;
}

public function setconfirm_password(?string $confirm_password): void {  // Correction ici
    $this->confirm_password = $confirm_password;
}
public function getdate_inscription(): ?DateTime {
    return $this->date_inscription;
}

public function setdate_inscription(DateTime $date_inscription): void {
    $this->date_inscription = $date_inscription;
}
}
?>
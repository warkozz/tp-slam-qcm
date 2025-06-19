<?php
// Classe représentant une réponse à une question de QCM
class Reponse {
    private string $texte;
    private bool $estCorrecte;

    public function __construct(string $texte, bool $estCorrecte) {
        $this->texte = $texte;
        $this->estCorrecte = $estCorrecte;
    }

    public function getTexte(): string {
        return $this->texte;
    }

    public function estCorrecte(): bool {
        return $this->estCorrecte;
    }

    public function setTexte(string $texte): void {
        $this->texte = $texte;
    }

    public function setEstCorrecte(bool $estCorrecte): void {
        $this->estCorrecte = $estCorrecte;
    }
}

<?php
// Classe représentant une question de QCM
require_once 'Reponse.php';

class Question {
    private string $texte;
    private array $reponses = [];
    private string $explication;

    public function __construct(string $texte, string $explication) {
        $this->texte = $texte;
        $this->explication = $explication;
    }

    public function ajouterReponse(Reponse $reponse): void {
        $this->reponses[] = $reponse;
    }

    public function getTexte(): string {
        return $this->texte;
    }
    public function getExplication(): string {
        return $this->explication;
    }
    public function getReponses(): array {
        return $this->reponses;
    }

    public function estBonneReponse($indices): bool {
        $bonnes = [];
        foreach ($this->reponses as $i => $rep) {
            if ($rep->estCorrecte()) {
                $bonnes[] = $i;
            }
        }
        sort($bonnes);
        $indices = (array)$indices;
        $indices = array_map('intval', $indices);
        sort($indices);
        return $indices === $bonnes;
    }
}

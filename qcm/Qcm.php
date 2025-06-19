<?php
// Classe représentant un QCM
require_once 'Question.php';

class Qcm {
    private string $titre;
    private array $questions = [];

    public function __construct(string $titre) {
        $this->titre = $titre;
    }

    public function ajouterQuestion(Question $question): void {
        $this->questions[] = $question;
    }

    public function getTitre(): string {
        return $this->titre;
    }
    public function getQuestions(): array {
        return $this->questions;
    }

    public function getNbQuestions(): int {
        return count($this->questions);
    }
}

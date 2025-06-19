<?php
// Script principal : création et simulation d'un QCM
require_once 'Qcm.php';
require_once 'Question.php';
require_once 'Reponse.php';

// Création du QCM
$qcm = new Qcm('Mini QCM PHP');

// Création des questions et réponses
$question1 = new Question('Quelle est la capitale de la France ?', "La capitale de la France est Paris.");
$question1->ajouterReponse(new Reponse('Paris', true));
$question1->ajouterReponse(new Reponse('Lyon', false));
$question1->ajouterReponse(new Reponse('Marseille', false));
$qcm->ajouterQuestion($question1);

$question2 = new Question('Combien y a-t-il de continents sur Terre ?', "Il y a 6 ou 7 continents selon les modèles.");
$question2->ajouterReponse(new Reponse('5', false));
$question2->ajouterReponse(new Reponse('6', true));
$question2->ajouterReponse(new Reponse('7', true));
$qcm->ajouterQuestion($question2);

$question3 = new Question('Quel langage est utilisé pour le développement web côté serveur ?', "PHP est un langage populaire côté serveur.");
$question3->ajouterReponse(new Reponse('HTML', false));
$question3->ajouterReponse(new Reponse('PHP', true));
$question3->ajouterReponse(new Reponse('CSS', false));
$qcm->ajouterQuestion($question3);

function sauvegarderQcm(Qcm $qcm, $fichier) {
    $data = [];
    foreach ($qcm->getQuestions() as $question) {
        $reponses = [];
        foreach ($question->getReponses() as $reponse) {
            $reponses[] = [
                'texte' => $reponse->getTexte(),
                'estCorrecte' => $reponse->estCorrecte()
            ];
        }
        $data[] = [
            'texte' => $question->getTexte(),
            'explication' => $question->getExplication(),
            'reponses' => $reponses
        ];
    }
    file_put_contents($fichier, json_encode($data, JSON_PRETTY_PRINT));
}

function chargerQcm($fichier) {
    $data = json_decode(file_get_contents($fichier), true);
    $qcm = new Qcm('QCM chargé');
    foreach ($data as $q) {
        $question = new Question($q['texte'], $q['explication']);
        foreach ($q['reponses'] as $r) {
            $question->ajouterReponse(new Reponse($r['texte'], $r['estCorrecte']));
        }
        $qcm->ajouterQuestion($question);
    }
    return $qcm;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo '<form method="post">';
    foreach ($qcm->getQuestions() as $i => $question) {
        echo '<fieldset><legend>' . htmlspecialchars($question->getTexte()) . '</legend>';
        foreach ($question->getReponses() as $j => $reponse) {
            echo '<label><input type="checkbox" name="rep['.$i.'][]" value="'.$j.'"> '.htmlspecialchars($reponse->getTexte()).'</label><br>';
        }
        echo '</fieldset><br>';
    }
    echo '<button type="submit">Valider</button></form>';
    exit;
}

$note = 0;
$nbQuestions = $qcm->getNbQuestions();
$reps = $_POST['rep'] ?? [];

$html = '';
foreach ($qcm->getQuestions() as $i => $question) {
    $indices = isset($reps[$i]) ? $reps[$i] : [];
    $isCorrect = $question->estBonneReponse($indices);
    if ($isCorrect) {
        $note++;
        $resultat = '<span style="color:green;">Bonne réponse</span>';
    } else {
        $resultat = '<span style="color:red;">Mauvaise réponse</span>';
    }
    $html .= '<fieldset><legend>' . htmlspecialchars($question->getTexte()) . '</legend>';
    $html .= $resultat . '<br>';
    $html .= 'Explication : ' . htmlspecialchars($question->getExplication()) . '<br>';
    $html .= 'Vos choix : ';
    if (empty($indices)) {
        $html .= '<em>Aucune réponse</em>';
    } else {
        $choix = [];
        foreach ((array)$indices as $idx) {
            $choix[] = htmlspecialchars($question->getReponses()[$idx]->getTexte());
        }
        $html .= implode(', ', $choix);
    }
    $html .= '<br>Bonne(s) réponse(s) : ';
    $br = [];
    foreach ($question->getReponses() as $j => $rep) {
        if ($rep->estCorrecte()) {
            $br[] = htmlspecialchars($rep->getTexte());
        }
    }
    $html .= implode(', ', $br);
    $html .= '</fieldset><br>';
}

$noteSur20 = ($note / $nbQuestions) * 20;

if ($noteSur20 >= 16) {
    $appreciation = 'Très bien';
} elseif ($noteSur20 >= 12) {
    $appreciation = 'Bien';
} elseif ($noteSur20 >= 8) {
    $appreciation = 'Passable';
} else {
    $appreciation = 'Insuffisant';
}

// Affichage des résultats
echo '<h2>Votre note : ' . $noteSur20 . ' / 20</h2>';
echo '<h3>Appréciation : ' . $appreciation . '</h3>';
echo $html;
?>

<?php
require "elements/header.php";
$error = null;
$success = null;
echo "<pre>";
var_dump($_POST);
echo "</pre>";
    // VERIF DONNEES POST
if (isset($_POST)) {
  try {
    $pdo = new PDO("mysql:host=localhost;dbname=qcm;charset=utf8","root","");

    if (isset($_POST["form_ajout_theme"])) {
      if (ajout_theme($_POST["ajout_theme"],$pdo)) {
        $success = "Thème ajouté avec succès !";
      }
    }

    if (isset($_POST["form_ajout_question"])) {
      if (!isset($_POST["choix_theme"])) {
        throw new Exception("Un thème doit être choisi");
      }
      if (ajout_question($_POST["ajout_question"],$_POST["choix_theme"],$_POST["ajout_reponse"],$pdo)) {
        $success = "Question/réponses ajoutées avec succès !";
      }
    }

    if (isset($_POST["form_ajout_rep_supp"])) {
      if (!isset($_POST["choix_question"])) {
        throw new Exception ("La réponse doit être liée à une question");
      }
      $bonne_rep = isset($_POST["bonne_rep_supp"]) ? $_POST["bonne_rep_supp"] : "0";
      if (ajout_rep_supp($_POST["choix_question"],$_POST["ajout_rep_supp"],$bonne_rep,$pdo)) {
        $success = "Réponse ajoutée avec succès !";
      }
    }
  } catch (Exception $e) {
    $error = $e->getMessage();
  }
}
?>

<div class="container">
  <p class="display-4">Espace d'administration</p>
  <hr class="my-4">
  <?php if($error): ?>
  <div class="alert alert-danger">
      <?= $error ?>
  </div>
  <?php endif ?>
  <?php if($success): ?>
  <div class="alert alert-success">
      <?= $success ?>
  </div>
  <?php endif ?>
  <div class="row">
    
    <div class="card col-6 p-0 m-2">
      <div class="card-header text-center text-white" style="background-color:#218ed6">
        <h3>Création</h3>
      </div>

        <div class="card-body">
            
            <!-- FORMULAIRE AJOUT THEME -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="ajout_theme"><h4>Ajouter un thème</h4></label>
                    <input type="text" class="form-control <?= isset($error_theme) ? 'is-invalid' : '' ?>" id="ajout_theme" name="ajout_theme" placeholder="Nom du thème">
                </div>
                <button type="submit" name="form_ajout_theme" class="btn btn-primary">Ajouter</button>
            </form>


            <hr class="my-4">

            <!-- FORMULAIRE AJOUT QUESTION -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="choix_theme"><h4>Ajouter une question</h4></label>
                      <select class="form-control" name="choix_theme" id="choix_theme">
                        <option selected disabled>Choix du thème</option>
                        <?php $themes = select_themes("choix_theme",$pdo); ?>
                        <?php foreach($themes as $theme): ?>
                        <option value="<?= $theme->id ?>"><?= $theme->nom ?></option>
                        <?php endforeach ?>
                      </select>
                </div>

                <!-- REPONSE 1 -->
                <div class="form-group">
                    <input class="form-control" id="ajout_question" name="ajout_question" placeholder="Contenu de la question">
                </div>
                <label for="ajout_reponse"><h4>Réponses associées</h4></label>
                <small class="text-muted">Cochez la/les bonne(s) réponse(s)</small>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <input type="checkbox" name="rep1" value="1">
                      </div>
                    </div>
                    <input class="form-control" id="ajout_reponse" name="ajout_reponse[]" placeholder="Reponse 1">
                  </div>
                </div>
                
                <!-- REPONSE 2 -->
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <input type="checkbox" name="rep2" value="1">
                      </div>
                    </div>
                    <input class="form-control" id="ajout_reponse" name="ajout_reponse[]" placeholder="Reponse 2">
                  </div>
                </div>
                
                <!-- REPONSE 3 -->
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <input type="checkbox" name="rep3" value="1">
                      </div>
                    </div>
                    <input class="form-control" id="ajout_reponse" name="ajout_reponse[]" placeholder="Reponse 3">
                  </div>
                </div>
                <button type="submit" name="form_ajout_question" class="btn btn-primary">Ajouter</button>
            </form>

            <hr class="my-4">

            <!-- FORMULAIRE AJOUT REPONSE -->
            <form action="" method="POST">
                <div class="form-group">
                    <label for="choix_question"><h4>Ajouter une réponse</h4></label>
                    <select class="form-control" name="choix_question" id="choix_question">
                        <option selected disabled>Choix de la question</option>
                        <?php $themes = select_themes(); ?>
                        <?php foreach($themes as $theme): ?>
                            <option disabled value="<?=$theme->id?>"><strong><?= $theme->nom ?></strong></option>
                            <?php $questions = select_questions($theme->id); ?>
                            <?php foreach ($questions as $question): ?>
                            <option value="<?= $question->id ?>"><?= $question->contenu ?></option>
                            <?php endforeach ?>
                        <?php endforeach ?>
                      </select>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <input type="checkbox" name="bonne_rep_supp" value="1">
                      </div>
                    </div>
                    <input type="text" class="form-control" id="ajout_rep_supp" name="ajout_rep_supp" placeholder="Contenu de la réponse" aria-describedby="help">
                  </div>
                  <small class="form-text text-muted" id="help">
                      Cochez la case s'il s'agit d'une bonne réponse
                  </small>
                </div>
                <button type="submit" name="form_ajout_rep_supp" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="card col p-0 m-2">
      <div class="card-header text-center text-white" style="background-color:#218ed6">
        <h3>Consultation/modification</h3>
      </div>
      <div class="card-body">
        Statut: <?= $_SESSION["statut"] ?>
      </div>
    </div>
  </div>
</div>
<?php
require "elements/footer.php";
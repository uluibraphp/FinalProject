<?php
session_start();

require_once 'validations-m.php';

require_login();

//connect to db
require_once 'databaseone.php';
$conn = db_connect();
?>

<?php
$title_tag="Edit Manager";
include_once 'shared/topone.php';


if ($_SERVER['REQUEST_METHOD'] =='POST'){

    $manager_id =trim(filter_var($_POST['manager_id'], FILTER_SANITIZE_NUMBER_INT));
    $year = trim(filter_var($_POST['year'], FILTER_SANITIZE_NUMBER_INT));
    $manager_name =trim(filter_var($_POST['manager_name'], FILTER_SANITIZE_STRING));
    $team = trim(filter_Var($_POST ['team'], FILTER_SANITIZE_STRING));
    



    $sql = "UPDATE manager SET manager_id=:manager_id, ";
    $sql .="year=:year,manager_name=:manager_name, team=:team ";
    $sql .= "WHERE manager_id=:id";

    $cmd = $conn->prepare($sql);
    $cmd -> bindParam(':manager_id', $manager_id, PDO::PARAM_STR, 50);
    $cmd -> bindParam(':year', $year, PDO::PARAM_INT,);
    $cmd -> bindParam(':manager_name', $manager_name, PDO::PARAM_STR, 50);
    $cmd -> bindParam(':team', $team, PDO::PARAM_STR, 100);
    $cmd -> bindParam(':id', $manager_id, PDO::PARAM_STR, 100);
    


$cmd -> execute();

header("Location: managers.php");

} else if ($_SERVER['REQUEST_METHOD'] == 'GET'){
    $id = filter_var ($_GET['manager_id'], FILTER_SANITIZE_NUMBER_INT);
   
    $sql = "SELECT * FROM manager WHERE manager_id=" . $id;
    $manager = db_queryOne($sql, $conn);

    $manager_id = $manager ['manager_id'];
    $year = $manager['year'];
    $manager_name= $manager['manager_name'];
    $team = $manager['team'];
}


?>



    <div class="container">
    <h1 class="text-center mt-5">Edit Career <i class="bi bi-person-bounding-box"></i></h1>


    <div class="row mt-5 ms-1 ">
            <form class="row justify-content-center mb-5"  method="POST" novalidate ENCTYPE="multipart/form-data">

                <div class="col-12 col-md-6">
                <div class="row mb-4 ">             
                    <label class="col-2 col-form-label fs-4" for="manager_id">Manager ID</label>
                    <div class="col-10">
                    <input inputmode="numeric" pattern="[0-9]{}" class="<?= (isset($errors['title']) ? 'is-invalid ' : '');?>form-control form-control-lg" type="text" name="manager_id" value="<?= $manager_id ?? ''; ?>">
                    <p class="text-danger"> <?= $errors['manager_id'] ?? ''; ?></p>
                    </div>
                </div>

                <div class="row mb-4">             
                    <label class="col-2 col-form-label fs-4" for="year">Year</label>
                    <div class="col-10">
                    <input pattern="/(?<=\D|^)(?<year>\d{4})(?<sep>[^\w\s])(?<month>1[0-2]|0[1-9])\k<sep>(?<day>0[1-9]|[12][0-9]|(?<=11\k<sep>|[^1][4-9]\k<sep>)30|(?<=1[02]\k<sep>|[^1][13578]\k<sep>)3[01])(?=\D|$)/gm"
                    class="<?= (isset($errors['year']) ? 'is-invalid ' : '');?>form-control form-control-lg" type="text" name="year" value="<?= $year ?? ''; ?>">
                    <p class="text-danger"> <?= $errors['year'] ?? ''; ?></p>
                    </div>
                </div>


                <div class="row mb-4">             
                    <label class="col-2 col-form-label fs-4" for="manager_name">Manager Name</label>
                    <div class="col-10">
                    <input required class="<?= (isset($errors['manager_name']) ? 'is-invalid ' : '');?>form-control form-control-lg" type="text" name="manager_name" value="<?= $manager_name ?? ''; ?>">
                    <p class="text-danger"> <?= $errors['manager_name'] ?? ''; ?></p>
                    </div>
                </div>


                <div class="row mb-4 test">             
                    <label class="col-2 col-form-label fs-5" for="team">Team</label>
                    <div class="col-10">
                <select name="team" id="" class="form-select form-select-lg">
                <?php 
                    $sql = "SELECT team FROM teams ORDER BY team";
                    $teams = db_queryAll($sql, $conn);
                    
                    foreach ($teams as $team) {
                        echo "<option value=". $team["team"] . ">" . ucfirst($team["team"]) . "</option>";

                    
                    }
                    
                ?>
                </select>
                    </div>
                </div>
                </div>

                <div class="col-12 col-sm-3 mb-5">
            <div class="card">
               <img id="cover" src="https://dummyimage.com/300x225" class="card-img-top" alt="game cover">
               <div class="card-body">
                  <input type="file" id="choosefile" name="pic" class="form-control">
               </div>
               <p class="px-3 pb-2 text-danger"><?= $errors['pic'] ?? ''; ?></p>
            </div>
         </div>

     

                <div class="col-12 d-grid">
                <div class="row">
                <button class="btn btn-primary btn-lg">Update Career</button>
                </div>
                </div>
                </div>  
            </form>
        </div>

        <script>

                function handleFileSelect(evt)
                {
                const reader = new FileReader();

                reader.addEventListener('load', (e) =>
                {
                    cover.src = e.target.result;
                    console.log(e.target.result);
                })

                reader.readAsDataURL(evt.target.files[0]);
                }

                choosefile.addEventListener('change', handleFileSelect);

        </script>
        <?php
include_once 'shared/footerone.php'
   ?>
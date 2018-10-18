<?php
function sendQuery($q, $url)
{
  $data = array("query" => $q );
  $data_string = json_encode($data);
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
  curl_setopt($ch, CURLOPT_POST, 1);

  $headers = array();
  $headers[] = "Content-Type: application/json";
  $headers[] = "Accept: application/json";
  $headers[] = "X-App-Id: 61897967";
  $headers[] = "X-App-Key: 27e4fc20cb3ca15842eec783ebe3faf0";
  $headers[] = "X-Remote-User-Id: 0";
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result = curl_exec($ch);
  if (curl_errno($ch)) {
      echo 'Error:' . curl_error($ch);
  }
  curl_close ($ch);
  $arr =json_decode($result,TRUE);
  return $arr;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['calories']) && $_POST['calories'] != '' && !empty($_POST['calories']) )
{
  $url = "https://trackapi.nutritionix.com/v2/natural/nutrients";
  $q = trim($_POST['calories']);
  $result = sendQuery($q, $url);
  $foods = $result['foods'] ;
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['exercise']) && $_POST['exercise'] != '' && !empty($_POST['exercise']) )
{
  $url = "https://trackapi.nutritionix.com/v2/natural/exercise";
  $q = trim($_POST['exercise']);
  $result = sendQuery($q, $url);
  $exercise = $result['exercises'] ;
}
else
{
  unset($_POST);
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Calories Tracker</title>
  </head>
  <body>
    <header>
      <div class="collapse bg-dark" id="navbarHeader">

      </div>
      <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
          <a href="#" class="navbar-brand d-flex align-items-center">

            <strong>Calories Tracker</strong>
          </a>
        </div>
      </div>
    </header>
    <main role="main">
      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Calories Tracker</h1>
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item ">
              <a class="nav-link active" href="#tab1"  data-toggle="tab" aria-controls="tab1" aria-selected="true">Calories Consumed</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#tab2"  data-toggle="tab" aria-controls="tab2" aria-selected="false">Calories Burnt</a>
            </li>
          </ul>
          <div class="tab-content">
            <div id="tab1" class="tab-pane fade show active" role="tabpanel" aria-labelledby="tab1-tab">
              <form method="post" action="">
                <div class="form-group">
                  <label for="" class="mb-3 mt-3">My Today's Diet</label>
                  <textarea name="calories" rows="3" cols="80" class="form-control" placeholder="for breakfast I ate 2 eggs, and french toast"></textarea>
                  <small id="emailHelp" class="form-text text-muted"></small>
                </div>
                <button type="submit" class="btn btn-primary">Calculate Calories</button>
              </form>
            </div>
            <div id="tab2" class="tab-pane fade " role="tabpanel" aria-labelledby="tab2-tab">
              <form method="post" action="">
                <div class="form-group">
                  <label for="" class="mb-3 mt-3">Calories Burnt</label>
                  <textarea name="exercise" rows="3" cols="80" class="form-control" placeholder="Walked 2 kms today"></textarea>
                  <small id="emailHelp" class="form-text text-muted"></small>
                </div>
                <button type="submit" class="btn btn-primary">Calculate Calories Burnt</button>
              </form>
            </div>
          </div>

        </div>
      </section>
      <?php if(isset($foods)): ?>
      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
            <?php
            $total=0;
            foreach ($foods as $food){
              $total = $food['nf_calories'] + $total;
            }
            ?>
            <div class="col-md-12">
              <div class="alert alert-primary">
                <h4> You Consumed  <?=$total ?> Calories</h4>
              </div>
            </div>
          <?php $total=0; foreach ($foods as $food) : ?>
            <div class="col-md-12">
              <div class="card mb-4 box-shadow">
                <div class="media">
                  <img class="mr-3" src="<?php echo $food['photo']['thumb']; ?>" >
                  <div class="media-body">
                    <h5 class="mt-0 font-weight-bold text-uppercase"><?php echo $food['food_name']; ?></h5>
                    <p> Calories: <?php echo $food['nf_calories']; ?> </p>
                    <p> Grams: <?php echo $food['serving_weight_grams']; ?>  </p>
                    <p> Fats: <?php echo $food['nf_total_fat']; ?>  </p>
                    <p> Saturated Fats: <?php echo $food['nf_saturated_fat']; ?>  </p>
                    <p> Cholestrol: <?php echo $food['nf_cholesterol']; ?>  </p>
                    <p> Carbohydrate: <?php echo $food['nf_total_carbohydrate']; ?>  </p>
                    <p> Dietary Fiber: <?php echo $food['nf_dietary_fiber']; ?>  </p>
                    <p> Sugar : <?php echo $food['nf_sugars']; ?>  </p>
                    <p> Protein : <?php echo $food['nf_protein']; ?>  </p>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>

          </div>
        </div>
      </div>
      <?php endif; ?>
      <?php if(isset($exercise)): ?>
      <div class="album py-5 bg-light">
        <div class="container">
          <div class="row">
            <?php
            $total=0;
            foreach ($exercise as $data){
              $total = $data['nf_calories'] + $total;
            }
            ?>
            <div class="col-md-12">
              <div class="alert alert-primary">
                <h4> You Burnt  <?=$total ?> Calories</h4>
              </div>
            </div>
          <?php foreach ($exercise as $data) : ?>
              <div class="col-md-12">
                <div class="table-responsive-md">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Exercise</th>
                        <th scope="col">MET</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Calories Burnt</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td> <img src="<?php echo $data['photo']['thumb']; ?>" width="100">  <br> <?php echo $data['user_input']; ?></td>
                        <td><?php echo $data['met']; ?></td>
                        <td><?php echo $data['duration_min']; ?></td>
                        <td><?php echo $data['nf_calories']; ?></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
        </div>
          <?php endforeach; ?>

        </div>
      </div>
    </div>
      <?php endif; ?>
    </main>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>

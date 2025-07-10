<?php
// session_start();

// Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// Include DB config
require 'config/config.php';

$data = [];
$keywords = '';
$location = '';

if (isset($_POST['search'])) {
    $keywords = isset($_POST['keywords']) ? htmlspecialchars(trim($_POST['keywords'])) : '';
    $location = isset($_POST['location']) ? htmlspecialchars(trim($_POST['location'])) : '';

    // Keywords-based search
    $keywordArr = array_filter(array_map('trim', explode(',', $keywords)));
    $locationArr = array_filter(array_map('trim', explode(',', $location)));
}

  
  // Define image gallery data
  $featured_properties = [
    [
        'src' => 'assets/img/img1.jpg',
        'title' => 'Luxury Apartment',
        'description' => 'Modern 3-bedroom apartment with premium amenities',
        'price' => '$1,200/month',
        'location' => 'Downtown Area'
    ],
    [
        'src' => 'assets/img/img2.jpg',
        'title' => 'Cozy Studio',
        'description' => 'Perfect studio apartment for students and professionals',
        'price' => '$800/month',
        'location' => 'University District'
    ],
    [
        'src' => 'assets/img/img3.jpg',
        'title' => 'Family Villa',
        'description' => 'Spacious 4-bedroom villa with garden and parking',
        'price' => '$2,500/month',
        'location' => 'Suburban Area'
    ],
    [
        'src' => 'assets/img/img4.jpg',
        'title' => 'Modern Townhouse',
        'description' => '2-bedroom townhouse with contemporary design',
        'price' => '$1,800/month',
        'location' => 'City Center'
    ]
  ];
  
  if(isset($_POST['search'])) {
    // Get data from FORM
    $keywords = $_POST['keywords'];
    $location = $_POST['location'];

    //keywords based search
    $keyword = explode(',', $keywords);
    $concats = "(";
    $numItems = count($keyword);
    $i = 0;
    foreach ($keyword as $key => $value) {
      # code...
      if(++$i === $numItems){
         $concats .= "'".$value."'";
      }else{
        $concats .= "'".$value."',";
      }
    }
    $concats .= ")";
  //end of keywords based search
  
  //location based search
    $locations = explode(',', $location);
    $loc = "(";
    $numItems = count($locations);
    $i = 0;
    foreach ($locations as $key => $value) {
      # code...
      if(++$i === $numItems){
         $loc .= "'".$value."'";
      }else{
        $loc .= "'".$value."',";
      }
    }
    $loc .= ")";

  //end of location based search
    
    try {
      //foreach ($keyword as $key => $value) {
        # code...

        $stmt = $connect->prepare("SELECT * FROM room_rental_registrations_apartment WHERE country IN $concats OR country IN $loc OR state IN $concats OR state IN $loc OR city IN $concats OR city IN $loc OR address IN $concats OR address IN $loc OR rooms IN $concats OR landmark IN $concats OR landmark IN $loc OR rent IN $concats OR deposit IN $concats");
        $stmt->execute();
        $data2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $connect->prepare("SELECT * FROM room_rental_registrations WHERE country IN $concats OR country IN $loc OR state IN $concats OR state IN $loc OR city IN $concats OR city IN $loc OR rooms IN $concats OR address IN $concats OR address IN $loc OR landmark IN $concats OR rent IN $concats OR deposit IN $concats");
        $stmt->execute();
        $data8 = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = array_merge($data2, $data8);

    }catch(PDOException $e) {
      $errMsg = $e->getMessage();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>App</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="assets/css/rent.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
  </head>

  <body id="page-top">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
      <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Prime Rentals</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
           
            <li class="nav-item">
              <a class="nav-link js-scroll-trigger" href="#search">Search</a>
            </li>
            
            <?php 
              if(empty($_SESSION['username'])){
                echo '<li class="nav-item">';
                  echo '<a class="nav-link" href="./auth/login.php">Login</a>';
                echo '</li>';
              }else{
                echo '<li class="nav-item">';
                 echo '<a class="nav-link" href="./auth/dashboard.php">Home</a>';
               echo '</li>';
              }
            ?>
            

            <li class="nav-item">
              <a class="nav-link" href="./auth/register.php">Register</a>
            </li>

          </ul>
        </div>
      </div>
    </nav>

    <!-- Header -->
    <header class="masthead">
      <div class="container">
        <div class="intro-text">
          <div class="intro-lead-in">Welcome To Room Rental Registration!</div>
          <div class="intro-heading text-uppercase">It's Nice To See You<br></div>
        </div>
      </div>
    </header>

     <!-- Search -->
    <section id="search">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="section-heading text-uppercase">Search</h2>
            <h3 class="section-subheading text-muted">Search rooms or homes for hire.</h3>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form action="" method="POST" class="center" novalidate>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input class="form-control" id="keywords" name="keywords" type="text" placeholder="Key words(Ex: 1bhk,rent..)" required data-validation-required-message="Please enter keywords">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <input class="form-control" id="location" type="text" name="location" placeholder="Location" required data-validation-required-message="Please enter location.">
                    <p class="help-block text-danger"></p>
                  </div>
                </div>         

                <div class="col-md-2">
                  <div class="form-group">
                    <button id="" class="btn btn-success btn-md text-uppercase" name="search" value="search" type="submit">Search</button>
                  </div>
                </div>
              </div>
            </form>

            <?php
              if(isset($errMsg)){
                echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
              }
              if(count($data) !== 0){
                echo "<h2 class='text-center'>List of Apartment Details</h2>";
              }else{
                //echo "<h2 class='text-center' style='color:red;'>Try Some other keywords</h2>";
              }
            ?>        
            <?php 
                foreach ($data as $key => $value) {           
                  echo '<div class="card card-inverse card-info mb-3" style="padding:1%;">          
                        <div class="card-block">';
                          // echo '<a class="btn btn-warning float-right" href="update.php?id='.$value['id'].'&act=';if(isset($value['ap_number_of_plats'])){ echo "ap"; }else{ echo "indi"; } echo '">Edit</a>';
                         echo   '<div class="row">
                            <div class="col-4">
                            <h4 class="text-center">Owner Details</h4>';
                              echo '<p><b>Owner Name: </b>'.$value['fullname'].'</p>';
                              echo '<p><b>Mobile Number: </b>'.$value['mobile'].'</p>';
                              echo '<p><b>Alternate Number: </b>'.$value['alternat_mobile'].'</p>';
                              echo '<p><b>Email: </b>'.$value['email'].'</p>';
                              echo '<p><b>Country: </b>'.$value['country'].'</p><p><b> State: </b>'.$value['state'].'</p><p><b> City: </b>'.$value['city'].'</p>';
                              if ($value['image'] !== 'uploads/') {
                                # code...
                                echo '<img src="app/'.$value['image'].'" width="100">';
                              }

                          echo '</div>
                            <div class="col-5">
                            <h4 class="text-center">Room Details</h4>';
                              // echo '<p><b>Country: </b>'.$value['country'].'<b> State: </b>'.$value['state'].'<b> City: </b>'.$value['city'].'</p>';
                              echo '<p><b>Plot Number: </b>'.$value['plot_number'].'</p>';

                              if(isset($value['sale'])){
                                echo '<p><b>Sale: </b>'.$value['sale'].'</p>';
                              } 
                              
                                if(isset($value['apartment_name']))                         
                                  echo '<div class="alert alert-success" role="alert"><p><b>Apartment Name: </b>'.$value['apartment_name'].'</p></div>';

                                if(isset($value['ap_number_of_plats']))
                                  echo '<div class="alert alert-success" role="alert"><p><b>Plat Number: </b>'.$value['ap_number_of_plats'].'</p></div>';

                              echo '<p><b>Available Rooms: </b>'.$value['rooms'].'</p>';
                              echo '<p><b>Address: </b>'.$value['address'].'</p><p><b> Landmark: </b>'.$value['landmark'].'</p>';
                          echo '</div>
                            <div class="col-3">
                            <h4>Other Details</h4>';
                            echo '<p><b>Accommodation: </b>'.$value['accommodation'].'</p>';
                            echo '<p><b>Description: </b>'.$value['description'].'</p>';
                              if($value['vacant'] == 0){ 
                                echo '<div class="alert alert-danger" role="alert"><p><b>Occupied</b></p></div>';
                              }else{
                                echo '<div class="alert alert-success" role="alert"><p><b>Vacant</b></p></div>';
                              } 
                            echo '</div>
                          </div>              
                         </div>
                      </div>';
                }
              ?>              
          </div>
        </div>
      </div>
      <br><br><br><br><br><br>
    </section>

    <!-- Featured Properties Gallery Section -->
    <section id="featured-properties" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0;">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 style="color: white; font-size: 2.5rem; margin-bottom: 20px; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
              Featured Properties
            </h2>
            <h3 style="color: rgba(255,255,255,0.9); font-size: 1.2rem; margin-bottom: 50px;">
              Discover our handpicked selection of premium rental properties
            </h3>
          </div>
        </div>
        
        <!-- Image Gallery Container -->
        <div style="
          display: flex; 
          justify-content: space-around; 
          flex-wrap: wrap; 
          gap: 25px; 
          max-width: 1400px; 
          margin: 0 auto;
        ">
          
          <?php foreach($featured_properties as $index => $property): ?>
            <div style="
              flex: 1; 
              min-width: 280px; 
              max-width: 320px; 
              background: white; 
              border-radius: 20px; 
              box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
              overflow: hidden; 
              transition: all 0.4s ease;
              margin-bottom: 30px;
              position: relative;
              cursor: pointer;
            " 
            onmouseover="
              this.style.transform='translateY(-10px) scale(1.02)'; 
              this.style.boxShadow='0 25px 50px rgba(0,0,0,0.2)';
            " 
            onmouseout="
              this.style.transform='translateY(0) scale(1)'; 
              this.style.boxShadow='0 15px 35px rgba(0,0,0,0.1)';
            ">
              
              <!-- Property Image -->
              <div style="position: relative; overflow: hidden;">
                <img src="<?php echo htmlspecialchars($property['src']); ?>" 
                     alt="<?php echo htmlspecialchars($property['title']); ?>"
                     style="
                        width: 100%; 
                        height: 220px; 
                        object-fit: cover; 
                        transition: transform 0.4s ease;
                     "
                     onmouseover="this.style.transform='scale(1.1)'"
                     onmouseout="this.style.transform='scale(1)'">
                
                <!-- Price Badge -->
                <div style="
                  position: absolute;
                  top: 15px;
                  right: 15px;
                  background: linear-gradient(45deg, #ff6b6b, #ee5a52);
                  color: white;
                  padding: 8px 15px;
                  border-radius: 25px;
                  font-weight: bold;
                  font-size: 14px;
                  box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
                ">
                  <?php echo htmlspecialchars($property['price']); ?>
                </div>
              </div>
              
              <!-- Property Details -->
              <div style="padding: 25px;">
                <h3 style="
                  margin: 0 0 10px 0; 
                  color: #2c3e50; 
                  font-size: 20px; 
                  font-weight: bold;
                  line-height: 1.3;
                ">
                  <?php echo htmlspecialchars($property['title']); ?>
                </h3>
                
                <div style="
                  display: flex;
                  align-items: center;
                  margin-bottom: 15px;
                  color: #7f8c8d;
                  font-size: 14px;
                ">
                  <i class="fa fa-map-marker" style="margin-right: 8px; color: #e74c3c;"></i>
                  <?php echo htmlspecialchars($property['location']); ?>
                </div>
                
                <p style="
                  margin: 0 0 20px 0; 
                  color: #666; 
                  font-size: 14px; 
                  line-height: 1.5;
                  height: 42px;
                  overflow: hidden;
                ">
                  <?php echo htmlspecialchars($property['description']); ?>
                </p>
                
                <!-- Action Buttons -->
                <div style="display: flex; gap: 10px;">
                  <button style="
                    flex: 1;
                    padding: 12px 20px; 
                    background: linear-gradient(45deg, #667eea, #764ba2); 
                    color: white; 
                    border: none; 
                    border-radius: 25px; 
                    cursor: pointer; 
                    font-size: 14px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
                  " 
                  onmouseover="
                    this.style.background='linear-gradient(45deg, #5a67d8, #6b46c1)'; 
                    this.style.transform='translateY(-2px)';
                    this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.6)';
                  " 
                  onmouseout="
                    this.style.background='linear-gradient(45deg, #667eea, #764ba2)';
                    this.style.transform='translateY(0)';
                    this.style.boxShadow='0 4px 15px rgba(102, 126, 234, 0.4)';
                  ">
                    <i class="fa fa-eye" style="margin-right: 8px;"></i>
                    View Details
                  </button>
                  
                  <button style="
                    padding: 12px 15px; 
                    background: white; 
                    color: #667eea; 
                    border: 2px solid #667eea; 
                    border-radius: 25px; 
                    cursor: pointer; 
                    font-size: 14px;
                    transition: all 0.3s ease;
                  " 
                  onmouseover="
                    this.style.background='#667eea'; 
                    this.style.color='white';
                    this.style.transform='translateY(-2px)';
                  " 
                  onmouseout="
                    this.style.background='white'; 
                    this.style.color='#667eea';
                    this.style.transform='translateY(0)';
                  ">
                    <i class="fa fa-heart"></i>
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          
        </div>
      </div>
    </section>

   <video 
  autoplay 
  muted 
  loop 
  playsinline 
  style="width: 100vw; height: 50%; display: block; box-shadow: 10px 8px 20px rgba(0, 0, 0, 0.4); object-fit: cover;">
  <source src="assets/img/house.mp4" type="video/mp4">
</video>

<div class="rental-section" style="
    max-width: 800px;
    margin: 40px auto;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    animation: fadeInUp 0.8s ease-out;
"> 
    
    <!-- Decorative background elements -->
    <div style="
        position: absolute;
        top: -50px;
        right: -50px;
        width: 100px;
        height: 100px;
        background: linear-gradient(45deg, #ff6b6b, #feca57);
        border-radius: 50%;
        opacity: 0.1;
        animation: float 6s ease-in-out infinite;
    "></div>
    
    <div style="
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, #48cae4, #023e8a);
        border-radius: 50%;
        opacity: 0.1;
        animation: float 8s ease-in-out infinite reverse;
    "></div>

    <h2 style="
        font-size: 2.5rem;
        color: #2c3e50;
        text-align: center;
        margin-bottom: 20px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        position: relative;
        animation: slideInDown 0.6s ease-out;
    ">
        <i class="fas fa-home" style="margin-right: 15px; color: #667eea;"></i>
        Find Your Perfect Rental Home
    </h2> 
    
    <p style="
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: center;
        margin-bottom: 30px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        animation: fadeIn 0.8s ease-out 0.2s both;
    ">
        Looking for a comfortable and affordable place to live? We offer a wide range of rental homes to suit every lifestyle and budget. Whether you're searching for a cozy apartment, a spacious family home, or a modern villa, we have the right property for you.
    </p> 
    
    <ul style="
        list-style: none;
        padding: 0;
        margin: 30px 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    "> 
        <li style="
            background: linear-gradient(135deg, #f8f9ff, #e8f0ff);
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #667eea;
            transition: all 0.3s ease;
            animation: slideInLeft 0.6s ease-out 0.3s both;
            cursor: pointer;
        " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 1.5rem; margin-right: 15px;">🏠</span>
            <strong style="color: #2c3e50;">Fully furnished and unfurnished options</strong>
        </li> 
        
        <li style="
            background: linear-gradient(135deg, #fff8f0, #ffe8d6);
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #feca57;
            transition: all 0.3s ease;
            animation: slideInLeft 0.6s ease-out 0.4s both;
            cursor: pointer;
        " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(254, 202, 87, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 1.5rem; margin-right: 15px;">🌐</span>
            <strong style="color: #2c3e50;">Prime locations in peaceful neighborhoods</strong>
        </li> 
        
        <li style="
            background: linear-gradient(135deg, #f0fff8, #d6ffe8);
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #26de81;
            transition: all 0.3s ease;
            animation: slideInLeft 0.6s ease-out 0.5s both;
            cursor: pointer;
        " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(38, 222, 129, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 1.5rem; margin-right: 15px;">🔐</span>
            <strong style="color: #2c3e50;">Secure, well-maintained, and ready to move in</strong>
        </li> 
        
        <li style="
            background: linear-gradient(135deg, #fff0f8, #ffd6e8);
            padding: 20px;
            border-radius: 15px;
            border-left: 5px solid #ff6b6b;
            transition: all 0.3s ease;
            animation: slideInLeft 0.6s ease-out 0.6s both;
            cursor: pointer;
        " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(255, 107, 107, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
            <span style="font-size: 1.5rem; margin-right: 15px;">💡</span>
            <strong style="color: #2c3e50;">Utilities and maintenance services available</strong>
        </li> 
    </ul> 
    
    <p style="
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        text-align: center;
        margin-top: 30px;
        animation: fadeIn 0.8s ease-out 0.7s both;
    ">
    </p> 
</div>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .rental-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    @media (max-width: 768px) {
        .rental-section {
            margin: 20px;
            padding: 30px 20px;
        }
        
        .rental-section h2 {
            font-size: 2rem;
        }
        
        .rental-section ul {
            grid-template-columns: 1fr;
        }

        #featured-properties div[style*="display: flex"] {
            flex-direction: column;
            align-items: center;
        }
        
        #featured-properties div[style*="flex: 1"] {
            min-width: 90%;
            max-width: 350px;
        }
    }
</style>

    
    <!-- Footer -->
    <footer style="background-color: #ccc;">
      <div class="container">
        
        <div class="row">
          <div class="col-md-4">
            <span class="copyright">Copyright &copy; Your Website 2025</span>
          </div>
          <div class="col-md-4">
            <ul class="list-inline social-buttons">
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-linkedin"></i>
                </a>
              </li>
<?php
     include '../includes/autoload.php';
     if(!isset($_SESSION['authenticated']) || $_SESSION['role'] != "VP")
       {
            header('Location: ../index.php');
            exit;
       }
       if($_SESSION['freeze'] == "TRUE")
       {
          // echo "Account Frozen";
           header('Location: accountEdit.php');
       }
     $userID = $_SESSION['userId'];

     $query = "SELECT * FROM user_info 	WHERE userInfoID='$userID'";
     $result = mysqli_query($link, $query)or die ("couldn't execute query.".mysql_error($link));
     $row = mysqli_fetch_assoc($result);
     $pic = $row['picPath'];
?>

<!DOCTYPE html>
<head>
	<title>Title | Fundraising Leaders A to Z</title>
</head>

<body>
<div id="container">
      <?php include 'header.inc.php' ; ?>
      <?php include 'sidenav.php' ; ?>

    <div id="content">

        <h1>Fundraising Leaders - A to Z</h1>
        <h3>Achieving Success for your Goal is our Goal... So Let’s Do It!!!</h3>

      		<h5>Step by Step Checklist to give to the Fundraising Leader</h5>
      		<ol>
      		  <li>Begin by Identifying and Setting up the Fundraising Team Leaders   </li>
      		  <li>Get Student Volunteers – Great College Entrance Reference for them  </li>
      		  <li>Help the Fundraising Leaders Setup their Main Website  </li>
      		  <li>Help Fundraising Leader Setup their Email and PayPal Account  </li>
      		  <li>Help the Fundraising Leader put together and Setup their Students or Members List  </li>
      		  <li>Have the Student Members Personalize their Website and Setup their Supporters List  </li>
      		  <li>Start the Email Campaign Kickoff  </li>
          </ol>

      		<h5>Year Round Opportunity to Generate Funds </h5>
          <ul class="bulletedlist">
      		  <li>Holidays </li>
      		  <li>Apparel Seasons</li>
          </ul>

          <br>

      		<h5>The FundraisingATM Mall Products and Gifts </h5>
          <ul class="bulletedlist">
            <li>Products for every reason and seasons</li>
            <li>Something for everyone</li>
          </ul>

          <br>
      		<h5>The Student Member Potential Prospects </h5>
          <ul class="bulletedlist">
      		  <li>Friends & Family   </li>
      		  <li>Local Businesses   </li>
                  <li>Meeting and Events   </li>
            </ul>

            <br>
                <h5>Fundraising Tools </h5>
                <ul class = "bulletedlist">
                  <li>Video Training   </li>
      		  <li>Tablet or Laptop Presentation   </li>
                  <li>Forms at Events   </li>
                  <li>Contact Cards   </li>
                </ul>
      </div>  <!--end content-->

	<?php include 'footer.php' ; ?>
</div> <!--end container-->

</body>
</html>
<pre>
<?php if ($_POST && $mailSent){
	echo htmlentities($message, ENT_COMPAT, 'UTF-8')."\n";
	echo 'Headers: '.htmlentities($headers, ENT_COMPAT, 'UTF-8');
} ?>
</pre>
<?php
   ob_end_flush();
?>

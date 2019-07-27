<?php 

//Rebuild URL

$url_query_strings_sb = http_build_query(array_merge($_GET,array('sb' => $sb, 'o' => $o)));

if(isset($_GET['p'])){
  $p = intval($_GET['p']);
  $record_from = (($p)-1)*10;
  $record_to =  10;
}else{
  $record_from = 0;
  $record_to = 10;
  $p = 1;
}
  
if(isset($_GET['q'])){
  $q = $_GET['q'];
}else{
  $q = "";
}

if(!empty($_GET['sb'])){
  $sb = $_GET['sb'];
}else{
  $sb = "software_id";
}

if(isset($_GET['o'])){
  if($_GET['o'] == 'ASC'){
    $o = "ASC";
    $disp = "DESC";
  }else{
    $o = "DESC";
    $disp = "ASC";
  }
}else{
  $o = "ASC";
  $disp = "DESC";
}

$sql = mysqli_query($mysqli,"SELECT SQL_CALC_FOUND_ROWS * FROM software 
  WHERE client_id = $client_id 
  AND (software_name LIKE '%$q%' OR software_type LIKE '%$q%' OR software_license LIKE '%$q%') 
  ORDER BY $sb $o LIMIT $record_from, $record_to");

$num_rows = mysqli_fetch_row(mysqli_query($mysqli,"SELECT FOUND_ROWS()"));
$total_found_rows = $num_rows[0];
$total_pages = ceil($total_found_rows / 10);

?>

<div class="card">
  <div class="card-header bg-dark text-white">
    <h6 class="float-left mt-1"><i class="fa fa-rocket mr-2"></i>Software</h6>
    <button class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#addSoftwareModal"><i class="fa fa-plus"></i></button>
  </div>
  <div class="card-body">
    <form autocomplete="off">
      <input type="hidden" name="client_id" value="<?php echo $client_id; ?>">
      <input type="hidden" name="tab" value="<?php echo $_GET['tab']; ?>">
      <div class="input-group">
        <input type="search" class="form-control " name="q" value="<?php if(isset($q)){echo $q;} ?>" placeholder="Search <?php echo ucwords($_GET['tab']); ?>">
        <div class="input-group-append">
          <button class="btn btn-secondary"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </form>
    <hr>
    <div class="table-responsive">
      <table class="table table-striped table-borderless table-hover">
        <thead class="text-dark">
          <tr>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=software_name&o=<?php echo $disp; ?>">Software</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=software_type&o=<?php echo $disp; ?>">Type</a></th>
            <th><a class="text-secondary" href="?<?php echo $url_query_strings_sb; ?>&sb=software_license&o=<?php echo $disp; ?>">License</a></th>
            <th></th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
      
          while($row = mysqli_fetch_array($sql)){
            $software_id = $row['software_id'];
            $software_name = $row['software_name'];
            $software_type = $row['software_type'];
            $software_license = $row['software_license'];

            $sql_login = mysqli_query($mysqli,"SELECT * FROM logins WHERE vendor_id = $vendor_id");
            $row = mysqli_fetch_array($sql_login);
            $login_id = $row['login_id'];
            $login_username = $row['login_username'];
            $login_password = $row['login_password'];
            $software_id_relation = $row['software_id'];

          ?>
          <tr>
            <td><?php echo $software_name; ?></td>
            <td><?php echo $software_type; ?></td>
            <td><?php echo $software_license; ?></td>
            <td>
              <?php
              if($software_id == $software_id_relation){
              ?>  
              <button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#viewPasswordModal<?php echo $login_id; ?>"><i class="fas fa-key"></i></button>

              <div class="modal" id="viewPasswordModal<?php echo $login_id; ?>" tabindex="-1">
                <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"><i class="fa fa-key"></i> Login</h5>
                      <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <h2><?php echo $login_username; ?></h2>
                      <h3><?php echo $login_password; ?></h3>
                    </div>
                  </div>
                </div>
              </div>
              <?php
              }
              ?>
            </td>
            <td>
              <div class="dropdown dropleft text-center">
                <button class="btn btn-secondary btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editSoftwareModal<?php echo $software_id; ?>">Edit</a>
                  <a class="dropdown-item" href="post.php?delete_software=<?php echo $software_id; ?>">Delete</a>
                </div>
              </div>
              <?php include("edit_software_modal.php"); ?>      
            </td>
          </tr>

          <?php
          
          }
          
          ?>

        </tbody>
      </table>

      <?php include("pagination.php"); ?>
      
    </div>
  </div>
</div>

<?php include("add_software_modal.php"); ?>
 <?php

 $title = 'Edit user';
 ob_start();
 ?>

 <h1>Edit user</h1>


 <form method="POST" action="index.php?page=users&action=update&id=<?php echo $user ['id']; ?>">
     <div class="form-group">
         <label for="login">Login</label>
         <input type="text" class="form-control" id="login" name="login" value="<?php echo $user['login'];?>" required>
     </div>

     <div class="form-group">
         <label for="admin">Admin</label>
         <select class="form-control" name="admin" id="admin">
             <option value="0" <?php if(!$user['is_admin']){ echo 'selected';} ?>>No</option>
             <option value="1" <?php if($user['is_admin']){ echo 'selected';} ?>>Yes</option>
         </select>
     </div>
     <button type="submit" class="btn btn-primary">Save</button>
 </form>

 <?php $content = ob_get_clean();

 include 'app/views/layout.php';
 ?>

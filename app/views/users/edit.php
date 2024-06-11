 <?php

 $title = 'Edit user';
 ob_start();
 ?>

 <h1>Edit user</h1>


 <form method="POST" action="index.php?page=users&action=update&id=<?php echo $user['id']; ?>">
     <div class="mb-3">
         <label for="username" class="form-label">Username</label>
         <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
     </div>
     <div class="mb-3">
         <label for="email" class="form-label">Email address</label>
         <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
     </div>
     <div class="mb-3">
         <label for="role" class="form-label">Role</label>
         <select class="form-control" id="role" name="role" required>
             <option value="0" <?php echo $user['role'] == 0 ? 'selected' : ''; ?>>User</option>
             <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Content creator</option>
             <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>Editor</option>
             <option value="3" <?php echo $user['role'] == 3 ? 'selected' : ''; ?>>Admin</option>
         </select>
     </div>
     <button type="submit" class="btn btn-primary">Save changes</button>
 </form>

 <?php $content = ob_get_clean();

 include 'app/views/layout.php';
 ?>

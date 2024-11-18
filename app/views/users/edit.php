 <?php

 $title = 'Edit user';
 ob_start();
 ?>

 <h1>Edit user</h1>

 <form method="POST" action="/users/update/<?php echo htmlspecialchars ($user['id']); ?>">
     <div class="mb-3">
         <label for="username" class="form-label">Username</label>
         <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
     </div>
     <div class="mb-3">
         <label for="email" class="form-label">Email address</label>
         <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
     </div>
     <div class="mb-3">
         <label for="role" class="form-label">Role</label>
         <select class="form-control" id="role" name="role">
             <?php if (!empty($roles) && is_array($roles)): ?>
                 <?php foreach ($roles as $role): ?>
                     <option value="<?php echo htmlspecialchars($role['id']); ?>" <?php echo $user['role'] == $role['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($role['role_name']); ?></option>
                 <?php endforeach; ?>
             <?php else: ?>
                 <option value="">No roles available</option>
             <?php endif; ?>

         </select>
     </div>
     <button type="submit" class="btn btn-primary">Save changes</button>
 </form>

 <?php $content = ob_get_clean();

 include 'app/views/layout.php';
 ?>

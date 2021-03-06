<?php require(APPROOT.'/views/inc/header.php'); ?>
<a href="<?php echo URLROOT; ?>/posts" class="btn btn-dark"><i class="fa fa-backward"></i> Back</a>
<br>
<h1><?php echo $data['post_title']; ?></h1>
<div class="bg-secondary text-white p-2 mb-3">
   Written by <?php echo $data['user_name']; ?> on <?php echo $data['post_created_at']; ?>
</div>
<p><?php echo $data['post_body']; ?></p>
<?php if($data['post_user_id'] == $_SESSION['user_id']): ?>
    <hr>
    <a href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data['post_id']; ?>" class="btn btn-dark">Edit</a>
    
    <form action="<?php echo URLROOT; ?>/posts/delete/<?php echo $data['post_id']; ?>" method="post" class="pull-right">
    <input type="submit" value="Delete" class="btn btn-danger">
    </form>
<?php endif; ?>
<?php require(APPROOT.'/views/inc/footer.php'); ?>
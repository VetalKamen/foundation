<?php
require_once dirname(__DIR__) . '/Controller/CatController.php';

$response = $cat_controller->listCategories();

$categories = \json_decode($response->getBody()->getContents());
?>

<?php include 'templates/header.php'; ?>
    <div class="container flexbox">
        All available categories:
        <?php foreach ($categories as $category): ?>
            <div>
                <h3><?php echo $category->name; ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
<?php include 'templates/footer.php'; ?>
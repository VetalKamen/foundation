<?php
require_once dirname(__DIR__) . '/Controller/CatController.php';

$response = $cat_controller->listCategories();

$categories = \json_decode($response->getBody()->getContents());
?>

<?php include 'templates/header.php'; ?>
    <div class="container flexbox">
        <?php foreach ($categories as $category): ?>
            <div>
                <h3>Name:<?php echo $category->name; ?></h3>
                <h3>Name:<?php echo $categories; ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
<?php include 'templates/footer.php'; ?>
<?php
require_once dirname( __DIR__ ) . '/Controller/CatController.php';

$response = $cat_controller->listBreeds();

$breeds = \json_decode( $response->getBody()->getContents() );
?>

<?php include 'templates/header.php'; ?>
    <div class="container flexbox">
		<?php foreach ( $breeds as $breed ): ?>
            <div>
                <h3>Name:<?php echo $breed->name; ?></h3>
                <br>
                <p>Description: <?php echo $breed->description; ?></p>
                <br>
                <img src="<?php echo $breed->image->url; ?>" alt="picture of a cat"
                     style="height: 300px; width: 400px">
                <br>
                <a href="<?php echo $breed->wikipedia_url ?>">read more about this breed</a>
            </div>
		<?php endforeach; ?>
    </div>
<?php include 'templates/footer.php'; ?>
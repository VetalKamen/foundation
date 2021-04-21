<?php
require_once dirname( __DIR__ ) . '/Controller/CatSearchController.php';

$response   = $cat_search_controller->searchByID();

$response_decoded = \json_decode($response->getBody()->getContents())[0];

$image_path = $response_decoded->url;
?>

<?php include 'templates/header.php'; ?>
    <div class="container flexbox">
        <div>
            <img src="<?php echo $image_path; ?>" alt="picture of a cat" style="height: 300px; width: 400px">
        </div>
        <div>
            <form action="">
                <button type="submit">Generate New Picture</button>
            </form>
        </div>
    </div>
<?php include 'templates/footer.php'; ?>
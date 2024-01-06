
<?php
function Category()
{
    $url_product = 'http://localhost/odoo/backend/odooAuth.php';
    $url_category = 'http://localhost/odoo/backend/api/getCategory.php';
    // Fetch JSON data using file_get_contents


    $jsonDataProducts = file_get_contents($url_product);
    // echo $jsonDataProducts;
    $dataProduct = json_decode($jsonDataProducts, true);

    $jsonDataCategory = file_get_contents($url_category);
    // echo $jsonDataCategory;
    $dataCategory = json_decode($jsonDataCategory, true);
    $CategoryCount = [];
    if ($dataCategory !== null) {
        // Iterate through the JSON data using a foreach loop
        $i=0;
        foreach ($dataCategory as $category) {
            $count = 0;
            $catname = '"' . $category['name'] . '"';
            foreach ($dataProduct as $products) {
                $pcatname = '"' . $products['category_name'] . '"';
                if (preg_match($catname, $pcatname)) {
                    $count++;
                }
                // else
                // {
                //     $count++;
                // }
            }
            // echo $catname;
            // echo $count;
            $categorylist = array(
                'id' => $category['id'],
                'name' => $category['name'],
                'count'=>$count
            );
            array_push($CategoryCount, $categorylist);
            $i++;
        }


        echo json_encode($CategoryCount);
    } else {
        echo "Error decoding JSON data.";
    }
}
Category();

?>

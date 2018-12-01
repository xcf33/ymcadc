<?php
// Due to CORS policy, we can't use AJAX request directly
$category = $_GET['cat'];
$url = 'https://easytoenroll.ymcadc.org/register/easytoenroll/branches/seccategory?ddPrimaryCat=' . $category;
$content = file_get_contents($url);
print $content;

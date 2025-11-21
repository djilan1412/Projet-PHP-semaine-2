<?php
$connexion = mysqli_connect('localhost', 'root', '', 'airbnb');

if(isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $image = $_POST['image'];
    $hote = $_POST['hote'];
    $image_hote = $_POST['image_hote'];
    $prix = $_POST['prix'];
    $ville = $_POST['ville'];
    $note = $_POST['note'];
    
    $requete = "INSERT INTO listings (name, picture_url, host_name, host_thumbnail_url, price, neighbourhood_group_cleansed, review_scores_value) VALUES ('$nom', '$image', '$hote', '$image_hote', '$prix', '$ville', '$note')";
    mysqli_query($connexion, $requete);
    $msg = "Ajouté avec succès";
}

if(isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 1;
}

if(isset($_GET['tri'])) {
    $ordre = $_GET['tri'];
} else {
    $ordre = 'id';
}

if(isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'liste';
}

$nombre_par_page = 10;
$debut = ($page - 1) * $nombre_par_page;


$req_total = mysqli_query($connexion, "SELECT COUNT(*) as nb FROM listings");
$data_total = mysqli_fetch_assoc($req_total);
$total = $data_total['nb'];
$nombre_pages = ceil($total / $nombre_par_page);


$requete_logements = "SELECT * FROM listings ORDER BY $ordre LIMIT $debut, $nombre_par_page";
$resultat = mysqli_query($connexion, $requete_logements);
?>

<html>
<head>
    <title>Site Vacbnb</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="entete">
    <h1>Vacbnb</h1>
    <a href="index.php">Accueil</a>
    <a href="index.php?action=ajouter">Ajouter</a>
</div>

<div class="contenu">

<?php
if($action == 'ajouter') {
?>
    <h2>Ajouter un résultat </h2>
    
    <?php if(isset($msg)) { ?>
        <div class="message"><?php echo $msg; ?></div>
    <?php } ?>
    
    <form method="POST">
        Nom du logement :<br>
        <input type="text" name="nom" required><br>
        
        URL de l'image :<br>
        <input type="text" name="image" required><br>
        
        Nom de l'hôte :<br>
        <input type="text" name="hote" required><br>
        
        URL image de l'hôte :<br>
        <input type="text" name="image_hote" required><br>
        
        Prix :<br>
        <input type="number" name="prix" required><br>
        
        Ville :<br>
        <input type="text" name="ville" required><br>
        
        Note :<br>
        <input type="number" step="0.01" name="note" required><br>
        
        <button type="submit" name="ajouter">Ajouter</button>
    </form>

<?php
} else {

?>

    <div style="margin-bottom: 20px;">
        Trier par : 
        <select onchange="window.location.href='index.php?p=<?php echo $page; ?>&tri='+this.value">
            <option value="id" <?php if($ordre == 'id') { echo 'selected'; } ?>>Par défaut</option>
            <option value="name" <?php if($ordre == 'name') { echo 'selected'; } ?>>Nom</option>
            <option value="neighbourhood_group_cleansed" <?php if($ordre == 'neighbourhood_group_cleansed') { echo 'selected'; } ?>>Ville</option>
            <option value="price" <?php if($ordre == 'price') { echo 'selected'; } ?>>Prix</option>
            <option value="host_name" <?php if($ordre == 'host_name') { echo 'selected'; } ?>>Propriétaire</option>
        </select>
    </div>

    <table>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Ville</th>
            <th>Prix</th>
            <th>Note</th>
            <th>Propriétaire</th>
        </tr>
        
        <?php
        while($ligne = mysqli_fetch_assoc($resultat)) {
        ?>
        <tr>
            <td><img src="<?php echo $ligne['picture_url']; ?>" width="100"></td>
            <td><?php echo $ligne['name']; ?></td>
            <td><?php echo $ligne['neighbourhood_group_cleansed']; ?></td>
            <td><?php echo $ligne['price']; ?> €</td>
            <td><?php echo $ligne['review_scores_value']; ?></td>
            <td><?php echo $ligne['host_name']; ?></td>
        </tr>
        <?php
        }
        ?>
    </table>

    <div class="pagination">
        <?php
        if($page > 1) {
            echo '<a href="index.php?p='.($page-1).'&tri='.$ordre.'">Précédent</a>';
        }
        
        
        for($i = 1; $i <= $nombre_pages; $i++) {
            if($i == $page) {
                echo '<span class="page_actuelle">'.$i.'</span>';
            } else {
                echo '<a href="index.php?p='.$i.'&tri='.$ordre.'">'.$i.'</a>';
            }
        }
        
        if($page < $nombre_pages) {
            echo '<a href="index.php?p='.($page+1).'&tri='.$ordre.'">Suivant</a>';
        }
        ?>
    </div>

<?php
}
?>

</div>

</body>
</html> 
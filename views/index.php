<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Reporter</title>
</head>

<body>
    <h1>Error Reporter</h1>
    <?php
    if (isset($_SESSION['user'])) { ?>
        <a href="/logout.php">Logout</a>
    <?php } else { ?>
        <a href="/login.php">Login</a>
        <a href="/register.php">Register</a>
    <?php } ?>

    <?php
    if (isset($_SESSION['user'])) { ?>
        <form action="/" method="post">
            <label for="head">Head:</label> <br>
            <input type="text" name="head" id="head"> <br>
            <label for="description">Description:</label> <br>
            <textarea name="description" id="description" cols="30" rows="10"></textarea> <br>
            <button type="submit">Submit</button>
        </form>
    <?php } ?>
    <div>
        <?php
        foreach ($props["error-list"] as $error) { ?>
            <div>
                <h2><?= $error['head'] ?></h2>
                <p>is <?= $error['status'] ?></p>
                <p><?= $error['description'] ?></p>
                <p>Reported at: <?= $error['reported_at'] ?></p>
                <p>Reported by: <?= $error['email'] ?></p>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $error['reported_by']) { ?>
                    <a href="/error/patch.php?id=<?= $error["id"] ?>&column=status&value=<?= $error['status'] === 'pending' ? 'resolved' : 'pending' ?>"><?= $error['status'] === 'pending' ? 'Resolve' : 'Pending' ?></a>
                    <a href="/error/delete.php?id=<?= $error['id'] ?>">Delete</a>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if ($props["cursor"] > 0) { ?>
            <a href="/?cursor=<?= $props["cursor"] ?>">Prev</a>
        <?php } ?>
        <?= $props["cursor"] + 1 ?> / <?= ceil($props["total"] / $props["limit"]) ?>
        <?php if ($props["cursor"] * $props["limit"] + $props["limit"] <= $props["total"]) { ?>
            <a href="/?cursor=<?= $props["cursor"] + 2 ?>">Next</a>
        <?php } ?>
    </div>
</body>

</html>
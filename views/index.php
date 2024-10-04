<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Reporter</title>
    <link rel="stylesheet" href="/bootstrap/bootstrap.css">
</head>

<body class="p-4">
    <h1>Error Reporter</h1>
    <?php
    if (isset($_SESSION['user'])) { ?>
        <a href="/logout.php">Logout</a>
    <?php } else { ?>
        <a href="/login.php">Login</a>
        <a href="/register.php">Register</a>
    <?php } ?>

    <div class="d-flex">
        <?php
        if (isset($_SESSION['user'])) { ?>
            <form action="/" method="post" class="flex-grow-1 d-flex flex-column py-4">
                <label for="head">Head:</label> <br>
                <input type="text" name="head" id="head"> <br>
                <label for="description">Description:</label> <br>
                <textarea name="description" id="description" cols="30" rows="10" style="resize: none;"></textarea> <br>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php } ?>
        <div class="flex-grow-1 p-4">
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
            <?php if ($props["total"] == 0) { ?>
                <p>No errors found</p>
            <?php } else { ?>
                <?php if ($props["cursor"] > 0) { ?>
                    <a href="/?cursor=<?= $props["cursor"] ?>">Prev</a>
                <?php } ?>
                <?= $props["cursor"] + 1 ?> / <?= ceil($props["total"] / $props["limit"]) ?>
                <?php if ($props["cursor"] * $props["limit"] + $props["limit"] <= $props["total"]) { ?>
                    <a href="/?cursor=<?= $props["cursor"] + 2 ?>">Next</a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <script src="/bootstrap/bootstrap.js"></script>
</body>

</html>
<table class="table">
    <tr><th>Remove</th><th>Title</th><th>Theater</th><th>DVD</th></tr>
    <?php foreach($movies as $key => $movie): ?>
        <tr><td><i class="icon-trash" id="user-movie-<?php echo $key; ?>"></td>
            <td><?php echo $movie['title']; ?></td>
            <td><?php echo isset($movie['release_dates']['theater']) ? $movie['release_dates']['theater'] : ''; ?></td>
            <td><?php echo isset($movie['release_dates']['dvd']) ? $movie['release_dates']['dvd'] : ''; ?></td>
        </tr>
    <?php endforeach; ?>
</table>
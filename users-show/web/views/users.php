<table class="table">
<?php
    $users = $content;
    foreach ($users as $user) {
        ?>
            <tr>
                <td><?=$user['name']?></td>
                <td><?=$user['surname']?></td>
                <td><?=$user['email']?></td>
                <td><?=$user['login']?></td>
            </tr>

        <?
    }
    ?>
</table>
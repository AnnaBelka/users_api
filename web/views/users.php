<table class="table">
<?php
    $users = $content;
    foreach ($users as $user) {
        ?>
            <tr>
                <td><a href="/users/update/<?=$user['id']?>"><?=$user['name']?></a></td>
                <td><?=$user['surname']?></td>
                <td><?=$user['email']?></td>
                <td><?=$user['login']?></td>
                <td><a href="/users/delete/<?=$user['id']?>">Delete</a> </td>
            </tr>

        <?
    }
    ?>
</table>
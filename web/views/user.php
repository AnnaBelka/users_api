<?php

if (!empty($content['errors'])) {
    $errors = $content['errors'];
}
if (!empty($content['user'])) {
    $user = $content['user'];
}

?>


<h2>Данные пользователя</h2>
<form role="form" method="post">
    <?if ($errors){?>
        <div class="bg-danger">
            <?php

            foreach ($errors as $error) {
                ?>
                <div class="text-danger">
                    <? if ($error == 'error_email'){?>
                        Неккоректный email
                    <?}?>
                </div>
                <div class="text-danger">
                    <? if ($error == 'error_user_exists_email'){?>
                        Пользователь с таким email уже существует
                    <?}?>
                </div>
                <div class="text-danger">
                    <? if ($error == 'error_user_exists_login'){?>
                        Пользователь с таким login уже существует

                    <?}?>
                </div>
                <div class="text-danger">
                    <? if ($error == 'error_password'){?>
                        Пароль должен содержать хотя бы одну цифру, большую и маленькую букву
                    <?}?>
                </div>
                <?
            }
            ?>
        </div>
    <?}?>

        <div class="form-group">
            <label>Имя</label>
            <input class="form-control" type="text" name="name" data-format=".+" data-notice="Введите имя" value="<?=$user['name']?>" maxlength="255" />
        </div>

        <div class="form-group">
            <label>Фамилия</label>
            <input class="form-control" type="text" name="surname" data-format=".+" data-notice="Введите имя" value="<?=$user['surname']?>" maxlength="255" />
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input class="form-control" type="text" name="email" data-format="email" data-notice="Введите email" value="<?=$user['email']?>" maxlength="255" />
        </div>
        <div class="form-group">
            <label>Login</label>
            <input class="form-control" type="text" name="login" data-notice="Введите login" value="<?=$user['login']?>" maxlength="255" />
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input class="form-control" id="password" value="" data-format=".+" data-notice="Введите пароль" name="password" type="password"/>
        </div>

        <input type="submit" name="account_user" class="btn btn-default" value="Сохранить">

</form>
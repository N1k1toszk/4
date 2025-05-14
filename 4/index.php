<?php
// Данные из cookies
$errors = [];
$oldValues = [];
$savedValues = [];

if (isset($_COOKIE['form_errors'])) {
    $errors = json_decode($_COOKIE['form_errors'], true);
    $oldValues = json_decode($_COOKIE['old_values'], true);
}
foreach ($_COOKIE as $name => $value) {
    if (strpos($name, 'saved_') === 0) {
        $field = substr($name, 6);
        $savedValues[$field] = $value;
    }
}
//значение поля
function getFieldValue($field, $default = '') {
    global $oldValues, $savedValues;

    if (isset($oldValues[$field])) {
        return $oldValues[$field];
    }

    if (isset($savedValues[$field])) {
        return $savedValues[$field];
    }

    return $default;
}
//проверка
function isSelected($field, $value) {
    global $oldValues, $savedValues;

    $currentValues = [];
    if (isset($oldValues[$field])) {
        if ($field === 'languages') {
            $currentValues = explode(',', $oldValues[$field]);
        } else {
            return $oldValues[$field] === $value ? 'checked' : '';
        }
    } elseif (isset($savedValues[$field])) {
        if ($field === 'languages') {
            $currentValues = explode(',', $savedValues[$field]);
        } else {
            return $savedValues[$field] === $value ? 'checked' : '';
        }
    }

    return in_array($value, $currentValues) ? 'selected' : '';
}

//чекбокс
function isChecked($field) {
    global $oldValues, $savedValues;

    if (isset($oldValues[$field])) {
        return $oldValues[$field] ? 'checked' : '';
    }

    if (isset($savedValues[$field])) {
        return $savedValues[$field] ? 'checked' : '';
    }

    return '';
}
?>
<!DOCTYPE html>
<html lang="ru-RU">
    <head>
        <meta charset="UTF-8">
    <title>index</title>
    </head>
    <h2>Форма HTML</h2>
    <?php if (isset($_GET['success'])): ?>
                <div class="success-message">Данные успешно сохранены!</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <h3>Обнаружены ошибки:</h3>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
    <form action="vit.php"
        id="form"
        method="POST">
        Ваше ФИО:<br/>
            <input name="name" required  id="name" required
                           value="<?php echo htmlspecialchars(getFieldValue('name')); ?>"
                           class="<?php echo isset($errors['name']) ? 'error-field' : ''; ?>">
                    <?php if (isset($errors['name'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['name']); ?></div>
                    <?php endif; ?>
            <br/><br/>
        Ваш номер телефона:<br/>
            <input type="number" required name="phone" id="phone"required
                           value="<?php echo htmlspecialchars(getFieldValue('phone')); ?>"
                           class="<?php echo isset($errors['phone']) ? 'error-field' : ''; ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['phone']); ?></div>
                    <?php endif; ?>
            <br/><br/>
        Ваша электронная почта:<br/>
            <input name="email" required
            type="email"
            placeholder="example@mail.ru"  id="email" required
                           value="<?php echo htmlspecialchars(getFieldValue('email')); ?>"
                           class="<?php echo isset($errors['email']) ? 'error-field' : ''; ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['email']); ?></div>
                    <?php endif; ?>
            <br/><br/>
        Ваша дата рождения:<br/>
            <input name="birthdate"
            value="1-01-01"
            type="date" name="birthdate" id="birthdate" required
                           value="<?php echo htmlspecialchars(getFieldValue('birthdate')); ?>"
                           class="<?php echo isset($errors['birthdate']) ? 'error-field' : ''; ?>">
                    <?php if (isset($errors['birthdate'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['birthdate']); ?></div>
                    <?php endif; ?>
            </br><br/>
        Ваш пол:<br/>
            <input type="radio" value="male"name="gender" id="male" required
                               <?php echo isSelected('gender', 'male'); ?>
                               class="<?php echo isset($errors['gender']) ? 'error-field' : ''; ?>">Мужской
            <input type="radio" value="female"name="gender" id="female" <?php echo isSelected('gender', 'female'); ?>
                               class="<?php echo isset($errors['gender']) ? 'error-field' : ''; ?>">Женский
            <br/><br/>
            <?php if (isset($errors['gender'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['gender']); ?></div>
                    <?php endif; ?>
        Ваши любимые языки программирования:<br/>
            <select name="languages[]" id="languages"
            multiple="multiple" required class="<?php echo isset($errors['languages']) ? 'error-field' : ''; ?>" size="5">
                        <?php
                        $allLanguages = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python', 'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala'];
                        foreach ($allLanguages as $lang): ?>
                            <option value="<?php echo htmlspecialchars($lang); ?>"
                                <?php echo isSelected('languages', $lang); ?>>
                                <?php echo htmlspecialchars($lang); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['languages'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['languages']); ?></div>
                    <?php endif; ?>
            </select><br/><br/>
        Ваша биография:<br/>
            <textarea name="bio" id="bio" required
                              class="<?php echo isset($errors['bio']) ? 'error-field' : ''; ?>"><?php
                              echo htmlspecialchars(getFieldValue('bio')); ?></textarea>
                    <?php if (isset($errors['bio'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['bio']); ?></div>
                    <?php endif; ?> 
            <br/><br/>
        Чекбокс<br/>
                <input type="checkbox"
                name="agreement" id="agreement" required
                           <?php echo isChecked('agreement'); ?>
                           class="<?php echo isset($errors['agreement']) ? 'error-field' : ''; ?>">
                    С контрактом ознакомлен(а)
                    <?php if (isset($errors['agreement'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($errors['agreement']); ?></div>
                    <?php endif; ?>
            <br/><br/>
        <input type="submit"value="Отправить" name="save" />
    </form>
</html>

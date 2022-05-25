# Reviews

## Установка
Установить, запустить модуль.
Для вывода звездочек подключить файл assets/snippets/reviews/stars.css - верстка для звездочек как в примерах ниже.

## Сниппет ReviewForm
Запускает FormLister c контроллером Reviews.

В форме должны быть поля:
* rid - id документа для которого пишется отзыв;
* name - имя пользователя;
* email - email пользователя;
* review - текст отзыва;
* rate - оценка от 0 до 5.

По умолчанию отзывы публикуются вручную администратором сайта. Это поведение можно изменить с помощью параметра &moderation: 1 - модерация включена (по умолчанию); 0 - модерация выключена.

Пример вызова:
```
[!ReviewForm?
&formid=`review`
&formControls=`rate`
&defaults=`{"rate":1, "rid":[*id*]}`
&formTpl=`reviewFormTpl`
&subject=`Новый отзыв`
&rewriteUrls=`1`
&successTpl=`@CODE:<p>Спасибо! Ваш отзыв будет опубликован после проверки модератором.</p>`
&reportTpl=`@CODE:<p><b>Товар:</b> <a href="[(site_url)][~[+page.id+]~]">[+page.pagetitle+]</a></p><p><b>Отправитель: [+name.value+]</b> (<a href="mailto:[+email.value+]">[+email.value+]</a>)</p><p>[+review.value:nl2br+]</p>`
!]
```

Чанк с формой:
```
[+form.messages+]
<form method="post" class="well">
    <input type="hidden" name="formid" value="review">
    <input type="hidden" name="rid" value="[+rid+]">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone">* Имя</label>
                <input type="text" class="form-control" placeholder="Ваше имя" name="name" value="[+name.value+]">
                [+name.error+]
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">* Email (не публикуется)</label>
                <input type="text" class="form-control" id="email" placeholder="Ваш email" name="email" value="[+email.value+]">
                [+email.error+]
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="message">* Комментарий</label>
                <textarea class="form-control" placeholder="Текст отзыва" name="review" rows="6">[+review.value+]</textarea>
                [+review.error+]
            </div>
        </div>
     </div>
     <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="rate">Оценка</label><br>
                <span class="star-rating">
                    <input type="radio" name="rate" value="1" [+c.rate.1+]><i></i>
                    <input type="radio" name="rate" value="2" [+c.rate.2+]><i></i>
                    <input type="radio" name="rate" value="3" [+c.rate.3+]><i></i>
                    <input type="radio" name="rate" value="4" [+c.rate.4+]><i></i>
                    <input type="radio" name="rate" value="5" [+c.rate.5+]><i></i>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group text-right">
                <button type="submit" class="btn btn-danger">Отправить</button>
            </div>
        </div>
    </div>
</form>
```

## Сниппет ReviewLister
Выводит список отзывов на странице товара c помощью сниппета DocLister.

В шаблоне для вывода доступны плейсхолдеры:
* id - id записи;
* rid - id документа для которого пишется отзыв;
* name - имя пользователя;
* email - email пользователя;
* review - текст отзыва;
* rate - оценка от 0 до 5;
* relrating - оценка в процентах;
* createdon - время создания отзыва;
* updatedon - время редактирования отзыва;
* active - опубликован или нет;
* date - отформатированная дата.

Для форматирования даты необходимо использовать параметры dateSource (поле даты, по умолчанию - createdon) и dateFormat (формат даты  по умолчанию - d.m.y H:i). Если значение dateFormat пустое, то будет выведена дата в формате "день - полное название месяца по-русски - год".

Сниппет устанавливает на странице глобальные плейсхолдеры:
* reviews.total - количество отзывов;
* reviews.rating - общий рейтинг абсолютный;
* reviews.relrating - общий рейтинг относительный в процентах.

Пример:
```
<div><p><span class="star-rating-results"><i style="width:[+reviews.relrating+]%;"></i></span> [+reviews.rating+]/[+reviews.total+]</p></div>

[!ReviewLister?
    &display=`20`
    &paginate=`pages`
    &tpl=`@CODE:
    <table class="table table-striped table-bordered">
    <tbody>
        <tr>
            <td style="width: 50%;"><strong>[+name+]</strong></td>
            <td class="text-right">[+date+]</td>
        </tr>
        <tr>
            <td colspan="2">
                 
                <p><span class="star-rating-results"><i style="width:[+relrating+]%;"></i></span></p>
                <p>[+review:nl2br+]</p>
            </td>
        </tr>
    </tbody>
    </table> 
    <br/>`
    &orderBy=`createdon DESC`
    !]
    [+pages+]
```

## Сниппет RatingLister
Выводит список документов с рейтингом c помощью сниппета DocLister.

В шаблоне для вывода доступны дополнительные плейсхолдеры:
* rating - общий рейтинг абсолютный;
* relrating - общий рейтинг относительный в процентах;
* total - количество отзывов.
* sorter - справедливый рейтинг, это поле нужно использовать для сортировки.

## Ajax
Разбирайтесь сами:
* assets/snippets/reviews/ajax.php - пример обработчика;
* assets/snippets/reviews/forms/review.sample.php - переименовать в review.php
* assets/snippets/reviews/reviews.js - пример скрипта, вызов формлистера на странице не нужен
